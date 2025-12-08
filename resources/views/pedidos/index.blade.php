@extends('layouts.app')

@section('title', 'Pedidos')
@section('subtitle', 'Gerencie todos os pedidos do restaurante')

@section('content')
    <div x-data="pedidosManager()" class="space-y-6">
        <!-- Estatísticas -->
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 sm:gap-6">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 sm:p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs sm:text-sm font-medium text-gray-600 dark:text-gray-400">Total de Pedidos</p>
                        <p class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $stats['total'] }}</p>
                    </div>
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 sm:p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs sm:text-sm font-medium text-gray-600 dark:text-gray-400">Pedidos Hoje</p>
                        <p class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $stats['hoje'] }}</p>
                    </div>
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 sm:p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs sm:text-sm font-medium text-gray-600 dark:text-gray-400">Pendentes</p>
                        <p class="text-2xl sm:text-3xl font-bold text-red-600 dark:text-red-400 mt-2">{{ $stats['pendentes'] }}</p>
                    </div>
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-red-100 dark:bg-red-900/30 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 sm:p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs sm:text-sm font-medium text-gray-600 dark:text-gray-400">Receita Hoje</p>
                        <p class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white mt-2">R$ {{ number_format($stats['receita_hoje'], 2, ',', '.') }}</p>
                    </div>
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtros -->
        <form method="GET" action="{{ route('pedidos.index') }}" class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4">
            <div class="flex flex-wrap items-center gap-3">
                <!-- Busca -->
                <div class="relative flex-1 min-w-[250px]">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-5 w-5 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text"
                           name="search"
                           placeholder="Buscar por número do pedido..."
                           value="{{ request('search') }}"
                           class="pl-10 w-full px-4 py-2.5 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400">
                </div>

                <!-- Filtro por Status -->
                <select name="status"
                        class="px-4 py-2.5 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                    <option value="">Todos os status</option>
                    <option value="pendente" @selected(request('status') === 'pendente')>Pendente</option>
                    <option value="recebido" @selected(request('status') === 'recebido')>Recebido</option>
                    <option value="em_preparo" @selected(request('status') === 'em_preparo')>Em Preparo</option>
                    <option value="pronto" @selected(request('status') === 'pronto')>Pronto</option>
                    <option value="entregue" @selected(request('status') === 'entregue')>Entregue</option>
                    <option value="concluido" @selected(request('status') === 'concluido')>Concluído</option>
                    <option value="cancelado" @selected(request('status') === 'cancelado')>Cancelado</option>
                </select>

                <!-- Filtro por Plataforma -->
                <select name="plataforma"
                        class="px-4 py-2.5 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                    <option value="">Todas as plataformas</option>
                    @foreach($plataformas as $plataforma)
                        <option value="{{ $plataforma }}" @selected(request('plataforma') === $plataforma)>{{ ucfirst($plataforma) }}</option>
                    @endforeach
                </select>

                <!-- Data Início -->
                <input type="date"
                       name="data_inicio"
                       value="{{ request('data_inicio') }}"
                       class="px-4 py-2.5 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">

                <!-- Data Fim -->
                <input type="date"
                       name="data_fim"
                       value="{{ request('data_fim') }}"
                       class="px-4 py-2.5 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">

                <!-- Itens por página -->
                <select name="per_page"
                        class="px-4 py-2.5 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                    <option value="10" @selected(request('per_page') == 10)>10 por página</option>
                    <option value="15" @selected(request('per_page') == 15)>15 por página</option>
                    <option value="25" @selected(request('per_page') == 25)>25 por página</option>
                    <option value="50" @selected(request('per_page') == 50)>50 por página</option>
                </select>

                <!-- Botões -->
                <button type="submit"
                        class="px-5 py-2.5 bg-red-600 text-white text-sm font-semibold rounded-lg hover:bg-red-500 transition-colors shadow-sm">
                    Filtrar
                </button>

                @if(request()->hasAny(['search', 'status', 'plataforma', 'data_inicio', 'data_fim']))
                    <a href="{{ route('pedidos.index') }}"
                       class="px-5 py-2.5 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm font-semibold rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">
                        Limpar
                    </a>
                @endif
            </div>
        </form>

        <!-- Barra de ações em lote -->
        <div x-show="selectedPedidos.length > 0" 
             x-transition
             class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl p-4">
            <div class="flex items-center justify-between flex-wrap gap-3">
                <div class="flex items-center gap-3">
                    <span class="text-sm font-semibold text-red-900 dark:text-red-200">
                        <span x-text="selectedPedidos.length"></span> pedido(s) selecionado(s)
                    </span>
                </div>
                <div class="flex items-center gap-2">
                    <form method="POST" action="{{ route('pedidos.lote') }}" class="inline">
                        @csrf
                        <input type="hidden" name="pedidos" :value="JSON.stringify(selectedPedidos)">
                        <input type="hidden" name="action" value="entregar">
                        <button type="submit"
                                class="px-4 py-2 bg-red-600 text-white text-sm font-semibold rounded-lg hover:bg-red-500 transition-colors">
                            Marcar como Entregue
                        </button>
                    </form>
                    <button @click="selectedPedidos = []"
                            class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm font-semibold rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">
                        Limpar Seleção
                    </button>
                </div>
            </div>
        </div>

        <!-- Tabela -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-900 sticky top-0 z-10">
                        <tr>
                            <th class="px-4 sm:px-6 py-3.5 w-12">
                                <input type="checkbox" 
                                       @change="toggleAll($event.target.checked)"
                                       class="rounded border-gray-300 text-red-600 focus:ring-red-500 dark:bg-gray-700 dark:border-gray-600">
                            </th>
                            <th class="px-4 sm:px-6 py-3.5 text-left text-xs font-semibold text-gray-900 dark:text-white uppercase tracking-wider cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-800"
                                @click="sortBy('id')">
                                <div class="flex items-center gap-2">
                                    <span>Pedido</span>
                                    <svg class="w-4 h-4" :class="{'opacity-0': currentSort !== 'id'}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="sortOrder === 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7'"/>
                                    </svg>
                                </div>
                            </th>
                            <th class="px-4 sm:px-6 py-3.5 text-left text-xs font-semibold text-gray-900 dark:text-white uppercase tracking-wider">Cliente</th>
                            <th class="px-4 sm:px-6 py-3.5 text-left text-xs font-semibold text-gray-900 dark:text-white uppercase tracking-wider">Plataforma</th>
                            <th class="px-4 sm:px-6 py-3.5 text-left text-xs font-semibold text-gray-900 dark:text-white uppercase tracking-wider cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-800"
                                @click="sortBy('status')">
                                <div class="flex items-center gap-2">
                                    <span>Status</span>
                                    <svg class="w-4 h-4" :class="{'opacity-0': currentSort !== 'status'}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="sortOrder === 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7'"/>
                                    </svg>
                                </div>
                            </th>
                            <th class="px-4 sm:px-6 py-3.5 text-left text-xs font-semibold text-gray-900 dark:text-white uppercase tracking-wider cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-800"
                                @click="sortBy('valor_total')">
                                <div class="flex items-center gap-2">
                                    <span>Valor</span>
                                    <svg class="w-4 h-4" :class="{'opacity-0': currentSort !== 'valor_total'}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="sortOrder === 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7'"/>
                                    </svg>
                                </div>
                            </th>
                            <th class="px-4 sm:px-6 py-3.5 text-left text-xs font-semibold text-gray-900 dark:text-white uppercase tracking-wider cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-800"
                                @click="sortBy('data_hora_pedido')">
                                <div class="flex items-center gap-2">
                                    <span>Data/Hora</span>
                                    <svg class="w-4 h-4" :class="{'opacity-0': currentSort !== 'data_hora_pedido'}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="sortOrder === 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7'"/>
                                    </svg>
                                </div>
                            </th>
                            <th class="px-4 sm:px-6 py-3.5 text-left text-xs font-semibold text-gray-900 dark:text-white uppercase tracking-wider">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-800">
                        @forelse ($pedidos as $pedido)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                <td class="px-4 sm:px-6 py-4" @click.stop>
                                    <input type="checkbox" 
                                           value="{{ $pedido->id }}"
                                           @change="togglePedido({{ $pedido->id }}, $event.target.checked)"
                                           class="rounded border-gray-300 text-red-600 focus:ring-red-500 dark:bg-gray-700 dark:border-gray-600">
                                </td>
                                <td class="px-4 sm:px-6 py-4 cursor-pointer" @click="showPedidoDetails({{ $pedido->id }})">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-gradient-to-br from-red-500 to-red-600 rounded-lg flex items-center justify-center text-white font-bold text-sm shadow-sm">
                                            #{{ $pedido->id }}
                                        </div>
                                        <div>
                                            <p class="font-semibold text-gray-900 dark:text-white">Pedido #{{ $pedido->id }}</p>
                                            @if($pedido->numero_pedido_externo)
                                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Ext: {{ $pedido->numero_pedido_externo }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 sm:px-6 py-4 cursor-pointer" @click="showPedidoDetails({{ $pedido->id }})">
                                    @if($pedido->usuario)
                                        <div class="flex items-center gap-2">
                                            <div class="w-8 h-8 bg-gray-200 dark:bg-gray-700 rounded-full flex items-center justify-center text-gray-600 dark:text-gray-300 text-xs font-semibold">
                                                {{ strtoupper(substr($pedido->usuario->name, 0, 2)) }}
                                            </div>
                                            <span class="text-sm text-gray-900 dark:text-white">{{ $pedido->usuario->name }}</span>
                                        </div>
                                    @else
                                        <span class="text-sm text-gray-500 dark:text-gray-400">—</span>
                                    @endif
                                </td>
                                <td class="px-4 sm:px-6 py-4 cursor-pointer" @click="showPedidoDetails({{ $pedido->id }})">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400">
                                        {{ ucfirst($pedido->plataforma_origem) }}
                                    </span>
                                </td>
                                <td class="px-4 sm:px-6 py-4 cursor-pointer" @click="showPedidoDetails({{ $pedido->id }})">
                                    @php
                                        $statusConfig = [
                                            'pendente' => [
                                                'label' => 'Pendente',
                                                'class' => 'bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 border-red-200 dark:border-red-800'
                                            ],
                                            'recebido' => [
                                                'label' => 'Recebido',
                                                'class' => 'bg-orange-100 dark:bg-orange-900/30 text-orange-700 dark:text-orange-400 border-orange-200 dark:border-orange-800'
                                            ],
                                            'em_preparo' => [
                                                'label' => 'Em Preparo',
                                                'class' => 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400 border-yellow-200 dark:border-yellow-800'
                                            ],
                                            'pronto' => [
                                                'label' => 'Pronto',
                                                'class' => 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 border-blue-200 dark:border-blue-800'
                                            ],
                                            'entregue' => [
                                                'label' => 'Entregue',
                                                'class' => 'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 border-green-200 dark:border-green-800'
                                            ],
                                            'concluido' => [
                                                'label' => 'Concluído',
                                                'class' => 'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 border-green-200 dark:border-green-800'
                                            ],
                                            'cancelado' => [
                                                'label' => 'Cancelado',
                                                'class' => 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 border-gray-200 dark:border-gray-600'
                                            ],
                                        ];
                                        $status = $statusConfig[$pedido->status] ?? ['label' => ucfirst($pedido->status), 'class' => 'bg-gray-100 text-gray-700'];
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold border {{ $status['class'] }}">
                                        {{ $status['label'] }}
                                    </span>
                                </td>
                                <td class="px-4 sm:px-6 py-4 cursor-pointer" @click="showPedidoDetails({{ $pedido->id }})">
                                    <span class="text-sm font-semibold text-gray-900 dark:text-white">
                                        {{ $pedido->valor_total ? 'R$ ' . number_format($pedido->valor_total, 2, ',', '.') : '—' }}
                                    </span>
                                </td>
                                <td class="px-4 sm:px-6 py-4 text-sm text-gray-600 dark:text-gray-400 cursor-pointer" @click="showPedidoDetails({{ $pedido->id }})">
                                    {{ $pedido->data_hora_pedido?->format('d/m/Y H:i') ?? '—' }}
                                </td>
                                <td class="px-4 sm:px-6 py-4" @click.stop>
                                    <div class="flex items-center gap-2">
                                        @if($pedido->status !== 'cancelado' && $pedido->status !== 'concluido' && $pedido->status !== 'entregue')
                                            @php
                                                $nextStatus = null;
                                                switch($pedido->status) {
                                                    case 'pendente':
                                                        $nextStatus = ['value' => 'recebido', 'label' => 'Receber'];
                                                        break;
                                                    case 'recebido':
                                                        $nextStatus = ['value' => 'em_preparo', 'label' => 'Em Preparo'];
                                                        break;
                                                    case 'em_preparo':
                                                        $nextStatus = ['value' => 'pronto', 'label' => 'Pronto'];
                                                        break;
                                                    case 'pronto':
                                                        $nextStatus = ['value' => 'entregue', 'label' => 'Entregar'];
                                                        break;
                                                }
                                            @endphp
                                            @if($nextStatus)
                                                <form method="POST" action="{{ route('pedidos.atualizarStatus', $pedido) }}" class="inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status" value="{{ $nextStatus['value'] }}">
                                                    <button type="submit"
                                                            class="px-3 py-1.5 bg-green-600 hover:bg-green-500 text-white text-xs font-semibold rounded-lg transition-colors flex items-center gap-1.5 shadow-sm">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                        </svg>
                                                        {{ $nextStatus['label'] }}
                                                    </button>
                                                </form>
                                            @endif
                                        @endif
                                        @if($pedido->status !== 'cancelado')
                                            <form method="POST" action="{{ route('pedidos.atualizarStatus', $pedido) }}" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="cancelado">
                                                <button type="submit"
                                                        onclick="return confirm('Tem certeza que deseja cancelar este pedido?')"
                                                        class="px-3 py-1.5 bg-red-600 hover:bg-red-500 text-white text-xs font-semibold rounded-lg transition-colors flex items-center gap-1.5 shadow-sm">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                    </svg>
                                                    Cancelar
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-16 text-center">
                                    <svg class="w-16 h-16 mx-auto text-gray-400 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                    </svg>
                                    <p class="text-gray-500 dark:text-gray-400 font-medium">Nenhum pedido encontrado.</p>
                                    <p class="text-sm text-gray-400 dark:text-gray-500 mt-1">Tente ajustar os filtros ou aguarde novos pedidos.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($pedidos->hasPages())
                <div class="border-t border-gray-200 dark:border-gray-700 px-6 py-4 bg-gray-50 dark:bg-gray-900">
                    {{ $pedidos->links() }}
                </div>
            @endif
        </div>

        <!-- Modal de Detalhes do Pedido -->
        <div x-show="showDetails"
              x-cloak
              @keydown.escape.window="showDetails = false"
              class="fixed inset-0 z-50 overflow-y-auto"
              x-transition>
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="showDetails"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75 dark:bg-opacity-50"
                     @click="showDetails = false"></div>

                <div x-show="showDetails"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-3xl sm:w-full">
                    <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Detalhes do Pedido #<span x-text="selectedPedidoId"></span></h3>
                            <button @click="showDetails = false" class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                        <div id="pedido-details-content" class="space-y-4">
                            <!-- Conteúdo será carregado via AJAX -->
                            <div class="text-center py-8">
                                <svg class="animate-spin h-8 w-8 text-red-600 mx-auto" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <p class="mt-4 text-gray-600 dark:text-gray-400">Carregando detalhes...</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function pedidosManager() {
            return {
                selectedPedidos: [],
                showDetails: false,
                selectedPedidoId: null,
                currentSort: '{{ request('sort', 'data_hora_pedido') }}',
                sortOrder: '{{ request('order', 'desc') }}',

                togglePedido(id, checked) {
                    if (checked) {
                        this.selectedPedidos.push(id);
                    } else {
                        this.selectedPedidos = this.selectedPedidos.filter(p => p !== id);
                    }
                },

                toggleAll(checked) {
                    const checkboxes = document.querySelectorAll('tbody input[type="checkbox"]');
                    checkboxes.forEach(cb => {
                        cb.checked = checked;
                        const id = parseInt(cb.value);
                        if (checked && !this.selectedPedidos.includes(id)) {
                            this.selectedPedidos.push(id);
                        } else if (!checked) {
                            this.selectedPedidos = this.selectedPedidos.filter(p => p !== id);
                        }
                    });
                },

                sortBy(column) {
                    if (this.currentSort === column) {
                        this.sortOrder = this.sortOrder === 'asc' ? 'desc' : 'asc';
                    } else {
                        this.currentSort = column;
                        this.sortOrder = 'asc';
                    }
                    window.location.href = '{{ route('pedidos.index') }}?sort=' + column + '&order=' + this.sortOrder + '&' + new URLSearchParams(window.location.search).toString().replace(/[?&]sort=[^&]*/, '').replace(/[?&]order=[^&]*/, '');
                },

                showPedidoDetails(pedidoId) {
                    this.selectedPedidoId = pedidoId;
                    this.showDetails = true;
                    
                    fetch(`{{ url('/pedidos') }}/${pedidoId}/detalhes`)
                        .then(response => {
                            if (!response.ok) throw new Error('Erro ao carregar');
                            return response.text();
                        })
                        .then(html => {
                            document.getElementById('pedido-details-content').innerHTML = html;
                        })
                        .catch(error => {
                            document.getElementById('pedido-details-content').innerHTML = 
                                '<div class="text-center py-8 text-red-600 dark:text-red-400">Erro ao carregar detalhes do pedido.</div>';
                        });
                }
            }
        }
    </script>
@endsection
