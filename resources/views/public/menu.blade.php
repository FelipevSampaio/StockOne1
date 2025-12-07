@extends('layouts.public')

@section('title', (isset($restaurante) && $restaurante) ? $restaurante->nome : 'Cardápio Público')

@section('content')
    <div class="space-y-10">
        <section class="rounded-3xl border border-red-100 bg-gradient-to-r from-red-600 to-red-500 p-8 text-white shadow-xl">
            <div class="flex flex-col gap-6 md:flex-row md:items-center md:justify-between">
                <div>
                    <p class="text-sm uppercase tracking-[0.4em] text-red-100">Bem-vindo</p>
                    <h2 class="mt-2 text-4xl font-semibold">{{ (isset($restaurante) && $restaurante) ? $restaurante->nome : 'Cardápio Público' }}</h2>
                    <p class="mt-3 max-w-2xl text-base text-red-50">
                        Explore nosso cardápio digital, personalize o pedido e finalize em poucos cliques.
                    </p>
                </div>
                <div class="rounded-2xl bg-white/10 px-6 py-4 text-center backdrop-blur">
                    <p class="text-xs uppercase tracking-[0.3em] text-red-100">Itens no pedido</p>
                    <p class="mt-1 text-4xl font-bold">{{ $cartCount }}</p>
                    <p class="text-sm text-red-100">Subtotal R$ {{ number_format($cartTotal, 2, ',', '.') }}</p>
                </div>
            </div>
        </section>

        <div class="grid gap-8 lg:grid-cols-[minmax(0,2fr)_minmax(320px,1fr)]">
            <div class="space-y-10">
                <section class="rounded-3xl border border-gray-100 bg-white p-5 shadow-sm">
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <p class="text-xs uppercase tracking-[0.3em] text-red-500">Busca</p>
                            <h3 class="text-lg font-semibold text-gray-900">Encontre seu prato</h3>
                            <p class="mt-1 text-xs text-gray-500">Digite o nome ou categoria para filtrar o cardápio.</p>
                        </div>
                        <div class="w-full sm:w-72">
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1010.5 18.5a7.5 7.5 0 006.15-3.85z" />
                                    </svg>
                                </span>
                                <input
                                    id="menu-search-input"
                                    type="text"
                                    placeholder=" Buscar itens do cardápio..."
                                    class="block w-full rounded-full border border-gray-200 bg-gray-50 py-2.5 pl-10 pr-3 text-sm text-gray-900 placeholder:text-gray-400 shadow-sm focus:border-red-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-red-500/50"
                                >
                            </div>
                        </div>
                    </div>
                    <p id="menu-search-empty-state" class="mt-3 hidden text-xs text-gray-500">
                        Nenhum item encontrado para a busca atual. Tente usar outras palavras ou limpar o campo de busca.
                    </p>
                </section>
                @forelse ($categorias as $categoria => $itens)
                    <section data-category-section>
                        <div class="mb-4 flex items-center justify-between">
                            <div>
                                <p class="text-xs uppercase tracking-[0.3em] text-red-500">Categoria</p>
                                <h3 class="text-2xl font-semibold text-gray-900">{{ $categoria }}</h3>
                            </div>
                            <span class="rounded-full bg-red-50 px-4 py-1 text-xs font-semibold text-red-600">{{ $itens->count() }} opções</span>
                        </div>

                        <div class="grid gap-6 sm:grid-cols-2">
                            @foreach ($itens as $item)
                                @php
                                    $cartItem = $cartItems->get($item->id);
                                @endphp
                                <article
                                    class="rounded-3xl border border-gray-100 bg-white shadow-sm transition hover:-translate-y-0.5 hover:shadow-lg"
                                    data-menu-item
                                    data-search-text="{{ $item->nome }} {{ $item->descricao }} {{ $categoria }}"
                                >
                                    @if ($item->imagem)
                                        <img src="{{ asset('storage/' . $item->imagem) }}" alt="{{ $item->nome }}" class="h-48 w-full rounded-t-3xl object-cover">
                                    @else
                                        <div class="h-48 w-full rounded-t-3xl bg-red-50/80"></div>
                                    @endif
                                    <div class="space-y-4 p-6">
                                        <div>
                                            <h4 class="text-xl font-semibold text-gray-900">{{ $item->nome }}</h4>
                                            @if ($item->descricao)
                                                <p class="mt-1 text-sm text-gray-500">{{ $item->descricao }}</p>
                                            @endif
                                        </div>
                                        <div class="flex items-center justify-between">
                                            <span class="text-2xl font-bold text-gray-900">R$ {{ number_format($item->preco_venda, 2, ',', '.') }}</span>
                                            <span class="text-xs uppercase tracking-[0.3em] text-red-500">{{ $item->tempo_preparo_minutos ? $item->tempo_preparo_minutos . ' min' : 'Entrega rápida' }}</span>
                                        </div>
                                        <div class="flex items-center justify-between gap-2">
                                            @if ($cartItem)
                                                <div class="flex items-center gap-3 rounded-full border border-red-100 bg-red-50 px-3 py-1 text-sm font-semibold text-red-600">
                                                    <form action="{{ route('public.cart.update', $item->id) }}" method="POST">
                                                        @csrf
                                                        @method('PATCH')
                                                        <input type="hidden" name="quantity" value="{{ max(0, $cartItem['quantidade'] - 1) }}">
                                                        <button type="submit" class="px-2 text-lg leading-none text-red-600 hover:text-red-800">−</button>
                                                    </form>
                                                    <span>{{ $cartItem['quantidade'] }}</span>
                                                    <form action="{{ route('public.cart.update', $item->id) }}" method="POST">
                                                        @csrf
                                                        @method('PATCH')
                                                        <input type="hidden" name="quantity" value="{{ min(99, $cartItem['quantidade'] + 1) }}">
                                                        <button type="submit" class="px-2 text-lg leading-none text-red-600 hover:text-red-800">+</button>
                                                    </form>
                                                </div>
                                                <form action="{{ route('public.cart.destroy', $item->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-sm font-semibold text-gray-400 hover:text-red-600">Remover</button>
                                                </form>
                                            @else
                                                <form action="{{ route('public.cart.store') }}" method="POST" class="w-full">
                                                    @csrf
                                                    <input type="hidden" name="cardapio_item_id" value="{{ $item->id }}">
                                                    <button type="submit" class="inline-flex w-full items-center justify-center rounded-full bg-red-600 px-6 py-2 text-sm font-semibold text-white shadow hover:bg-red-500">
                                                        Adicionar
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    </section>
                @empty
                    <div class="rounded-3xl border border-dashed border-gray-200 bg-white p-10 text-center text-gray-500">
                        Nenhum item disponível no momento.
                    </div>
                @endforelse

                @if ($suggestions->isNotEmpty())
                    <section class="rounded-3xl border border-yellow-100 bg-yellow-50/70 p-6 shadow-sm">
                        <div class="flex flex-wrap items-center justify-between gap-3">
                            <div>
                                <p class="text-xs uppercase tracking-[0.3em] text-yellow-600">Sugestões da mesma categoria</p>
                                <h3 class="text-2xl font-semibold text-gray-900">Você também pode gostar</h3>
                            </div>
                        </div>
                        <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                            @foreach ($suggestions as $suggestion)
                                <article class="rounded-2xl border border-white/60 bg-white/80 p-4 shadow-sm backdrop-blur">
                                    <h4 class="text-lg font-semibold text-gray-900">{{ $suggestion->nome }}</h4>
                                    <p class="mt-1 text-sm text-gray-500">{{ $suggestion->categoria ?? 'Mais pedidos' }}</p>
                                    <p class="mt-4 text-xl font-bold text-gray-900">R$ {{ number_format($suggestion->preco_venda, 2, ',', '.') }}</p>
                                    <form action="{{ route('public.cart.store') }}" method="POST" class="mt-4">
                                        @csrf
                                        <input type="hidden" name="cardapio_item_id" value="{{ $suggestion->id }}">
                                        <button type="submit" class="w-full rounded-full bg-red-600 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-red-500">
                                            Adicionar ao pedido
                                        </button>
                                    </form>
                                </article>
                            @endforeach
                        </div>
                    </section>
                @endif
            </div>

            <aside class="space-y-6">
                <div class="sticky top-6 space-y-6">
                    <section class="rounded-3xl border border-gray-100 bg-white p-6 shadow-lg">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs uppercase tracking-[0.3em] text-red-500">Pedido atual</p>
                                <h3 class="text-2xl font-semibold text-gray-900">Seu carrinho</h3>
                            </div>
                            <span class="rounded-full bg-red-50 px-4 py-1 text-xs font-semibold text-red-600">{{ $cartCount }} itens</span>
                        </div>

                        <div class="mt-6 space-y-4">
                            @forelse ($cartItems as $cartItem)
                                <div class="rounded-2xl border border-gray-100 bg-gray-50/80 p-4">
                                    <div class="flex items-start justify-between gap-3">
                                        <div>
                                            <p class="text-sm font-semibold text-gray-900">{{ $cartItem['nome'] }}</p>
                                            <p class="text-xs uppercase tracking-[0.3em] text-gray-400">{{ $cartItem['categoria'] ?? 'Cardápio' }}</p>
                                        </div>
                                        <form action="{{ route('public.cart.destroy', $cartItem['id']) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-xs font-semibold uppercase tracking-[0.3em] text-gray-400 hover:text-red-600">Remover</button>
                                        </form>
                                    </div>
                                    <div class="mt-3 flex items-center justify-between">
                                        <div class="flex items-center gap-2 rounded-full border border-white/60 bg-white px-3 py-1 text-sm font-semibold text-red-600">
                                            <form action="{{ route('public.cart.update', $cartItem['id']) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="quantity" value="{{ max(0, $cartItem['quantidade'] - 1) }}">
                                                <button type="submit" class="px-2 text-lg leading-none">−</button>
                                            </form>
                                            <span>{{ $cartItem['quantidade'] }}</span>
                                            <form action="{{ route('public.cart.update', $cartItem['id']) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="quantity" value="{{ min(99, $cartItem['quantidade'] + 1) }}">
                                                <button type="submit" class="px-2 text-lg leading-none">+</button>
                                            </form>
                                        </div>
                                        <p class="text-lg font-semibold text-gray-900">R$ {{ number_format($cartItem['preco'] * $cartItem['quantidade'], 2, ',', '.') }}</p>
                                    </div>
                                </div>
                            @empty
                                <p class="text-sm text-gray-500">Seu carrinho está vazio. Comece adicionando pratos do cardápio.</p>
                            @endforelse
                        </div>

                        <div class="mt-6 border-t border-gray-100 pt-4">
                            <div class="flex items-center justify-between text-sm text-gray-500">
                                <span>Subtotal</span>
                                <span class="text-lg font-semibold text-gray-900">R$ {{ number_format($cartTotal, 2, ',', '.') }}</span>
                            </div>
                            <form action="{{ route('public.cart.checkout') }}" method="POST" class="mt-4">
                                @csrf
                                <button type="submit" @class([
                                    'w-full rounded-full px-5 py-3 text-sm font-semibold text-white shadow transition',
                                    'bg-red-600 hover:bg-red-500' => $cartItems->isNotEmpty(),
                                    'bg-gray-200 text-gray-400 cursor-not-allowed' => $cartItems->isEmpty(),
                                ]) {{ $cartItems->isEmpty() ? 'disabled' : '' }}>
                                    Finalizar pedido
                                </button>
                            </form>
                        </div>
                    </section>

                    <div class="rounded-3xl border border-gray-100 bg-white p-5 text-sm text-gray-500 shadow-sm">
                        <p>Pedidos enviados são recebidos instantaneamente no painel do restaurante, mantendo o fluxo conectado com estoque e fila de produção.</p>
                    </div>
                </div>
            </aside>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.getElementById('menu-search-input');
            const items = Array.from(document.querySelectorAll('[data-menu-item]'));
            const sections = Array.from(document.querySelectorAll('[data-category-section]'));
            const emptyState = document.getElementById('menu-search-empty-state');

            if (!searchInput || !items.length) {
                return;
            }

            const normalize = (text) => {
                return text
                    .toLowerCase()
                    .normalize('NFD')
                    .replace(/[\u0300-\u036f]/g, '');
            };

            const applyFilter = () => {
                const query = normalize(searchInput.value.trim());
                let visibleItemsCount = 0;

                items.forEach((item) => {
                    const haystack = normalize(item.getAttribute('data-search-text') || '');
                    const matches = !query || haystack.includes(query);

                    if (matches) {
                        item.classList.remove('hidden');
                        visibleItemsCount++;
                    } else {
                        item.classList.add('hidden');
                    }
                });

                sections.forEach((section) => {
                    const sectionItems = Array.from(section.querySelectorAll('[data-menu-item]'));
                    const hasVisibleItems = sectionItems.some((el) => !el.classList.contains('hidden'));

                    if (hasVisibleItems) {
                        section.classList.remove('hidden');
                    } else {
                        section.classList.add('hidden');
                    }
                });

                if (emptyState) {
                    if (!query || visibleItemsCount > 0) {
                        emptyState.classList.add('hidden');
                    } else {
                        emptyState.classList.remove('hidden');
                    }
                }
            };

            searchInput.addEventListener('input', applyFilter);
        });
    </script>
@endsection
