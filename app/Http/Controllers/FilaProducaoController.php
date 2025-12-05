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

        $perPage = $request->get('per_page', 15);
        $filas = $query->paginate($perPage)->withQueryString();

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

        return view('fila_producao.index', compact('filas', 'stats', 'statusList'));
    }

    public function create()
    {
        $pedidoItens = $this->pedidoItensDisponiveis();

        return view('fila_producao.create', compact('pedidoItens'));
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

    protected function pedidoItensDisponiveis()
    {
        $restauranteId = $this->restauranteId();

        return PedidoItem::with(['pedido', 'cardapioItem'])
            ->whereHas('pedido', fn ($query) => $query->where('restaurante_id', $restauranteId))
            ->orderByDesc('created_at')
            ->get()
            ->mapWithKeys(function ($item) {
                $pedidoNumero = $item->pedido?->numero_pedido_externo ?? $item->pedido_id;
                $label = sprintf('#%s - %s', $pedidoNumero, $item->cardapioItem?->nome ?? 'Item');

                return [$item->id => $label];
            });
    }
}
