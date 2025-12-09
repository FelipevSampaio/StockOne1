<div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border-l-4 {{ $registro->prioridade >= 5 ? 'border-red-500' : ($registro->status_producao === 'em_producao' ? 'border-blue-500' : 'border-gray-300 dark:border-gray-600') }} p-4 hover:shadow-md transition-all duration-200 cursor-move"
     x-data="{ showActions: false }"
     @mouseenter="showActions = true"
     @mouseleave="showActions = false">
    <!-- Prioridade -->
    <div class="flex items-center justify-between mb-2">
        <div class="flex items-center gap-2">
            <div class="w-8 h-8 {{ $registro->prioridade >= 5 ? 'bg-red-100 dark:bg-red-900/30 ring-2 ring-red-500' : ($registro->status_producao === 'em_producao' ? 'bg-blue-100 dark:bg-blue-900/30 ring-2 ring-blue-500' : 'bg-gray-100 dark:bg-gray-700') }} rounded-lg flex items-center justify-center flex-shrink-0 {{ $registro->prioridade >= 5 ? 'animate-pulse' : '' }}">
                <span class="text-xs font-bold {{ $registro->prioridade >= 5 ? 'text-red-600 dark:text-red-400' : ($registro->status_producao === 'em_producao' ? 'text-blue-600 dark:text-blue-400' : 'text-gray-600 dark:text-gray-400') }}">{{ $registro->prioridade ?? 0 }}</span>
            </div>
            <div>
                <h4 class="font-semibold text-sm text-gray-900 dark:text-white">Pedido #{{ $registro->pedido_id }}</h4>
                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $registro->pedidoItem?->cardapioItem?->nome ?? 'Item' }}</p>
            </div>
        </div>
    </div>

    <!-- Informações -->
    <div class="space-y-1.5 mb-3">
        @if($registro->pedidoItem)
            <div class="flex items-center gap-1 text-xs text-gray-600 dark:text-gray-400">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                </svg>
                Qtd: {{ $registro->pedidoItem->quantidade }}
            </div>
        @endif
        @if($registro->data_hora_inicio)
            <div class="flex items-center gap-1 text-xs text-gray-600 dark:text-gray-400">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ $registro->data_hora_inicio->format('H:i') }}
                @if($registro->status_producao === 'em_producao')
                    <span class="text-blue-600 dark:text-blue-400">({{ $registro->data_hora_inicio->diffForHumans() }})</span>
                @endif
            </div>
        @endif
    </div>

    <!-- Ações Rápidas -->
    <div class="flex flex-wrap items-center gap-2 pt-2 border-t border-gray-200 dark:border-gray-700"
         x-show="showActions"
         x-transition>
        @if($registro->status_producao === 'pendente')
            <form action="{{ route('fila-producao.update', $registro) }}" method="POST" class="inline">
                @csrf
                @method('PATCH')
                <input type="hidden" name="status_producao" value="em_producao">
                <input type="hidden" name="data_hora_inicio" value="{{ now()->format('Y-m-d\TH:i') }}">
                <button type="submit" 
                        class="w-full px-2 py-1.5 text-xs font-medium bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 rounded-lg hover:bg-blue-200 dark:hover:bg-blue-900/50 transition-colors flex items-center justify-center gap-1">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Iniciar
                </button>
            </form>
        @endif
        
        @if($registro->status_producao === 'em_producao')
            <form action="{{ route('fila-producao.update', $registro) }}" method="POST" class="inline">
                @csrf
                @method('PATCH')
                <input type="hidden" name="status_producao" value="pronto">
                <input type="hidden" name="data_hora_fim" value="{{ now()->format('Y-m-d\TH:i') }}">
                <button type="submit" 
                        class="w-full px-2 py-1.5 text-xs font-medium bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 rounded-lg hover:bg-green-200 dark:hover:bg-green-900/50 transition-colors flex items-center justify-center gap-1">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Pronto
                </button>
            </form>
        @endif

        <a href="{{ route('pedidos.index', ['search' => $registro->pedido_id]) }}" 
           class="flex-1 px-2 py-1.5 text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors flex items-center justify-center gap-1">
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
            </svg>
            Ver
        </a>
    </div>
</div>

