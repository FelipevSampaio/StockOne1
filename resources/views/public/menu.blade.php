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
            <div class="space-y-10" x-data="menuFilters()" x-init="init()">
                <!-- Filtros e Busca -->
                <section class="rounded-3xl border border-gray-100 bg-white p-5 shadow-sm">
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between mb-4">
                        <div>
                            <p class="text-xs uppercase tracking-[0.3em] text-red-500">Busca e Filtros</p>
                            <h3 class="text-lg font-semibold text-gray-900">Encontre seu prato</h3>
                            <p class="mt-1 text-xs text-gray-500">Digite o nome ou categoria para filtrar o cardápio.</p>
                        </div>
                        <div class="w-full sm:w-72">
                            <div class="relative">
                                <svg class="pointer-events-none absolute inset-y-0 left-0 ml-3 flex h-full items-center text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1010.5 18.5a7.5 7.5 0 006.15-3.85z" />
                                </svg>
                                <input
                                    id="menu-search-input"
                                    type="text"
                                    x-model="searchQuery"
                                    @input="applyFilters()"
                                    placeholder=" Buscar itens do cardápio..."
                                    class="block w-full rounded-full border border-gray-200 bg-gray-50 py-2.5 pl-10 pr-10 text-sm text-gray-900 placeholder:text-gray-400 shadow-sm focus:border-red-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-red-500/50 transition-all"
                                >
                                <button x-show="searchQuery" 
                                        @click="searchQuery = ''; applyFilters()"
                                        class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-red-600 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Filtros por Categoria -->
                    <div class="mb-4">
                        <p class="text-xs font-medium text-gray-600 mb-2">Filtrar por categoria:</p>
                        <div class="flex flex-wrap gap-2">
                            <button @click="selectedCategory = 'all'; applyFilters()"
                                    :class="selectedCategory === 'all' ? 'bg-red-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'"
                                    class="px-4 py-2 rounded-full text-sm font-medium transition-all">
                                Todas
                            </button>
                            @foreach ($categorias->keys() as $cat)
                            <button @click="selectedCategory = '{{ $cat }}'; applyFilters()"
                                    :class="selectedCategory === '{{ $cat }}' ? 'bg-red-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'"
                                    class="px-4 py-2 rounded-full text-sm font-medium transition-all">
                                {{ $cat }}
                            </button>
                            @endforeach
                        </div>
                    </div>

                    <!-- Ordenação -->
                    <div class="flex items-center gap-3">
                        <p class="text-xs font-medium text-gray-600">Ordenar por:</p>
                        <select x-model="sortBy" @change="applyFilters()" 
                                class="rounded-lg border border-gray-200 bg-white px-3 py-1.5 text-sm text-gray-900 focus:border-red-500 focus:outline-none focus:ring-2 focus:ring-red-500/50">
                            <option value="name">Nome (A-Z)</option>
                            <option value="price-asc">Preço: Menor para Maior</option>
                            <option value="price-desc">Preço: Maior para Menor</option>
                            <option value="time">Tempo de Preparo</option>
                        </select>
                        <span class="text-xs text-gray-500" x-text="`${visibleItemsCount} itens encontrados`"></span>
                    </div>

                    <p id="menu-search-empty-state" 
                       x-show="visibleItemsCount === 0 && (searchQuery || selectedCategory !== 'all')"
                       x-transition
                       class="mt-3 text-xs text-gray-500">
                        Nenhum item encontrado para a busca atual. Tente usar outras palavras ou limpar o campo de busca.
                    </p>
                </section>
                @forelse ($categorias as $categoria => $itens)
                    <section data-category-section 
                             data-category="{{ $categoria }}"
                             x-show="selectedCategory === 'all' || selectedCategory === '{{ $categoria }}'"
                             x-transition>
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
                                    class="menu-item-card rounded-3xl border border-gray-100 bg-white shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-xl group"
                                    data-menu-item
                                    data-item-id="{{ $item->id }}"
                                    data-search-text="{{ $item->nome }} {{ $item->descricao }} {{ $categoria }}"
                                    data-category="{{ $categoria }}"
                                    data-price="{{ $item->preco_venda }}"
                                    data-time="{{ $item->tempo_preparo_minutos ?? 0 }}"
                                    x-data="{ showImageModal: false }"
                                >
                                    <div class="relative overflow-hidden">
                                        @if ($item->imagem)
                                            <img src="{{ asset('storage/' . $item->imagem) }}" 
                                                 alt="{{ $item->nome }}" 
                                                 @click="showImageModal = true"
                                                 class="h-48 w-full rounded-t-3xl object-cover cursor-pointer transition-transform duration-300 group-hover:scale-110">
                                        @else
                                            <div class="h-48 w-full rounded-t-3xl bg-gradient-to-br from-red-50 to-red-100 flex items-center justify-center">
                                                <svg class="w-16 h-16 text-red-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                                </svg>
                                            </div>
                                        @endif
                                        <!-- Badges -->
                                        <div class="absolute top-3 right-3 flex flex-col gap-2">
                                            @if($item->tempo_preparo_minutos && $item->tempo_preparo_minutos <= 15)
                                                <span class="rounded-full bg-green-500 px-3 py-1 text-xs font-bold text-white shadow-lg">⚡ Rápido</span>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <!-- Modal de Imagem -->
                                    <div x-show="showImageModal" 
                                         @click.away="showImageModal = false"
                                         x-transition:enter="transition ease-out duration-300"
                                         x-transition:enter-start="opacity-0"
                                         x-transition:enter-end="opacity-100"
                                         x-transition:leave="transition ease-in duration-200"
                                         x-transition:leave-start="opacity-100"
                                         x-transition:leave-end="opacity-0"
                                         class="fixed inset-0 z-50 flex items-center justify-center bg-black/80 backdrop-blur-sm"
                                         style="display: none;">
                                        <div class="relative max-w-4xl max-h-[90vh] p-4">
                                            <button @click="showImageModal = false" 
                                                    class="absolute -top-2 -right-2 rounded-full bg-white p-2 text-gray-900 shadow-lg hover:bg-gray-100">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                </svg>
                                            </button>
                                            @if ($item->imagem)
                                                <img src="{{ asset('storage/' . $item->imagem) }}" 
                                                     alt="{{ $item->nome }}" 
                                                     class="max-h-[90vh] rounded-lg object-contain">
                                            @endif
                                        </div>
                                    </div>
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
                                                <form action="{{ route('public.cart.store') }}" method="POST" class="w-full" 
                                                      @submit.prevent="addToCart($event, {{ $item->id }})">
                                                    @csrf
                                                    <input type="hidden" name="cardapio_item_id" value="{{ $item->id }}">
                                                    <button type="submit" 
                                                            class="inline-flex w-full items-center justify-center gap-2 rounded-full bg-red-600 px-6 py-2 text-sm font-semibold text-white shadow transition-all hover:bg-red-500 hover:scale-105 active:scale-95">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                                        </svg>
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
    <!-- Toast Notification -->
    <div x-data="{ show: false, message: '', type: 'success' }" 
         x-show="show"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 translate-y-2"
         class="fixed top-4 right-4 z-50 max-w-sm"
         style="display: none;">
        <div class="rounded-lg bg-white p-4 shadow-xl border border-gray-200 flex items-center gap-3">
            <div class="flex-shrink-0">
                <svg x-show="type === 'success'" class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
            </div>
            <p class="text-sm font-medium text-gray-900" x-text="message"></p>
        </div>
    </div>

    <!-- Carrinho Flutuante Mobile -->
    <div x-data="{ cartOpen: false }" 
         class="lg:hidden fixed bottom-0 left-0 right-0 z-40"
         x-show="cartOpen || {{ $cartCount }} > 0"
         x-transition>
        <button @click="cartOpen = !cartOpen"
                class="w-full bg-red-600 text-white px-6 py-4 flex items-center justify-between shadow-lg">
            <div class="flex items-center gap-3">
                <div class="relative">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    <span class="absolute -top-2 -right-2 flex h-5 w-5 items-center justify-center rounded-full bg-white text-xs font-bold text-red-600">{{ $cartCount }}</span>
                </div>
                <div class="text-left">
                    <p class="text-sm font-semibold">{{ $cartCount }} itens</p>
                    <p class="text-xs opacity-90">R$ {{ number_format($cartTotal, 2, ',', '.') }}</p>
                </div>
            </div>
            <svg class="w-5 h-5 transition-transform" :class="{ 'rotate-180': cartOpen }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        </button>
        
        <!-- Cart Content -->
        <div x-show="cartOpen"
             x-transition
             class="max-h-[70vh] overflow-y-auto bg-white border-t border-gray-200">
            <div class="p-4 space-y-4">
                @forelse ($cartItems as $cartItem)
                    <div class="flex items-center gap-3 rounded-lg border border-gray-100 bg-gray-50 p-3">
                        <div class="flex-1">
                            <p class="text-sm font-semibold text-gray-900">{{ $cartItem['nome'] }}</p>
                            <p class="text-xs text-gray-500">R$ {{ number_format($cartItem['preco'], 2, ',', '.') }} x {{ $cartItem['quantidade'] }}</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <form action="{{ route('public.cart.update', $cartItem['id']) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="quantity" value="{{ max(0, $cartItem['quantidade'] - 1) }}">
                                <button type="submit" class="px-2 py-1 rounded bg-white text-red-600 hover:bg-red-50">−</button>
                            </form>
                            <span class="w-8 text-center font-semibold">{{ $cartItem['quantidade'] }}</span>
                            <form action="{{ route('public.cart.update', $cartItem['id']) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="quantity" value="{{ min(99, $cartItem['quantidade'] + 1) }}">
                                <button type="submit" class="px-2 py-1 rounded bg-white text-red-600 hover:bg-red-50">+</button>
                            </form>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-gray-500 text-center py-4">Carrinho vazio</p>
                @endforelse
                <div class="border-t border-gray-200 pt-4">
                    <div class="flex justify-between mb-4">
                        <span class="font-semibold">Total:</span>
                        <span class="font-bold text-lg">R$ {{ number_format($cartTotal, 2, ',', '.') }}</span>
                    </div>
                    <form action="{{ route('public.cart.checkout') }}" method="POST">
                        @csrf
                        <button type="submit" 
                                class="w-full rounded-full bg-red-600 px-6 py-3 text-sm font-semibold text-white shadow hover:bg-red-500 transition-colors"
                                {{ $cartItems->isEmpty() ? 'disabled' : '' }}>
                            Finalizar Pedido
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function menuFilters() {
            return {
                searchQuery: '',
                selectedCategory: 'all',
                sortBy: 'name',
                visibleItemsCount: {{ $categorias->flatten()->count() }},
                
                init() {
                    this.applyFilters();
                },
                
                applyFilters() {
                    const items = Array.from(document.querySelectorAll('[data-menu-item]'));
                    const sections = Array.from(document.querySelectorAll('[data-category-section]'));
                    const query = this.normalize(this.searchQuery.trim());
                    let visibleCount = 0;
                    
                    items.forEach((item) => {
                        const category = item.getAttribute('data-category');
                        const searchText = this.normalize(item.getAttribute('data-search-text') || '');
                        
                        const matchesCategory = this.selectedCategory === 'all' || category === this.selectedCategory;
                        const matchesSearch = !query || searchText.includes(query);
                        
                        if (matchesCategory && matchesSearch) {
                            item.classList.remove('hidden');
                            visibleCount++;
                        } else {
                            item.classList.add('hidden');
                        }
                    });
                    
                    // Ordenar itens visíveis
                    this.sortItems();
                    
                    // Mostrar/ocultar seções
                    sections.forEach((section) => {
                        const sectionItems = Array.from(section.querySelectorAll('[data-menu-item]'));
                        const hasVisible = sectionItems.some(el => !el.classList.contains('hidden'));
                        section.style.display = hasVisible ? 'block' : 'none';
                    });
                    
                    this.visibleItemsCount = visibleCount;
                },
                
                sortItems() {
                    const sections = Array.from(document.querySelectorAll('[data-category-section]'));
                    
                    sections.forEach((section) => {
                        const items = Array.from(section.querySelectorAll('[data-menu-item]:not(.hidden)'));
                        const container = section.querySelector('.grid');
                        if (!container) return;
                        
                        items.sort((a, b) => {
                            switch(this.sortBy) {
                                case 'price-asc':
                                    return parseFloat(a.getAttribute('data-price')) - parseFloat(b.getAttribute('data-price'));
                                case 'price-desc':
                                    return parseFloat(b.getAttribute('data-price')) - parseFloat(a.getAttribute('data-price'));
                                case 'time':
                                    return (parseInt(a.getAttribute('data-time')) || 999) - (parseInt(b.getAttribute('data-time')) || 999);
                                default: // 'name'
                                    return a.getAttribute('data-search-text').localeCompare(b.getAttribute('data-search-text'));
                            }
                        });
                        
                        items.forEach(item => container.appendChild(item));
                    });
                },
                
                normalize(text) {
                    return text.toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '');
                },
                
                addToCart(event, itemId) {
                    const form = event.target.closest('form');
                    const button = event.target;
                    const originalText = button.innerHTML;
                    
                    button.disabled = true;
                    button.innerHTML = '<svg class="animate-spin w-4 h-4 mx-auto" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';
                    
                    fetch(form.action, {
                        method: 'POST',
                        body: new FormData(form),
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || form.querySelector('input[name="_token"]')?.value
                        }
                    })
                    .then(response => {
                        if (response.ok) {
                            window.location.reload();
                        } else {
                            button.disabled = false;
                            button.innerHTML = originalText;
                        }
                    })
                    .catch(error => {
                        console.error('Erro:', error);
                        button.disabled = false;
                        button.innerHTML = originalText;
                    });
                }
            }
        }
    </script>
@endsection
