<?php

namespace App\Http\Controllers;

use App\Models\CardapioItem;
use App\Models\Restaurante;
use App\Support\PublicCart;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class PublicMenuController extends Controller
{
    public function index(Request $request)
    {
        $itens = CardapioItem::where('ativo_online', true)
            ->orderBy('categoria')
            ->orderBy('nome')
            ->get()
            ->groupBy(fn ($item) => $item->categoria ?: 'Sugestões da casa');

        $cartItems = PublicCart::all();
        $suggestions = collect(); // Sugestões desabilitadas para todos os restaurantes

        return view('public.menu', [
            'restaurante' => null,
            'categorias' => $itens,
            'cartItems' => $cartItems,
            'cartTotal' => PublicCart::subtotal(),
            'cartCount' => PublicCart::itemsCount(),
            'suggestions' => $suggestions,
        ]);
    }

    protected function buildSuggestions(int $restauranteId, Collection $cartItems): Collection
    {
        $cartCategories = $cartItems
            ->pluck('categoria')
            ->filter()
            ->unique()
            ->values();

        $query = CardapioItem::where('restaurante_id', $restauranteId)
            ->where('ativo_online', true)
            ->whereNotIn('id', $cartItems->pluck('id')->all());

        if ($cartCategories->isNotEmpty()) {
            $query->whereIn('categoria', $cartCategories->all());
        }

        return $query
            ->orderByDesc('updated_at')
            ->limit(4)
            ->get();
    }
}


