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
                    $q->whereRaw('estoque.quantidade_atual <= insumos.estoque_minimo');
                } elseif ($request->nivel === 'ok') {
                    $q->whereRaw('estoque.quantidade_atual > insumos.estoque_minimo');
                }
            });
        }

        // Ordenação
        $sortField = $request->get('sort', 'updated_at');
        $sortDirection = $request->get('direction', 'desc');

        if ($sortField === 'insumo') {
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
              ->whereRaw('estoque.quantidade_atual <= insumos.estoque_minimo');
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

    public function create()
    {
        $insumos = Insumo::where('restaurante_id', $this->restauranteId())
            ->doesntHave('estoque')
            ->orderBy('nome')
            ->pluck('nome', 'id');

        return view('estoque.create', compact('insumos'));
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

        $insumos = Insumo::where('restaurante_id', $this->restauranteId())
            ->orderBy('nome')
            ->pluck('nome', 'id');

        return view('estoque.edit', compact('estoque', 'insumos'));
    }

    public function update(Request $request, Estoque $estoque)
    {
        $this->authorizeEstoque($estoque);

        $restauranteId = $this->restauranteId();

        $data = $request->validate([
            'insumo_id' => [
                'required',
                Rule::exists('insumos', 'id')->where('restaurante_id', $restauranteId),
                Rule::unique('estoque', 'insumo_id')->ignore($estoque->id),
            ],
            'quantidade_atual' => ['required', 'numeric'],
            'localizacao' => ['nullable', 'string', 'max:255'],
        ]);

        $estoque->update($data);

        return redirect()->route('estoque.index')->with('success', 'Estoque atualizado com sucesso.');
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
