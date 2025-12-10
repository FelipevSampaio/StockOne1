<?php

namespace App\Http\Controllers;

use App\Models\CardapioItem;
use App\Models\Pedido;
use App\Models\PedidoItem;
use App\Models\Restaurante;
use App\Support\PublicCart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PublicCartController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'cardapio_item_id' => ['required', 'exists:cardapio_itens,id'],
            'quantity' => ['nullable', 'integer', 'min:1', 'max:99'],
        ]);

        $item = CardapioItem::where('ativo_online', true)
            ->findOrFail($data['cardapio_item_id']);

        PublicCart::add($item, $data['quantity'] ?? 1);

        // Se for requisição AJAX, retornar JSON
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => "{$item->nome} adicionado ao pedido.",
                'cart' => [
                    'count' => PublicCart::itemsCount(),
                    'total' => PublicCart::subtotal(),
                    'items' => PublicCart::all()->values()->all(),
                ],
            ]);
        }

        return redirect()->route('public.menu')->with('success', "{$item->nome} adicionado ao pedido.");
    }

    public function update(Request $request, int $cardapioItemId)
    {
        try {
            $data = $request->validate([
                'quantity' => ['required', 'integer', 'min:0', 'max:99'],
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Dados inválidos.',
                    'errors' => $e->errors(),
                    'cart' => [
                        'count' => PublicCart::itemsCount(),
                        'total' => PublicCart::subtotal(),
                        'items' => PublicCart::all()->values()->all(),
                    ],
                ], 422);
            }
            throw $e;
        }

        $quantity = $data['quantity'];

        if ($quantity === 0) {
            PublicCart::remove($cardapioItemId);

            // Se for requisição AJAX, retornar JSON
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Item removido do pedido.',
                    'cart' => [
                        'count' => PublicCart::itemsCount(),
                        'total' => PublicCart::subtotal(),
                        'items' => PublicCart::all()->values()->all(),
                    ],
                ]);
            }

            return redirect()->route('public.menu')->with('success', 'Item removido do pedido.');
        }

        $item = CardapioItem::where('ativo_online', true)
            ->find($cardapioItemId);

        if (!$item) {
            PublicCart::remove($cardapioItemId);

            // Se for requisição AJAX, retornar JSON
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Este item não está mais disponível.',
                    'cart' => [
                        'count' => PublicCart::itemsCount(),
                        'total' => PublicCart::subtotal(),
                        'items' => PublicCart::all()->values()->all(),
                    ],
                ]);
            }

            return redirect()->route('public.menu')->with('error', 'Este item não está mais disponível.');
        }

        PublicCart::refreshFromModel($item);
        PublicCart::updateQuantity($cardapioItemId, $quantity);

        // Se for requisição AJAX, retornar JSON
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Quantidade atualizada.',
                'cart' => [
                    'count' => PublicCart::itemsCount(),
                    'total' => PublicCart::subtotal(),
                    'items' => PublicCart::all()->values()->all(),
                ],
            ]);
        }

        return redirect()->route('public.menu')->with('success', 'Quantidade atualizada.');
    }

    public function destroy(int $cardapioItemId)
    {
        PublicCart::remove($cardapioItemId);

        // Se for requisição AJAX, retornar JSON
        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Item removido do pedido.',
                'cart' => [
                    'count' => PublicCart::itemsCount(),
                    'total' => PublicCart::subtotal(),
                    'items' => PublicCart::all()->values()->all(),
                ],
            ]);
        }

        return redirect()->route('public.menu')->with('success', 'Item removido do pedido.');
    }

    public function checkout()
    {
        // Priorizar restaurante da sessão se o usuário estiver logado
        $restauranteId = session('restaurante_id') ?? $this->publicRestauranteId();

        if (! $restauranteId) {
            return redirect()->route('public.menu')->with('error', 'Restaurante indisponível no momento.');
        }

        if (PublicCart::isEmpty()) {
            return redirect()->route('public.menu')->with('error', 'Seu carrinho está vazio.');
        }

        $cartItems = PublicCart::all();
        $itemIds = $cartItems->pluck('id')->all();

        $availableItems = CardapioItem::where('restaurante_id', $restauranteId)
            ->whereIn('id', $itemIds)
            ->where('ativo_online', true)
            ->get()
            ->keyBy('id');

        $missing = collect($itemIds)->diff($availableItems->keys());

        if ($missing->isNotEmpty()) {
            PublicCart::removeMany($missing->all());
            return redirect()->route('public.menu')->with('error', 'Atualizamos seu pedido: alguns itens ficaram indisponíveis.');
        }

        $valorTotal = 0;

        foreach ($cartItems as $item) {
            $valorTotal += $availableItems[$item['id']]->preco_venda * $item['quantidade'];
        }
        $valorTotal = round($valorTotal, 2);

        DB::beginTransaction();

        try {
            $pedido = Pedido::create([
                'restaurante_id' => $restauranteId,
                'usuario_id' => auth()->id(), // Usar usuário logado se houver
                'numero_pedido_externo' => now()->format('YmdHis'),
                'plataforma_origem' => 'web',
                'data_hora_pedido' => now(),
                'status' => 'recebido', // Status inicial conforme enum da migration
                'valor_total' => $valorTotal,
                'tempo_preparo_estimado' => null,
            ]);

            foreach ($cartItems as $item) {
                $modelo = $availableItems[$item['id']];

                PedidoItem::create([
                    'pedido_id' => $pedido->id,
                    'cardapio_item_id' => $modelo->id,
                    'quantidade' => $item['quantidade'],
                    'preco_unitario' => $modelo->preco_venda,
                    'observacao' => null,
                ]);
            }

            DB::commit();
        } catch (\Throwable $exception) {
            DB::rollBack();
            report($exception);

            // Log mais detalhado do erro para debug
            \Log::error('Erro ao criar pedido no checkout público', [
                'exception' => $exception->getMessage(),
                'trace' => $exception->getTraceAsString(),
                'restaurante_id' => $restauranteId,
                'cart_items_count' => $cartItems->count(),
            ]);

            return redirect()->route('public.menu')->with('error', 'Não conseguimos registrar seu pedido. Tente novamente.');
        }

        PublicCart::clear();

        return redirect()->route('public.menu')->with('success', "Recebemos seu pedido! Código #{$pedido->id}.");
    }

    protected function publicRestauranteId(): ?int
    {
        static $restauranteId = null;

        if ($restauranteId !== null) {
            return $restauranteId;
        }

        $restauranteId = optional(Restaurante::first())->id;

        return $restauranteId;
    }
}


