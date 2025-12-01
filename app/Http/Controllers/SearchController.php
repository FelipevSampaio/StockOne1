<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Restaurante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SearchController extends Controller
{
    /**
     * Busca global no sistema
     */
    public function search(Request $request)
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            return response()->json(['error' => 'Não autorizado'], 403);
        }

        $query = $request->get('q', '');

        if (strlen($query) < 2) {
            return response()->json([
                'users' => [],
                'restaurantes' => [],
            ]);
        }

        // Buscar usuários
        $users = User::where('name', 'like', "%{$query}%")
            ->orWhere('email', 'like', "%{$query}%")
            ->limit(5)
            ->get(['id', 'name', 'email', 'role']);

        // Buscar restaurantes
        $restaurantes = Restaurante::where('nome', 'like', "%{$query}%")
            ->orWhere('cnpj', 'like', "%{$query}%")
            ->orWhere('email', 'like', "%{$query}%")
            ->limit(5)
            ->get(['id', 'nome', 'cnpj', 'email']);

        return response()->json([
            'users' => $users,
            'restaurantes' => $restaurantes,
        ]);
    }
}
