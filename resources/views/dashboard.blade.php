@extends('layouts.app')

@section('title', 'Dashboard')
@section('subtitle', 'Visão geral do seu restaurante')

@section('content')
<div class="w-full" x-data="dashboardData()" x-init="init()" x-cloak>
    <!-- Filtros de Período -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 mb-6"
         x-data="{ showHelp: false }">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div class="flex flex-wrap items-center gap-2">
                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Período:</span>
                <button @click="setPeriod('hoje')" 
                        :class="period === 'hoje' ? 'bg-red-600 text-white shadow-md' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300'"
                        class="px-4 py-2 rounded-lg text-sm font-medium transition-all hover:scale-105">
                    Hoje
                </button>
                <button @click="setPeriod('semana')" 
                        :class="period === 'semana' ? 'bg-red-600 text-white shadow-md' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300'"
                        class="px-4 py-2 rounded-lg text-sm font-medium transition-all hover:scale-105">
                    Esta Semana
                </button>
                <button @click="setPeriod('mes')" 
                        :class="period === 'mes' ? 'bg-red-600 text-white shadow-md' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300'"
                        class="px-4 py-2 rounded-lg text-sm font-medium transition-all hover:scale-105">
                    Este Mês
                </button>
                <button @click="setPeriod('custom')" 
                        :class="period === 'custom' ? 'bg-red-600 text-white shadow-md' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300'"
                        class="px-4 py-2 rounded-lg text-sm font-medium transition-all hover:scale-105">
                    Personalizado
                </button>
            </div>
            <div class="flex items-center gap-3">
                <button @click="showHelp = !showHelp" 
                        class="px-3 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg text-sm font-medium hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors"
                        title="Atalhos de teclado">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </button>
                <button @click="exportDashboard()" 
                        class="px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg text-sm font-medium hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors flex items-center gap-2"
                        title="Exportar (Ctrl+E)">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Exportar
                </button>
                <button @click="toggleCustomizeMode()" 
                        :class="customizeMode ? 'bg-red-600 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300'"
                        class="px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors flex items-center gap-2"
                        title="Personalizar (Ctrl+P)">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h4a1 1 0 011 1v7a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM14 5a1 1 0 011-1h4a1 1 0 011 1v7a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zM4 16a1 1 0 011-1h4a1 1 0 011 1v3a1 1 0 01-1 1H5a1 1 0 01-1-1v-3zM14 16a1 1 0 011-1h4a1 1 0 011 1v3a1 1 0 01-1 1h-4a1 1 0 01-1-1v-3z"/>
                    </svg>
                    Personalizar
                </button>
            </div>
        </div>
        <!-- Ajuda de Atalhos -->
        <div x-show="showHelp" 
             x-transition
             class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 text-xs">
                <div>
                    <kbd class="px-2 py-1 bg-gray-200 dark:bg-gray-700 rounded font-mono">Ctrl+K</kbd>
                    <span class="ml-2 text-gray-600 dark:text-gray-400">Ações rápidas</span>
                </div>
                <div>
                    <kbd class="px-2 py-1 bg-gray-200 dark:bg-gray-700 rounded font-mono">Ctrl+E</kbd>
                    <span class="ml-2 text-gray-600 dark:text-gray-400">Exportar</span>
                </div>
                <div>
                    <kbd class="px-2 py-1 bg-gray-200 dark:bg-gray-700 rounded font-mono">Ctrl+P</kbd>
                    <span class="ml-2 text-gray-600 dark:text-gray-400">Personalizar</span>
                </div>
                <div>
                    <kbd class="px-2 py-1 bg-gray-200 dark:bg-gray-700 rounded font-mono">1-4</kbd>
                    <span class="ml-2 text-gray-600 dark:text-gray-400">Navegar cards</span>
                </div>
            </div>
        </div>
        <!-- Período Customizado -->
        <div x-show="period === 'custom'" 
             x-transition
             class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700 flex flex-wrap items-center gap-3">
            <div>
                <label class="text-xs text-gray-600 dark:text-gray-400 mb-1 block">Data Início</label>
                <input type="date" 
                       x-model="customStartDate"
                       @change="applyCustomPeriod()"
                       class="px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
            </div>
            <div>
                <label class="text-xs text-gray-600 dark:text-gray-400 mb-1 block">Data Fim</label>
                <input type="date" 
                       x-model="customEndDate"
                       @change="applyCustomPeriod()"
                       class="px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
            </div>
        </div>
    </div>

    <!-- Toast Notifications -->
    <div class="fixed top-20 right-4 z-50 space-y-2 max-w-sm" 
         x-data="{ toasts: [] }"
         x-init="$watch('showToast', value => {
             if (value) {
                 toasts.push({ id: Date.now(), message: toastMessage, type: 'success' });
                 setTimeout(() => {
                     toasts.shift();
                 }, 3000);
             }
         })">
        <template x-for="toast in toasts" :key="toast.id">
            <div x-show="true"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-x-full"
                 x-transition:enter-end="opacity-100 translate-x-0"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-x-0"
                 x-transition:leave-end="opacity-0 translate-x-full"
                 class="bg-white dark:bg-gray-800 rounded-lg shadow-xl border border-gray-200 dark:border-gray-700 p-4 flex items-center gap-3 backdrop-blur-sm">
                <div class="flex-shrink-0">
                    <svg class="w-5 h-5 text-green-500 animate-pulse" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-900 dark:text-white" x-text="toast.message"></p>
                </div>
                <button @click="toasts = toasts.filter(t => t.id !== toast.id)" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </template>
    </div>

    <!-- Indicador de Status do Sistema -->
    <div class="fixed bottom-4 left-4 z-40" x-data="{ status: 'online' }" x-init="status = navigator.onLine ? 'online' : 'offline'">
        <div class="flex items-center gap-2 px-3 py-2 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700">
            <div class="w-2 h-2 rounded-full" 
                 :class="status === 'online' ? 'bg-green-500 animate-pulse' : 'bg-red-500'"></div>
            <span class="text-xs font-medium text-gray-700 dark:text-gray-300" x-text="status === 'online' ? 'Sistema Online' : 'Sistema Offline'"></span>
        </div>
    </div>

    <!-- Insights e Recomendações -->
    @if(count($insights) > 0)
    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-xl shadow-sm border border-blue-200 dark:border-blue-800 p-6 mb-6"
         x-data="{ expanded: true }">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                </svg>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Insights e Recomendações</h3>
                <span class="px-2 py-0.5 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 text-xs font-bold rounded-full">
                    {{ count($insights) }}
                </span>
            </div>
            <button @click="expanded = !expanded" class="p-2 hover:bg-blue-100 dark:hover:bg-blue-900/30 rounded-lg transition-colors">
                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 transition-transform" :class="{ 'rotate-180': !expanded }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>
        </div>
        <div x-show="expanded" x-transition>
        <div class="space-y-3">
            @foreach($insights as $insight)
                <div class="flex items-start gap-3 p-3 bg-white dark:bg-gray-800 rounded-lg border border-{{ $insight['type'] === 'success' ? 'green' : ($insight['type'] === 'warning' ? 'yellow' : 'blue') }}-200 dark:border-{{ $insight['type'] === 'success' ? 'green' : ($insight['type'] === 'warning' ? 'yellow' : 'blue') }}-800">
                    <div class="flex-shrink-0 w-8 h-8 rounded-full bg-{{ $insight['type'] === 'success' ? 'green' : ($insight['type'] === 'warning' ? 'yellow' : 'blue') }}-100 dark:bg-{{ $insight['type'] === 'success' ? 'green' : ($insight['type'] === 'warning' ? 'yellow' : 'blue') }}-900/30 flex items-center justify-center">
                        @if($insight['icon'] === 'trending-up')
                            <svg class="w-4 h-4 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                            </svg>
                        @elseif($insight['icon'] === 'trending-down')
                            <svg class="w-4 h-4 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"/>
                            </svg>
                        @elseif($insight['icon'] === 'clock')
                            <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        @elseif($insight['icon'] === 'alert-triangle')
                            <svg class="w-4 h-4 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                        @elseif($insight['icon'] === 'trophy')
                            <svg class="w-4 h-4 text-green-600 dark:text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2 10.5a1.5 1.5 0 113 0v6a1.5 1.5 0 01-3 0v-6zM6 10.333v5.43a2 2 0 001.106 1.79l.05.025A4 4 0 008.943 18h5.416a2 2 0 001.962-1.608l1.2-6A2 2 0 0015.56 8H12V4a2 2 0 00-2-2 1 1 0 00-1 1v.667a4 4 0 01-.8 2.4L6.8 7.933a4 4 0 00-.8 2.4z"/>
                            </svg>
                        @elseif($insight['icon'] === 'target')
                            <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                            </svg>
                        @else
                            <svg class="w-4 h-4 text-green-600 dark:text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"/>
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd"/>
                            </svg>
                        @endif
                    </div>
                    <p class="text-sm text-gray-700 dark:text-gray-300 flex-1">{{ $insight['message'] }}</p>
                </div>
            @endforeach
        </div>
        </div>
    </div>
    @endif

    <!-- Skeleton Loading -->
    <div x-show="isLoading" class="grid gap-6 md:grid-cols-2 xl:grid-cols-4 mb-6">
        @for($i = 0; $i < 4; $i++)
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 animate-pulse">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-24 mb-3"></div>
                    <div class="h-8 bg-gray-300 dark:bg-gray-600 rounded w-20 mb-2"></div>
                    <div class="h-3 bg-gray-200 dark:bg-gray-700 rounded w-16"></div>
                </div>
                <div class="w-12 h-12 bg-gray-200 dark:bg-gray-700 rounded-lg"></div>
            </div>
        </div>
        @endfor
    </div>

    <!-- Stats Cards -->
    <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-4 mb-6" 
         x-show="!isLoading"
         id="stats-cards"
         :class="customizeMode ? 'cursor-move' : ''"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0">
        <div x-show="customizeMode" class="col-span-full mb-2 p-3 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg">
            <p class="text-sm text-yellow-800 dark:text-yellow-300 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Modo personalização ativo: Arraste os cards para reorganizar. O layout será salvo automaticamente.
            </p>
        </div>
        <!-- Pedidos Hoje -->
        <div class="dashboard-card bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-xl hover:scale-105 transition-all duration-300 group"
             :class="customizeMode ? 'cursor-move' : 'cursor-pointer'"
             data-card-id="pedidos"
             @click="!customizeMode && (window.location.href='{{ route('pedidos.index') }}')"
             x-data="{ index: 0 }"
             x-init="setTimeout(() => { index = 1 }, 100)"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0"
             :style="`transition-delay: ${index * 50}ms`">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Pedidos Hoje</p>
                    <div class="flex items-baseline gap-2 mt-2">
                        <p class="text-3xl font-bold text-gray-900 dark:text-white" data-stat="pedidos_hoje">{{ $stats['pedidos_hoje'] ?? 0 }}</p>
                        @if(($stats['percentual_pedidos'] ?? 0) != 0)
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold {{ $stats['percentual_pedidos'] > 0 ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400 animate-pulse' : 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400' }}">
                                <svg class="w-3.5 h-3.5 mr-1 {{ $stats['percentual_pedidos'] > 0 ? '' : 'rotate-180' }}" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 5.414V17a1 1 0 11-2 0V5.414L6.707 7.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                                </svg>
                                {{ abs($stats['percentual_pedidos'] ?? 0) }}%
                            </span>
                        @endif
                    </div>
                    <div class="flex items-center gap-2 mt-1">
                        <p class="text-xs text-gray-500 dark:text-gray-500">vs. ontem</p>
                        @php
                            $pedidosOntem = \App\Models\Pedido::where('restaurante_id', session('restaurante_id'))
                                ->whereDate('data_hora_pedido', today()->subDay())
                                ->count();
                        @endphp
                        <span class="text-xs font-medium {{ $stats['pedidos_hoje'] > $pedidosOntem ? 'text-green-600 dark:text-green-400' : ($stats['pedidos_hoje'] < $pedidosOntem ? 'text-red-600 dark:text-red-400' : 'text-gray-500') }}">
                            ({{ $pedidosOntem }} ontem)
                        </span>
                    </div>
                    <!-- Mini gráfico sparkline -->
                    <div class="mt-2 h-8 w-full" id="sparkline-pedidos"></div>
                </div>
                <div class="w-12 h-12 bg-red-100 dark:bg-red-900/30 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-center justify-between">
                <a href="{{ route('pedidos.index') }}" class="text-sm font-medium text-red-600 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300 group-hover:underline">
                    Ver pedidos →
                </a>
                <div class="text-xs text-gray-400 dark:text-gray-600">
                    Clique para ver detalhes
                </div>
            </div>
        </div>

        <!-- Receita Hoje -->
        <div class="dashboard-card bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-xl hover:scale-105 transition-all duration-300 group"
             :class="customizeMode ? 'cursor-move' : 'cursor-pointer'"
             data-card-id="receita"
             x-data="{ index: 1 }"
             x-init="setTimeout(() => { index = 1 }, 150)"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0"
             :style="`transition-delay: ${index * 50}ms`">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Receita Hoje</p>
                    <div class="flex items-baseline gap-2 mt-2">
                        <p class="text-3xl font-bold text-gray-900 dark:text-white" data-stat="receita_hoje">R$ {{ number_format($stats['receita_hoje'] ?? 0, 2, ',', '.') }}</p>
                        @if(($stats['percentual_receita_hoje'] ?? 0) != 0)
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold {{ $stats['percentual_receita_hoje'] > 0 ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400 animate-pulse' : 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400' }}">
                                <svg class="w-3.5 h-3.5 mr-1 {{ $stats['percentual_receita_hoje'] > 0 ? '' : 'rotate-180' }}" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 5.414V17a1 1 0 11-2 0V5.414L6.707 7.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                                </svg>
                                {{ abs($stats['percentual_receita_hoje'] ?? 0) }}%
                            </span>
                        @endif
                    </div>
                    <div class="flex items-center gap-2 mt-1">
                        <p class="text-xs text-gray-500 dark:text-gray-500">vs. ontem</p>
                        @php
                            $receitaOntem = \App\Models\Pedido::where('restaurante_id', session('restaurante_id'))
                                ->whereDate('data_hora_pedido', today()->subDay())
                                ->where('status', 'concluido')
                                ->sum('valor_total') ?? 0;
                        @endphp
                        <span class="text-xs font-medium {{ $stats['receita_hoje'] > $receitaOntem ? 'text-green-600 dark:text-green-400' : ($stats['receita_hoje'] < $receitaOntem ? 'text-red-600 dark:text-red-400' : 'text-gray-500') }}">
                            (R$ {{ number_format($receitaOntem, 2, ',', '.') }} ontem)
                        </span>
                    </div>
                    <!-- Mini gráfico sparkline -->
                    <div class="mt-2 h-8 w-full" id="sparkline-receita"></div>
                </div>
                <div class="w-12 h-12 bg-red-100 dark:bg-red-900/30 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Alertas Críticos -->
        <div class="dashboard-card bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-xl hover:scale-105 transition-all duration-300 group {{ $stats['alertas_count'] > 0 ? 'ring-2 ring-red-200 dark:ring-red-900/50' : '' }}"
             :class="customizeMode ? 'cursor-move' : 'cursor-pointer'"
             data-card-id="alertas"
             @click="!customizeMode && (window.location.href='{{ route('alertas.index') }}')"
             x-data="{ index: 2 }"
             x-init="setTimeout(() => { index = 1 }, 200)"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0"
             :style="`transition-delay: ${index * 50}ms`">
            <div class="flex items-center justify-between">
                <div>
                    <div class="flex items-center gap-2">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Alertas Ativos</p>
                        @if($stats['alertas_count'] > 0)
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold text-white bg-red-600 animate-pulse">
                                {{ $stats['alertas_count'] > 9 ? '9+' : $stats['alertas_count'] }}
                            </span>
                        @endif
                    </div>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $stats['alertas_count'] ?? 0 }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">
                        {{ $stats['itens_estoque_critico'] ?? 0 }} itens críticos
                    </p>
                </div>
                <div class="w-12 h-12 bg-red-100 dark:bg-red-900/30 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform {{ $stats['alertas_count'] > 0 ? 'animate-pulse' : '' }}">
                    <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-center justify-between">
                <a href="{{ route('alertas.index') }}" class="text-sm font-medium text-red-600 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300 group-hover:underline">
                    Ver alertas →
                </a>
                <div class="text-xs text-gray-400 dark:text-gray-600">
                    Clique para ver detalhes
                </div>
            </div>
        </div>

        <!-- Total de Itens -->
        <div class="dashboard-card bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-xl hover:scale-105 transition-all duration-300 group"
             :class="customizeMode ? 'cursor-move' : 'cursor-pointer'"
             data-card-id="cardapio"
             @click="!customizeMode && (window.location.href='{{ route('cardapio-itens.index') }}')"
             x-data="{ index: 3 }"
             x-init="setTimeout(() => { index = 1 }, 250)"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0"
             :style="`transition-delay: ${index * 50}ms`">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Cardápio</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $stats['cardapio_count'] ?? 0 }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">
                        {{ $stats['insumos_count'] ?? 0 }} insumos
                    </p>
                </div>
                <div class="w-12 h-12 bg-red-100 dark:bg-red-900/30 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-center justify-between">
                <a href="{{ route('cardapio-itens.index') }}" class="text-sm font-medium text-red-600 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300 group-hover:underline">
                    Ver cardápio →
                </a>
                <div class="text-xs text-gray-400 dark:text-gray-600">
                    Clique para ver detalhes
                </div>
            </div>
        </div>
    </div>

    <!-- Extra Metrics Row -->
    <div class="grid gap-4 md:grid-cols-3 mb-6">
        <!-- Ticket Médio -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Ticket Médio</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2" data-stat="ticket_medio">R$ {{ number_format($stats['ticket_medio'] ?? 0, 2, ',', '.') }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">por pedido hoje</p>
                </div>
                <div class="w-12 h-12 bg-red-100 dark:bg-red-900/30 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Horário de Pico -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Horário de Pico</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $stats['horario_pico'] ?? 'N/A' }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">{{ $stats['horario_pico_pedidos'] ?? 0 }} pedidos</p>
                </div>
                <div class="w-12 h-12 bg-red-100 dark:bg-red-900/30 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Meta do Mês -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-3">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Meta do Mês</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ number_format($stats['progresso_meta'] ?? 0, 1) }}%</p>
                    <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">R$ {{ number_format($stats['receita_mes'] ?? 0, 2, ',', '.') }} / R$ {{ number_format($stats['meta_mes'] ?? 0, 2, ',', '.') }}</p>
                </div>
                <div class="w-12 h-12 bg-red-100 dark:bg-red-900/30 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                    </svg>
                </div>
            </div>
            <!-- Progress Bar -->
            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-3">
                <div class="h-3 rounded-full transition-all duration-500 {{ ($stats['progresso_meta'] ?? 0) >= 100 ? 'bg-green-500' : (($stats['progresso_meta'] ?? 0) >= 75 ? 'bg-red-500' : 'bg-yellow-500') }}"
                     style="width: {{ min($stats['progresso_meta'] ?? 0, 100) }}%"></div>
            </div>
        </div>
    </div>

    <!-- KPIs Detalhados -->
    <div class="bg-gradient-to-r from-slate-50 to-gray-50 dark:from-slate-900/50 dark:to-gray-900/50 rounded-xl border border-gray-200 dark:border-gray-700 p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"/>
                </svg>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Indicadores de Performance (KPIs)</h3>
            </div>
            <span class="text-xs text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 px-2 py-1 rounded-full">Atualizado hoje</span>
        </div>

        <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-5">
            <!-- Taxa de Conversão -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 hover:shadow-md transition-all hover:scale-105 group">
                <div class="flex items-center justify-between mb-2">
                    <div class="w-10 h-10 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <span class="text-xs px-2 py-0.5 rounded-full {{ ($stats['taxa_conversao'] ?? 0) >= 80 ? 'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400' : (($stats['taxa_conversao'] ?? 0) >= 60 ? 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400' : 'bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400') }}">
                        {{ ($stats['taxa_conversao'] ?? 0) >= 80 ? 'Ótimo' : (($stats['taxa_conversao'] ?? 0) >= 60 ? 'Bom' : 'Atenção') }}
                    </span>
                </div>
                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Taxa de Conversão</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ number_format($stats['taxa_conversao'] ?? 0, 1) }}%</p>
                <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">
                    {{ $stats['pedidos_concluidos'] ?? 0 }} de {{ $stats['pedidos_hoje'] ?? 0 }} concluídos
                </p>
                @if(($stats['pedidos_cancelados'] ?? 0) > 0)
                    <p class="text-xs text-red-600 dark:text-red-400 mt-1">
                        {{ $stats['pedidos_cancelados'] ?? 0 }} cancelado{{ ($stats['pedidos_cancelados'] ?? 0) > 1 ? 's' : '' }}
                    </p>
                @endif
            </div>

            <!-- Tempo Médio de Preparo -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 hover:shadow-md transition-all hover:scale-105 group">
                <div class="flex items-center justify-between mb-2">
                    <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <span class="text-xs px-2 py-0.5 rounded-full {{ ($stats['tempo_medio_preparo'] ?? 0) <= 25 ? 'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400' : (($stats['tempo_medio_preparo'] ?? 0) <= 40 ? 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400' : 'bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400') }}">
                        {{ ($stats['tempo_medio_preparo'] ?? 0) <= 25 ? 'Rápido' : (($stats['tempo_medio_preparo'] ?? 0) <= 40 ? 'Normal' : 'Lento') }}
                    </span>
                </div>
                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Tempo de Preparo</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">
                    @if(($stats['tempo_medio_preparo'] ?? 0) > 0)
                        {{ round($stats['tempo_medio_preparo']) }} min
                    @else
                        <span class="text-gray-400 dark:text-gray-600">--</span>
                    @endif
                </p>
                <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">
                    Estimativa média
                </p>
                <div class="mt-2 flex items-center text-xs">
                    @if(($stats['tempo_medio_preparo'] ?? 0) > 0)
                        @if(($stats['tempo_medio_preparo'] ?? 0) <= 25)
                            <span class="text-green-600 dark:text-green-400">⚡ Eficiência alta</span>
                        @elseif(($stats['tempo_medio_preparo'] ?? 0) > 45)
                            <span class="text-red-600 dark:text-red-400">⚠️ Revisar processos</span>
                        @endif
                    @else
                        <span class="text-gray-400 dark:text-gray-600">Sem dados hoje</span>
                    @endif
                </div>
            </div>

            <!-- Taxa de Recompra -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 hover:shadow-md transition-all hover:scale-105 group opacity-60">
                <div class="flex items-center justify-between mb-2">
                    <div class="w-10 h-10 bg-purple-100 dark:bg-purple-900/30 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <span class="text-xs px-2 py-0.5 rounded-full bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-400">
                        Em breve
                    </span>
                </div>
                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Taxa de Recompra</p>
                <p class="text-2xl font-bold text-gray-400 dark:text-gray-600 mt-1">
                    <span class="text-gray-400 dark:text-gray-600">--</span>
                </p>
                <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">
                    Funcionalidade em desenvolvimento
                </p>
                <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">
                    Requer cadastro de clientes
                </p>
            </div>

            <!-- Margem de Lucro -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 hover:shadow-md transition-all hover:scale-105 group">
                <div class="flex items-center justify-between mb-2">
                    <div class="w-10 h-10 bg-emerald-100 dark:bg-emerald-900/30 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <span class="text-xs px-2 py-0.5 rounded-full {{ ($stats['margem_lucro'] ?? 0) >= 60 ? 'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400' : (($stats['margem_lucro'] ?? 0) >= 40 ? 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400' : 'bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400') }}">
                        {{ ($stats['margem_lucro'] ?? 0) >= 60 ? 'Alta' : (($stats['margem_lucro'] ?? 0) >= 40 ? 'Média' : 'Baixa') }}
                    </span>
                </div>
                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Margem de Lucro</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ number_format($stats['margem_lucro'] ?? 0, 1) }}%</p>
                <p class="text-xs text-emerald-600 dark:text-emerald-400 mt-1 font-medium">
                    +R$ {{ number_format(($stats['receita_hoje'] ?? 0) - ($stats['custo_insumos_hoje'] ?? 0), 2, ',', '.') }}
                </p>
                <p class="text-xs text-gray-500 dark:text-gray-500">
                    Custo: R$ {{ number_format($stats['custo_insumos_hoje'] ?? 0, 2, ',', '.') }}
                </p>
            </div>

            <!-- Itens em Risco Crítico Hoje -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 hover:shadow-md transition-all hover:scale-105 group {{ ($stats['itens_risco_critico_hoje'] ?? 0) > 0 ? 'ring-2 ring-orange-200 dark:ring-orange-900/50' : '' }}">
                <div class="flex items-center justify-between mb-2">
                    <div class="w-10 h-10 bg-orange-100 dark:bg-orange-900/30 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform {{ ($stats['itens_risco_critico_hoje'] ?? 0) > 0 ? 'animate-pulse' : '' }}">
                        <svg class="w-5 h-5 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    </div>
                    @if(($stats['itens_risco_critico_hoje'] ?? 0) > 0)
                        <span class="text-xs px-2 py-0.5 rounded-full bg-orange-100 dark:bg-orange-900/30 text-orange-700 dark:text-orange-400 font-medium">
                            Urgente
                        </span>
                    @else
                        <span class="text-xs px-2 py-0.5 rounded-full bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400">
                            OK
                        </span>
                    @endif
                </div>
                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Risco de Falta Hoje</p>
                <p class="text-2xl font-bold {{ ($stats['itens_risco_critico_hoje'] ?? 0) > 0 ? 'text-orange-600 dark:text-orange-400' : 'text-gray-900 dark:text-white' }} mt-1">
                    {{ $stats['itens_risco_critico_hoje'] ?? 0 }}
                </p>
                <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">
                    {{ ($stats['itens_risco_critico_hoje'] ?? 0) === 1 ? 'item pode' : 'itens podem' }} acabar
                </p>
                @if(($stats['itens_risco_critico_hoje'] ?? 0) > 0)
                    <a href="{{ route('estoque.index') }}" class="text-xs text-orange-600 dark:text-orange-400 hover:underline mt-1 inline-block">
                        Ver estoque →
                    </a>
                @else
                    <p class="text-xs text-green-600 dark:text-green-400 mt-1">
                        ✓ Estoque seguro
                    </p>
                @endif
            </div>
        </div>
    </div>

    <!-- Status de Pedidos em Tempo Real -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Status dos Pedidos Hoje</h3>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                    <span class="inline-flex items-center">
                        <svg class="w-3 h-3 mr-1 animate-pulse" fill="currentColor" viewBox="0 0 20 20">
                            <circle cx="10" cy="10" r="8"/>
                        </svg>
                        Atualiza a cada 30 segundos | Última atualização: <span x-text="lastUpdate" class="font-medium">--:--:--</span>
                    </span>
                </p>
            </div>
            <button @click="refreshData()" class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors" title="Atualizar agora">
                <svg class="w-5 h-5 text-gray-600 dark:text-gray-400" :class="{'animate-spin': isRefreshing}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
            </button>
        </div>
        <div class="grid grid-cols-3 gap-4">
            <!-- Pendentes -->
            <div class="relative overflow-hidden bg-gradient-to-br from-yellow-50 to-yellow-100 dark:from-yellow-900/20 dark:to-yellow-800/20 rounded-lg p-4 border-2 border-yellow-200 dark:border-yellow-800">
                <div class="absolute top-0 right-0 w-20 h-20 bg-yellow-200 dark:bg-yellow-700 rounded-full -mr-10 -mt-10 opacity-20"></div>
                <div class="relative">
                    <p class="text-sm font-medium text-yellow-800 dark:text-yellow-300 mb-1">Pendentes</p>
                    <p class="text-3xl font-bold text-yellow-900 dark:text-yellow-100" x-text="statusPedidos.pendentes">{{ $statusPedidos['pendentes'] }}</p>
                    <div class="mt-2 flex items-center text-xs text-yellow-700 dark:text-yellow-400">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                        </svg>
                        Aguardando
                    </div>
                </div>
            </div>

            <!-- Em Preparo -->
            <div class="relative overflow-hidden bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 rounded-lg p-4 border-2 border-blue-200 dark:border-blue-800">
                <div class="absolute top-0 right-0 w-20 h-20 bg-blue-200 dark:bg-blue-700 rounded-full -mr-10 -mt-10 opacity-20"></div>
                <div class="relative">
                    <p class="text-sm font-medium text-blue-800 dark:text-blue-300 mb-1">Em Preparo</p>
                    <p class="text-3xl font-bold text-blue-900 dark:text-blue-100" x-text="statusPedidos.em_preparo">{{ $statusPedidos['em_preparo'] }}</p>
                    <div class="mt-2 flex items-center text-xs text-blue-700 dark:text-blue-400">
                        <svg class="w-4 h-4 mr-1 animate-pulse" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M11 3a1 1 0 10-2 0v1a1 1 0 102 0V3zM15.657 5.757a1 1 0 00-1.414-1.414l-.707.707a1 1 0 001.414 1.414l.707-.707zM18 10a1 1 0 01-1 1h-1a1 1 0 110-2h1a1 1 0 011 1zM5.05 6.464A1 1 0 106.464 5.05l-.707-.707a1 1 0 00-1.414 1.414l.707.707zM5 10a1 1 0 01-1 1H3a1 1 0 110-2h1a1 1 0 011 1zM8 16v-1h4v1a2 2 0 11-4 0zM12 14c.015-.34.208-.646.477-.859a4 4 0 10-4.954 0c.27.213.462.519.476.859h4.002z"/>
                        </svg>
                        Na cozinha
                    </div>
                </div>
            </div>

            <!-- Prontos -->
            <div class="relative overflow-hidden bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-800/20 rounded-lg p-4 border-2 border-green-200 dark:border-green-800">
                <div class="absolute top-0 right-0 w-20 h-20 bg-green-200 dark:bg-green-700 rounded-full -mr-10 -mt-10 opacity-20"></div>
                <div class="relative">
                    <p class="text-sm font-medium text-green-800 dark:text-green-300 mb-1">Prontos</p>
                    <p class="text-3xl font-bold text-green-900 dark:text-green-100" x-text="statusPedidos.pronto">{{ $statusPedidos['pronto'] }}</p>
                    <div class="mt-2 flex items-center text-xs text-green-700 dark:text-green-400">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        Para entrega
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Monthly Revenue Card -->
    <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 mb-6">
        <div class="flex items-center justify-between">
            <div class="text-white">
                <p class="text-sm font-medium opacity-90">Receita do Mês</p>
                <div class="flex items-baseline gap-3 mt-2">
                    <p class="text-4xl font-bold">R$ {{ number_format($stats['receita_mes'] ?? 0, 2, ',', '.') }}</p>
                    @if(($stats['percentual_receita_mes'] ?? 0) != 0)
                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-sm font-medium bg-white/20 backdrop-blur-sm">
                            <svg class="w-4 h-4 mr-1 {{ ($stats['percentual_receita_mes'] ?? 0) > 0 ? '' : 'rotate-180' }}" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 5.414V17a1 1 0 11-2 0V5.414L6.707 7.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                            </svg>
                            {{ abs($stats['percentual_receita_mes'] ?? 0) }}%
                        </span>
                    @endif
                </div>
                <p class="text-sm opacity-75 mt-1">vs. mês anterior</p>
            </div>
            <div class="w-16 h-16 bg-white/10 backdrop-blur-sm rounded-xl flex items-center justify-center">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="grid gap-6 lg:grid-cols-3 mb-6">
        <!-- Gráfico de Vendas -->
        <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Pedidos dos Últimos Dias</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Evolução diária com comparação</p>
                </div>
                <select x-model="chartPeriod" @change="loadChartData()" class="text-sm border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-1.5 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                    <option value="7">7 dias</option>
                    <option value="15">15 dias</option>
                    <option value="30">30 dias</option>
                </select>
            </div>
            <div id="salesChart" class="h-64"></div>
        </div>

        <!-- Distribuição por Plataforma -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Pedidos por Plataforma</h3>
            <div id="platformChart" class="min-h-64 max-h-96 overflow-y-auto"></div>
        </div>
    </div>

    <!-- Gráfico Receita vs Custos -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Receita vs Custos</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">Análise financeira do período</p>
            </div>
        </div>
        <div id="revenueChart" class="h-80"></div>
    </div>

    <!-- Itens Mais Vendidos Row -->
    <div class="grid gap-6 lg:grid-cols-2 mb-6">
        <!-- Itens Mais Vendidos -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6"
             x-data="{ sortBy: 'quantidade', sortOrder: 'desc' }">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Top 5 Itens Mais Vendidos</h3>
                <div class="flex items-center gap-2">
                    <button @click="sortBy = 'quantidade'; sortOrder = sortOrder === 'asc' ? 'desc' : 'asc'"
                            class="px-3 py-1.5 text-xs font-medium rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                        Ordenar
                    </button>
                </div>
            </div>
            <div class="space-y-3">
                @forelse($itensMaisVendidos as $index => $item)
                    <div class="flex items-center justify-between p-3 rounded-lg bg-gray-50 dark:bg-gray-900/50 hover:bg-gray-100 dark:hover:bg-gray-700/50 transition-all hover:scale-[1.02] cursor-pointer group"
                         @click="window.location.href='{{ route('cardapio-itens.index') }}'">
                        <div class="flex items-center gap-3 flex-1 min-w-0">
                            <div class="flex-shrink-0 w-8 h-8 rounded-full bg-gradient-to-br from-red-500 to-red-600 flex items-center justify-center text-white font-bold text-sm">
                                {{ $index + 1 }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ $item->nome }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $item->total_vendido }} vendidos</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-semibold text-gray-900 dark:text-white">R$ {{ number_format($item->receita_total, 2, ',', '.') }}</p>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8">
                        <svg class="w-12 h-12 mx-auto text-gray-400 dark:text-gray-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                        </svg>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Nenhum item vendido nos últimos 30 dias</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Bottom Row -->
    <div class="grid gap-6 lg:grid-cols-2">
        <!-- Alertas Críticos -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Alertas Críticos</h3>
                <a href="{{ route('alertas.index') }}" class="text-sm font-medium text-red-600 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300">
                    Ver todos →
                </a>
            </div>
            <div class="space-y-2">
                @forelse($alertasCriticos as $alerta)
                    <div class="flex items-start gap-3 p-3 rounded-lg border border-red-100 dark:border-red-900/30 bg-red-50 dark:bg-red-900/10">
                        <div class="flex-shrink-0 w-8 h-8 bg-red-100 dark:bg-red-900/30 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $alerta->insumo->nome }}</p>
                            <p class="text-xs text-gray-600 dark:text-gray-400 mt-0.5">{{ $alerta->mensagem }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">{{ $alerta->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8">
                        <svg class="w-12 h-12 mx-auto text-green-500 dark:text-green-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Nenhum alerta crítico</p>
                        <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Tudo sob controle! 🎉</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Últimos Pedidos -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6"
             x-data="{ sortBy: 'data', sortOrder: 'desc' }">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Últimos Pedidos</h3>
                <div class="flex items-center gap-2">
                    <button @click="sortBy = 'data'; sortOrder = sortOrder === 'asc' ? 'desc' : 'asc'"
                            class="px-3 py-1.5 text-xs font-medium rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                        Ordenar
                    </button>
                    <a href="{{ route('pedidos.index') }}" class="text-sm font-medium text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300">
                        Ver todos →
                    </a>
                </div>
            </div>
            <div class="space-y-2">
                @forelse($ultimosPedidos as $pedido)
                    <div class="flex items-center justify-between p-3 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-all hover:scale-[1.02] cursor-pointer group"
                         @click="window.location.href='{{ route('pedidos.index') }}?search={{ $pedido->id }}'">
                        <div class="flex items-center gap-3 flex-1 min-w-0">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 rounded-lg bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 dark:text-white">
                                    Pedido #{{ $pedido->id }}
                                    @if($pedido->numero_pedido_externo)
                                        <span class="text-gray-500 dark:text-gray-400">· {{ $pedido->numero_pedido_externo }}</span>
                                    @endif
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $pedido->data_hora_pedido?->format('d/m/Y H:i') }} · {{ $pedido->plataforma_origem }}
                                </p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-semibold text-gray-900 dark:text-white">
                                @if($pedido->valor_total)
                                    R$ {{ number_format($pedido->valor_total, 2, ',', '.') }}
                                @else
                                    -
                                @endif
                            </p>
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                                {{ strtolower($pedido->status) === 'concluido' ? 'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400' : 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400' }}">
                                {{ ucfirst($pedido->status) }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8">
                        <svg class="w-12 h-12 mx-auto text-gray-400 dark:text-gray-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Nenhum pedido registrado</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Quick Actions FAB -->
    <div class="fixed bottom-6 right-6 z-40" x-data="{ open: false }">
        <button @click="open = !open"
                class="w-14 h-14 bg-gradient-to-br from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white rounded-full shadow-lg hover:shadow-xl transition-all duration-200 flex items-center justify-center group"
                title="Ações Rápidas (Ctrl+K)">
            <svg class="w-6 h-6 transition-transform" :class="{ 'rotate-45': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
        </button>

        <div x-show="open"
             @click.away="open = false"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 transform scale-95 translate-y-4"
             x-transition:enter-end="opacity-100 transform scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 transform scale-100"
             x-transition:leave-end="opacity-0 transform scale-95"
             class="absolute bottom-16 right-0 mb-2 space-y-2"
             style="display: none;">
            <a href="{{ route('pedidos.create') }}" class="flex items-center gap-3 px-4 py-3 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 text-gray-900 dark:text-white rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 transition-all hover:scale-105 whitespace-nowrap">
                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                <span class="text-sm font-medium">Novo Pedido</span>
            </a>
            <a href="{{ route('insumos.create') }}" class="flex items-center gap-3 px-4 py-3 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 text-gray-900 dark:text-white rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 transition-all hover:scale-105 whitespace-nowrap">
                <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
                <span class="text-sm font-medium">Novo Insumo</span>
            </a>
            <a href="{{ route('cardapio-itens.create') }}" class="flex items-center gap-3 px-4 py-3 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 text-gray-900 dark:text-white rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 transition-all hover:scale-105 whitespace-nowrap">
                <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
                <span class="text-sm font-medium">Novo Item</span>
            </a>
            <a href="{{ route('receitas.create') }}" class="flex items-center gap-3 px-4 py-3 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 text-gray-900 dark:text-white rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 transition-all hover:scale-105 whitespace-nowrap">
                <svg class="w-5 h-5 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <span class="text-sm font-medium">Nova Receita</span>
            </a>
            <a href="{{ route('estoque.index') }}" class="flex items-center gap-3 px-4 py-3 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 text-gray-900 dark:text-white rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 transition-all hover:scale-105 whitespace-nowrap">
                <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                </svg>
                <span class="text-sm font-medium">Adicionar Estoque</span>
            </a>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
function dashboardData() {
    return {
        chartPeriod: '7',
        period: 'hoje',
        customStartDate: '',
        customEndDate: '',
        customizeMode: false,
        chart: null,
        platformChart: null,
        revenueChart: null,
        isRefreshing: false,
        isLoading: false,
        lastUpdate: new Date().toLocaleTimeString('pt-BR'),
        showToast: false,
        toastMessage: '',
        previousPendentes: {{ $statusPedidos['pendentes'] }},
        statusPedidos: {
            pendentes: {{ $statusPedidos['pendentes'] }},
            em_preparo: {{ $statusPedidos['em_preparo'] }},
            pronto: {{ $statusPedidos['pronto'] }}
        },

        async init() {
            // Carregar layout salvo
            this.loadSavedLayout();
            
            // Inicializar drag and drop
            this.initDragAndDrop();
            
            await this.loadChartData();
            this.renderPlatformChart();
            this.renderRevenueChart();
            this.renderSparklines();

            // Auto-refresh a cada 30 segundos
            setInterval(() => {
                this.refreshData();
            }, 30000);

            // Atalhos de teclado
            this.initKeyboardShortcuts();
        },

        renderSparklines() {
            // Mini gráficos sparkline para os cards principais
            const sparklineData = [10, 15, 12, 18, 20, 16, 22, 19, 25, 23, 28, 26];
            
            // Sparkline para pedidos
            const pedidosSparkline = {
                series: [{
                    name: 'Pedidos',
                    data: sparklineData
                }],
                chart: {
                    type: 'area',
                    height: 32,
                    sparkline: {
                        enabled: true
                    },
                    toolbar: {
                        show: false
                    }
                },
                stroke: {
                    curve: 'smooth',
                    width: 2,
                    colors: ['#ef4444']
                },
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.4,
                        opacityTo: 0.1,
                        stops: [0, 100]
                    },
                    colors: ['#ef4444']
                },
                tooltip: {
                    fixed: {
                        enabled: false
                    },
                    x: {
                        show: false
                    },
                    y: {
                        title: {
                            formatter: function (seriesName) {
                                return ''
                            }
                        }
                    },
                    marker: {
                        show: false
                    }
                }
            };

            // Sparkline para receita
            const receitaSparkline = {
                ...pedidosSparkline,
                stroke: {
                    ...pedidosSparkline.stroke,
                    colors: ['#10b981']
                },
                fill: {
                    ...pedidosSparkline.fill,
                    colors: ['#10b981']
                }
            };

            if (typeof ApexCharts !== 'undefined') {
                const pedidosEl = document.getElementById('sparkline-pedidos');
                const receitaEl = document.getElementById('sparkline-receita');
                
                if (pedidosEl) {
                    new ApexCharts(pedidosEl, pedidosSparkline).render();
                }
                if (receitaEl) {
                    new ApexCharts(receitaEl, receitaSparkline).render();
                }
            }
        },

        setPeriod(newPeriod) {
            this.period = newPeriod;
            this.loadDataForPeriod();
        },

        applyCustomPeriod() {
            if (this.customStartDate && this.customEndDate) {
                this.loadDataForPeriod();
            }
        },

        async loadDataForPeriod() {
            this.isLoading = true;
            try {
                const params = new URLSearchParams({
                    period: this.period,
                    start: this.customStartDate,
                    end: this.customEndDate
                });
                const response = await fetch(`/dashboard/data?${params}`);
                const data = await response.json();
                
                // Atualizar dados do dashboard
                this.updateDashboardData(data);
            } catch (error) {
                console.error('Erro ao carregar dados:', error);
            } finally {
                this.isLoading = false;
            }
        },

        updateDashboardData(data) {
            // Atualizar stats com animação
            this.updateStatsWithAnimation(data.stats);
            this.statusPedidos = data.statusPedidos;
        },

        toggleCustomizeMode() {
            this.customizeMode = !this.customizeMode;
            if (this.customizeMode) {
                this.initDragAndDrop();
                this.showToastNotification('Modo personalização ativado. Arraste os cards para reorganizar.');
            } else {
                this.showToastNotification('Layout salvo com sucesso!');
            }
        },

        initDragAndDrop() {
            if (typeof Sortable === 'undefined') {
                // Carregar SortableJS se não estiver disponível
                const script = document.createElement('script');
                script.src = 'https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js';
                script.onload = () => this.setupSortable();
                document.head.appendChild(script);
            } else {
                this.setupSortable();
            }
        },

        setupSortable() {
            if (!this.customizeMode) return;
            
            const container = document.getElementById('stats-cards');
            if (container && typeof Sortable !== 'undefined') {
                new Sortable(container, {
                    animation: 150,
                    handle: '.dashboard-card',
                    onEnd: (evt) => {
                        this.saveLayout();
                    }
                });
            }
        },

        saveLayout() {
            const cards = Array.from(document.querySelectorAll('.dashboard-card'));
            const layout = cards.map(card => card.dataset.cardId);
            localStorage.setItem('dashboard_layout', JSON.stringify(layout));
        },

        loadSavedLayout() {
            const saved = localStorage.getItem('dashboard_layout');
            if (saved) {
                try {
                    const layout = JSON.parse(saved);
                    // Reordenar cards conforme layout salvo
                    const container = document.getElementById('stats-cards');
                    if (container) {
                        layout.forEach(cardId => {
                            const card = container.querySelector(`[data-card-id="${cardId}"]`);
                            if (card) container.appendChild(card);
                        });
                    }
                } catch (e) {
                    console.error('Erro ao carregar layout:', e);
                }
            }
        },

        async exportDashboard() {
            try {
                this.showToastNotification('Preparando exportação...');
                
                // Criar dados para exportação
                const exportData = {
                    periodo: this.period,
                    stats: this.getCurrentStats(),
                    timestamp: new Date().toLocaleString('pt-BR')
                };

                // Exportar como JSON (pode ser expandido para PDF/Excel)
                const dataStr = JSON.stringify(exportData, null, 2);
                const dataBlob = new Blob([dataStr], { type: 'application/json' });
                const url = URL.createObjectURL(dataBlob);
                const link = document.createElement('a');
                link.href = url;
                link.download = `dashboard-${this.period}-${Date.now()}.json`;
                link.click();
                URL.revokeObjectURL(url);

                this.showToastNotification('Exportação concluída!');
            } catch (error) {
                console.error('Erro ao exportar:', error);
                this.showToastNotification('Erro ao exportar dados');
            }
        },

        getCurrentStats() {
            // Coletar todos os stats visíveis
            const stats = {};
            document.querySelectorAll('[data-stat]').forEach(el => {
                stats[el.dataset.stat] = el.textContent.trim();
            });
            return stats;
        },

        initKeyboardShortcuts() {
            document.addEventListener('keydown', (e) => {
                // Ctrl/Cmd + K para ações rápidas
                if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                    e.preventDefault();
                    // Abrir FAB de ações rápidas
                    const fab = document.querySelector('[x-data*="open"]');
                    if (fab) {
                        const fabData = Alpine.$data(fab);
                        if (fabData) fabData.open = !fabData.open;
                    }
                }
                // Ctrl/Cmd + E para exportar
                if ((e.ctrlKey || e.metaKey) && e.key === 'e') {
                    e.preventDefault();
                    this.exportDashboard();
                }
                // Ctrl/Cmd + P para personalizar
                if ((e.ctrlKey || e.metaKey) && e.key === 'p') {
                    e.preventDefault();
                    this.toggleCustomizeMode();
                }
                // Números para navegação rápida
                if (e.key >= '1' && e.key <= '4' && !e.ctrlKey && !e.metaKey && !e.shiftKey && !e.altKey) {
                    const cards = document.querySelectorAll('.dashboard-card');
                    const index = parseInt(e.key) - 1;
                    if (cards[index]) {
                        cards[index].click();
                    }
                }
            });
        },

        async refreshData() {
            if (this.isRefreshing) return;

            this.isRefreshing = true;
            try {
                const response = await fetch('/dashboard/refresh-stats');
                const data = await response.json();

                if (data.success) {
                    // Verificar novos pedidos pendentes
                    if (data.statusPedidos.pendentes > this.previousPendentes) {
                        const diff = data.statusPedidos.pendentes - this.previousPendentes;
                        this.showToastNotification(`${diff} novo${diff > 1 ? 's' : ''} pedido${diff > 1 ? 's' : ''} pendente${diff > 1 ? 's' : ''}!`);
                    }

                    this.previousPendentes = data.statusPedidos.pendentes;

                    // Atualizar status de pedidos
                    this.statusPedidos = data.statusPedidos;

                    // Atualizar stats nos cards (com animação)
                    this.updateStatsWithAnimation(data.stats);

                    // Atualizar timestamp
                    this.lastUpdate = data.timestamp;

                    // Toast de confirmação (discreto)
                    if (!this.showToast) {
                        this.showToastNotification('Dados atualizados');
                    }
                }
            } catch (error) {
                console.error('Erro ao atualizar dados:', error);
            } finally {
                this.isRefreshing = false;
            }
        },

        showToastNotification(message) {
            this.toastMessage = message;
            this.showToast = true;

            setTimeout(() => {
                this.showToast = false;
            }, 3000);
        },

        updateStatsWithAnimation(stats) {
            // Atualizar pedidos hoje
            const pedidosEl = document.querySelector('[data-stat="pedidos_hoje"]');
            if (pedidosEl) this.animateNumber(pedidosEl, parseInt(pedidosEl.textContent), stats.pedidos_hoje);

            // Atualizar receita hoje
            const receitaEl = document.querySelector('[data-stat="receita_hoje"]');
            if (receitaEl) {
                const oldValue = parseFloat(receitaEl.textContent.replace(/[^0-9,]/g, '').replace(',', '.'));
                this.animateNumber(receitaEl, oldValue, stats.receita_hoje, true);
            }

            // Atualizar ticket médio
            const ticketEl = document.querySelector('[data-stat="ticket_medio"]');
            if (ticketEl) {
                const oldValue = parseFloat(ticketEl.textContent.replace(/[^0-9,]/g, '').replace(',', '.'));
                this.animateNumber(ticketEl, oldValue, stats.ticket_medio, true);
            }
        },

        animateNumber(element, start, end, isCurrency = false) {
            const duration = 1000;
            const steps = 30;
            const stepValue = (end - start) / steps;
            let current = start;
            let step = 0;

            const interval = setInterval(() => {
                step++;
                current += stepValue;

                if (step >= steps) {
                    current = end;
                    clearInterval(interval);
                }

                if (isCurrency) {
                    element.textContent = 'R$ ' + current.toFixed(2).replace('.', ',').replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.');
                } else {
                    element.textContent = Math.round(current);
                }
            }, duration / steps);
        },

        async loadChartData() {
            try {
                const response = await fetch(`/dashboard/chart-data?period=${this.chartPeriod}`);
                const data = await response.json();

                this.renderChart(data);
            } catch (error) {
                console.error('Erro ao carregar dados do gráfico:', error);
            }
        },

        async renderChart(data) {
            // Destroy previous chart if exists
            if (this.chart) {
                this.chart.destroy();
            }

            const isDark = document.documentElement.classList.contains('dark');

            // Buscar dados do período anterior para comparação
            const previousData = await this.getPreviousPeriodData(data);

            const options = {
                series: [
                    {
                        name: 'Pedidos (Atual)',
                        data: data.map(d => d.total),
                        type: 'area'
                    },
                    {
                        name: 'Pedidos (Período Anterior)',
                        data: previousData.map(d => d.total),
                        type: 'line',
                        stroke: {
                            width: 2,
                            dashArray: 5
                        },
                        fill: {
                            type: 'solid',
                            opacity: 0
                        }
                    }
                ],
                chart: {
                    type: 'area',
                    height: 256,
                    toolbar: {
                        show: false
                    },
                    zoom: {
                        enabled: false
                    },
                    background: 'transparent',
                    fontFamily: 'Inter, sans-serif'
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    curve: 'smooth',
                    width: 3,
                    colors: ['#3b82f6']
                },
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.4,
                        opacityTo: 0.1,
                        stops: [0, 90, 100]
                    },
                    colors: ['#3b82f6']
                },
                grid: {
                    borderColor: isDark ? '#374151' : '#e5e7eb',
                    strokeDashArray: 4,
                    xaxis: {
                        lines: {
                            show: false
                        }
                    }
                },
                xaxis: {
                    categories: data.map(d => d.date_formatted),
                    labels: {
                        style: {
                            colors: isDark ? '#9ca3af' : '#6b7280',
                            fontSize: '12px'
                        }
                    },
                    axisBorder: {
                        color: isDark ? '#374151' : '#e5e7eb'
                    }
                },
                yaxis: {
                    labels: {
                        style: {
                            colors: isDark ? '#9ca3af' : '#6b7280',
                            fontSize: '12px'
                        },
                        formatter: function (value) {
                            return Math.round(value);
                        }
                    }
                },
                tooltip: {
                    theme: isDark ? 'dark' : 'light',
                    x: {
                        format: 'dd/MM'
                    },
                    y: {
                        formatter: function (value) {
                            return value + ' pedidos';
                        }
                    }
                },
                markers: {
                    size: 4,
                    colors: ['#3b82f6'],
                    strokeColors: '#fff',
                    strokeWidth: 2,
                    hover: {
                        size: 6
                    }
                }
            };

            this.chart = new ApexCharts(document.querySelector("#salesChart"), options);
            this.chart.render();
        },

        renderPlatformChart() {
            const platformData = @json($pedidosPorPlataforma);
            const isDark = document.documentElement.classList.contains('dark');

            // Calcular altura dinâmica baseada no número de plataformas
            const legendHeight = platformData.length > 3 ? 80 : 50;
            const chartHeight = 240;

            const options = {
                series: platformData.map(p => p.total),
                chart: {
                    type: 'donut',
                    height: chartHeight + legendHeight,
                    background: 'transparent',
                    fontFamily: 'Inter, sans-serif'
                },
                labels: platformData.map(p => p.plataforma),
                colors: ['#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#ec4899'],
                legend: {
                    position: 'bottom',
                    offsetY: 10,
                    height: legendHeight,
                    labels: {
                        colors: isDark ? '#9ca3af' : '#6b7280',
                        useSeriesColors: false
                    },
                    itemMargin: {
                        horizontal: 8,
                        vertical: 4
                    },
                    formatter: function(seriesName, opts) {
                        return seriesName + ': ' + opts.w.globals.series[opts.seriesIndex]
                    }
                },
                dataLabels: {
                    enabled: true,
                    style: {
                        fontSize: '12px',
                        fontWeight: 600
                    },
                    dropShadow: {
                        enabled: false
                    }
                },
                plotOptions: {
                    pie: {
                        donut: {
                            size: '70%',
                            labels: {
                                show: true,
                                name: {
                                    show: true,
                                    fontSize: '14px',
                                    color: isDark ? '#9ca3af' : '#6b7280'
                                },
                                value: {
                                    show: true,
                                    fontSize: '24px',
                                    fontWeight: 700,
                                    color: isDark ? '#f3f4f6' : '#111827',
                                    formatter: function (val) {
                                        return val
                                    }
                                },
                                total: {
                                    show: true,
                                    label: 'Total',
                                    fontSize: '14px',
                                    color: isDark ? '#9ca3af' : '#6b7280',
                                    formatter: function (w) {
                                        return w.globals.seriesTotals.reduce((a, b) => a + b, 0)
                                    }
                                }
                            }
                        }
                    }
                },
                tooltip: {
                    theme: isDark ? 'dark' : 'light',
                    y: {
                        formatter: function (value) {
                            return value + ' pedidos';
                        }
                    }
                }
            };

            this.platformChart = new ApexCharts(document.querySelector("#platformChart"), options);
            this.platformChart.render();
        },

        async getPreviousPeriodData(currentData) {
            try {
                // Buscar dados do período anterior
                const period = this.chartPeriod;
                const response = await fetch(`/dashboard/chart-data?period=${period}&previous=true`);
                const data = await response.json();
                return data.previous || currentData.map(() => 0);
            } catch (error) {
                console.error('Erro ao buscar dados anteriores:', error);
                return currentData.map(() => 0);
            }
        },

        renderRevenueChart() {
            const isDark = document.documentElement.classList.contains('dark');
            
            // Gráfico de Receita vs Custos
            const revenueData = {
                receita: {{ $stats['receita_hoje'] ?? 0 }},
                custos: {{ $stats['custo_insumos_hoje'] ?? 0 }},
                lucro: {{ ($stats['receita_hoje'] ?? 0) - ($stats['custo_insumos_hoje'] ?? 0) }}
            };

            // Criar gráfico se o elemento existir
            const revenueChartEl = document.getElementById('revenueChart');
            if (revenueChartEl && typeof ApexCharts !== 'undefined') {
                const options = {
                    series: [revenueData.receita, revenueData.custos, revenueData.lucro],
                    chart: {
                        type: 'bar',
                        height: 300,
                        background: 'transparent',
                        fontFamily: 'Inter, sans-serif'
                    },
                    labels: ['Receita', 'Custos', 'Lucro'],
                    colors: ['#10b981', '#ef4444', '#3b82f6'],
                    plotOptions: {
                        bar: {
                            horizontal: false,
                            columnWidth: '55%',
                            endingShape: 'rounded'
                        }
                    },
                    dataLabels: {
                        enabled: true,
                        formatter: function (val) {
                            return 'R$ ' + val.toFixed(2).replace('.', ',').replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.');
                        }
                    },
                    xaxis: {
                        labels: {
                            style: {
                                colors: isDark ? '#9ca3af' : '#6b7280'
                            }
                        }
                    },
                    yaxis: {
                        labels: {
                            style: {
                                colors: isDark ? '#9ca3af' : '#6b7280'
                            },
                            formatter: function (val) {
                                return 'R$ ' + val.toFixed(0);
                            }
                        }
                    },
                    tooltip: {
                        theme: isDark ? 'dark' : 'light',
                        y: {
                            formatter: function (val) {
                                return 'R$ ' + val.toFixed(2).replace('.', ',').replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.');
                            }
                        }
                    },
                    legend: {
                        labels: {
                            colors: isDark ? '#9ca3af' : '#6b7280'
                        }
                    }
                };

                this.revenueChart = new ApexCharts(revenueChartEl, options);
                this.revenueChart.render();
            }
        }
    }
}
</script>
<!-- SortableJS para drag and drop -->
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
@endsection

