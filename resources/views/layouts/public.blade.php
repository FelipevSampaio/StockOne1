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
        <div class="min-h-screen flex">
            <aside class="fixed inset-y-0 left-0 z-30 w-64 bg-red-700 text-white flex flex-col overflow-y-auto">
                <div class="p-6 flex-shrink-0">
                    <div class="text-2xl font-bold tracking-wide">StockOne</div>
                    <p class="text-sm text-red-100">Gestão inteligente para restaurantes</p>
                </div>

                <nav class="flex-1 px-4 space-y-1 overflow-y-auto">
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
                            class="block rounded-lg px-4 py-2 text-sm font-medium transition
                                {{ $active ? 'bg-white text-red-700 shadow' : 'text-red-100 hover:bg-red-600 hover:text-white' }}"
                        >
                            {{ $item['label'] }}
                        </a>
                    @endforeach
                </nav>

                <div class="p-4 text-xs text-red-100 flex-shrink-0">
                    StockOne © {{ date('Y') }} · SaaS de restaurantes
                </div>
            </aside>

            <div class="flex-1 flex flex-col bg-gray-50 ml-64">
                <header class="bg-white/90 shadow-sm backdrop-blur">
                    <div class="mx-auto flex max-w-6xl flex-wrap items-center justify-between gap-4 px-8 py-6">
                        <div>
                            <p class="text-xs uppercase tracking-[0.3em] text-red-600">StockOne</p>
                            <h1 class="text-2xl font-semibold text-gray-900">@yield('title', 'Cardápio digital')</h1>
                        </div>
                        <div class="flex flex-col items-end">
                            <span class="text-sm font-medium text-gray-500">Powered by StockOne</span>
                            <a href="{{ route('auth.login') }}" class="text-xs font-semibold uppercase tracking-[0.3em] text-red-500 hover:text-red-600">Área do restaurante</a>
                        </div>
                    </div>
                </header>

                <main class="flex-1 px-4 py-10 sm:px-6">
                    <div class="mx-auto max-w-6xl space-y-6">
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
                    <div class="mx-auto flex max-w-6xl flex-col gap-2 px-6 py-6 text-sm text-gray-500 sm:flex-row sm:items-center sm:justify-between">
                        <p>&copy; {{ date('Y') }} StockOne • Experiência digital para restaurantes</p>
                        <p class="text-xs uppercase tracking-[0.3em] text-red-500">Sabor com tecnologia</p>
                    </div>
                </footer>
            </div>
        </div>
    </body>
</html>


