<?php

namespace App\Http\Middleware;

use App\Models\Restaurante;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureRestauranteSession
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Se for admin, permite o acesso
        if ($request->user() && $request->user()->isAdmin()) {
            return $next($request);
        }

        // Verifica se tem restaurante_id na sessão
        if (!$request->session()->has('restaurante_id')) {
            return redirect()
                ->route('auth.login')
                ->with('error', 'Faça login para acessar o painel.');
        }

        // Verifica se o restaurante está ativo
        $restauranteId = $request->session()->get('restaurante_id');
        $restaurante = Restaurante::find($restauranteId);

        if (!$restaurante) {
            // Restaurante não encontrado - limpa sessão
            $request->session()->forget(['restaurante_id', 'restaurante_nome']);

            return redirect()
                ->route('auth.login')
                ->with('error', 'Restaurante não encontrado.');
        }

        if ($restaurante->status !== 'ativo') {
            // Restaurante desativado - limpa sessão e faz logout
            $request->session()->forget(['restaurante_id', 'restaurante_nome']);
            auth()->logout();

            return redirect()
                ->route('auth.login')
                ->with('error', 'Este restaurante está desativado. Entre em contato com o administrador.');
        }

        return $next($request);
    }
}
