@extends('layouts.app')

@section('title', 'Nova receita')
@section('subtitle', 'Associe insumos aos itens do cardápio')

@section('content')
    <div x-data="receitaForm()" x-init="init()" class="space-y-6">
        <!-- Preview da Receita -->
        <div x-show="receitaItems.length > 0" 
             x-transition
             class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-xl border border-blue-200 dark:border-blue-800 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Preview da Receita</h3>
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
              class="space-y-6 rounded-2xl border border-gray-100 dark:border-gray-700 bg-white dark:bg-gray-800 p-8 shadow-sm">
            @csrf

            <!-- Adicionar Múltiplos Insumos -->
            <div class="bg-gray-50 dark:bg-gray-900/50 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Adicionar Insumo à Receita</h3>
                
                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="text-sm font-semibold text-gray-700 dark:text-gray-300 block mb-2">
                            Item do cardápio *
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

                    <div>
                        <label class="text-sm font-semibold text-gray-700 dark:text-gray-300 block mb-2">
                            Insumo *
                        </label>
                        <div class="relative">
                            <input type="text" 
                                   x-model="searchInsumo"
                                   @input="filterInsumos()"
                                   @focus="showInsumoDropdown = true"
                                   @click.away="showInsumoDropdown = false"
                                   placeholder="Buscar insumo..."
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
                        <label class="text-sm font-semibold text-gray-700 dark:text-gray-300 block mb-2">
                            Quantidade necessária *
                        </label>
                        <div class="relative">
                            <input type="number" 
                                   step="0.01" 
                                   min="0.01"
                                   x-model="quantidade"
                                   @input="calculateCusto()"
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

                <button type="button"
                        @click="addItem()"
                        :disabled="!canAddItem"
                        :class="canAddItem ? 'bg-red-600 hover:bg-red-500' : 'bg-gray-300 dark:bg-gray-600 cursor-not-allowed'"
                        class="mt-4 w-full rounded-full px-5 py-2.5 text-sm font-semibold text-white shadow transition-colors flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Adicionar à Receita
                </button>
            </div>

            <!-- Lista de Receitas para Salvar -->
            <div x-show="receitaItems.length > 0" 
                 x-transition
                 class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Receitas a serem criadas</h3>
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

            <div class="flex items-center justify-end gap-3">
                <a href="{{ route('receitas.index') }}" class="rounded-full border border-gray-200 dark:border-gray-600 px-5 py-2 text-sm text-gray-600 dark:text-gray-300 hover:text-red-600 dark:hover:text-red-400 transition-colors">
                    Cancelar
                </a>
                <button type="submit" 
                        :disabled="receitaItems.length === 0"
                        :class="receitaItems.length > 0 ? 'bg-red-600 hover:bg-red-500' : 'bg-gray-300 dark:bg-gray-600 cursor-not-allowed'"
                        class="rounded-full px-5 py-2 text-sm font-semibold text-white shadow transition-colors">
                    Salvar <span x-show="receitaItems.length > 0" x-text="`${receitaItems.length} receita(s)`"></span>
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

