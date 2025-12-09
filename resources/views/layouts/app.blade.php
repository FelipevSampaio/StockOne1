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

            /* Prevenir flash de conteúdo incorreto no dark mode */
            html {
                color-scheme: light dark;
            }
        </style>
        <script>
            // Dark Mode: Carregar preferência ANTES de qualquer render para evitar flash
            (function() {
                try {
                    const theme = localStorage.getItem('theme');
                    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

                    if (theme === 'dark' || (!theme && prefersDark)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
                } catch (e) {
                    console.warn('Erro ao carregar tema:', e);
                }
            })();
        </script>
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    </head>
    <body class="bg-gray-50 dark:bg-gray-900 font-sans text-gray-900 dark:text-gray-100 transition-colors" x-data="appLayout()">
        <div class="flex h-screen overflow-hidden"
             x-data="sidebarManager()"
             x-init="initSidebar(); window.addEventListener('resize', () => { if(window.innerWidth >= 1024) sidebarOpen = false; });"
             x-cloak>
            <!-- Overlay escuro em mobile com animação -->
            <div x-show="sidebarOpen && window.innerWidth < 1024"
                 @click="closeSidebar()"
                 x-transition:enter="transition-opacity ease-linear duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition-opacity ease-linear duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 z-30 bg-black/50 backdrop-blur-sm lg:hidden"></div>

            <!-- Botão para abrir menu lateral em telas pequenas -->
            <button @click="openSidebar()"
                    class="lg:hidden fixed top-4 left-4 z-50 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-2 shadow-lg hover:shadow-xl transition-all duration-200 hover:scale-110 focus:outline-none">
                <svg class="w-6 h-6 text-gray-700 dark:text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>

            <!-- Sidebar com animações melhoradas -->
            <aside class="fixed inset-y-0 left-0 z-40 w-64 bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 transform transition-transform duration-300 ease-out lg:translate-x-0"
                :class="window.innerWidth >= 1024 ? 'translate-x-0' : (sidebarOpen ? 'translate-x-0' : '-translate-x-full')"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="-translate-x-full"
                x-transition:enter-end="translate-x-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="translate-x-0"
                x-transition:leave-end="-translate-x-full"
                @touchstart="touchStart($event)"
                @touchmove="touchMove($event)"
                @touchend="touchEnd()">
                <!-- Logo -->
                <div class="p-6 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
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
                    <!-- Botão fechar em mobile -->
                    <button @click="closeSidebar()"
                            class="lg:hidden p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                        <svg class="w-5 h-5 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <!-- Navigation -->
                <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
                    @php
                        $menu = [
                            ['label' => 'Dashboard', 'route' => 'dashboard', 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6', 'description' => 'Visão geral do restaurante e métricas principais'],
                            ['label' => 'Cardápio', 'route' => 'cardapio-itens.index', 'icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253', 'description' => 'Gerenciar itens do cardápio e preços'],
                            ['label' => 'Pedidos', 'route' => 'pedidos.index', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01', 'badge' => true, 'description' => 'Visualizar e gerenciar pedidos do restaurante'],
                            ['label' => 'Insumos', 'route' => 'insumos.index', 'icon' => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4', 'description' => 'Cadastrar e gerenciar insumos utilizados'],
                            ['label' => 'Estoque', 'route' => 'estoque.index', 'icon' => 'M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4', 'description' => 'Controle de estoque e movimentações'],
                            ['label' => 'Receitas', 'route' => 'receitas.index', 'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z', 'description' => 'Criar e gerenciar receitas dos pratos'],
                            ['label' => 'Alertas', 'route' => 'alertas.index', 'icon' => 'M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9', 'badge' => true, 'description' => 'Alertas do sistema e notificações'],
                            ['label' => 'Fila de Produção', 'route' => 'fila-producao.index', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z', 'description' => 'Acompanhar pedidos em produção'],
                            ['label' => 'Sugestões de Compras', 'route' => 'compras-sugestoes.index', 'icon' => 'M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z', 'description' => 'Sugestões automáticas de compras'],
                            ['label' => 'Menu Público', 'route' => 'public.menu', 'icon' => 'M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9', 'description' => 'Visualizar menu público do restaurante'],
                        ];
                    @endphp

                    @foreach ($menu as $item)
                        @php
                            $active = request()->routeIs(str_replace('.index', '', $item['route']) . '*');
                            $alertCount = 0;
                            if(isset($item['badge']) && $item['badge']) {
                                if($item['route'] === 'alertas.index') {
                                    $alertCount = App\Models\Alerta::where('resolvido', false)->whereHas('insumo', fn($q) => $q->where('restaurante_id', session('restaurante_id')))->count();
                                } elseif($item['route'] === 'pedidos.index') {
                                    $alertCount = App\Models\Pedido::where('status', 'pendente')->where('restaurante_id', session('restaurante_id'))->count();
                                }
                            }
                        @endphp
                        <a href="{{ $item['route'] === 'public.menu' ? route('public.menu', ['restaurante' => session('restaurante_slug')]) : route($item['route']) }}"
                           x-data="{ tooltip: false }"
                           @mouseenter="tooltip = true; setTimeout(() => tooltip = false, 3000)"
                           @mouseleave="tooltip = false"
                           class="group flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-300 relative
                                  {{ $active
                                      ? 'bg-gradient-to-r from-red-50 to-red-100/50 dark:from-red-900/30 dark:to-red-800/20 text-red-700 dark:text-red-300 shadow-md shadow-red-500/10 border-l-4 border-red-600 dark:border-red-400 transform translate-x-1'
                                      : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700/50 hover:translate-x-1 hover:shadow-sm' }}"
                           :class="window.innerWidth < 1024 ? 'justify-center' : ''"
                           title="{{ $item['description'] ?? $item['label'] }}">
                            <!-- Indicador lateral para página ativa -->
                            @if($active)
                                <div class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-8 bg-gradient-to-b from-red-500 to-red-600 rounded-r-full shadow-lg"></div>
                            @endif

                            <svg class="w-5 h-5 flex-shrink-0 transition-all duration-300 {{ $active ? 'text-red-600 dark:text-red-400 scale-110' : 'group-hover:scale-110 group-hover:text-red-600 dark:group-hover:text-red-400' }}"
                                 fill="none"
                                 stroke="currentColor"
                                 viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}"/>
                            </svg>
                            <span class="flex-1 transition-all duration-300 {{ $active ? 'font-semibold' : '' }}">{{ $item['label'] }}</span>
                            @if(isset($item['badge']) && $item['badge'] && $alertCount > 0)
                                <span class="flex items-center justify-center min-w-[20px] h-5 px-1.5 text-xs font-bold text-white bg-red-600 rounded-full animate-pulse shadow-lg">
                                    {{ $alertCount > 9 ? '9+' : $alertCount }}
                                </span>
                            @endif

                            <!-- Tooltip -->
                            <div x-show="tooltip && window.innerWidth >= 1024"
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 scale-95"
                                 x-transition:enter-end="opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-150"
                                 x-transition:leave-start="opacity-100 scale-100"
                                 x-transition:leave-end="opacity-0 scale-95"
                                 class="absolute left-full ml-2 px-3 py-2 bg-gray-900 dark:bg-gray-700 text-white text-xs rounded-lg shadow-xl z-50 whitespace-nowrap pointer-events-none"
                                 style="display: none;">
                                <div class="font-semibold mb-0.5">{{ $item['label'] }}</div>
                                <div class="text-gray-300 dark:text-gray-400 text-[10px]">{{ $item['description'] ?? '' }}</div>
                                <!-- Seta do tooltip -->
                                <div class="absolute right-full top-1/2 -translate-y-1/2 border-4 border-transparent border-r-gray-900 dark:border-r-gray-700"></div>
                            </div>
                        </a>
                    @endforeach
                </nav>

                <!-- Footer -->
                <div class="p-4 border-t border-gray-200 dark:border-gray-700">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-red-500 to-red-600 flex items-center justify-center text-white font-bold text-lg">
                            {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 2)) }}
                        </div>
                        <div class="flex-1">
                            <div class="text-sm font-semibold text-gray-900 dark:text-white">{{ Auth::user()->name ?? 'Usuário' }}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">{{ Auth::user()->email ?? '' }}</div>
                        </div>
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none" title="Abrir menu do usuário">
                                <svg class="w-5 h-5 text-gray-500 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                            <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-40 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 py-2 z-50">
                                <form action="{{ route('auth.logout') }}" method="POST" class="px-4 py-2">
                                    @csrf
                                    <button type="submit" class="w-full text-left text-sm text-red-600 dark:text-red-400 hover:underline">Sair</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3 text-xs text-gray-500 dark:text-gray-400 text-center">
                        StockOne © {{ date('Y') }}
                    </div>
                </div>
            </aside>

            <!-- Main Content -->
            <main class="flex-1 flex flex-col bg-gray-50 dark:bg-gray-900 lg:ml-64 w-full overflow-hidden">
                <!-- Header -->
                <header class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 px-4 sm:px-6 py-3 sm:py-4 shadow-sm sticky top-0 z-10 w-full">
                    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3 sm:gap-4">
                        <div class="flex-1 min-w-0">
                            <!-- Breadcrumbs (opcional) -->
                            @hasSection('breadcrumbs')
                                <nav class="flex items-center gap-1.5 mb-1 text-sm text-gray-500 dark:text-gray-400">
                                    @yield('breadcrumbs')
                                </nav>
                            @endif
                            
                            <div class="flex flex-wrap items-center gap-2 sm:gap-3">
                                <!-- Nome do Restaurante -->
                                <div class="hidden md:flex items-center gap-2 px-3 py-1.5 bg-red-50 dark:bg-red-900/20 rounded-lg border border-red-200 dark:border-red-800">
                                    <svg class="w-4 h-4 text-red-600 dark:text-red-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                    <span class="text-sm font-semibold text-red-700 dark:text-red-300 truncate max-w-[200px] lg:max-w-none" title="{{ session('restaurante_nome') }}">
                                        {{ session('restaurante_nome') }}
                                    </span>
                                </div>
                                
                                <h1 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white truncate">@yield('title', 'Painel')</h1>
                                @hasSection('subtitle')
                                    <span class="hidden sm:inline text-gray-400 dark:text-gray-600">•</span>
                                    <p class="hidden sm:inline text-sm text-gray-600 dark:text-gray-400 truncate">@yield('subtitle')</p>
                                @endif
                            </div>
                        </div>

                        <div class="flex items-center justify-end gap-2 sm:gap-3 w-full sm:w-auto ml-auto">
                            @yield('actions')

                            <!-- Barra de Busca Global (Ctrl+K) -->
                            <div class="relative hidden lg:block" x-data="{ open: false, query: '' }" 
                                 x-init="
                                    window.addEventListener('keydown', (e) => {
                                        if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                                            e.preventDefault();
                                            open = true;
                                            $nextTick(() => document.querySelector('#global-search-input')?.focus());
                                        }
                                    });
                                 ">
                                <button @click="open = !open"
                                        class="flex items-center gap-2 px-3 py-2 text-sm text-gray-600 dark:text-gray-400 bg-gray-50 dark:bg-gray-700/50 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600 transition-colors min-w-[200px] justify-between">
                                    <div class="flex items-center gap-2 flex-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                        </svg>
                                        <span class="text-xs text-gray-500 dark:text-gray-400">Buscar...</span>
                                    </div>
                                    <kbd class="hidden xl:inline-flex items-center px-1.5 py-0.5 text-xs font-semibold text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600 rounded">Ctrl+K</kbd>
                                </button>

                                <!-- Modal de Busca -->
                                <div x-show="open"
                                     @click.away="open = false"
                                     x-transition:enter="transition ease-out duration-200"
                                     x-transition:enter-start="opacity-0 scale-95"
                                     x-transition:enter-end="opacity-100 scale-100"
                                     x-transition:leave="transition ease-in duration-150"
                                     x-transition:leave-start="opacity-100 scale-100"
                                     x-transition:leave-end="opacity-0 scale-95"
                                     class="absolute right-0 mt-2 w-96 bg-white dark:bg-gray-800 rounded-lg shadow-xl border border-gray-200 dark:border-gray-700 z-50"
                                     style="display: none;">
                                    <div class="p-3 border-b border-gray-200 dark:border-gray-700">
                                        <input type="text" 
                                               id="global-search-input"
                                               x-model="query"
                                               @keydown.escape="open = false"
                                               placeholder="Buscar pedidos, itens, receitas..."
                                               class="w-full px-3 py-2 text-sm bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 dark:focus:ring-red-400 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500">
                                    </div>
                                    <div class="max-h-96 overflow-y-auto p-2">
                                        <div class="text-center py-8 text-gray-500 dark:text-gray-400 text-sm">
                                            <svg class="w-12 h-12 mx-auto mb-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                            </svg>
                                            <p>Digite para buscar...</p>
                                            <p class="text-xs mt-1 text-gray-400 dark:text-gray-500">Pressione ESC para fechar</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Badge de Pedidos Pendentes -->
                            @php
                                $pedidosPendentes = App\Models\Pedido::where('status', 'pendente')
                                    ->where('restaurante_id', session('restaurante_id'))
                                    ->count();
                            @endphp
                            @if($pedidosPendentes > 0)
                                <a href="{{ route('pedidos.index', ['status' => 'pendente']) }}" 
                                   class="relative hidden md:flex items-center gap-2 px-3 py-2 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg hover:bg-red-100 dark:hover:bg-red-900/30 transition-colors group">
                                    <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                    </svg>
                                    <span class="text-sm font-semibold text-red-700 dark:text-red-300">{{ $pedidosPendentes }}</span>
                                    <span class="hidden lg:inline text-xs text-red-600 dark:text-red-400 group-hover:underline">Pendentes</span>
                                    <span class="absolute -top-1 -right-1 w-3 h-3 bg-red-500 rounded-full animate-pulse"></span>
                                </a>
                            @endif

                            <!-- Separador Visual -->
                            <div class="hidden md:block w-px h-6 bg-gray-200 dark:bg-gray-700"></div>

                            <!-- Notificações -->
                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = !open"
                                        class="relative p-2 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                    </svg>
                                    @php
                                        $alertCount = App\Models\Alerta::where('resolvido', false)->whereHas('insumo', fn($q) => $q->where('restaurante_id', session('restaurante_id')))->count();
                                    @endphp
                                    @if($alertCount > 0)
                                        <span class="absolute -top-1 -right-1 min-w-[18px] h-4.5 px-1.5 flex items-center justify-center text-xs font-bold text-white bg-red-500 rounded-full ring-2 ring-white dark:ring-gray-800 animate-pulse">
                                            {{ $alertCount > 9 ? '9+' : $alertCount }}
                                        </span>
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

                            <!-- Separador Visual -->
                            <div class="hidden md:block w-px h-6 bg-gray-200 dark:bg-gray-700"></div>

                            <!-- Relógio e Data -->
                            <div class="hidden xl:flex flex-col items-end px-3 py-1.5 bg-gray-50 dark:bg-gray-700/50 rounded-lg border border-gray-200 dark:border-gray-600"
                                 x-data="{
                                     time: new Date().toLocaleTimeString('pt-BR', { hour: '2-digit', minute: '2-digit' }),
                                     date: new Date().toLocaleDateString('pt-BR', { weekday: 'short', day: '2-digit', month: 'short' })
                                 }"
                                 x-init="
                                     setInterval(() => {
                                         time = new Date().toLocaleTimeString('pt-BR', { hour: '2-digit', minute: '2-digit' });
                                         date = new Date().toLocaleDateString('pt-BR', { weekday: 'short', day: '2-digit', month: 'short' });
                                     }, 1000);
                                 ">
                                <div class="text-sm font-semibold text-gray-900 dark:text-white" x-text="time"></div>
                                <div class="text-xs text-gray-500 dark:text-gray-400 capitalize" x-text="date"></div>
                            </div>

                            <!-- Estatísticas Rápidas (Receita Hoje) -->
                            @php
                                $receitaHoje = \App\Models\Pedido::where('restaurante_id', session('restaurante_id'))
                                    ->whereDate('data_hora_pedido', today())
                                    ->where('status', 'concluido')
                                    ->sum('valor_total') ?? 0;
                            @endphp
                            <div class="hidden lg:flex items-center gap-2 px-3 py-1.5 bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 rounded-lg border border-green-200 dark:border-green-800">
                                <svg class="w-4 h-4 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <div class="flex flex-col">
                                    <span class="text-xs text-gray-500 dark:text-gray-400">Hoje</span>
                                    <span class="text-sm font-bold text-green-700 dark:text-green-400">R$ {{ number_format($receitaHoje, 2, ',', '.') }}</span>
                                </div>
                            </div>

                            <!-- Indicador de Status do Sistema -->
                            <div class="hidden sm:flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600" 
                                 x-data="{ status: 'online' }" 
                                 x-init="status = navigator.onLine ? 'online' : 'offline'; window.addEventListener('online', () => status = 'online'); window.addEventListener('offline', () => status = 'offline')"
                                 title="Status da conexão">
                                <div class="w-2 h-2 rounded-full" 
                                     :class="status === 'online' ? 'bg-green-500 animate-pulse' : 'bg-red-500'"></div>
                                <span class="text-xs font-medium text-gray-600 dark:text-gray-300 hidden lg:inline" 
                                      x-text="status === 'online' ? 'Online' : 'Offline'"></span>
                            </div>

                            <!-- Botão de Ajuda -->
                            <button class="hidden lg:flex p-2 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors"
                                    title="Ajuda e Suporte"
                                    onclick="window.open('https://docs.stockone.com.br', '_blank')">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </button>

                            <!-- Dark Mode Toggle -->
                            <button @click="toggleDarkMode()"
                                    class="p-2 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors"
                                    title="Alternar modo escuro (Ctrl+Shift+D)">
                                <!-- Ícone de lua (modo claro ativo) -->
                                <svg x-show="!isDark"
                                     x-transition
                                     class="w-6 h-6"
                                     fill="none"
                                     stroke="currentColor"
                                     viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                                </svg>
                                <!-- Ícone de sol (modo escuro ativo) -->
                                <svg x-show="isDark"
                                     x-transition
                                     x-cloak
                                     class="w-6 h-6"
                                     fill="none"
                                     stroke="currentColor"
                                     viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                                </svg>
                            </button>

                            <!-- User Menu -->
                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = !open"
                                        class="flex items-center gap-2 px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors border border-gray-200 dark:border-gray-700">
                                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-red-500 to-red-600 flex items-center justify-center text-white font-bold text-xs flex-shrink-0">
                                        {{ strtoupper(substr(session('restaurante_nome'), 0, 2)) }}
                                    </div>
                                    <div class="hidden lg:flex flex-col items-start">
                                        <span class="text-xs font-semibold text-gray-900 dark:text-white leading-tight">{{ session('restaurante_nome') }}</span>
                                        <span class="text-xs text-gray-500 dark:text-gray-400">{{ Auth::user()->name }}</span>
                                    </div>
                                    <span class="hidden md:inline lg:hidden">{{ session('restaurante_nome') }}</span>
                                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                                     class="absolute right-0 mt-2 w-64 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 py-1 z-50"
                                     style="display: none;">
                                    <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ Auth::user()->name }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ Auth::user()->email }}</p>
                                        <div class="mt-2 flex items-center gap-2">
                                            <div class="w-2 h-2 rounded-full bg-green-500"></div>
                                            <span class="text-xs text-gray-500 dark:text-gray-400">{{ session('restaurante_nome') }}</span>
                                        </div>
                                    </div>
                                    
                                    <div class="py-1">
                                        <a href="{{ route('dashboard') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                            </svg>
                                            Dashboard
                                        </a>
                                        <a href="{{ route('public.menu', ['restaurante' => session('restaurante_slug')]) }}" target="_blank" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                                            </svg>
                                            Menu Público
                                            <svg class="w-3 h-3 ml-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                            </svg>
                                        </a>
                                    </div>
                                    
                                    <div class="border-t border-gray-200 dark:border-gray-700 py-1">
                                        <form action="{{ route('auth.logout') }}" method="POST" class="p-1">
                                            @csrf
                                            <button type="submit" class="w-full flex items-center gap-2 px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-md transition-colors">
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
                    </div>
                </header>

                <!-- Content -->
                <section class="flex-1 p-4 sm:p-6 overflow-y-auto w-full max-w-full">
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

                    @if (session('error'))
                        <div x-data="{ show: true }"
                             x-show="show"
                             x-init="setTimeout(() => show = false, 8000)"
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 transform scale-90"
                             x-transition:enter-end="opacity-100 transform scale-100"
                             x-transition:leave="transition ease-in duration-200"
                             x-transition:leave-start="opacity-100 transform scale-100"
                             x-transition:leave-end="opacity-0 transform scale-90"
                             class="mb-6 rounded-lg border border-red-200 dark:border-red-800 bg-red-50 dark:bg-red-900/20 px-4 py-3 text-sm text-red-800 dark:text-red-200 flex items-center justify-between shadow-sm">
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                                <span>{{ session('error') }}</span>
                            </div>
                            <button @click="show = false" class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-200">
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
                    isDark: document.documentElement.classList.contains('dark'),

                    init() {
                        // Sincronizar estado inicial
                        this.isDark = document.documentElement.classList.contains('dark');

                        // Observar mudanças na classe dark do documento
                        const observer = new MutationObserver(() => {
                            this.isDark = document.documentElement.classList.contains('dark');
                        });

                        observer.observe(document.documentElement, {
                            attributes: true,
                            attributeFilter: ['class']
                        });

                        // Atalho de teclado para dark mode (Ctrl+Shift+D)
                        document.addEventListener('keydown', (e) => {
                            if ((e.ctrlKey || e.metaKey) && e.shiftKey && e.key === 'D') {
                                e.preventDefault();
                                this.toggleDarkMode();
                            }
                        });
                    },

                    toggleDarkMode() {
                        if (document.documentElement.classList.contains('dark')) {
                            document.documentElement.classList.remove('dark');
                            localStorage.theme = 'light';
                            this.isDark = false;
                        } else {
                            document.documentElement.classList.add('dark');
                            localStorage.theme = 'dark';
                            this.isDark = true;
                        }
                    }
                }
            }

            function sidebarManager() {
                return {
                    sidebarOpen: false,
                    touchStartX: 0,
                    touchStartY: 0,
                    touchEndX: 0,
                    touchEndY: 0,
                    minSwipeDistance: 50,

                    initSidebar() {
                        // Fechar sidebar ao clicar fora em mobile
                        document.addEventListener('click', (e) => {
                            if (window.innerWidth < 1024 && this.sidebarOpen) {
                                const sidebar = document.querySelector('aside');
                                const button = document.querySelector('button[\\@click*="openSidebar"]');
                                if (sidebar && !sidebar.contains(e.target) && !button?.contains(e.target)) {
                                    this.closeSidebar();
                                }
                            }
                        });

                        // Fechar sidebar com ESC
                        document.addEventListener('keydown', (e) => {
                            if (e.key === 'Escape' && this.sidebarOpen) {
                                this.closeSidebar();
                            }
                        });
                    },

                    openSidebar() {
                        this.sidebarOpen = true;
                        document.body.style.overflow = 'hidden';
                    },

                    closeSidebar() {
                        this.sidebarOpen = false;
                        document.body.style.overflow = '';
                    },

                    touchStart(e) {
                        if (window.innerWidth >= 1024) return;
                        this.touchStartX = e.changedTouches[0].screenX;
                        this.touchStartY = e.changedTouches[0].screenY;
                    },

                    touchMove(e) {
                        if (window.innerWidth >= 1024) return;
                        // Prevenir scroll durante swipe
                        if (Math.abs(e.changedTouches[0].screenX - this.touchStartX) > Math.abs(e.changedTouches[0].screenY - this.touchStartY)) {
                            e.preventDefault();
                        }
                    },

                    touchEnd(e) {
                        if (window.innerWidth >= 1024) return;
                        this.touchEndX = e.changedTouches[0].screenX;
                        this.touchEndY = e.changedTouches[0].screenY;
                        this.handleSwipe();
                    },

                    handleSwipe() {
                        const swipeDistanceX = this.touchEndX - this.touchStartX;
                        const swipeDistanceY = this.touchEndY - this.touchStartY;

                        // Verificar se é um swipe horizontal
                        if (Math.abs(swipeDistanceX) > Math.abs(swipeDistanceY) && Math.abs(swipeDistanceX) > this.minSwipeDistance) {
                            if (swipeDistanceX > 0 && !this.sidebarOpen) {
                                // Swipe da esquerda para direita - abrir
                                this.openSidebar();
                            } else if (swipeDistanceX < 0 && this.sidebarOpen) {
                                // Swipe da direita para esquerda - fechar
                                this.closeSidebar();
                            }
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

