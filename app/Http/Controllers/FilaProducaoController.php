<?php

namespace App\Http\Controllers;

use App\Models\FilaProducao;
use App\Models\PedidoItem;
use Illuminate\Http\Request;
class FilaProducaoController extends Controller
{
    public function index(Request $request)
    {
        $restauranteId = $this->restauranteId();

        $query = FilaProducao::with(['pedidoItem.cardapioItem', 'pedido'])
            ->whereHas('pedido', fn ($q) => $q->where('restaurante_id', $restauranteId));

        // Filtro de busca
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('pedido_id', 'like', "%{$search}%")
                  ->orWhere('status_producao', 'like', "%{$search}%")
                  ->orWhereHas('pedidoItem.cardapioItem', fn ($q2) => $q2->where('nome', 'like', "%{$search}%"));
            });
        }

        // Filtro por status
        if ($request->filled('status')) {
            $query->where('status_producao', $request->status);
        }

        // Filtro por prioridade
        if ($request->filled('prioridade')) {
            if ($request->prioridade === 'alta') {
                $query->where('prioridade', '>=', 5);
            } elseif ($request->prioridade === 'media') {
                $query->whereBetween('prioridade', [3, 4]);
            } elseif ($request->prioridade === 'baixa') {
                $query->where('prioridade', '<=', 2);
            }
        }

        // Ordenação
        $query->orderBy('prioridade', 'desc')->orderBy('created_at');

        // Modo de visualização
        $viewMode = $request->get('view', 'table'); // 'table' ou 'kanban'
        
        if ($viewMode === 'kanban') {
            // Para Kanban, não paginar, apenas agrupar por status
            $filasAgrupadas = $query->get()->groupBy('status_producao');
            // Garantir que todas as colunas existam
            $statusColunas = ['pendente', 'em_producao', 'pronto'];
            foreach ($statusColunas as $status) {
                if (!$filasAgrupadas->has($status)) {
                    $filasAgrupadas->put($status, collect());
                }
            }
            $filas = null;
        } else {
            $perPage = $request->get('per_page', 15);
            $filas = $query->paginate($perPage)->withQueryString();
            $filasAgrupadas = collect([
                'pendente' => collect(),
                'em_producao' => collect(),
                'pronto' => collect()
            ]);
        }

        // Estatísticas
        $totalItens = FilaProducao::whereHas('pedido', fn ($q) => $q->where('restaurante_id', $restauranteId))->count();
        $emProducao = FilaProducao::whereHas('pedido', fn ($q) => $q->where('restaurante_id', $restauranteId))
            ->where('status_producao', 'em_producao')
            ->count();
        $pendentes = FilaProducao::whereHas('pedido', fn ($q) => $q->where('restaurante_id', $restauranteId))
            ->where('status_producao', 'pendente')
            ->count();
        $prioridadeAlta = FilaProducao::whereHas('pedido', fn ($q) => $q->where('restaurante_id', $restauranteId))
            ->where('prioridade', '>=', 5)
            ->count();

        $stats = [
            'total' => $totalItens,
            'em_producao' => $emProducao,
            'pendentes' => $pendentes,
            'prioridade_alta' => $prioridadeAlta,
        ];

        // Lista de status únicos
        $statusList = FilaProducao::whereHas('pedido', fn ($q) => $q->where('restaurante_id', $restauranteId))
            ->distinct('status_producao')
            ->pluck('status_producao');

        return view('fila_producao.index', compact('filas', 'filasAgrupadas', 'stats', 'statusList', 'viewMode'));
    }

    public function create(Request $request)
    {
        $pedidoId = $request->get('pedido_id');
        $pedidoItens = $this->pedidoItensDisponiveis($pedidoId);

        return view('fila_producao.create', compact('pedidoItens', 'pedidoId'));
    }

    public function store(Request $request)
    {
        $restauranteId = $this->restauranteId();

        $data = $request->validate([
            'pedido_item_id' => ['required', 'exists:pedido_itens,id'],
            'status_producao' => ['required', 'string', 'max:50'],
            'prioridade' => ['nullable', 'integer'],
            'data_hora_inicio' => ['nullable', 'date'],
            'data_hora_fim' => ['nullable', 'date', 'after_or_equal:data_hora_inicio'],
        ]);

        $pedidoItem = PedidoItem::with('pedido')->findOrFail($data['pedido_item_id']);
        abort_unless($pedidoItem->pedido?->restaurante_id === $restauranteId, 403);

        $data['pedido_id'] = $pedidoItem->pedido_id;

        FilaProducao::create($data);

        return redirect()->route('fila-producao.index')->with('success', 'Item enviado para a fila de produção.');
    }

    public function edit(FilaProducao $filaProducao)
    {
        $this->authorizeFila($filaProducao);

        $pedidoItens = $this->pedidoItensDisponiveis();

        return view('fila_producao.edit', [
            'fila' => $filaProducao,
            'pedidoItens' => $pedidoItens,
        ]);
    }

    public function update(Request $request, FilaProducao $filaProducao)
    {
        $this->authorizeFila($filaProducao);

        $restauranteId = $this->restauranteId();

        // Atualização rápida de status (sem precisar do pedido_item_id)
        if ($request->has('status_producao') && !$request->has('pedido_item_id')) {
            $updateData = ['status_producao' => $request->status_producao];
            
            // Se iniciando produção, registrar data/hora de início
            if ($request->status_producao === 'em_producao' && !$filaProducao->data_hora_inicio) {
                $updateData['data_hora_inicio'] = now();
            }
            
            // Se finalizando produção, registrar data/hora de fim
            if ($request->status_producao === 'pronto' && !$filaProducao->data_hora_fim) {
                $updateData['data_hora_fim'] = now();
            }
            
            // Se foi enviado data_hora_inicio ou data_hora_fim, usar o valor enviado
            if ($request->filled('data_hora_inicio')) {
                $updateData['data_hora_inicio'] = $request->data_hora_inicio;
            }
            if ($request->filled('data_hora_fim')) {
                $updateData['data_hora_fim'] = $request->data_hora_fim;
            }
            
            $filaProducao->update($updateData);
            
            $mensagem = match($request->status_producao) {
                'em_producao' => 'Produção iniciada com sucesso.',
                'pronto' => 'Item marcado como pronto.',
                default => 'Status atualizado com sucesso.'
            };
            
            return redirect()->route('fila-producao.index')->with('success', $mensagem);
        }

        // Atualização completa
        $data = $request->validate([
            'pedido_item_id' => ['required', 'exists:pedido_itens,id'],
            'status_producao' => ['required', 'string', 'max:50'],
            'prioridade' => ['nullable', 'integer'],
            'data_hora_inicio' => ['nullable', 'date'],
            'data_hora_fim' => ['nullable', 'date', 'after_or_equal:data_hora_inicio'],
        ]);

        $pedidoItem = PedidoItem::with('pedido')->findOrFail($data['pedido_item_id']);
        abort_unless($pedidoItem->pedido?->restaurante_id === $restauranteId, 403);

        $data['pedido_id'] = $pedidoItem->pedido_id;

        $filaProducao->update($data);

        return redirect()->route('fila-producao.index')->with('success', 'Fila de produção atualizada com sucesso.');
    }

    public function destroy(FilaProducao $filaProducao)
    {
        $this->authorizeFila($filaProducao);

        $filaProducao->delete();

        return redirect()->route('fila-producao.index')->with('success', 'Item removido da fila.');
    }

    protected function authorizeFila(FilaProducao $filaProducao): void
    {
        abort_unless($filaProducao->pedido?->restaurante_id === $this->restauranteId(), 403);
    }

    protected function pedidoItensDisponiveis($pedidoId = null)
    {
        $restauranteId = $this->restauranteId();

        // Buscar IDs de itens que já estão na fila
        $itensNaFila = FilaProducao::whereHas('pedido', fn ($q) => $q->where('restaurante_id', $restauranteId))
            ->whereIn('status_producao', ['pendente', 'em_producao'])
            ->pluck('pedido_item_id')
            ->toArray();

        $query = PedidoItem::with(['pedido', 'cardapioItem', 'filaProducao'])
            ->whereHas('pedido', fn ($query) => $query->where('restaurante_id', $restauranteId))
            ->whereNotIn('id', $itensNaFila) // Excluir itens já na fila
            ->whereHas('pedido', fn ($q) => $q->whereIn('status', ['pendente', 'recebido', 'em_preparo'])); // Apenas pedidos em produção

        // Filtrar por pedido específico se fornecido
        if ($pedidoId) {
            $query->where('pedido_id', $pedidoId);
        }

        return $query->orderByDesc('created_at')
            ->get()
            ->mapWithKeys(function ($item) {
                $pedidoNumero = $item->pedido?->numero_pedido_externo ?? $item->pedido_id;
                $statusPedido = $item->pedido?->status ?? 'pendente';
                $quantidade = $item->quantidade ?? 1;
                $nomeItem = $item->cardapioItem?->nome ?? 'Item';
                
                // Formatar label com mais informações
                $statusLabel = '';
                switch($statusPedido) {
                    case 'pendente':
                        $statusLabel = '⏳ Pendente';
                        break;
                    case 'recebido':
                        $statusLabel = '📥 Recebido';
                        break;
                    case 'em_preparo':
                        $statusLabel = '👨‍🍳 Em Preparo';
                        break;
                }
                
                $label = sprintf('#%s - %s (Qtd: %d) %s', 
                    $pedidoNumero, 
                    $nomeItem, 
                    $quantidade,
                    $statusLabel
                );

                return [$item->id => $label];
            });
    }
}
