@php
    $item = $item ?? null;
@endphp

<div class="space-y-6">
    <!-- Informações do Restaurante -->
    <div class="rounded-xl border border-red-200 dark:border-red-800/50 bg-gradient-to-r from-red-50 to-red-100 dark:from-red-900/20 dark:to-red-800/20 px-4 py-3">
        <div class="flex items-center gap-2 text-sm text-red-700 dark:text-red-400">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
            </svg>
            <span>Operando em: <strong>{{ session('restaurante_nome') }}</strong></span>
        </div>
    </div>
    <input type="hidden" name="restaurante_id" value="{{ session('restaurante_id') }}">

    <!-- Seção: Informações Básicas -->
    <div class="space-y-4">
        <div class="flex items-center gap-2 pb-2 border-b border-gray-200 dark:border-gray-700">
            <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Informações Básicas</h3>
        </div>
        
        <div class="grid gap-4 md:grid-cols-2">

            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">
                    Nome do item *
                </label>
                <input type="text" 
                       name="nome" 
                       value="{{ old('nome', $item->nome ?? '') }}" 
                       required 
                       placeholder="Ex: Pizza Margherita"
                       class="w-full rounded-xl border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-800 px-4 py-2.5 text-sm text-gray-900 dark:text-white placeholder:text-gray-400 dark:placeholder:text-gray-400 focus:border-red-500 dark:focus:border-red-500 focus:ring-2 focus:ring-red-500/50 transition-colors">
                @error('nome')
                    <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">
                    Preço de venda (R$) *
                </label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 dark:text-gray-400">R$</span>
                    <input type="number" 
                           step="0.01" 
                           min="0"
                           name="preco_venda" 
                           value="{{ old('preco_venda', $item->preco_venda ?? '') }}" 
                           required 
                           placeholder="0.00"
                           class="w-full rounded-xl border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-800 px-4 pl-10 py-2.5 text-sm text-gray-900 dark:text-white placeholder:text-gray-400 dark:placeholder:text-gray-400 focus:border-red-500 dark:focus:border-red-500 focus:ring-2 focus:ring-red-500/50 transition-colors">
                </div>
                @error('preco_venda')
                    <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">
                    Tempo de preparo (minutos)
                </label>
                <input type="number" 
                       name="tempo_preparo_minutos" 
                       value="{{ old('tempo_preparo_minutos', $item->tempo_preparo_minutos ?? '') }}" 
                       min="0"
                       placeholder="Ex: 15"
                       class="w-full rounded-xl border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-800 px-4 py-2.5 text-sm text-gray-900 dark:text-white placeholder:text-gray-400 dark:placeholder:text-gray-400 focus:border-red-500 dark:focus:border-red-500 focus:ring-2 focus:ring-red-500/50 transition-colors">
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Tempo estimado para preparar este item</p>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">
                    Complexidade (1-10) *
                </label>
                <input type="number" 
                       min="1" 
                       max="10" 
                       name="complexidade_preparo" 
                       value="{{ old('complexidade_preparo', $item->complexidade_preparo ?? 1) }}" 
                       required 
                       class="w-full rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 px-4 py-2.5 text-sm text-gray-900 dark:text-white focus:border-red-500 focus:ring-2 focus:ring-red-500/50 transition-colors">
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Nível de dificuldade de preparo</p>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">
                    Categoria
                </label>
                <input type="text" 
                       name="categoria" 
                       value="{{ old('categoria', $item->categoria ?? '') }}" 
                       placeholder="Ex: Pizzas, Bebidas, Sobremesas"
                       class="w-full rounded-xl border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-800 px-4 py-2.5 text-sm text-gray-900 dark:text-white placeholder:text-gray-400 dark:placeholder:text-gray-400 focus:border-red-500 dark:focus:border-red-500 focus:ring-2 focus:ring-red-500/50 transition-colors">
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Agrupa itens similares no cardápio</p>
            </div>
        </div>
    </div>

    <!-- Seção: Descrição e Imagem -->
    <div class="space-y-4">
        <div class="flex items-center gap-2 pb-2 border-b border-gray-200 dark:border-gray-700">
            <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Descrição e Imagem</h3>
        </div>
        
        <div class="grid gap-4 md:grid-cols-2">

    <div class="md:col-span-2">
        <label class="text-sm font-semibold text-gray-700 dark:text-gray-300">
            Imagem do item
            <div class="mt-2 space-y-3">
                <!-- Preview da imagem -->
                <div x-show="imagePreview" 
                     x-transition
                     class="relative inline-block">
                    <img :src="imagePreview" 
                         alt="Preview" 
                         class="h-40 w-40 rounded-xl object-cover border-2 border-gray-200 dark:border-gray-700 shadow-sm">
                    <button type="button" 
                            @click="imagePreview = ''; document.querySelector('input[name=imagem]').value = ''"
                            class="absolute -top-2 -right-2 rounded-full bg-red-600 text-white p-1.5 shadow-lg hover:bg-red-500 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                
                <!-- Área de Drag and Drop -->
                <div x-data="{ 
                        isDragging: false,
                        handleDroppedFile(file) {
                            if (!file) return;
                            
                            // Validar tipo - aceita JPEG, PNG, GIF e WEBP
                            const allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
                            const allowedExtensions = ['.jpg', '.jpeg', '.png', '.gif', '.webp'];
                            const fileName = file.name.toLowerCase();
                            const hasValidExtension = allowedExtensions.some(ext => fileName.endsWith(ext));
                            
                            // Para WEBP, aceitar mesmo se o MIME type não estiver definido (alguns navegadores não definem)
                            const isWebP = fileName.endsWith('.webp') || file.type === 'image/webp';
                            const isValidType = allowedTypes.includes(file.type) || (isWebP && hasValidExtension);
                            
                            if (!file.type.startsWith('image/') && !isWebP) {
                                if (!isValidType && !hasValidExtension) {
                                    alert('Por favor, selecione apenas arquivos de imagem nos formatos: JPEG, PNG, GIF ou WEBP');
                                    return;
                                }
                            }
                            
                            // Se for WEBP mas o tipo não estiver definido, aceitar pela extensão
                            if (isWebP && !file.type) {
                                console.log('Arquivo WEBP detectado pela extensão:', fileName);
                            }
                            
                            // Validar tamanho
                            if (file.size > 8 * 1024 * 1024) {
                                alert('O arquivo é muito grande. Tamanho máximo: 8MB');
                                return;
                            }
                            
                            const fileInput = document.getElementById('imagem-input');
                            if (!fileInput) {
                                console.error('Input file não encontrado');
                                return;
                            }
                            
                            // Tentar atualizar o input file usando DataTransfer
                            try {
                                // Verificar se DataTransfer está disponível
                                if (typeof DataTransfer === 'undefined') {
                                    console.error('DataTransfer não está disponível neste navegador');
                                    alert('Seu navegador não suporta arrastar e soltar arquivos. Por favor, clique na área para selecionar o arquivo.');
                                    return;
                                }
                                
                                const dataTransfer = new DataTransfer();
                                dataTransfer.items.add(file);
                                fileInput.files = dataTransfer.files;
                                
                                // Verificar imediatamente se o arquivo foi atribuído
                                if (fileInput.files.length === 0) {
                                    console.error('Erro: arquivo não foi atribuído ao input após DataTransfer');
                                    alert('Erro ao processar o arquivo. Por favor, clique na área para selecionar o arquivo manualmente.');
                                    return;
                                }
                                
                                // Verificar se é o mesmo arquivo
                                const assignedFile = fileInput.files[0];
                                if (assignedFile.name !== file.name || assignedFile.size !== file.size) {
                                    console.error('Erro: arquivo atribuído não corresponde ao arquivo original');
                                    console.log('Original:', file.name, file.size);
                                    console.log('Atribuído:', assignedFile.name, assignedFile.size);
                                    alert('Erro ao processar o arquivo. Por favor, clique na área para selecionar o arquivo manualmente.');
                                    return;
                                }
                                
                                // Disparar evento change para atualizar o preview (sem await para evitar problemas)
                                try {
                                    const changeEvent = new Event('change', { bubbles: true, cancelable: true });
                                    fileInput.dispatchEvent(changeEvent);
                                } catch (e) {
                                    console.warn('Erro ao disparar evento change:', e);
                                    // Mesmo com erro, continuar para atualizar o preview manualmente
                                }
                                
                                console.log('✅ Arquivo atribuído com sucesso:', assignedFile.name, assignedFile.size, 'bytes');
                                console.log('✅ Input file contém:', fileInput.files.length, 'arquivo(s)');
                                
                                // Atualizar o preview manualmente se o evento não funcionou
                                const reader = new FileReader();
                                reader.onload = (e) => {
                                    window.dispatchEvent(new CustomEvent('image-uploaded', {
                                        detail: { imageData: e.target.result }
                                    }));
                                };
                                reader.onerror = () => {
                                    console.error('Erro ao ler arquivo para preview');
                                };
                                reader.readAsDataURL(file);
                            } catch (error) {
                                console.error('Erro ao atribuir arquivo:', error);
                                alert('Erro ao processar o arquivo. Por favor, clique na área para selecionar o arquivo manualmente.');
                            }
                        }
                    }"
                     @dragover.prevent="isDragging = true"
                     @dragleave.prevent="isDragging = false"
                     @drop.prevent="
                        isDragging = false;
                        const file = $event.dataTransfer.files[0];
                        handleDroppedFile(file);
                     "
                     :class="isDragging ? 'border-red-500 bg-red-50 dark:bg-red-900/20' : 'border-gray-300 dark:border-gray-600'"
                     class="relative border-2 border-dashed rounded-xl p-8 text-center transition-all duration-200 bg-gray-50 dark:bg-gray-900/50 hover:border-red-400 dark:hover:border-red-600 cursor-pointer">
                    <input type="file" 
                           name="imagem" 
                           id="imagem-input"
                           accept="image/jpeg,image/png,image/gif,image/webp,.jpg,.jpeg,.png,.gif,.webp" 
                           @change="
                                const file = $event.target.files && $event.target.files[0];
                                if (file) {
                                    // Validar tipo e tamanho - aceita JPEG, PNG, GIF e WEBP
                                    const allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
                                    const allowedExtensions = ['.jpg', '.jpeg', '.png', '.gif', '.webp'];
                                    const fileName = file.name.toLowerCase();
                                    const hasValidExtension = allowedExtensions.some(ext => fileName.endsWith(ext));
                                    
                                    // Para WEBP, aceitar mesmo se o MIME type não estiver definido (alguns navegadores não definem)
                                    const isWebP = fileName.endsWith('.webp') || file.type === 'image/webp';
                                    const isValidType = allowedTypes.includes(file.type) || (isWebP && hasValidExtension);
                                    
                                    if (!file.type.startsWith('image/') && !isWebP) {
                                        if (!isValidType && !hasValidExtension) {
                                            alert('Por favor, selecione apenas arquivos de imagem nos formatos: JPEG, PNG, GIF ou WEBP');
                                            $event.target.value = '';
                                            return;
                                        }
                                    }
                                    
                                    // Se for WEBP mas o tipo não estiver definido, aceitar pela extensão
                                    if (isWebP && !file.type) {
                                        console.log('Arquivo WEBP detectado pela extensão:', fileName);
                                    }
                                    if (file.size > 8 * 1024 * 1024) {
                                        alert('O arquivo é muito grande. Tamanho máximo: 8MB');
                                        $event.target.value = '';
                                        return;
                                    }
                                    // Atualizar o preview quando arquivo é selecionado
                                    const reader = new FileReader();
                                    reader.onload = (e) => {
                                        window.dispatchEvent(new CustomEvent('image-uploaded', {
                                            detail: { imageData: e.target.result }
                                        }));
                                    };
                                    reader.onerror = () => {
                                        alert('Erro ao ler o arquivo. Tente novamente.');
                                    };
                                    reader.readAsDataURL(file);
                                } else {
                                    // Se não há arquivo, limpar o preview
                                    window.dispatchEvent(new CustomEvent('image-uploaded', {
                                        detail: { imageData: '' }
                                    }));
                                }
                           "
                           class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                    
                    <div class="flex flex-col items-center justify-center gap-3">
                        <div class="w-16 h-16 rounded-full bg-red-100 dark:bg-red-900/30 flex items-center justify-center">
                            <svg class="w-8 h-8 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                <span class="text-red-600 dark:text-red-400">Clique para fazer upload</span> ou arraste e solte
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                PNG, JPG, GIF ou WEBP (máx. 8MB)
                            </p>
                        </div>
                    </div>
                </div>
                
                @if (isset($item) && $item->imagem)
                    <div x-init="imagePreview = '{{ asset('storage/' . $item->imagem) }}'" class="hidden"></div>
                @endif
            </div>
        </label>
        <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
            <span class="inline-flex items-center gap-1">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Formatos aceitos: JPEG, PNG, JPG, GIF, WEBP (máx. 8MB)
            </span>
        </p>
    </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">
                    Descrição do item
                </label>
                <textarea name="descricao" 
                          rows="4" 
                          placeholder="Descreva o item, ingredientes principais, sabor, etc..."
                          class="w-full rounded-xl border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-800 px-4 py-2.5 text-sm text-gray-900 dark:text-white placeholder:text-gray-400 dark:placeholder:text-gray-400 focus:border-red-500 dark:focus:border-red-500 focus:ring-2 focus:ring-red-500/50 transition-colors resize-none">{{ old('descricao', $item->descricao ?? '') }}</textarea>
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Esta descrição aparecerá no cardápio público</p>
            </div>
        </div>
    </div>

    <!-- Seção: Configurações -->
    <div class="space-y-4">
        <div class="flex items-center gap-2 pb-2 border-b border-gray-200 dark:border-gray-700">
            <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Configurações</h3>
        </div>
        
        <div class="flex items-center gap-3 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50 px-4 py-3 hover:bg-gray-100 dark:hover:bg-gray-900 transition-colors">
            <input type="checkbox" 
                   name="ativo_online" 
                   value="1" 
                   @checked(old('ativo_online', $item->ativo_online ?? true)) 
                   id="ativo_online"
                   class="h-4 w-4 rounded border-gray-300 text-red-600 focus:ring-red-500 dark:bg-gray-700 dark:border-gray-600">
            <label for="ativo_online" class="flex-1 text-sm font-medium text-gray-700 dark:text-gray-300 cursor-pointer">
                Disponível nos canais online
            </label>
            <span class="text-xs text-gray-500 dark:text-gray-400">Visível no menu público</span>
        </div>
    </div>
</div>

