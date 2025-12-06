@extends('layouts.admin')

@section('title', 'Logs de Auditoria')
@section('page-title')
    <div class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-red-500 to-red-600 flex items-center justify-center shadow-lg">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
        </div>
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Logs de Auditoria</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">Monitoramento e rastreamento de ações</p>
        </div>
    </div>
@endsection

@section('topbar-actions')
    <div class="flex items-center gap-2">
        <button type="button"
                @click="bulkExport()"
                x-show="selectedLogs.length > 0"
                x-cloak
                class="btn-ghost text-blue-600 dark:text-blue-400">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            Exportar <span x-text="selectedLogs.length"></span>
        </button>

        <button type="button"
                @click="bulkDelete()"
                x-show="selectedLogs.length > 0"
                x-cloak
                class="btn-ghost text-red-600 dark:text-red-400">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
            </svg>
            Deletar <span x-text="selectedLogs.length"></span>
        </button>

        <!-- Dropdown de Export -->
        <div x-data="{ exportOpen: false }" class="relative">
            <button @click="exportOpen = !exportOpen" class="btn-secondary flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Exportar
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>

            <div x-show="exportOpen"
                 @click.away="exportOpen = false"
                 x-cloak
                 x-transition:enter="transition ease-out duration-100"
                 x-transition:enter-start="transform opacity-0 scale-95"
                 x-transition:enter-end="transform opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-75"
                 x-transition:leave-start="transform opacity-100 scale-100"
                 x-transition:leave-end="transform opacity-0 scale-95"
                 class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 z-50">
                <a href="{{ route('admin.audit-logs.export-excel') }}{{ request()->getQueryString() ? '?' . request()->getQueryString() : '' }}"
                   class="flex items-center gap-3 px-4 py-3 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-t-lg transition">
                    <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                    <div>
                        <div class="font-medium">Excel/CSV</div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">Até 1000 registros</div>
                    </div>
                </a>
                <a href="{{ route('admin.audit-logs.export-pdf') }}{{ request()->getQueryString() ? '?' . request()->getQueryString() : '' }}"
                   class="flex items-center gap-3 px-4 py-3 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-b-lg transition">
                    <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                    <div>
                        <div class="font-medium">PDF</div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">Até 500 registros</div>
                    </div>
                </a>
            </div>
        </div>
    </div>
@endsection

