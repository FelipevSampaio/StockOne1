<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Models\User;
use Illuminate\Http\Request;

class PedidoController extends Controller
{
    /**
     * Atualiza apenas o status do pedido
     */
    public function atualizarStatus(Request $request, Pedido $pedido)
    {
        $this->authorizePedido($pedido);
        $data = $request->validate([
            'status' => ['required', 'in:pendente,recebido,em_preparo,pronto,entregue,concluido,cancelado'],
        ]);
        $pedido->update(['status' => $data['status']]);
        return redirect()->route('pedidos.index')->with('success', 'Status do pedido atualizado com sucesso.');
    }

    /**
     * Retorna os detalhes do pedido para o modal
     */
    public function detalhes(Pedido $pedido)
    {
        $this->authorizePedido($pedido);
        $pedido->load(['itens.cardapioItem', 'usuario', 'restaurante']);
        
        return view('pedidos.detalhes', compact('pedido'));
    }
    public function lote(Request $request)
    {
        $ids = $request->input('pedidos', []);
        $action = $request->input('action');
        if (empty($ids) || !$action) {
            return redirect()->route('pedidos.index')->with('error', 'Selecione pedidos e uma ação.');
        }

        if ($action === 'entregar') {
            Pedido::whereIn('id', $ids)->update(['status' => 'concluido']);
            return redirect()->route('pedidos.index')->with('success', 'Pedidos marcados como entregues.');
        }

        // Outras ações podem ser adicionadas aqui
        return redirect()->route('pedidos.index')->with('error', 'Ação não reconhecida.');
    }
    public function index(Request $request)
    {
        $restauranteId = $this->restauranteId();

        $query = Pedido::with(['restaurante', 'usuario', 'itens.cardapioItem'])
            ->where('restaurante_id', $restauranteId);

        // Filtro de busca
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('numero_pedido_externo', 'like', "%{$search}%")
                  ->orWhere('id', 'like', "%{$search}%");
            });
        }

        // Filtro por status
        if ($request->filled('status')) {
            $query->where('status', $request->get('status'));
        }

        // Filtro por plataforma
        if ($request->filled('plataforma')) {
            $query->where('plataforma_origem', $request->get('plataforma'));
        }

        // Filtro por data
        if ($request->filled('data_inicio')) {
            $query->whereDate('data_hora_pedido', '>=', $request->get('data_inicio'));
        }
        if ($request->filled('data_fim')) {
            $query->whereDate('data_hora_pedido', '<=', $request->get('data_fim'));
        }

        // Ordenação
        $sortBy = $request->get('sort', 'data_hora_pedido');
        $sortOrder = $request->get('order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $perPage = $request->get('per_page', 15);
        $pedidos = $query->paginate($perPage)->withQueryString();

        // Estatísticas
        $stats = [
            'total' => Pedido::where('restaurante_id', $restauranteId)->count(),
            'hoje' => Pedido::where('restaurante_id', $restauranteId)
                ->whereDate('data_hora_pedido', today())->count(),
            'pendentes' => Pedido::where('restaurante_id', $restauranteId)
                ->where('status', 'pendente')->count(),
            'receita_hoje' => Pedido::where('restaurante_id', $restauranteId)
                ->whereDate('data_hora_pedido', today())
                ->sum('valor_total'),
        ];

        // Plataformas disponíveis
        $plataformas = Pedido::where('restaurante_id', $restauranteId)
            ->distinct()
            ->orderBy('plataforma_origem')
            ->pluck('plataforma_origem');

        return view('pedidos.index', compact('pedidos', 'stats', 'plataformas'));
    }

    public function create()
    {
        $usuarios = User::orderBy('name')->pluck('name', 'id');

        return view('pedidos.create', compact('usuarios'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'usuario_id' => ['nullable', 'exists:users,id'],
            'numero_pedido_externo' => ['nullable', 'string', 'max:255'],
            'plataforma_origem' => ['required', 'string', 'max:100'],
            'data_hora_pedido' => ['required', 'date'],
            'status' => ['required', 'string', 'max:50'],
            'valor_total' => ['nullable', 'numeric'],
            'tempo_preparo_estimado' => ['nullable', 'integer', 'min:0'],
        ]);

        $data['restaurante_id'] = $this->restauranteId();

        $pedido = Pedido::create($data);

        // Integração com delivery: criar DeliveryOrder se for delivery
        if ($request->has('delivery')) {
            $deliveryOrder = new \App\Models\DeliveryOrder([
                'pedido_id' => $pedido->id,
                'rota' => $request->input('rota'),
                'horario_entrega' => $request->input('horario_entrega'),
                'status' => 'pendente',
            ]);
            $deliveryOrder->save();
            // Otimizar tempo de preparo e despacho
            $deliveryOrder->calcularTempoIdeal();
        }

        return redirect()->route('pedidos.index')->with('success', 'Pedido registrado com sucesso.');
    }

    public function edit(Pedido $pedido)
    {
        $this->authorizePedido($pedido);

        $usuarios = User::orderBy('name')->pluck('name', 'id');

        return view('pedidos.edit', compact('pedido', 'usuarios'));
    }

    public function update(Request $request, Pedido $pedido)
    {
        $this->authorizePedido($pedido);

        $data = $request->validate([
            'usuario_id' => ['nullable', 'exists:users,id'],
            'numero_pedido_externo' => ['nullable', 'string', 'max:255'],
            'plataforma_origem' => ['required', 'string', 'max:100'],
            'data_hora_pedido' => ['required', 'date'],
            'status' => ['required', 'string', 'max:50'],
            'valor_total' => ['nullable', 'numeric'],
            'tempo_preparo_estimado' => ['nullable', 'integer', 'min:0'],
        ]);

        $pedido->update($data);

        return redirect()->route('pedidos.index')->with('success', 'Pedido atualizado com sucesso.');
    }

    public function destroy(Pedido $pedido)
    {
        $this->authorizePedido($pedido);

        $pedido->delete();

        return redirect()->route('pedidos.index')->with('success', 'Pedido removido com sucesso.');
    }

    protected function restauranteId(): int
    {
        return (int) session('restaurante_id');
    }

    protected function authorizePedido(Pedido $pedido): void
    {
        abort_unless($pedido->restaurante_id === $this->restauranteId(), 403);
    }
}
