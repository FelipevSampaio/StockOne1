@props(['user', 'showCheckbox' => true])

<div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-100 dark:border-gray-700 p-6 hover:shadow-md transition-shadow {{ $user->trashed() ? 'bg-red-50/30 dark:bg-red-900/10' : '' }}">
    <!-- Checkbox e Badge -->
    <div class="flex items-start justify-between mb-4">
        @if($showCheckbox)
        <input type="checkbox"
               :checked="selectedUsers.includes({{ $user->id }})"
               @change="toggleUser({{ $user->id }})"
               class="rounded border-gray-300 dark:border-gray-600 text-red-600 focus:ring-red-500 dark:bg-gray-700 mt-1 cursor-pointer">
        @endif

        <div class="flex gap-1 ml-auto">
            @if($user->created_at >= now()->subDays(7))
            <span class="px-2 py-0.5 text-xs font-medium bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 rounded-full">Novo</span>
            @endif

            @if($user->isOnline())
            <span class="relative inline-flex items-center px-2 py-0.5 text-xs font-medium bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 rounded-full">
                <span class="absolute left-1 h-2 w-2 rounded-full bg-green-500 animate-pulse"></span>
                <span class="ml-3">Online</span>
            </span>
            @endif
        </div>
    </div>

    <!-- Avatar e Nome -->
    <div class="text-center mb-4">
        <div class="relative mx-auto h-20 w-20 mb-3">
            <div class="h-full w-full rounded-full {{ $user->trashed() ? 'bg-gray-200 dark:bg-gray-700' : 'bg-red-100 dark:bg-red-900/30' }} flex items-center justify-center">
                <span class="{{ $user->trashed() ? 'text-gray-500 dark:text-gray-400' : 'text-red-600 dark:text-red-400' }} font-semibold text-2xl">
                    {{ $user->avatar_initials }}
                </span>
            </div>

            @php
                $presenceStatus = $user->getPresenceStatus();
            @endphp

            @if($presenceStatus !== 'never')
            <span class="absolute bottom-0 right-0 h-4 w-4 rounded-full border-2 border-white dark:border-gray-800
                {{ $presenceStatus === 'online' ? 'bg-green-500' : ($presenceStatus === 'away' ? 'bg-yellow-500' : 'bg-gray-400') }}">
            </span>
            @endif
        </div>

        <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100">{{ $user->name }}</h3>
        <p class="text-sm text-gray-500 dark:text-gray-400 truncate">{{ $user->email }}</p>
    </div>

    <!-- Informações -->
    <div class="space-y-2 mb-4">
        @if($user->restaurante)
        <div class="flex items-center text-xs text-gray-600 dark:text-gray-400">
            <svg class="w-4 h-4 mr-2 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
            </svg>
            <div class="flex items-center gap-1.5 flex-1 min-w-0">
                <span class="truncate">{{ $user->restaurante->nome }}</span>
                <span class="flex-shrink-0 h-2 w-2 rounded-full {{ $user->restaurante->status === 'ativo' ? 'bg-green-500' : 'bg-red-500' }}"
                      title="Restaurante {{ $user->restaurante->status === 'ativo' ? 'ativo' : 'desativado' }}"></span>
            </div>
        </div>
        @endif

        @if($user->last_login_at)
        <div class="flex items-center text-xs text-gray-600 dark:text-gray-400">
            <svg class="w-4 h-4 mr-2 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span class="truncate" title="Último login: {{ $user->last_login_at->format('d/m/Y H:i') }}">
                Login {{ $user->last_login_at->diffForHumans() }}
            </span>
        </div>
        @endif

        <div class="flex items-center justify-between pt-2 border-t border-gray-100 dark:border-gray-700">
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $user->isAdmin() ? 'bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-400' : 'bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-400' }}">
                {{ $user->isAdmin() ? 'Admin' : 'Usuário' }}
            </span>

            @if($user->trashed())
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-400">
                Inativo
            </span>
            @else
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-400">
                Ativo
            </span>
            @endif
        </div>

        <div class="text-xs text-gray-500 dark:text-gray-400 pt-1">
            Criado {{ $user->created_at->diffForHumans() }}
        </div>
    </div>

    <!-- Ações -->
    <div class="flex gap-2">
        <button @click="$dispatch('open-quick-view', { userId: {{ $user->id }} })"
                class="flex-1 inline-flex items-center justify-center px-3 py-2 text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
            </svg>
            Ver
        </button>

        @if(!$user->trashed())
        <a href="{{ route('admin.users.edit', $user) }}"
           class="flex-1 inline-flex items-center justify-center px-3 py-2 text-xs font-medium bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
            Editar
        </a>
        @endif
    </div>
</div>
