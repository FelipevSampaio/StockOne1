@extends('layouts.admin')

@section('title', 'Usuários')
@section('page-title', 'Gerenciar Usuários')

@section('topbar-actions')
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.users.export') }}{{ request()->getQueryString() ? '?' . request()->getQueryString() : '' }}" class="btn-secondary">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            Exportar CSV
        </a>
        <a href="{{ route('admin.users.create') }}" class="btn-primary">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Novo Usuário
        </a>
    </div>
@endsection

@section('content')

    <!-- Filtros Inteligentes (Atalhos) -->
    <div class="flex flex-wrap gap-2 mb-4">
        <a href="{{ route('admin.users.index') }}" class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-lg {{ !request()->has('status') && !request()->has('role') ? 'bg-red-600 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }} transition-colors">
            <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
            </svg>
            Todos
        </a>
        <a href="{{ route('admin.users.index', ['status' => 'ativo']) }}" class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-lg {{ request('status') === 'ativo' ? 'bg-green-600 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }} transition-colors">
            <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Ativos
        </a>
        <a href="{{ route('admin.users.index', ['role' => 'admin']) }}" class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-lg {{ request('role') === 'admin' ? 'bg-red-600 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }} transition-colors">
            <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
            </svg>
            Admins
        </a>
        <a href="{{ route('admin.users.index', ['restaurante_id' => 'null']) }}" class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-lg {{ request('restaurante_id') === 'null' || (request()->has('restaurante_id') && request('restaurante_id') === '') ? 'bg-orange-600 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }} transition-colors">
            <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
            Sem Restaurante
        </a>
    </div>

    <!-- Filtros Avançados -->
    <form method="GET" action="{{ route('admin.users.index') }}" class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-100 dark:border-gray-700 p-4 mb-6" x-data="filterManager()">
        <div class="flex flex-wrap items-center gap-3">
            <!-- Busca em Tempo Real -->
            <div class="relative flex-1 min-w-[200px]" x-data="liveSearch()">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text"
                       name="search"
                       placeholder="Busca em tempo real..."
                       value="{{ request('search') }}"
                       @input.debounce.300ms="performSearch($event.target.value)"
                       class="pl-10 w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-1 focus:ring-red-500 focus:border-red-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400">

                <!-- Spinner de Loading -->
                <div x-show="loading" class="absolute right-3 top-1/2 -translate-y-1/2">
                    <svg class="animate-spin h-4 w-4 text-red-600" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>

                <!-- Resultados da Busca -->
                <div x-show="results.length > 0"
                     x-cloak
                     @click.away="results = []"
                     class="absolute z-50 w-full mt-1 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-lg max-h-96 overflow-y-auto">
                    <template x-for="user in results" :key="user.id">
                        <a :href="`{{ route('admin.users.index') }}?search=${user.email}`"
                           class="flex items-center gap-3 px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700 border-b border-gray-100 dark:border-gray-700 last:border-0">
                            <div class="flex-shrink-0 h-10 w-10 rounded-full flex items-center justify-center"
                                 :class="user.is_active ? 'bg-red-100 dark:bg-red-900/30' : 'bg-gray-200 dark:bg-gray-700'">
                                <span class="text-sm font-semibold"
                                      :class="user.is_active ? 'text-red-600 dark:text-red-400' : 'text-gray-500 dark:text-gray-400'"
                                      x-text="user.avatar_initials"></span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 dark:text-gray-100" x-text="user.name"></p>
                                <p class="text-xs text-gray-500 dark:text-gray-400" x-text="user.email"></p>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-xs px-2 py-0.5 rounded-full"
                                      :class="user.role === 'admin' ? 'bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400' : 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400'"
                                      x-text="user.role === 'admin' ? 'Admin' : 'User'"></span>
                                <span x-show="!user.is_active" class="text-xs px-2 py-0.5 rounded-full bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400">Inativo</span>
                            </div>
                        </a>
                    </template>
                </div>
            </div>

            <select name="restaurante_id" class="px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-1 focus:ring-red-500 focus:border-red-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                <option value="">Restaurante</option>
                @foreach($restaurantes as $restaurante)
                    <option value="{{ $restaurante->id }}" @selected(request('restaurante_id') == $restaurante->id)>{{ $restaurante->nome }}</option>
                @endforeach
            </select>

            <select name="role" class="px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-1 focus:ring-red-500 focus:border-red-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                <option value="">Papel</option>
                <option value="admin" @selected(request('role') === 'admin')>Admin</option>
                <option value="user" @selected(request('role') === 'user')>Usuário</option>
            </select>

            <select name="status" class="px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-1 focus:ring-red-500 focus:border-red-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                <option value="">Status</option>
                <option value="ativo" @selected(request('status') === 'ativo')>Ativo</option>
                <option value="inativo" @selected(request('status') === 'inativo')>Inativo</option>
            </select>

            <select name="per_page" class="px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-1 focus:ring-red-500 focus:border-red-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                <option value="10" @selected(request('per_page') == 10)>10 por página</option>
                <option value="25" @selected(request('per_page') == 25)>25 por página</option>
                <option value="50" @selected(request('per_page') == 50)>50 por página</option>
                <option value="100" @selected(request('per_page') == 100)>100 por página</option>
            </select>

            <button type="button" @click="showAdvanced = !showAdvanced" class="p-2 text-sm text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors" title="Filtros Avançados">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                </svg>
            </button>

            <button type="submit" class="btn-sm">
                Filtrar
            </button>

            @if(request()->anyFilled(['search', 'restaurante_id', 'role', 'status']))
                <a href="{{ route('admin.users.index') }}" class="inline-flex items-center px-4 py-2 text-sm bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Limpar Todos
                </a>
            @endif

            <!-- Seletor de Visualização -->
            <div class="flex items-center border border-gray-300 dark:border-gray-600 rounded-lg overflow-hidden" x-data="{ viewMode: localStorage.getItem('users_view_mode') || 'table' }">
                <button type="button"
                        @click="viewMode = 'table'; localStorage.setItem('users_view_mode', 'table'); $dispatch('view-changed', 'table')"
                        :class="viewMode === 'table' ? 'bg-red-600 text-white' : 'bg-white dark:bg-gray-700 text-gray-600 dark:text-gray-400'"
                        class="px-3 py-2 text-sm transition-colors"
                        title="Visualização em Tabela">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                    </svg>
                </button>
                <button type="button"
                        @click="viewMode = 'grid'; localStorage.setItem('users_view_mode', 'grid'); $dispatch('view-changed', 'grid')"
                        :class="viewMode === 'grid' ? 'bg-red-600 text-white' : 'bg-white dark:bg-gray-700 text-gray-600 dark:text-gray-400'"
                        class="px-3 py-2 text-sm border-l border-gray-300 dark:border-gray-600 transition-colors"
                        title="Visualização em Grid">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Filtros Avançados -->
        <div x-show="showAdvanced" x-cloak x-transition class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                <div>
                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Data de Criação (De)</label>
                    <input type="date" name="created_from" value="{{ request('created_from') }}" class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-1 focus:ring-red-500 focus:border-red-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Data de Criação (Até)</label>
                    <input type="date" name="created_to" value="{{ request('created_to') }}" class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-1 focus:ring-red-500 focus:border-red-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Ordenar por</label>
                    <select name="sort" class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-1 focus:ring-red-500 focus:border-red-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                        <option value="newest">Mais Recentes</option>
                        <option value="oldest">Mais Antigos</option>
                        <option value="name_asc">Nome (A-Z)</option>
                        <option value="name_desc">Nome (Z-A)</option>
                    </select>
                </div>
            </div>
        </div>

    </form>

    <!-- Container Principal com Bulk Actions -->
    <div x-data="bulkActions()">
        <!-- Barra de Ações em Massa -->
        <div x-show="selectedUsers.length > 0"
             x-cloak
             x-transition
             class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4 mb-4 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <span class="text-sm font-medium text-red-900 dark:text-red-100">
                    <span x-text="selectedUsers.length"></span> usuário(s) selecionado(s)
                </span>
                <button @click="deselectAll()" class="text-xs text-red-700 dark:text-red-300 hover:text-red-900 dark:hover:text-red-100 underline">
                    Desmarcar todos
                </button>
            </div>
        <div class="flex items-center gap-2">
            <button @click="bulkAction('export')"
                    class="inline-flex items-center px-3 py-1.5 text-xs font-medium bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Exportar
            </button>
            <button @click="bulkAction('activate')"
                    class="inline-flex items-center px-3 py-1.5 text-xs font-medium bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Ativar
            </button>
            <button @click="bulkAction('deactivate')"
                    class="inline-flex items-center px-3 py-1.5 text-xs font-medium bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition-colors">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                </svg>
                Desativar
            </button>
            <button @click="bulkAction('delete')"
                    class="inline-flex items-center px-3 py-1.5 text-xs font-medium bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
                Deletar
            </button>
        </div>
        </div>

        <!-- Container de Visualização -->
        <div x-data="{ viewMode: localStorage.getItem('users_view_mode') || 'table' }"
             @view-changed.window="viewMode = $event.detail">

        <!-- Visualização em Tabela -->
        <div x-show="viewMode === 'table'" class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-900">
                    <tr>
                        <th class="px-4 py-4 w-12">
                            <input type="checkbox"
                                   @change="toggleAll($event.target.checked)"
                                   :checked="(() => {
                                       const pageIds = @json($users->pluck('id')->toArray());
                                       return pageIds.length > 0 && pageIds.every(id => selectedUsers.includes(id));
                                   })()"
                                   class="rounded border-gray-300 dark:border-gray-600 text-red-600 focus:ring-red-500 dark:bg-gray-700">
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Usuário</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Restaurante</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Papel</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Métricas</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($users as $user)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors {{ $user->trashed() ? 'bg-red-50/30 dark:bg-red-900/10' : '' }}" x-data="{ open: false, quickView: false }">
                            <td class="px-4 py-4">
                                <input type="checkbox"
                                       :checked="selectedUsers.includes({{ $user->id }})"
                                       @change="toggleUser({{ $user->id }})"
                                       class="rounded border-gray-300 dark:border-gray-600 text-red-600 focus:ring-red-500 dark:bg-gray-700">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <div class="h-10 w-10 rounded-full {{ $user->trashed() ? 'bg-gray-200 dark:bg-gray-700' : 'bg-red-100 dark:bg-red-900/30' }} flex items-center justify-center">
                                            <span class="{{ $user->trashed() ? 'text-gray-500 dark:text-gray-400' : 'text-red-600 dark:text-red-400' }} font-semibold text-sm">
                                                {{ strtoupper(substr($user->name, 0, 2)) }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="flex items-center gap-2">
                                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $user->name }}</div>
                                            @if($user->created_at >= now()->subDays(7))
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-400">
                                                    <svg class="w-3 h-3 mr-0.5" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                    </svg>
                                                    Novo
                                                </span>
                                            @endif
                                        </div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">{{ $user->email }}</div>
                                        <div class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">
                                            Criado {{ $user->created_at->diffForHumans() }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($user->restaurante)
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 text-gray-400 dark:text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                        </svg>
                                        <span class="text-sm text-gray-900 dark:text-gray-100">{{ $user->restaurante->nome }}</span>
                                    </div>
                                @else
                                    <span class="text-sm text-gray-400">—</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $user->isAdmin() ? 'bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-400' : 'bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-400' }}">
                                    {{ $user->isAdmin() ? 'Admin' : 'Usuário' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($user->trashed())
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-400">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M13.477 14.89A6 6 0 015.11 6.524l8.367 8.368zm1.414-1.414L6.524 5.11a6 6 0 018.367 8.367zM18 10a8 8 0 11-16 0 8 8 0 0116 0z" clip-rule="evenodd"/>
                                        </svg>
                                        Inativo
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-400">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        Ativo
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="space-y-1">
                                    <div class="flex items-center text-xs text-gray-600 dark:text-gray-400">
                                        <svg class="w-4 h-4 mr-1.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        ID: <span class="font-mono font-medium ml-1">#{{ $user->id }}</span>
                                    </div>
                                    <div class="flex items-center text-xs text-gray-600 dark:text-gray-400">
                                        <svg class="w-4 h-4 mr-1.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        {{ $user->created_at->format('d/m/Y') }}
                                    </div>
                                    @if($user->updated_at->diffInDays($user->created_at) > 0)
                                    <div class="flex items-center text-xs text-gray-600 dark:text-gray-400">
                                        <svg class="w-4 h-4 mr-1.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                        </svg>
                                        Atualizado {{ $user->updated_at->diffForHumans() }}
                                    </div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end gap-2">
                                    <!-- Botão Quick View -->
                                    <button @click="$dispatch('open-quick-view', { userId: {{ $user->id }} })"
                                            type="button"
                                            class="inline-flex items-center gap-1.5 px-3 py-2 border border-gray-300 dark:border-gray-600 text-gray-600 dark:text-gray-400 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 rounded-lg transition-colors text-sm font-medium"
                                            title="Ver detalhes completos e atividades recentes">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        <span class="hidden xl:inline">Ver</span>
                                    </button>

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
                                            @if (!$user->trashed())
                                                <a href="{{ route('admin.users.edit', $user) }}" class="group flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-gray-100">
                                                    <svg class="mr-3 h-4 w-4 text-gray-400 group-hover:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                    </svg>
                                                    Editar
                                                </a>
                                                <form method="POST" action="{{ route('admin.users.destroy', $user) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" onclick="return confirmDelete('Tem certeza que deseja desativar este usuário?')" class="group flex w-full items-center px-4 py-2 text-sm text-yellow-700 hover:bg-yellow-50">
                                                        <svg class="mr-3 h-4 w-4 text-yellow-400 group-hover:text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                                        </svg>
                                                        Desativar
                                                    </button>
                                                </form>
                                            @else
                                                <form method="POST" action="{{ route('admin.users.restore', $user) }}">
                                                    @csrf
                                                    <button type="submit" onclick="return confirmDelete('Deseja reativar este usuário?')" class="group flex w-full items-center px-4 py-2 text-sm text-green-700 hover:bg-green-50">
                                                        <svg class="mr-3 h-4 w-4 text-green-400 group-hover:text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                        </svg>
                                                        Reativar
                                                    </button>
                                                </form>
                                                <form method="POST" action="{{ route('admin.users.forceDelete', $user) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" onclick="return confirmDelete('Deletar permanentemente? Esta ação não pode ser desfeita!')" class="group flex w-full items-center px-4 py-2 text-sm text-red-700 hover:bg-red-50">
                                                        <svg class="mr-3 h-4 w-4 text-red-400 group-hover:text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                        </svg>
                                                        Deletar Permanente
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-24 h-24 bg-gradient-to-br from-red-50 to-red-100 dark:from-red-900/20 dark:to-red-800/20 rounded-full flex items-center justify-center mb-4">
                                        <svg class="w-12 h-12 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                        </svg>
                                    </div>
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-1">Nenhum usuário encontrado</h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-6 max-w-sm">
                                        @if(request()->anyFilled(['search', 'restaurante_id', 'role', 'status']))
                                            Nenhum usuário corresponde aos filtros aplicados. Tente ajustar os critérios de busca.
                                        @else
                                            Comece adicionando o primeiro usuário ao sistema.
                                        @endif
                                    </p>
                                    @if(request()->anyFilled(['search', 'restaurante_id', 'role', 'status']))
                                        <a href="{{ route('admin.users.index') }}" class="btn-secondary">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                            Limpar Filtros
                                        </a>
                                    @else
                                        <a href="{{ route('admin.users.create') }}" class="btn-primary">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                            </svg>
                                            Criar Primeiro Usuário
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
        <div x-show="viewMode === 'grid'" x-cloak>
            @if($users->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4" x-data="bulkActions()">
                @foreach ($users as $user)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-100 dark:border-gray-700 p-6 hover:shadow-md transition-shadow {{ $user->trashed() ? 'bg-red-50/30 dark:bg-red-900/10' : '' }}">
                    <!-- Checkbox e Badge -->
                    <div class="flex items-start justify-between mb-4">
                        <input type="checkbox"
                               :checked="selectedUsers.includes({{ $user->id }})"
                               @change="toggleUser({{ $user->id }})"
                               class="rounded border-gray-300 dark:border-gray-600 text-red-600 focus:ring-red-500 dark:bg-gray-700 mt-1">
                        <div class="flex gap-1">
                            @if($user->created_at >= now()->subDays(7))
                            <span class="px-2 py-0.5 text-xs font-medium bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 rounded">Novo</span>
                            @endif
                        </div>
                    </div>

                    <!-- Avatar e Nome -->
                    <div class="text-center mb-4">
                        <div class="mx-auto h-20 w-20 rounded-full {{ $user->trashed() ? 'bg-gray-200 dark:bg-gray-700' : 'bg-red-100 dark:bg-red-900/30' }} flex items-center justify-center mb-3">
                            <span class="{{ $user->trashed() ? 'text-gray-500 dark:text-gray-400' : 'text-red-600 dark:text-red-400' }} font-semibold text-2xl">
                                {{ strtoupper(substr($user->name, 0, 2)) }}
                            </span>
                        </div>
                        <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100">{{ $user->name }}</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 truncate">{{ $user->email }}</p>
                    </div>

                    <!-- Informações -->
                    <div class="space-y-2 mb-4">
                        @if($user->restaurante)
                        <div class="flex items-center text-xs text-gray-600 dark:text-gray-400">
                            <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                            <span class="truncate">{{ $user->restaurante->nome }}</span>
                        </div>
                        @endif
                        <div class="flex items-center justify-between">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $user->isAdmin() ? 'bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-400' : 'bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-400' }}">
                                {{ $user->isAdmin() ? 'Admin' : 'Usuário' }}
                            </span>
                            @if($user->trashed())
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-400">
                                Inativo
                            </span>
                            @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-400">
                                Ativo
                            </span>
                            @endif
                        </div>
                        <div class="text-xs text-gray-500 dark:text-gray-400 pt-2 border-t border-gray-100 dark:border-gray-700">
                            Criado {{ $user->created_at->diffForHumans() }}
                        </div>
                    </div>

                    <!-- Ações -->
                    <div class="flex gap-2">
                        <button @click="openQuickView({{ $user->id }})"
                                class="flex-1 inline-flex items-center justify-center px-3 py-2 text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            Ver
                        </button>
                        <a href="{{ route('admin.users.edit', $user) }}"
                           class="flex-1 inline-flex items-center justify-center px-3 py-2 text-xs font-medium bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Editar
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-16 text-center">
                <div class="w-24 h-24 bg-gradient-to-br from-red-50 to-red-100 dark:from-red-900/20 dark:to-red-800/20 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-12 h-12 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-1">Nenhum usuário encontrado</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">
                    @if(request()->anyFilled(['search', 'restaurante_id', 'role', 'status']))
                        Nenhum usuário corresponde aos filtros aplicados.
                    @else
                        Comece adicionando o primeiro usuário ao sistema.
                    @endif
                </p>
            </div>
            @endif
        </div>
    </div>

    <!-- Paginação -->
    <div class="mt-6">
        {{ $users->links() }}
    </div>

    <!-- Estatísticas -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mt-8">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-4 border border-gray-100 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Total</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-gray-100 mt-1">{{ $totalUsers }}</p>
                </div>
                <div class="p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-4 border border-gray-100 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Ativos</p>
                    <p class="text-2xl font-bold text-green-600 dark:text-green-400 mt-1">{{ $totalActive }}</p>
                </div>
                <div class="p-3 bg-green-50 dark:bg-green-900/20 rounded-lg">
                    <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-4 border border-gray-100 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Inativos</p>
                    <p class="text-2xl font-bold text-red-600 dark:text-red-400 mt-1">{{ $totalInactive }}</p>
                </div>
                <div class="p-3 bg-red-50 dark:bg-red-900/20 rounded-lg">
                    <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-4 border border-gray-100 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Admins</p>
                    <p class="text-2xl font-bold text-red-600 dark:text-red-400 mt-1">{{ $totalAdmins }}</p>
                </div>
                <div class="p-3 bg-red-50 dark:bg-red-900/20 rounded-lg">
                    <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-4 border border-gray-100 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Novos (7d)</p>
                    <p class="text-2xl font-bold text-purple-600 dark:text-purple-400 mt-1">{{ $newUsersWeek }}</p>
                </div>
                <div class="p-3 bg-purple-50 dark:bg-purple-900/20 rounded-lg">
                    <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"/>
                    </svg>
                </div>
            </div>
        </div>
        </div>
    </div>
@endsection

<!-- Modal Quick View -->
<div x-data="quickViewModal()"
     x-show="isOpen"
     x-cloak
     @keydown.escape.window="closeModal()"
     @open-quick-view.window="loadUserData($event.detail.userId)"
     class="fixed inset-0 z-50 overflow-y-auto"
     style="display: none;">
    <!-- Overlay -->
    <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity"
         @click="closeModal()"></div>

    <!-- Modal -->
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="relative bg-white dark:bg-gray-800 rounded-xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto"
             @click.away="closeModal()">
            <!-- Header -->
            <div class="sticky top-0 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 px-6 py-4 flex items-center justify-between z-10">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Detalhes do Usuário</h3>
                <button @click="closeModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <!-- Loading -->
            <div x-show="loading" class="p-12 text-center">
                <svg class="animate-spin h-8 w-8 text-red-600 mx-auto" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">Carregando...</p>
            </div>

            <!-- Content -->
            <div x-show="!loading && userData" class="p-6">
                <!-- Avatar e Info Principal -->
                <div class="flex items-start gap-6 mb-6 pb-6 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex-shrink-0">
                        <div class="h-24 w-24 rounded-full flex items-center justify-center text-3xl font-bold"
                             :class="userData?.is_active ? 'bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400' : 'bg-gray-200 dark:bg-gray-700 text-gray-500 dark:text-gray-400'"
                             x-text="userData?.avatar_initials"></div>
                    </div>
                    <div class="flex-1">
                        <h4 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-1" x-text="userData?.name"></h4>
                        <p class="text-gray-600 dark:text-gray-400 mb-3" x-text="userData?.email"></p>
                        <div class="flex flex-wrap gap-2">
                            <span class="px-3 py-1 rounded-full text-sm font-medium"
                                  :class="userData?.role === 'admin' ? 'bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400' : 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400'"
                                  x-text="userData?.role === 'admin' ? 'Administrador' : 'Usuário'"></span>
                            <span class="px-3 py-1 rounded-full text-sm font-medium"
                                  :class="userData?.is_active ? 'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400' : 'bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400'"
                                  x-text="userData?.is_active ? 'Ativo' : 'Inativo'"></span>
                        </div>
                    </div>
                </div>

                <!-- Informações Detalhadas -->
                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div>
                        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase mb-1">ID</label>
                        <p class="text-sm font-mono font-semibold text-gray-900 dark:text-gray-100" x-text="'#' + userData?.id"></p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase mb-1">Restaurante</label>
                        <p class="text-sm text-gray-900 dark:text-gray-100" x-text="userData?.restaurante || 'N/A'"></p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase mb-1">Criado em</label>
                        <p class="text-sm text-gray-900 dark:text-gray-100" x-text="userData?.created_at"></p>
                        <p class="text-xs text-gray-500 dark:text-gray-400" x-text="userData?.created_diff"></p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase mb-1">Atualizado em</label>
                        <p class="text-sm text-gray-900 dark:text-gray-100" x-text="userData?.updated_at"></p>
                    </div>
                </div>

                <!-- Atividades Recentes -->
                <div>
                    <h5 class="text-sm font-semibold text-gray-900 dark:text-gray-100 mb-3">Atividades Recentes</h5>
                    <div class="space-y-2">
                        <template x-for="log in recentLogs" :key="log.created_at">
                            <div class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                <div class="flex-shrink-0 h-8 w-8 bg-red-100 dark:bg-red-900/30 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm text-gray-900 dark:text-gray-100" x-text="log.description"></p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400" x-text="log.created_at"></p>
                                </div>
                            </div>
                        </template>
                        <div x-show="recentLogs.length === 0" class="text-sm text-gray-500 dark:text-gray-400 text-center py-4">
                            Nenhuma atividade registrada
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="sticky bottom-0 bg-gray-50 dark:bg-gray-900 border-t border-gray-200 dark:border-gray-700 px-6 py-4 flex justify-end gap-3">
                <button @click="closeModal()" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                    Fechar
                </button>
                <a :href="`{{ route('admin.users.index') }}/${userData?.id}/edit`" class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 transition-colors">
                    Editar Usuário
                </a>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        // Busca em Tempo Real
        Alpine.data('liveSearch', () => ({
            loading: false,
            results: [],

            async performSearch(query) {
                if (query.length < 2) {
                    this.results = [];
                    return;
                }

                this.loading = true;

                try {
                    const response = await fetch(`{{ route('admin.users.live-search') }}?q=${encodeURIComponent(query)}&limit=5`);
                    const data = await response.json();
                    this.results = data;
                } catch (error) {
                    console.error('Erro na busca:', error);
                } finally {
                    this.loading = false;
                }
            }
        }));

        // Ações em Massa
        Alpine.data('bulkActions', () => ({
            selectedUsers: [],

            toggleUser(userId) {
                const index = this.selectedUsers.indexOf(userId);
                if (index > -1) {
                    this.selectedUsers.splice(index, 1);
                } else {
                    this.selectedUsers.push(userId);
                }
            },

            toggleAll(checked) {
                const allIds = @json($users->pluck('id')->toArray());
                if (checked) {
                    // Adiciona todos os IDs que ainda não estão selecionados
                    allIds.forEach(id => {
                        if (!this.selectedUsers.includes(id)) {
                            this.selectedUsers.push(id);
                        }
                    });
                } else {
                    // Remove apenas os IDs da página atual
                    this.selectedUsers = this.selectedUsers.filter(id => !allIds.includes(id));
                }
            },

            deselectAll() {
                this.selectedUsers = [];
            },

            async bulkAction(action) {
                if (this.selectedUsers.length === 0) {
                    alert('Selecione pelo menos um usuário');
                    return;
                }

                // Exportação não precisa de confirmação
                if (action === 'export') {
                    const ids = this.selectedUsers.join(',');
                    window.location.href = `{{ route('admin.users.export') }}?ids=${ids}`;
                    return;
                }

                const confirmMessages = {
                    'activate': 'Deseja ativar os usuários selecionados?',
                    'deactivate': 'Deseja desativar os usuários selecionados?',
                    'delete': 'Deseja deletar permanentemente os usuários selecionados? Esta ação não pode ser desfeita!'
                };

                if (!confirm(confirmMessages[action])) {
                    return;
                }

                try {
                    const response = await fetch('{{ route('admin.users.bulk-action') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            action: action,
                            user_ids: this.selectedUsers
                        })
                    });

                    const data = await response.json();

                    if (data.success) {
                        alert(data.message);
                        window.location.reload();
                    }
                } catch (error) {
                    console.error('Erro:', error);
                    alert('Erro ao processar ação em massa');
                }
            }
        }));

        // Quick View Modal
        window.quickViewModal = Alpine.data('quickViewModal', () => ({
            isOpen: false,
            loading: false,
            userData: null,
            recentLogs: [],

            async loadUserData(userId) {
                console.log('Carregando dados do usuário:', userId);
                this.isOpen = true;
                this.loading = true;
                this.userData = null;
                this.recentLogs = [];

                try {
                    const url = `/admin/users/${userId}/quick-view`;
                    console.log('Fazendo requisição para:', url);

                    const response = await fetch(url, {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });

                    console.log('Status da resposta:', response.status);

                    if (!response.ok) {
                        const errorText = await response.text();
                        console.error('Resposta de erro:', errorText);
                        throw new Error(`Erro ${response.status}: ${response.statusText}`);
                    }

                    const data = await response.json();
                    console.log('Dados recebidos:', data);

                    this.userData = data.user;
                    this.recentLogs = data.recent_logs || [];
                } catch (error) {
                    console.error('Erro completo:', error);
                    alert(`Erro ao carregar informações do usuário: ${error.message}`);
                    this.closeModal();
                } finally {
                    this.loading = false;
                }
            },

            closeModal() {
                this.isOpen = false;
                setTimeout(() => {
                    this.userData = null;
                    this.recentLogs = [];
                }, 300);
            }
        }));

        Alpine.data('filterManager', () => ({
            showAdvanced: false
        }));
    });
</script>

<!-- Paginação com Info -->
@if($users->hasPages())
    <div class="mt-6 flex flex-col sm:flex-row items-center justify-between gap-4">
        <div class="text-sm text-gray-700 dark:text-gray-300">
            Exibindo <span class="font-semibold">{{ $users->firstItem() }}</span>
            a <span class="font-semibold">{{ $users->lastItem() }}</span>
            de <span class="font-semibold">{{ $users->total() }}</span> resultados
        </div>
        <div>
            {{ $users->links() }}
        </div>
    </div>
@endif
@endsection
