<div class="space-y-4">
    <!-- Header Info -->
    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Ação</label>
            <p class="text-base font-bold text-gray-900 dark:text-gray-100 mt-1">{{ ucfirst($log->action) }}</p>
        </div>
        <div>
            <label class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Modelo</label>
            <p class="text-base font-bold text-gray-900 dark:text-gray-100 mt-1">{{ class_basename($log->model) }} #{{ $log->model_id }}</p>
        </div>
        <div>
            <label class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Usuário</label>
            <p class="text-base font-bold text-gray-900 dark:text-gray-100 mt-1">{{ $log->user->name ?? 'Sistema' }}</p>
        </div>
        <div>
            <label class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Data/Hora</label>
            <p class="text-base font-bold text-gray-900 dark:text-gray-100 mt-1">{{ $log->created_at->format('d/m/Y H:i:s') }}</p>
        </div>
    </div>

    <!-- Network Info -->
    <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
        <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Informações de Rede</h4>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">IP</label>
                <p class="text-sm text-gray-900 dark:text-gray-100 mt-1 font-mono">{{ $log->ip_address ?? 'N/A' }}</p>
            </div>
            <div>
                <label class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">User Agent</label>
                <p class="text-sm text-gray-900 dark:text-gray-100 mt-1 truncate" title="{{ $log->user_agent ?? 'N/A' }}">{{ $log->user_agent ?? 'N/A' }}</p>
            </div>
        </div>
    </div>

    <!-- Changes -->
    @if($log->changes)
        <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
            <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Mudanças Registradas</h4>

            @php
                $changes = is_string($log->changes) ? json_decode($log->changes, true) : $log->changes;
            @endphp

            @if($changes && isset($changes['old']) && isset($changes['new']))
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-900">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Campo</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Valor Antigo</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Valor Novo</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($changes['old'] as $key => $oldValue)
                                @if(isset($changes['new'][$key]))
                                    <tr>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                            {{ ucfirst(str_replace('_', ' ', $key)) }}
                                        </td>
                                        <td class="px-4 py-3 text-sm text-red-600 dark:text-red-400">
                                            <span class="line-through">{{ $oldValue ?? 'null' }}</span>
                                        </td>
                                        <td class="px-4 py-3 text-sm text-green-600 dark:text-green-400 font-semibold">
                                            {{ $changes['new'][$key] ?? 'null' }}
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="bg-gray-900 dark:bg-gray-950 rounded-lg p-4 overflow-x-auto">
                    <pre class="text-xs text-green-400 font-mono">{{ json_encode($changes, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                </div>
            @endif
        </div>
    @endif

    <!-- Admin Notes -->
    @if(isset($changes['admin_notes']) && count($changes['admin_notes']) > 0)
        <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
            <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Notas do Administrador</h4>
            <div class="space-y-2">
                @foreach($changes['admin_notes'] as $note)
                    <div class="bg-purple-50 dark:bg-purple-900/20 border border-purple-200 dark:border-purple-800 rounded-lg p-3">
                        <p class="text-sm text-gray-900 dark:text-gray-100">{{ $note['note'] }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                            Por {{ $note['added_by'] }} em {{ \Carbon\Carbon::parse($note['added_at'])->format('d/m/Y H:i') }}
                        </p>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
