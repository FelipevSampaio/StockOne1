<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>@yield('title', 'Cardápio') • StockOne</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    </head>
    <body class="bg-gray-50 text-gray-900">
        @php
            $isPublicMenu = request()->routeIs('public.menu');
        @endphp
        <div class="min-h-screen flex"
             x-data="{ sidebarOpen: false }"
             x-init="window.addEventListener('resize', () => { if(window.innerWidth >= 1024) sidebarOpen = false; })">
            @if(!$isPublicMenu)
            <!-- Overlay escuro em mobile -->
            <div x-show="sidebarOpen && window.innerWidth < 1024"
                 @click="sidebarOpen = false"
                 x-transition:enter="transition-opacity ease-linear duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition-opacity ease-linear duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 z-30 bg-black/50 backdrop-blur-sm lg:hidden"
                 style="display: none;"></div>

            <!-- Botão hambúrguer para mobile -->
            <button @click="sidebarOpen = !sidebarOpen"
                    class="lg:hidden fixed top-3 left-3 md:top-4 md:left-4 z-50 bg-white border border-gray-200 rounded-lg p-2 shadow-lg hover:shadow-xl transition-all duration-200 hover:scale-110 focus:outline-none"
                    aria-label="Abrir menu">
                <svg class="w-5 h-5 md:w-6 md:h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>

            <!-- Menu lateral -->
            <aside class="fixed inset-y-0 left-0 z-40 w-64 bg-red-700 text-white flex flex-col overflow-y-auto transform transition-transform duration-300 ease-in-out lg:translate-x-0"
                   :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
                   x-show="true">
                <div class="p-4 md:p-6 flex-shrink-0 flex items-center justify-between lg:block">
                    <div class="flex-1">
                        <div class="text-xl md:text-2xl font-bold tracking-wide">StockOne</div>
                        <p class="text-xs md:text-sm text-red-100 mt-1">Gestão inteligente para restaurantes</p>
                    </div>
                    <button @click="sidebarOpen = false"
                            class="lg:hidden text-red-100 hover:text-white p-1 ml-2 flex-shrink-0"
                            aria-label="Fechar menu">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <nav class="flex-1 px-3 md:px-4 space-y-1 overflow-y-auto">
                    @php
                        $menu = [
                            ['label' => 'Dashboard', 'route' => 'dashboard'],
                            ['label' => 'Cardápio', 'route' => 'cardapio-itens.index'],
                            ['label' => 'Receitas', 'route' => 'receitas.index'],
                            ['label' => 'Insumos', 'route' => 'insumos.index'],
                            ['label' => 'Estoque', 'route' => 'estoque.index'],
                            ['label' => 'Pedidos', 'route' => 'pedidos.index'],
                            ['label' => 'Itens do pedido', 'route' => 'pedido-itens.index'],
                            ['label' => 'Fila de Produção', 'route' => 'fila-producao.index'],
                            ['label' => 'Alertas', 'route' => 'alertas.index'],
                            ['label' => 'Sugestões de Compras', 'route' => 'compras-sugestoes.index'],
                            ['label' => 'Menu', 'route' => 'public.menu'],
                        ];
                    @endphp

                    @foreach ($menu as $item)
                        @php
                            $active = request()->routeIs(str_replace('.index', '', $item['route']) . '*');
                        @endphp
                        <a
                            href="{{ route($item['route']) }}"
                            @click="if(window.innerWidth < 1024) sidebarOpen = false"
                            class="block rounded-lg px-3 md:px-4 py-2 text-xs md:text-sm font-medium transition
                                {{ $active ? 'bg-white text-red-700 shadow' : 'text-red-100 hover:bg-red-600 hover:text-white' }}"
                        >
                            {{ $item['label'] }}
                        </a>
                    @endforeach
                </nav>

                <div class="p-3 md:p-4 text-xs text-red-100 flex-shrink-0">
                    StockOne © {{ date('Y') }} · SaaS de restaurantes
                </div>
            </aside>
            @endif

            <div class="flex-1 flex flex-col bg-gray-50 w-full {{ !$isPublicMenu ? 'lg:ml-64' : '' }}">
                <header class="bg-white/90 shadow-sm backdrop-blur">
                    <div class="mx-auto flex max-w-6xl flex-wrap items-center justify-between gap-4 px-4 md:px-6 lg:px-8 py-4 md:py-6">
                        <div class="min-w-0 flex-1">
                            <p class="text-xs uppercase tracking-[0.2em] md:tracking-[0.3em] text-red-600">StockOne</p>
                            <h1 class="text-xl md:text-2xl font-semibold text-gray-900 truncate">@yield('title', 'Cardápio digital')</h1>
                        </div>
                        <div class="flex flex-col items-end flex-shrink-0">
                            <span class="text-xs md:text-sm font-medium text-gray-500">Powered by StockOne</span>
                            <a href="{{ route('auth.login') }}" class="text-xs font-semibold uppercase tracking-[0.2em] md:tracking-[0.3em] text-red-500 hover:text-red-600">Área do restaurante</a>
                        </div>
                    </div>
                </header>

                <main class="flex-1 px-3 md:px-4 lg:px-6 py-6 md:py-10">
                    <div class="mx-auto max-w-6xl space-y-4 md:space-y-6">
                        @if (session('success'))
                            <div class="rounded-2xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800 shadow-sm">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800 shadow-sm">
                                {{ session('error') }}
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800 shadow-sm">
                                <p class="font-semibold">Ops! Confira os campos:</p>
                                <ul class="mt-2 list-inside list-disc space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @yield('content')
                    </div>
                </main>

                <footer class="border-t border-gray-100 bg-white/80">
                    <div class="mx-auto flex max-w-6xl flex-col gap-2 px-4 md:px-6 py-4 md:py-6 text-xs md:text-sm text-gray-500 sm:flex-row sm:items-center sm:justify-between">
                        <p class="text-center sm:text-left">&copy; {{ date('Y') }} StockOne • Experiência digital para restaurantes</p>
                        <p class="text-xs uppercase tracking-[0.2em] md:tracking-[0.3em] text-red-500 text-center sm:text-right">Sabor com tecnologia</p>
                    </div>
                </footer>
            </div>
        </div>
    </body>
</html>


