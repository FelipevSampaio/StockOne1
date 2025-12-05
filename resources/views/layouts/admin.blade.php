<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') - StockOne</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        [x-cloak] { display: none !important; }

        /* Skeleton Loader Animation */
        @keyframes shimmer {
            0% { background-position: -1000px 0; }
            100% { background-position: 1000px 0; }
        }
        .skeleton {
            animation: shimmer 2s infinite linear;
            background: linear-gradient(to right, #f0f0f0 4%, #e0e0e0 25%, #f0f0f0 36%);
            background-size: 1000px 100%;
        }
        .dark .skeleton {
            background: linear-gradient(to right, #374151 4%, #4b5563 25%, #374151 36%);
            background-size: 1000px 100%;
        }

        /* Smooth Checkbox Animations */
        input[type="checkbox"] {
            transition: all 0.2s ease-in-out;
        }

        input[type="checkbox"]:checked {
            animation: checkboxPulse 0.3s ease-out;
        }

        @keyframes checkboxPulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }

        /* Row Selection Highlight */
        tr.selected-row {
            background-color: rgba(239, 68, 68, 0.05);
            transition: background-color 0.2s ease;
        }

        .dark tr.selected-row {
            background-color: rgba(239, 68, 68, 0.1);
        }

        /* Bulk Actions Bar Animation */
        .bulk-actions-bar {
            animation: slideInFromTop 0.3s ease-out;
        }

        @keyframes slideInFromTop {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Button Ripple Effect */
        .btn-ripple {
            position: relative;
            overflow: hidden;
        }

        .btn-ripple::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }

        .btn-ripple:active::after {
            width: 300px;
            height: 300px;
        }
    </style>
    <script>
        // Dark Mode: Carregar preferência antes do render
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-50 dark:bg-gray-900 font-sans antialiased transition-colors duration-200"
      x-data="{
          sidebarOpen: false,
          showToast: false,
          toastMessage: '',
          toastType: 'success',
          searchOpen: false,
          get darkMode() {
              return document.documentElement.classList.contains('dark');
          },
          set darkMode(value) {
              if (value) {
                  document.documentElement.classList.add('dark');
                  localStorage.theme = 'dark';
              } else {
                  document.documentElement.classList.remove('dark');
                  localStorage.theme = 'light';
              }
          },
          toggleDark() {
              if (document.documentElement.classList.contains('dark')) {
                  document.documentElement.classList.remove('dark');
                  localStorage.theme = 'light';
              } else {
                  document.documentElement.classList.add('dark');
                  localStorage.theme = 'dark';
              }
          }
      }"
      @keydown.ctrl.k.window.prevent="searchOpen = true"
      @keydown.escape.window="searchOpen = false; sidebarOpen = false">

    <!-- Toast Notifications -->
    <div x-show="showToast"
         x-cloak
         @click="showToast = false"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed top-4 right-4 z-50 max-w-sm cursor-pointer">
        <div :class="{
            'bg-green-50 border-green-400 text-green-800 dark:bg-green-900 dark:border-green-600 dark:text-green-100': toastType === 'success',
            'bg-red-50 border-red-400 text-red-800 dark:bg-red-900 dark:border-red-600 dark:text-red-100': toastType === 'error',
            'bg-blue-50 border-blue-400 text-blue-800 dark:bg-blue-900 dark:border-blue-600 dark:text-blue-100': toastType === 'info'
        }" class="flex items-center p-4 rounded-lg shadow-lg border-l-4">
            <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            <span x-text="toastMessage"></span>
        </div>
    </div>

    <!-- Global Search Modal -->
    <div x-show="searchOpen"
         x-cloak
         @click="searchOpen = false"
         class="fixed inset-0 z-50 overflow-y-auto bg-gray-900/50 backdrop-blur-sm"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100">
        <div class="flex min-h-screen items-start justify-center p-4 pt-16">
            <div @click.stop class="w-full max-w-2xl bg-white dark:bg-gray-800 rounded-xl shadow-2xl"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100">
                <div class="p-4 border-b border-gray-200 dark:border-gray-700"
                     x-data="{
                         searchQuery: '',
                         searchResults: { users: [], restaurantes: [], pages: [] },
                         isSearching: false,
                         async performSearch() {
                             if (this.searchQuery.length < 2) {
                                 this.searchResults = { users: [], restaurantes: [], pages: [] };
                                 return;
                             }
                             this.isSearching = true;
                             try {
                                 const response = await fetch(`/admin/search?q=${encodeURIComponent(this.searchQuery)}`);
                                 this.searchResults = await response.json();
                             } catch (error) {
                                 console.error('Erro na busca:', error);
                             } finally {
                                 this.isSearching = false;
                             }
                         }
                     }">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <input type="text"
                               x-model="searchQuery"
                               @input.debounce.300ms="performSearch()"
                               placeholder="Buscar usuários, restaurantes, configurações... (Ctrl+K)"
                               class="flex-1 ml-3 bg-transparent border-0 text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 focus:ring-0 text-sm"
                               x-ref="searchInput"
                               @keydown.escape="searchOpen = false">
                        <svg x-show="isSearching" class="animate-spin h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>

                    <!-- Resultados da busca -->
                    <div x-show="searchQuery.length >= 2" x-cloak class="mt-4 max-h-96 overflow-y-auto">
                        <!-- Usuários -->
                        <template x-if="searchResults.users && searchResults.users.length > 0">
                            <div class="mb-4">
                                <div class="px-2 py-1 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Usuários</div>
                                <template x-for="user in searchResults.users" :key="user.id">
                                    <a :href="`/admin/users/${user.id}/edit`" class="flex items-center px-3 py-2 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg transition-colors">
                                        <div class="flex-shrink-0 w-8 h-8 bg-blue-50 dark:bg-blue-900/20 rounded-full flex items-center justify-center">
                                            <span class="text-xs font-medium text-blue-600 dark:text-blue-400" x-text="user.name.charAt(0).toUpperCase()"></span>
                                        </div>
                                        <div class="ml-3 flex-1 min-w-0">
                                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100 truncate" x-text="user.name"></p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 truncate" x-text="user.email"></p>
                                        </div>
                                    </a>
                                </template>
                            </div>
                        </template>

                        <!-- Restaurantes -->
                        <template x-if="searchResults.restaurantes && searchResults.restaurantes.length > 0">
                            <div class="mb-4">
                                <div class="px-2 py-1 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Restaurantes</div>
                                <template x-for="rest in searchResults.restaurantes" :key="rest.id">
                                    <a :href="`/admin/restaurantes/${rest.id}/edit`" class="flex items-center px-3 py-2 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg transition-colors">
                                        <div class="flex-shrink-0 w-8 h-8 bg-red-50 dark:bg-red-900/20 rounded-lg flex items-center justify-center">
                                            <svg class="w-4 h-4 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                            </svg>
                                        </div>
                                        <div class="ml-3 flex-1 min-w-0">
                                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100 truncate" x-text="rest.nome"></p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 truncate" x-text="rest.cnpj"></p>
                                        </div>
                                    </a>
                                </template>
                            </div>
                        </template>

                        <!-- Nenhum resultado -->
                        <template x-if="!isSearching && searchQuery.length >= 2 && searchResults.users.length === 0 && searchResults.restaurantes.length === 0">
                            <div class="text-center py-8">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Nenhum resultado encontrado</p>
                            </div>
                        </template>
                    </div>

                    <!-- Páginas padrão (quando não há busca) -->
                    <div x-show="searchQuery.length < 2" x-cloak class="mt-4">
                        <div class="px-2 py-1 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Páginas</div>
                    </div>
                </div>
                <div class="max-h-96 overflow-y-auto p-2" x-show="searchQuery.length < 2" x-cloak>
                    <a href="{{ route('admin.users.index') }}" class="flex items-center px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg transition-colors group">
                        <div class="flex-shrink-0 w-10 h-10 bg-red-50 dark:bg-red-900/20 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                        </div>
                        <div class="ml-4 flex-1">
                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100">Usuários</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Gerenciar usuários do sistema</p>
                        </div>
                        <svg class="w-4 h-4 text-gray-400 opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                    <a href="{{ route('admin.restaurantes.index') }}" class="flex items-center px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg transition-colors group">
                        <div class="flex-shrink-0 w-10 h-10 bg-red-50 dark:bg-red-900/20 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                        <div class="ml-4 flex-1">
                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100">Restaurantes</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Gerenciar restaurantes cadastrados</p>
                        </div>
                        <svg class="w-4 h-4 text-gray-400 opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                    <a href="{{ route('admin.audit-logs.index') }}" class="flex items-center px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg transition-colors group">
                        <div class="flex-shrink-0 w-10 h-10 bg-red-50 dark:bg-red-900/20 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <div class="ml-4 flex-1">
                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100">Logs de Auditoria</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Ver histórico de ações</p>
                        </div>
                        <svg class="w-4 h-4 text-gray-400 opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>
                <div class="p-3 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50 rounded-b-xl">
                    <div class="flex items-center justify-between text-xs text-gray-500 dark:text-gray-400">
                        <span>Use <kbd class="px-2 py-1 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded text-xs">↑↓</kbd> para navegar</span>
                        <span><kbd class="px-2 py-1 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded text-xs">ESC</kbd> para fechar</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside class="fixed inset-y-0 left-0 z-40 w-64 bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 transform transition-all duration-300 ease-in-out lg:translate-x-0"
               :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">

            <!-- Logo -->
            <div class="flex items-center justify-between h-16 px-6 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center">
                    <span class="text-2xl font-bold text-red-600 dark:text-red-500">Stock</span>
                    <span class="text-2xl font-light text-gray-800 dark:text-gray-200">One</span>
                </div>
                <button @click="sidebarOpen = false" class="lg:hidden text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto">
                <a href="{{ route('admin.dashboard') }}"
                   class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    Dashboard
                </a>

                <a href="{{ route('admin.users.index') }}"
                   class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.users.*') ? 'bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    Usuários
                </a>

                <a href="{{ route('admin.restaurantes.index') }}"
                   class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.restaurantes.*') ? 'bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    Restaurantes
                </a>

                <a href="{{ route('admin.audit-logs.index') }}"
                   class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.audit-logs.*') ? 'bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Logs de Auditoria
                </a>

                <a href="{{ route('admin.settings') }}"
                   class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.settings') ? 'bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    Configurações
                </a>
            </nav>

            <!-- User Info -->
            <div class="absolute bottom-0 left-0 right-0 p-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 rounded-full bg-red-100 dark:bg-red-900/30 flex items-center justify-center">
                            <span class="text-red-600 dark:text-red-400 font-semibold text-sm">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</span>
                        </div>
                    </div>
                    <div class="ml-3 flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 dark:text-gray-100 truncate">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ auth()->user()->email }}</p>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col lg:ml-64 overflow-hidden">
            <!-- Topbar -->
            <header class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 h-16 flex items-center justify-between px-6 sticky top-0 z-30 transition-colors duration-200">
                <!-- Mobile Menu Button -->
                <button @click="sidebarOpen = true" class="lg:hidden text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>

                <!-- Page Title -->
                <h1 class="text-xl font-semibold text-gray-800 dark:text-gray-100 hidden lg:block">@yield('page-title', 'Dashboard')</h1>

                <!-- Right Actions -->
                <div class="flex items-center space-x-3">
                    @yield('topbar-actions')

                    <!-- Search Button -->
                    <button @click="searchOpen = !searchOpen" class="p-2 rounded-lg text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition-all hover:scale-110" title="Buscar (Ctrl+K)">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </button>

                    <!-- Notifications -->
                    <div x-data="notificationManager()" x-init="init()" class="relative">
                        <button @click="toggleDropdown()" class="relative p-2 rounded-lg text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition-all hover:scale-110" title="Notificações">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                            </svg>
                            <span x-show="unreadCount > 0" x-text="unreadCount" class="absolute top-0 right-0 inline-flex items-center justify-center px-1.5 py-0.5 text-xs font-bold leading-none text-white bg-red-500 rounded-full"></span>
                        </button>

                        <!-- Dropdown Notifications -->
                        <div x-show="open"
                             @click.away="open = false"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95"
                             x-cloak
                             class="absolute right-0 mt-2 w-96 bg-white dark:bg-gray-800 rounded-lg shadow-xl border border-gray-200 dark:border-gray-700 z-50">
                            <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                                <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100">Notificações</h3>
                                <button @click="markAllAsRead()" class="text-xs text-red-600 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300 font-medium">
                                    Marcar todas como lidas
                                </button>
                            </div>
                            <div class="max-h-96 overflow-y-auto">
                                <template x-if="loading">
                                    <div class="p-8 text-center">
                                        <svg class="animate-spin h-8 w-8 mx-auto text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                    </div>
                                </template>

                                <template x-if="!loading && notifications.length === 0">
                                    <div class="p-8 text-center">
                                        <svg class="w-12 h-12 mx-auto text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                        </svg>
                                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Nenhuma notificação</p>
                                    </div>
                                </template>

                                <template x-for="notification in notifications" :key="notification.id">
                                    <div @click="markAsRead(notification.id)"
                                         :class="notification.read_at ? 'bg-transparent' : 'bg-blue-50 dark:bg-blue-900/10'"
                                         class="flex items-start p-4 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors border-b border-gray-100 dark:border-gray-700 cursor-pointer">
                                        <div class="flex-shrink-0">
                                            <div :class="getNotificationColor(notification.type)" class="w-10 h-10 rounded-full flex items-center justify-center">
                                                <svg x-html="getNotificationIcon(notification.type)" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"></svg>
                                            </div>
                                        </div>
                                        <div class="ml-3 flex-1">
                                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100" x-text="notification.title"></p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1" x-text="notification.message"></p>
                                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-1" x-text="notification.time_ago"></p>
                                        </div>
                                        <button x-show="!notification.read_at" class="ml-2 text-blue-600 dark:text-blue-400">
                                            <svg class="w-2 h-2" fill="currentColor" viewBox="0 0 8 8">
                                                <circle cx="4" cy="4" r="3"/>
                                            </svg>
                                        </button>
                                    </div>
                                </template>
                            </div>
                            <div class="p-3 border-t border-gray-200 dark:border-gray-700 flex items-center justify-between">
                                <label class="flex items-center text-xs text-gray-600 dark:text-gray-400">
                                    <input type="checkbox" x-model="soundEnabled" @change="toggleSound()" class="mr-2 rounded border-gray-300 text-red-600 focus:ring-red-500">
                                    Som de notificações
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Dark Mode Toggle -->
                    <button @click="toggleDark()" class="relative group p-2 rounded-lg text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition-all hover:scale-110" title="Alternar modo escuro">
                        <!-- Ícone de lua (modo claro ativo) -->
                        <svg x-show="!document.documentElement.classList.contains('dark')" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                        </svg>
                        <!-- Ícone de sol (modo escuro ativo) -->
                        <svg x-show="document.documentElement.classList.contains('dark')" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </button>

                    <form method="POST" action="{{ route('auth.logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="flex items-center px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-red-600 dark:hover:text-red-400 transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                            Sair
                        </button>
                    </form>
                </div>
            </header>            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto p-6">
                @if (session('success'))
                    <script>
                        document.addEventListener('alpine:initialized', () => {
                            Alpine.store('toast', { message: "{{ session('success') }}", type: 'success' });
                            setTimeout(() => {
                                const event = new CustomEvent('show-toast', {
                                    detail: { message: "{{ session('success') }}", type: 'success' }
                                });
                                window.dispatchEvent(event);
                            }, 100);
                        });
                    </script>
                @endif

                @if (session('error'))
                    <script>
                        document.addEventListener('alpine:initialized', () => {
                            setTimeout(() => {
                                const event = new CustomEvent('show-toast', {
                                    detail: { message: "{{ session('error') }}", type: 'error' }
                                });
                                window.dispatchEvent(event);
                            }, 100);
                        });
                    </script>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <!-- Toast Notification System -->
    <div x-data="toastManager()"
         @show-toast.window="showToast($event.detail)"
         class="fixed top-4 right-4 z-[9999] space-y-2">
        <template x-for="(toast, index) in toasts" :key="toast.id">
            <div x-show="toast.visible"
                 x-transition:enter="transition ease-out duration-300 transform"
                 x-transition:enter-start="translate-x-full opacity-0"
                 x-transition:enter-end="translate-x-0 opacity-100"
                 x-transition:leave="transition ease-in duration-200 transform"
                 x-transition:leave-start="translate-x-0 opacity-100"
                 x-transition:leave-end="translate-x-full opacity-0"
                 class="flex items-center gap-3 px-4 py-3 rounded-lg shadow-lg max-w-sm border backdrop-blur-sm"
                 :class="{
                     'bg-green-50 dark:bg-green-900/30 border-green-200 dark:border-green-800': toast.type === 'success',
                     'bg-red-50 dark:bg-red-900/30 border-red-200 dark:border-red-800': toast.type === 'error',
                     'bg-blue-50 dark:bg-blue-900/30 border-blue-200 dark:border-blue-800': toast.type === 'info',
                     'bg-yellow-50 dark:bg-yellow-900/30 border-yellow-200 dark:border-yellow-800': toast.type === 'warning'
                 }">
                <div class="flex-shrink-0">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                         :class="{
                             'text-green-600 dark:text-green-400': toast.type === 'success',
                             'text-red-600 dark:text-red-400': toast.type === 'error',
                             'text-blue-600 dark:text-blue-400': toast.type === 'info',
                             'text-yellow-600 dark:text-yellow-400': toast.type === 'warning'
                         }">
                        <template x-if="toast.type === 'success'">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </template>
                        <template x-if="toast.type === 'error'">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </template>
                        <template x-if="toast.type === 'info'">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </template>
                        <template x-if="toast.type === 'warning'">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </template>
                    </svg>
                </div>
                <div class="flex-1 text-sm font-medium"
                     :class="{
                         'text-green-900 dark:text-green-100': toast.type === 'success',
                         'text-red-900 dark:text-red-100': toast.type === 'error',
                         'text-blue-900 dark:text-blue-100': toast.type === 'info',
                         'text-yellow-900 dark:text-yellow-100': toast.type === 'warning'
                     }"
                     x-text="toast.message"></div>
                <button @click="removeToast(toast.id)"
                        class="flex-shrink-0 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition-colors">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                </button>
            </div>
        </template>
    </div>

    <!-- Confirmation Modal -->
    <div x-data="confirmModal()"
         @show-confirm.window="showConfirm($event.detail)"
         x-show="visible"
         x-cloak
         class="fixed inset-0 z-[9998] overflow-y-auto"
         @keydown.escape.window="cancel()">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <!-- Overlay -->
            <div x-show="visible"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 @click="cancel()"
                 class="fixed inset-0 transition-opacity bg-gray-500 dark:bg-gray-900 bg-opacity-75 dark:bg-opacity-80"></div>

            <!-- Modal -->
            <div x-show="visible"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="inline-block w-full max-w-md p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white dark:bg-gray-800 shadow-xl rounded-2xl">

                <div class="flex items-start gap-4">
                    <div class="flex-shrink-0 flex items-center justify-center w-12 h-12 rounded-full"
                         :class="{
                             'bg-red-100 dark:bg-red-900/30': type === 'danger',
                             'bg-yellow-100 dark:bg-yellow-900/30': type === 'warning',
                             'bg-blue-100 dark:bg-blue-900/30': type === 'info'
                         }">
                        <svg class="w-6 h-6"
                             :class="{
                                 'text-red-600 dark:text-red-400': type === 'danger',
                                 'text-yellow-600 dark:text-yellow-400': type === 'warning',
                                 'text-blue-600 dark:text-blue-400': type === 'info'
                             }"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <template x-if="type === 'danger'">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </template>
                            <template x-if="type === 'warning'">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </template>
                            <template x-if="type === 'info'">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </template>
                        </svg>
                    </div>

                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2" x-text="title"></h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400" x-text="message"></p>
                    </div>
                </div>

                <div class="flex gap-3 mt-6">
                    <button @click="cancel()"
                            class="flex-1 px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors"
                            x-text="cancelText"></button>
                    <button @click="confirm()"
                            class="flex-1 px-4 py-2 text-sm font-medium text-white rounded-lg transition-colors"
                            :class="{
                                'bg-red-600 hover:bg-red-700': type === 'danger',
                                'bg-yellow-600 hover:bg-yellow-700': type === 'warning',
                                'bg-blue-600 hover:bg-blue-700': type === 'info'
                            }"
                            x-text="confirmText"></button>
                </div>
            </div>
        </div>
    </div>

    <!-- Global Loading Overlay -->
    <div x-data="{ loading: false }"
         @show-loading.window="loading = true"
         @hide-loading.window="loading = false"
         x-show="loading"
         x-cloak
         class="fixed inset-0 z-[9997] flex items-center justify-center bg-gray-900/50 dark:bg-gray-950/70 backdrop-blur-sm">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl p-8 flex flex-col items-center gap-4">
            <svg class="animate-spin h-12 w-12 text-red-600" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Processando...</p>
        </div>
    </div>

    <!-- Mobile Sidebar Overlay -->
    <div x-show="sidebarOpen"
         x-cloak
         @click="sidebarOpen = false"
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-gray-900 dark:bg-black bg-opacity-50 dark:bg-opacity-70 z-30 lg:hidden"></div>    <script>
        // Toast System
        window.addEventListener('show-toast', (event) => {
            const { message, type } = event.detail;
            Alpine.store('toast', { message, type });

            // Find the Alpine component
            const body = document.querySelector('body');
            const alpineData = Alpine.$data(body);
            alpineData.toastMessage = message;
            alpineData.toastType = type || 'success';
            alpineData.showToast = true;

            setTimeout(() => {
                alpineData.showToast = false;
            }, 4000);
        });

        // Modal System
        function confirmDelete(message = 'Tem certeza que deseja deletar?') {
            return confirm(message);
        }

        // Notification Manager Component
        document.addEventListener('alpine:init', () => {
            Alpine.data('notificationManager', () => ({
                open: false,
                notifications: [],
                unreadCount: 0,
                loading: false,
                soundEnabled: localStorage.getItem('notification_sound') === 'true',
                pollInterval: null,
                lastNotificationId: 0,

                init() {
                    this.fetchNotifications();
                    // Poll a cada 30 segundos
                    this.pollInterval = setInterval(() => {
                        this.fetchNotifications();
                    }, 30000);
                },

                async fetchNotifications() {
                    try {
                        const response = await fetch('/admin/notifications');
                        const data = await response.json();

                        // Verificar novas notificações
                        if (data.notifications.length > 0) {
                            const newestId = data.notifications[0].id;
                            if (newestId > this.lastNotificationId && this.lastNotificationId !== 0) {
                                this.playSound();
                                this.showNewNotificationToast(data.notifications[0]);
                            }
                            this.lastNotificationId = newestId;
                        }

                        this.notifications = data.notifications;
                        this.unreadCount = data.unread_count;
                    } catch (error) {
                        console.error('Erro ao buscar notificações:', error);
                    }
                },

                toggleDropdown() {
                    this.open = !this.open;
                    if (this.open) {
                        this.fetchNotifications();
                    }
                },

                async markAsRead(notificationId) {
                    try {
                        await fetch(`/admin/notifications/${notificationId}/read`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            }
                        });
                        this.fetchNotifications();
                    } catch (error) {
                        console.error('Erro ao marcar notificação como lida:', error);
                    }
                },

                async markAllAsRead() {
                    try {
                        await fetch('/admin/notifications/mark-all-read', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            }
                        });
                        this.fetchNotifications();
                    } catch (error) {
                        console.error('Erro ao marcar todas como lidas:', error);
                    }
                },

                toggleSound() {
                    localStorage.setItem('notification_sound', this.soundEnabled);
                },

                playSound() {
                    if (this.soundEnabled) {
                        // Criar um som simples usando Web Audio API
                        const audioContext = new (window.AudioContext || window.webkitAudioContext)();
                        const oscillator = audioContext.createOscillator();
                        const gainNode = audioContext.createGain();

                        oscillator.connect(gainNode);
                        gainNode.connect(audioContext.destination);

                        oscillator.frequency.value = 800;
                        oscillator.type = 'sine';

                        gainNode.gain.setValueAtTime(0.3, audioContext.currentTime);
                        gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.5);

                        oscillator.start(audioContext.currentTime);
                        oscillator.stop(audioContext.currentTime + 0.5);
                    }
                },

                showNewNotificationToast(notification) {
                    window.dispatchEvent(new CustomEvent('show-toast', {
                        detail: { message: notification.title, type: 'info' }
                    }));
                },

                getNotificationIcon(type) {
                    const icons = {
                        'user_created': '<path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6zM16 7a1 1 0 10-2 0v1h-1a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V7z"></path>',
                        'user_updated': '<path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>',
                        'user_deleted': '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>',
                        'system': '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>',
                        'warning': '<path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>',
                        'default': '<path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"></path>'
                    };
                    return icons[type] || icons['default'];
                },

                getNotificationColor(type) {
                    const colors = {
                        'user_created': 'bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400',
                        'user_updated': 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-600 dark:text-yellow-400',
                        'user_deleted': 'bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400',
                        'system': 'bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400',
                        'warning': 'bg-orange-100 dark:bg-orange-900/30 text-orange-600 dark:text-orange-400',
                        'default': 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400'
                    };
                    return colors[type] || colors['default'];
                }
            }));

            // Toast Manager
            Alpine.data('toastManager', () => ({
                toasts: [],
                nextId: 1,

                showToast({ message, type = 'success', duration = 4000 }) {
                    const id = this.nextId++;
                    const toast = { id, message, type, visible: true };
                    this.toasts.push(toast);

                    setTimeout(() => {
                        this.removeToast(id);
                    }, duration);
                },

                removeToast(id) {
                    const index = this.toasts.findIndex(t => t.id === id);
                    if (index > -1) {
                        this.toasts[index].visible = false;
                        setTimeout(() => {
                            this.toasts.splice(index, 1);
                        }, 300);
                    }
                }
            }));

            // Confirmation Modal
            Alpine.data('confirmModal', () => ({
                visible: false,
                title: '',
                message: '',
                type: 'warning',
                confirmText: 'Confirmar',
                cancelText: 'Cancelar',
                onConfirm: null,
                onCancel: null,

                showConfirm({ title, message, type = 'warning', confirmText = 'Confirmar', cancelText = 'Cancelar', onConfirm, onCancel }) {
                    console.log('Modal showConfirm chamado', { title, message, onConfirm: typeof onConfirm });
                    this.title = title;
                    this.message = message;
                    this.type = type;
                    this.confirmText = confirmText;
                    this.cancelText = cancelText;
                    this.onConfirm = onConfirm;
                    this.onCancel = onCancel;
                    this.visible = true;
                },

                confirm() {
                    console.log('Modal confirm() chamado, onConfirm:', typeof this.onConfirm);
                    this.visible = false;
                    if (this.onConfirm && typeof this.onConfirm === 'function') {
                        console.log('Executando onConfirm...');
                        this.onConfirm();
                    } else {
                        console.error('onConfirm não é uma função válida:', this.onConfirm);
                    }
                },

                cancel() {
                    this.visible = false;
                    if (this.onCancel) {
                        this.onCancel();
                    }
                }
            }));
        });
    </script>

    @yield('scripts')
</body>
</html>
