@extends('layouts.app')

@section('title', 'Editar Funcionário')
@section('subtitle', 'Alterar dados do funcionário')

@section('content')
    <form action="{{ route('funcionarios.update', $funcionario) }}" method="POST" class="space-y-6 bg-white rounded-xl shadow-sm p-8">
        @csrf
        @method('PUT')
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium">Nome *</label>
                <input type="text" name="nome" value="{{ old('nome', $funcionario->nome) }}" required class="w-full rounded-lg border-gray-300 px-3 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium">E-mail *</label>
                <input type="email" name="email" value="{{ old('email', $funcionario->email) }}" required class="w-full rounded-lg border-gray-300 px-3 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium">Telefone</label>
                <input type="text" name="telefone" value="{{ old('telefone', $funcionario->telefone) }}" class="w-full rounded-lg border-gray-300 px-3 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium">Cargo *</label>
                <input type="text" name="cargo" value="{{ old('cargo', $funcionario->cargo) }}" required class="w-full rounded-lg border-gray-300 px-3 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium">Status *</label>
                <select name="status" required class="w-full rounded-lg border-gray-300 px-3 py-2">
                    <option value="ativo" @selected(old('status', $funcionario->status)=='ativo')>Ativo</option>
                    <option value="inativo" @selected(old('status', $funcionario->status)=='inativo')>Inativo</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium">Data de Admissão</label>
                <input type="date" name="data_admissao" value="{{ old('data_admissao', $funcionario->data_admissao?->format('Y-m-d')) }}" class="w-full rounded-lg border-gray-300 px-3 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium">Data de Desligamento</label>
                <input type="date" name="data_desligamento" value="{{ old('data_desligamento', $funcionario->data_desligamento?->format('Y-m-d')) }}" class="w-full rounded-lg border-gray-300 px-3 py-2">
            </div>
        </div>
        <div class="flex justify-end gap-3">
            <a href="{{ route('funcionarios.index') }}" class="rounded-lg border border-gray-300 px-5 py-2 text-sm text-gray-600 hover:text-red-600">Cancelar</a>
            <button type="submit" class="rounded-lg bg-green-600 px-5 py-2 text-sm font-semibold text-white shadow hover:bg-green-500">Salvar</button>
        </div>
    </form>
@endsection
