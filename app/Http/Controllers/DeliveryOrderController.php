<?php
namespace App\Http\Controllers;

use App\Models\DeliveryOrder;
use App\Models\Pedido;
use Illuminate\Http\Request;

class DeliveryOrderController extends Controller
{
    // Lista pedidos de delivery
    public function index()
    {
        $orders = DeliveryOrder::with('pedido')->orderByDesc('created_at')->get();
        return view('admin.delivery.index', compact('orders'));
    }

    // Detalhes do pedido de delivery
    public function show($id)
    {
        $order = DeliveryOrder::with('pedido')->findOrFail($id);
        return view('admin.delivery.show', compact('order'));
    }

    // Atualiza status e rastreamento
    public function update(Request $request, $id)
    {
        $order = DeliveryOrder::findOrFail($id);
        $validated = $request->validate([
            'status' => 'required|in:' . implode(',', array_keys(DeliveryOrder::statusList())),
            'rota' => 'nullable|string|max:255',
            'horario_entrega' => 'nullable|date',
        ]);

        $oldStatus = $order->status;
        $order->status = $validated['status'];
        $order->rota = $validated['rota'] ?? $order->rota;
        $order->horario_entrega = $validated['horario_entrega'] ?? $order->horario_entrega;
        $order->save();

        if ($oldStatus !== $order->status) {
            \App\Models\DeliveryOrderStatusLog::create([
                'delivery_order_id' => $order->id,
                'old_status' => $oldStatus,
                'new_status' => $order->status,
                'changed_at' => now(),
            ]);
        }

        return redirect()->route('admin.delivery.show', $order->id)->with('success', 'Pedido atualizado!');
    }
}