@section('content')
<div x-data="auditLogsEnhanced()">

    <!-- 📈 Cards de Estatísticas -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-3 mb-6">
        <!-- Total -->
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg hover:shadow-xl p-4 text-white relative overflow-hidden group cursor-pointer transform hover:scale-105 transition-all duration-200">
            <div class="absolute top-0 right-0 opacity-10 group-hover:opacity-20 transition-opacity">
                <svg class="w-24 h-24 transform group-hover:rotate-12 transition-transform" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/><path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                </svg>
            </div>
            <p class="text-blue-100 text-xs uppercase tracking-wider font-bold mb-2">Total</p>
            <p class="text-3xl font-extrabold">{{ number_format($stats['total']) }}</p>
            <p class="text-blue-100 text-xs mt-1 opacity-90">registros</p>
        </div>

        <!-- Hoje -->
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg hover:shadow-xl p-4 text-white relative overflow-hidden group cursor-pointer transform hover:scale-105 transition-all duration-200">
            <div class="absolute top-0 right-0 opacity-10 group-hover:opacity-20 transition-opacity">
                <svg class="w-24 h-24 transform group-hover:rotate-12 transition-transform" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                </svg>
            </div>
            <p class="text-green-100 text-xs uppercase tracking-wider font-bold mb-2">Hoje</p>
            <p class="text-3xl font-extrabold">{{ number_format($stats['today']) }}</p>
            <p class="text-green-100 text-xs mt-1 opacity-90">{{ now()->format('d/m') }}</p>
        </div>

        <!-- Semana -->
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg hover:shadow-xl p-4 text-white relative overflow-hidden group cursor-pointer transform hover:scale-105 transition-all duration-200">
            <div class="absolute top-0 right-0 opacity-10 group-hover:opacity-20 transition-opacity">
                <svg class="w-24 h-24 transform group-hover:rotate-12 transition-transform" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"/>
                </svg>
            </div>
            <p class="text-purple-100 text-xs uppercase tracking-wider font-bold mb-2">Semana</p>
            <p class="text-3xl font-extrabold">{{ number_format($stats['this_week']) }}</p>
            <p class="text-purple-100 text-xs mt-1 opacity-90">7 dias</p>
        </div>

        <!-- Mês -->
        <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl shadow-lg hover:shadow-xl p-4 text-white relative overflow-hidden group cursor-pointer transform hover:scale-105 transition-all duration-200">
            <div class="absolute top-0 right-0 opacity-10 group-hover:opacity-20 transition-opacity">
                <svg class="w-24 h-24 transform group-hover:rotate-12 transition-transform" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z"/><path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z"/>
                </svg>
            </div>
            <p class="text-orange-100 text-xs uppercase tracking-wider font-bold mb-2">Mês</p>
            <p class="text-3xl font-extrabold">{{ number_format($stats['this_month']) }}</p>
            <p class="text-orange-100 text-xs mt-1 opacity-90">30 dias</p>
        </div>

        <!-- Top Usuário -->
        <div class="bg-gradient-to-br from-pink-500 to-pink-600 rounded-xl shadow-lg hover:shadow-xl p-4 text-white relative overflow-hidden group cursor-pointer transform hover:scale-105 transition-all duration-200">
            <div class="absolute top-0 right-0 opacity-10 group-hover:opacity-20 transition-opacity">
                <svg class="w-24 h-24 transform group-hover:rotate-12 transition-transform" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                </svg>
            </div>
            <p class="text-pink-100 text-xs uppercase tracking-wider font-bold mb-2">🏆 Top</p>
            <p class="text-xl font-extrabold truncate">{{ $stats['top_users']->first()->user->name ?? 'N/A' }}</p>
            <p class="text-pink-100 text-xs mt-1 opacity-90">{{ $stats['top_users']->first()->total ?? 0 }} ações</p>
        </div>
    </div>

    <!-- 🔍 Filtros Inteligentes com Header -->
    <div class="mb-8">
        <div class="flex items-center gap-3 mb-4">
            <div class="h-1 w-12 bg-gradient-to-r from-blue-500 to-blue-600 rounded-full"></div>
            <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100">🔍 Filtros Inteligentes</h2>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border-2 border-gray-100 dark:border-gray-700 p-6 mb-6">
        <!-- Tags de Filtro Rápido -->
        <div class="flex flex-wrap gap-2 mb-4">
            <button @click="quickFilter('last_24h')" :class="activeQuickFilter === 'last_24h' ? 'bg-gradient-to-r from-red-600 to-red-700 text-white shadow-lg' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-white border border-gray-300 dark:border-gray-600'" class="inline-flex items-center px-4 py-2 text-xs font-bold rounded-lg hover:scale-105 hover:shadow-md transition-all">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Últimas 24h
            </button>
            <button @click="quickFilter('my_activity')" :class="activeQuickFilter === 'my_activity' ? 'bg-gradient-to-r from-red-600 to-red-700 text-white shadow-lg' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-white border border-gray-300 dark:border-gray-600'" class="inline-flex items-center px-4 py-2 text-xs font-bold rounded-lg hover:scale-105 hover:shadow-md transition-all">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                Minha Atividade
            </button>
            <button @click="quickFilter('critical')" :class="activeQuickFilter === 'critical' ? 'bg-gradient-to-r from-red-600 to-red-700 text-white shadow-lg' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-white border border-gray-300 dark:border-gray-600'" class="inline-flex items-center px-4 py-2 text-xs font-bold rounded-lg hover:scale-105 hover:shadow-md transition-all">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                Críticas
            </button>
            <button @click="quickFilter('suspicious_ip')" :class="activeQuickFilter === 'suspicious_ip' ? 'bg-gradient-to-r from-red-600 to-red-700 text-white shadow-lg' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-white border border-gray-300 dark:border-gray-600'" class="inline-flex items-center px-4 py-2 text-xs font-bold rounded-lg hover:scale-105 hover:shadow-md transition-all">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
                IPs Suspeitos
            </button>
            <button @click="quickFilter('creates')" :class="activeQuickFilter === 'creates' ? 'bg-gradient-to-r from-green-500 to-green-600 text-white shadow-lg' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-white border border-gray-300 dark:border-gray-600'" class="inline-flex items-center px-4 py-2 text-xs font-bold rounded-lg hover:scale-105 hover:shadow-md transition-all">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"/></svg>
                Criações
            </button>
            <button @click="quickFilter('updates')" :class="activeQuickFilter === 'updates' ? 'bg-gradient-to-r from-blue-500 to-blue-600 text-white shadow-lg' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-white border border-gray-300 dark:border-gray-600'" class="inline-flex items-center px-4 py-2 text-xs font-bold rounded-lg hover:scale-105 hover:shadow-md transition-all">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Edições
            </button>
            <button @click="quickFilter('deletes')" :class="activeQuickFilter === 'deletes' ? 'bg-gradient-to-r from-red-500 to-red-600 text-white shadow-lg' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-white border border-gray-300 dark:border-gray-600'" class="inline-flex items-center px-4 py-2 text-xs font-bold rounded-lg hover:scale-105 hover:shadow-md transition-all">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                Exclusões
            </button>
            @if(request()->anyFilled(['search', 'action', 'model', 'user_id', 'date_from', 'date_to', 'period']))
                <a href="{{ route('admin.audit-logs.index') }}" class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-lg bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-white border border-gray-300 dark:border-gray-600 hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                    <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Limpar Filtros
                </a>
            @endif
        </div>

        <!-- Busca Avançada -->
        <form method="GET" action="{{ route('admin.audit-logs.index') }}" class="space-y-4">
            <div class="flex flex-wrap items-center gap-3">
                <div class="relative flex-1 min-w-[250px] group">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-5 w-5 text-gray-400 dark:text-gray-500 group-focus-within:text-red-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text" name="search" placeholder="🔍 Buscar em logs, mudanças, usuários..." value="{{ request('search') }}" class="pl-11 w-full px-4 py-3 text-sm border-2 border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400 transition-all duration-200 hover:border-red-300 dark:hover:border-red-700">
                </div>

                <select name="action" class="px-4 py-3 text-sm font-medium border-2 border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-red-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 hover:border-red-300 dark:hover:border-red-700 transition-all cursor-pointer">
                    <option value="" class="bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">Todas as Ações</option>
                    <option value="create" @selected(request('action') === 'create') class="bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">➕ Criação</option>
                    <option value="update" @selected(request('action') === 'update') class="bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">✏️ Atualização</option>
                    <option value="delete" @selected(request('action') === 'delete') class="bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">🗑️ Exclusão</option>
                    <option value="restore" @selected(request('action') === 'restore') class="bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">♻️ Restauração</option>
                </select>

                <select name="model" class="px-4 py-3 text-sm font-medium border-2 border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-red-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 hover:border-red-300 dark:hover:border-red-700 transition-all cursor-pointer">
                    <option value="" class="bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">Todos os Modelos</option>
                    @foreach($models as $model)
                        <option value="{{ $model }}" @selected(request('model') === $model) class="bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">{{ $model }}</option>
                    @endforeach
                </select>

                <button type="submit" class="inline-flex items-center px-6 py-3 text-sm font-bold rounded-xl bg-gradient-to-r from-red-600 to-red-700 text-white hover:from-red-700 hover:to-red-800 shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                    </svg>
                    Aplicar Filtros
                </button>
            </div>
        </form>
    </div>

    <!-- 📅 Timeline Visual Agrupada -->
    <!-- Header Timeline -->
    <div class="mb-8">
        <div class="flex items-center gap-3 mb-4">
            <div class="h-1 w-12 bg-gradient-to-r from-purple-500 to-purple-600 rounded-full"></div>
            <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100">📜 Linha do Tempo</h2>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border-2 border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-700 p-6 border-b-2 border-gray-200 dark:border-gray-600">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100 flex items-center gap-2">
                        <svg class="w-6 h-6 text-red-600 dark:text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Histórico de Ações
                    </h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 font-medium">
                        <span class="inline-flex items-center gap-1">
                            <span class="inline-block w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                            {{ $logs->total() }} registro(s) encontrado(s)
                        </span>
                    </p>
                </div>

                <div class="flex items-center gap-3">
                    <button type="button" @click="toggleSelectAll()" class="inline-flex items-center px-4 py-2 text-sm font-bold rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600 shadow-md hover:shadow-lg transform hover:scale-105 transition-all">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                        </svg>
                        <span x-text="selectedLogs.length > 0 ? 'Desmarcar (' + selectedLogs.length + ')' : 'Selecionar Todos'"></span>
                    </button>

                    <button type="button" @click="compareMode = !compareMode" :class="compareMode ? 'bg-gradient-to-r from-red-600 to-red-700 text-white shadow-lg' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300'" class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-bold hover:shadow-lg transform hover:scale-105 transition-all">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                        </svg>
                        <span x-text="compareMode ? '✓ Comparando' : 'Modo Comparar'"></span>
                    </button>
                </div>
            </div>
        </div>

        <div class="p-6 max-h-[800px] overflow-y-auto" id="timeline-container">
            @php
                $groupedLogs = $logs->groupBy(function($log) {
                    if ($log->created_at->isToday()) return 'Hoje';
                    if ($log->created_at->isYesterday()) return 'Ontem';
                    if ($log->created_at->isCurrentWeek()) return 'Esta Semana';
                    if ($log->created_at->isCurrentMonth()) return 'Este Mês';
                    return $log->created_at->format('F Y');
                });
            @endphp

            @foreach($groupedLogs as $period => $periodLogs)
                <!-- Cabeçalho do Período Melhorado com Progress -->
                <div class="relative">
                    <div class="absolute -top-2 left-0 right-0 h-1 bg-gradient-to-r from-red-400 via-red-500 to-red-600 rounded-full shadow-lg"></div>
                    <div class="flex items-center gap-4 mb-8 sticky top-0 bg-white dark:bg-gray-800 py-3 z-10 group">
                    <div class="flex-shrink-0">
                        <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-red-500 via-red-600 to-red-700 flex items-center justify-center text-white font-extrabold shadow-xl ring-4 ring-red-100 dark:ring-red-900/30 group-hover:scale-110 transition-transform">
                            <span class="text-xl">{{ $periodLogs->count() }}</span>
                        </div>
                    </div>
                    <div class="flex-1">
                        <h4 class="text-2xl font-extrabold text-gray-900 dark:text-gray-100 tracking-tight">{{ $period }}</h4>
                        <p class="text-sm text-gray-500 dark:text-gray-400 font-medium mt-0.5">
                            <span class="inline-flex items-center gap-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/></svg>
                                {{ $periodLogs->count() }} ações registradas
                            </span>
                        </p>
                    </div>
                    <div class="h-0.5 flex-1 bg-gradient-to-r from-red-200 via-red-300 to-transparent dark:from-red-800 dark:via-red-700 rounded-full"></div>
                    </div>
                </div>

                <!-- Logs do Período -->
                <div class="relative ml-6">
                    @foreach($periodLogs as $log)
                        <div class="relative pb-6 {{ !$loop->last ? 'border-l-2 border-gray-200 dark:border-gray-700' : '' }} pl-10 group" x-data="{ expanded: false, showDiff: false }">
                            <!-- Checkpoint Icon Melhorado -->
                            <div class="absolute left-0 top-2 -ml-4 flex items-center justify-center">
                                <div class="h-10 w-10 rounded-xl border-4 border-white dark:border-gray-800 shadow-xl flex items-center justify-center text-white ring-2 ring-opacity-30
                                    {{ $log->action === 'create' ? 'bg-gradient-to-br from-green-500 to-green-600 ring-green-300 dark:ring-green-700' : '' }}
                                    {{ $log->action === 'update' ? 'bg-gradient-to-br from-blue-500 to-blue-600 ring-blue-300 dark:ring-blue-700' : '' }}
                                    {{ $log->action === 'delete' ? 'bg-gradient-to-br from-red-500 to-red-600 ring-red-300 dark:ring-red-700' : '' }}
                                    {{ $log->action === 'restore' ? 'bg-gradient-to-br from-yellow-500 to-yellow-600 ring-yellow-300 dark:ring-yellow-700' : '' }}
                                    group-hover:scale-125 group-hover:rotate-12 transition-all duration-200">
                                    @php
                                        $modelIcons = [
                                            'Restaurante' => '🏪',
                                            'User' => '👤',
                                            'Pedido' => '📦',
                                            'Insumo' => '📦',
                                            'CardapioItem' => '🍔',
                                            'Estoque' => '📊',
                                        ];
                                        $icon = $modelIcons[class_basename($log->model)] ?? '📄';
                                    @endphp
                                    <span class="text-xl">{{ $icon }}</span>
                                </div>
                            </div>

                            <!-- Checkbox -->
                            <div class="absolute left-0 top-2 -ml-2">
                                <input type="checkbox"
                                       :checked="selectedLogs.includes({{ $log->id }})"
                                       @change="toggleLog({{ $log->id }})"
                                       class="w-4 h-4 text-red-600 bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 rounded focus:ring-red-500 cursor-pointer opacity-0 group-hover:opacity-100 transition-opacity">
                            </div>

                            <!-- Log Card -->
                            <div class="bg-white dark:bg-gray-800 rounded-xl p-5 hover:shadow-xl hover:border-red-300 dark:hover:border-red-600 transition-all duration-200 border border-gray-200 dark:border-gray-700 cursor-pointer transform hover:-translate-y-1" @click="expanded = !expanded">
                                <div class="flex items-start justify-between mb-3">
                                    <div class="flex-1">
                                        <div class="flex items-center flex-wrap gap-2 mb-3">
                                            <!-- Badge Ação Melhorado -->
                                            @if($log->action === 'create')
                                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-extrabold bg-gradient-to-r from-green-500 to-green-600 text-white shadow-md hover:shadow-lg transform hover:scale-105 transition-all">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"/></svg>
                                                    CRIADO
                                                </span>
                                            @elseif($log->action === 'update')
                                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-extrabold bg-gradient-to-r from-blue-500 to-blue-600 text-white shadow-md hover:shadow-lg transform hover:scale-105 transition-all">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                                    EDITADO
                                                </span>
                                            @elseif($log->action === 'delete')
                                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-extrabold bg-gradient-to-r from-red-500 to-red-600 text-white shadow-md hover:shadow-lg transform hover:scale-105 transition-all">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                    DELETADO
                                                </span>
                                            @elseif($log->action === 'restore')
                                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-extrabold bg-gradient-to-r from-yellow-500 to-yellow-600 text-white shadow-md hover:shadow-lg transform hover:scale-105 transition-all">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                                    RESTAURADO
                                                </span>
                                            @endif

                                            <!-- Modelo -->
                                            <span class="text-base font-bold text-gray-900 dark:text-gray-100">{{ class_basename($log->model) }}</span>

                                            <!-- ID -->
                                            @if($log->model_id)
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-mono font-bold bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 shadow-sm">
                                                    #{{ $log->model_id }}
                                                </span>
                                            @endif
                                        </div>

                                        <!-- Info do Usuário -->
                                        <div class="flex items-center gap-3 text-sm">
                                            <div class="flex items-center gap-2">
                                                <div class="w-6 h-6 rounded-full bg-gradient-to-br from-red-500 to-red-600 flex items-center justify-center text-white text-xs font-bold">
                                                    {{ substr($log->user->name ?? 'S', 0, 1) }}
                                                </div>
                                                <span class="font-semibold text-gray-900 dark:text-gray-100">{{ $log->user->name ?? 'Sistema' }}</span>
                                            </div>
                                            <span class="text-gray-400">•</span>
                                            <span class="text-gray-600 dark:text-gray-400">{{ $log->created_at->format('H:i') }}</span>
                                            <span class="text-gray-400">•</span>
                                            <span class="text-gray-600 dark:text-gray-400 text-xs">{{ $log->ip_address ?? 'N/A' }}</span>
                                        </div>
                                    </div>

                                    <!-- Expand Icon -->
                                    <svg x-show="!expanded" class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                    <svg x-show="expanded" x-cloak class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>
                                    </svg>
                                </div>

                                <!-- Detalhes Expandidos -->
                                <div x-show="expanded" x-cloak x-transition class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700 space-y-3">
                                    @if($log->changes)
                                        <!-- Toggle Diff View -->
                                        <div class="flex items-center justify-between mb-2">
                                            <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">Mudanças:</span>
                                            <button @click.stop="showDiff = !showDiff" class="text-xs px-3 py-1 rounded-lg bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 hover:bg-blue-200 dark:hover:bg-blue-900/50 transition">
                                                <span x-text="showDiff ? 'Ver JSON' : 'Ver Diff Visual'"></span>
                                            </button>
                                        </div>

                                        <!-- Diff Visual -->
                                        <div x-show="showDiff" x-cloak class="space-y-2">
                                            @php
                                                $changes = is_string($log->changes) ? json_decode($log->changes, true) : $log->changes;
                                            @endphp
                                            @if($changes && isset($changes['old']) && isset($changes['new']))
                                                <div class="grid grid-cols-2 gap-4">
                                                    <!-- Antes -->
                                                    <div class="space-y-2">
                                                        <div class="flex items-center gap-2 text-xs font-semibold text-red-700 dark:text-red-400 uppercase tracking-wide">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                            </svg>
                                                            Antes
                                                        </div>
                                                        <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-3 space-y-1">
                                                            @foreach($changes['old'] as $key => $value)
                                                                <div class="text-sm">
                                                                    <span class="font-semibold text-gray-700 dark:text-gray-300">{{ ucfirst(str_replace('_', ' ', $key)) }}:</span>
                                                                    <span class="text-red-700 dark:text-red-400 line-through ml-2">{{ $value ?? 'null' }}</span>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>

                                                    <!-- Depois -->
                                                    <div class="space-y-2">
                                                        <div class="flex items-center gap-2 text-xs font-semibold text-green-700 dark:text-green-400 uppercase tracking-wide">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                            </svg>
                                                            Depois
                                                        </div>
                                                        <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-3 space-y-1">
                                                            @foreach($changes['new'] as $key => $value)
                                                                <div class="text-sm">
                                                                    <span class="font-semibold text-gray-700 dark:text-gray-300">{{ ucfirst(str_replace('_', ' ', $key)) }}:</span>
                                                                    <span class="text-green-700 dark:text-green-400 font-semibold ml-2">{{ $value ?? 'null' }}</span>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="bg-gray-100 dark:bg-gray-800 rounded-lg p-3">
                                                    <p class="text-sm text-gray-600 dark:text-gray-400">Sem comparação disponível</p>
                                                </div>
                                            @endif
                                        </div>

                                        <!-- JSON View -->
                                        <div x-show="!showDiff" class="bg-gray-900 rounded-lg p-4 overflow-x-auto">
                                            <pre class="text-xs text-green-400 font-mono">{{ json_encode($changes ?? [], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                        </div>
                                    @else
                                        <p class="text-sm text-gray-500 dark:text-gray-400 italic">Sem mudanças registradas</p>
                                    @endif

                                    <!-- Ações Rápidas Melhoradas -->
                                    <div class="flex items-center gap-2 pt-4 border-t border-gray-200 dark:border-gray-700 mt-4">
                                        <button @click.stop="viewDetails({{ $log->id }})" class="inline-flex items-center px-4 py-2 text-xs font-bold rounded-lg bg-gradient-to-r from-blue-500 to-blue-600 text-white hover:from-blue-600 hover:to-blue-700 shadow-md hover:shadow-lg transform hover:scale-105 transition-all">
                                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                            Ver Detalhes
                                        </button>
                                        @if($log->action === 'update' || $log->action === 'delete')
                                            <button @click.stop="revertLog({{ $log->id }})" class="inline-flex items-center px-4 py-2 text-xs font-bold rounded-lg bg-gradient-to-r from-yellow-500 to-yellow-600 text-white hover:from-yellow-600 hover:to-yellow-700 shadow-md hover:shadow-lg transform hover:scale-105 transition-all">
                                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
                                                </svg>
                                                Reverter
                                            </button>
                                        @endif
                                        <button @click.stop="addNote({{ $log->id }})" class="inline-flex items-center px-4 py-2 text-xs font-bold rounded-lg bg-gradient-to-r from-purple-500 to-purple-600 text-white hover:from-purple-600 hover:to-purple-700 shadow-md hover:shadow-lg transform hover:scale-105 transition-all">
                                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                                            </svg>
                                            Nota
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endforeach

            <!-- Empty State Melhorado -->
            @if($logs->isEmpty())
                <div class="flex flex-col items-center justify-center py-20 px-4">
                    <div class="relative">
                        <div class="absolute inset-0 bg-gradient-to-r from-red-400 to-red-600 rounded-full blur-3xl opacity-20 animate-pulse"></div>
                        <div class="relative w-32 h-32 bg-gradient-to-br from-red-100 to-red-200 dark:from-red-900/30 dark:to-red-800/30 rounded-3xl flex items-center justify-center shadow-2xl">
                            <svg class="w-16 h-16 text-red-500 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                    </div>
                    <h3 class="mt-8 text-2xl font-bold text-gray-900 dark:text-gray-100">Nenhum log encontrado</h3>
                    <p class="mt-2 text-gray-500 dark:text-gray-400 text-center max-w-md">
                        Não há registros de auditoria para exibir no momento. Tente ajustar seus filtros ou aguarde novas ações.
                    </p>
                    @if(request()->hasAny(['search', 'action', 'model', 'user_id', 'date_from', 'date_to', 'period']))
                        <a href="{{ route('admin.audit-logs.index') }}" class="mt-6 inline-flex items-center px-6 py-3 text-sm font-bold rounded-xl bg-gradient-to-r from-red-600 to-red-700 text-white hover:from-red-700 hover:to-red-800 shadow-lg hover:shadow-xl transform hover:scale-105 transition-all">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Limpar todos os filtros
                        </a>
                    @endif
                </div>
            @endif

            <!-- Load More Button Melhorado -->
            @if($logs->hasMorePages())
                <div class="flex justify-center pt-8">
                    <button @click="loadMore()" class="inline-flex items-center px-8 py-4 text-sm font-bold rounded-xl bg-gradient-to-r from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-600 text-gray-700 dark:text-gray-200 hover:from-gray-200 hover:to-gray-300 dark:hover:from-gray-600 dark:hover:to-gray-500 shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200">
                        <svg class="w-5 h-5 mr-2 animate-spin" x-show="loading" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <svg class="w-5 h-5 mr-2" x-show="!loading" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                        <span x-text="loading ? 'Carregando mais logs...' : 'Carregar Mais Logs'"></span>
                    </button>
                </div>
            @endif
        </div>
    </div>

    <!-- Paginação Melhorada -->
    <div class="mt-8">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border-2 border-gray-100 dark:border-gray-700 p-4">
            {{ $logs->links() }}
        </div>
    </div>

    <!-- Botão Voltar ao Topo -->
    <div class="fixed bottom-8 right-8 z-50" x-data="{ showScrollTop: false }" @scroll.window="showScrollTop = window.pageYOffset > 300" x-show="showScrollTop" x-cloak x-transition>
        <button @click="window.scrollTo({ top: 0, behavior: 'smooth' })" class="w-14 h-14 rounded-full bg-gradient-to-br from-red-600 to-red-700 text-white shadow-2xl hover:shadow-3xl transform hover:scale-110 transition-all duration-200 flex items-center justify-center group">
            <svg class="w-6 h-6 transform group-hover:-translate-y-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
            </svg>
        </button>
    </div>

</div>

<!-- Modal de Detalhes -->
<div x-show="showModal" x-cloak @click.away="showModal = false" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <!-- Overlay -->
        <div x-show="showModal"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 transition-opacity bg-gray-500 dark:bg-gray-900 bg-opacity-75 dark:bg-opacity-75"></div>

        <!-- Modal -->
        <div x-show="showModal"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Detalhes do Log</h3>
                    <button @click="showModal = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>
            <div class="px-6 py-4" x-html="modalContent"></div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
function auditLogsEnhanced() {
    return {
        selectedLogs: [],
        activeQuickFilter: null,
        viewMode: 'timeline',
        compareMode: false,
        showModal: false,
        modalContent: '',
        loading: false,

        quickFilter(filter) {
            this.activeQuickFilter = filter;
            const params = new URLSearchParams(window.location.search);

            switch(filter) {
                case 'last_24h':
                    params.set('period', 'today');
                    break;
                case 'my_activity':
                    params.set('user_id', '{{ auth()->id() }}');
                    break;
                case 'critical':
                    params.set('action', 'delete');
                    break;
                case 'creates':
                    params.set('action', 'create');
                    break;
                case 'updates':
                    params.set('action', 'update');
                    break;
                case 'deletes':
                    params.set('action', 'delete');
                    break;
            }

            window.location.search = params.toString();
        },

        toggleLog(id) {
            const index = this.selectedLogs.indexOf(id);
            if (index > -1) {
                this.selectedLogs.splice(index, 1);
            } else {
                this.selectedLogs.push(id);
            }
        },

        toggleSelectAll() {
            if (this.selectedLogs.length > 0) {
                this.selectedLogs = [];
            } else {
                this.selectedLogs = @json($logs->pluck('id')->toArray());
            }
        },

        bulkExport() {
            if (this.selectedLogs.length === 0) {
                alert('Selecione logs para exportar');
                return;
            }
            window.location.href = `/admin/audit-logs/export?ids=${this.selectedLogs.join(',')}`;
        },

        bulkDelete() {
            if (this.selectedLogs.length === 0) {
                alert('Selecione logs para deletar');
                return;
            }

            if (!confirm(`Deletar ${this.selectedLogs.length} log(s)?`)) return;

            fetch('/admin/audit-logs/bulk-destroy', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ ids: this.selectedLogs })
            })
            .then(response => response.json())
            .then(data => {
                alert(data.message);
                window.location.reload();
            })
            .catch(error => console.error('Error:', error));
        },

        viewDetails(id) {
            this.showModal = true;
            this.modalContent = '<div class="flex items-center justify-center py-8"><div class="animate-spin rounded-full h-8 w-8 border-b-2 border-red-600"></div></div>';

            fetch(`/admin/audit-logs/${id}`)
                .then(response => response.text())
                .then(html => {
                    this.modalContent = html;
                })
                .catch(error => {
                    this.modalContent = '<p class="text-red-600">Erro ao carregar detalhes</p>';
                });
        },

        revertLog(id) {
            if (!confirm('Tem certeza que deseja reverter esta mudança?')) return;

            fetch(`/admin/audit-logs/${id}/revert`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                alert(data.message);
                if (data.success) window.location.reload();
            })
            .catch(error => console.error('Error:', error));
        },

        addNote(id) {
            const note = prompt('Adicione uma nota a este log:');
            if (!note) return;

            fetch(`/admin/audit-logs/${id}/note`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ note })
            })
            .then(response => response.json())
            .then(data => {
                alert(data.message);
                if (data.success) window.location.reload();
            })
            .catch(error => console.error('Error:', error));
        },

        loadMore() {
            this.loading = true;
            // Implementar infinite scroll aqui
            setTimeout(() => {
                this.loading = false;
            }, 1000);
        }
    }
}
</script>
@endpush
