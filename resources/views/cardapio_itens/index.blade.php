@extends('layouts.app')

@section('title', 'Itens de cardápio')
@section('subtitle', 'Estruture o menu digital do restaurante')

@section('actions')
    <a href="{{ route('cardapio-itens.create') }}" class="rounded-full bg-red-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-red-500 transition-colors flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Novo item
    </a>
@endsection

@section('content')
    <div class="space-y-6">
        <!-- Estatísticas -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total de Itens</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $stats['total'] }}</p>
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
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Online</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $stats['online'] }}</p>
                    </div>
                    <div class="w-12 h-12 bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Offline</p>
                        <p class="text-3xl font-bold text-red-600 dark:text-red-400 mt-2">{{ $stats['offline'] }}</p>
                    </div>
                    <div class="w-12 h-12 bg-red-100 dark:bg-red-900/30 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
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
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Receita Total</p>
                        <p class="text-3xl font-bold text-green-600 dark:text-green-400 mt-2">R$ {{ number_format($stats['receita_total'], 2, ',', '.') }}</p>
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
                <a href="{{ route('cardapio-itens.index', array_merge(request()->all(), ['status' => 'offline'])) }}"
                   class="px-3 py-1.5 text-xs font-medium rounded-lg transition-colors {{ request('status') === 'offline' ? 'bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }}">
                    ❌ Offline
                </a>
                <a href="{{ route('cardapio-itens.index', array_merge(request()->except(['status']), ['status' => 'all'])) }}"
                   class="px-3 py-1.5 text-xs font-medium rounded-lg transition-colors {{ request('status') === 'all' ? 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }}">
                    Todos
                </a>
                <a href="{{ route('cardapio-itens.index', array_merge(request()->except(['status']))) }}"
                   class="px-3 py-1.5 text-xs font-medium rounded-lg transition-colors {{ !request()->has('status') || request('status') === 'online' ? 'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }}">
                    ✅ Online
                </a>
                <div class="ml-auto flex items-center gap-2">
                    <span class="text-sm text-gray-600 dark:text-gray-400">Visualização:</span>
                    <button onclick="setViewMode('grid')"
                            id="btn-grid"
                            class="p-2 rounded-lg transition-colors bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                        </svg>
                    </button>
                    <button onclick="setViewMode('list')"
                            id="btn-list"
                            class="p-2 rounded-lg transition-colors bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                </div>
            </div>

            <form method="GET" action="{{ route('cardapio-itens.index') }}" id="filter-form">
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

                <!-- Filtro por Status -->
                <select name="status"
                        class="px-4 py-2.5 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                    <option value="all" @selected(request('status') === 'all')>Todos os status</option>
                    <option value="online" @selected(request('status') === 'online' || !request()->has('status'))>✅ Online</option>
                    <option value="offline" @selected(request('status') === 'offline')>❌ Offline</option>
                </select>

                <!-- Ordenação -->
                <select name="sort"
                        class="px-4 py-2.5 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                    <option value="nome" @selected(request('sort') === 'nome')>Nome</option>
                    <option value="preco_venda" @selected(request('sort') === 'preco_venda')>Preço</option>
                    <option value="vendas" @selected(request('sort') === 'vendas')>Mais Vendidos</option>
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

                @if(request()->hasAny(['search', 'categoria', 'status', 'sort']))
                    <a href="{{ route('cardapio-itens.index') }}"
                       class="px-5 py-2.5 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm font-semibold rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">
                        Limpar
                    </a>
                @endif
            </div>
        </form>
        </div>

        <!-- Visualização Agrupada por Categoria com Drag-and-Drop -->
        <div id="grid-view" class="space-y-8" style="display: block;">
            @php
                $itensAgrupados = $itensAgrupados ?? collect();
            @endphp
            @forelse($itensAgrupados as $categoria => $itensCategoria)
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <div class="flex items-center justify-between mb-4 pb-4 border-b border-gray-200 dark:border-gray-700">
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                            <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                            </svg>
                            {{ $categoria ?: 'Sem Categoria' }}
                        </h2>
                        <span class="px-3 py-1 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm font-semibold rounded-full">
                            {{ $itensCategoria->count() }} item(ns)
                        </span>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 sortable-categoria" 
                         data-categoria="{{ $categoria ?: 'sem-categoria' }}">
                        @foreach($itensCategoria as $item)
                @php
                    $ctrl = app(\App\Http\Controllers\CardapioItemController::class);
                    $disp = $ctrl->verificarDisponibilidadeEsubstituicoes($item);
                @endphp
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden hover:shadow-md transition-shadow {{ !$item->ativo_online ? 'opacity-75' : '' }} sortable-item group relative"
                     data-item-id="{{ $item->id }}">
                    <!-- Handle para arrastar -->
                    <div class="sortable-handle absolute top-2 left-2 z-20 p-2 bg-gray-200 dark:bg-gray-700 rounded-lg opacity-70 group-hover:opacity-100 transition-opacity cursor-move hover:bg-gray-300 dark:hover:bg-gray-600" 
                         title="Arraste para reordenar">
                        <svg class="w-5 h-5 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/>
                        </svg>
                    </div>
                    <!-- Imagem -->
                    <div class="relative h-48 bg-gray-100 dark:bg-gray-700 {{ !$item->ativo_online ? 'grayscale' : '' }}">
                        @if ($item->imagem)
                            <img src="{{ asset('storage/' . $item->imagem) }}" alt="{{ $item->nome }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center">
                                <svg class="w-16 h-16 text-gray-400 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                        @endif

                        <!-- Badge de Status -->
                        <div class="absolute top-3 right-3">
                            @if($item->ativo_online)
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    Ativo
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                    </svg>
                                    Desativado
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Conteúdo -->
                    <div class="p-4">
                        <div class="mb-3">
                            @if($item->categoria)
                                <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                                    {{ $item->categoria }}
                                </span>
                            @endif
                        </div>

                        <h3 class="font-semibold text-gray-900 dark:text-white text-lg mb-2">{{ $item->nome }}</h3>

                        @if($item->descricao)
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-3 line-clamp-2">{{ $item->descricao }}</p>
                        @endif

                        <!-- Disponibilidade -->
                        @if(!$disp['disponivel'])
                            <div class="mb-2">
                                <span class="inline-block px-2 py-1 rounded bg-red-100 text-red-700 text-xs font-semibold">Indisponível: falta insumo essencial</span>
                                @if(count($disp['substituicoes']))
                                    <ul class="mt-1 text-xs text-gray-700">
                                        @foreach($disp['substituicoes'] as $sub)
                                            <li>Substitua <b>{{ $sub['insumo_faltante'] }}</b> por <b>{{ $sub['substituto'] }}</b> ({{ $sub['categoria'] }})</li>
                                        @endforeach
                                    </ul>
                                @else
                                    <span class="block text-xs text-gray-500">Nenhuma substituição automática disponível.</span>
                                @endif
                            </div>
                        @endif

                        <!-- Estatísticas de Vendas -->
                        @if($item->total_vendido > 0)
                            <div class="mb-2 flex items-center gap-3 text-xs text-gray-600 dark:text-gray-400">
                                <span class="flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                                    </svg>
                                    {{ $item->total_vendido }} vendas
                                </span>
                            </div>
                        @endif

                        @php
                            $custoTotal = $item->calcularCustoTotal();
                            $margemLucro = $item->calcularMargemLucro();
                            $lucro = $item->calcularLucro();
                        @endphp
                        
                        @if($custoTotal > 0)
                            <div class="mb-2 p-2 bg-gray-50 dark:bg-gray-900/50 rounded-lg border border-gray-200 dark:border-gray-700">
                                <div class="flex items-center justify-between text-xs">
                                    <span class="text-gray-600 dark:text-gray-400">Custo:</span>
                                    <span class="font-semibold text-gray-900 dark:text-white">R$ {{ number_format($custoTotal, 2, ',', '.') }}</span>
                                </div>
                                <div class="flex items-center justify-between text-xs mt-1">
                                    <span class="text-gray-600 dark:text-gray-400">Margem:</span>
                                    <span class="font-semibold {{ $margemLucro >= 50 ? 'text-green-600 dark:text-green-400' : ($margemLucro >= 30 ? 'text-yellow-600 dark:text-yellow-400' : 'text-red-600 dark:text-red-400') }}">
                                        {{ number_format($margemLucro, 1) }}%
                                    </span>
                                </div>
                                @if($item->insumo_id)
                                    <div class="mt-1 text-xs text-blue-600 dark:text-blue-400">
                                        <svg class="w-3 h-3 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                        </svg>
                                        Vinculado ao estoque
                                    </div>
                                @endif
                            </div>
                        @endif

                        <div class="flex items-center justify-between pt-3 border-t border-gray-200 dark:border-gray-700">
                            <span class="text-xl font-bold text-gray-900 dark:text-white">
                                R$ {{ number_format($item->preco_venda, 2, ',', '.') }}
                            </span>

                            <div class="flex items-center gap-2 no-drag">
                                <!-- Botão Desativar/Ativar -->
                                <form action="{{ route('cardapio-itens.toggle-status', $item) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit"
                                            class="p-2 {{ $item->ativo_online ? 'text-orange-600 dark:text-orange-400 hover:text-orange-700 dark:hover:text-orange-300 hover:bg-orange-50 dark:hover:bg-orange-900/20' : 'text-green-600 dark:text-green-400 hover:text-green-700 dark:hover:text-green-300 hover:bg-green-50 dark:hover:bg-green-900/20' }} rounded-lg transition-colors"
                                            title="{{ $item->ativo_online ? 'Desativar item (mantém histórico de pedidos)' : 'Ativar item' }}">
                                        @if($item->ativo_online)
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                            </svg>
                                        @else
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        @endif
                                    </button>
                                </form>

                                <a href="{{ route('cardapio-itens.edit', $item) }}"
                                   class="p-2 text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors"
                                   title="Editar">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>

                                <form action="{{ route('cardapio-itens.destroy', $item) }}"
                                      method="POST"
                                      data-confirm="⚠️ Atenção: Esta ação é permanente e não pode ser desfeita. O item será removido completamente do sistema. Se você quiser apenas removê-lo do menu público mantendo o histórico de pedidos, use o botão 'Desativar' ao invés disso. Deseja realmente excluir?"
                                      class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="p-2 text-red-600 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors"
                                            title="Excluir permanentemente (use 'Desativar' para manter histórico)">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                        @endforeach
                    </div>
                </div>
            @empty
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-16 text-center">
                    <svg class="w-16 h-16 mx-auto text-gray-400 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                        <p class="text-gray-500 dark:text-gray-400 font-medium">Nenhum item encontrado.</p>
                        <p class="text-sm text-gray-400 dark:text-gray-500 mt-1">Tente ajustar os filtros ou adicione um novo item ao cardápio.</p>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Lista de Itens -->
        <div id="list-view"
             class="space-y-4"
             style="display: none;">
            @php
                $itensAgrupados = $itensAgrupados ?? collect();
                // Criar uma lista plana de todos os itens agrupados para a visualização em lista
                $itensLista = $itensAgrupados->flatten();
            @endphp
            @forelse ($itensLista as $item)
                @php
                    $ctrl = app(\App\Http\Controllers\CardapioItemController::class);
                    $disp = $ctrl->verificarDisponibilidadeEsubstituicoes($item);
                @endphp
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 hover:shadow-md transition-shadow {{ !$item->ativo_online ? 'opacity-75' : '' }}">
                    <div class="flex items-start gap-4">
                        <!-- Imagem -->
                        <div class="relative w-24 h-24 flex-shrink-0 bg-gray-100 dark:bg-gray-700 rounded-lg overflow-hidden {{ !$item->ativo_online ? 'grayscale' : '' }}">
                            @if ($item->imagem)
                                <img src="{{ asset('storage/' . $item->imagem) }}" alt="{{ $item->nome }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <svg class="w-8 h-8 text-gray-400 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                            @endif
                        </div>

                        <!-- Conteúdo -->
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between gap-4">
                                <div class="flex-1">
                                    <div class="flex items-center gap-2 mb-2">
                                        @if($item->categoria)
                                            <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                                                {{ $item->categoria }}
                                            </span>
                                        @endif
                                        @if($item->ativo_online)
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400">
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                </svg>
                                                Ativo
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400">
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                                </svg>
                                                Desativado
                                            </span>
                                        @endif
                                    </div>

                                    <h3 class="font-semibold text-gray-900 dark:text-white text-lg mb-1">{{ $item->nome }}</h3>

                                    @if($item->descricao)
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">{{ Str::limit($item->descricao, 150) }}</p>
                                    @endif

                                    <!-- Disponibilidade -->
                                    @if(!$disp['disponivel'])
                                        <div class="mb-2">
                                            <span class="inline-block px-2 py-1 rounded bg-red-100 text-red-700 text-xs font-semibold">Indisponível: falta insumo essencial</span>
                                        </div>
                                    @endif

                                    <!-- Estatísticas -->
                                    <div class="flex items-center gap-4 text-sm text-gray-600 dark:text-gray-400">
                                        <span class="text-xl font-bold text-gray-900 dark:text-white">
                                            R$ {{ number_format($item->preco_venda, 2, ',', '.') }}
                                        </span>
                                        @if($item->total_vendido > 0)
                                            <span class="flex items-center gap-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                                                </svg>
                                                {{ $item->total_vendido }} vendas
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Ações -->
                                <div class="flex items-center gap-2 flex-shrink-0">
                                    <form action="{{ route('cardapio-itens.toggle-status', $item) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                                class="p-2 {{ $item->ativo_online ? 'text-orange-600 dark:text-orange-400 hover:text-orange-700 dark:hover:text-orange-300 hover:bg-orange-50 dark:hover:bg-orange-900/20' : 'text-green-600 dark:text-green-400 hover:text-green-700 dark:hover:text-green-300 hover:bg-green-50 dark:hover:bg-green-900/20' }} rounded-lg transition-colors"
                                                title="{{ $item->ativo_online ? 'Desativar item' : 'Ativar item' }}">
                                            @if($item->ativo_online)
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                                </svg>
                                            @else
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                            @endif
                                        </button>
                                    </form>

                                    <form action="{{ route('cardapio-itens.duplicate', $item) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit"
                                                class="p-2 text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg transition-colors"
                                                title="Duplicar item">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                            </svg>
                                        </button>
                                    </form>

                                    <form action="{{ route('cardapio-itens.duplicate', $item) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit"
                                                class="p-2 text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg transition-colors"
                                                title="Duplicar item">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                            </svg>
                                        </button>
                                    </form>

                                    <a href="{{ route('cardapio-itens.edit', $item) }}"
                                       class="p-2 text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors"
                                       title="Editar">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>

                                    <form action="{{ route('cardapio-itens.destroy', $item) }}"
                                          method="POST"
                                          data-confirm="⚠️ Atenção: Esta ação é permanente e não pode ser desfeita. O item será removido completamente do sistema. Se você quiser apenas removê-lo do menu público mantendo o histórico de pedidos, use o botão 'Desativar' ao invés disso. Deseja realmente excluir?"
                                          class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="p-2 text-red-600 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors"
                                                title="Excluir permanentemente">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-16 text-center">
                    <svg class="w-16 h-16 mx-auto text-gray-400 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                    <p class="text-gray-500 dark:text-gray-400 font-medium">Nenhum item encontrado.</p>
                    <p class="text-sm text-gray-400 dark:text-gray-500 mt-1">Tente ajustar os filtros ou adicione um novo item ao cardápio.</p>
                </div>
            @endforelse
        </div>

    </div>

