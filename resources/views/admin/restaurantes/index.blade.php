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

            <select name="per_page" class="px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-1 focus:ring-red-500 focus:border-red-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                <option value="10" @selected(request('per_page') == 10)>10 por página</option>
                <option value="25" @selected(request('per_page') == 25)>25 por página</option>
                <option value="50" @selected(request('per_page') == 50)>50 por página</option>
                <option value="100" @selected(request('per_page') == 100)>100 por página</option>
            </select>

            <button type="submit" class="btn-sm btn-ripple">
                Filtrar
            </button>

            @if(request()->anyFilled(['search', 'status']))
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

    <!-- Tabela -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden" x-data="bulkActions()">
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
                                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $restaurante->nome }}</div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">{{ $restaurante->cnpj }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900 dark:text-gray-100">{{ $restaurante->email }}</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">{{ $restaurante->telefone }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
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
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <!-- Botão Ver Detalhes -->
                                    <button @click="quickView({{ $restaurante->id }})"
                                            class="btn-ripple inline-flex items-center gap-1.5 px-3 py-2 text-sm font-medium text-purple-700 dark:text-purple-400 bg-purple-50 dark:bg-purple-900/30 hover:bg-purple-100 dark:hover:bg-purple-900/50 rounded-lg transition-all duration-200 border border-purple-200 dark:border-purple-800 hover:scale-105"
                                            title="Ver detalhes">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        <span class="hidden xl:inline">Ver</span>
                                    </button>

                                    <!-- Botão Editar -->
                                    <a href="{{ route('admin.restaurantes.edit', $restaurante) }}"
                                       class="btn-ripple inline-flex items-center gap-1.5 px-3 py-2 text-sm font-medium text-blue-700 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/30 hover:bg-blue-100 dark:hover:bg-blue-900/50 rounded-lg transition-all duration-200 border border-blue-200 dark:border-blue-800 hover:scale-105"
                                       title="Editar restaurante">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                        <span class="hidden xl:inline">Editar</span>
                                    </a>

                                    <!-- Botão Toggle Status -->
                                    <button @click="toggleStatus({{ $restaurante->id }}, '{{ $restaurante->status }}')"
                                            class="btn-ripple inline-flex items-center gap-1.5 px-3 py-2 text-sm font-medium {{ $restaurante->status === 'ativo' ? 'text-yellow-700 dark:text-yellow-400 bg-yellow-50 dark:bg-yellow-900/30 hover:bg-yellow-100 dark:hover:bg-yellow-900/50 border-yellow-200 dark:border-yellow-800' : 'text-green-700 dark:text-green-400 bg-green-50 dark:bg-green-900/30 hover:bg-green-100 dark:hover:bg-green-900/50 border-green-200 dark:border-green-800' }} rounded-lg transition-all duration-200 border hover:scale-105"
                                            title="{{ $restaurante->status === 'ativo' ? 'Desativar' : 'Ativar' }} restaurante">
                                        @if($restaurante->status === 'ativo')
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                            </svg>
                                            <span class="hidden xl:inline">Desativar</span>
                                        @else
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            <span class="hidden xl:inline">Ativar</span>
                                        @endif
                                    </button>

                                    <!-- Botão Deletar -->
                                    <button @click="deleteRestaurante({{ $restaurante->id }}, '{{ $restaurante->nome }}')"
                                            class="btn-ripple inline-flex items-center gap-1.5 px-3 py-2 text-sm font-medium text-red-700 dark:text-red-400 bg-red-50 dark:bg-red-900/30 hover:bg-red-100 dark:hover:bg-red-900/50 rounded-lg transition-all duration-200 border border-red-200 dark:border-red-800 hover:scale-105"
                                            title="Excluir restaurante">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                        <span class="hidden xl:inline">Excluir</span>
                                    </button>
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
@endsection

@section('scripts')
<script>
    document.addEventListener('alpine:init', () => {
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
            },

            async bulkAction(action) {
                if (this.selectedRestaurantes.length === 0) {
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
                        confirmMessage = `Deseja ativar ${this.selectedRestaurantes.length} restaurante(s)?`;
                        confirmType = 'info';
                        break;
                    case 'deactivate':
                        confirmTitle = 'Desativar Restaurantes';
                        confirmMessage = `Deseja desativar ${this.selectedRestaurantes.length} restaurante(s)?`;
                        confirmType = 'warning';
                        break;
                    case 'delete':
                        confirmTitle = 'Excluir Restaurantes';
                        confirmMessage = `Deseja realmente excluir ${this.selectedRestaurantes.length} restaurante(s)? Esta ação NÃO pode ser desfeita.`;
                        confirmType = 'danger';
                        break;
                    case 'export':
                        confirmTitle = 'Exportar Restaurantes';
                        confirmMessage = `Deseja exportar ${this.selectedRestaurantes.length} restaurante(s) para CSV?`;
                        confirmType = 'info';
                        break;
                }

                const confirmed = await new Promise(resolve => {
                    window.dispatchEvent(new CustomEvent('show-confirm', {
                        detail: {
                            title: confirmTitle,
                            message: confirmMessage,
                            type: confirmType,
                            onConfirm: () => resolve(true),
                            onCancel: () => resolve(false)
                        }
                    }));
                });

                if (!confirmed) return;

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

                        this.selectedRestaurantes.forEach(id => {
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
                            ids: this.selectedRestaurantes
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

            async toggleStatus(id, currentStatus) {
                const newStatus = currentStatus === 'ativo' ? 'inativo' : 'ativo';
                const action = newStatus === 'ativo' ? 'ativar' : 'desativar';

                const confirmed = await new Promise(resolve => {
                    window.dispatchEvent(new CustomEvent('show-confirm', {
                        detail: {
                            title: `${action.charAt(0).toUpperCase() + action.slice(1)} Restaurante`,
                            message: `Deseja ${action} este restaurante?`,
                            type: 'info',
                            onConfirm: () => resolve(true),
                            onCancel: () => resolve(false)
                        }
                    }));
                });

                if (!confirmed) return;

                window.dispatchEvent(new CustomEvent('show-loading'));

                try {
                    const response = await fetch(`/admin/restaurantes/${id}/toggle-status`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
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
                        detail: { message: 'Erro ao alterar status', type: 'error' }
                    }));
                }
            },

            async deleteRestaurante(id, nome) {
                const confirmed = await new Promise(resolve => {
                    window.dispatchEvent(new CustomEvent('show-confirm', {
                        detail: {
                            title: 'Excluir Restaurante',
                            message: `Deseja realmente excluir o restaurante "${nome}"? Esta ação NÃO pode ser desfeita e irá desvincular todos os usuários associados.`,
                            type: 'danger',
                            onConfirm: () => resolve(true),
                            onCancel: () => resolve(false)
                        }
                    }));
                });

                if (!confirmed) return;

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
        }));
    });
</script>
@endsection
