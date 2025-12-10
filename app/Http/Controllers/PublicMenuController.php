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
        // Obter restaurante_id da sessão (se usuário logado) ou do parâmetro da URL
        $restauranteId = $request->get('restaurante_id') 
            ?? session('restaurante_id') 
            ?? null;

        // Se não houver restaurante_id, buscar todos (comportamento antigo para compatibilidade)
        // Mas idealmente deveria ter um restaurante_id sempre
        $query = CardapioItem::where('ativo_online', true);
        
        if ($restauranteId) {
            $query->where('restaurante_id', $restauranteId);
        }

        $itens = $query
            ->orderBy('categoria')
            ->orderBy('ordem')
            ->orderBy('nome')
            ->get()
            ->groupBy(fn ($item) => $item->categoria ?: 'Sugestões da casa');

        $cartItems = PublicCart::all();
        $suggestions = collect(); // Sugestões desabilitadas para todos os restaurantes

        // Buscar informações do restaurante se houver restaurante_id
        $restaurante = null;
        if ($restauranteId) {
            $restaurante = Restaurante::find($restauranteId);
        }

        return view('public.menu', [
            'restaurante' => $restaurante,
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


