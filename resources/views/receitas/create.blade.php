@extends('layouts.app')

@section('title', 'Nova receita')
@section('subtitle', 'Associe insumos aos itens do cardápio')

@section('content')
    <div x-data="receitaForm()" x-init="init()" class="space-y-6">
        <!-- Banner Informativo -->
        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl p-4">
            <div class="flex items-start gap-3">
                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div class="flex-1">
                    <h4 class="text-sm font-semibold text-blue-900 dark:text-blue-300 mb-1">Dicas para criar receitas</h4>
                    <ul class="text-xs text-blue-800 dark:text-blue-400 space-y-1">
                        <li>• Você pode adicionar múltiplos insumos à mesma receita antes de salvar</li>
                        <li>• Marque como "Essencial" os insumos que não podem faltar na produção</li>
                        <li>• O sistema calcula automaticamente o custo total e a margem de lucro</li>
                        <li>• Verifique se o custo não ultrapassa o preço de venda do item</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Preview da Receita -->
        <div x-show="receitaItems.length > 0" 
             x-transition
             class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-xl border border-blue-200 dark:border-blue-800 p-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Preview da Receita</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1" x-show="itemSelecionado" x-text="`Item: ${cardapioItens.find(i => i.id == itemSelecionado)?.nome || ''}`"></p>
                </div>
                <span class="px-3 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 text-sm font-semibold rounded-full" x-text="`${receitaItems.length} insumo(s)`"></span>
            </div>
            <div class="space-y-3">
                <template x-for="(item, index) in receitaItems" :key="index">
                    <div class="flex items-center justify-between p-3 bg-white dark:bg-gray-800 rounded-lg border border-blue-200 dark:border-blue-800">
                        <div class="flex-1">
                            <p class="font-semibold text-gray-900 dark:text-white" x-text="item.insumoNome"></p>
                            <p class="text-sm text-gray-500 dark:text-gray-400" x-text="`${item.quantidade} ${item.unidade}`"></p>
                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-1" x-text="item.custo ? `Custo: R$ ${item.custo}` : ''"></p>
                        </div>
                        <div class="flex items-center gap-2">
                            <span x-show="item.essencial" class="px-2 py-1 bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 text-xs font-semibold rounded-full">Essencial</span>
                            <button @click="removeItem(index)" class="p-1 text-red-600 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </template>
                <div class="pt-3 border-t border-blue-200 dark:border-blue-800">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Custo Total Estimado:</span>
                        <span class="text-lg font-bold text-gray-900 dark:text-white" x-text="`R$ ${custoTotal.toFixed(2).replace('.', ',').replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.')}`"></span>
                    </div>
                    <div x-show="itemSelecionado && precoVenda" class="mt-2 flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Preço de Venda:</span>
                        <span class="text-lg font-bold text-gray-900 dark:text-white" x-text="`R$ ${precoVenda.toFixed(2).replace('.', ',').replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.')}`"></span>
                    </div>
                    <div x-show="itemSelecionado && precoVenda && custoTotal > 0" class="mt-2 flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Margem de Lucro:</span>
                        <span class="text-lg font-bold" 
                              :class="margemLucro >= 50 ? 'text-green-600 dark:text-green-400' : (margemLucro >= 30 ? 'text-yellow-600 dark:text-yellow-400' : 'text-red-600 dark:text-red-400')"
                              x-text="`${margemLucro.toFixed(1)}%`"></span>
                    </div>
                    <div x-show="custoTotal > precoVenda && precoVenda > 0" class="mt-3 p-3 bg-red-100 dark:bg-red-900/30 border border-red-200 dark:border-red-800 rounded-lg">
                        <p class="text-sm font-semibold text-red-700 dark:text-red-400 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                            Atenção: O custo total é maior que o preço de venda!
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Formulário Principal -->
        <form action="{{ route('receitas.store') }}" method="POST" 
              @submit.prevent="submitForm()"
              class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6"
              x-data="{ isSubmitting: false }"
              @submit="isSubmitting = true">
            @csrf

            <!-- Seleção do Item do Cardápio -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                    Item do Cardápio
                </h3>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Item do cardápio <span class="text-red-600">*</span>
                    </label>
                        <div class="relative">
                            <input type="text" 
                                   x-model="searchItem"
                                   @input="filterItems()"
                                   @focus="showItemDropdown = true"
                                   @click.away="showItemDropdown = false"
                                   placeholder="Buscar item..."
                                   class="w-full rounded-xl border-gray-200 dark:border-gray-600 px-4 py-2.5 text-sm focus:border-red-500 focus:ring-red-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                            <svg class="absolute right-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <div x-show="showItemDropdown && filteredItems.length > 0"
                                 class="absolute z-10 w-full mt-1 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-lg max-h-60 overflow-y-auto"
                                 style="display: none;">
                                <template x-for="item in filteredItems" :key="item.id">
                                    <button type="button"
                                            @click="selectItem(item)"
                                            class="w-full text-left px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 text-sm text-gray-900 dark:text-white">
                                        <p class="font-medium" x-text="item.nome"></p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400" x-text="`R$ ${item.preco}`"></p>
                                    </button>
                                </template>
                            </div>
                        </div>
                        <select name="cardapio_item_id" 
                                x-model="itemSelecionado"
                                @change="onItemChange()"
                                required 
                                class="mt-2 w-full rounded-xl border-gray-200 dark:border-gray-600 px-4 py-2.5 text-sm focus:border-red-500 focus:ring-red-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                            <option value="">Selecione um item...</option>
                            @foreach ($cardapioItens as $item)
                                <option value="{{ $item->id }}" data-preco="{{ $item->preco_venda ?? 0 }}">{{ $item->nome }}</option>
                            @endforeach
                        </select>
                        <div x-show="itemSelecionado" class="mt-2 p-2 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                            <p class="text-xs text-blue-700 dark:text-blue-400">
                                <span x-show="precoVenda > 0" x-text="`Preço de venda: R$ ${precoVenda.toFixed(2).replace('.', ',').replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.')}`"></span>
                            </p>
                        </div>
                </div>
            </div>

            <!-- Adicionar Insumos -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                    Adicionar Insumos à Receita
                </h3>
                
                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Insumo <span class="text-red-600">*</span>
                        </label>
                        <div class="relative">
                            <input type="text" 
                                   x-model="searchInsumo"
                                   @input="filterInsumos()"
                                   @focus="showInsumoDropdown = true"
                                   @click.away="showInsumoDropdown = false"
                                   @keydown.enter.prevent="if(filteredInsumos.length === 1) selectInsumo(filteredInsumos[0])"
                                   placeholder="Buscar insumo... (Enter para selecionar único resultado)"
                                   class="w-full rounded-xl border-gray-200 dark:border-gray-600 px-4 py-2.5 text-sm focus:border-red-500 focus:ring-red-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                            <svg class="absolute right-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <div x-show="showInsumoDropdown && filteredInsumos.length > 0"
                                 class="absolute z-10 w-full mt-1 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-lg max-h-60 overflow-y-auto"
                                 style="display: none;">
                                <template x-for="insumo in filteredInsumos" :key="insumo.id">
                                    <button type="button"
                                            @click="selectInsumo(insumo)"
                                            class="w-full text-left px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 text-sm text-gray-900 dark:text-white">
                                        <p class="font-medium" x-text="insumo.nome"></p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400" x-text="`${insumo.categoria} · R$ ${insumo.custo}/un`"></p>
                                    </button>
                                </template>
                            </div>
                        </div>
                        <select x-model="insumoSelecionado"
                                @change="onInsumoChange()"
                                class="mt-2 w-full rounded-xl border-gray-200 dark:border-gray-600 px-4 py-2.5 text-sm focus:border-red-500 focus:ring-red-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                            <option value="">Selecione um insumo...</option>
                            @foreach ($insumos as $id => $nome)
                                @php
                                    $insumo = \App\Models\Insumo::find($id);
                                @endphp
                                <option value="{{ $id }}" 
                                        data-nome="{{ $nome }}"
                                        data-custo="{{ $insumo->custo_unitario ?? 0 }}"
                                        data-unidade="{{ $insumo->unidade_medida ?? '' }}"
                                        data-categoria="{{ $insumo->categoria ?? '' }}">
                                    {{ $nome }} 
                                    @if($insumo && $insumo->custo_unitario)
                                        (R$ {{ number_format($insumo->custo_unitario, 2, ',', '.') }}/{{ $insumo->unidade_medida }})
                                    @endif
                                </option>
                            @endforeach
                        </select>
                        <div x-show="insumoSelecionado && insumoInfo" class="mt-2 p-2 bg-green-50 dark:bg-green-900/20 rounded-lg">
                            <p class="text-xs text-green-700 dark:text-green-400" x-text="`Custo unitário: R$ ${insumoInfo.custo}/un · ${insumoInfo.unidade}`"></p>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Quantidade necessária <span class="text-red-600">*</span>
                        </label>
                        <div class="relative">
                            <input type="number" 
                                   step="0.01" 
                                   min="0.01"
                                   x-model="quantidade"
                                   @input="calculateCusto()"
                                   @keydown.enter.prevent="addItem()"
                                   @keydown.ctrl.enter.prevent="addItemAndContinue()"
                                   placeholder="0.00"
                                   class="w-full rounded-xl border-gray-200 dark:border-gray-600 px-4 py-2.5 text-sm focus:border-red-500 focus:ring-red-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                            <span x-show="insumoInfo" class="absolute right-3 top-1/2 -translate-y-1/2 text-xs text-gray-500 dark:text-gray-400" x-text="insumoInfo.unidade"></span>
                        </div>
                        <p x-show="quantidade && insumoInfo" class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                            Custo: <span class="font-semibold" x-text="`R$ ${(quantidade * insumoInfo.custo).toFixed(2).replace('.', ',').replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.')}`"></span>
                        </p>
                    </div>

                    <div class="flex items-center gap-3 rounded-xl border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-900/50 px-4 py-3">
                        <input type="checkbox" 
                               x-model="essencial"
                               class="h-4 w-4 rounded border-gray-300 text-red-600 focus:ring-red-500">
                        <span class="text-sm text-gray-700 dark:text-gray-300">Insumo essencial para produção</span>
                    </div>
                </div>

                <div x-show="insumoSelecionado && receitaItems.some(item => item.insumoId == insumoSelecionado && item.cardapioItemId == itemSelecionado)" 
                     class="mt-2 p-2 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg">
                    <p class="text-xs text-yellow-700 dark:text-yellow-400 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        Este insumo já foi adicionado à receita. Você pode adicionar novamente se necessário.
                    </p>
                </div>

                <div class="flex items-center gap-3 mt-4">
                    <button type="button"
                            @click="addItem()"
                            :disabled="!canAddItem"
                            @keydown.enter.prevent="addItem()"
                            :class="canAddItem ? 'bg-red-600 hover:bg-red-500' : 'bg-gray-300 dark:bg-gray-600 cursor-not-allowed'"
                            class="flex-1 rounded-lg px-5 py-2.5 text-sm font-semibold text-white shadow transition-colors flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Adicionar à Receita
                    </button>
                    <button type="button"
                            @click="addItemAndContinue()"
                            :disabled="!canAddItem"
                            @keydown.ctrl.enter.prevent="addItemAndContinue()"
                            :class="canAddItem ? 'bg-blue-600 hover:bg-blue-500' : 'bg-gray-300 dark:bg-gray-600 cursor-not-allowed'"
                            class="rounded-lg px-4 py-2.5 text-sm font-semibold text-white shadow transition-colors flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed"
                            title="Adicionar e continuar (Ctrl+Enter)">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        <span class="hidden sm:inline">Adicionar e Continuar</span>
                        <span class="sm:hidden">+</span>
                    </button>
                </div>
                
                <p class="mt-2 text-xs text-gray-500 dark:text-gray-400 text-center">
                    💡 Dica: Use <kbd class="px-1.5 py-0.5 bg-gray-200 dark:bg-gray-700 rounded text-xs">Enter</kbd> para adicionar ou <kbd class="px-1.5 py-0.5 bg-gray-200 dark:bg-gray-700 rounded text-xs">Ctrl+Enter</kbd> para adicionar e continuar
                </p>
            </div>

            <!-- Lista de Receitas para Salvar -->
            <div x-show="receitaItems.length > 0" 
                 x-transition
                 class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Receitas a serem criadas (<span x-text="receitaItems.length"></span>)
                </h3>
                <div class="space-y-3">
                    <template x-for="(item, index) in receitaItems" :key="index">
                        <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-900/50 rounded-lg border border-gray-200 dark:border-gray-700">
                            <div class="flex-1">
                                <input type="hidden" :name="`receitas[${index}][cardapio_item_id]`" :value="item.cardapioItemId">
                                <input type="hidden" :name="`receitas[${index}][insumo_id]`" :value="item.insumoId">
                                <input type="hidden" :name="`receitas[${index}][quantidade_necessaria]`" :value="item.quantidade">
                                <input type="hidden" :name="`receitas[${index}][essencial]`" :value="item.essencial ? 1 : 0">
                                
                                <p class="font-semibold text-gray-900 dark:text-white" x-text="item.insumoNome"></p>
                                <p class="text-sm text-gray-600 dark:text-gray-400" x-text="`Quantidade: ${item.quantidade} ${item.unidade}`"></p>
                                <p class="text-xs text-gray-500 dark:text-gray-500 mt-1" x-text="item.custo ? `Custo: R$ ${item.custo}` : ''"></p>
                            </div>
                            <div class="flex items-center gap-2">
                                <span x-show="item.essencial" class="px-2 py-1 bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 text-xs font-semibold rounded-full">Essencial</span>
                                <button type="button" @click="removeItem(index)" class="p-2 text-red-600 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <div class="flex items-center justify-between gap-3 pt-6 border-t border-gray-200 dark:border-gray-700">
                <a href="{{ route('receitas.index') }}" 
                   class="px-5 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Cancelar
                </a>
                <button type="submit" 
                        :disabled="receitaItems.length === 0 || isSubmitting"
                        :class="receitaItems.length > 0 && !isSubmitting ? 'bg-red-600 hover:bg-red-500' : 'bg-gray-300 dark:bg-gray-600 cursor-not-allowed'"
                        class="px-5 py-2.5 text-sm font-semibold text-white rounded-lg shadow transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2">
                    <svg x-show="!isSubmitting" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <svg x-show="isSubmitting" class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span x-text="isSubmitting ? 'Salvando...' : (receitaItems.length > 0 ? `Salvar ${receitaItems.length} receita(s)` : 'Salvar')"></span>
                </button>
            </div>
        </form>
    </div>

    <script>
        function receitaForm() {
            return {
                // Estados
                itemSelecionado: '',
                insumoSelecionado: '',
                quantidade: '',
                essencial: true,
                receitaItems: [],
                
                // Busca
                searchItem: '',
                searchInsumo: '',
                showItemDropdown: false,
                showInsumoDropdown: false,
                
                // Dados
                cardapioItens: @json($cardapioItensJson),
                insumos: @json($insumosJson),
                
                // Computed
                get filteredItems() {
                    if (!this.searchItem) return this.cardapioItens;
                    const search = this.searchItem.toLowerCase();
                    return this.cardapioItens.filter(item => 
                        item.nome.toLowerCase().includes(search)
                    );
                },
                
                get filteredInsumos() {
                    if (!this.searchInsumo) return this.insumos;
                    const search = this.searchInsumo.toLowerCase();
                    return this.insumos.filter(insumo => 
                        insumo.nome.toLowerCase().includes(search) ||
                        insumo.categoria.toLowerCase().includes(search)
                    );
                },
                
                get insumoInfo() {
                    if (!this.insumoSelecionado) return null;
                    const insumo = this.insumos.find(i => i.id == this.insumoSelecionado);
                    return insumo ? {
                        nome: insumo.nome,
                        custo: parseFloat(insumo.custo) || 0,
                        unidade: insumo.unidade,
                        categoria: insumo.categoria
                    } : null;
                },
                
                get precoVenda() {
                    if (!this.itemSelecionado) return 0;
                    const item = this.cardapioItens.find(i => i.id == this.itemSelecionado);
                    return parseFloat(item?.preco) || 0;
                },
                
                get custoTotal() {
                    return this.receitaItems.reduce((total, item) => {
                        return total + (parseFloat(item.custo) || 0);
                    }, 0);
                },
                
                get margemLucro() {
                    if (!this.precoVenda || this.custoTotal === 0) return 0;
                    return ((this.precoVenda - this.custoTotal) / this.precoVenda) * 100;
                },
                
                get canAddItem() {
                    return this.itemSelecionado && 
                           this.insumoSelecionado && 
                           this.quantidade && 
                           parseFloat(this.quantidade) > 0;
                },
                
                init() {
                    // Carregar dados iniciais
                },
                
                filterItems() {
                    // Filtro já é computed
                },
                
                filterInsumos() {
                    // Filtro já é computed
                },
                
                selectItem(item) {
                    this.itemSelecionado = item.id;
                    this.searchItem = item.nome;
                    this.showItemDropdown = false;
                    this.onItemChange();
                },
                
                selectInsumo(insumo) {
                    this.insumoSelecionado = insumo.id;
                    this.searchInsumo = insumo.nome;
                    this.showInsumoDropdown = false;
                    this.onInsumoChange();
                },
                
                onItemChange() {
                    // Atualizar preço quando item muda
                },
                
                onInsumoChange() {
                    this.calculateCusto();
                },
                
                calculateCusto() {
                    // Custo é calculado automaticamente via computed
                },
                
                addItem() {
                    if (!this.canAddItem) return;
                    
                    // Verificar se já existe (aviso, mas permite)
                    const jaExiste = this.receitaItems.some(item => 
                        item.insumoId == this.insumoSelecionado && 
                        item.cardapioItemId == this.itemSelecionado
                    );
                    
                    if (jaExiste) {
                        if (!confirm('Este insumo já foi adicionado. Deseja adicionar novamente?')) {
                            return;
                        }
                    }
                    
                    const item = {
                        cardapioItemId: this.itemSelecionado,
                        cardapioItemNome: this.cardapioItens.find(i => i.id == this.itemSelecionado)?.nome || '',
                        insumoId: this.insumoSelecionado,
                        insumoNome: this.insumoInfo.nome,
                        quantidade: parseFloat(this.quantidade),
                        unidade: this.insumoInfo.unidade,
                        essencial: this.essencial,
                        custo: (parseFloat(this.quantidade) * this.insumoInfo.custo).toFixed(2)
                    };
                    
                    this.receitaItems.push(item);
                    
                    // Limpar formulário
                    this.insumoSelecionado = '';
                    this.quantidade = '';
                    this.searchInsumo = '';
                    this.essencial = true;
                    
                    // Focar no campo de busca de insumo para adicionar o próximo
                    this.$nextTick(() => {
                        const insumoInput = document.querySelector('input[placeholder="Buscar insumo..."]');
                        if (insumoInput) insumoInput.focus();
                    });
                },
                
                addItemAndContinue() {
                    if (!this.canAddItem) return;
                    
                    // Adicionar o item
                    this.addItem();
                    
                    // Manter o foco no formulário para adicionar o próximo rapidamente
                    this.$nextTick(() => {
                        const insumoInput = document.querySelector('input[placeholder="Buscar insumo..."]');
                        if (insumoInput) {
                            insumoInput.focus();
                            insumoInput.select();
                        }
                    });
                },
                
                removeItem(index) {
                    this.receitaItems.splice(index, 1);
                },
                
                submitForm() {
                    if (this.receitaItems.length === 0) {
                        alert('Adicione pelo menos um insumo à receita');
                        return;
                    }
                    
                    // Criar formulário dinâmico para cada receita
                    const form = document.querySelector('form[action="{{ route('receitas.store') }}"]');
                    
                    // Se houver apenas uma receita, usar o método normal
                    if (this.receitaItems.length === 1) {
                        const item = this.receitaItems[0];
                        const hiddenInputs = `
                            <input type="hidden" name="cardapio_item_id" value="${item.cardapioItemId}">
                            <input type="hidden" name="insumo_id" value="${item.insumoId}">
                            <input type="hidden" name="quantidade_necessaria" value="${item.quantidade}">
                            <input type="hidden" name="essencial" value="${item.essencial ? 1 : 0}">
                        `;
                        form.insertAdjacentHTML('beforeend', hiddenInputs);
                        form.submit();
                    } else {
                        // Múltiplas receitas - criar formulário separado
                        this.submitMultiple();
                    }
                },
                
                async submitMultiple() {
                    // Enviar múltiplas receitas via AJAX
                    try {
                        const formData = new FormData();
                        formData.append('_token', '{{ csrf_token() }}');
                        
                        this.receitaItems.forEach((item, index) => {
                            formData.append(`receitas[${index}][cardapio_item_id]`, item.cardapioItemId);
                            formData.append(`receitas[${index}][insumo_id]`, item.insumoId);
                            formData.append(`receitas[${index}][quantidade_necessaria]`, item.quantidade);
                            formData.append(`receitas[${index}][essencial]`, item.essencial ? 1 : 0);
                        });
                        
                        const response = await fetch('{{ route('receitas.store') }}', {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json'
                            }
                        });
                        
                        if (response.ok) {
                            window.location.href = '{{ route('receitas.index') }}';
                        } else {
                            const data = await response.json();
                            alert(data.message || 'Erro ao salvar receitas. Tente novamente.');
                        }
                    } catch (error) {
                        console.error('Erro ao salvar receitas:', error);
                        alert('Erro ao salvar receitas. Tente novamente.');
                    }
                }
            }
        }
    </script>
@endsection

