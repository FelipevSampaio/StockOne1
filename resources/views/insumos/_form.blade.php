@php
    $insumo = $insumo ?? null;
    $categorias = $categorias ?? collect();
    // Buscar categoria_id se o insumo já existe e tem categoria
    $categoriaIdSelecionada = '';
    if ($insumo && $insumo->categoria && $insumo->exists) {
        $categoria = \App\Models\CategoriaInsumo::where('nome', $insumo->categoria)
            ->where('restaurante_id', $insumo->restaurante_id)
            ->first();
        $categoriaIdSelecionada = $categoria ? $categoria->id : '';
    }
@endphp

<div class="space-y-6">
    <!-- Informações Básicas -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Informações Básicas
        </h3>
        
        <div class="grid gap-4 md:grid-cols-2">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Nome <span class="text-red-600">*</span>
                </label>
                <input type="text" 
                       name="nome" 
                       value="{{ old('nome', $insumo->nome ?? '') }}" 
                       required 
                       placeholder="Ex: Farinha de trigo"
                       class="w-full px-4 py-2.5 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400">
                @error('nome')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div x-data="categoriaManager()">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Categoria
                </label>
                <div class="flex gap-2">
                    <select name="categoria_id" 
                            x-model="categoriaId"
                            @change="updateCategoriaNome()"
                            class="flex-1 px-4 py-2.5 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                        <option value="">Selecione uma categoria...</option>
                        @foreach($categorias as $id => $nome)
                            <option value="{{ $id }}" 
                                    data-nome="{{ $nome }}"
                                    @selected(old('categoria_id', $categoriaIdSelecionada) == $id)>
                                {{ $nome }}
                            </option>
                        @endforeach
                        <option value="nova">+ Criar nova categoria</option>
                    </select>
                    <a href="{{ route('categoria-insumos.index') }}" 
                       target="_blank"
                       class="px-3 py-2.5 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors flex items-center"
                       title="Gerenciar categorias">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </a>
                </div>
                
                <!-- Campo para nova categoria -->
                <div x-show="categoriaId === 'nova'" 
                     x-transition
                     class="mt-2">
                    <input type="text" 
                           name="nova_categoria" 
                           x-model="novaCategoriaNome"
                           placeholder="Nome da nova categoria..."
                           class="w-full px-4 py-2.5 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400">
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">A categoria será criada automaticamente ao salvar o insumo.</p>
                </div>
                
                <!-- Campo hidden para enviar o nome da categoria selecionada -->
                <input type="hidden" name="categoria" x-model="categoriaNome">
                
                @error('categoria')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>
            
            <script>
                function categoriaManager() {
                    return {
                        categoriaId: '{{ old('categoria_id', $insumo->categoriaInsumo?->id ?? '') }}',
                        novaCategoriaNome: '',
                        categoriaNome: '{{ old('categoria', $insumo->categoria ?? '') }}',
                        
                        init() {
                            this.updateCategoriaNome();
                        },
                        
                        updateCategoriaNome() {
                            if (this.categoriaId === 'nova') {
                                this.categoriaNome = this.novaCategoriaNome;
                            } else if (this.categoriaId) {
                                const option = document.querySelector(`select[name="categoria_id"] option[value="${this.categoriaId}"]`);
                                this.categoriaNome = option ? option.dataset.nome : '';
                            } else {
                                this.categoriaNome = '';
                            }
                        }
                    }
                }
            </script>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Unidade de Medida <span class="text-red-600">*</span>
                </label>
                <div class="flex gap-2">
                    <select name="unidade_medida" 
                            id="unidade-select"
                            required
                            class="flex-1 px-4 py-2.5 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                        <option value="">Selecione...</option>
                        <option value="kg" {{ old('unidade_medida', $insumo->unidade_medida ?? '') === 'kg' ? 'selected' : '' }}>Quilograma (kg)</option>
                        <option value="g" {{ old('unidade_medida', $insumo->unidade_medida ?? '') === 'g' ? 'selected' : '' }}>Grama (g)</option>
                        <option value="l" {{ old('unidade_medida', $insumo->unidade_medida ?? '') === 'l' ? 'selected' : '' }}>Litro (l)</option>
                        <option value="ml" {{ old('unidade_medida', $insumo->unidade_medida ?? '') === 'ml' ? 'selected' : '' }}>Mililitro (ml)</option>
                        <option value="un" {{ old('unidade_medida', $insumo->unidade_medida ?? '') === 'un' ? 'selected' : '' }}>Unidade (un)</option>
                        <option value="cx" {{ old('unidade_medida', $insumo->unidade_medida ?? '') === 'cx' ? 'selected' : '' }}>Caixa (cx)</option>
                        <option value="pct" {{ old('unidade_medida', $insumo->unidade_medida ?? '') === 'pct' ? 'selected' : '' }}>Pacote (pct)</option>
                    </select>
                    <input type="text" 
                           name="unidade_medida_custom" 
                           id="unidade-custom"
                           value="{{ !in_array(old('unidade_medida', $insumo->unidade_medida ?? ''), ['kg', 'g', 'l', 'ml', 'un', 'cx', 'pct', '']) ? old('unidade_medida', $insumo->unidade_medida ?? '') : '' }}"
                           placeholder="Outra..."
                           class="flex-1 px-4 py-2.5 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400"
                           style="display: none;">
                </div>
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Selecione uma unidade comum ou digite uma personalizada</p>
                @error('unidade_medida')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Descrição
                </label>
                <textarea name="descricao" 
                          rows="3" 
                          placeholder="Descrição adicional do insumo..."
                          class="w-full px-4 py-2.5 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400">{{ old('descricao', $insumo->descricao ?? '') }}</textarea>
                @error('descricao')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>

    <!-- Controle de Estoque -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
            </svg>
            Controle de Estoque
        </h3>
        
        <div class="grid gap-4 md:grid-cols-2">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Ponto de Reposição Mínimo
                </label>
                <input type="number" 
                       step="0.01" 
                       min="0"
                       name="ponto_reposicao_minimo" 
                       value="{{ old('ponto_reposicao_minimo', $insumo->ponto_reposicao_minimo ?? '') }}" 
                       placeholder="0.00"
                       class="w-full px-4 py-2.5 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400">
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Quantidade mínima antes de gerar alerta de estoque baixo</p>
                @error('ponto_reposicao_minimo')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Custo Unitário (R$)
                </label>
                <div class="relative">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 dark:text-gray-400">R$</span>
                    <input type="number" 
                           step="0.000001" 
                           min="0"
                           name="custo_unitario" 
                           value="{{ old('custo_unitario', $insumo->custo_unitario ?? '') }}" 
                           placeholder="0.000000"
                           class="w-full pl-10 pr-4 py-2.5 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400">
                </div>
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Custo por unidade de medida (ex: R$ 0,005 por grama de sal)</p>
                @error('custo_unitario')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Data de Validade Mínima
                </label>
                <input type="date" 
                       name="data_validade_minima" 
                       value="{{ old('data_validade_minima', isset($insumo->data_validade_minima) ? $insumo->data_validade_minima->format('Y-m-d') : '') }}" 
                       class="w-full px-4 py-2.5 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Data mínima de validade esperada para este insumo</p>
                @error('data_validade_minima')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>
