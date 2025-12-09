<div class="space-y-6">
    <!-- Informações do Pedido -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4">
            <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Informações do Pedido</h4>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-600 dark:text-gray-400">ID:</span>
                    <span class="font-semibold text-gray-900 dark:text-white">#{{ $pedido->id }}</span>
                </div>
                @if($pedido->numero_pedido_externo)
                <div class="flex justify-between">
                    <span class="text-gray-600 dark:text-gray-400">Número Externo:</span>
                    <span class="font-semibold text-gray-900 dark:text-white">{{ $pedido->numero_pedido_externo }}</span>
                </div>
                @endif
                <div class="flex justify-between">
                    <span class="text-gray-600 dark:text-gray-400">Plataforma:</span>
                    <span class="font-semibold text-gray-900 dark:text-white">{{ ucfirst($pedido->plataforma_origem) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600 dark:text-gray-400">Data/Hora:</span>
                    <span class="font-semibold text-gray-900 dark:text-white">{{ $pedido->data_hora_pedido?->format('d/m/Y H:i') ?? '—' }}</span>
                </div>
                @if($pedido->tempo_preparo_estimado)
                <div class="flex justify-between">
                    <span class="text-gray-600 dark:text-gray-400">Tempo Estimado:</span>
                    <span class="font-semibold text-gray-900 dark:text-white">{{ $pedido->tempo_preparo_estimado }} min</span>
                </div>
                @endif
            </div>
        </div>

        <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4">
            <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Cliente</h4>
            <div class="space-y-2 text-sm">
                @if($pedido->usuario)
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-red-500 to-red-600 rounded-full flex items-center justify-center text-white font-semibold text-sm">
                            {{ strtoupper(substr($pedido->usuario->name, 0, 2)) }}
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900 dark:text-white">{{ $pedido->usuario->name }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $pedido->usuario->email }}</p>
                        </div>
                    </div>
                @else
                    <p class="text-gray-500 dark:text-gray-400">Cliente não identificado</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Status -->
    <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4">
        <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Status</h4>
        @php
            $statusConfig = [
                'pendente' => [
                    'label' => 'Pendente',
                    'class' => 'bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 border-red-200 dark:border-red-800'
                ],
                'recebido' => [
                    'label' => 'Recebido',
                    'class' => 'bg-orange-100 dark:bg-orange-900/30 text-orange-700 dark:text-orange-400 border-orange-200 dark:border-orange-800'
                ],
                'em_preparo' => [
                    'label' => 'Em Preparo',
                    'class' => 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400 border-yellow-200 dark:border-yellow-800'
                ],
                'pronto' => [
                    'label' => 'Pronto',
                    'class' => 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 border-blue-200 dark:border-blue-800'
                ],
                'entregue' => [
                    'label' => 'Entregue',
                    'class' => 'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 border-green-200 dark:border-green-800'
                ],
                'concluido' => [
                    'label' => 'Concluído',
                    'class' => 'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 border-green-200 dark:border-green-800'
                ],
                'cancelado' => [
                    'label' => 'Cancelado',
                    'class' => 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 border-gray-200 dark:border-gray-600'
                ],
            ];
            $status = $statusConfig[$pedido->status] ?? ['label' => ucfirst($pedido->status), 'class' => 'bg-gray-100 text-gray-700'];
        @endphp
        <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-semibold border {{ $status['class'] }}">
            {{ $status['label'] }}
        </span>
    </div>

    <!-- Itens do Pedido -->
    <div>
        <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Itens do Pedido</h4>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-800">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-900 dark:text-white uppercase">Item</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-900 dark:text-white uppercase">Quantidade</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-900 dark:text-white uppercase">Preço Unit.</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-900 dark:text-white uppercase">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($pedido->itens as $item)
                        <tr>
                            <td class="px-4 py-3">
                                <div>
                                    <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $item->cardapioItem->nome ?? 'Item removido' }}</p>
                                    @if($item->observacao)
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $item->observacao }}</p>
                                    @endif
                                </div>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">{{ $item->quantidade }}</td>
                            <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">R$ {{ number_format($item->preco_unitario, 2, ',', '.') }}</td>
                            <td class="px-4 py-3 text-sm font-semibold text-gray-900 dark:text-white">R$ {{ number_format($item->preco_unitario * $item->quantidade, 2, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">Nenhum item encontrado</td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot class="bg-gray-50 dark:bg-gray-800">
                    <tr>
                        <td colspan="3" class="px-4 py-3 text-right text-sm font-semibold text-gray-900 dark:text-white">Total:</td>
                        <td class="px-4 py-3 text-sm font-bold text-gray-900 dark:text-white">R$ {{ number_format($pedido->valor_total, 2, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>



