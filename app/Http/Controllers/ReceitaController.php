<?php

namespace App\Http\Controllers;

use App\Models\CardapioItem;
use App\Models\Insumo;
use App\Models\Receita;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ReceitaController extends Controller
{
    public function index(Request $request)
    {
        $restauranteId = $this->restauranteId();

        $query = Receita::with(['cardapioItem', 'insumo'])
            ->whereHas('cardapioItem', fn ($q) => $q->where('restaurante_id', $restauranteId));

        // Filtro de busca
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('cardapioItem', fn ($q2) => $q2->where('nome', 'like', "%{$search}%"))
                  ->orWhereHas('insumo', fn ($q2) => $q2->where('nome', 'like', "%{$search}%"));
            });
        }

        // Filtro por essencial
        if ($request->filled('essencial')) {
            $query->where('essencial', $request->essencial === 'sim');
        }

        // Filtro por item do cardápio
        if ($request->filled('cardapio_item')) {
            $query->where('cardapio_item_id', $request->cardapio_item);
        }

        // Ordenação
        $sortField = $request->get('sort', 'cardapio_item_id');
        $query->orderBy($sortField);

        $perPage = $request->get('per_page', 15);
        $receitas = $query->paginate($perPage)->withQueryString();

        // Estatísticas
        $totalReceitas = Receita::whereHas('cardapioItem', fn ($q) => $q->where('restaurante_id', $restauranteId))->count();
        $itensComReceita = Receita::whereHas('cardapioItem', fn ($q) => $q->where('restaurante_id', $restauranteId))
            ->distinct('cardapio_item_id')
            ->count('cardapio_item_id');
        $insumosUsados = Receita::whereHas('cardapioItem', fn ($q) => $q->where('restaurante_id', $restauranteId))
            ->distinct('insumo_id')
            ->count('insumo_id');
        $essenciais = Receita::whereHas('cardapioItem', fn ($q) => $q->where('restaurante_id', $restauranteId))
            ->where('essencial', true)
            ->count();

        $stats = [
            'total' => $totalReceitas,
            'itens_com_receita' => $itensComReceita,
            'insumos_usados' => $insumosUsados,
            'essenciais' => $essenciais,
        ];

        // Lista de itens do cardápio para o filtro
        $cardapioItens = CardapioItem::where('restaurante_id', $restauranteId)
            ->orderBy('nome')
            ->get(['id', 'nome']);

        return view('receitas.index', compact('receitas', 'stats', 'cardapioItens'));
    }

    public function create()
    {
        [$cardapioItens, $insumos] = $this->formOptions();

        return view('receitas.create', compact('cardapioItens', 'insumos'));
    }

    public function store(Request $request)
    {
        $restauranteId = $this->restauranteId();

        $data = $request->validate([
            'cardapio_item_id' => [
                'required',
                Rule::exists('cardapio_itens', 'id')->where('restaurante_id', $restauranteId),
            ],
            'insumo_id' => [
                'required',
                Rule::exists('insumos', 'id')->where('restaurante_id', $restauranteId),
            ],
            'quantidade_necessaria' => ['required', 'numeric'],
            'essencial' => ['nullable', 'boolean'],
        ]);

        $data['essencial'] = $request->boolean('essencial');

        Receita::create($data);

        return redirect()->route('receitas.index')->with('success', 'Receita vinculada com sucesso.');
    }

    public function edit(Receita $receita)
    {
        $this->authorizeReceita($receita);

        [$cardapioItens, $insumos] = $this->formOptions();

        return view('receitas.edit', compact('receita', 'cardapioItens', 'insumos'));
    }

    public function update(Request $request, Receita $receita)
    {
        $this->authorizeReceita($receita);

        $restauranteId = $this->restauranteId();

        $data = $request->validate([
            'cardapio_item_id' => [
                'required',
                Rule::exists('cardapio_itens', 'id')->where('restaurante_id', $restauranteId),
            ],
            'insumo_id' => [
                'required',
                Rule::exists('insumos', 'id')->where('restaurante_id', $restauranteId),
            ],
            'quantidade_necessaria' => ['required', 'numeric'],
            'essencial' => ['nullable', 'boolean'],
        ]);

        $data['essencial'] = $request->boolean('essencial');

        $receita->update($data);

        return redirect()->route('receitas.index')->with('success', 'Receita atualizada com sucesso.');
    }

    public function destroy(Receita $receita)
    {
        $this->authorizeReceita($receita);

        $receita->delete();

        return redirect()->route('receitas.index')->with('success', 'Receita removida com sucesso.');
    }

    protected function authorizeReceita(Receita $receita): void
    {
        abort_unless(optional($receita->cardapioItem)->restaurante_id === $this->restauranteId(), 403);
    }

    protected function formOptions(): array
    {
        $restauranteId = $this->restauranteId();

        $cardapioItens = CardapioItem::where('restaurante_id', $restauranteId)
            ->orderBy('nome')
            ->pluck('nome', 'id');

        $insumos = Insumo::where('restaurante_id', $restauranteId)
            ->orderBy('nome')
            ->pluck('nome', 'id');

        return [$cardapioItens, $insumos];
    }
}
