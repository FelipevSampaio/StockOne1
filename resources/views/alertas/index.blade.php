@extends('layouts.app')

@section('title', 'Alertas operacionais')
@section('subtitle', 'Priorize ações críticas do estoque')

@section('actions')
    <a href="{{ route('alertas.create') }}" class="rounded-full bg-red-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-red-500 transition-colors flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Novo alerta
    </a>
@endsection

@section('content')
    <div class="space-y-6" x-data="alertasData()" x-init="init()">
        <!-- Estatísticas Interativas -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <a href="{{ route('alertas.index') }}" 
               class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-lg hover:scale-105 transition-all duration-300 cursor-pointer group">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total de Alertas</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $stats['total'] }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">Todos os alertas</p>
                    </div>
                    <div class="w-12 h-12 bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                    </div>
                </div>
            </a>

            <a href="{{ route('alertas.index', ['status' => 'pendente']) }}" 
               class="bg-gradient-to-br from-red-50 to-red-100 dark:from-red-900/20 dark:to-red-800/20 rounded-xl shadow-sm border-2 border-red-200 dark:border-red-800 p-6 hover:shadow-lg hover:scale-105 transition-all duration-300 cursor-pointer group {{ request('status') === 'pendente' ? 'ring-2 ring-red-500' : '' }}">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-red-700 dark:text-red-400">Pendentes</p>
                        <p class="text-3xl font-bold text-red-600 dark:text-red-400 mt-2">{{ $stats['pendentes'] }}</p>
                        <p class="text-xs text-red-600 dark:text-red-400 mt-1">Não visualizados</p>
                    </div>
                    <div class="w-12 h-12 bg-red-200 dark:bg-red-900/40 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform {{ $stats['pendentes'] > 0 ? 'animate-pulse' : '' }}">
                        <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </a>

            <a href="{{ route('alertas.index', ['status' => 'aberto']) }}" 
               class="bg-gradient-to-br from-orange-50 to-orange-100 dark:from-orange-900/20 dark:to-orange-800/20 rounded-xl shadow-sm border-2 border-orange-200 dark:border-orange-800 p-6 hover:shadow-lg hover:scale-105 transition-all duration-300 cursor-pointer group {{ request('status') === 'aberto' ? 'ring-2 ring-orange-500' : '' }}">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-orange-700 dark:text-orange-400">Abertos</p>
                        <p class="text-3xl font-bold text-orange-600 dark:text-orange-400 mt-2">{{ $stats['abertos'] }}</p>
                        <p class="text-xs text-orange-600 dark:text-orange-400 mt-1">Não resolvidos</p>
                    </div>
                    <div class="w-12 h-12 bg-orange-200 dark:bg-orange-900/40 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                </div>
            </a>

            <a href="{{ route('alertas.index', ['tipo' => 'estoque_baixo', 'status' => 'aberto']) }}" 
               class="bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900/20 dark:to-purple-800/20 rounded-xl shadow-sm border-2 border-purple-200 dark:border-purple-800 p-6 hover:shadow-lg hover:scale-105 transition-all duration-300 cursor-pointer group">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-purple-700 dark:text-purple-400">Críticos</p>
                        <p class="text-3xl font-bold text-purple-600 dark:text-purple-400 mt-2">{{ $stats['criticos'] }}</p>
                        <p class="text-xs text-purple-600 dark:text-purple-400 mt-1">Estoque baixo</p>
                    </div>
                    <div class="w-12 h-12 bg-purple-200 dark:bg-purple-900/40 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform {{ $stats['criticos'] > 0 ? 'animate-pulse' : '' }}">
                        <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.618 5.984A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016zM12 9v2m0 4h.01"/>
                        </svg>
                    </div>
                </div>
            </a>
        </div>

        <!-- Filtros Rápidos (Chips) -->
        <div class="flex flex-wrap items-center gap-2">
            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Filtros rápidos:</span>
            <a href="{{ route('alertas.index') }}" 
               class="px-3 py-1.5 text-xs font-medium rounded-lg transition-colors {{ !request()->hasAny(['status', 'tipo']) ? 'bg-red-600 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }}">
                Todos
            </a>
            <a href="{{ route('alertas.index', ['status' => 'pendente']) }}" 
               class="px-3 py-1.5 text-xs font-medium rounded-lg transition-colors {{ request('status') === 'pendente' ? 'bg-red-600 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }}">
                Pendentes ({{ $stats['pendentes'] }})
            </a>
            <a href="{{ route('alertas.index', ['status' => 'aberto']) }}" 
               class="px-3 py-1.5 text-xs font-medium rounded-lg transition-colors {{ request('status') === 'aberto' ? 'bg-orange-600 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }}">
                Abertos ({{ $stats['abertos'] }})
            </a>
            <a href="{{ route('alertas.index', ['status' => 'resolvido']) }}" 
               class="px-3 py-1.5 text-xs font-medium rounded-lg transition-colors {{ request('status') === 'resolvido' ? 'bg-green-600 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }}">
                Resolvidos
            </a>
            <a href="{{ route('alertas.index', ['tipo' => 'estoque_baixo']) }}" 
               class="px-3 py-1.5 text-xs font-medium rounded-lg transition-colors {{ request('tipo') === 'estoque_baixo' ? 'bg-purple-600 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }}">
                Estoque Baixo
            </a>
            <a href="{{ route('alertas.index', ['tipo' => 'validade_proxima']) }}" 
               class="px-3 py-1.5 text-xs font-medium rounded-lg transition-colors {{ request('tipo') === 'validade_proxima' ? 'bg-yellow-600 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }}">
                Validade Próxima
            </a>
        </div>

        <!-- Filtros Avançados -->
        <form method="GET" action="{{ route('alertas.index') }}" 
              class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4"
              x-data="{ expanded: {{ request()->hasAny(['search', 'tipo', 'status', 'per_page']) ? 'true' : 'false' }} }">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Filtros Avançados</h3>
                <button type="button" 
                        @click="expanded = !expanded"
                        class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white">
                    <svg class="w-5 h-5 transition-transform" :class="{ 'rotate-180': expanded }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
            </div>
            
            <div x-show="expanded" x-transition class="space-y-3">
                <div class="flex flex-wrap items-center gap-3">
                    <!-- Busca -->
                    <div class="relative flex-1 min-w-[250px]">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-5 w-5 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <input type="text"
                               name="search"
                               placeholder="Buscar por insumo, tipo ou mensagem..."
                               value="{{ request('search') }}"
                               class="pl-10 w-full px-4 py-2.5 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400">
                    </div>

                    <!-- Filtro por Tipo -->
                    <select name="tipo"
                            class="px-4 py-2.5 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                        <option value="">Todos os tipos</option>
                        @foreach($tipos as $tipo)
                            <option value="{{ $tipo }}" @selected(request('tipo') === $tipo)>{{ ucfirst(str_replace('_', ' ', $tipo)) }}</option>
                        @endforeach
                    </select>

                    <!-- Filtro por Status -->
                    <select name="status"
                            class="px-4 py-2.5 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                        <option value="">Todos os status</option>
                        <option value="pendente" @selected(request('status') === 'pendente')>Pendente</option>
                        <option value="visualizado" @selected(request('status') === 'visualizado')>Visualizado</option>
                        <option value="aberto" @selected(request('status') === 'aberto')>Aberto</option>
                        <option value="resolvido" @selected(request('status') === 'resolvido')>Resolvido</option>
                    </select>

                    <!-- Ordenação -->
                    <select name="sort"
                            class="px-4 py-2.5 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                        <option value="data_hora_alerta" @selected(request('sort') === 'data_hora_alerta')>Mais recente</option>
                        <option value="tipo_alerta" @selected(request('sort') === 'tipo_alerta')>Tipo</option>
                        <option value="resolvido" @selected(request('sort') === 'resolvido')>Status</option>
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

                    @if(request()->hasAny(['search', 'tipo', 'status']))
                        <a href="{{ route('alertas.index') }}"
                           class="px-5 py-2.5 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm font-semibold rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">
                            Limpar
                        </a>
                    @endif
                </div>
            </div>
        </form>

        <!-- Cards de Alertas -->
        <div class="space-y-4">
            @forelse ($alertas as $alerta)
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border-l-4 {{ !$alerta->resolvido ? 'border-red-500' : 'border-green-500' }} p-6 hover:shadow-lg transition-all duration-300"
                     x-data="{ showActions: false }"
                     @mouseenter="showActions = true"
                     @mouseleave="showActions = false">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex items-start gap-4 flex-1">
                            <!-- Ícone -->
                            <div class="w-12 h-12 {{ !$alerta->resolvido ? 'bg-red-100 dark:bg-red-900/30' : 'bg-green-100 dark:bg-green-900/30' }} rounded-lg flex items-center justify-center flex-shrink-0">
                                @if($alerta->tipo_alerta === 'estoque_baixo')
                                    <svg class="w-6 h-6 {{ !$alerta->resolvido ? 'text-red-600 dark:text-red-400' : 'text-green-600 dark:text-green-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                    </svg>
                                @elseif($alerta->tipo_alerta === 'validade_proxima')
                                    <svg class="w-6 h-6 {{ !$alerta->resolvido ? 'text-red-600 dark:text-red-400' : 'text-green-600 dark:text-green-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                @else
                                    <svg class="w-6 h-6 {{ !$alerta->resolvido ? 'text-red-600 dark:text-red-400' : 'text-green-600 dark:text-green-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                    </svg>
                                @endif
                            </div>

                            <!-- Conteúdo -->
                            <div class="flex-1 min-w-0">
                                <div class="flex items-start justify-between gap-3 mb-2">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2 mb-1">
                                            <h3 class="font-semibold text-gray-900 dark:text-white text-lg">{{ $alerta->insumo?->nome ?? 'Insumo não encontrado' }}</h3>
                                            @if($alerta->insumo)
                                                <a href="{{ route('estoque.index', ['search' => $alerta->insumo->nome]) }}" 
                                                   class="text-xs text-blue-600 dark:text-blue-400 hover:underline">
                                                    Ver estoque →
                                                </a>
                                            @endif
                                        </div>
                                        <div class="flex flex-wrap items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
                                            <span class="inline-flex items-center gap-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                                </svg>
                                                {{ ucfirst(str_replace('_', ' ', $alerta->tipo_alerta)) }}
                                            </span>
                                            <span>•</span>
                                            <span class="inline-flex items-center gap-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                {{ $alerta->data_hora_alerta?->format('d/m/Y H:i') ?? 'Data não informada' }}
                                            </span>
                                            @if($alerta->data_hora_alerta)
                                                <span>•</span>
                                                <span class="text-xs">{{ $alerta->data_hora_alerta->diffForHumans() }}</span>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Badges de Status -->
                                    <div class="flex flex-wrap gap-2">
                                        @if(!$alerta->visualizado)
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 animate-pulse">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"/>
                                                </svg>
                                                Pendente
                                            </span>
                                        @endif
                                        @if(!$alerta->resolvido)
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-orange-100 dark:bg-orange-900/30 text-orange-700 dark:text-orange-400">
                                                Aberto
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                </svg>
                                                Resolvido
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <p class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed mb-3">{{ $alerta->mensagem }}</p>

                                <!-- Ações Rápidas -->
                                <div class="flex flex-wrap items-center gap-2 pt-3 border-t border-gray-200 dark:border-gray-700"
                                     x-show="showActions || !{{ !$alerta->resolvido ? 'true' : 'false' }}"
                                     x-transition>
                                    @if(!$alerta->visualizado)
                                        <form action="{{ route('alertas.update', $alerta) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="visualizado" value="1">
                                            <button type="submit" 
                                                    class="px-3 py-1.5 text-xs font-medium bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 rounded-lg hover:bg-blue-200 dark:hover:bg-blue-900/50 transition-colors flex items-center gap-1">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                                Marcar como visualizado
                                            </button>
                                        </form>
                                    @endif
                                    
                                    @if(!$alerta->resolvido)
                                        <form action="{{ route('alertas.update', $alerta) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="resolvido" value="1">
                                            <button type="submit" 
                                                    class="px-3 py-1.5 text-xs font-medium bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 rounded-lg hover:bg-green-200 dark:hover:bg-green-900/50 transition-colors flex items-center gap-1">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                </svg>
                                                Marcar como resolvido
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Ações -->
                        <div class="flex items-center gap-2 flex-shrink-0">
                            <a href="{{ route('alertas.edit', $alerta) }}"
                               class="p-2 text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors"
                               title="Editar">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </a>
                            <form action="{{ route('alertas.destroy', $alerta) }}" method="POST" data-confirm="Remover este alerta?" class="inline">
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-gray-500 dark:text-gray-400 font-medium">Nenhum alerta encontrado.</p>
                    <p class="text-sm text-gray-400 dark:text-gray-500 mt-1">Tudo está sob controle! 🎉</p>
                    @if(request()->hasAny(['search', 'tipo', 'status']))
                        <a href="{{ route('alertas.index') }}" class="mt-4 inline-block px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-500 transition-colors text-sm font-medium">
                            Limpar filtros
                        </a>
                    @endif
                </div>
            @endforelse
        </div>

        @if($alertas->hasPages())
            <div class="flex justify-center">
                {{ $alertas->links() }}
            </div>
        @endif
    </div>

    <script>
        function alertasData() {
            return {
                init() {
                    // Auto-refresh a cada 30 segundos se houver alertas pendentes
                    @if($stats['pendentes'] > 0)
                        setInterval(() => {
                            if (document.visibilityState === 'visible') {
                                window.location.reload();
                            }
                        }, 30000);
                    @endif
                }
            }
        }
    </script>
@endsection
