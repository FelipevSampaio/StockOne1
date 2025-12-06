<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>StockOne • @yield('title', 'Painel')</title>
        @if (app()->environment('testing'))
            <style>
                :root {
                    font-family: 'Instrument Sans', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
                }
            </style>
        @else
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif
        <style>
            [x-cloak] { display: none !important; }
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
    <body class="bg-gray-50 dark:bg-gray-900 font-sans text-gray-900 dark:text-gray-100 transition-colors" x-data="appLayout()">
        <div class="flex h-screen overflow-hidden" x-data="{ sidebarOpen: false }" x-init="window.addEventListener('resize', () => { if(window.innerWidth >= 1024) sidebarOpen = false; });" x-cloak>
                 <!-- Sidebar -->
                 <aside class="fixed inset-y-0 left-0 z-40 w-64 bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 transform transition-all duration-300 ease-in-out lg:static lg:translate-x-0 lg:block"
                       :class="window.innerWidth >= 1024 ? 'translate-x-0' : (sidebarOpen ? 'translate-x-0' : '-translate-x-full')" x-transition>
                <!-- Logo -->
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-red-500 to-red-600 flex items-center justify-center shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                        <div>
                            <div class="text-xl font-bold text-gray-900 dark:text-white">StockOne</div>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Gestão de Restaurantes</p>
                        </div>
                    </div>
                </div>

                <!-- Navigation -->
                <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
                    @php
                        $menu = [
                            ['label' => 'Dashboard', 'route' => 'dashboard', 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
                            ['label' => 'Cardápio', 'route' => 'cardapio-itens.index', 'icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253'],
                            ['label' => 'Pedidos', 'route' => 'pedidos.index', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01'],
                            ['label' => 'Insumos', 'route' => 'insumos.index', 'icon' => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4'],
                            ['label' => 'Estoque', 'route' => 'estoque.index', 'icon' => 'M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4'],
                            ['label' => 'Receitas', 'route' => 'receitas.index', 'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
                            ['label' => 'Alertas', 'route' => 'alertas.index', 'icon' => 'M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9', 'badge' => true],
                            ['label' => 'Fila de Produção', 'route' => 'fila-producao.index', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
                            ['label' => 'Sugestões de Compras', 'route' => 'compras-sugestoes.index', 'icon' => 'M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z'],
                            ['label' => 'Menu Público', 'route' => 'public.menu', 'icon' => 'M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9'],
                        ];
                    @endphp

                    @foreach ($menu as $item)
                        @php
                            $active = request()->routeIs(str_replace('.index', '', $item['route']) . '*');
                            $alertCount = isset($item['badge']) && $item['badge'] ? App\Models\Alerta::where('resolvido', false)->whereHas('insumo', fn($q) => $q->where('restaurante_id', session('restaurante_id')))->count() : 0;
                        @endphp
                        <a href="{{ route($item['route']) }}"
                           class="group flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-200 relative
                                {{ $active
                                    ? 'bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 shadow-sm'
                                    : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700/50' }}">
                            <svg class="w-5 h-5 flex-shrink-0 transition-transform duration-200 {{ $active ? '' : 'group-hover:scale-110' }}"
                                 fill="none"
                                 stroke="currentColor"
                                 viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}"/>
                            </svg>
                            <span class="flex-1">{{ $item['label'] }}</span>
                            @if(isset($item['badge']) && $item['badge'] && $alertCount > 0)
                                <span class="flex items-center justify-center min-w-[20px] h-5 px-1.5 text-xs font-bold text-white bg-red-600 rounded-full">
                                    {{ $alertCount > 9 ? '9+' : $alertCount }}
                                </span>
                            @endif
                        </a>
                    @endforeach
                </nav>

                <!-- Footer -->
                <div class="p-4 border-t border-gray-200 dark:border-gray-700">
                    <div class="text-xs text-gray-500 dark:text-gray-400 text-center">
                        StockOne © {{ date('Y') }}
                    </div>
                </div>
            </aside>

            <!-- Overlay escuro em mobile -->
            <div x-show="sidebarOpen && window.innerWidth < 1024" class="fixed inset-0 z-30 bg-black/40 transition-opacity lg:hidden" @click="sidebarOpen = false" x-cloak></div>
            <!-- Botão para abrir menu lateral em telas pequenas -->
            <button @click="sidebarOpen = true" class="lg:hidden fixed top-4 left-4 z-50 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-2 shadow focus:outline-none">
                <svg class="w-6 h-6 text-gray-700 dark:text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
            <!-- Main Content -->
            <main class="flex-1 flex flex-col bg-gray-50 dark:bg-gray-900 lg:ml-64 overflow-hidden">
                <!-- Header -->
                <header class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 px-6 py-4 shadow-sm sticky top-0 z-10">
                    <div class="flex items-center justify-between gap-4">
                        <div class="flex-1">
                            <div class="flex items-center gap-3">
                                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">@yield('title', 'Painel')</h1>
                                @hasSection('subtitle')
                                    <span class="hidden sm:inline text-gray-400 dark:text-gray-600">•</span>
                                    <p class="hidden sm:inline text-sm text-gray-600 dark:text-gray-400">@yield('subtitle')</p>
                                @endif
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            @yield('actions')

                            <!-- Notificações -->
                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = !open"
                                        class="relative p-2 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                    </svg>
                                    @if(App\Models\Alerta::where('resolvido', false)->whereHas('insumo', fn($q) => $q->where('restaurante_id', session('restaurante_id')))->count() > 0)
                                        <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full ring-2 ring-white dark:ring-gray-800"></span>
                                    @endif
                                </button>

                                <div x-show="open"
                                     @click.away="open = false"
                                     x-transition:enter="transition ease-out duration-200"
                                     x-transition:enter-start="opacity-0 scale-95"
                                     x-transition:enter-end="opacity-100 scale-100"
                                     x-transition:leave="transition ease-in duration-150"
                                     x-transition:leave-start="opacity-100 scale-100"
                                     x-transition:leave-end="opacity-0 scale-95"
                                     class="absolute right-0 mt-2 w-80 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 py-2 z-50"
                                     style="display: none;">
                                    <div class="px-4 py-2 border-b border-gray-200 dark:border-gray-700">
                                        <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Notificações</h3>
                                    </div>
                                    <div class="max-h-96 overflow-y-auto">
                                        @php
                                            $notificacoes = App\Models\Alerta::with('insumo')
                                                ->where('resolvido', false)
                                                ->whereHas('insumo', fn($q) => $q->where('restaurante_id', session('restaurante_id')))
                                                ->orderBy('created_at', 'desc')
                                                ->limit(5)
                                                ->get();
                                        @endphp
                                        @forelse($notificacoes as $notificacao)
                                            <a href="{{ route('alertas.index') }}" class="block px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors border-b border-gray-100 dark:border-gray-700 last:border-0">
                                                <div class="flex gap-3">
                                                    <div class="flex-shrink-0 w-8 h-8 bg-red-100 dark:bg-red-900/30 rounded-full flex items-center justify-center">
                                                        <svg class="w-4 h-4 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                                        </svg>
                                                    </div>
                                                    <div class="flex-1 min-w-0">
                                                        <p class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ $notificacao->insumo->nome }}</p>
                                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ $notificacao->mensagem }}</p>
                                                        <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">{{ $notificacao->created_at->diffForHumans() }}</p>
                                                    </div>
                                                </div>
                                            </a>
                                        @empty
                                            <div class="px-4 py-8 text-center">
                                                <svg class="w-12 h-12 mx-auto text-gray-400 dark:text-gray-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                <p class="text-sm text-gray-500 dark:text-gray-400">Nenhuma notificação</p>
                                            </div>
                                        @endforelse
                                    </div>
                                    @if($notificacoes->count() > 0)
                                        <div class="px-4 py-2 border-t border-gray-200 dark:border-gray-700">
                                            <a href="{{ route('alertas.index') }}" class="text-xs font-medium text-red-600 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300">
                                                Ver todos os alertas →
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Dark Mode Toggle -->
                            <button @click="toggleDarkMode()"
                                    class="p-2 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors"
                                    title="Alternar modo escuro">
                                <!-- Ícone de lua (modo claro ativo) -->
                                <svg x-show="!document.documentElement.classList.contains('dark')" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                                </svg>
                                <!-- Ícone de sol (modo escuro ativo) -->
                                <svg x-show="document.documentElement.classList.contains('dark')" x-cloak class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                                </svg>
                            </button>

                            <!-- User Menu -->
                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = !open"
                                        class="flex items-center gap-2 px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
                                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-red-500 to-red-600 flex items-center justify-center text-white font-bold text-xs">
                                        {{ strtoupper(substr(session('restaurante_nome'), 0, 2)) }}
                                    </div>
                                    <span class="hidden md:inline">{{ session('restaurante_nome') }}</span>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>

                                <div x-show="open"
                                     @click.away="open = false"
                                     x-transition:enter="transition ease-out duration-200"
                                     x-transition:enter-start="opacity-0 scale-95"
                                     x-transition:enter-end="opacity-100 scale-100"
                                     x-transition:leave="transition ease-in duration-150"
                                     x-transition:leave-start="opacity-100 scale-100"
                                     x-transition:leave-end="opacity-0 scale-95"
                                     class="absolute right-0 mt-2 w-56 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 py-1 z-50"
                                     style="display: none;">
                                    <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ Auth::user()->name }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ Auth::user()->email }}</p>
                                    </div>
                                    <form action="{{ route('auth.logout') }}" method="POST" class="p-1">
                                        @csrf
                                        <button type="submit" class="w-full flex items-center gap-2 px-3 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-md transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                            </svg>
                                            Sair
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </header>

                <!-- Content -->
                <section class="flex-1 p-6 overflow-y-auto">
                    <!-- Flash Messages -->
                    @if (session('success'))
                        <div x-data="{ show: true }"
                             x-show="show"
                             x-init="setTimeout(() => show = false, 5000)"
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 transform scale-90"
                             x-transition:enter-end="opacity-100 transform scale-100"
                             x-transition:leave="transition ease-in duration-200"
                             x-transition:leave-start="opacity-100 transform scale-100"
                             x-transition:leave-end="opacity-0 transform scale-90"
                             class="mb-6 rounded-lg border border-green-200 dark:border-green-800 bg-green-50 dark:bg-green-900/20 px-4 py-3 text-sm text-green-800 dark:text-green-200 flex items-center justify-between shadow-sm">
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span>{{ session('success') }}</span>
                            </div>
                            <button @click="show = false" class="text-green-600 dark:text-green-400 hover:text-green-800 dark:hover:text-green-200">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div x-data="{ show: true }"
                             x-show="show"
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 transform scale-90"
                             x-transition:enter-end="opacity-100 transform scale-100"
                             class="mb-6 rounded-lg border border-red-200 dark:border-red-800 bg-red-50 dark:bg-red-900/20 px-4 py-3 text-sm text-red-800 dark:text-red-200 shadow-sm">
                            <div class="flex items-start justify-between gap-3">
                                <div class="flex-1">
                                    <div class="flex items-center gap-2 mb-2">
                                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <p class="font-semibold">Ops! Verifique os campos:</p>
                                    </div>
                                    <ul class="list-disc list-inside space-y-1 ml-7">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                                <button @click="show = false" class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-200">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    @endif

                    @yield('content')
                </section>
            </main>
        </div>

        <div id="confirm-overlay" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/50 px-4">
            <div id="confirm-modal" class="w-full max-w-md rounded-2xl bg-white p-6 shadow-2xl mx-auto lg:ml-64">
                <div class="mb-4">
                    <p class="text-xs uppercase tracking-[0.2em] text-red-500">Confirmação</p>
                    <h2 class="mt-1 text-2xl font-semibold text-gray-900">Tem certeza?</h2>
                </div>
                <p id="confirm-message" class="text-sm text-gray-600" data-default="Esta ação é irreversível.">
                    Esta ação é irreversível.
                </p>
                <div class="mt-6 flex flex-col gap-3 sm:flex-row sm:justify-end">
                    <button id="confirm-cancel" type="button" class="rounded-full border border-gray-200 px-5 py-2 text-sm font-semibold text-gray-600 transition hover:border-red-200 hover:text-red-600">
                        Cancelar
                    </button>
                    <button id="confirm-accept" type="button" class="rounded-full bg-red-600 px-5 py-2 text-sm font-semibold text-white shadow transition hover:bg-red-500">
                        Confirmar
                    </button>
                </div>
            </div>
        </div>

        <script>
            function appLayout() {
                return {
                    toggleDarkMode() {
                        if (document.documentElement.classList.contains('dark')) {
                            document.documentElement.classList.remove('dark');
                            localStorage.theme = 'light';
                        } else {
                            document.documentElement.classList.add('dark');
                            localStorage.theme = 'dark';
                        }
                    }
                }
            }

            document.addEventListener('DOMContentLoaded', () => {
                const overlay = document.getElementById('confirm-overlay');
                const messageEl = document.getElementById('confirm-message');
                const confirmBtn = document.getElementById('confirm-accept');
                const cancelBtn = document.getElementById('confirm-cancel');
                let pendingForm = null;

                const closeModal = () => {
                    overlay.classList.add('hidden');
                    pendingForm = null;
                };

                const openModal = (message, form) => {
                    pendingForm = form;
                    messageEl.textContent = message || messageEl.dataset.default;
                    overlay.classList.remove('hidden');
                    confirmBtn.focus();
                };

                confirmBtn.addEventListener('click', () => {
                    if (pendingForm) {
                        pendingForm.dataset.confirmed = 'true';
                        pendingForm.submit();
                    }
                    closeModal();
                });

                cancelBtn.addEventListener('click', closeModal);
                overlay.addEventListener('click', (event) => {
                    if (event.target === overlay) {
                        closeModal();
                    }
                });

                document.querySelectorAll('form[data-confirm]').forEach((form) => {
                    form.addEventListener('submit', (event) => {
                        if (form.dataset.confirmed === 'true') {
                            form.dataset.confirmed = '';
                            return;
                        }

                        event.preventDefault();
                        openModal(form.dataset.confirm, form);
                    });
                });

                document.querySelectorAll('[data-confirm-trigger]').forEach((trigger) => {
                    trigger.addEventListener('click', (event) => {
                        event.preventDefault();
                        const targetId = trigger.dataset.confirmTarget || trigger.getAttribute('form');
                        const form = document.getElementById(targetId);
                        if (!form) {
                            console.warn('Formulário não encontrado para confirmação:', targetId);
                            return;
                        }

                        openModal(trigger.dataset.confirm || form.dataset.confirm, form);
                    });
                });
            });
        </script>

        @yield('scripts')
    </body>
</html>

