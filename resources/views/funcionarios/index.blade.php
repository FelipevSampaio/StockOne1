@extends('layouts.app')

@section('title', 'Funcionários')
@section('subtitle', 'Gestão de funcionários do restaurante')

@section('content')
    <div class="mb-6 flex justify-between items-center">
        <form method="GET" class="flex gap-2">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar por nome, e-mail ou cargo" class="rounded-lg border-gray-300 px-3 py-2 text-sm">
            <select name="status" class="rounded-lg border-gray-300 px-3 py-2 text-sm">
                <option value="">Todos</option>
                <option value="ativo" @selected(request('status')=='ativo')>Ativo</option>
                <option value="inativo" @selected(request('status')=='inativo')>Inativo</option>
            </select>
            <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg">Filtrar</button>
        </form>
        <a href="{{ route('funcionarios.create') }}" class="bg-green-600 text-white px-4 py-2 rounded-lg">Novo Funcionário</a>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-6">
        <table class="min-w-full divide-y divide-gray-200">
            <thead>
                <tr>
                    <th class="px-4 py-2 text-left text-xs font-semibold">Nome</th>
                    <th class="px-4 py-2 text-left text-xs font-semibold">E-mail</th>
                    <th class="px-4 py-2 text-left text-xs font-semibold">Cargo</th>
                    <th class="px-4 py-2 text-left text-xs font-semibold">Status</th>
                    <th class="px-4 py-2 text-left text-xs font-semibold">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($funcionarios as $funcionario)
                    <tr>
                        <td class="px-4 py-2">{{ $funcionario->nome }}</td>
                        <td class="px-4 py-2">{{ $funcionario->email }}</td>
                        <td class="px-4 py-2">{{ $funcionario->cargo }}</td>
                        <td class="px-4 py-2">{{ ucfirst($funcionario->status) }}</td>
                        <td class="px-4 py-2 flex gap-2">
                            <a href="{{ route('funcionarios.edit', $funcionario) }}" class="text-blue-600">Editar</a>
                            <form action="{{ route('funcionarios.destroy', $funcionario) }}" method="POST" onsubmit="return confirm('Confirma excluir?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600">Excluir</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-2 text-center text-gray-500">Nenhum funcionário encontrado.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="mt-4">{{ $funcionarios->links() }}</div>
    </div>
@endsection
