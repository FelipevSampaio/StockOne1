@php
    $fila = $fila ?? null;
    $pedidoId = $pedidoId ?? null;
@endphp

<div class="grid gap-4 md:grid-cols-2">
    <div class="md:col-span-2">
        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
            Item do pedido <span class="text-red-500">*</span>
        </label>
        <div class="relative">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            <select name="pedido_item_id" 
                    required 
                    class="pl-10 w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 px-4 py-3 text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors">
                <option value="">Selecione um item do pedido...</option>
                @foreach ($pedidoItens as $id => $label)
                    <option value="{{ $id }}" 
                            @selected(old('pedido_item_id', $fila->pedido_item_id ?? '') == $id)
                            @if($pedidoId && str_contains($label, "#{$pedidoId}"))
                                data-pedido="{{ $pedidoId }}"
                            @endif>
                        {{ $label }}
                    </option>
                @endforeach
            </select>
        </div>
        <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">Ao selecionar um item, o pedido é vinculado automaticamente à fila.</p>
    </div>

    <div>
        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
            Status da produção <span class="text-red-500">*</span>
        </label>
        <div class="relative">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <select name="status_producao" 
                    required 
                    class="pl-10 w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 px-4 py-3 text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors">
                <option value="pendente" @selected(old('status_producao', $fila->status_producao ?? 'pendente') === 'pendente')>Pendente</option>
                <option value="em_producao" @selected(old('status_producao', $fila->status_producao ?? '') === 'em_producao')>Em Produção</option>
                <option value="pronto" @selected(old('status_producao', $fila->status_producao ?? '') === 'pronto')>Pronto</option>
            </select>
        </div>
    </div>

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
                   value="{{ old('prioridade', $fila->prioridade ?? 0) }}" 
                   class="pl-10 w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 px-4 py-3 text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors">
        </div>
        <div class="mt-2 flex items-center gap-2">
            <button type="button" 
                    onclick="document.querySelector('input[name=\"prioridade\"]').value = '5'"
                    class="px-2 py-1 text-xs bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 rounded hover:bg-red-200 dark:hover:bg-red-900/50 transition-colors">
                Alta (5)
            </button>
            <button type="button" 
                    onclick="document.querySelector('input[name=\"prioridade\"]').value = '3'"
                    class="px-2 py-1 text-xs bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400 rounded hover:bg-yellow-200 dark:hover:bg-yellow-900/50 transition-colors">
                Média (3)
            </button>
            <button type="button" 
                    onclick="document.querySelector('input[name=\"prioridade\"]').value = '1'"
                    class="px-2 py-1 text-xs bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                Baixa (1)
            </button>
        </div>
    </div>

    <div>
        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
            Início produção
        </label>
        <div class="relative">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <input type="datetime-local" 
                   name="data_hora_inicio" 
                   value="{{ old('data_hora_inicio', isset($fila->data_hora_inicio) ? $fila->data_hora_inicio->format('Y-m-d\TH:i') : '') }}" 
                   class="pl-10 w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 px-4 py-3 text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors">
        </div>
        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Deixe em branco para usar a data/hora atual ao iniciar</p>
    </div>

    <div>
        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
            Fim produção
        </label>
        <div class="relative">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <input type="datetime-local" 
                   name="data_hora_fim" 
                   value="{{ old('data_hora_fim', isset($fila->data_hora_fim) ? $fila->data_hora_fim->format('Y-m-d\TH:i') : '') }}" 
                   class="pl-10 w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 px-4 py-3 text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors">
        </div>
        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Será preenchido automaticamente ao marcar como pronto</p>
    </div>
</div>

@if($pedidoId)
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const select = document.querySelector('select[name="pedido_item_id"]');
        const options = Array.from(select.options);
        
        // Filtrar e destacar itens do pedido selecionado
        options.forEach(option => {
            if (option.dataset.pedido == '{{ $pedidoId }}') {
                option.style.fontWeight = 'bold';
                if (!select.value) {
                    select.value = option.value;
                }
            }
        });
    });
</script>
@endif
