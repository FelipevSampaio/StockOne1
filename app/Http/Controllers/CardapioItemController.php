<?php

namespace App\Http\Controllers;

use App\Models\CardapioItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CardapioItemController extends Controller
{
    /**
     * Verifica se o item do cardápio está disponível (todos insumos essenciais em estoque)
     * e sugere substituições automáticas para itens em falta.
     */
    public function verificarDisponibilidadeEsubstituicoes(CardapioItem $item)
    {
        $disponivel = true;
        $substituicoes = [];
        foreach ($item->receitas()->where('essencial', true)->get() as $receita) {
            $insumo = $receita->insumo;
            $estoque = $insumo->estoque;
            if (!$estoque || $estoque->quantidade_atual < $receita->quantidade_necessaria) {
                $disponivel = false;
                // Buscar insumos da mesma categoria com estoque suficiente
                $alternativas = \App\Models\Insumo::where('categoria', $insumo->categoria)
                    ->where('id', '!=', $insumo->id)
                    ->whereHas('estoque', function($q) use ($receita) {
                        $q->where('quantidade_atual', '>=', $receita->quantidade_necessaria);
                    })
                    ->get();
                foreach ($alternativas as $alt) {
                    $substituicoes[] = [
                        'insumo_faltante' => $insumo->nome,
                        'substituto' => $alt->nome,
                        'categoria' => $alt->categoria,
                    ];
                }
            }
        }
        return [
            'disponivel' => $disponivel,
            'substituicoes' => $substituicoes,
        ];
    }
    public function index(Request $request)
    {
        $restauranteId = $this->restauranteId();

        $query = CardapioItem::with('restaurante')
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

        // Filtro por status (padrão: online se não especificado)
        if ($request->filled('status')) {
            $status = $request->get('status');
            if ($status === 'online') {
                $query->where('ativo_online', true);
            } elseif ($status === 'offline') {
                $query->where('ativo_online', false);
            } elseif ($status === 'all') {
                // Mostrar todos (online e offline)
                // Não aplica filtro
            }
        } else {
            // Por padrão, mostrar apenas itens online
            $query->where('ativo_online', true);
        }

        // Ordenação
        $sortBy = $request->get('sort', 'nome');
        $sortOrder = $request->get('order', 'asc');
        
        // Adicionar contagem de vendas para todos os itens
        $query->withCount(['pedidoItens as total_vendido' => function($q) {
            $q->selectRaw('COALESCE(SUM(quantidade), 0)');
        }]);

        // Ordenação especial por mais vendidos
        if ($sortBy === 'vendas') {
            $query->orderBy('total_vendido', $sortOrder);
        } else {
            // Ordenar por categoria e depois por ordem dentro da categoria
            $query->orderBy('categoria')
                  ->orderBy('ordem')
                  ->orderBy($sortBy, $sortOrder);
        }

        $perPage = $request->get('per_page', 15);
        $itens = $query->with(['receitas.insumo', 'insumo'])->paginate($perPage)->withQueryString();
        
        // Agrupar itens por categoria para visualização com drag-and-drop
        // Aplicar os mesmos filtros da query principal
        $queryAgrupados = CardapioItem::where('restaurante_id', $restauranteId);
        
        // Aplicar filtro de busca
        if ($request->filled('search')) {
            $search = $request->get('search');
            $queryAgrupados->where(function($q) use ($search) {
                $q->where('nome', 'like', "%{$search}%")
                  ->orWhere('descricao', 'like', "%{$search}%")
                  ->orWhere('categoria', 'like', "%{$search}%");
            });
        }
        
        // Filtro por categoria
        if ($request->filled('categoria')) {
            $queryAgrupados->where('categoria', $request->get('categoria'));
        }
        
        // Filtro por status
        if ($request->filled('status')) {
            $status = $request->get('status');
            if ($status === 'online') {
                $queryAgrupados->where('ativo_online', true);
            } elseif ($status === 'offline') {
                $queryAgrupados->where('ativo_online', false);
            }
        } else {
            // Por padrão, mostrar apenas itens online
            $queryAgrupados->where('ativo_online', true);
        }
        
        $itensAgrupados = $queryAgrupados
            ->with(['receitas.insumo', 'insumo'])
            ->withCount(['pedidoItens as total_vendido' => function($q) {
                $q->selectRaw('COALESCE(SUM(quantidade), 0)');
            }])
            ->orderBy('categoria')
            ->orderBy('ordem')
            ->orderBy('nome')
            ->get()
            ->groupBy('categoria');
        
        // Inicializar ordem para itens que ainda não têm ordem definida
        foreach ($itensAgrupados as $categoria => $itens) {
            $ordem = 0;
            foreach ($itens as $item) {
                if ($item->ordem === null || $item->ordem === 0) {
                    $item->ordem = $ordem;
                    $item->save();
                }
                $ordem++;
            }
        }
        
        // Recarregar após inicializar ordem
        $itensAgrupados = CardapioItem::where('restaurante_id', $restauranteId)
            ->where(function($q) use ($request) {
                if (!$request->filled('status') || $request->get('status') !== 'all') {
                    $q->where('ativo_online', true);
                }
            })
            ->with(['receitas.insumo', 'insumo'])
            ->orderBy('categoria')
            ->orderBy('ordem')
            ->orderBy('nome')
            ->get()
            ->groupBy('categoria');

        // Estatísticas
        $stats = [
            'total' => CardapioItem::where('restaurante_id', $restauranteId)->count(),
            'online' => CardapioItem::where('restaurante_id', $restauranteId)
                ->where('ativo_online', true)->count(),
            'offline' => CardapioItem::where('restaurante_id', $restauranteId)
                ->where('ativo_online', false)->count(),
            'categorias' => CardapioItem::where('restaurante_id', $restauranteId)
                ->distinct()->count('categoria'),
            'receita_total' => \App\Models\PedidoItem::whereHas('pedido', function($q) use ($restauranteId) {
                $q->where('restaurante_id', $restauranteId);
            })->selectRaw('COALESCE(SUM(preco_unitario * quantidade), 0) as total')
              ->value('total') ?? 0,
        ];

        // Categorias disponíveis
        $categorias = CardapioItem::where('restaurante_id', $restauranteId)
            ->whereNotNull('categoria')
            ->distinct()
            ->orderBy('categoria')
            ->pluck('categoria');

        return view('cardapio_itens.index', compact('itens', 'stats', 'categorias', 'itensAgrupados'));
    }

    public function create()
    {
        $restauranteId = $this->restauranteId();
        
        // Buscar categorias existentes do restaurante
        $categorias = CardapioItem::where('restaurante_id', $restauranteId)
            ->whereNotNull('categoria')
            ->distinct()
            ->orderBy('categoria')
            ->pluck('categoria')
            ->toArray();

        // Buscar insumos com estoque para vinculação direta
        $insumos = \App\Models\Insumo::where('restaurante_id', $restauranteId)
            ->whereHas('estoque')
            ->orderBy('nome')
            ->get(['id', 'nome', 'custo_unitario', 'unidade_medida']);
        
        // Buscar insumos com estoque para vinculação direta
        $insumos = \App\Models\Insumo::where('restaurante_id', $restauranteId)
            ->whereHas('estoque')
            ->orderBy('nome')
            ->get(['id', 'nome', 'custo_unitario', 'unidade_medida']);
        
        return view('cardapio_itens.create', compact('categorias', 'insumos'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nome' => ['required', 'string', 'max:255'],
            'descricao' => ['nullable', 'string'],
            'preco_venda' => ['required', 'numeric'],
            'tempo_preparo_minutos' => ['nullable', 'integer', 'min:0'],
            'complexidade_preparo' => ['required', 'integer', 'min:1', 'max:10'],
            'categoria' => ['nullable', 'string', 'max:100'],
            'ativo_online' => ['nullable', 'boolean'], // Keep this line for validation
            'imagem' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'mimetypes:image/jpeg,image/png,image/gif,image/webp', 'max:8192'],
            'disponibilidade' => ['nullable', 'boolean'],
            'ingredientes' => ['nullable', 'string'],
            'promocao' => ['nullable', 'string'],
            'insumo_id' => ['nullable', 'exists:insumos,id'],
            'quantidade_por_unidade' => ['nullable', 'numeric', 'min:0'],
        ]);

        $data['restaurante_id'] = $this->restauranteId();
        $data['ativo_online'] = true; // Set default to true
        $data['disponibilidade'] = $request->boolean('disponibilidade');

        // Se não houver insumo_id, limpar quantidade_por_unidade
        if (empty($data['insumo_id'])) {
            $data['quantidade_por_unidade'] = null;
        }

        // Ingredientes: transforma string em array
        if (!empty($data['ingredientes'])) {
            $data['ingredientes'] = array_map('trim', explode(',', $data['ingredientes']));
        } else {
            $data['ingredientes'] = [];
        }

        // Promoção: salva como array json
        if (!empty($data['promocao'])) {
            $data['promocao'] = ['descricao' => $data['promocao']];
        } else {
            $data['promocao'] = null;
        }

        if ($request->hasFile('imagem')) {
            $data['imagem'] = $request->file('imagem')->store('cardapio-itens', 'public');
        }

        // Definir ordem automaticamente (última posição na categoria)
        if (empty($data['ordem'])) {
            $ultimaOrdem = CardapioItem::where('restaurante_id', $data['restaurante_id'])
                ->where('categoria', $data['categoria'] ?? null)
                ->max('ordem') ?? -1;
            $data['ordem'] = $ultimaOrdem + 1;
        }

        CardapioItem::create($data);

        return redirect()->route('admin.cardapio.index')->with('success', 'Item de cardápio cadastrado com sucesso.');
    }

    public function edit(CardapioItem $cardapioItem)
    {
        $this->authorizeItem($cardapioItem);

        $restauranteId = $this->restauranteId();
        
        // Buscar categorias existentes do restaurante
        $categorias = CardapioItem::where('restaurante_id', $restauranteId)
            ->whereNotNull('categoria')
            ->distinct()
            ->orderBy('categoria')
            ->pluck('categoria')
            ->toArray();

        // Buscar insumos com estoque para vinculação direta
        $insumos = \App\Models\Insumo::where('restaurante_id', $restauranteId)
            ->whereHas('estoque')
            ->orderBy('nome')
            ->get(['id', 'nome', 'custo_unitario', 'unidade_medida']);

        // Estatísticas do item
        $stats = [
            'total_vendido' => \App\Models\PedidoItem::where('cardapio_item_id', $cardapioItem->id)
                ->sum('quantidade'),
            'receita_total' => \App\Models\PedidoItem::where('cardapio_item_id', $cardapioItem->id)
                ->selectRaw('SUM(preco_unitario * quantidade) as total')
                ->value('total') ?? 0,
            'pedidos_count' => \App\Models\PedidoItem::where('cardapio_item_id', $cardapioItem->id)
                ->distinct('pedido_id')
                ->count('pedido_id'),
            'ultimo_pedido' => \App\Models\PedidoItem::where('cardapio_item_id', $cardapioItem->id)
                ->join('pedidos', 'pedido_itens.pedido_id', '=', 'pedidos.id')
                ->orderBy('pedidos.data_hora_pedido', 'desc')
                ->first(),
        ];

        return view('cardapio_itens.edit', ['item' => $cardapioItem, 'stats' => $stats, 'categorias' => $categorias, 'insumos' => $insumos]);
    }

    public function update(Request $request, CardapioItem $cardapioItem)
    {
        $this->authorizeItem($cardapioItem);

        $data = $request->validate([
            'nome' => ['required', 'string', 'max:255'],
            'descricao' => ['nullable', 'string'],
            'preco_venda' => ['required', 'numeric'],
            'tempo_preparo_minutos' => ['nullable', 'integer', 'min:0'],
            'complexidade_preparo' => ['required', 'integer', 'min:1', 'max:10'],
            'categoria' => ['nullable', 'string', 'max:100'],
            'ativo_online' => ['nullable', 'boolean'],
            'imagem' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'mimetypes:image/jpeg,image/png,image/gif,image/webp', 'max:8192'],
            'disponibilidade' => ['nullable', 'boolean'],
            'ingredientes' => ['nullable', 'string'],
            'promocao' => ['nullable', 'string'],
            'insumo_id' => ['nullable', 'exists:insumos,id'],
            'quantidade_por_unidade' => ['nullable', 'numeric', 'min:0'],
        ]);

        $data['ativo_online'] = $request->boolean('ativo_online');
        $data['disponibilidade'] = $request->boolean('disponibilidade');

        // Se não houver insumo_id, limpar quantidade_por_unidade
        if (empty($data['insumo_id'])) {
            $data['quantidade_por_unidade'] = null;
        }

        // Ingredientes: transforma string em array
        if (!empty($data['ingredientes'])) {
            $data['ingredientes'] = array_map('trim', explode(',', $data['ingredientes']));
        } else {
            $data['ingredientes'] = [];
        }

        // Promoção: salva como array json
        if (!empty($data['promocao'])) {
            $data['promocao'] = ['descricao' => $data['promocao']];
        } else {
            $data['promocao'] = null;
        }

        if ($request->hasFile('imagem')) {
            // Remove a imagem antiga se existir
            if ($cardapioItem->imagem) {
                Storage::disk('public')->delete($cardapioItem->imagem);
            }
            $data['imagem'] = $request->file('imagem')->store('cardapio-itens', 'public');
        }

        $cardapioItem->update($data);

        return redirect()->route('admin.cardapio.index')->with('success', 'Item de cardápio atualizado com sucesso.');
    }

    public function toggleStatus(CardapioItem $cardapio_item)
    {
        $this->authorizeItem($cardapio_item);

        $cardapio_item->ativo_online = !$cardapio_item->ativo_online;
        $cardapio_item->save();

        $message = $cardapio_item->ativo_online 
            ? 'Item ativado com sucesso. Agora ele aparece no menu público.' 
            : 'Item desativado com sucesso. Ele não aparecerá mais no menu público, mas o histórico de pedidos será preservado para relatórios.';

        return redirect()
            ->route('cardapio-itens.index')
            ->with('success', $message);
    }

    public function duplicate(CardapioItem $cardapio_item)
    {
        $this->authorizeItem($cardapio_item);

        $newItem = $cardapio_item->replicate();
        $newItem->nome = $cardapio_item->nome . ' (Cópia)';
        $newItem->ativo_online = false; // Desativado por padrão
        $newItem->save();

        // Duplicar receitas
        foreach ($cardapio_item->receitas as $receita) {
            $newReceita = $receita->replicate();
            $newReceita->cardapio_item_id = $newItem->id;
            $newReceita->save();
        }

        return redirect()
            ->route('cardapio-itens.edit', $newItem)
            ->with('success', 'Item duplicado com sucesso! Revise as informações antes de ativar.');
    }

    public function destroy(CardapioItem $cardapioItem)
    {
        $this->authorizeItem($cardapioItem);

        // Verificar se há pedidos relacionados
        $pedidosCount = \App\Models\PedidoItem::where('cardapio_item_id', $cardapioItem->id)->count();
        
        if ($pedidosCount > 0) {
            return redirect()
                ->route('cardapio-itens.index')
                ->with('error', "Não é possível excluir este item porque ele está relacionado a {$pedidosCount} pedido(s). Use o botão 'Desativar' para removê-lo do menu público mantendo o histórico de pedidos para relatórios.");
        }

        // Verificar se há receitas relacionadas
        $receitasCount = $cardapioItem->receitas()->count();
        if ($receitasCount > 0) {
            // Deletar receitas relacionadas primeiro
            $cardapioItem->receitas()->delete();
        }

        // Remove a imagem se existir
        if ($cardapioItem->imagem) {
            Storage::disk('public')->delete($cardapioItem->imagem);
        }

        try {
            $cardapioItem->delete();
            return redirect()
                ->route('cardapio-itens.index')
                ->with('success', 'Item de cardápio removido com sucesso.');
        } catch (\Illuminate\Database\QueryException $e) {
            // Fallback caso ainda haja alguma constraint
            return redirect()
                ->route('cardapio-itens.index')
                ->with('error', 'Não foi possível excluir este item. Ele pode estar relacionado a outros registros no sistema. Use o botão "Desativar" para removê-lo do menu público mantendo o histórico.');
        }
    }

    protected function restauranteId(): int
    {
        return (int) session('restaurante_id');
    }

    public function updateOrder(Request $request)
    {
        $restauranteId = $this->restauranteId();
        
        $request->validate([
            'items' => ['required', 'array'],
            'items.*.id' => ['required', 'exists:cardapio_itens,id'],
            'items.*.ordem' => ['required', 'integer', 'min:0'],
            'categoria' => ['nullable', 'string'],
        ]);

        // Converter 'sem-categoria' para null
        $categoria = ($request->categoria === 'sem-categoria' || $request->categoria === null) ? null : $request->categoria;
        $items = $request->items;

        // Verificar se todos os itens pertencem ao restaurante e à categoria
        foreach ($items as $itemData) {
            $item = CardapioItem::findOrFail($itemData['id']);
            if ($item->restaurante_id !== $restauranteId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Um ou mais itens não pertencem a este restaurante.',
                ], 403);
            }
            // Comparar categoria (tratando null corretamente)
            $itemCategoria = $item->categoria ?? null;
            if ($itemCategoria !== $categoria) {
                return response()->json([
                    'success' => false,
                    'message' => 'Um ou mais itens não pertencem a esta categoria.',
                ], 403);
            }
        }

        // Atualizar a ordem de cada item
        foreach ($items as $itemData) {
            $query = CardapioItem::where('id', $itemData['id'])
                ->where('restaurante_id', $restauranteId);
            
            // Tratar categoria null corretamente
            if ($categoria === null) {
                $query->whereNull('categoria');
            } else {
                $query->where('categoria', $categoria);
            }
            
            $query->update(['ordem' => $itemData['ordem']]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Ordem dos itens atualizada com sucesso.',
        ]);
    }

    protected function authorizeItem(CardapioItem $cardapioItem): void
    {
        abort_unless($cardapioItem->restaurante_id === $this->restauranteId(), 403);
    }
}

