<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            // Se é admin, vai para o painel de admin
            if (Auth::user()->isAdmin()) {
                return redirect()->route('admin.users.index');
            }
            return redirect()->route('dashboard');
        }

        return view('auth.login');
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Adicionar informações do restaurante na sessão
            $user = Auth::user();

            // Se é admin, redirecionar para o painel de admin
            if ($user->isAdmin()) {
                return redirect()->intended(route('admin.users.index'));
            }

            // Verificar se o usuário tem restaurante vinculado
            if (!$user->restaurante_id || !$user->restaurante) {
                Auth::logout();
                return back()
                    ->withErrors([
                        'email' => 'Sua conta não está vinculada a nenhum restaurante.'
                    ])
                    ->with('error_details', 'Entre em contato com o administrador do sistema para vincular sua conta a um restaurante.')
                    ->onlyInput('email');
            }

            // Verificar se o restaurante está ativo
            if ($user->restaurante->status !== 'ativo') {
                $restauranteName = $user->restaurante->nome;
                Auth::logout();
                return back()
                    ->withErrors([
                        'email' => "O restaurante '{$restauranteName}' está temporariamente desativado."
                    ])
                    ->with('error_details', 'Acesso bloqueado. Entre em contato com o administrador do sistema para reativar o acesso.')
                    ->with('error_type', 'restaurante_desativado')
                    ->onlyInput('email');
            }

            $request->session()->put('restaurante_id', $user->restaurante_id);
            $request->session()->put('restaurante_nome', $user->restaurante->nome);
            $request->session()->put('restaurante_cnpj', $user->restaurante->cnpj);

            return redirect()->intended(route('dashboard'));
        }

        return back()->withErrors([
            'email' => 'As credenciais fornecidas não são válidas.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('auth.login')->with('success', 'Sessão encerrada.');
    }
}
