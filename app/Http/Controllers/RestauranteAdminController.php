<?php

namespace App\Http\Controllers;

use App\Models\Restaurante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RestauranteAdminController extends Controller
{
    private function checkAdmin()
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403, 'Acesso negado.');
        }
    }

    public function index()
    {
        $this->checkAdmin();
        $restaurantes = Restaurante::paginate(15);
        return view('admin.restaurantes.index', compact('restaurantes'));
    }

    public function create()
    {
        $this->checkAdmin();
        return view('admin.restaurantes.create');
    }

    public function store(Request $request)
    {
        $this->checkAdmin();
        $validated = $request->validate([
            'nome' => ['required', 'string', 'max:255'],
            'cnpj' => ['required', 'string', 'max:18', 'unique:restaurantes'],
            'email' => ['required', 'email', 'unique:restaurantes'],
            'endereco' => ['nullable', 'string'],
            'telefone' => ['nullable', 'string', 'max:20'],
            'status' => ['required', 'in:ativo,inativo'],
        ]);

        Restaurante::create($validated);

        return redirect()->route('admin.restaurantes.index')
            ->with('success', 'Restaurante criado com sucesso!');
    }

    public function edit(Restaurante $restaurante)
    {
        $this->checkAdmin();
        return view('admin.restaurantes.edit', compact('restaurante'));
    }

    public function update(Request $request, Restaurante $restaurante)
    {
        $this->checkAdmin();
        $validated = $request->validate([
            'nome' => ['required', 'string', 'max:255'],
            'cnpj' => ['required', 'string', 'max:18', 'unique:restaurantes,cnpj,' . $restaurante->id],
            'email' => ['required', 'email', 'unique:restaurantes,email,' . $restaurante->id],
            'endereco' => ['nullable', 'string'],
            'telefone' => ['nullable', 'string', 'max:20'],
            'status' => ['required', 'in:ativo,inativo'],
        ]);

        $restaurante->update($validated);

        return redirect()->route('admin.restaurantes.index')
            ->with('success', 'Restaurante atualizado com sucesso!');
    }

    public function destroy(Restaurante $restaurante)
    {
        $this->checkAdmin();
        $restaurante->delete();

        return redirect()->route('admin.restaurantes.index')
            ->with('success', 'Restaurante deletado com sucesso!');
    }
}

