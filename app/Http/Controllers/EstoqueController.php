<?php

namespace App\Http\Controllers;

use App\Models\Estoque;
use App\Models\Insumo;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class EstoqueController extends Controller
{
    public function index(Request $request)
    {
        $restauranteId = $this->restauranteId();

        $query = Estoque::with('insumo.restaurante')
            ->whereHas('insumo', fn ($q) => $q->where('restaurante_id', $restauranteId));

        // Filtro de busca
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('insumo', function ($q) use ($search) {
                $q->where('nome', 'like', "%{$search}%");
            })->orWhere('localizacao', 'like', "%{$search}%");
        }

        // Filtro por nível de estoque
        if ($request->filled('nivel')) {
            $query->whereHas('insumo', function ($q) use ($request) {
                if ($request->nivel === 'baixo') {
                    $q->whereRaw('estoque.quantidade_atual <= insumos.ponto_reposicao_minimo');
                } elseif ($request->nivel === 'ok') {
                    $q->whereRaw('estoque.quantidade_atual > insumos.ponto_reposicao_minimo');
                }
            });
        }

        // Ordenação
        $sortField = $request->get('sort', 'updated_at');
        $sortDirection = $request->get('direction', 'desc');

        // Ordenação especial por estoque baixo
        if ($sortField === 'estoque_baixo') {
            $query->join('insumos', 'estoque.insumo_id', '=', 'insumos.id')
                  ->select('estoque.*')
                  ->selectRaw('CASE WHEN estoque.quantidade_atual <= insumos.ponto_reposicao_minimo THEN 1 ELSE 0 END as is_baixo')
                  ->orderBy('is_baixo', 'desc')
                  ->orderBy('insumos.nome', $sortDirection);
        } elseif ($sortField === 'insumo') {
            $query->join('insumos', 'estoque.insumo_id', '=', 'insumos.id')
                  ->orderBy('insumos.nome', $sortDirection)
                  ->select('estoque.*');
        } else {
            $query->orderBy($sortField, $sortDirection);
        }

        $perPage = $request->get('per_page', 15);
        $estoques = $query->paginate($perPage)->withQueryString();

        // Estatísticas
        $totalItens = Estoque::whereHas('insumo', fn ($q) => $q->where('restaurante_id', $restauranteId))->count();

        $estoqueBaixo = Estoque::whereHas('insumo', function ($q) use ($restauranteId) {
            $q->where('restaurante_id', $restauranteId)
              ->whereRaw('estoque.quantidade_atual <= insumos.ponto_reposicao_minimo');
        })->count();

        $valorTotal = Estoque::whereHas('insumo', fn ($q) => $q->where('restaurante_id', $restauranteId))
            ->join('insumos', 'estoque.insumo_id', '=', 'insumos.id')
            ->sum(\DB::raw('estoque.quantidade_atual * insumos.custo_unitario'));

        $localizacoes = Estoque::whereHas('insumo', fn ($q) => $q->where('restaurante_id', $restauranteId))
            ->whereNotNull('localizacao')
            ->distinct('localizacao')
            ->count('localizacao');

        $stats = [
            'total' => $totalItens,
            'estoque_baixo' => $estoqueBaixo,
            'valor_total' => $valorTotal,
            'localizacoes' => $localizacoes,
        ];

        return view('estoque.index', compact('estoques', 'stats'));
    }

    public function create(Request $request)
    {
        $restauranteId = $this->restauranteId();
        $insumos = Insumo::where('restaurante_id', $restauranteId)
            ->doesntHave('estoque')
            ->orderBy('nome')
            ->get();

        // Se insumo_id foi passado na query, pré-selecionar
        $insumoSelecionado = null;
        if ($request->has('insumo_id')) {
            $insumoSelecionado = Insumo::where('restaurante_id', $restauranteId)
                ->where('id', $request->insumo_id)
                ->doesntHave('estoque')
                ->first();
        }

        // Buscar localizações existentes para autocomplete
        $localizacoes = Estoque::whereHas('insumo', fn ($q) => $q->where('restaurante_id', $restauranteId))
            ->whereNotNull('localizacao')
            ->distinct()
            ->orderBy('localizacao')
            ->pluck('localizacao');

        return view('estoque.create', compact('insumos', 'localizacoes', 'insumoSelecionado'));
    }

    public function store(Request $request)
    {
        $restauranteId = $this->restauranteId();

        $data = $request->validate([
            'insumo_id' => [
                'required',
                Rule::exists('insumos', 'id')->where('restaurante_id', $restauranteId),
                Rule::unique('estoque', 'insumo_id'),
            ],
            'quantidade_atual' => ['required', 'numeric'],
            'localizacao' => ['nullable', 'string', 'max:255'],
        ]);

        Estoque::create($data);

        return redirect()->route('estoque.index')->with('success', 'Estoque registrado com sucesso.');
    }

    public function edit(Estoque $estoque)
    {
        $this->authorizeEstoque($estoque);

        $restauranteId = $this->restauranteId();
        $insumos = Insumo::where('restaurante_id', $restauranteId)
            ->orderBy('nome')
            ->get();

        // Buscar localizações existentes para autocomplete
        $localizacoes = Estoque::whereHas('insumo', fn ($q) => $q->where('restaurante_id', $restauranteId))
            ->whereNotNull('localizacao')
            ->distinct()
            ->orderBy('localizacao')
            ->pluck('localizacao');

        // Estatísticas do estoque
        $stats = [
            'valor_total' => ($estoque->quantidade_atual ?? 0) * ($estoque->insumo->custo_unitario ?? 0),
            'percentual_minimo' => $estoque->insumo && $estoque->insumo->ponto_reposicao_minimo 
                ? ($estoque->quantidade_atual / $estoque->insumo->ponto_reposicao_minimo) * 100 
                : 0,
        ];

        return view('estoque.edit', compact('estoque', 'insumos', 'localizacoes', 'stats'));
    }

    public function update(Request $request, Estoque $estoque)
    {
        $this->authorizeEstoque($estoque);

        $restauranteId = $this->restauranteId();

        // Verificar se o estoque ainda existe antes de atualizar
        if (!$estoque->exists) {
            return redirect()->route('estoque.index')
                ->with('error', 'O registro de estoque não foi encontrado.');
        }

        $data = $request->validate([
            'quantidade_atual' => ['required', 'numeric', 'min:0'],
            'localizacao' => ['nullable', 'string', 'max:255'],
        ]);

        // Garantir que quantidade_atual seja um número válido
        // Converter vírgula para ponto se necessário (formato brasileiro)
        $quantidade = $data['quantidade_atual'];
        if (is_string($quantidade)) {
            $quantidade = str_replace(',', '.', $quantidade);
        }
        $quantidade = (float) $quantidade;
        
        // Validar que a quantidade não seja negativa
        if ($quantidade < 0) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['quantidade_atual' => 'A quantidade não pode ser negativa.']);
        }
        
        // Verificar novamente se o estoque ainda existe antes de atualizar
        $estoque->refresh();
        if (!$estoque->exists) {
            return redirect()->route('estoque.index')
                ->with('error', 'O registro de estoque foi removido durante a atualização.');
        }

        // Não incluir insumo_id nos dados de atualização (não pode ser alterado)
        // Atualizar apenas quantidade_atual e localizacao
        try {
            $updated = $estoque->update([
                'quantidade_atual' => $quantidade,
                'localizacao' => $data['localizacao'] ?? null,
            ]);

            if (!$updated) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Não foi possível atualizar o estoque. Tente novamente.');
            }
        } catch (\Illuminate\Database\QueryException $e) {
            // Se houver erro de unique constraint, pode ser que o insumo_id foi alterado para um que já existe
            if (str_contains($e->getMessage(), 'Duplicate entry')) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['insumo_id' => 'Este insumo já possui um registro de estoque.']);
            }
            throw $e;
        }

        // Garantir que a mensagem de sucesso seja exibida corretamente
        return redirect()->route('estoque.index')
            ->with('success', 'Estoque atualizado com sucesso!');
    }

    public function destroy(Estoque $estoque)
    {
        $this->authorizeEstoque($estoque);

        $estoque->delete();

        return redirect()->route('estoque.index')->with('success', 'Registro de estoque removido com sucesso.');
    }

    protected function restauranteId(): int
    {
        return (int) session('restaurante_id');
    }

    protected function authorizeEstoque(Estoque $estoque): void
    {
        abort_unless(optional($estoque->insumo)->restaurante_id === $this->restauranteId(), 403);
    }
}
