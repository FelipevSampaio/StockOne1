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
        <a href="{{ route('admin.restaurantes.create') }}" class="btn-primary">
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

            <button type="submit" class="btn-sm">
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
         x-transition
         class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4 mb-4 flex items-center justify-between">
        <div class="flex items-center gap-4">
            <span class="text-sm font-medium text-red-900 dark:text-red-100">
                <span x-text="selectedRestaurantes.length"></span> restaurante(s) selecionado(s)
            </span>
            <button @click="deselectAll()" class="text-xs text-red-700 dark:text-red-300 hover:text-red-900 dark:hover:text-red-100 underline">
                Desmarcar todos
            </button>
        </div>
        <div class="flex items-center gap-2">
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
            <button @click="bulkAction('export')"
                    class="inline-flex items-center px-3 py-1.5 text-xs font-medium bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Exportar
            </button>
            <button @click="bulkAction('delete')"
                    class="inline-flex items-center px-3 py-1.5 text-xs font-medium bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
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
                                   class="rounded border-gray-300 dark:border-gray-600 text-red-600 focus:ring-red-500 dark:bg-gray-700 dark:checked:bg-red-600">
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Restaurante</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Contato</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($restaurantes as $restaurante)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors"
                            :class="{ 'bg-red-50 dark:bg-red-900/10': selectedRestaurantes.includes({{ $restaurante->id }}) }"
                            x-data="{ open: false }">
                            <td class="px-6 py-4">
                                <input type="checkbox"
                                       :checked="selectedRestaurantes.includes({{ $restaurante->id }})"
                                       @change="toggleRestaurante({{ $restaurante->id }})"
                                       class="rounded border-gray-300 dark:border-gray-600 text-red-600 focus:ring-red-500 dark:bg-gray-700 dark:checked:bg-red-600">
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <div class="h-10 w-10 rounded-full bg-red-100 dark:bg-red-900/30 flex items-center justify-center">
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
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $restaurante->status === 'ativo' ? 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-400' : 'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300' }}">
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
                                    <!-- Botão Editar -->
                                    <a href="{{ route('admin.restaurantes.edit', $restaurante) }}"
                                       class="inline-flex items-center gap-1.5 px-3 py-2 text-sm font-medium text-blue-700 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/30 hover:bg-blue-100 dark:hover:bg-blue-900/50 rounded-lg transition-colors border border-blue-200 dark:border-blue-800"
                                       title="Editar restaurante">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                        <span class="hidden xl:inline">Editar</span>
                                    </a>

                                    <!-- Botão Toggle Status -->
                                    <form method="POST" 
                                          action="{{ route('admin.restaurantes.update', $restaurante) }}"
                                          class="inline-block"
                                          onsubmit="return confirm('Deseja alterar o status deste restaurante?')">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="nome" value="{{ $restaurante->nome }}">
                                        <input type="hidden" name="cnpj" value="{{ $restaurante->cnpj }}">
                                        <input type="hidden" name="email" value="{{ $restaurante->email }}">
                                        <input type="hidden" name="telefone" value="{{ $restaurante->telefone }}">
                                        <input type="hidden" name="endereco" value="{{ $restaurante->endereco }}">
                                        <input type="hidden" name="status" value="{{ $restaurante->status === 'ativo' ? 'inativo' : 'ativo' }}">
                                        
                                        <button type="submit"
                                                class="inline-flex items-center gap-1.5 px-3 py-2 text-sm font-medium {{ $restaurante->status === 'ativo' ? 'text-yellow-700 dark:text-yellow-400 bg-yellow-50 dark:bg-yellow-900/30 hover:bg-yellow-100 dark:hover:bg-yellow-900/50 border-yellow-200 dark:border-yellow-800' : 'text-green-700 dark:text-green-400 bg-green-50 dark:bg-green-900/30 hover:bg-green-100 dark:hover:bg-green-900/50 border-green-200 dark:border-green-800' }} rounded-lg transition-colors border"
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
                                    </form>

                                    <!-- Botão Deletar -->
                                    <form method="POST" 
                                          action="{{ route('admin.restaurantes.destroy', $restaurante) }}"
                                          class="inline-block"
                                          onsubmit="return confirm('⚠️ ATENÇÃO!\n\nDeseja realmente excluir o restaurante {{ $restaurante->nome }}?\n\nEsta ação irá:\n- Remover o restaurante permanentemente\n- Desvincular todos os usuários associados\n- Esta ação NÃO pode ser desfeita\n\nTem certeza que deseja continuar?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="inline-flex items-center gap-1.5 px-3 py-2 text-sm font-medium text-red-700 dark:text-red-400 bg-red-50 dark:bg-red-900/30 hover:bg-red-100 dark:hover:bg-red-900/50 rounded-lg transition-colors border border-red-200 dark:border-red-800"
                                                title="Excluir restaurante">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                            <span class="hidden xl:inline">Excluir</span>
                                        </button>
                                    </form>
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
        // Componente de Ações em Massa
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
                    alert('Selecione pelo menos um restaurante');
                    return;
                }

                // Confirmações específicas
                let confirmMessage = '';
                switch(action) {
                    case 'activate':
                        confirmMessage = `Deseja ativar ${this.selectedRestaurantes.length} restaurante(s)?`;
                        break;
                    case 'deactivate':
                        confirmMessage = `Deseja desativar ${this.selectedRestaurantes.length} restaurante(s)?`;
                        break;
                    case 'delete':
                        confirmMessage = `⚠️ ATENÇÃO!\n\nDeseja realmente excluir ${this.selectedRestaurantes.length} restaurante(s)?\n\nEsta ação:\n- Removerá os restaurantes permanentemente\n- Desvinculará todos os usuários associados\n- NÃO pode ser desfeita\n\nTem certeza?`;
                        break;
                    case 'export':
                        confirmMessage = `Deseja exportar ${this.selectedRestaurantes.length} restaurante(s) para CSV?`;
                        break;
                }

                if (!confirm(confirmMessage)) {
                    return;
                }

                // Mostrar loading
                const toast = this.showToast('Processando...', 'info');

                try {
                    // Para exportação, fazer download direto
                    if (action === 'export') {
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = '{{ route("admin.restaurantes.bulk-action") }}';
                        
                        // CSRF Token
                        const csrfInput = document.createElement('input');
                        csrfInput.type = 'hidden';
                        csrfInput.name = '_token';
                        csrfInput.value = '{{ csrf_token() }}';
                        form.appendChild(csrfInput);
                        
                        // Action
                        const actionInput = document.createElement('input');
                        actionInput.type = 'hidden';
                        actionInput.name = 'action';
                        actionInput.value = action;
                        form.appendChild(actionInput);
                        
                        // IDs
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
                        
                        toast.remove();
                        this.showToast('Download iniciado!', 'success');
                        return;
                    }

                    // Para outras ações, fazer request AJAX
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

                    toast.remove();

                    if (data.success) {
                        this.showToast(data.message, 'success');
                        // Recarregar página após 1 segundo
                        setTimeout(() => window.location.reload(), 1000);
                    } else {
                        this.showToast(data.message, 'error');
                    }

                } catch (error) {
                    toast.remove();
                    console.error('Erro:', error);
                    this.showToast('Erro ao processar ação', 'error');
                }
            },

            showToast(message, type = 'info') {
                const colors = {
                    success: 'bg-green-500',
                    error: 'bg-red-500',
                    info: 'bg-blue-500'
                };

                const toast = document.createElement('div');
                toast.className = `fixed top-4 right-4 ${colors[type]} text-white px-6 py-3 rounded-lg shadow-lg z-50 transition-opacity duration-300`;
                toast.textContent = message;
                document.body.appendChild(toast);

                if (type !== 'info') {
                    setTimeout(() => {
                        toast.style.opacity = '0';
                        setTimeout(() => toast.remove(), 300);
                    }, 3000);
                }

                return toast;
            }
        }));
    });
</script>
@endsection
