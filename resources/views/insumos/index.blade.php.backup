@extends('layouts.app')

@section('title', 'Insumos')
@section('subtitle', 'Centralize matérias-primas de cada restaurante')

@section('actions')
    <a href="{{ route('insumos.create') }}" class="rounded-full bg-red-600 px-5 py-2 text-sm font-semibold text-white shadow hover:bg-red-500">
        Novo insumo
    </a>
@endsection

@section('content')
    <div class="rounded-2xl border border-gray-100 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-red-50 text-left text-xs font-semibold uppercase tracking-wide text-red-600">
                    <tr>
                        <th class="px-6 py-3">Nome</th>
                        <th class="px-6 py-3">Restaurante</th>
                        <th class="px-6 py-3">Unidade</th>
                        <th class="px-6 py-3">Ponto de reposição</th>
                        <th class="px-6 py-3 text-right">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($insumos as $insumo)
                        <tr>
                            <td class="px-6 py-4 font-semibold text-gray-900">{{ $insumo->nome }}</td>
                            <td class="px-6 py-4 text-gray-600">{{ $insumo->restaurante?->nome ?? '—' }}</td>
                            <td class="px-6 py-4 text-gray-500">{{ $insumo->unidade_medida }}</td>
                            <td class="px-6 py-4 text-gray-500">{{ $insumo->ponto_reposicao_minimo ?? '—' }}</td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('insumos.edit', $insumo) }}" class="text-sm font-semibold text-red-600 hover:text-red-800">
                                        Editar
                                    </a>
                                    <form action="{{ route('insumos.destroy', $insumo) }}" method="POST" data-confirm="Deseja remover este insumo?">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-sm text-gray-500 hover:text-red-700">Excluir</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                Nenhum insumo cadastrado.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="border-t border-gray-100 px-6 py-4">
            {{ $insumos->links() }}
        </div>
    </div>
@endsection

