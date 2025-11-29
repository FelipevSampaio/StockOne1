<!-- Skeleton Table Loader -->
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 dark:bg-gray-900">
                <tr>
                    @for($i = 0; $i < ($columns ?? 5); $i++)
                        <th class="px-6 py-4">
                            <div class="h-3 bg-gray-200 dark:bg-gray-700 rounded w-20 animate-pulse"></div>
                        </th>
                    @endfor
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @for($row = 0; $row < ($rows ?? 5); $row++)
                    <tr>
                        @for($col = 0; $col < ($columns ?? 5); $col++)
                            <td class="px-6 py-4">
                                <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-{{ $col === 0 ? 'full' : '24' }} animate-pulse"></div>
                            </td>
                        @endfor
                    </tr>
                @endfor
            </tbody>
        </table>
    </div>
</div>
