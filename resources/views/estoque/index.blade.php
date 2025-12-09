@extends('layouts.app')

@section('title', 'Controle de Estoque')
@section('subtitle', 'Localização física e quantidades em estoque')

@section('actions')
    <a href="{{ route('estoque.create') }}" class="rounded-full bg-red-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-red-500 transition-colors flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Registrar estoque
    </a>
@endsection

@section('content')
    <div class="space-y-6">
        <!-- Estatísticas -->
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total de Itens</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $stats['total'] }}</p>
                    </div>
                    <div class="w-12 h-12 bg-red-100 dark:bg-red-900/30 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 {{ $stats['estoque_baixo'] > 0 ? 'ring-2 ring-red-200 dark:ring-red-900/50' : '' }}">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Estoque Baixo</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $stats['estoque_baixo'] }}</p>
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
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Valor Total</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">R$ {{ number_format($stats['valor_total'], 2, ',', '.') }}</p>
                    </div>
                    <div class="w-12 h-12 bg-red-100 dark:bg-red-900/30 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Localizações</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $stats['localizacoes'] }}</p>
                    </div>
                    <div class="w-12 h-12 bg-red-100 dark:bg-red-900/30 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
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
                <a href="{{ route('estoque.index', array_merge(request()->except(['nivel']))) }}" 
                   class="px-3 py-1.5 text-xs font-medium rounded-lg transition-colors {{ !request('nivel') ? 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }}">
                    Todos
                </a>
                <a href="{{ route('estoque.index', array_merge(request()->except(['nivel']), ['nivel' => 'baixo'])) }}" 
                   class="px-3 py-1.5 text-xs font-medium rounded-lg transition-colors {{ request('nivel') === 'baixo' ? 'bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }}">
                    ⚠️ Estoque Baixo
                </a>
                <a href="{{ route('estoque.index', array_merge(request()->except(['nivel']), ['nivel' => 'ok'])) }}" 
                   class="px-3 py-1.5 text-xs font-medium rounded-lg transition-colors {{ request('nivel') === 'ok' ? 'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }}">
                    ✅ Estoque OK
                </a>
            </div>

            <form method="GET" action="{{ route('estoque.index') }}" id="filter-form">
                <div class="flex flex-wrap items-center gap-3">
                    <!-- Busca -->
                    <div class="relative flex-1 min-w-[250px]">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-5 w-5 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <input type="text"
                               name="search"
                               id="search-input"
                               placeholder="Buscar por insumo ou localização..."
                               value="{{ request('search') }}"
                               class="pl-10 w-full px-4 py-2.5 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400">
                    </div>

                <!-- Filtro por Nível -->
                <select name="nivel"
                        class="px-4 py-2.5 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                    <option value="">Todos os níveis</option>
                    <option value="baixo" @selected(request('nivel') === 'baixo')>Estoque baixo</option>
                    <option value="ok" @selected(request('nivel') === 'ok')>Estoque OK</option>
                </select>

                <!-- Ordenação -->
                <select name="sort"
                        class="px-4 py-2.5 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                    <option value="updated_at" @selected(request('sort') === 'updated_at')>Mais recentes</option>
                    <option value="estoque_baixo" @selected(request('sort') === 'estoque_baixo')>Estoque Baixo</option>
                    <option value="insumo" @selected(request('sort') === 'insumo')>Nome do insumo</option>
                    <option value="quantidade_atual" @selected(request('sort') === 'quantidade_atual')>Quantidade</option>
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

                @if(request()->hasAny(['search', 'nivel', 'sort']))
                    <a href="{{ route('estoque.index') }}"
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
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Insumo</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Quantidade</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Ponto Mínimo</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Localização</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Valor</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Status</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider min-w-[150px]">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse ($estoques as $registro)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                <td class="px-4 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center flex-shrink-0">
                                            <span class="text-sm font-semibold text-gray-600 dark:text-gray-400">
                                                {{ strtoupper(substr($registro->insumo?->nome ?? 'N/A', 0, 2)) }}
                                            </span>
                                        </div>
                                        <div>
                                            <p class="font-semibold text-gray-900 dark:text-white">{{ $registro->insumo?->nome ?? 'Insumo removido' }}</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $registro->insumo?->categoria ?? '—' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-4">
                                    <div class="flex flex-col">
                                        <span class="text-lg font-bold text-gray-900 dark:text-white">{{ number_format($registro->quantidade_atual, 2, ',', '.') }}</span>
                                        <span class="text-xs text-gray-500 dark:text-gray-400">{{ $registro->insumo?->unidade_medida ?? '—' }}</span>
                                    </div>
                                </td>
                                <td class="px-4 py-4">
                                    @if($registro->insumo && $registro->insumo->ponto_reposicao_minimo)
                                        <div class="flex flex-col">
                                            <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ number_format($registro->insumo->ponto_reposicao_minimo, 2, ',', '.') }}</span>
                                            @php
                                                $percentual = ($registro->quantidade_atual / $registro->insumo->ponto_reposicao_minimo) * 100;
                                                $percentual = min(100, max(0, $percentual));
                                            @endphp
                                            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2 mt-1">
                                                <div class="h-2 rounded-full {{ $percentual <= 100 ? 'bg-red-500' : 'bg-green-500' }}" style="width: {{ min(100, $percentual) }}%"></div>
                                            </div>
                                            <span class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ number_format($percentual, 0) }}%</span>
                                        </div>
                                    @else
                                        <span class="text-sm text-gray-400 dark:text-gray-500">—</span>
                                    @endif
                                </td>
                                <td class="px-4 py-4">
                                    @if($registro->localizacao)
                                        <div class="flex items-center gap-2">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            </svg>
                                            <span class="text-sm text-gray-600 dark:text-gray-400">{{ $registro->localizacao }}</span>
                                        </div>
                                    @else
                                        <span class="text-sm text-gray-400 dark:text-gray-500">—</span>
                                    @endif
                                </td>
                                <td class="px-4 py-4">
                                    @if($registro->insumo && $registro->insumo->custo_unitario)
                                        <span class="text-sm font-semibold text-green-600 dark:text-green-400">
                                            R$ {{ number_format($registro->quantidade_atual * $registro->insumo->custo_unitario, 2, ',', '.') }}
                                        </span>
                                    @else
                                        <span class="text-sm text-gray-400 dark:text-gray-500">—</span>
                                    @endif
                                </td>
                                <td class="px-4 py-4">
                                    @if($registro->insumo && $registro->insumo->ponto_reposicao_minimo && $registro->quantidade_atual <= $registro->insumo->ponto_reposicao_minimo)
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400">
                                            Estoque baixo
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                                            OK
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-4">
                                    <div class="flex items-center justify-end gap-2 flex-wrap">
                                        <a href="{{ route('estoque.edit', $registro) }}"
                                           class="p-2 text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors"
                                           title="Editar">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </a>
                                        <form action="{{ route('estoque.destroy', $registro) }}" method="POST" data-confirm="Remover este registro de estoque?" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="p-2 text-red-600 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors"
                                                    title="Excluir">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-16 text-center">
                                    <svg class="w-16 h-16 mx-auto text-gray-400 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                    </svg>
                                    <p class="text-gray-500 dark:text-gray-400 font-medium">Nenhum registro de estoque encontrado.</p>
                                    <p class="text-sm text-gray-400 dark:text-gray-500 mt-1">Comece registrando seu primeiro item!</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($estoques->hasPages())
            <div class="flex justify-center">
                {{ $estoques->links() }}
            </div>
        @endif
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

