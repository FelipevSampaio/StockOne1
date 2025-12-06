@extends('layouts.admin')

@section('title', 'Restaurantes')
@section('page-title', 'Gerenciar Restaurantes')

@section('topbar-actions')
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.restaurantes.export') }}" class="btn-secondary">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            Exportar CSV
        </a>
        <a href="{{ route('admin.restaurantes.create') }}" class="btn-primary btn-ripple">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Novo Restaurante
        </a>
    </div>
@endsection

@section('content')

    <!-- Toggle de Visualização -->
    <div class="mb-4 flex justify-end" x-data="viewToggle()">
        <div class="inline-flex rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 p-1">
            <button @click="setView('table')"
                    :class="currentView === 'table' ? 'bg-red-600 text-white' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'"
                    class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium rounded-md transition-all duration-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                </svg>
                Tabela
            </button>
            <button @click="setView('grid')"
                    :class="currentView === 'grid' ? 'bg-red-600 text-white' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'"
                    class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium rounded-md transition-all duration-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                </svg>
                Grid
            </button>
        </div>
    </div>

    <!-- Filtros -->
    <form method="GET" action="{{ route('admin.restaurantes.index') }}" class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-100 dark:border-gray-700 p-4 mb-6">
        <div class="flex flex-wrap items-center gap-3">
            <div class="relative flex-1 min-w-[200px]">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" name="search" placeholder="Buscar por nome, CNPJ ou email..." value="{{ request('search') }}" class="pl-10 w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-1 focus:ring-red-500 focus:border-red-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400">
            </div>

            <select name="status" class="px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-1 focus:ring-red-500 focus:border-red-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                <option value="">Status</option>
                <option value="ativo" @selected(request('status') === 'ativo')>Ativo</option>
                <option value="inativo" @selected(request('status') === 'inativo')>Inativo</option>
            </select>

            <select name="health_risk" class="px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-1 focus:ring-red-500 focus:border-red-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                <option value="">📊 Health Score</option>
                <option value="baixo" @selected(request('health_risk') === 'baixo')>✅ Baixo Risco (60-100)</option>
                <option value="medio" @selected(request('health_risk') === 'medio')>⚠️ Risco Médio (40-59)</option>
                <option value="alto" @selected(request('health_risk') === 'alto')>🔶 Alto Risco (20-39)</option>
                <option value="critico" @selected(request('health_risk') === 'critico')>🔴 Crítico (0-19)</option>
            </select>

            <select name="per_page" class="px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-1 focus:ring-red-500 focus:border-red-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                <option value="10" @selected(request('per_page') == 10)>10 por página</option>
                <option value="25" @selected(request('per_page') == 25)>25 por página</option>
                <option value="50" @selected(request('per_page') == 50)>50 por página</option>
                <option value="100" @selected(request('per_page') == 100)>100 por página</option>
            </select>

            <button type="submit" class="btn-sm btn-ripple">
                Filtrar
            </button>

            @if(request()->anyFilled(['search', 'status', 'health_risk']))
                <a href="{{ route('admin.restaurantes.index') }}" class="inline-flex items-center px-4 py-2 text-sm bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Limpar
                </a>
            @endif
        </div>
    </form>

    <!-- Barra de Ações em Massa -->
    <div x-data="bulkActions()"
         x-show="selectedRestaurantes.length > 0"
         x-cloak
         x-transition:enter="slideInFromTop"
         class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4 mb-4 flex items-center justify-between shadow-sm">
        <div class="flex items-center gap-4">
            <span class="text-sm font-medium text-red-900 dark:text-red-100">
                <span x-text="selectedRestaurantes.length"></span> restaurante(s) selecionado(s)
            </span>
            <button @click="deselectAll()" class="text-xs text-red-700 dark:text-red-300 hover:text-red-900 dark:hover:text-red-100 underline transition-colors">
                Desmarcar todos
            </button>
        </div>
        <div class="flex items-center gap-2">
            <button @click="bulkAction('activate')"
                    class="btn-ripple inline-flex items-center px-3 py-1.5 text-xs font-medium bg-green-600 text-white rounded-lg hover:bg-green-700 hover:scale-105 transition-all duration-200">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Ativar
            </button>
            <button @click="bulkAction('deactivate')"
                    class="btn-ripple inline-flex items-center px-3 py-1.5 text-xs font-medium bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 hover:scale-105 transition-all duration-200">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                </svg>
                Desativar
            </button>
            <button @click="bulkAction('export')"
                    class="btn-ripple inline-flex items-center px-3 py-1.5 text-xs font-medium bg-blue-600 text-white rounded-lg hover:bg-blue-700 hover:scale-105 transition-all duration-200">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Exportar
            </button>
            <button @click="bulkAction('delete')"
                    class="btn-ripple inline-flex items-center px-3 py-1.5 text-xs font-medium bg-red-600 text-white rounded-lg hover:bg-red-700 hover:scale-105 transition-all duration-200">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
                Excluir
            </button>
        </div>
    </div>

    <!-- Container de Visualização -->
    <div x-data="{ currentView: localStorage.getItem('restaurantes_view') || 'table', isLoading: false }">

    <!-- Skeleton Loading -->
    <div x-show="isLoading" x-cloak class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden mb-6">
        <div class="overflow-x-auto">
            <div class="animate-pulse p-6 space-y-4">
                @for($i = 0; $i < 8; $i++)
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-gray-300 dark:bg-gray-700 rounded-full"></div>
                    <div class="flex-1 space-y-2">
                        <div class="h-4 bg-gray-300 dark:bg-gray-700 rounded w-3/4"></div>
                        <div class="h-3 bg-gray-300 dark:bg-gray-700 rounded w-1/2"></div>
                    </div>
                    <div class="w-20 h-6 bg-gray-300 dark:bg-gray-700 rounded"></div>
                    <div class="flex gap-2">
                        <div class="w-16 h-8 bg-gray-300 dark:bg-gray-700 rounded"></div>
                        <div class="w-16 h-8 bg-gray-300 dark:bg-gray-700 rounded"></div>
                    </div>
                </div>
                @endfor
            </div>
        </div>
    </div>

    <!-- Tabela -->
    <div x-show="!isLoading && currentView === 'table'" x-data="{ ...viewToggle(), ...bulkActions() }" class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden" x-transition>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-900">
                    <tr>
                        <th class="px-6 py-4 text-left">
                            <input type="checkbox"
                                   @change="toggleAll($event.target.checked)"
                                   :checked="selectedRestaurantes.length === {{ $restaurantes->count() }} && {{ $restaurantes->count() }} > 0"
                                   class="rounded border-gray-300 dark:border-gray-600 text-red-600 focus:ring-red-500 dark:bg-gray-700 dark:checked:bg-red-600 transition-all duration-200 cursor-pointer">
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Restaurante</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Contato</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($restaurantes as $restaurante)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-all duration-200"
                            :class="{ 'bg-red-50 dark:bg-red-900/10 selected-row': selectedRestaurantes.includes({{ $restaurante->id }}) }"
                            x-data="{ restauranteId: {{ $restaurante->id }} }">
                            <td class="px-6 py-4">
                                <input type="checkbox"
                                       :checked="selectedRestaurantes.includes({{ $restaurante->id }})"
                                       @change="toggleRestaurante({{ $restaurante->id }})"
                                       class="rounded border-gray-300 dark:border-gray-600 text-red-600 focus:ring-red-500 dark:bg-gray-700 dark:checked:bg-red-600 transition-all duration-200 cursor-pointer"
                                       :class="{ 'checkboxPulse': selectedRestaurantes.includes({{ $restaurante->id }}) }">
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <div class="h-10 w-10 rounded-full bg-red-100 dark:bg-red-900/30 flex items-center justify-center transition-transform duration-200 hover:scale-110">
                                            <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="flex items-center gap-2">
                                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $restaurante->nome }}</div>
                                            @if($restaurante->created_at >= now()->subDays(7))
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-400">
                                                    <svg class="w-3 h-3 mr-0.5" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                    </svg>
                                                    Novo
                                                </span>
                                            @endif
                                            @php
                                                $usersCount = $restaurante->users_count;
                                                $badgeColor = $usersCount === 0 ? 'red' : ($usersCount < 5 ? 'yellow' : 'green');
                                            @endphp
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-{{ $badgeColor }}-100 dark:bg-{{ $badgeColor }}-900/30 text-{{ $badgeColor }}-800 dark:text-{{ $badgeColor }}-400" title="{{ $usersCount }} usuário(s) vinculado(s)">
                                                <svg class="w-3 h-3 mr-0.5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                                                </svg>
                                                {{ $usersCount }}
                                            </span>
                                        </div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">{{ $restaurante->cnpj }}</div>
                                        @if($restaurante->updated_at >= now()->subDays(7))
                                            <div class="mt-1 flex items-center text-xs">
                                                <svg class="w-3 h-3 mr-1 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                                </svg>
                                                <span class="text-green-600 dark:text-green-400 font-medium">Ativo recentemente</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900 dark:text-gray-100">{{ $restaurante->email }}</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">{{ $restaurante->telefone }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-2">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium transition-all duration-200 {{ $restaurante->status === 'ativo' ? 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-400' : 'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300' }}">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            @if($restaurante->status === 'ativo')
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                            @else
                                                <path fill-rule="evenodd" d="M13.477 14.89A6 6 0 015.11 6.524l8.367 8.368zm1.414-1.414L6.524 5.11a6 6 0 018.367 8.367zM18 10a8 8 0 11-16 0 8 8 0 0116 0z" clip-rule="evenodd"/>
                                            @endif
                                        </svg>
                                        {{ ucfirst($restaurante->status) }}
                                    </span>
                                    @php
                                        $healthData = $restaurante->health_data;
                                        $colorMap = [
                                            'green' => 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-400 border-green-300 dark:border-green-700',
                                            'blue' => 'bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-400 border-blue-300 dark:border-blue-700',
                                            'yellow' => 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-400 border-yellow-300 dark:border-yellow-700',
                                            'orange' => 'bg-orange-100 dark:bg-orange-900/30 text-orange-800 dark:text-orange-400 border-orange-300 dark:border-orange-700',
                                            'red' => 'bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-400 border-red-300 dark:border-red-700',
                                        ];
                                        $colorClass = $colorMap[$healthData['color']] ?? 'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300 border-gray-300 dark:border-gray-600';
                                    @endphp
                                    <button @click="window.quickView({{ $restaurante->id }})" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border transition-all duration-200 hover:scale-105 {{ $colorClass }}" title="Clique para ver análise detalhada">
                                        📊 {{ $healthData['score'] }}
                                    </button>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right" x-data="{ open: false }">
                                <div class="flex items-center justify-end gap-2">
                                    <!-- Botão Ver Detalhes -->
                                    <button @click="window.quickView({{ $restaurante->id }})"
                                            class="inline-flex items-center gap-1.5 px-3 py-2 border border-gray-300 dark:border-gray-600 text-gray-600 dark:text-gray-400 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 rounded-lg transition-colors text-sm font-medium"
                                            title="Ver detalhes">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        <span class="hidden xl:inline">Ver</span>
                                    </button>

                                    <!-- Dropdown de Ações -->
                                    <div class="relative inline-block text-left">
                                        <button @click="open = !open" type="button" class="inline-flex items-center px-3 py-2 border border-gray-300 dark:border-gray-600 shadow-sm text-sm font-medium rounded-lg text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
                                            Ações
                                            <svg class="ml-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                            </svg>
                                        </button>

                                        <div x-show="open"
                                             @click.away="open = false"
                                             x-cloak
                                             x-transition:enter="transition ease-out duration-100"
                                             x-transition:enter-start="transform opacity-0 scale-95"
                                             x-transition:enter-end="transform opacity-100 scale-100"
                                             x-transition:leave="transition ease-in duration-75"
                                             x-transition:leave-start="transform opacity-100 scale-100"
                                             x-transition:leave-end="transform opacity-0 scale-95"
                                             class="origin-top-right absolute right-0 mt-2 w-48 rounded-lg shadow-lg bg-white dark:bg-gray-800 ring-1 ring-black dark:ring-gray-700 ring-opacity-5 z-10">
                                            <div class="py-1">
                                                <a href="{{ route('admin.restaurantes.edit', $restaurante) }}" class="group flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-gray-100">
                                                    <svg class="mr-3 h-4 w-4 text-gray-400 group-hover:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                    </svg>
                                                    Editar
                                                </a>

                                                <button @click="window.toggleStatus({{ $restaurante->id }}, '{{ $restaurante->status }}'); open = false" class="group flex w-full items-center px-4 py-2 text-sm {{ $restaurante->status === 'ativo' ? 'text-yellow-700 dark:text-yellow-400 hover:bg-yellow-50 dark:hover:bg-yellow-900/20' : 'text-green-700 dark:text-green-400 hover:bg-green-50 dark:hover:bg-green-900/20' }}">
                                                    @if($restaurante->status === 'ativo')
                                                        <svg class="mr-3 h-4 w-4 text-yellow-400 group-hover:text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                                        </svg>
                                                        Desativar
                                                    @else
                                                        <svg class="mr-3 h-4 w-4 text-green-400 group-hover:text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                        </svg>
                                                        Ativar
                                                    @endif
                                                </button>

                                                <div class="border-t border-gray-100 dark:border-gray-700"></div>

                                                <button @click="window.deleteRestaurante({{ $restaurante->id }}, '{{ $restaurante->nome }}'); open = false" class="group flex w-full items-center px-4 py-2 text-sm text-red-700 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20">
                                                    <svg class="mr-3 h-4 w-4 text-red-400 group-hover:text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                    Excluir
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-24 h-24 bg-gradient-to-br from-red-50 to-red-100 dark:from-red-900/20 dark:to-red-800/20 rounded-full flex items-center justify-center mb-4">
                                        <svg class="w-12 h-12 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                        </svg>
                                    </div>
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-1">Nenhum restaurante encontrado</h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-6 max-w-sm">
                                        @if(request()->anyFilled(['search', 'status']))
                                            Nenhum restaurante corresponde aos filtros aplicados. Tente ajustar os critérios de busca.
                                        @else
                                            Adicione o primeiro restaurante para começar a gerenciar o sistema.
                                        @endif
                                    </p>
                                    @if(request()->anyFilled(['search', 'status']))
                                        <a href="{{ route('admin.restaurantes.index') }}" class="btn-secondary">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                            Limpar Filtros
                                        </a>
                                    @else
                                        <a href="{{ route('admin.restaurantes.create') }}" class="btn-primary">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                            </svg>
                                            Criar Primeiro Restaurante
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Visualização em Grid -->
    <div x-show="!isLoading && currentView === 'grid'" x-data="{ ...viewToggle(), ...bulkActions() }" x-cloak x-transition class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($restaurantes as $restaurante)
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 hover:shadow-md transition-all duration-200 overflow-hidden"
                 :class="{ 'ring-2 ring-red-500': selectedRestaurantes.includes({{ $restaurante->id }}) }">
                <div class="p-6">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center gap-3">
                            <input type="checkbox"
                                   :checked="selectedRestaurantes.includes({{ $restaurante->id }})"
                                   @change="toggleRestaurante({{ $restaurante->id }})"
                                   class="rounded border-gray-300 dark:border-gray-600 text-red-600 focus:ring-red-500 dark:bg-gray-700 dark:checked:bg-red-600 transition-all duration-200 cursor-pointer">
                            <div class="h-12 w-12 rounded-full bg-red-100 dark:bg-red-900/30 flex items-center justify-center">
                                <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                            </div>
                        </div>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $restaurante->status === 'ativo' ? 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-400' : 'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300' }}">
                            {{ ucfirst($restaurante->status) }}
                        </span>
                    </div>

                    <div class="mb-4">
                        <div class="flex items-center gap-2 mb-2">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $restaurante->nome }}</h3>
                            @if($restaurante->created_at >= now()->subDays(7))
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-400">
                                    <svg class="w-3 h-3 mr-0.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                    Novo
                                </span>
                            @endif
                        </div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $restaurante->cnpj }}</p>
                    </div>

                    <div class="space-y-2 mb-4">
                        <div class="flex items-center text-sm text-gray-700 dark:text-gray-300">
                            <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            {{ $restaurante->email }}
                        </div>
                        <div class="flex items-center text-sm text-gray-700 dark:text-gray-300">
                            <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                            {{ $restaurante->telefone ?: 'Não informado' }}
                        </div>
                        @php
                            $usersCount = $restaurante->users_count;
                            $badgeColor = $usersCount === 0 ? 'red' : ($usersCount < 5 ? 'yellow' : 'green');
                        @endphp
                        <div class="flex items-center text-sm">
                            <svg class="w-4 h-4 mr-2 text-{{ $badgeColor }}-500" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                            </svg>
                            <span class="text-{{ $badgeColor }}-600 dark:text-{{ $badgeColor }}-400 font-medium">{{ $usersCount }} usuário(s)</span>
                        </div>
                        @if($restaurante->updated_at >= now()->subDays(7))
                            <div class="flex items-center text-sm">
                                <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                                <span class="text-green-600 dark:text-green-400 font-medium">Ativo recentemente</span>
                            </div>
                        @endif
                    </div>

                    <div class="flex items-center gap-2 pt-4 border-t border-gray-200 dark:border-gray-700" x-data="{ open: false }">
                        <button @click="window.quickView({{ $restaurante->id }})"
                                class="flex-1 inline-flex items-center justify-center gap-1.5 px-3 py-2 border border-gray-300 dark:border-gray-600 text-gray-600 dark:text-gray-400 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 rounded-lg transition-colors text-sm font-medium">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            Ver
                        </button>

                        <div class="flex-1 relative">
                            <button @click="open = !open" type="button" class="w-full btn-ripple inline-flex items-center justify-center gap-1.5 px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-600 rounded-lg transition-all duration-200">
                                Ações
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>

                            <div x-show="open"
                                 @click.away="open = false"
                                 x-cloak
                                 x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="transform opacity-0 scale-95"
                                 x-transition:enter-end="transform opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="transform opacity-100 scale-100"
                                 x-transition:leave-end="transform opacity-0 scale-95"
                                 class="origin-top-right absolute right-0 bottom-full mb-2 w-48 rounded-lg shadow-lg bg-white dark:bg-gray-800 ring-1 ring-black dark:ring-gray-700 ring-opacity-5 z-10">
                                <div class="py-1">
                                    <a href="{{ route('admin.restaurantes.edit', $restaurante) }}" class="group flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-gray-100">
                                        <svg class="mr-3 h-4 w-4 text-gray-400 group-hover:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                        Editar
                                    </a>

                                    <button @click="window.toggleStatus({{ $restaurante->id }}, '{{ $restaurante->status }}'); open = false" class="group flex w-full items-center px-4 py-2 text-sm {{ $restaurante->status === 'ativo' ? 'text-yellow-700 dark:text-yellow-400 hover:bg-yellow-50 dark:hover:bg-yellow-900/20' : 'text-green-700 dark:text-green-400 hover:bg-green-50 dark:hover:bg-green-900/20' }}">
                                        @if($restaurante->status === 'ativo')
                                            <svg class="mr-3 h-4 w-4 text-yellow-400 group-hover:text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                            </svg>
                                            Desativar
                                        @else
                                            <svg class="mr-3 h-4 w-4 text-green-400 group-hover:text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            Ativar
                                        @endif
                                    </button>

                                    <div class="border-t border-gray-100 dark:border-gray-700"></div>

                                    <button @click="window.deleteRestaurante({{ $restaurante->id }}, '{{ $restaurante->nome }}'); open = false" class="group flex w-full items-center px-4 py-2 text-sm text-red-700 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20">
                                        <svg class="mr-3 h-4 w-4 text-red-400 group-hover:text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                        Excluir
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Paginação com Info -->
    @if($restaurantes->hasPages())
        <div class="mt-6 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="text-sm text-gray-700 dark:text-gray-300">
                Exibindo <span class="font-semibold">{{ $restaurantes->firstItem() }}</span>
                a <span class="font-semibold">{{ $restaurantes->lastItem() }}</span>
                de <span class="font-semibold">{{ $restaurantes->total() }}</span> resultados
            </div>
            <div>
                {{ $restaurantes->links() }}
            </div>
        </div>
    @endif
    </div>

    <!-- Cards Estatísticos -->
    <div class="mt-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 rounded-xl p-6 border border-blue-200 dark:border-blue-800">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-blue-600 dark:text-blue-400">Total de Restaurantes</p>
                    <p class="text-3xl font-bold text-blue-900 dark:text-blue-100 mt-2">{{ $stats['total'] }}</p>
                    <p class="text-xs text-blue-700 dark:text-blue-300 mt-1">
                        {{ $stats['ativos'] }} ativos · {{ $stats['inativos'] }} inativos
                    </p>
                </div>
                <div class="h-12 w-12 bg-blue-500 dark:bg-blue-600 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-800/20 rounded-xl p-6 border border-green-200 dark:border-green-800">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-green-600 dark:text-green-400">Novos (7 dias)</p>
                    <p class="text-3xl font-bold text-green-900 dark:text-green-100 mt-2">{{ $stats['novos'] }}</p>
                    <p class="text-xs text-green-700 dark:text-green-300 mt-1">
                        @if($stats['total'] > 0)
                            {{ round(($stats['novos'] / $stats['total']) * 100, 1) }}% do total
                        @else
                            0% do total
                        @endif
                    </p>
                </div>
                <div class="h-12 w-12 bg-green-500 dark:bg-green-600 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900/20 dark:to-purple-800/20 rounded-xl p-6 border border-purple-200 dark:border-purple-800">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-purple-600 dark:text-purple-400">Total de Usuários</p>
                    <p class="text-3xl font-bold text-purple-900 dark:text-purple-100 mt-2">{{ $stats['total_usuarios'] }}</p>
                    <p class="text-xs text-purple-700 dark:text-purple-300 mt-1">
                        Vinculados aos restaurantes
                    </p>
                </div>
                <div class="h-12 w-12 bg-purple-500 dark:bg-purple-600 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-orange-50 to-orange-100 dark:from-orange-900/20 dark:to-orange-800/20 rounded-xl p-6 border border-orange-200 dark:border-orange-800">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-orange-600 dark:text-orange-400">Média de Usuários</p>
                    <p class="text-3xl font-bold text-orange-900 dark:text-orange-100 mt-2">{{ $stats['media_usuarios'] }}</p>
                    <p class="text-xs text-orange-700 dark:text-orange-300 mt-1">
                        Por restaurante
                    </p>
                </div>
                <div class="h-12 w-12 bg-orange-500 dark:bg-orange-600 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    // Definir funções globais ANTES do Alpine inicializar
    console.log('Definindo window.restauranteActions...');

    window.restauranteActions = {};

    document.addEventListener('alpine:init', () => {
        Alpine.data('viewToggle', () => ({
            currentView: localStorage.getItem('restaurantes_view') || 'table',

            init() {
                window.addEventListener('view-changed', () => {
                    this.currentView = localStorage.getItem('restaurantes_view') || 'table';
                });
            },

            setView(view) {
                this.currentView = view;
                localStorage.setItem('restaurantes_view', view);
                window.dispatchEvent(new CustomEvent('view-changed'));
            }
        }));

        Alpine.data('bulkActions', () => ({
            selectedRestaurantes: [],

            toggleRestaurante(id) {
                const index = this.selectedRestaurantes.indexOf(id);
                if (index > -1) {
                    this.selectedRestaurantes.splice(index, 1);
                } else {
                    this.selectedRestaurantes.push(id);
                }
            },

            toggleAll(checked) {
                if (checked) {
                    this.selectedRestaurantes = [
                        @foreach($restaurantes as $restaurante)
                            {{ $restaurante->id }},
                        @endforeach
                    ];
                } else {
                    this.selectedRestaurantes = [];
                }
            },

            deselectAll() {
                this.selectedRestaurantes = [];
            }
        }));

        // Funções globais disponíveis para todos os componentes
        window.restauranteActions = {
            bulkAction(action) {
                const bulkActionsComponent = Alpine.$data(document.querySelector('[x-data*="bulkActions"]'));
                if (!bulkActionsComponent) return;

                const selectedRestaurantes = bulkActionsComponent.selectedRestaurantes;
                if (selectedRestaurantes.length === 0) {
                    window.dispatchEvent(new CustomEvent('show-toast', {
                        detail: { message: 'Selecione pelo menos um restaurante', type: 'warning' }
                    }));
                    return;
                }

                let confirmTitle = '';
                let confirmMessage = '';
                let confirmType = 'warning';

                switch(action) {
                    case 'activate':
                        confirmTitle = 'Ativar Restaurantes';
                        confirmMessage = `Deseja ativar ${selectedRestaurantes.length} restaurante(s)?`;
                        confirmType = 'info';
                        break;
                    case 'deactivate':
                        confirmTitle = 'Desativar Restaurantes';
                        confirmMessage = `Deseja desativar ${selectedRestaurantes.length} restaurante(s)?`;
                        confirmType = 'warning';
                        break;
                    case 'delete':
                        confirmTitle = 'Excluir Restaurantes';
                        confirmMessage = `Deseja realmente excluir ${selectedRestaurantes.length} restaurante(s)? Esta ação NÃO pode ser desfeita.`;
                        confirmType = 'danger';
                        break;
                    case 'export':
                        confirmTitle = 'Exportar Restaurantes';
                        confirmMessage = `Deseja exportar ${selectedRestaurantes.length} restaurante(s) para CSV?`;
                        confirmType = 'info';
                        break;
                }

                const selectedIds = [...selectedRestaurantes];

                window.dispatchEvent(new CustomEvent('show-confirm', {
                    detail: {
                        title: confirmTitle,
                        message: confirmMessage,
                        type: confirmType,
                        onConfirm: async () => {
                            window.dispatchEvent(new CustomEvent('show-loading'));

                try {
                    if (action === 'export') {
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = '{{ route("admin.restaurantes.bulk-action") }}';

                        const csrfInput = document.createElement('input');
                                csrfInput.type = 'hidden';
                                csrfInput.name = '_token';
                                csrfInput.value = '{{ csrf_token() }}';
                                form.appendChild(csrfInput);

                                const actionInput = document.createElement('input');
                                actionInput.type = 'hidden';
                                actionInput.name = 'action';
                                actionInput.value = action;
                                form.appendChild(actionInput);

                                selectedIds.forEach(id => {
                                    const idInput = document.createElement('input');
                                    idInput.type = 'hidden';
                                    idInput.name = 'ids[]';
                                    idInput.value = id;
                                    form.appendChild(idInput);
                                });

                                document.body.appendChild(form);
                                form.submit();
                                document.body.removeChild(form);

                                window.dispatchEvent(new CustomEvent('hide-loading'));
                                window.dispatchEvent(new CustomEvent('show-toast', {
                                    detail: { message: 'Download iniciado!', type: 'success' }
                                }));
                                return;
                            }

                            const response = await fetch('{{ route("admin.restaurantes.bulk-action") }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify({
                                    action: action,
                                    ids: selectedIds
                                })
                            });

                            const data = await response.json();

                            window.dispatchEvent(new CustomEvent('hide-loading'));

                            if (data.success) {
                                window.dispatchEvent(new CustomEvent('show-toast', {
                                    detail: { message: data.message, type: 'success' }
                                }));
                                setTimeout(() => window.location.reload(), 1000);
                            } else {
                                window.dispatchEvent(new CustomEvent('show-toast', {
                                    detail: { message: data.message, type: 'error' }
                                }));
                            }
                            } catch (error) {
                                window.dispatchEvent(new CustomEvent('hide-loading'));
                                console.error('Erro:', error);
                                window.dispatchEvent(new CustomEvent('show-toast', {
                                    detail: { message: 'Erro ao processar ação', type: 'error' }
                                }));
                            }
                        },
                        onCancel: () => {}
                    }
                }));
            },

            async toggleStatus(id, currentStatus) {
                console.log('=== toggleStatus CHAMADO ===');
                console.log('ID:', id);
                console.log('Status atual:', currentStatus);

                const newStatus = currentStatus === 'ativo' ? 'inativo' : 'ativo';
                const action = newStatus === 'ativo' ? 'ativar' : 'desativar';

                console.log('Novo status será:', newStatus);
                console.log('Ação:', action);

                // Confirmação via confirm nativo primeiro para debug
                if (!confirm(`Deseja realmente ${action} este restaurante?`)) {
                    console.log('Usuário cancelou a ação');
                    return;
                }

                console.log('Usuário confirmou - iniciando requisição...');
                window.dispatchEvent(new CustomEvent('show-loading'));

                try {
                    const url = `/admin/restaurantes/${id}/toggle-status`;
                    console.log('URL da requisição:', url);
                    console.log('CSRF Token:', '{{ csrf_token() }}');

                    const response = await fetch(url, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    });

                    console.log('Status HTTP:', response.status);
                    console.log('Response OK:', response.ok);

                    const responseText = await response.text();
                    console.log('Resposta (texto):', responseText);

                    let data;
                    try {
                        data = JSON.parse(responseText);
                        console.log('Resposta (JSON):', data);
                    } catch (e) {
                        console.error('Erro ao fazer parse do JSON:', e);
                        throw new Error('Resposta inválida do servidor');
                    }

                    window.dispatchEvent(new CustomEvent('hide-loading'));

                    if (data.success) {
                        console.log('✅ SUCCESS! Novo status:', data.status);
                        window.dispatchEvent(new CustomEvent('show-toast', {
                            detail: { message: data.message || 'Status alterado com sucesso!', type: 'success' }
                        }));
                        console.log('Aguardando 800ms para recarregar...');
                        setTimeout(() => {
                            console.log('Recarregando página agora...');
                            window.location.reload();
                        }, 800);
                    } else {
                        console.error('❌ ERRO:', data.message);
                        window.dispatchEvent(new CustomEvent('show-toast', {
                            detail: { message: data.message || 'Erro ao alterar status', type: 'error' }
                        }));
                    }
                } catch (error) {
                    window.dispatchEvent(new CustomEvent('hide-loading'));
                    console.error('❌ EXCEPTION:', error);
                    console.error('Stack trace:', error.stack);
                    window.dispatchEvent(new CustomEvent('show-toast', {
                        detail: { message: 'Erro ao alterar status: ' + error.message, type: 'error' }
                    }));
                }
            },

            deleteRestaurante(id, nome) {
                window.dispatchEvent(new CustomEvent('show-confirm', {
                    detail: {
                        title: 'Excluir Restaurante',
                        message: `Deseja realmente excluir o restaurante "${nome}"? Esta ação NÃO pode ser desfeita e irá desvincular todos os usuários associados.`,
                        type: 'danger',
                        onConfirm: () => {
                            window.dispatchEvent(new CustomEvent('show-loading'));

                            try {
                                const form = document.createElement('form');
                                form.method = 'POST';
                                form.action = `/admin/restaurantes/${id}`;

                                const csrfInput = document.createElement('input');
                                csrfInput.type = 'hidden';
                                csrfInput.name = '_token';
                                csrfInput.value = '{{ csrf_token() }}';
                                form.appendChild(csrfInput);

                                const methodInput = document.createElement('input');
                                methodInput.type = 'hidden';
                                methodInput.name = '_method';
                                methodInput.value = 'DELETE';
                                form.appendChild(methodInput);

                                document.body.appendChild(form);
                                form.submit();
                            } catch (error) {
                                window.dispatchEvent(new CustomEvent('hide-loading'));
                                console.error('Erro:', error);
                                window.dispatchEvent(new CustomEvent('show-toast', {
                                    detail: { message: 'Erro ao excluir restaurante', type: 'error' }
                                }));
                            }
                        },
                        onCancel: () => {}
                    }
                }));
            },

            async quickView(id) {
                window.dispatchEvent(new CustomEvent('show-loading'));

                try {
                    const response = await fetch(`/admin/restaurantes/${id}/quick-view`, {
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    });

                    const data = await response.json();

                    window.dispatchEvent(new CustomEvent('hide-loading'));

                    if (data.success) {
                        const restaurante = data.restaurante;

                        const usersHtml = restaurante.users.length > 0
                            ? restaurante.users.map(user => `
                                <div class="flex items-center gap-2 p-2 bg-gray-50 dark:bg-gray-800 rounded">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    <div class="flex-1 min-w-0">
                                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100 truncate">${user.name}</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400 truncate">${user.email}</div>
                                    </div>
                                </div>
                            `).join('')
                            : '<p class="text-sm text-gray-500 dark:text-gray-400 text-center py-4">Nenhum usuário vinculado</p>';

                        const modalContent = `
                            <div class="space-y-4">
                                <div class="flex items-start gap-4">
                                    <div class="flex-shrink-0">
                                        <div class="w-16 h-16 rounded-full bg-red-100 dark:bg-red-900/30 flex items-center justify-center">
                                            <svg class="w-8 h-8 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-1">${restaurante.nome}</h3>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${restaurante.status === 'ativo' ? 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-400' : 'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300'}">
                                            ${restaurante.status === 'ativo' ? 'Ativo' : 'Inativo'}
                                        </span>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">CNPJ</label>
                                        <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">${restaurante.cnpj}</p>
                                    </div>
                                    <div>
                                        <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Telefone</label>
                                        <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">${restaurante.telefone || '-'}</p>
                                    </div>
                                    <div class="md:col-span-2">
                                        <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">E-mail</label>
                                        <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">${restaurante.email}</p>
                                    </div>
                                    <div class="md:col-span-2">
                                        <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Endereço</label>
                                        <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">${restaurante.endereco || '-'}</p>
                                    </div>
                                </div>

                                <div>
                                    <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2 block">
                                        Usuários Vinculados (${restaurante.users_count})
                                    </label>
                                    <div class="space-y-2 max-h-48 overflow-y-auto">
                                        ${usersHtml}
                                    </div>
                                </div>

                                ${data.health_data ? `
                                <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                                    <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-3 block">
                                        📊 Health Score
                                    </label>

                                    <!-- Score Principal com Tendência -->
                                    <div class="mb-4 p-4 rounded-lg bg-gradient-to-r from-${data.health_data.color}-50 to-${data.health_data.color}-100 dark:from-${data.health_data.color}-900/20 dark:to-${data.health_data.color}-800/20 border border-${data.health_data.color}-200 dark:border-${data.health_data.color}-800">
                                        <div class="flex items-center justify-between mb-3">
                                            <div>
                                                <div class="flex items-center gap-2">
                                                    <span class="text-3xl font-bold text-${data.health_data.color}-800 dark:text-${data.health_data.color}-300">${data.health_data.score}</span>
                                                    ${data.health_trend ? `
                                                        <span class="text-2xl">${data.health_trend.icon}</span>
                                                        ${data.health_trend.difference > 0 ? `
                                                            <span class="text-sm font-semibold text-${data.health_trend.color}-600 dark:text-${data.health_trend.color}-400">
                                                                ${data.health_trend.difference > 0 ? '+' : ''}${data.health_trend.difference}
                                                            </span>
                                                        ` : ''}
                                                    ` : ''}
                                                </div>
                                                <div class="text-sm font-medium text-${data.health_data.color}-700 dark:text-${data.health_data.color}-400">${data.health_data.status}</div>
                                                ${data.health_trend && data.health_trend.difference > 0 ? `
                                                    <div class="text-xs text-gray-600 dark:text-gray-400 mt-1">vs. mês anterior</div>
                                                ` : ''}
                                            </div>
                                            <div class="flex flex-col items-end">
                                                <div class="text-xs text-${data.health_data.color}-600 dark:text-${data.health_data.color}-400 mb-1">Risco</div>
                                                <span class="px-3 py-1 rounded text-xs font-bold uppercase ${data.health_data.risk === 'crítico' ? 'bg-red-600 text-white' : data.health_data.risk === 'alto' ? 'bg-orange-600 text-white' : data.health_data.risk === 'médio' ? 'bg-yellow-600 text-white' : 'bg-green-600 text-white'}">
                                                    ${data.health_data.risk}
                                                </span>
                                            </div>
                                        </div>

                                        <!-- Ciclo de Vida -->
                                        ${data.lifecycle_stage ? `
                                            <div class="pt-3 border-t border-${data.health_data.color}-200 dark:border-${data.health_data.color}-700">
                                                <div class="flex items-center gap-2">
                                                    <span class="text-lg">${data.lifecycle_stage.icon}</span>
                                                    <span class="text-xs font-medium text-gray-700 dark:text-gray-300">${data.lifecycle_stage.stage}</span>
                                                </div>
                                            </div>
                                        ` : ''}
                                    </div>

                                    <!-- Resumo Simples -->
                                    <div class="grid grid-cols-2 gap-3">
                                        <div class="p-3 rounded-lg bg-gray-50 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700">
                                            <div class="flex items-center gap-2 mb-1">
                                                <span class="text-lg">🔐</span>
                                                <span class="text-xs font-medium text-gray-900 dark:text-gray-100">Último Login</span>
                                            </div>
                                            <p class="text-sm text-gray-600 dark:text-gray-400 ml-6">
                                                ${data.health_data.last_login_days !== null ? data.health_data.last_login_days + ' dia(s) atrás' : 'Nunca'}
                                            </p>
                                        </div>

                                        <div class="p-3 rounded-lg bg-gray-50 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700">
                                            <div class="flex items-center gap-2 mb-1">
                                                <span class="text-lg">📦</span>
                                                <span class="text-xs font-medium text-gray-900 dark:text-gray-100">Pedidos (30d)</span>
                                            </div>
                                            <p class="text-sm text-gray-600 dark:text-gray-400 ml-6">${data.health_data.pedidos_mes} pedidos</p>
                                        </div>

                                        <div class="p-3 rounded-lg bg-gray-50 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700">
                                            <div class="flex items-center gap-2 mb-1">
                                                <span class="text-lg">📊</span>
                                                <span class="text-xs font-medium text-gray-900 dark:text-gray-100">Insumos</span>
                                            </div>
                                            <p class="text-sm text-gray-600 dark:text-gray-400 ml-6">${data.health_data.tem_insumos ? 'Cadastrado' : 'Não cadastrado'}</p>
                                        </div>

                                        <div class="p-3 rounded-lg bg-gray-50 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700">
                                            <div class="flex items-center gap-2 mb-1">
                                                <span class="text-lg">🍽️</span>
                                                <span class="text-xs font-medium text-gray-900 dark:text-gray-100">Cardápio</span>
                                            </div>
                                            <p class="text-sm text-gray-600 dark:text-gray-400 ml-6">${data.health_data.tem_cardapio ? 'Ativo' : 'Inativo'}</p>
                                        </div>
                                    </div>

                                    <!-- Ações Recomendadas -->
                                    ${data.recommended_actions && data.recommended_actions.length > 0 ? `
                                        <div class="mt-4">
                                            <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2 block">
                                                💡 Ações Recomendadas
                                            </label>
                                            <div class="space-y-2">
                                                ${data.recommended_actions.map(action => `
                                                    <div class="p-3 rounded-lg border ${action.priority === 'critical' ? 'bg-red-50 dark:bg-red-900/20 border-red-300 dark:border-red-800' : action.priority === 'high' ? 'bg-orange-50 dark:bg-orange-900/20 border-orange-300 dark:border-orange-800' : 'bg-blue-50 dark:bg-blue-900/20 border-blue-300 dark:border-blue-800'}">
                                                        <div class="flex items-start gap-2">
                                                            <span class="text-lg">${action.icon}</span>
                                                            <div class="flex-1">
                                                                <div class="flex items-center gap-2 mb-1">
                                                                    <span class="text-sm font-semibold text-gray-900 dark:text-gray-100">${action.title}</span>
                                                                    <span class="px-2 py-0.5 rounded text-xs font-medium ${action.priority === 'critical' ? 'bg-red-600 text-white' : action.priority === 'high' ? 'bg-orange-600 text-white' : 'bg-blue-600 text-white'}">
                                                                        ${action.priority === 'critical' ? 'URGENTE' : action.priority === 'high' ? 'ALTA' : 'MÉDIA'}
                                                                    </span>
                                                                </div>
                                                                <p class="text-xs text-gray-600 dark:text-gray-400">${action.description}</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                `).join('')}
                                            </div>
                                        </div>
                                    ` : ''}

                                    <!-- Histórico Simplificado (Últimos 30 dias) -->
                                    ${data.health_history && data.health_history.length > 0 ? `
                                        <div class="mt-4">
                                            <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2 block">
                                                📈 Evolução (Últimos 30 dias)
                                            </label>
                                            <div class="p-3 rounded-lg bg-gray-50 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700">
                                                <div class="flex items-end justify-between gap-1 h-24">
                                                    ${data.health_history.map(point => `
                                                        <div class="flex flex-col items-center flex-1" title="${point.date}: ${point.score} pts">
                                                            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-t" style="height: ${point.score}%"></div>
                                                            <div class="text-xs text-gray-500 dark:text-gray-400 mt-1 rotate-45 origin-top-left whitespace-nowrap">${point.date}</div>
                                                        </div>
                                                    `).join('')}
                                                </div>
                                            </div>
                                        </div>
                                    ` : ''}

                                    <!-- Alertas -->
                                    ${data.health_data.alerts && data.health_data.alerts.length > 0 ? `
                                        <div class="mt-3 p-3 rounded-lg bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800">
                                            <div class="text-xs font-semibold text-yellow-800 dark:text-yellow-300 mb-2">⚠️ Alertas</div>
                                            <ul class="space-y-1">
                                                ${data.health_data.alerts.map(alert => `
                                                    <li class="text-xs text-yellow-700 dark:text-yellow-400">${alert}</li>
                                                `).join('')}
                                            </ul>
                                        </div>
                                    ` : ''}
                                </div>
                                ` : ''}

                                <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                                    <div class="flex items-center justify-between text-xs text-gray-500 dark:text-gray-400">
                                        <span>Criado em: ${restaurante.created_at}</span>
                                        <span>Atualizado: ${restaurante.updated_at}</span>
                                    </div>
                                </div>

                                <div class="flex justify-end gap-2 pt-4">
                                    <button onclick="window.location.href='/admin/restaurantes/${restaurante.id}/edit'"
                                            class="btn-ripple inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                        Editar
                                    </button>
                                </div>
                            </div>
                        `;

                        // Criar modal customizado
                        const modalOverlay = document.createElement('div');
                        modalOverlay.className = 'fixed inset-0 z-50 overflow-y-auto';
                        modalOverlay.innerHTML = `
                            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                                <div class="fixed inset-0 transition-opacity bg-gray-500 dark:bg-gray-900 bg-opacity-75 dark:bg-opacity-75 backdrop-blur-sm" onclick="this.parentElement.parentElement.remove()"></div>
                                <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
                                <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                                    <div class="bg-white dark:bg-gray-800 px-6 pt-5 pb-4">
                                        <div class="flex items-center justify-between mb-4">
                                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Detalhes do Restaurante</h3>
                                            <button onclick="this.closest('.fixed').remove()" class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                </svg>
                                            </button>
                                        </div>
                                        ${modalContent}
                                    </div>
                                </div>
                            </div>
                        `;
                        document.body.appendChild(modalOverlay);
                    } else {
                        window.dispatchEvent(new CustomEvent('show-toast', {
                            detail: { message: 'Erro ao carregar dados', type: 'error' }
                        }));
                    }
                } catch (error) {
                    window.dispatchEvent(new CustomEvent('hide-loading'));
                    console.error('Erro:', error);
                    window.dispatchEvent(new CustomEvent('show-toast', {
                        detail: { message: 'Erro ao carregar detalhes', type: 'error' }
                    }));
                }
            }
        };

        console.log('window.restauranteActions definido:', window.restauranteActions);

        // Expor funções globalmente para uso em @click
        window.quickView = window.restauranteActions.quickView.bind(window.restauranteActions);
        window.toggleStatus = window.restauranteActions.toggleStatus.bind(window.restauranteActions);
        window.deleteRestaurante = window.restauranteActions.deleteRestaurante.bind(window.restauranteActions);
        window.bulkAction = window.restauranteActions.bulkAction.bind(window.restauranteActions);

        console.log('Funções globais criadas:', {
            quickView: typeof window.quickView,
            toggleStatus: typeof window.toggleStatus,
            deleteRestaurante: typeof window.deleteRestaurante,
            bulkAction: typeof window.bulkAction
        });
    });
</script>
@endsection
