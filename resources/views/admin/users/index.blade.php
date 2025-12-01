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

    <!-- Filtros Avançados -->
    <form method="GET" action="{{ route('admin.users.index') }}" class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-100 dark:border-gray-700 p-4 mb-6" x-data="filterManager()">
        <div class="flex flex-wrap items-center gap-3">
            <div class="relative flex-1 min-w-[200px]">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" name="search" placeholder="Buscar..." value="{{ request('search') }}" class="pl-10 w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-1 focus:ring-red-500 focus:border-red-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400">
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

            <button type="button" @click="saveCurrentFilter()" class="p-2 text-sm text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors" title="Salvar Filtro">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/>
                </svg>
            </button>
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

        <!-- Filtros Salvos -->
        <div x-show="savedFilters.length > 0" x-cloak class="mt-3 flex flex-wrap gap-2">
            <span class="text-xs font-medium text-gray-500 dark:text-gray-400">Filtros salvos:</span>
            <template x-for="(filter, index) in savedFilters" :key="index">
                <button type="button" @click="applyFilter(filter)" class="inline-flex items-center px-2 py-1 text-xs bg-red-50 dark:bg-red-900/20 text-red-700 dark:text-red-400 rounded hover:bg-red-100 dark:hover:bg-red-900/30 transition-colors">
                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/>
                    </svg>
                    <span x-text="filter.name"></span>
                </button>
            </template>
        </div>
    </form>

    <!-- Tabela -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-900">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Usuário</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Restaurante</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Papel</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700" x-data="{}">
                    @forelse ($users as $user)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors {{ $user->trashed() ? 'bg-red-50/30 dark:bg-red-900/10' : '' }}" x-data="{ open: false }">
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
                                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $user->name }}</div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">{{ $user->email }}</div>
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
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
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
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-16 text-center">
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

    <!-- Paginação -->
    <div class="mt-6">
        {{ $users->links() }}
    </div>
@endsection

@section('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('filterManager', () => ({
            showAdvanced: false,
            savedFilters: [],

            init() {
                // Carregar filtros salvos do localStorage
                const saved = localStorage.getItem('user_filters');
                if (saved) {
                    this.savedFilters = JSON.parse(saved);
                }
            },

            saveCurrentFilter() {
                const form = this.$el;
                const formData = new FormData(form);
                const filterData = {};
                let filterName = '';

                // Extrair dados do formulário
                for (let [key, value] of formData.entries()) {
                    if (value) {
                        filterData[key] = value;
                        if (!filterName && key !== '_token') {
                            filterName += value.substring(0, 15);
                        }
                    }
                }

                if (Object.keys(filterData).length === 0) {
                    alert('Configure pelo menos um filtro antes de salvar');
                    return;
                }

                // Pedir nome do filtro
                const name = prompt('Nome do filtro:', filterName || 'Meu Filtro');
                if (!name) return;

                // Adicionar à lista
                this.savedFilters.push({
                    name: name,
                    data: filterData
                });

                // Salvar no localStorage
                localStorage.setItem('user_filters', JSON.stringify(this.savedFilters));

                // Feedback
                const toast = document.createElement('div');
                toast.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50';
                toast.textContent = 'Filtro salvo com sucesso!';
                document.body.appendChild(toast);
                setTimeout(() => toast.remove(), 3000);
            },

            applyFilter(filter) {
                const form = this.$el;

                // Aplicar cada campo do filtro salvo
                for (let [key, value] of Object.entries(filter.data)) {
                    const input = form.querySelector(`[name="${key}"]`);
                    if (input) {
                        input.value = value;
                    }
                }

                // Submeter o formulário
                form.submit();
            }
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
