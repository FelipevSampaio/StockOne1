@extends('layouts.admin')

@section('title', 'Logs de Auditoria')
@section('page-title', 'Logs de Auditoria')

@section('topbar-actions')
    <div class="flex items-center gap-2">
        <button type="button"
                @click="bulkDelete()"
                x-show="selectedLogs.length > 0"
                x-cloak
                class="btn-ghost text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
            </svg>
            Deletar <span x-text="selectedLogs.length"></span> selecionados
        </button>

        <a href="{{ route('admin.audit-logs.export') }}{{ request()->getQueryString() ? '?' . request()->getQueryString() : '' }}" class="btn-secondary">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            Exportar CSV
        </a>
    </div>
@endsection

@section('content')
<div x-data="auditLogsManager()">

    <!-- Cards de Estatísticas -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <!-- Total de Logs -->
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium">Total de Logs</p>
                    <p class="text-3xl font-bold mt-2">{{ number_format($stats['total']) }}</p>
                    <p class="text-blue-100 text-xs mt-1">Todos os registros</p>
                </div>
                <div class="bg-white/20 rounded-lg p-3">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Hoje -->
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium">Hoje</p>
                    <p class="text-3xl font-bold mt-2">{{ number_format($stats['today']) }}</p>
                    <p class="text-green-100 text-xs mt-1">Ações realizadas</p>
                </div>
                <div class="bg-white/20 rounded-lg p-3">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Esta Semana -->
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm font-medium">Esta Semana</p>
                    <p class="text-3xl font-bold mt-2">{{ number_format($stats['this_week']) }}</p>
                    <p class="text-purple-100 text-xs mt-1">Últimos 7 dias</p>
                </div>
                <div class="bg-white/20 rounded-lg p-3">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Este Mês -->
        <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-orange-100 text-sm font-medium">Este Mês</p>
                    <p class="text-3xl font-bold mt-2">{{ number_format($stats['this_month']) }}</p>
                    <p class="text-orange-100 text-xs mt-1">{{ now()->format('F Y') }}</p>
                </div>
                <div class="bg-white/20 rounded-lg p-3">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Cards de Ações por Tipo -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-100 dark:border-gray-700 p-4">
            <div class="flex items-center gap-3">
                <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-green-100 dark:bg-green-900/30">
                    <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ number_format($stats['by_action']['create']) }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Criações</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-100 dark:border-gray-700 p-4">
            <div class="flex items-center gap-3">
                <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-blue-100 dark:bg-blue-900/30">
                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ number_format($stats['by_action']['update']) }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Atualizações</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-100 dark:border-gray-700 p-4">
            <div class="flex items-center gap-3">
                <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-red-100 dark:bg-red-900/30">
                    <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ number_format($stats['by_action']['delete']) }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Exclusões</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-100 dark:border-gray-700 p-4">
            <div class="flex items-center gap-3">
                <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-yellow-100 dark:bg-yellow-900/30">
                    <svg class="w-5 h-5 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ number_format($stats['by_action']['restore']) }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Restaurações</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros Avançados -->
    <form method="GET" action="{{ route('admin.audit-logs.index') }}" class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-100 dark:border-gray-700 p-4 mb-6" x-data="{ showAdvanced: false }">
        <div class="flex flex-wrap items-center gap-3 mb-3">
            <div class="relative flex-1 min-w-[250px]">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" name="search" placeholder="Buscar por modelo, usuário, ID..." value="{{ request('search') }}" class="pl-10 w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-1 focus:ring-red-500 focus:border-red-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400">
            </div>

            <select name="period" class="px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-1 focus:ring-red-500 focus:border-red-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                <option value="">Período</option>
                <option value="today" @selected(request('period') === 'today')>Hoje</option>
                <option value="yesterday" @selected(request('period') === 'yesterday')>Ontem</option>
                <option value="last_7_days" @selected(request('period') === 'last_7_days')>Últimos 7 dias</option>
                <option value="last_30_days" @selected(request('period') === 'last_30_days')>Últimos 30 dias</option>
                <option value="this_month" @selected(request('period') === 'this_month')>Este mês</option>
                <option value="last_month" @selected(request('period') === 'last_month')>Mês passado</option>
            </select>

            <select name="action" class="px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-1 focus:ring-red-500 focus:border-red-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                <option value="">Todas as Ações</option>
                <option value="create" @selected(request('action') === 'create')>➕ Criação</option>
                <option value="update" @selected(request('action') === 'update')>✏️ Atualização</option>
                <option value="delete" @selected(request('action') === 'delete')>🗑️ Exclusão</option>
                <option value="restore" @selected(request('action') === 'restore')>♻️ Restauração</option>
            </select>

            <button type="button" @click="showAdvanced = !showAdvanced" class="btn-ghost text-sm">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                </svg>
                Filtros Avançados
            </button>

            <button type="submit" class="btn-primary text-sm">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                </svg>
                Filtrar
            </button>

            @if(request()->anyFilled(['search', 'action', 'model', 'user_id', 'date_from', 'date_to', 'period']))
                <a href="{{ route('admin.audit-logs.index') }}" class="px-4 py-2 text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 transition-colors">
                    Limpar Filtros
                </a>
            @endif
        </div>

        <!-- Filtros Avançados Expansíveis -->
        <div x-show="showAdvanced"
             x-cloak
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 transform -translate-y-2"
             x-transition:enter-end="opacity-100 transform translate-y-0"
             class="border-t border-gray-200 dark:border-gray-700 pt-3 mt-3 grid grid-cols-1 md:grid-cols-3 gap-3">

            <div>
                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Modelo</label>
                <select name="model" class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-1 focus:ring-red-500 focus:border-red-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                    <option value="">Todos os Modelos</option>
                    @foreach($models as $model)
                        <option value="{{ $model }}" @selected(request('model') === $model)>{{ $model }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Usuário</label>
                <select name="user_id" class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-1 focus:ring-red-500 focus:border-red-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                    <option value="">Todos os Usuários</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" @selected(request('user_id') == $user->id)>{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Registros por página</label>
                <select name="per_page" class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-1 focus:ring-red-500 focus:border-red-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                    <option value="20" @selected(request('per_page', 20) == 20)>20</option>
                    <option value="50" @selected(request('per_page') == 50)>50</option>
                    <option value="100" @selected(request('per_page') == 100)>100</option>
                    <option value="200" @selected(request('per_page') == 200)>200</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Data Inicial</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}" class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-1 focus:ring-red-500 focus:border-red-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Data Final</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}" class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-1 focus:ring-red-500 focus:border-red-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
            </div>
        </div>
    </form>

    <!-- Alternância de Visualização -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Histórico de Ações</h3>
                    <p class="text-sm text-gray-500 mt-1">{{ $logs->total() }} registro(s) encontrado(s)</p>
                </div>

                <div class="flex items-center gap-2">
                    <!-- Seleção em Massa -->
                    <button type="button" @click="toggleSelectAll()" class="btn-ghost text-sm">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                        </svg>
                        <span x-text="selectedLogs.length > 0 ? 'Desmarcar' : 'Selecionar'"></span>
                    </button>

                    <!-- Toggle View -->
                    <div class="flex items-center gap-1 bg-gray-100 dark:bg-gray-700 rounded-lg p-1">
                        <button type="button"
                                @click="viewMode = 'timeline'"
                                :class="viewMode === 'timeline' ? 'bg-white dark:bg-gray-600 shadow-sm' : ''"
                                class="px-3 py-1.5 rounded-md text-sm font-medium transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </button>
                        <button type="button"
                                @click="viewMode = 'cards'"
                                :class="viewMode === 'cards' ? 'bg-white dark:bg-gray-600 shadow-sm' : ''"
                                class="px-3 py-1.5 rounded-md text-sm font-medium transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Timeline View -->
        <div x-show="viewMode === 'timeline'" class="p-6">
            @forelse($logs as $log)
                <div class="relative pb-8 {{ !$loop->last ? 'border-l-2 border-gray-200 dark:border-gray-700' : '' }} pl-10" x-data="{ expanded: false }">
                    <!-- Checkbox -->
                    <div class="absolute left-0 top-2 -ml-3 flex items-center justify-center">
                        <input type="checkbox"
                               :checked="selectedLogs.includes({{ $log->id }})"
                               @change="toggleLog({{ $log->id }})"
                               class="w-4 h-4 text-red-600 bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 rounded focus:ring-red-500 cursor-pointer">
                    </div>

                    <!-- Timeline dot -->
                    <div class="absolute left-0 top-2 ml-6 flex items-center justify-center">
                        <div class="h-4 w-4 rounded-full border-2 border-white dark:border-gray-800
                            {{ $log->action === 'create' ? 'bg-green-500' : '' }}
                            {{ $log->action === 'update' ? 'bg-blue-500' : '' }}
                            {{ $log->action === 'delete' ? 'bg-red-500' : '' }}
                            {{ $log->action === 'restore' ? 'bg-yellow-500' : '' }}
                            shadow-md"></div>
                    </div>

                    <!-- Log content -->
                    <div class="bg-gray-50 dark:bg-gray-900/50 rounded-lg p-4 hover:bg-gray-100 dark:hover:bg-gray-900/70 transition-colors border border-gray-200 dark:border-gray-700">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center flex-wrap gap-2 mb-2">
                                    <!-- Ícone da Ação -->
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium
                                        {{ $log->action === 'create' ? 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-400' : '' }}
                                        {{ $log->action === 'update' ? 'bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-400' : '' }}
                                        {{ $log->action === 'delete' ? 'bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-400' : '' }}
                                        {{ $log->action === 'restore' ? 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-400' : '' }}">
                                        @if($log->action === 'create')
                                            ➕
                                        @elseif($log->action === 'update')
                                            ✏️
                                        @elseif($log->action === 'delete')
                                            🗑️
                                        @elseif($log->action === 'restore')
                                            ♻️
                                        @endif
                                        {{ ucfirst($log->action) }}
                                    </span>
                                    <span class="text-sm font-semibold text-gray-900 dark:text-gray-100">{{ class_basename($log->model) }}</span>
                                    @if($log->model_id)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                                            ID: {{ $log->model_id }}
                                        </span>
                                    @endif
                                </div>

                                <p class="text-sm text-gray-700 dark:text-gray-300 mb-2">
                                    Por <span class="font-semibold text-gray-900 dark:text-gray-100">{{ $log->user->name ?? 'Sistema' }}</span>
                                    <span class="text-gray-400">•</span>
                                    <time class="text-gray-500 dark:text-gray-400" datetime="{{ $log->created_at }}" title="{{ $log->created_at->format('d/m/Y H:i:s') }}">
                                        {{ $log->created_at->diffForHumans() }}
                                    </time>
                                </p>

                                @if($log->changes)
                                    <button @click="expanded = !expanded" class="text-sm text-red-600 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300 font-medium flex items-center gap-1 mt-2">
                                        <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-90': expanded }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                        <span x-text="expanded ? 'Ocultar detalhes' : 'Ver detalhes das mudanças'"></span>
                                    </button>

                                    <div x-show="expanded"
                                         x-cloak
                                         x-transition:enter="transition ease-out duration-200"
                                         x-transition:enter-start="opacity-0 transform -translate-y-2"
                                         x-transition:enter-end="opacity-100 transform translate-y-0"
                                         class="mt-3 bg-white dark:bg-gray-800 rounded-lg p-4 border border-gray-300 dark:border-gray-600">
                                        <h4 class="text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-3 flex items-center gap-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                                            </svg>
                                            Alterações Realizadas:
                                        </h4>
                                        <div class="bg-gray-50 dark:bg-gray-900 rounded-md p-3 overflow-x-auto">
                                            <pre class="text-xs text-gray-700 dark:text-gray-300 font-mono">{{ json_encode(is_array($log->changes) ? $log->changes : json_decode($log->changes, true), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <div class="ml-4 flex-shrink-0">
                                @if($log->user && $log->user->avatar)
                                    <img src="{{ $log->user->avatar }}" alt="{{ $log->user->name }}" class="w-10 h-10 rounded-full">
                                @else
                                    <div class="h-10 w-10 rounded-full flex items-center justify-center
                                        {{ $log->action === 'create' ? 'bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400' : '' }}
                                        {{ $log->action === 'update' ? 'bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400' : '' }}
                                        {{ $log->action === 'delete' ? 'bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400' : '' }}
                                        {{ $log->action === 'restore' ? 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-600 dark:text-yellow-400' : '' }}">
                                        <span class="font-bold text-sm">{{ strtoupper(substr($log->user->name ?? 'S', 0, 2)) }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Nenhum log de auditoria encontrado</p>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Tente ajustar os filtros de busca</p>
                </div>
            @endforelse
        </div>

        <!-- Cards View -->
        <div x-show="viewMode === 'cards'" class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @forelse($logs as $log)
                    <div x-data="{ expanded: false }" class="bg-white dark:bg-gray-900/50 rounded-lg border border-gray-200 dark:border-gray-700 p-4 hover:shadow-md transition-all">
                        <!-- Header do Card -->
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex items-center gap-2">
                                <input type="checkbox"
                                       :checked="selectedLogs.includes({{ $log->id }})"
                                       @change="toggleLog({{ $log->id }})"
                                       class="w-4 h-4 text-red-600 bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 rounded focus:ring-red-500 cursor-pointer">
                                <span class="inline-flex items-center gap-1 px-2 py-1 rounded-md text-xs font-medium
                                    {{ $log->action === 'create' ? 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-400' : '' }}
                                    {{ $log->action === 'update' ? 'bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-400' : '' }}
                                    {{ $log->action === 'delete' ? 'bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-400' : '' }}
                                    {{ $log->action === 'restore' ? 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-400' : '' }}">
                                    @if($log->action === 'create') ➕ @elseif($log->action === 'update') ✏️ @elseif($log->action === 'delete') 🗑️ @else ♻️ @endif
                                    {{ ucfirst($log->action) }}
                                </span>
                            </div>

                            @if($log->user && $log->user->avatar)
                                <img src="{{ $log->user->avatar }}" alt="{{ $log->user->name }}" class="w-8 h-8 rounded-full">
                            @else
                                <div class="h-8 w-8 rounded-full flex items-center justify-center
                                    {{ $log->action === 'create' ? 'bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400' : '' }}
                                    {{ $log->action === 'update' ? 'bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400' : '' }}
                                    {{ $log->action === 'delete' ? 'bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400' : '' }}
                                    {{ $log->action === 'restore' ? 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-600 dark:text-yellow-400' : '' }}">
                                    <span class="font-bold text-xs">{{ strtoupper(substr($log->user->name ?? 'S', 0, 2)) }}</span>
                                </div>
                            @endif
                        </div>

                        <!-- Conteúdo do Card -->
                        <div class="space-y-2">
                            <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">
                                {{ class_basename($log->model) }}
                                @if($log->model_id)
                                    <span class="text-xs text-gray-500 dark:text-gray-400">#{{ $log->model_id }}</span>
                                @endif
                            </p>

                            <div class="text-xs text-gray-600 dark:text-gray-400">
                                <p class="font-medium text-gray-900 dark:text-gray-100">{{ $log->user->name ?? 'Sistema' }}</p>
                                <p class="mt-0.5" title="{{ $log->created_at->format('d/m/Y H:i:s') }}">{{ $log->created_at->diffForHumans() }}</p>
                            </div>

                            @if($log->changes)
                                <button @click="expanded = !expanded" class="text-xs text-red-600 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300 font-medium flex items-center gap-1 mt-2">
                                    <svg class="w-3 h-3 transition-transform duration-200" :class="{ 'rotate-90': expanded }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                    Detalhes
                                </button>

                                <div x-show="expanded"
                                     x-cloak
                                     x-transition
                                     class="mt-2 bg-gray-50 dark:bg-gray-800 rounded p-2 text-xs overflow-x-auto">
                                    <pre class="text-gray-700 dark:text-gray-300 font-mono text-[10px]">{{ json_encode(is_array($log->changes) ? $log->changes : json_decode($log->changes, true), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Nenhum log de auditoria encontrado</p>
                        <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Tente ajustar os filtros de busca</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Paginação -->
    <div class="mt-6">
        {{ $logs->links() }}
    </div>

    <!-- Top Users & Models Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
        <!-- Top Usuários Ativos -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
                Top 5 Usuários Mais Ativos
            </h3>
            <div class="space-y-3">
                @forelse($stats['top_users'] as $item)
                    <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-900/50 rounded-lg">
                        <div class="flex items-center gap-3">
                            <div class="h-10 w-10 rounded-full bg-red-100 dark:bg-red-900/30 flex items-center justify-center">
                                <span class="text-red-600 dark:text-red-400 font-bold text-sm">
                                    {{ strtoupper(substr($item->user->name ?? 'S', 0, 2)) }}
                                </span>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $item->user->name ?? 'Desconhecido' }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $item->user->email ?? '' }}</p>
                            </div>
                        </div>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-400">
                            {{ number_format($item->total) }} ações
                        </span>
                    </div>
                @empty
                    <p class="text-sm text-gray-500 dark:text-gray-400 text-center py-4">Nenhum dado disponível</p>
                @endforelse
            </div>
        </div>

        <!-- Top Modelos Modificados -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"/>
                </svg>
                Top 5 Modelos Mais Modificados
            </h3>
            <div class="space-y-3">
                @forelse($stats['top_models'] as $item)
                    <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-900/50 rounded-lg">
                        <div class="flex items-center gap-3">
                            <div class="h-10 w-10 rounded-lg bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $item->model }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Modelo do sistema</p>
                            </div>
                        </div>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-400">
                            {{ number_format($item->total) }} logs
                        </span>
                    </div>
                @empty
                    <p class="text-sm text-gray-500 dark:text-gray-400 text-center py-4">Nenhum dado disponível</p>
                @endforelse
            </div>
        </div>
    </div>

</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('auditLogsManager', () => ({
            viewMode: localStorage.getItem('audit_logs_view') || 'timeline',
            selectedLogs: [],

            init() {
                this.$watch('viewMode', value => {
                    localStorage.setItem('audit_logs_view', value);
                });
            },

            toggleLog(logId) {
                const index = this.selectedLogs.indexOf(logId);
                if (index > -1) {
                    this.selectedLogs.splice(index, 1);
                } else {
                    this.selectedLogs.push(logId);
                }
            },

            toggleSelectAll() {
                if (this.selectedLogs.length > 0) {
                    this.selectedLogs = [];
                } else {
                    // Selecionar todos os logs visíveis
                    this.selectedLogs = @json($logs->pluck('id')->toArray());
                }
            },

            async bulkDelete() {
                if (this.selectedLogs.length === 0) {
                    this.showToast('Selecione ao menos um log', 'error');
                    return;
                }

                if (!confirm(`Tem certeza que deseja deletar ${this.selectedLogs.length} log(s) selecionado(s)?`)) {
                    return;
                }

                try {
                    const response = await fetch('{{ route('admin.audit-logs.bulkDestroy') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            ids: this.selectedLogs
                        })
                    });

                    const data = await response.json();

                    if (response.ok) {
                        this.showToast(data.message || 'Logs deletados com sucesso', 'success');
                        setTimeout(() => window.location.reload(), 1500);
                    } else {
                        throw new Error(data.message || 'Erro ao deletar logs');
                    }
                } catch (error) {
                    this.showToast(error.message, 'error');
                }
            },

            showToast(message, type = 'success') {
                const toast = document.createElement('div');
                toast.className = `fixed top-4 right-4 z-50 px-6 py-4 rounded-lg shadow-lg transition-all duration-300 ${
                    type === 'error'
                        ? 'bg-red-600 text-white'
                        : 'bg-green-600 text-white'
                }`;
                toast.innerHTML = `
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            ${type === 'error'
                                ? '<path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>'
                                : '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>'
                            }
                        </svg>
                        <p class="font-medium">${message}</p>
                    </div>
                `;
                document.body.appendChild(toast);
                setTimeout(() => {
                    toast.classList.add('opacity-0', 'translate-x-full');
                    setTimeout(() => toast.remove(), 300);
                }, 3000);
            }
        }));
    });
</script>
@endsection