@endsection

@section('scripts')
<!-- SortableJS para drag-and-drop -->
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script>
    // Função para alternar entre visualizações
    function setViewMode(mode) {
        const gridView = document.getElementById('grid-view');
        const listView = document.getElementById('list-view');
        const btnGrid = document.getElementById('btn-grid');
        const btnList = document.getElementById('btn-list');

        if (mode === 'grid') {
            if (gridView) gridView.style.display = 'block';
            if (listView) listView.style.display = 'none';
            if (btnGrid) {
                btnGrid.classList.remove('bg-gray-100', 'dark:bg-gray-700', 'text-gray-600', 'dark:text-gray-400');
                btnGrid.classList.add('bg-red-100', 'dark:bg-red-900/30', 'text-red-600', 'dark:text-red-400');
            }
            if (btnList) {
                btnList.classList.remove('bg-red-100', 'dark:bg-red-900/30', 'text-red-600', 'dark:text-red-400');
                btnList.classList.add('bg-gray-100', 'dark:bg-gray-700', 'text-gray-600', 'dark:text-gray-400');
            }
            // Reinicializar SortableJS quando voltar para grid
            setTimeout(initSortable, 100);
        } else {
            if (gridView) gridView.style.display = 'none';
            if (listView) listView.style.display = 'block';
            if (btnList) {
                btnList.classList.remove('bg-gray-100', 'dark:bg-gray-700', 'text-gray-600', 'dark:text-gray-400');
                btnList.classList.add('bg-red-100', 'dark:bg-red-900/30', 'text-red-600', 'dark:text-red-400');
            }
            if (btnGrid) {
                btnGrid.classList.remove('bg-red-100', 'dark:bg-red-900/30', 'text-red-600', 'dark:text-red-400');
                btnGrid.classList.add('bg-gray-100', 'dark:bg-gray-700', 'text-gray-600', 'dark:text-gray-400');
            }
        }

        try {
            localStorage.setItem('cardapio-view-mode', mode);
        } catch(e) {
            console.warn('Erro ao salvar preferência:', e);
        }
    }

    // Função para inicializar SortableJS
    function initSortable() {
        // Verificar se SortableJS está disponível
        if (typeof Sortable === 'undefined') {
            console.error('SortableJS não foi carregado! Tentando novamente...');
            setTimeout(initSortable, 500);
            return;
        }
        
        // Remover instâncias anteriores (se houver)
        document.querySelectorAll('.sortable-categoria').forEach(container => {
            if (container.sortableInstance) {
                container.sortableInstance.destroy();
            }
        });
        
        // Inicializar SortableJS para cada categoria
        const categorias = document.querySelectorAll('.sortable-categoria');
        
        if (categorias.length === 0) {
            console.warn('Nenhuma categoria encontrada para ordenação');
            return;
        }
        
        console.log('Inicializando SortableJS para', categorias.length, 'categoria(s)');
        
        categorias.forEach(function(container) {
            const categoria = container.dataset.categoria || container.getAttribute('data-categoria');
            
            if (!categoria) {
                console.warn('Categoria não encontrada no container');
                return;
            }
            
            // Verificar se há itens no container
            const items = container.querySelectorAll('.sortable-item');
            if (items.length === 0) {
                console.warn('Nenhum item encontrado na categoria:', categoria);
                return;
            }
            
            try {
                const sortable = new Sortable(container, {
                    animation: 150,
                    ghostClass: 'opacity-50',
                    chosenClass: 'ring-2 ring-red-500',
                    dragClass: 'opacity-50',
                    handle: '.sortable-handle', // Usar o handle específico para arrastar
                    forceFallback: true,
                    fallbackOnBody: true,
                    swapThreshold: 0.65,
                    filter: '.no-drag', // Elementos com classe 'no-drag' não podem ser arrastados
                    preventOnFilter: false,
                    onStart: function(evt) {
                        console.log('Iniciando arraste do item:', evt.item.dataset.itemId);
                        evt.item.style.cursor = 'grabbing';
                    },
                    onEnd: function(evt) {
                        evt.item.style.cursor = '';
                        console.log('Arraste finalizado');
                        const items = Array.from(container.querySelectorAll('.sortable-item'));
                        const itemsData = items.map((item, index) => {
                            const itemId = item.getAttribute('data-item-id') || item.dataset.itemId;
                            if (!itemId) {
                                console.error('Item ID não encontrado:', item);
                                return null;
                            }
                            return {
                                id: parseInt(itemId),
                                ordem: index
                            };
                        }).filter(item => item !== null);
                        
                        if (itemsData.length === 0) {
                            console.error('Nenhum item encontrado para atualizar');
                            return;
                        }
                        
                        console.log('Enviando nova ordem:', itemsData);
                        
                        // Enviar nova ordem para o servidor
                        fetch('{{ route('cardapio-itens.update-order') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                items: itemsData,
                                categoria: categoria === 'sem-categoria' ? null : categoria
                            })
                        })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Erro na resposta do servidor: ' + response.status);
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (data.success) {
                                console.log('✅ Ordem atualizada com sucesso');
                            } else {
                                console.error('❌ Erro ao atualizar ordem:', data.message);
                                location.reload();
                            }
                        })
                        .catch(error => {
                            console.error('❌ Erro ao atualizar ordem:', error);
                            alert('Erro ao salvar a ordem. A página será recarregada.');
                            location.reload();
                        });
                    }
                });
                
                // Armazenar instância para poder destruir depois
                container.sortableInstance = sortable;
                
                if (sortable) {
                    console.log('✅ SortableJS inicializado para categoria:', categoria, 'com', items.length, 'itens');
                }
            } catch (error) {
                console.error('❌ Erro ao inicializar SortableJS para categoria', categoria, ':', error);
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Carregar preferência de visualização
        try {
            const saved = localStorage.getItem('cardapio-view-mode');
            if (saved === 'list' || saved === 'grid') {
                setViewMode(saved);
            } else {
                setViewMode('grid');
            }
        } catch(e) {
            setViewMode('grid');
        }
        
        // Aguardar um pouco para garantir que o DOM está totalmente renderizado
        setTimeout(function() {
            initSortable();
        }, 300);

        // Busca em tempo real com debounce
        const searchInput = document.getElementById('search-input');
        const filterForm = document.getElementById('filter-form');
        let searchTimeout;

        if (searchInput && filterForm) {
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(function() {
                    filterForm.submit();
                }, 500);
            });
        }
    });
</script>
@endsection