</div>

<input type="hidden" name="restaurante_id" value="{{ session('restaurante_id') }}">

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Toggle entre select e input customizado para unidade de medida
        const unidadeSelect = document.getElementById('unidade-select');
        const unidadeCustom = document.getElementById('unidade-custom');
        
        if (unidadeSelect && unidadeCustom) {
            // Se já tem valor customizado, mostrar o input
            if (unidadeCustom.value) {
                unidadeSelect.value = '';
                unidadeCustom.style.display = 'block';
                unidadeSelect.style.display = 'none';
            }
            
            // Adicionar opção "Outra..." no select
            const outraOption = document.createElement('option');
            outraOption.value = 'custom';
            outraOption.textContent = 'Outra...';
            if (!unidadeSelect.querySelector('option[value="custom"]')) {
                unidadeSelect.appendChild(outraOption);
            }
            
            unidadeSelect.addEventListener('change', function() {
                if (this.value === 'custom') {
                    this.style.display = 'none';
                    unidadeCustom.style.display = 'block';
                    unidadeCustom.focus();
                    unidadeCustom.required = true;
                    this.removeAttribute('required');
                } else if (this.value) {
                    unidadeCustom.style.display = 'none';
                    unidadeCustom.value = '';
                    unidadeCustom.removeAttribute('required');
                    this.setAttribute('required', 'required');
                }
            });
            
            unidadeCustom.addEventListener('blur', function() {
                if (!this.value && unidadeSelect.value !== 'custom') {
                    this.style.display = 'none';
                    unidadeSelect.style.display = 'block';
                }
            });
            
            // Antes de submeter, garantir que o valor correto seja enviado
            const form = unidadeSelect.closest('form');
            if (form) {
                form.addEventListener('submit', function(e) {
                    if (unidadeCustom.style.display === 'block' && unidadeCustom.value) {
                        // Criar um input hidden com o valor correto
                        let hiddenInput = form.querySelector('input[name="unidade_medida_hidden"]');
                        if (!hiddenInput) {
                            hiddenInput = document.createElement('input');
                            hiddenInput.type = 'hidden';
                            hiddenInput.name = 'unidade_medida';
                            form.appendChild(hiddenInput);
                        }
                        hiddenInput.value = unidadeCustom.value;
                        unidadeSelect.removeAttribute('name');
                    } else if (unidadeSelect.value && unidadeSelect.value !== 'custom') {
                        // Garantir que o select tenha o name correto
                        unidadeSelect.setAttribute('name', 'unidade_medida');
                        if (unidadeCustom.hasAttribute('name')) {
                            unidadeCustom.removeAttribute('name');
                        }
                    }
                });
            }
        }
    });
</script>
@endsection
