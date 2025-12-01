<div id="logs-table-container" class="overflow-x-auto">
    <table class="w-full table-auto text-sm">
        <thead class="bg-gray-50 text-xs text-gray-600">
            <tr class="divide-x divide-gray-100">
                <th class="px-3 py-2 text-left"><span class="sr-only">Selecionar</span></th>
                <th class="px-3 py-2 text-left">Data</th>
                <th class="px-3 py-2 text-left">Usuário</th>
                <th class="px-3 py-2 text-left">Ação</th>
                <th class="px-3 py-2 text-left">Modelo</th>
                <th class="px-3 py-2 text-left">IP</th>
                <th class="px-3 py-2 text-left">Mudanças</th>
                <th class="px-3 py-2 text-right">Ações</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($logs as $log)
                @php
                    $actionColors = [
                        'create' => 'bg-green-100 text-green-800',
                        'update' => 'bg-blue-100 text-blue-800',
                        'delete' => 'bg-yellow-100 text-yellow-800',
                        'restore' => 'bg-purple-100 text-purple-800',
                        'force_delete' => 'bg-red-100 text-red-800',
                        'login' => 'bg-indigo-100 text-indigo-800',
                        'logout' => 'bg-gray-100 text-gray-800',
                    ];
                    $actionLabels = [
                        'create' => 'Criação',
                        'update' => 'Atualização',
                        'delete' => 'Desativação',
                        'restore' => 'Reativação',
                        'force_delete' => 'Deleção Perm.',
                        'login' => 'Login',
                        'logout' => 'Logout',
                    ];
                @endphp
                <tr class="hover:bg-gray-50" data-id="{{ $log->id }}">
                    <td class="px-3 py-2">
                        <input type="checkbox" class="rowCheckbox h-4 w-4 text-red-600 border-gray-300 rounded" value="{{ $log->id }}" aria-label="Selecionar log {{ $log->id }}">
                    </td>
                    <td class="px-3 py-2 whitespace-nowrap">{{ $log->created_at->format('d/m/Y H:i') }}</td>
                    <td class="px-3 py-2 font-medium">{{ $log->user?->name ?? 'Sistema' }}</td>
                    <td class="px-3 py-2">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $actionColors[$log->action] ?? 'bg-gray-100 text-gray-800' }}">
                            {{ $actionLabels[$log->action] ?? $log->action }}
                        </span>
                    </td>
                    <td class="px-3 py-2">{{ $log->model }} #{{ $log->model_id }}</td>
                    <td class="px-3 py-2 text-xs text-gray-600">{{ $log->ip_address }}</td>
                    <td class="px-3 py-2">
                        @if($log->changes)
                            <button class="text-blue-600 hover:underline text-sm expand-json" data-target="json-{{ $log->id }}">Expandir JSON</button>
                            <pre id="json-{{ $log->id }}" class="mt-2 p-2 bg-gray-50 rounded text-xs hidden">{{ json_encode($log->changes, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                        @else
                            <span class="text-gray-400">—</span>
                        @endif
                    </td>
                    <td class="px-3 py-2 text-right">
                        <div class="relative inline-block text-left">
                            <button class="inline-flex justify-center w-full px-2 py-1 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 rounded" aria-haspopup="true">•••</button>
                            <div class="origin-top-right absolute right-0 mt-2 w-40 rounded-md shadow-lg bg-white dark:bg-gray-800 ring-1 ring-black dark:ring-gray-700 ring-opacity-5 hidden" role="menu">
                                <div class="py-1">
                                    <a href="{{ route('admin.audit-logs.show', $log->id) }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">Ver</a>
                                    <a href="{{ route('admin.audit-logs.edit', $log->id) }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">Editar</a>
                                    <button data-id="{{ $log->id }}" class="block w-full text-left px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-gray-50 dark:hover:bg-gray-700 delete-btn">Apagar</button>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="px-6 py-8 text-center text-gray-500">Nenhum log encontrado</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="px-4 py-3 border-t border-gray-100 bg-gray-50">
    <div id="pagination" class="flex items-center justify-between">
        <div>
            {{ $logs->links() }}
        </div>
        <div class="text-sm text-gray-600">Página {{ $logs->currentPage() ?? 1 }}</div>
    </div>
</div>
