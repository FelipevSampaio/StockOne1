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

        return redirect()->route('public.menu')->with('success', "{$item->nome} adicionado ao pedido.");
    }

    public function update(Request $request, int $cardapioItemId)
    {
        $data = $request->validate([
            'quantity' => ['required', 'integer', 'min:0', 'max:99'],
        ]);

        $quantity = $data['quantity'];

        $restauranteSlug = $request->route('restaurante');
        if ($quantity === 0) {
            PublicCart::remove($cardapioItemId);
            return redirect()->route('public.menu', ['restaurante' => $restauranteSlug])->with('success', 'Item removido do pedido.');
        }

        $item = CardapioItem::where('ativo_online', true)
            ->where('restaurante_id', $this->publicRestauranteId())
            ->find($cardapioItemId);

        if (!$item) {
            PublicCart::remove($cardapioItemId);
            return redirect()->route('public.menu', ['restaurante' => $restauranteSlug])->with('error', 'Este item não está mais disponível.');
        }

        PublicCart::refreshFromModel($item);
        PublicCart::updateQuantity($cardapioItemId, $quantity);

        return redirect()->route('public.menu', ['restaurante' => $restauranteSlug])->with('success', 'Quantidade atualizada.');
    }

    public function destroy(int $cardapioItemId)
    {
        $restauranteSlug = request()->route('restaurante');
        PublicCart::remove($cardapioItemId);

        return redirect()->route('public.menu', ['restaurante' => $restauranteSlug])->with('success', 'Item removido do pedido.');
    }

    public function checkout()
    {
        $restauranteSlug = request()->route('restaurante');
        
        // Priorizar restaurante da sessão se o usuário estiver logado
        $restauranteId = session('restaurante_id') ?? $this->publicRestauranteId();

        if (! $restauranteId) {
            return redirect()->route('public.menu', ['restaurante' => $restauranteSlug])->with('error', 'Restaurante indisponível no momento.');
        }

        if (PublicCart::isEmpty()) {
            return redirect()->route('public.menu', ['restaurante' => $restauranteSlug])->with('error', 'Seu carrinho está vazio.');
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
            return redirect()->route('public.menu', ['restaurante' => $restauranteSlug])->with('error', 'Atualizamos seu pedido: alguns itens ficaram indisponíveis.');
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
                'status' => 'pendente', // Mudar para pendente ao invés de concluido
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

            return redirect()->route('public.menu', ['restaurante' => $restauranteSlug])->with('error', 'Não conseguimos registrar seu pedido. Tente novamente.');
        }

        PublicCart::clear();

        return redirect()->route('public.menu', ['restaurante' => $restauranteSlug])->with('success', "Recebemos seu pedido! Código #{$pedido->id}.");
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


