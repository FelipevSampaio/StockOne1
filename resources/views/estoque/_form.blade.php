@php
    $registro = $estoque ?? null;
    $insumos = $insumos ?? collect();
    $localizacoes = $localizacoes ?? collect();
    $insumoSelecionado = $insumoSelecionado ?? null;
@endphp

<div class="space-y-6">
    <!-- Informações do Insumo -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
            </svg>
            Informações do Insumo
        </h3>
        
        <div class="grid gap-4 md:grid-cols-2">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Insumo
                </label>
                @if($registro && $registro->exists && $registro->insumo_id)
                    {{-- Em modo de edição, mostrar o insumo como texto (não editável) --}}
                    <div class="w-full px-4 py-2.5 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-100 flex items-center gap-2">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                        <span class="font-medium">{{ $registro->insumo?->nome ?? 'Insumo removido' }}</span>
                        @if($registro->insumo?->categoriaInsumo)
                            <span class="text-xs text-gray-500 dark:text-gray-400">({{ $registro->insumo->categoriaInsumo->nome }})</span>
                        @endif
                    </div>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">O insumo não pode ser alterado após a criação do estoque.</p>
                @else
                    {{-- Em modo de criação, permitir seleção --}}
                    <select name="insumo_id" 
                            id="insumo_id"
                            required 
                            class="w-full px-4 py-2.5 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                        <option value="">Selecione um insumo...</option>
                        @foreach ($insumos as $insumo)
                            <option value="{{ $insumo->id }}" 
                                    @selected(old('insumo_id', ($insumoSelecionado && $insumoSelecionado->id == $insumo->id ? $insumo->id : '')) == $insumo->id)
                                    data-unidade="{{ $insumo->unidade_medida }}"
                                    data-ponto-minimo="{{ $insumo->ponto_reposicao_minimo ?? 0 }}">
                                {{ $insumo->nome }} 
                                @if($insumo->categoriaInsumo)
                                    ({{ $insumo->categoriaInsumo->nome }})
                                @endif
                            </option>
                        @endforeach
                    </select>
                    @error('insumo_id')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                @endif
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Quantidade Atual <span class="text-red-600">*</span>
                </label>
                <div class="relative">
                    <input type="number" 
                           step="0.000001" 
                           min="0"
                           name="quantidade_atual" 
                           id="quantidade-input"
                           value="{{ old('quantidade_atual', $registro && $registro->quantidade_atual !== null ? number_format($registro->quantidade_atual, 6, '.', '') : '') }}" 
                           required 
                           placeholder="0.000000"
                           class="w-full px-4 py-2.5 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400">
                    <span id="unidade-display" class="absolute right-3 top-1/2 -translate-y-1/2 text-sm text-gray-500 dark:text-gray-400"></span>
                </div>
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Quantidade atual em estoque</p>
                @error('quantidade_atual')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>

    <!-- Localização -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            Localização
        </h3>
        
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Localização no Estoque
            </label>
            <input type="text" 
                   name="localizacao" 
                   id="localizacao-input"
                   value="{{ old('localizacao', $registro->localizacao ?? '') }}" 
                   list="localizacoes-list"
                   placeholder="Ex: Geladeira A1, Freezer B2, Armário C3..."
                   class="w-full px-4 py-2.5 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400">
            <datalist id="localizacoes-list">
                @foreach($localizacoes as $loc)
                    <option value="{{ $loc }}">
                @endforeach
            </datalist>
            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Opcional: onde este item está armazenado fisicamente</p>
            @error('localizacao')
                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>
    </div>
</div>

