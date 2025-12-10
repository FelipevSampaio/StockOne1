@extends('layouts.app')

@section('title', 'Receitas e composição')
@section('subtitle', 'Vincule insumos aos itens do cardápio')

@section('actions')
    <a href="{{ route('receitas.create') }}" class="rounded-full bg-red-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-red-500 transition-colors flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Nova receita
    </a>
@endsection

@section('content')
    <div class="space-y-6" x-data="receitasData()" x-init="init()">
        <!-- Estatísticas -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total de Receitas</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $stats['total'] }}</p>
                    </div>
                    <div class="w-12 h-12 bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Itens com Receita</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $stats['itens_com_receita'] }}</p>
                    </div>
                    <div class="w-12 h-12 bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Insumos Usados</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $stats['insumos_usados'] }}</p>
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
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Essenciais</p>
                        <p class="text-3xl font-bold text-red-600 dark:text-red-400 mt-2">{{ $stats['essenciais'] }}</p>
                    </div>
                    <div class="w-12 h-12 bg-red-100 dark:bg-red-900/30 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Custo Total</p>
                        <p class="text-2xl font-bold text-orange-600 dark:text-orange-400 mt-2">R$ {{ number_format($stats['custo_total'], 2, ',', '.') }}</p>
                    </div>
                    <div class="w-12 h-12 bg-orange-100 dark:bg-orange-900/30 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Margem Média</p>
                        <p class="text-2xl font-bold {{ $stats['margem_media'] >= 50 ? 'text-green-600 dark:text-green-400' : ($stats['margem_media'] >= 30 ? 'text-yellow-600 dark:text-yellow-400' : 'text-red-600 dark:text-red-400') }} mt-2">
                            {{ number_format($stats['margem_media'], 1) }}%
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
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
                <a href="{{ route('receitas.index', array_merge(request()->except(['essencial']))) }}" 
                   class="px-3 py-1.5 text-xs font-medium rounded-lg transition-colors {{ !request('essencial') ? 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }}">
                    Todos
                </a>
                <a href="{{ route('receitas.index', array_merge(request()->except(['essencial']), ['essencial' => 'sim'])) }}" 
                   class="px-3 py-1.5 text-xs font-medium rounded-lg transition-colors {{ request('essencial') === 'sim' ? 'bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }}">
                    ⭐ Essenciais
                </a>
                <a href="{{ route('receitas.index', array_merge(request()->except(['essencial']), ['essencial' => 'nao'])) }}" 
                   class="px-3 py-1.5 text-xs font-medium rounded-lg transition-colors {{ request('essencial') === 'nao' ? 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }}">
                    Opcionais
                </a>
            </div>

            <form method="GET" action="{{ route('receitas.index') }}" 
                  id="filter-form"
                  @submit.prevent="applyFilters()">
                <div class="flex flex-wrap items-center gap-3">
                <!-- Busca em Tempo Real -->
                <div class="relative flex-1 min-w-[250px]">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-5 w-5 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text"
                           name="search"
                           id="search-input"
                           x-model="searchQuery"
                           @input.debounce.500ms="applyFilters()"
                           placeholder="Buscar por item ou insumo..."
                           value="{{ request('search') }}"
                           class="pl-10 w-full px-4 py-2.5 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400">
                </div>

                <!-- Filtro por Item do Cardápio -->
                <select name="cardapio_item"
                        class="px-4 py-2.5 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                    <option value="">Todos os itens</option>
                    @foreach($cardapioItens as $item)
                        <option value="{{ $item->id }}" @selected(request('cardapio_item') == $item->id)>{{ $item->nome }}</option>
                    @endforeach
                </select>

                <!-- Filtro por Essencial -->
                <select name="essencial"
                        class="px-4 py-2.5 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                    <option value="">Todos</option>
                    <option value="sim" @selected(request('essencial') === 'sim')>Essenciais</option>
                    <option value="nao" @selected(request('essencial') === 'nao')>Não essenciais</option>
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
                <button type="button"
                        @click="applyFilters()"
                        class="px-5 py-2.5 bg-red-600 text-white text-sm font-semibold rounded-lg hover:bg-red-500 transition-colors shadow-sm">
                    Filtrar
                </button>

                @if(request()->hasAny(['search', 'cardapio_item', 'essencial']))
                    <a href="{{ route('receitas.index') }}"
                       class="px-5 py-2.5 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm font-semibold rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">
                        Limpar
                    </a>
                @endif
            </div>
        </form>
        </div>

        <!-- Modo de Visualização -->
        <div class="flex items-center justify-between bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4">
            <div class="flex items-center gap-3">
                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Visualização:</span>
                <button @click="viewMode = 'table'" 
                        :class="viewMode === 'table' ? 'bg-red-600 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300'"
                        class="px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                    Tabela
                </button>
                <button @click="viewMode = 'grouped'" 
                        :class="viewMode === 'grouped' ? 'bg-red-600 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300'"
                        class="px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                    Agrupado
                </button>
            </div>
            <div class="flex items-center gap-3">
                <label class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300 cursor-pointer">
                    <input type="checkbox" x-model="selectAll" @change="toggleSelectAll()" class="rounded border-gray-300 text-red-600 focus:ring-red-500">
                    Selecionar todos
                </label>
                <div x-show="selectedItems.length > 0" 
                     x-transition
                     class="flex items-center gap-2">
                    <span class="text-sm text-gray-700 dark:text-gray-300" x-text="`${selectedItems.length} selecionado(s)`"></span>
                    <button @click="bulkMarkEssential()" 
                            class="px-3 py-1.5 bg-yellow-600 text-white text-xs font-semibold rounded-lg hover:bg-yellow-500 transition-colors">
                        Marcar Essenciais
                    </button>
                    <button @click="bulkDelete()" 
                            class="px-3 py-1.5 bg-red-600 text-white text-xs font-semibold rounded-lg hover:bg-red-500 transition-colors">
                        Excluir
                    </button>
                </div>
            </div>
        </div>

        <!-- Visualização Agrupada -->
        <div x-show="viewMode === 'grouped'" 
             x-transition
             class="space-y-4">
            @foreach($receitasAgrupadas as $itemId => $receitasItem)
                @php
                    $item = $receitasItem->first()->cardapioItem;
                    $custoInfo = $custosPorItem[$itemId] ?? null;
                @endphp
                @if($item)
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden"
                     x-data="{ expanded: false }">
                    <div class="p-4 cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors"
                         @click="expanded = !expanded">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-4 flex-1">
                                <button class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-transform"
                                        :class="{ 'rotate-90': expanded }">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </button>
                                <div class="flex-1">
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $item->nome }}</h3>
                                    <div class="flex items-center gap-4 mt-1">
                                        <span class="text-sm text-gray-600 dark:text-gray-400">{{ $receitasItem->count() }} insumo(s)</span>
                                        @if($custoInfo)
                                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                                Custo: R$ {{ number_format($custoInfo['custo_total'], 2, ',', '.') }}
                                            </span>
                                            <span class="text-sm font-medium {{ $custoInfo['margem_lucro'] >= 50 ? 'text-green-600' : ($custoInfo['margem_lucro'] >= 30 ? 'text-yellow-600' : 'text-red-600') }}">
                                                Margem: {{ number_format($custoInfo['margem_lucro'], 1) }}%
                                            </span>
                                            @if($custoInfo['custo_total'] > $custoInfo['preco_venda'])
                                                <span class="px-2 py-1 bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 text-xs font-semibold rounded-full">
                                                    ⚠️ Custo > Preço
                                                </span>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="text-right">
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Preço de Venda</p>
                                    <p class="text-lg font-bold text-gray-900 dark:text-white">R$ {{ number_format($item->preco_venda ?? 0, 2, ',', '.') }}</p>
                                </div>
                                <button @click.stop="openDuplicateModal({{ $item->id }}, '{{ $item->nome }}')"
                                        class="px-3 py-1.5 bg-blue-600 text-white text-xs font-semibold rounded-lg hover:bg-blue-500 transition-colors flex items-center gap-1.5"
                                        title="Duplicar receitas para outro item">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                    </svg>
                                    Duplicar
                                </button>
                            </div>
                        </div>
                    </div>
                    <div x-show="expanded" 
                         x-transition
                         class="border-t border-gray-200 dark:border-gray-700">
                        <div class="p-4 space-y-2">
                            @foreach($receitasItem as $receita)
                                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-900/50 rounded-lg">
                                    <div class="flex items-center gap-3 flex-1">
                                        <input type="checkbox" 
                                               value="{{ $receita->id }}"
                                               x-model="selectedItems"
                                               class="rounded border-gray-300 text-red-600 focus:ring-red-500">
                                        <div class="flex-1">
                                            <p class="font-medium text-gray-900 dark:text-white">{{ $receita->insumo?->nome ?? 'Insumo removido' }}</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ number_format($receita->quantidade_necessaria, 2, ',', '.') }} {{ $receita->insumo?->unidade_medida ?? '' }}
                                                @if($receita->insumo && $receita->insumo->custo_unitario)
                                                    · R$ {{ $receita->insumo->custo_unitario < 0.01 ? number_format($receita->insumo->custo_unitario, 6, ',', '.') : number_format($receita->insumo->custo_unitario, 2, ',', '.') }}/{{ $receita->insumo->unidade_medida }}
                                                    · Subtotal: R$ {{ ($receita->quantidade_necessaria * $receita->insumo->custo_unitario) < 0.01 ? number_format($receita->quantidade_necessaria * $receita->insumo->custo_unitario, 6, ',', '.') : number_format($receita->quantidade_necessaria * $receita->insumo->custo_unitario, 2, ',', '.') }}
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        @if($receita->essencial)
                                            <span class="px-2 py-1 bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 text-xs font-semibold rounded-full">Essencial</span>
                                        @endif
                                        <a href="{{ route('receitas.edit', $receita) }}" 
                                           class="p-2 text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors"
                                           title="Editar">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </a>
                                        <button @click="showDetails({{ $receita->id }})"
                                                class="p-2 text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg transition-colors"
                                                title="Ver detalhes">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif
            @endforeach
        </div>

        <!-- Tabela -->
        <div x-show="viewMode === 'table'" 
             x-transition
             class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-900">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                <input type="checkbox" 
                                       x-model="selectAll" 
                                       @change="toggleSelectAll()"
                                       class="rounded border-gray-300 text-red-600 focus:ring-red-500">
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-800"
                                @click="sortBy('cardapio_item_id')">
                                Item do Cardápio
                                <svg class="w-4 h-4 inline ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/>
                                </svg>
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-800"
                                @click="sortBy('insumo_id')">
                                Insumo
                                <svg class="w-4 h-4 inline ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/>
                                </svg>
                            </th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-800"
                                @click="sortBy('quantidade_necessaria')">
                                Quantidade
                                <svg class="w-4 h-4 inline ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/>
                                </svg>
                            </th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Custo</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Status</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider min-w-[150px]">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse ($receitas as $receita)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors"
                                x-data="{ itemId: {{ $receita->id }} }">
                                <td class="px-4 py-4">
                                    <input type="checkbox" 
                                           :value="itemId"
                                           x-model="selectedItems"
                                           class="rounded border-gray-300 text-red-600 focus:ring-red-500">
                                </td>
                                <td class="px-4 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center flex-shrink-0">
                                            <svg class="w-5 h-5 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="font-semibold text-gray-900 dark:text-white">{{ $receita->cardapioItem?->nome ?? 'Item removido' }}</p>
                                            @if($receita->cardapioItem)
                                                <p class="text-xs text-gray-500 dark:text-gray-400">R$ {{ number_format($receita->cardapioItem->preco, 2, ',', '.') }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-4">
                                    <div>
                                        <p class="font-medium text-gray-900 dark:text-white">{{ $receita->insumo?->nome ?? 'Insumo removido' }}</p>
                                        @if($receita->insumo)
                                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $receita->insumo->categoria }}</p>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-4 py-4 text-center">
                                    <span class="text-lg font-bold text-gray-900 dark:text-white">{{ number_format($receita->quantidade_necessaria, 2, ',', '.') }}</span>
                                    @if($receita->insumo)
                                        <span class="text-xs text-gray-500 dark:text-gray-400 block mt-1">{{ $receita->insumo->unidade_medida }}</span>
                                    @endif
                                </td>
                                <td class="px-4 py-4 text-center">
                                    @if($receita->insumo && $receita->insumo->custo_unitario)
                                        <div>
                                            <p class="font-medium text-gray-900 dark:text-white">
                                                R$ {{ ($receita->quantidade_necessaria * $receita->insumo->custo_unitario) < 0.01 ? number_format($receita->quantidade_necessaria * $receita->insumo->custo_unitario, 6, ',', '.') : number_format($receita->quantidade_necessaria * $receita->insumo->custo_unitario, 2, ',', '.') }}
                                            </p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                                R$ {{ $receita->insumo->custo_unitario < 0.01 ? number_format($receita->insumo->custo_unitario, 6, ',', '.') : number_format($receita->insumo->custo_unitario, 2, ',', '.') }}/{{ $receita->insumo->unidade_medida }}
                                            </p>
                                        </div>
                                    @else
                                        <span class="text-sm text-gray-400 dark:text-gray-500">—</span>
                                    @endif
                                </td>
                                <td class="px-4 py-4 text-center">
                                    @if($receita->essencial)
                                        <span class="inline-flex items-center justify-center px-2.5 py-1 rounded-full text-xs font-semibold bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                            </svg>
                                            Essencial
                                        </span>
                                    @else
                                        <span class="inline-flex items-center justify-center px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                                            Opcional
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-4">
                                    <div class="flex items-center justify-end gap-2 flex-wrap">
                                        <button @click="showDetails({{ $receita->id }})"
                                                class="p-2 text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg transition-colors"
                                                title="Ver detalhes">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </button>
                                        <a href="{{ route('receitas.edit', $receita) }}"
                                           class="p-2 text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors"
                                           title="Editar">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </a>
                                        <form action="{{ route('receitas.destroy', $receita) }}" method="POST" data-confirm="Remover esta receita?" class="inline">
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
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    <p class="text-gray-500 dark:text-gray-400 font-medium">Nenhuma receita cadastrada.</p>
                                    <p class="text-sm text-gray-400 dark:text-gray-500 mt-1">Crie receitas para vincular insumos aos itens do cardápio!</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($receitas->hasPages())
            <div class="flex justify-center">
                {{ $receitas->links() }}
            </div>
        @endif

        <!-- Modal de Detalhes -->
        <div x-show="showModal" 
             @click.away="showModal = false"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm"
             style="display: none;"
             x-data="{ receita: null }">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto"
                 @click.stop>
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Detalhes da Receita</h3>
                        <button @click="showModal = false" 
                                class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                    <div x-html="receitaDetails" class="space-y-4"></div>
                </div>
            </div>
        </div>

        <!-- Modal de Duplicação -->
        <div x-show="showDuplicateModal"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm"
             style="display: none;"
             @click.self="showDuplicateModal = false">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl max-w-md w-full mx-4"
                 @click.stop>
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Duplicar Receitas</h3>
                        <button @click="showDuplicateModal = false" 
                                class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                    
                    <div class="space-y-4">
                        <div class="p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-800">
                            <p class="text-sm text-blue-800 dark:text-blue-300">
                                <span class="font-semibold">Origem:</span> <span x-text="duplicateItemOrigemNome"></span>
                            </p>
                            <p class="text-xs text-blue-600 dark:text-blue-400 mt-1">
                                Todas as receitas deste item serão copiadas para o item de destino.
                            </p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Item de Destino <span class="text-red-600">*</span>
                            </label>
                            <select x-model="duplicateItemDestinoId"
                                    class="w-full px-4 py-2.5 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                                <option value="">Selecione o item de destino...</option>
                                @foreach($cardapioItens as $item)
                                    <option value="{{ $item->id }}">{{ $item->nome }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="flex items-center gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                            <button @click="showDuplicateModal = false"
                                    class="flex-1 px-4 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                                Cancelar
                            </button>
                            <button @click="duplicateReceitas()"
                                    :disabled="!duplicateItemDestinoId"
                                    class="flex-1 px-4 py-2.5 text-sm font-semibold text-white bg-blue-600 rounded-lg hover:bg-blue-500 transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                </svg>
                                Duplicar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function receitasData() {
            return {
                viewMode: 'table',
                searchQuery: '{{ request('search') }}',
                selectedItems: [],
                selectAll: false,
                showModal: false,
                receitaDetails: '',
                sortField: '{{ request('sort', 'cardapio_item_id') }}',
                sortDirection: 'asc',
                showDuplicateModal: false,
                duplicateItemOrigemId: null,
                duplicateItemOrigemNome: '',
                duplicateItemDestinoId: '',

                init() {
                    // Carregar modo de visualização salvo
                    const savedMode = localStorage.getItem('receitas_view_mode');
                    if (savedMode) this.viewMode = savedMode;
                },

                applyFilters() {
                    const form = document.querySelector('form[method="GET"]');
                    if (form) {
                        const formData = new FormData(form);
                        formData.set('search', this.searchQuery);
                        const params = new URLSearchParams(formData);
                        window.location.href = '{{ route('receitas.index') }}?' + params.toString();
                    }
                },

                toggleSelectAll() {
                    if (this.selectAll) {
                        this.selectedItems = Array.from(document.querySelectorAll('input[type="checkbox"][value]'))
                            .map(cb => parseInt(cb.value));
                    } else {
                        this.selectedItems = [];
                    }
                },

                sortBy(field) {
                    this.sortField = field;
                    this.sortDirection = this.sortDirection === 'asc' ? 'desc' : 'asc';
                    window.location.href = '{{ route('receitas.index') }}?sort=' + field + '&direction=' + this.sortDirection;
                },

                showDetails(receitaId) {
                    // Buscar detalhes da receita via AJAX
                    fetch(`{{ url('/receitas') }}/${receitaId}/detalhes`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    })
                        .then(response => {
                            if (!response.ok) throw new Error('Erro ao buscar detalhes');
                            return response.json();
                        })
                        .then(data => {
                            this.receitaDetails = this.formatReceitaDetails(data);
                            this.showModal = true;
                        })
                        .catch(error => {
                            console.error('Erro ao buscar detalhes:', error);
                            alert('Erro ao carregar detalhes da receita');
                        });
                },

                formatReceitaDetails(data) {
                    return `
                        <div class="space-y-4">
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Item do Cardápio</p>
                                <p class="text-lg font-semibold text-gray-900 dark:text-white">${data.cardapio_item || 'N/A'}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Insumo</p>
                                <p class="text-lg font-semibold text-gray-900 dark:text-white">${data.insumo || 'N/A'}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Quantidade Necessária</p>
                                <p class="text-lg font-semibold text-gray-900 dark:text-white">${data.quantidade} ${data.unidade || ''}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Status</p>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold ${data.essencial ? 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400' : 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300'}">
                                    ${data.essencial ? 'Essencial' : 'Opcional'}
                                </span>
                            </div>
                            ${data.custo ? `
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Custo Unitário</p>
                                <p class="text-lg font-semibold text-gray-900 dark:text-white">R$ ${data.custo_unitario}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Custo Total</p>
                                <p class="text-lg font-semibold text-gray-900 dark:text-white">R$ ${data.custo}</p>
                            </div>
                            ` : ''}
                        </div>
                    `;
                },

                bulkMarkEssential() {
                    if (this.selectedItems.length === 0) return;
                    if (!confirm(`Marcar ${this.selectedItems.length} receita(s) como essenciais?`)) return;
                    
                    // Criar formulário para ação em lote
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '{{ route('receitas.index') }}/bulk-essential';
                    
                    const csrf = document.createElement('input');
                    csrf.type = 'hidden';
                    csrf.name = '_token';
                    csrf.value = '{{ csrf_token() }}';
                    form.appendChild(csrf);
                    
                    this.selectedItems.forEach(id => {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'ids[]';
                        input.value = id;
                        form.appendChild(input);
                    });
                    
                    document.body.appendChild(form);
                    // form.submit(); // Descomentar quando implementar a rota
                    alert('Funcionalidade em desenvolvimento');
                    document.body.removeChild(form);
                },

                bulkDelete() {
                    if (this.selectedItems.length === 0) return;
                    if (!confirm(`Excluir ${this.selectedItems.length} receita(s)? Esta ação não pode ser desfeita.`)) return;
                    
                    // Criar formulário para exclusão em lote
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '{{ route('receitas.index') }}/bulk-delete';
                    
                    const csrf = document.createElement('input');
                    csrf.type = 'hidden';
                    csrf.name = '_token';
                    csrf.value = '{{ csrf_token() }}';
                    form.appendChild(csrf);
                    
                    const method = document.createElement('input');
                    method.type = 'hidden';
                    method.name = '_method';
                    method.value = 'DELETE';
                    form.appendChild(method);
                    
                    this.selectedItems.forEach(id => {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'ids[]';
                        input.value = id;
                        form.appendChild(input);
                    });
                    
                    document.body.appendChild(form);
                    // form.submit(); // Descomentar quando implementar a rota
                    alert('Funcionalidade em desenvolvimento');
                    document.body.removeChild(form);
                },

                openDuplicateModal(itemId, itemNome) {
                    this.duplicateItemOrigemId = itemId;
                    this.duplicateItemOrigemNome = itemNome;
                    this.duplicateItemDestinoId = '';
                    this.showDuplicateModal = true;
                },

                async duplicateReceitas() {
                    if (!this.duplicateItemDestinoId) {
                        alert('Selecione o item de destino');
                        return;
                    }

                    if (this.duplicateItemOrigemId == this.duplicateItemDestinoId) {
                        alert('O item de destino deve ser diferente do item de origem');
                        return;
                    }

                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '{{ route('receitas.duplicate') }}';
                    
                    const csrf = document.createElement('input');
                    csrf.type = 'hidden';
                    csrf.name = '_token';
                    csrf.value = '{{ csrf_token() }}';
                    form.appendChild(csrf);
                    
                    const origem = document.createElement('input');
                    origem.type = 'hidden';
                    origem.name = 'item_origem_id';
                    origem.value = this.duplicateItemOrigemId;
                    form.appendChild(origem);
                    
                    const destino = document.createElement('input');
                    destino.type = 'hidden';
                    destino.name = 'item_destino_id';
                    destino.value = this.duplicateItemDestinoId;
                    form.appendChild(destino);
                    
                    document.body.appendChild(form);
                    form.submit();
                }
            }
        }

        // Salvar modo de visualização quando mudar
        document.addEventListener('DOMContentLoaded', () => {
            // Observar mudanças no viewMode usando MutationObserver ou eventos
            const observer = new MutationObserver(() => {
                const viewModeEl = document.querySelector('[x-data*="receitasData"]');
                if (viewModeEl && window.Alpine) {
                    const data = Alpine.$data(viewModeEl);
                    if (data && data.viewMode) {
                        localStorage.setItem('receitas_view_mode', data.viewMode);
                    }
                }
            });
            
            // Observar mudanças no DOM
            observer.observe(document.body, { childList: true, subtree: true });
        });
    </script>
@endsection

