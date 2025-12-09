@extends('layouts.app')

@section('title', 'Fila de produção')
@section('subtitle', 'Controle prioridades da cozinha')

@section('actions')
    <div class="flex items-center gap-2">
        <a href="{{ route('pedidos.index', ['filtro_rapido' => 'em_producao']) }}" 
           class="rounded-full bg-gray-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-gray-500 transition-colors flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            Ver Pedidos
        </a>
        <a href="{{ route('fila-producao.create') }}" class="rounded-full bg-red-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-red-500 transition-colors flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Adicionar à fila
        </a>
    </div>
@endsection

@section('content')
    <div class="space-y-6">
        <!-- Estatísticas -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total na Fila</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $stats['total'] }}</p>
                    </div>
                    <div class="w-12 h-12 bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Em Produção</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $stats['em_producao'] }}</p>
                    </div>
                    <div class="w-12 h-12 bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Pendentes</p>
                        <p class="text-3xl font-bold text-red-600 dark:text-red-400 mt-2">{{ $stats['pendentes'] }}</p>
                    </div>
                    <div class="w-12 h-12 bg-red-100 dark:bg-red-900/30 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Prioridade Alta</p>
                        <p class="text-3xl font-bold text-red-600 dark:text-red-400 mt-2">{{ $stats['prioridade_alta'] }}</p>
                    </div>
                    <div class="w-12 h-12 bg-red-100 dark:bg-red-900/30 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtros -->
        <form method="GET" action="{{ route('fila-producao.index') }}" class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4">
            @if(request('view'))
                <input type="hidden" name="view" value="{{ request('view') }}">
            @endif
            <div class="flex flex-wrap items-center gap-3">
                <!-- Busca -->
                <div class="relative flex-1 min-w-[250px]">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-5 w-5 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text"
                           name="search"
                           placeholder="Buscar por pedido, item ou status..."
                           value="{{ request('search') }}"
                           class="pl-10 w-full px-4 py-2.5 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400">
                </div>

                <!-- Filtro por Status -->
                <select name="status"
                        class="px-4 py-2.5 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                    <option value="">Todos os status</option>
                    @foreach($statusList as $status)
                        <option value="{{ $status }}" @selected(request('status') === $status)>{{ ucfirst(str_replace('_', ' ', $status)) }}</option>
                    @endforeach
                </select>

                <!-- Filtro por Prioridade -->
                <select name="prioridade"
                        class="px-4 py-2.5 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                    <option value="">Todas as prioridades</option>
                    <option value="alta" @selected(request('prioridade') === 'alta')>Alta (≥5)</option>
                    <option value="media" @selected(request('prioridade') === 'media')>Média (3-4)</option>
                    <option value="baixa" @selected(request('prioridade') === 'baixa')>Baixa (≤2)</option>
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

                <!-- Controles de Visualização -->
                <div class="flex items-center gap-2 border-l border-gray-300 dark:border-gray-600 pl-3 ml-3">
                    <a href="{{ route('fila-producao.index', array_merge(request()->all(), ['view' => 'table'])) }}"
                       class="p-2 rounded-lg transition-colors {{ ($viewMode ?? 'table') === 'table' ? 'bg-red-600 text-white shadow-sm' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }}"
                       title="Visualização em Tabela">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                        </svg>
                    </a>
                    <a href="{{ route('fila-producao.index', array_merge(request()->all(), ['view' => 'kanban'])) }}"
                       class="p-2 rounded-lg transition-colors {{ ($viewMode ?? 'table') === 'kanban' ? 'bg-red-600 text-white shadow-sm' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }}"
                       title="Visualização em Kanban">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"/>
                        </svg>
                    </a>
                </div>

                @if(request()->hasAny(['search', 'status', 'prioridade']))
                    <a href="{{ route('fila-producao.index') }}"
                       class="px-5 py-2.5 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm font-semibold rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">
                        Limpar
                    </a>
                @endif
            </div>
        </form>

        @if(($viewMode ?? 'table') === 'kanban')
            <!-- Visualização Kanban -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4" x-data="kanbanManager()">
                <!-- Coluna: Pendente -->
                <div class="bg-gray-50 dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-700 p-4">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 bg-red-100 dark:bg-red-900/30 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <h3 class="font-semibold text-gray-900 dark:text-white">Pendente</h3>
                            <span class="px-2 py-0.5 bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 rounded-full text-xs font-bold">
                                {{ $filasAgrupadas['pendente']->count() ?? 0 }}
                            </span>
                        </div>
                    </div>

                    <!-- Cards da Coluna -->
                    <div class="space-y-3 min-h-[200px]" id="kanban-pendente">
                        @if(isset($filasAgrupadas['pendente']) && $filasAgrupadas['pendente']->count() > 0)
                            @foreach($filasAgrupadas['pendente'] as $registro)
                                @include('fila_producao._kanban_card', ['registro' => $registro])
                            @endforeach
                        @else
                            <div class="text-center py-8 text-gray-400 dark:text-gray-600 text-sm">
                                Nenhum item
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Coluna: Em Produção -->
                <div class="bg-gray-50 dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-700 p-4">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                </svg>
                            </div>
                            <h3 class="font-semibold text-gray-900 dark:text-white">Em Produção</h3>
                            <span class="px-2 py-0.5 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 rounded-full text-xs font-bold">
                                {{ $filasAgrupadas['em_producao']->count() ?? 0 }}
                            </span>
                        </div>
                    </div>
                    <!-- Cards da Coluna -->
                    <div class="space-y-3 min-h-[200px]" id="kanban-em_producao">
                        @if(isset($filasAgrupadas['em_producao']) && $filasAgrupadas['em_producao']->count() > 0)
                            @foreach($filasAgrupadas['em_producao'] as $registro)
                                @include('fila_producao._kanban_card', ['registro' => $registro])
                            @endforeach
                        @else
                            <div class="text-center py-8 text-gray-400 dark:text-gray-600 text-sm">
                                Nenhum item
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Coluna: Pronto -->
                <div class="bg-gray-50 dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-700 p-4">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <h3 class="font-semibold text-gray-900 dark:text-white">Pronto</h3>
                            <span class="px-2 py-0.5 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 rounded-full text-xs font-bold">
                                {{ $filasAgrupadas['pronto']->count() ?? 0 }}
                            </span>
                        </div>
                    </div>
                    <!-- Cards da Coluna -->
                    <div class="space-y-3 min-h-[200px]" id="kanban-pronto">
                        @if(isset($filasAgrupadas['pronto']) && $filasAgrupadas['pronto']->count() > 0)
                            @foreach($filasAgrupadas['pronto'] as $registro)
                                @include('fila_producao._kanban_card', ['registro' => $registro])
                            @endforeach
                        @else
                            <div class="text-center py-8 text-gray-400 dark:text-gray-600 text-sm">
                                Nenhum item
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @else
            <!-- Visualização em Tabela (Lista) -->
            <div class="space-y-4" x-data="{ expandedCard: null }">
                @forelse ($filas as $registro)
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border-l-4 {{ $registro->prioridade >= 5 ? 'border-red-500' : ($registro->status_producao === 'em_producao' ? 'border-blue-500' : 'border-gray-300 dark:border-gray-600') }} p-6 hover:shadow-lg transition-all duration-300"
                     x-data="{ showActions: false }"
                     @mouseenter="showActions = true"
                     @mouseleave="showActions = false">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex items-start gap-4 flex-1">
                            <!-- Badge de Prioridade -->
                            <div class="w-14 h-14 {{ $registro->prioridade >= 5 ? 'bg-red-100 dark:bg-red-900/30 ring-2 ring-red-500' : ($registro->status_producao === 'em_producao' ? 'bg-blue-100 dark:bg-blue-900/30 ring-2 ring-blue-500' : 'bg-gray-100 dark:bg-gray-700') }} rounded-lg flex flex-col items-center justify-center flex-shrink-0 {{ $registro->prioridade >= 5 ? 'animate-pulse' : '' }}">
                                <span class="text-xs font-medium {{ $registro->prioridade >= 5 ? 'text-red-600 dark:text-red-400' : ($registro->status_producao === 'em_producao' ? 'text-blue-600 dark:text-blue-400' : 'text-gray-600 dark:text-gray-400') }}">PRIO</span>
                                <span class="text-xl font-bold {{ $registro->prioridade >= 5 ? 'text-red-600 dark:text-red-400' : ($registro->status_producao === 'em_producao' ? 'text-blue-600 dark:text-blue-400' : 'text-gray-600 dark:text-gray-400') }}">{{ $registro->prioridade ?? 0 }}</span>
                            </div>

                            <!-- Conteúdo -->
                            <div class="flex-1 min-w-0">
                                <div class="flex items-start justify-between gap-3 mb-2">
                                    <div>
                                        <div class="flex items-center gap-2 mb-1">
                                            <h3 class="font-semibold text-gray-900 dark:text-white text-lg">Pedido #{{ $registro->pedido_id }}</h3>
                                            <a href="{{ route('pedidos.index', ['search' => $registro->pedido_id]) }}" 
                                               class="text-xs text-blue-600 dark:text-blue-400 hover:underline flex items-center gap-1">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                                Ver pedido
                                            </a>
                                        </div>
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-0.5">
                                            <span class="font-medium">{{ $registro->pedidoItem?->cardapioItem?->nome ?? 'Item não encontrado' }}</span>
                                            @if($registro->pedidoItem)
                                                • Qtd: {{ $registro->pedidoItem->quantidade }}
                                                @if($registro->pedidoItem->observacao)
                                                    <span class="text-xs text-gray-500 dark:text-gray-500">• {{ Str::limit($registro->pedidoItem->observacao, 30) }}</span>
                                                @endif
                                            @endif
                                        </p>
                                    </div>

                                    <!-- Badge de Status -->
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold
                                        {{ $registro->status_producao === 'pendente' ? 'bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 animate-pulse' :
                                           ($registro->status_producao === 'em_producao' ? 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400' :
                                            'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400') }}">
                                        @if($registro->status_producao === 'em_producao')
                                            <svg class="w-3 h-3 mr-1 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                            </svg>
                                        @endif
                                        {{ ucfirst(str_replace('_', ' ', $registro->status_producao)) }}
                                    </span>
                                </div>

                                <div class="flex flex-wrap items-center gap-4 text-xs text-gray-500 dark:text-gray-400 mb-3">
                                    @if($registro->data_hora_inicio)
                                        <div class="flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            Início: {{ $registro->data_hora_inicio->format('d/m H:i') }}
                                            @if($registro->status_producao === 'em_producao')
                                                <span class="ml-1 text-blue-600 dark:text-blue-400 font-medium">
                                                    ({{ $registro->data_hora_inicio->diffForHumans() }})
                                                </span>
                                            @endif
                                        </div>
                                    @endif
                                    @if($registro->data_hora_fim)
                                        <div class="flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            Fim: {{ $registro->data_hora_fim->format('d/m H:i') }}
                                        </div>
                                    @endif
                                    @if($registro->data_hora_inicio && $registro->data_hora_fim)
                                        @php
                                            $tempoDecorrido = $registro->data_hora_inicio->diffInMinutes($registro->data_hora_fim);
                                        @endphp
                                        <div class="flex items-center gap-1 text-orange-600 dark:text-orange-400 font-medium">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                            </svg>
                                            Tempo: {{ $tempoDecorrido }} min
                                        </div>
                                    @endif
                                    <div class="flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                        </svg>
                                        {{ $registro->updated_at?->diffForHumans() }}
                                    </div>
                                </div>

                                <!-- Ações Rápidas -->
                                <div class="flex flex-wrap items-center gap-2 pt-3 border-t border-gray-200 dark:border-gray-700"
                                     x-show="showActions"
                                     x-transition>
                                    @if($registro->status_producao === 'pendente')
                                        <form action="{{ route('fila-producao.update', $registro) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status_producao" value="em_producao">
                                            <input type="hidden" name="data_hora_inicio" value="{{ now()->format('Y-m-d\TH:i') }}">
                                            <button type="submit" 
                                                    class="px-3 py-1.5 text-xs font-medium bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 rounded-lg hover:bg-blue-200 dark:hover:bg-blue-900/50 transition-colors flex items-center gap-1">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                Iniciar Produção
                                            </button>
                                        </form>
                                    @endif
                                    
                                    @if($registro->status_producao === 'em_producao')
                                        <form action="{{ route('fila-producao.update', $registro) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status_producao" value="pronto">
                                            <input type="hidden" name="data_hora_fim" value="{{ now()->format('Y-m-d\TH:i') }}">
                                            <button type="submit" 
                                                    class="px-3 py-1.5 text-xs font-medium bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 rounded-lg hover:bg-green-200 dark:hover:bg-green-900/50 transition-colors flex items-center gap-1">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                </svg>
                                                Marcar como Pronto
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Ações -->
                        <div class="flex items-center gap-2 flex-shrink-0">
                            <a href="{{ route('fila-producao.edit', $registro) }}"
                               class="p-2 text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors"
                               title="Editar">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </a>
                            <form action="{{ route('fila-producao.destroy', $registro) }}" method="POST" data-confirm="Remover da fila?" class="inline">
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
                    </div>
                </div>
            @empty
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-16 text-center">
                    <svg class="w-16 h-16 mx-auto text-gray-400 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    <p class="text-gray-500 dark:text-gray-400 font-medium">Nenhum item na fila.</p>
                    <p class="text-sm text-gray-400 dark:text-gray-500 mt-1">Adicione pedidos à fila de produção!</p>
                </div>
                @endforelse
            </div>

            @if($filas->hasPages())
                <div class="flex justify-center">
                    {{ $filas->links() }}
                </div>
            @endif
        @endif
    </div>

    <script>
        function kanbanManager() {
            return {
                init() {
                    // Inicializar drag and drop se necessário
                }
            }
        }
    </script>
@endsection

