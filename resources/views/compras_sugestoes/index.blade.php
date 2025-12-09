@extends('layouts.app')

@section('title', 'Sugestões de compra')
@section('subtitle', 'Planeje o reabastecimento com inteligência')

@section('actions')
    <a href="{{ route('compras-sugestoes.create') }}" class="rounded-full bg-red-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-red-500 transition-colors flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Nova sugestão
    </a>
@endsection

@section('content')
    <div class="space-y-6">
        <!-- Estatísticas Interativas -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <a href="{{ route('compras-sugestoes.index', ['status' => '']) }}" 
               class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-all cursor-pointer">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total de Sugestões</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $stats['total'] }}</p>
                    </div>
                    <div class="w-12 h-12 bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                        </svg>
                    </div>
                </div>
            </a>

            <a href="{{ route('compras-sugestoes.index', ['status' => 'pendente']) }}" 
               class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border {{ request('status') === 'pendente' ? 'border-red-500 ring-2 ring-red-200 dark:ring-red-900/50' : 'border-gray-200 dark:border-gray-700' }} p-6 hover:shadow-md transition-all cursor-pointer">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Pendentes</p>
                        <p class="text-3xl font-bold text-red-600 dark:text-red-400 mt-2">{{ $stats['pendentes'] }}</p>
                    </div>
                    <div class="w-12 h-12 bg-red-100 dark:bg-red-900/30 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </a>

            <a href="{{ route('compras-sugestoes.index', ['status' => 'aprovada']) }}" 
               class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border {{ request('status') === 'aprovada' ? 'border-green-500 ring-2 ring-green-200 dark:ring-green-900/50' : 'border-gray-200 dark:border-gray-700' }} p-6 hover:shadow-md transition-all cursor-pointer">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Aprovadas</p>
                        <p class="text-3xl font-bold text-green-600 dark:text-green-400 mt-2">{{ $stats['aprovadas'] }}</p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </a>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Valor Total</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">R$ {{ number_format($stats['valor_total'], 2, ',', '.') }}</p>
                    </div>
                    <div class="w-12 h-12 bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtros Rápidos (Chips) -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4">
            <div class="flex flex-wrap items-center gap-2 mb-4">
                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Filtros rápidos:</span>
                <a href="{{ route('compras-sugestoes.index', request()->except('status')) }}" 
                   class="px-3 py-1.5 text-xs font-medium rounded-full transition-colors {{ !request('status') ? 'bg-red-600 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }}">
                    Todos
                </a>
                @foreach($statusList as $status)
                    <a href="{{ route('compras-sugestoes.index', array_merge(request()->all(), ['status' => $status])) }}" 
                       class="px-3 py-1.5 text-xs font-medium rounded-full transition-colors {{ request('status') === $status ? 'bg-red-600 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }}">
                        {{ ucfirst($status) }}
                    </a>
                @endforeach
            </div>

            <!-- Filtros Avançados -->
            <form method="GET" action="{{ route('compras-sugestoes.index') }}">
                <div class="flex flex-wrap items-center gap-3">
                    <!-- Busca -->
                    <div class="relative flex-1 min-w-[250px]">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-5 w-5 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <input type="text"
                               name="search"
                               placeholder="Buscar por insumo, status ou justificativa..."
                               value="{{ request('search') }}"
                               class="pl-10 w-full px-4 py-2.5 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400">
                    </div>

                    <!-- Filtro por Status -->
                    <select name="status"
                            class="px-4 py-2.5 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                        <option value="">Todos os status</option>
                        @foreach($statusList as $status)
                            <option value="{{ $status }}" @selected(request('status') === $status)>{{ ucfirst($status) }}</option>
                        @endforeach
                    </select>

                    <!-- Filtro por Data Início -->
                    <input type="date"
                           name="data_inicio"
                           value="{{ request('data_inicio') }}"
                           placeholder="Data início"
                           class="px-4 py-2.5 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">

                    <!-- Filtro por Data Fim -->
                    <input type="date"
                           name="data_fim"
                           value="{{ request('data_fim') }}"
                           placeholder="Data fim"
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

                    @if(request()->hasAny(['search', 'status', 'data_inicio', 'data_fim']))
                        <a href="{{ route('compras-sugestoes.index') }}"
                           class="px-5 py-2.5 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm font-semibold rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">
                            Limpar
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Cards de Sugestões -->
        <div class="space-y-4">
            @forelse ($sugestoes as $sugestao)
                @php
                    $insumo = $sugestao->insumo;
                    $estoqueAtual = $insumo?->estoque?->quantidade_atual ?? 0;
                    $pontoReposicao = $insumo?->ponto_reposicao_minimo ?? 0;
                    $custoEstimado = $insumo ? ($sugestao->quantidade_sugerida * ($insumo->custo_unitario ?? 0)) : 0;
                    $percentualEstoque = $pontoReposicao > 0 ? ($estoqueAtual / $pontoReposicao) * 100 : 0;
                @endphp
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border-l-4 {{ $sugestao->status === 'pendente' ? 'border-red-500' : ($sugestao->status === 'aprovada' ? 'border-green-500' : 'border-gray-300 dark:border-gray-600') }} p-6 hover:shadow-md transition-shadow">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex items-start gap-4 flex-1">
                            <!-- Ícone -->
                            <div class="w-12 h-12 {{ $sugestao->status === 'pendente' ? 'bg-red-100 dark:bg-red-900/30' : ($sugestao->status === 'aprovada' ? 'bg-green-100 dark:bg-green-900/30' : 'bg-gray-100 dark:bg-gray-700') }} rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 {{ $sugestao->status === 'pendente' ? 'text-red-600 dark:text-red-400' : ($sugestao->status === 'aprovada' ? 'text-green-600 dark:text-green-400' : 'text-gray-600 dark:text-gray-400') }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                            </div>

                            <!-- Conteúdo -->
                            <div class="flex-1 min-w-0">
                                <div class="flex items-start justify-between gap-3 mb-3">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2 mb-1">
                                            <h3 class="font-semibold text-gray-900 dark:text-white text-lg">
                                                @if($insumo)
                                                    <a href="{{ route('insumos.edit', $insumo) }}" class="hover:text-red-600 dark:hover:text-red-400 transition-colors">
                                                        {{ $insumo->nome }}
                                                    </a>
                                                @else
                                                    Insumo não encontrado
                                                @endif
                                            </h3>
                                            @if($insumo && $insumo->estoque)
                                                <a href="{{ route('estoque.index', ['search' => $insumo->nome]) }}" 
                                                   class="text-xs text-gray-500 dark:text-gray-400 hover:text-red-600 dark:hover:text-red-400 transition-colors"
                                                   title="Ver estoque">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                                    </svg>
                                                </a>
                                            @endif
                                        </div>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ $sugestao->data_geracao?->format('d/m/Y') ?? 'Data não informada' }}
                                            @if($sugestao->periodo_analise_dias)
                                                • Análise: {{ $sugestao->periodo_analise_dias }} dias
                                            @endif
                                        </p>
                                    </div>

                                    <!-- Badge de Status -->
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold
                                        {{ $sugestao->status === 'pendente' ? 'bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400' :
                                           ($sugestao->status === 'aprovada' ? 'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400' :
                                            'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300') }}">
                                        {{ ucfirst($sugestao->status) }}
                                    </span>
                                </div>

                                <!-- Informações de Estoque e Sugestão -->
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-3">
                                    <div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Estoque Atual</p>
                                        <p class="text-lg font-bold {{ $estoqueAtual <= $pontoReposicao ? 'text-red-600 dark:text-red-400' : 'text-gray-900 dark:text-white' }}">
                                            {{ number_format($estoqueAtual, 2, ',', '.') }}
                                            @if($insumo)
                                                <span class="text-sm font-normal text-gray-500 dark:text-gray-400">{{ $insumo->unidade_medida }}</span>
                                            @endif
                                        </p>
                                        @if($pontoReposicao > 0)
                                            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-1.5 mt-1">
                                                <div class="h-1.5 rounded-full {{ $percentualEstoque <= 100 ? 'bg-red-500' : 'bg-green-500' }}" 
                                                     style="width: {{ min(100, $percentualEstoque) }}%"></div>
                                            </div>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                                Mínimo: {{ number_format($pontoReposicao, 2, ',', '.') }} {{ $insumo->unidade_medida ?? '' }}
                                            </p>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Quantidade Sugerida</p>
                                        <p class="text-lg font-bold text-gray-900 dark:text-white">
                                            {{ number_format($sugestao->quantidade_sugerida, 2, ',', '.') }}
                                            @if($insumo)
                                                <span class="text-sm font-normal text-gray-500 dark:text-gray-400">{{ $insumo->unidade_medida }}</span>
                                            @endif
                                        </p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                            Estoque após compra: {{ number_format($estoqueAtual + $sugestao->quantidade_sugerida, 2, ',', '.') }} {{ $insumo->unidade_medida ?? '' }}
                                        </p>
                                    </div>
                                    @if($insumo && $insumo->custo_unitario)
                                        <div>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Custo Estimado</p>
                                            <p class="text-lg font-bold text-gray-900 dark:text-white">
                                                R$ {{ number_format($custoEstimado, 2, ',', '.') }}
                                            </p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                                Unitário: R$ {{ $insumo->custo_unitario < 0.01 ? number_format($insumo->custo_unitario, 6, ',', '.') : number_format($insumo->custo_unitario, 2, ',', '.') }}
                                            </p>
                                        </div>
                                    @endif
                                </div>

                                @if($sugestao->justificativa)
                                    <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-3 mt-3">
                                        <p class="text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Justificativa:</p>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ $sugestao->justificativa }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Ações -->
                        <div class="flex flex-col items-end gap-2">
                            @if($sugestao->status === 'pendente')
                                <div class="flex items-center gap-2">
                                    <button onclick="updateStatus({{ $sugestao->id }}, 'aprovada')"
                                            class="p-2 text-green-600 dark:text-green-400 hover:text-green-700 dark:hover:text-green-300 hover:bg-green-50 dark:hover:bg-green-900/20 rounded-lg transition-colors"
                                            title="Aprovar">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    </button>
                                    <button onclick="updateStatus({{ $sugestao->id }}, 'rejeitada')"
                                            class="p-2 text-red-600 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors"
                                            title="Rejeitar">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                </div>
                            @endif
                            <div class="flex items-center gap-2">
                                <a href="{{ route('compras-sugestoes.edit', $sugestao) }}"
                                   class="p-2 text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors"
                                   title="Editar">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                <button onclick="confirmDelete({{ $sugestao->id }})"
                                        class="p-2 text-red-600 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors"
                                        title="Excluir">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-16 text-center">
                    <svg class="w-16 h-16 mx-auto text-gray-400 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                    </svg>
                    <p class="text-gray-500 dark:text-gray-400 font-medium">Nenhuma sugestão encontrada.</p>
                    <p class="text-sm text-gray-400 dark:text-gray-500 mt-1">
                        @if(request()->hasAny(['search', 'status', 'data_inicio', 'data_fim']))
                            Tente ajustar os filtros ou
                        @endif
                        <a href="{{ route('compras-sugestoes.create') }}" class="text-red-600 dark:text-red-400 hover:underline">crie uma nova sugestão</a>
                    </p>
                </div>
            @endforelse
        </div>

        @if($sugestoes->hasPages())
            <div class="flex justify-center">
                {{ $sugestoes->links() }}
            </div>
        @endif
    </div>
@endsection

@section('scripts')
<script>
    function updateStatus(sugestaoId, status) {
        const statusText = status === 'aprovada' ? 'aprovar' : 'rejeitar';
        if (!confirm(`Deseja realmente ${statusText} esta sugestão?`)) {
            return;
        }

        fetch(`/compras-sugestoes/${sugestaoId}/status`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ status: status })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.reload();
            } else {
                alert('Erro ao atualizar status: ' + (data.message || 'Erro desconhecido'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Erro ao atualizar status. Tente novamente.');
        });
    }

    function confirmDelete(sugestaoId) {
        if (confirm('⚠️ Atenção: Esta ação é permanente e não pode ser desfeita. Deseja realmente excluir esta sugestão?')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/compras-sugestoes/${sugestaoId}`;
            
            const csrf = document.createElement('input');
            csrf.type = 'hidden';
            csrf.name = '_token';
            csrf.value = '{{ csrf_token() }}';
            form.appendChild(csrf);
            
            const method = document.createElement('input');
            method.type = 'hidden';
            method.name = '_method';
            method.value = 'DELETE';
            form.appendChild(method);
            
            document.body.appendChild(form);
            form.submit();
        }
    }
</script>
@endsection
