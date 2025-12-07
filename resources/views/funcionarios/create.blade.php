@extends('layouts.app')

@section('title', 'Novo Funcionário')
@section('subtitle', 'Cadastro de funcionário')

@section('content')
    <form action="{{ route('funcionarios.store') }}" method="POST" class="space-y-6 bg-white rounded-xl shadow-sm p-8">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium">Nome *</label>
                <input type="text" name="nome" value="{{ old('nome') }}" required class="w-full rounded-lg border-gray-300 px-3 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium">E-mail *</label>
                <input type="email" name="email" value="{{ old('email') }}" required class="w-full rounded-lg border-gray-300 px-3 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium">Telefone</label>
                <input type="text" name="telefone" value="{{ old('telefone') }}" class="w-full rounded-lg border-gray-300 px-3 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium">Cargo *</label>
                <input type="text" name="cargo" value="{{ old('cargo') }}" required class="w-full rounded-lg border-gray-300 px-3 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium">Status *</label>
                <select name="status" required class="w-full rounded-lg border-gray-300 px-3 py-2">
                    <option value="ativo" @selected(old('status')=='ativo')>Ativo</option>
                    <option value="inativo" @selected(old('status')=='inativo')>Inativo</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium">Data de Admissão</label>
                <input type="date" name="data_admissao" value="{{ old('data_admissao') }}" class="w-full rounded-lg border-gray-300 px-3 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium">Data de Desligamento</label>
                <input type="date" name="data_desligamento" value="{{ old('data_desligamento') }}" class="w-full rounded-lg border-gray-300 px-3 py-2">
            </div>
        </div>
        <div class="flex justify-end gap-3">
            <a href="{{ route('funcionarios.index') }}" class="rounded-lg border border-gray-300 px-5 py-2 text-sm text-gray-600 hover:text-red-600">Cancelar</a>
            <button type="submit" class="rounded-lg bg-green-600 px-5 py-2 text-sm font-semibold text-white shadow hover:bg-green-500">Salvar</button>
        </div>
    </form>
@endsection
