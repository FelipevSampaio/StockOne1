<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RecordUserLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();

            // Atualizar apenas se o último login foi há mais de 5 minutos
            // (para evitar atualizações excessivas no banco)
            if (!$user->last_login_at || $user->last_login_at->lt(now()->subMinutes(5))) {
                $user->recordLogin($request->ip());
            }
        }

        return $next($request);
    }
}
