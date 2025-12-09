@extends('layouts.app')

@section('title', 'Insumos')
@section('subtitle', 'Centralize matérias-primas de cada restaurante')

@section('actions')
    <a href="{{ route('insumos.create') }}" class="rounded-full bg-red-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-red-500 transition-colors flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Novo insumo
    </a>
@endsection

@section('content')
    <div class="space-y-6">
        <!-- Estatísticas -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total de Insumos</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $stats['total'] }}</p>
                    </div>
                    <div class="w-12 h-12 bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Estoque Baixo</p>
                        <p class="text-3xl font-bold text-red-600 dark:text-red-400 mt-2">{{ $stats['estoque_baixo'] }}</p>
                    </div>
                    <div class="w-12 h-12 bg-red-100 dark:bg-red-900/30 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Categorias</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $stats['categorias'] }}</p>
                    </div>
                    <div class="w-12 h-12 bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Valor Total</p>
                        <p class="text-3xl font-bold text-green-600 dark:text-green-400 mt-2">R$ {{ number_format($stats['valor_total'], 2, ',', '.') }}</p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtros -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4">
            <!-- Filtros Rápidos -->
            <div class="flex flex-wrap items-center gap-2 mb-4 pb-4 border-b border-gray-200 dark:border-gray-700">
                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Filtros rápidos:</span>
                <a href="{{ route('insumos.index', array_merge(request()->except(['estoque']))) }}" 
                   class="px-3 py-1.5 text-xs font-medium rounded-lg transition-colors {{ !request('estoque') ? 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }}">
                    Todos
                </a>
                <a href="{{ route('insumos.index', array_merge(request()->except(['estoque']), ['estoque' => 'baixo'])) }}" 
                   class="px-3 py-1.5 text-xs font-medium rounded-lg transition-colors {{ request('estoque') === 'baixo' ? 'bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }}">
                    ⚠️ Estoque Baixo
                </a>
                <a href="{{ route('insumos.index', array_merge(request()->except(['estoque']), ['estoque' => 'ok'])) }}" 
                   class="px-3 py-1.5 text-xs font-medium rounded-lg transition-colors {{ request('estoque') === 'ok' ? 'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }}">
                    ✅ Estoque OK
                </a>
            </div>

            <form method="GET" action="{{ route('insumos.index') }}" id="filter-form">
                <div class="flex flex-wrap items-center gap-3">
                    <!-- Busca -->
                    <div class="relative flex-1 min-w-[250px]">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-5 w-5 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <input type="text"
                               name="search"
                               id="search-input"
                               placeholder="Buscar por nome, descrição ou categoria..."
                               value="{{ request('search') }}"
                               class="pl-10 w-full px-4 py-2.5 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400">
                    </div>

                <!-- Filtro por Categoria -->
                <select name="categoria"
                        class="px-4 py-2.5 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                    <option value="">Todas as categorias</option>
                    @foreach($categorias as $categoria)
                        <option value="{{ $categoria }}" @selected(request('categoria') === $categoria)>{{ $categoria }}</option>
                    @endforeach
                </select>

                <!-- Filtro por Estoque -->
                <select name="estoque"
                        class="px-4 py-2.5 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                    <option value="">Todos os estoques</option>
                    <option value="baixo" @selected(request('estoque') === 'baixo')>⚠️ Estoque Baixo</option>
                    <option value="ok" @selected(request('estoque') === 'ok')>✅ Estoque OK</option>
                </select>

                <!-- Ordenação -->
                <select name="sort"
                        class="px-4 py-2.5 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                    <option value="nome" @selected(request('sort') === 'nome')>Nome</option>
                    <option value="estoque_baixo" @selected(request('sort') === 'estoque_baixo')>Estoque Baixo</option>
                    <option value="categoria" @selected(request('sort') === 'categoria')>Categoria</option>
                    <option value="created_at" @selected(request('sort') === 'created_at')>Data de cadastro</option>
                </select>

                <!-- Itens por página -->
                <select name="per_page"
                        class="px-4 py-2.5 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                    <option value="10" @selected(request('per_page') == 10)>10 por página</option>
                    <option value="15" @selected(request('per_page') == 15)>15 por página</option>
                    <option value="25" @selected(request('per_page') == 25)>25 por página</option>
                    <option value="50" @selected(request('per_page') == 50)>50 por página</option>
                </select>

                <!-- Botões -->
                <button type="submit"
                        class="px-5 py-2.5 bg-red-600 text-white text-sm font-semibold rounded-lg hover:bg-red-500 transition-colors shadow-sm">
                    Filtrar
                </button>

                @if(request()->hasAny(['search', 'categoria', 'estoque', 'sort']))
                    <a href="{{ route('insumos.index') }}"
                       class="px-5 py-2.5 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm font-semibold rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">
                        Limpar
                    </a>
                @endif
            </div>
        </form>
        </div>

        <!-- Tabela -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-900">
                        <tr>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-900 dark:text-white uppercase tracking-wider">Nome</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-900 dark:text-white uppercase tracking-wider">Categoria</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-900 dark:text-white uppercase tracking-wider">Unidade</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-900 dark:text-white uppercase tracking-wider">Estoque Atual</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-900 dark:text-white uppercase tracking-wider">Ponto Mínimo</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-900 dark:text-white uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3.5 text-right text-xs font-semibold text-gray-900 dark:text-white uppercase tracking-wider">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-800">
                        @forelse ($insumos as $insumo)
                            @php
                                $estoqueAtual = $insumo->estoque->sum('quantidade_atual') ?? 0;
                                $pontoMinimo = $insumo->ponto_reposicao_minimo ?? 0;
                                $estoqueBaixo = $pontoMinimo > 0 && $estoqueAtual <= $pontoMinimo;
                            @endphp
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-gray-200 dark:bg-gray-700 rounded-lg flex items-center justify-center text-gray-700 dark:text-gray-300 font-bold text-sm">
                                            {{ strtoupper(substr($insumo->nome, 0, 2)) }}
                                        </div>
                                        <div>
                                            <p class="font-semibold text-gray-900 dark:text-white">{{ $insumo->nome }}</p>
                                            @if($insumo->descricao)
                                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ Str::limit($insumo->descricao, 40) }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @if($insumo->categoria)
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                                            {{ $insumo->categoria }}
                                        </span>
                                    @else
                                        <span class="text-gray-400 dark:text-gray-600 text-sm">—</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                    {{ $insumo->unidade_medida }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-sm font-semibold text-gray-900 dark:text-white">
                                        {{ number_format($estoqueAtual, 2, ',', '.') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                    {{ $pontoMinimo > 0 ? number_format($pontoMinimo, 2, ',', '.') : '—' }}
                                </td>
                                <td class="px-6 py-4">
                                    @if($estoqueBaixo)
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400">
                                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                            </svg>
                                            Baixo
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                            </svg>
                                            OK
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('insumos.edit', $insumo) }}"
                                           class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                            Editar
                                        </a>
                                        <form action="{{ route('insumos.destroy', $insumo) }}" method="POST" data-confirm="Deseja remover este insumo?">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm font-medium text-red-600 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                                Excluir
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-16 text-center">
                                    <svg class="w-16 h-16 mx-auto text-gray-400 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                    </svg>
                                    <p class="text-gray-500 dark:text-gray-400 font-medium">Nenhum insumo encontrado.</p>
                                    <p class="text-sm text-gray-400 dark:text-gray-500 mt-1">Tente ajustar os filtros ou adicione um novo insumo.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($insumos->hasPages())
                <div class="border-t border-gray-200 dark:border-gray-700 px-6 py-4 bg-gray-50 dark:bg-gray-900">
                    {{ $insumos->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Busca em tempo real com debounce
        const searchInput = document.getElementById('search-input');
        const filterForm = document.getElementById('filter-form');
        let searchTimeout;

        if (searchInput && filterForm) {
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(function() {
                    filterForm.submit();
                }, 500); // Aguarda 500ms após parar de digitar
            });
        }
    });
</script>
@endsection


