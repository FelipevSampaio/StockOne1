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

        // Agrupar receitas por item do cardápio para visualização
        $receitasAgrupadas = Receita::with(['cardapioItem', 'insumo'])
            ->whereHas('cardapioItem', fn ($q) => $q->where('restaurante_id', $restauranteId))
            ->get()
            ->groupBy('cardapio_item_id');

        // Calcular custos por item
        $custosPorItem = [];
        foreach ($receitasAgrupadas as $itemId => $receitasItem) {
            $custoTotal = 0;
            foreach ($receitasItem as $receita) {
                if ($receita->insumo && $receita->insumo->custo_unitario) {
                    $custoTotal += $receita->quantidade_necessaria * $receita->insumo->custo_unitario;
                }
            }
            $item = $receitasItem->first()->cardapioItem;
            if ($item) {
                $precoVenda = $item->preco_venda ?? 0;
                $margemLucro = $precoVenda > 0 ? (($precoVenda - $custoTotal) / $precoVenda) * 100 : 0;
                
                $custosPorItem[$itemId] = [
                    'custo_total' => $custoTotal,
                    'preco_venda' => $precoVenda,
                    'margem_lucro' => $margemLucro,
                    'lucro' => $precoVenda - $custoTotal,
                ];
            }
        }

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

        // Calcular custo total e margem média
        $custoTotalGeral = 0;
        $margens = [];
        foreach ($custosPorItem as $custoInfo) {
            $custoTotalGeral += $custoInfo['custo_total'];
            if ($custoInfo['margem_lucro'] > 0) {
                $margens[] = $custoInfo['margem_lucro'];
            }
        }
        $margemMedia = count($margens) > 0 ? array_sum($margens) / count($margens) : 0;

        $stats = [
            'total' => $totalReceitas,
            'itens_com_receita' => $itensComReceita,
            'insumos_usados' => $insumosUsados,
            'essenciais' => $essenciais,
            'custo_total' => $custoTotalGeral,
            'margem_media' => $margemMedia,
        ];

        // Lista de itens do cardápio para o filtro
        $cardapioItens = CardapioItem::where('restaurante_id', $restauranteId)
            ->orderBy('nome')
            ->get(['id', 'nome']);

        return view('receitas.index', compact('receitas', 'stats', 'cardapioItens', 'receitasAgrupadas', 'custosPorItem'));
    }

    public function create()
    {
        [$cardapioItens, $insumos] = $this->formOptions();

        // Preparar dados para JavaScript
        $cardapioItensJson = $cardapioItens->map(function($item) {
            return [
                'id' => $item->id,
                'nome' => $item->nome,
                'preco' => floatval($item->preco_venda ?? 0)
            ];
        })->values()->all();

        $insumosJson = $insumos->map(function($insumo) {
            return [
                'id' => $insumo->id,
                'nome' => $insumo->nome,
                'custo' => floatval($insumo->custo_unitario ?? 0),
                'unidade' => $insumo->unidade_medida ?? '',
                'categoria' => $insumo->categoria ?? ''
            ];
        })->values()->all();

        return view('receitas.create', compact('cardapioItens', 'insumos', 'cardapioItensJson', 'insumosJson'));
    }

    public function store(Request $request)
    {
        $restauranteId = $this->restauranteId();

        // Suportar múltiplas receitas
        if ($request->has('receitas') && is_array($request->receitas)) {
            $created = 0;
            foreach ($request->receitas as $receitaData) {
                $data = $this->validateReceitaData($receitaData, $restauranteId);
                Receita::create($data);
                $created++;
            }
            
            $message = $created === 1 
                ? 'Receita vinculada com sucesso.' 
                : "{$created} receitas vinculadas com sucesso.";
            
            return redirect()->route('receitas.index')->with('success', $message);
        }

        // Receita única (compatibilidade)
        $data = $this->validateReceitaData($request->all(), $restauranteId);
        Receita::create($data);

        return redirect()->route('receitas.index')->with('success', 'Receita vinculada com sucesso.');
    }

    protected function validateReceitaData(array $data, int $restauranteId): array
    {
        $validated = validator($data, [
            'cardapio_item_id' => [
                'required',
                Rule::exists('cardapio_itens', 'id')->where('restaurante_id', $restauranteId),
            ],
            'insumo_id' => [
                'required',
                Rule::exists('insumos', 'id')->where('restaurante_id', $restauranteId),
            ],
            'quantidade_necessaria' => ['required', 'numeric', 'min:0.01'],
            'essencial' => ['nullable', 'boolean'],
        ])->validate();

        $validated['essencial'] = isset($data['essencial']) && $data['essencial'];

        return $validated;
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

    public function detalhes(Receita $receita)
    {
        $this->authorizeReceita($receita);

        $receita->load(['cardapioItem', 'insumo']);

        $custoUnitario = $receita->insumo?->custo_unitario ?? 0;
        $custoTotal = $receita->quantidade_necessaria * $custoUnitario;

        return response()->json([
            'cardapio_item' => $receita->cardapioItem?->nome ?? 'Item removido',
            'insumo' => $receita->insumo?->nome ?? 'Insumo removido',
            'quantidade' => number_format($receita->quantidade_necessaria, 2, ',', '.'),
            'unidade' => $receita->insumo?->unidade_medida ?? '',
            'essencial' => $receita->essencial,
            'custo_unitario' => $custoUnitario > 0 ? number_format($custoUnitario, 2, ',', '.') : null,
            'custo' => $custoTotal > 0 ? number_format($custoTotal, 2, ',', '.') : null,
        ]);
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
            ->get(['id', 'nome', 'preco_venda']);

        $insumos = Insumo::where('restaurante_id', $restauranteId)
            ->orderBy('nome')
            ->get(['id', 'nome', 'custo_unitario', 'unidade_medida', 'categoria']);

        return [$cardapioItens, $insumos];
    }
}
