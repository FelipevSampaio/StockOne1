<?php

namespace App\Http\Controllers;

use App\Models\Funcionario;
use Illuminate\Http\Request;

class FuncionarioController extends Controller
{
    public function index(Request $request)
    {
        $restauranteId = $this->restauranteId();
        $query = Funcionario::where('restaurante_id', $restauranteId);

        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('nome', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('cargo', 'like', "%{$search}%");
            });
        }
        if ($request->filled('status')) {
            $query->where('status', $request->get('status'));
        }
        $funcionarios = $query->orderBy('nome')->paginate(15);
        return view('funcionarios.index', compact('funcionarios'));
    }

    public function create()
    {
        return view('funcionarios.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nome' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:funcionarios,email'],
            'telefone' => ['nullable', 'string', 'max:20'],
            'cargo' => ['required', 'string', 'max:100'],
            'status' => ['required', 'in:ativo,inativo'],
            'data_admissao' => ['nullable', 'date'],
            'data_desligamento' => ['nullable', 'date'],
        ]);
        $data['restaurante_id'] = $this->restauranteId();
        Funcionario::create($data);
        return redirect()->route('funcionarios.index')->with('success', 'Funcionário cadastrado com sucesso.');
    }

    public function edit(Funcionario $funcionario)
    {
        $this->authorizeFuncionario($funcionario);
        return view('funcionarios.edit', compact('funcionario'));
    }

    public function update(Request $request, Funcionario $funcionario)
    {
        $this->authorizeFuncionario($funcionario);
        $data = $request->validate([
            'nome' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:funcionarios,email,' . $funcionario->id],
            'telefone' => ['nullable', 'string', 'max:20'],
            'cargo' => ['required', 'string', 'max:100'],
            'status' => ['required', 'in:ativo,inativo'],
            'data_admissao' => ['nullable', 'date'],
            'data_desligamento' => ['nullable', 'date'],
        ]);
        $funcionario->update($data);
        return redirect()->route('funcionarios.index')->with('success', 'Funcionário atualizado com sucesso.');
    }

    public function destroy(Funcionario $funcionario)
    {
        $this->authorizeFuncionario($funcionario);
        $funcionario->delete();
        return redirect()->route('funcionarios.index')->with('success', 'Funcionário removido com sucesso.');
    }

    protected function restauranteId(): int
    {
        return (int) session('restaurante_id');
    }

    protected function authorizeFuncionario(Funcionario $funcionario): void
    {
        abort_unless($funcionario->restaurante_id === $this->restauranteId(), 403);
    }
}
