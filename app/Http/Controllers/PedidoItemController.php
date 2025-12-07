<?php

namespace App\Http\Controllers;

use App\Models\CardapioItem;
use App\Models\Pedido;
use App\Models\PedidoItem;
use Illuminate\Http\Request;
class PedidoItemController extends Controller
{
    public function index()
    {
        $restauranteId = $this->restauranteId();

        $itens = PedidoItem::with(['pedido', 'cardapioItem'])
            ->whereHas('pedido', fn ($query) => $query->where('restaurante_id', $restauranteId))
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('pedido_itens.index', compact('itens'));
    }

    public function create()
    {
        [$pedidos, $cardapioItens] = $this->formOptions();

        return view('pedido_itens.create', compact('pedidos', 'cardapioItens'));
    }

    public function store(Request $request)
    {
        $restauranteId = $this->restauranteId();

        $data = $request->validate([
            'pedido_id' => ['required', 'exists:pedidos,id'],
            'cardapio_item_id' => ['required', 'exists:cardapio_itens,id'],
            'quantidade' => ['required', 'integer', 'min:1'],
            'preco_unitario' => ['required', 'numeric'],
            'observacao' => ['nullable', 'string'],
        ]);

        $this->ensurePedidoPertenceRestaurante($data['pedido_id'], $restauranteId);
        $this->ensureCardapioPertenceRestaurante($data['cardapio_item_id'], $restauranteId);

        $pedidoItem = PedidoItem::create($data);

        // Baixa automática de insumos conforme receitas do item do cardápio
        $cardapioItem = CardapioItem::find($data['cardapio_item_id']);
        $quantidadePedido = $data['quantidade'];
        if ($cardapioItem) {
            foreach ($cardapioItem->receitas as $receita) {
                $insumo = $receita->insumo;
                if ($insumo && $insumo->estoque) {
                    // Calcula quantidade total a baixar
                    $quantidadeBaixar = $receita->quantidade_necessaria * $quantidadePedido;
                    $estoque = $insumo->estoque;
                    $estoque->quantidade_atual = max(0, $estoque->quantidade_atual - $quantidadeBaixar);
                    $estoque->save();
                    // Verifica e gera alerta se necessário
                    $insumo->verificarBaixoEstoqueEAlertar();
                }
            }
        }

        return redirect()->route('pedido-itens.index')->with('success', 'Item adicionado ao pedido com sucesso e insumos baixados do estoque.');
    }

    public function edit(PedidoItem $pedidoItem)
    {
        $this->authorizePedidoItem($pedidoItem);

        [$pedidos, $cardapioItens] = $this->formOptions();

        return view('pedido_itens.edit', compact('pedidoItem', 'pedidos', 'cardapioItens'));
    }

    public function update(Request $request, PedidoItem $pedidoItem)
    {
        $this->authorizePedidoItem($pedidoItem);

        $restauranteId = $this->restauranteId();

        $data = $request->validate([
            'pedido_id' => ['required', 'exists:pedidos,id'],
            'cardapio_item_id' => ['required', 'exists:cardapio_itens,id'],
            'quantidade' => ['required', 'integer', 'min:1'],
            'preco_unitario' => ['required', 'numeric'],
            'observacao' => ['nullable', 'string'],
        ]);

        $this->ensurePedidoPertenceRestaurante($data['pedido_id'], $restauranteId);
        $this->ensureCardapioPertenceRestaurante($data['cardapio_item_id'], $restauranteId);

        $pedidoItem->update($data);

        return redirect()->route('pedido-itens.index')->with('success', 'Item do pedido atualizado com sucesso.');
    }

    public function destroy(PedidoItem $pedidoItem)
    {
        $this->authorizePedidoItem($pedidoItem);

        $pedidoItem->delete();

        return redirect()->route('pedido-itens.index')->with('success', 'Item do pedido removido com sucesso.');
    }

    protected function restauranteId(): int
    {
        return (int) session('restaurante_id');
    }

    protected function authorizePedidoItem(PedidoItem $pedidoItem): void
    {
        abort_unless(
            optional($pedidoItem->pedido)->restaurante_id === $this->restauranteId(),
            403
        );
    }

    protected function formOptions(): array
    {
        $restauranteId = $this->restauranteId();

        $pedidos = Pedido::where('restaurante_id', $restauranteId)
            ->orderByDesc('data_hora_pedido')
            ->get()
            ->mapWithKeys(function ($pedido) {
                $numero = $pedido->numero_pedido_externo ? '· ' . $pedido->numero_pedido_externo : '';
                $label = sprintf('#%s %s', $pedido->id, $numero);

                return [$pedido->id => trim($label)];
            });

        $cardapioItens = CardapioItem::where('restaurante_id', $restauranteId)
            ->orderBy('nome')
            ->pluck('nome', 'id');

        return [$pedidos, $cardapioItens];
    }

    protected function ensurePedidoPertenceRestaurante(int $pedidoId, int $restauranteId): void
    {
        abort_unless(
            Pedido::where('id', $pedidoId)->where('restaurante_id', $restauranteId)->exists(),
            403
        );
    }

    protected function ensureCardapioPertenceRestaurante(int $cardapioItemId, int $restauranteId): void
    {
        abort_unless(
            CardapioItem::where('id', $cardapioItemId)->where('restaurante_id', $restauranteId)->exists(),
            403
        );
    }
}
