<?php

namespace App\Http\Controllers;

use App\Models\Insumo;
use Illuminate\Http\Request;

class InsumoController extends Controller
{
    public function index(Request $request)
    {
        $restauranteId = $this->restauranteId();

        $query = Insumo::with(['restaurante', 'estoque'])
            ->where('restaurante_id', $restauranteId);

        // Filtro de busca
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('nome', 'like', "%{$search}%")
                  ->orWhere('descricao', 'like', "%{$search}%")
                  ->orWhere('categoria', 'like', "%{$search}%");
            });
        }

        // Filtro por categoria
        if ($request->filled('categoria')) {
            $query->where('categoria', $request->get('categoria'));
        }

        // Filtro por estoque
        if ($request->filled('estoque')) {
            $estoque = $request->get('estoque');
            if ($estoque === 'baixo') {
                $query->whereHas('estoque', function($q) {
                    $q->whereColumn('quantidade_atual', '<=', 'insumos.ponto_reposicao_minimo');
                });
            } elseif ($estoque === 'ok') {
                $query->whereHas('estoque', function($q) {
                    $q->whereColumn('quantidade_atual', '>', 'insumos.ponto_reposicao_minimo');
                });
            }
        }

        // Ordenação
        $sortBy = $request->get('sort', 'nome');
        $sortOrder = $request->get('order', 'asc');
        
        // Ordenação especial por estoque baixo
        if ($sortBy === 'estoque_baixo') {
            $query->leftJoin('estoque', 'insumos.id', '=', 'estoque.insumo_id')
                  ->select('insumos.*')
                  ->selectRaw('CASE WHEN estoque.quantidade_atual <= insumos.ponto_reposicao_minimo THEN 1 ELSE 0 END as is_baixo')
                  ->orderBy('is_baixo', 'desc')
                  ->orderBy('insumos.nome', $sortOrder);
        } else {
            $query->orderBy($sortBy, $sortOrder);
        }

        $perPage = $request->get('per_page', 15);
        $insumos = $query->paginate($perPage)->withQueryString();

        // Estatísticas
        $stats = [
            'total' => Insumo::where('restaurante_id', $restauranteId)->count(),
            'estoque_baixo' => Insumo::where('restaurante_id', $restauranteId)
                ->whereHas('estoque', function($q) {
                    $q->whereColumn('quantidade_atual', '<=', 'insumos.ponto_reposicao_minimo');
                })->count(),
            'categorias' => Insumo::where('restaurante_id', $restauranteId)
                ->distinct()
                ->count('categoria'),
            'valor_total' => \App\Models\Estoque::whereHas('insumo', function($q) use ($restauranteId) {
                $q->where('restaurante_id', $restauranteId);
            })
            ->join('insumos', 'estoque.insumo_id', '=', 'insumos.id')
            ->selectRaw('COALESCE(SUM(estoque.quantidade_atual * insumos.custo_unitario), 0) as total')
            ->value('total') ?? 0,
        ];

        // Categorias disponíveis
        $categorias = Insumo::where('restaurante_id', $restauranteId)
            ->whereNotNull('categoria')
            ->distinct()
            ->orderBy('categoria')
            ->pluck('categoria');

        return view('insumos.index', compact('insumos', 'stats', 'categorias'));
    }

    public function create()
    {
        // Buscar categorias existentes para autocomplete
        $restauranteId = $this->restauranteId();
        $categorias = Insumo::where('restaurante_id', $restauranteId)
            ->whereNotNull('categoria')
            ->distinct()
            ->orderBy('categoria')
            ->pluck('categoria');
        
        return view('insumos.create', compact('categorias'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nome' => ['required', 'string', 'max:255'],
            'descricao' => ['nullable', 'string', 'max:255'],
            'categoria' => ['nullable', 'string', 'max:100'],
            'unidade_medida' => ['required', 'string', 'max:50'],
            'ponto_reposicao_minimo' => ['nullable', 'numeric'],
            'custo_unitario' => ['nullable', 'numeric'],
            'data_validade_minima' => ['nullable', 'date'],
        ]);

        $data['restaurante_id'] = $this->restauranteId();

        Insumo::create($data);

        return redirect()->route('insumos.index')->with('success', 'Insumo cadastrado com sucesso.');
    }

    public function edit(Insumo $insumo)
    {
        $this->authorizeInsumo($insumo);

        // Buscar categorias existentes para autocomplete
        $restauranteId = $this->restauranteId();
        $categorias = Insumo::where('restaurante_id', $restauranteId)
            ->whereNotNull('categoria')
            ->distinct()
            ->orderBy('categoria')
            ->pluck('categoria');

        // Estatísticas do insumo
        $stats = [
            'estoque_atual' => $insumo->estoque->quantidade_atual ?? 0,
            'valor_total' => ($insumo->estoque->quantidade_atual ?? 0) * ($insumo->custo_unitario ?? 0),
            'receitas_count' => $insumo->receitas()->count(),
            'ultima_atualizacao' => $insumo->estoque->updated_at ?? $insumo->updated_at,
        ];

        return view('insumos.edit', compact('insumo', 'categorias', 'stats'));
    }

    public function update(Request $request, Insumo $insumo)
    {
        $this->authorizeInsumo($insumo);

        $data = $request->validate([
            'nome' => ['required', 'string', 'max:255'],
            'descricao' => ['nullable', 'string', 'max:255'],
            'categoria' => ['nullable', 'string', 'max:100'],
            'unidade_medida' => ['required', 'string', 'max:50'],
            'ponto_reposicao_minimo' => ['nullable', 'numeric'],
            'custo_unitario' => ['nullable', 'numeric'],
            'data_validade_minima' => ['nullable', 'date'],
        ]);

        $insumo->update($data);

        return redirect()->route('insumos.index')->with('success', 'Insumo atualizado com sucesso.');
    }

    public function destroy(Insumo $insumo)
    {
        $this->authorizeInsumo($insumo);

        $insumo->delete();

        return redirect()->route('insumos.index')->with('success', 'Insumo removido com sucesso.');
    }

    protected function restauranteId(): int
    {
        return (int) session('restaurante_id');
    }

    protected function authorizeInsumo(Insumo $insumo): void
    {
        abort_unless($insumo->restaurante_id === $this->restauranteId(), 403);
    }
}
