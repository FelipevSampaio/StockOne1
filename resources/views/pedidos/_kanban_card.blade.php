<div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border-l-4 {{ $pedido->status === 'pendente' ? 'border-red-500' : ($pedido->status === 'em_preparo' ? 'border-yellow-500' : ($pedido->status === 'pronto' ? 'border-blue-500' : 'border-green-500')) }} p-4 hover:shadow-md transition-all duration-200 cursor-move kanban-card"
     data-pedido-id="{{ $pedido->id }}"
     x-data="{ showActions: false }"
     @mouseenter="showActions = true"
     @mouseleave="showActions = false">
    <!-- Cabeçalho do Card -->
    <div class="flex items-center justify-between mb-2">
        <div class="flex items-center gap-2">
            <div class="w-8 h-8 bg-gradient-to-br from-red-500 to-red-600 rounded-lg flex items-center justify-center text-white font-bold text-xs shadow-sm">
                #{{ $pedido->id }}
            </div>
            <div>
                <h4 class="font-semibold text-sm text-gray-900 dark:text-white">Pedido #{{ $pedido->id }}</h4>
                @if($pedido->numero_pedido_externo)
                    <p class="text-xs text-gray-500 dark:text-gray-400">Ext: {{ $pedido->numero_pedido_externo }}</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Informações do Pedido -->
    <div class="space-y-1.5 mb-3">
        @if($pedido->usuario)
            <div class="flex items-center gap-1 text-xs text-gray-600 dark:text-gray-400">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                {{ Str::limit($pedido->usuario->name, 20) }}
            </div>
        @endif
        @if($pedido->valor_total)
            <div class="flex items-center gap-1 text-xs font-semibold text-gray-900 dark:text-white">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                R$ {{ number_format($pedido->valor_total, 2, ',', '.') }}
            </div>
        @endif
        @if($pedido->itens && $pedido->itens->count() > 0)
            <div class="flex items-center gap-1 text-xs text-gray-600 dark:text-gray-400">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                {{ $pedido->itens->count() }} item(ns)
            </div>
        @endif
        @if($pedido->data_hora_pedido)
            <div class="flex items-center gap-1 text-xs text-gray-500 dark:text-gray-500">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ $pedido->data_hora_pedido->format('d/m H:i') }}
            </div>
        @endif
        @if($pedido->plataforma_origem)
            <div class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400">
                {{ ucfirst($pedido->plataforma_origem) }}
            </div>
        @endif
    </div>

    <!-- Ações Rápidas -->
    <div class="flex flex-wrap items-center gap-2 pt-2 border-t border-gray-200 dark:border-gray-700"
         x-show="showActions"
         x-transition>
        @php
            $nextStatus = null;
            switch($pedido->status) {
                case 'pendente':
                    $nextStatus = ['value' => 'recebido', 'label' => 'Receber'];
                    break;
                case 'recebido':
                    $nextStatus = ['value' => 'em_preparo', 'label' => 'Preparar'];
                    break;
                case 'em_preparo':
                    $nextStatus = ['value' => 'pronto', 'label' => 'Pronto'];
                    break;
                case 'pronto':
                    $nextStatus = ['value' => 'entregue', 'label' => 'Entregar'];
                    break;
            }
        @endphp
        @if($nextStatus && $pedido->status !== 'cancelado' && $pedido->status !== 'concluido')
            <form action="{{ route('pedidos.atualizarStatus', $pedido) }}" method="POST" class="inline flex-1">
                @csrf
                @method('PATCH')
                <input type="hidden" name="status" value="{{ $nextStatus['value'] }}">
                <button type="submit" 
                        class="w-full px-2 py-1.5 text-xs font-medium bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 rounded-lg hover:bg-green-200 dark:hover:bg-green-900/50 transition-colors flex items-center justify-center gap-1">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    {{ $nextStatus['label'] }}
                </button>
            </form>
        @endif

        <button @click="showPedidoDetails({{ $pedido->id }})"
                class="flex-1 px-2 py-1.5 text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors flex items-center justify-center gap-1">
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
            </svg>
            Ver
        </button>
    </div>
</div>

