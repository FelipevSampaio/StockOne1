<?php

namespace App\Support;

use App\Models\CardapioItem;
use Illuminate\Support\Collection;

class PublicCart
{
    private const SESSION_KEY = 'public_cart_items';

    public static function all(): Collection
    {
        return collect(session(self::SESSION_KEY, []));
    }

    public static function add(CardapioItem $item, int $quantity = 1): void
    {
        $items = self::all();

        // Verificar se o item já existe no carrinho
        if ($items->has($item->id)) {
            // Item já existe, incrementar quantidade
            $current = $items->get($item->id);
            $current['quantidade'] = min(99, $current['quantidade'] + max(1, $quantity));
            $current['preco'] = (float) $item->preco_venda;
            $current['categoria'] = $item->categoria;
            $current['imagem'] = $item->imagem;
        } else {
            // Item novo, criar entrada
            $current = [
                'id' => $item->id,
                'nome' => $item->nome,
                'categoria' => $item->categoria,
                'preco' => (float) $item->preco_venda,
                'quantidade' => max(1, $quantity),
                'imagem' => $item->imagem,
            ];
        }

        $items->put($item->id, $current);

        // Garantir que salvamos como array associativo preservando as chaves
        session([self::SESSION_KEY => $items->all()]);
    }

    public static function updateQuantity(int $itemId, int $quantity): void
    {
        $items = self::all();

        if ($quantity <= 0) {
            $items->forget($itemId);
        } elseif ($items->has($itemId)) {
            $item = $items->get($itemId);
            $item['quantidade'] = min(99, $quantity);
            $items->put($itemId, $item);
        }

        session([self::SESSION_KEY => $items->all()]);
    }

    public static function refreshFromModel(CardapioItem $item): void
    {
        $items = self::all();

        if (! $items->has($item->id)) {
            return;
        }

        $current = $items->get($item->id);
        $current['nome'] = $item->nome;
        $current['categoria'] = $item->categoria;
        $current['preco'] = (float) $item->preco_venda;
        $current['imagem'] = $item->imagem;

        $items->put($item->id, $current);

        session([self::SESSION_KEY => $items->all()]);
    }

    public static function remove(int $itemId): void
    {
        $items = self::all();
        $items->forget($itemId);
        session([self::SESSION_KEY => $items->all()]);
    }

    public static function removeMany(array $ids): void
    {
        if (empty($ids)) {
            return;
        }

        $items = self::all();
        foreach ($ids as $id) {
            $items->forget($id);
        }
        session([self::SESSION_KEY => $items->all()]);
    }

    public static function clear(): void
    {
        session()->forget(self::SESSION_KEY);
    }

    public static function itemsCount(): int
    {
        return self::all()->sum('quantidade');
    }

    public static function subtotal(): float
    {
        return round(
            self::all()->reduce(
                fn ($total, $item) => $total + ($item['preco'] * $item['quantidade']),
                0.0
            ),
            2
        );
    }

    public static function isEmpty(): bool
    {
        return self::all()->isEmpty();
    }
}


