@extends('layouts.app')

@section('title', 'Adicionar à fila de produção')
@section('subtitle', 'Organize a produção da cozinha por prioridade')

@section('content')
    <div class="max-w-4xl mx-auto" x-data="filaForm()">
        <form action="{{ route('fila-producao.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 md:p-8">
                <div class="mb-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Informações do Item</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Selecione o item do pedido para adicionar à fila de produção</p>
                </div>

                <div class="grid gap-6 md:grid-cols-2">
                    <!-- Item do Pedido -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            Item do Pedido <span class="text-red-500">*</span>
                        </label>
                        <div class="relative mb-2">
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400 dark:text-gray-500 pointer-events-none z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <input type="text" 
                                   x-model="searchItem"
                                   @input="filterItems()"
                                   placeholder="Buscar por pedido ou item..."
                                   class="pl-10 pr-4 w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 py-3 text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors">
                            <select name="pedido_item_id" 
                                    required
                                    x-model="selectedItem"
                                    @change="updateItemInfo()"
                                    class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 px-4 py-3 text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors">
                                <option value="">Selecione um item do pedido...</option>
                                @foreach ($pedidoItens as $id => $label)
                                    <option value="{{ $id }}" 
                                            data-label="{{ $label }}"
                                            @if($pedidoId && str_contains($label, "#{$pedidoId}"))
                                                data-pedido="{{ $pedidoId }}"
                                                class="font-semibold"
                                            @endif>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @error('pedido_item_id')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                        <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">O pedido será vinculado automaticamente ao item selecionado.</p>
                    </div>

                    <!-- Status da Produção -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            Status Inicial <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <select name="status_producao" 
                                    required
                                    x-model="statusProducao"
                                    class="pl-10 w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 px-4 py-3 text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors">
                                <option value="pendente" @selected(old('status_producao', 'pendente') === 'pendente')>Pendente</option>
                                <option value="em_producao" @selected(old('status_producao') === 'em_producao')>Em Produção</option>
                                <option value="pronto" @selected(old('status_producao') === 'pronto')>Pronto</option>
                            </select>
                        </div>
                        @error('status_producao')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Prioridade -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            Prioridade
                            <span class="text-xs font-normal text-gray-500 dark:text-gray-400">(0-10, maior = mais urgente)</span>
                        </label>
                        <div class="relative">
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
                            </svg>
                            <input type="number" 
                                   name="prioridade" 
                                   min="0" 
                                   max="10"
                                   x-model="prioridade"
                                   value="{{ old('prioridade', 0) }}" 
                                   class="pl-10 w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 px-4 py-3 text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors">
                        </div>
                        <div class="mt-2 flex items-center gap-2 flex-wrap">
                            <button type="button" 
                                    @click="prioridade = 8"
                                    class="px-3 py-1.5 text-xs font-medium bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 rounded-lg hover:bg-red-200 dark:hover:bg-red-900/50 transition-colors">
                                🔴 Urgente (8)
                            </button>
                            <button type="button" 
                                    @click="prioridade = 5"
                                    class="px-3 py-1.5 text-xs font-medium bg-orange-100 dark:bg-orange-900/30 text-orange-700 dark:text-orange-400 rounded-lg hover:bg-orange-200 dark:hover:bg-orange-900/50 transition-colors">
                                🟠 Alta (5)
                            </button>
                            <button type="button" 
                                    @click="prioridade = 3"
                                    class="px-3 py-1.5 text-xs font-medium bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400 rounded-lg hover:bg-yellow-200 dark:hover:bg-yellow-900/50 transition-colors">
                                🟡 Média (3)
                            </button>
                            <button type="button" 
                                    @click="prioridade = 1"
                                    class="px-3 py-1.5 text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                                ⚪ Baixa (1)
                            </button>
                        </div>
                        @error('prioridade')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Data/Hora Início -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            Início da Produção
                        </label>
                        <div class="relative">
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <input type="datetime-local" 
                                   name="data_hora_inicio" 
                                   x-model="dataHoraInicio"
                                   value="{{ old('data_hora_inicio', '') }}" 
                                   class="pl-10 w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 px-4 py-3 text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors">
                        </div>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                            @if(old('status_producao') === 'em_producao')
                                Será preenchido automaticamente se deixar em branco
                            @else
                                Deixe em branco para usar a data/hora atual ao iniciar
                            @endif
                        </p>
                        @error('data_hora_inicio')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Data/Hora Fim -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            Fim da Produção
                        </label>
                        <div class="relative">
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <input type="datetime-local" 
                                   name="data_hora_fim" 
                                   x-model="dataHoraFim"
                                   value="{{ old('data_hora_fim', '') }}" 
                                   class="pl-10 w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 px-4 py-3 text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors">
                        </div>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Será preenchido automaticamente ao marcar como pronto</p>
                        @error('data_hora_fim')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Preview do Item na Fila -->
            <div x-show="selectedItem" 
                 x-transition
                 class="bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-900 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center gap-2 mb-4">
                    <svg class="w-5 h-5 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Preview na Fila</h3>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-lg border-l-4 border-orange-500 p-4 shadow-sm">
                    <div class="flex items-start gap-3">
                        <div class="w-12 h-12 bg-orange-100 dark:bg-orange-900/30 rounded-lg flex flex-col items-center justify-center flex-shrink-0">
                            <span class="text-xs font-medium text-orange-600 dark:text-orange-400">PRIO</span>
                            <span class="text-lg font-bold text-orange-600 dark:text-orange-400" x-text="prioridade || 0"></span>
                        </div>
                        <div class="flex-1">
                            <h4 class="font-semibold text-gray-900 dark:text-white mb-1" x-text="getItemLabel()"></h4>
                            <div class="flex flex-wrap items-center gap-2 text-xs text-gray-500 dark:text-gray-400 mb-2">
                                <span class="inline-flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                    </svg>
                                    <span x-text="getStatusLabel(statusProducao)"></span>
                                </span>
                                <span>•</span>
                                <span x-text="dataHoraInicio ? new Date(dataHoraInicio).toLocaleString('pt-BR') : 'A definir'"></span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold"
                                      :class="{
                                          'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400': prioridade >= 8,
                                          'bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-400': prioridade >= 5 && prioridade < 8,
                                          'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400': prioridade >= 3 && prioridade < 5,
                                          'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300': prioridade < 3
                                      }">
                                    Prioridade: <span x-text="getPrioridadeLabel(prioridade)"></span>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Informações Adicionais -->
            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl p-4">
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div class="flex-1">
                        <h4 class="text-sm font-semibold text-blue-900 dark:text-blue-200 mb-1">Dicas de Uso</h4>
                        <ul class="text-xs text-blue-800 dark:text-blue-300 space-y-1 list-disc list-inside">
                            <li>Itens com prioridade alta (≥5) aparecem no topo da fila</li>
                            <li>Use prioridade 8+ para pedidos urgentes ou com prazo apertado</li>
                            <li>O status "Em Produção" registra automaticamente a data/hora de início</li>
                            <li>Você pode ajustar a prioridade e status depois na tela de fila</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Ações -->
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-6 border-t border-gray-200 dark:border-gray-700">
                <a href="{{ route('fila-producao.index') }}" 
                   class="w-full sm:w-auto px-6 py-3 rounded-lg border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors text-sm font-medium text-center">
                    Cancelar
                </a>
                <button type="submit" 
                        :disabled="!selectedItem"
                        class="w-full sm:w-auto px-6 py-3 rounded-lg bg-red-600 text-white font-semibold shadow-sm hover:bg-red-500 disabled:opacity-50 disabled:cursor-not-allowed transition-colors text-sm flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Adicionar à Fila
                </button>
            </div>
        </form>
    </div>

    <script>
        function filaForm() {
            return {
                selectedItem: '{{ old('pedido_item_id') }}',
                statusProducao: '{{ old('status_producao', 'pendente') }}',
                prioridade: {{ old('prioridade', 0) }},
                dataHoraInicio: '{{ old('data_hora_inicio', '') }}',
                dataHoraFim: '{{ old('data_hora_fim', '') }}',
                searchItem: '',
                pedidoItens: @json($pedidoItens),

                init() {
                    @if($pedidoId)
                        // Pré-selecionar itens do pedido se vier de um pedido específico
                        const select = document.querySelector('select[name="pedido_item_id"]');
                        const options = Array.from(select.options);
                        const pedidoOption = options.find(opt => opt.dataset.pedido == '{{ $pedidoId }}');
                        if (pedidoOption && !this.selectedItem) {
                            this.selectedItem = pedidoOption.value;
                            select.value = pedidoOption.value;
                        }
                    @endif
                },

                filterItems() {
                    const select = document.querySelector('select[name="pedido_item_id"]');
                    const options = Array.from(select.options);
                    const search = this.searchItem.toLowerCase();
                    
                    options.forEach(option => {
                        if (option.value === '') return;
                        const label = option.textContent.toLowerCase();
                        option.style.display = label.includes(search) ? '' : 'none';
                    });
                },

                updateItemInfo() {
                    // Sugerir prioridade baseada no item selecionado
                    if (this.selectedItem) {
                        const option = document.querySelector(`select[name="pedido_item_id"] option[value="${this.selectedItem}"]`);
                        if (option) {
                            const label = option.textContent.toLowerCase();
                            // Se contém "urgente" ou números altos, sugerir prioridade alta
                            if (label.includes('urgente') || label.includes('prioridade')) {
                                this.prioridade = 8;
                            } else if (this.prioridade === 0) {
                                this.prioridade = 3; // Prioridade média por padrão
                            }
                        }
                    }
                },

                getItemLabel() {
                    if (!this.selectedItem) return 'Selecione um item';
                    const option = document.querySelector(`select[name="pedido_item_id"] option[value="${this.selectedItem}"]`);
                    return option ? option.textContent : 'Item não encontrado';
                },

                getStatusLabel(status) {
                    const labels = {
                        'pendente': 'Pendente',
                        'em_producao': 'Em Produção',
                        'pronto': 'Pronto'
                    };
                    return labels[status] || status;
                },

                getPrioridadeLabel(prio) {
                    if (prio >= 8) return 'Urgente';
                    if (prio >= 5) return 'Alta';
                    if (prio >= 3) return 'Média';
                    if (prio >= 1) return 'Baixa';
                    return 'Sem prioridade';
                }
            }
        }
    </script>
@endsection
