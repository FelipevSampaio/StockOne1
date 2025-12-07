@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard Administrativo')

@section('content')
    <!-- Hero Section com Gradiente -->
    <div class="relative mb-8 overflow-hidden rounded-2xl bg-gradient-to-br from-red-600 via-red-700 to-red-800 p-8 shadow-2xl">
        <div class="absolute top-0 right-0 -mt-4 -mr-4 h-40 w-40 rounded-full bg-white/10 blur-3xl"></div>
        <div class="absolute bottom-0 left-0 -mb-8 -ml-8 h-40 w-40 rounded-full bg-white/10 blur-3xl"></div>

        <div class="relative flex flex-wrap items-center justify-between gap-6">
            <div class="flex-1 min-w-[300px]">
                <h1 class="text-3xl font-bold text-white mb-2 flex items-center gap-3">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    Painel de Controle
                </h1>
                <p class="text-red-100 text-sm">Gerencie restaurantes, usuários e monitore o desempenho da plataforma</p>

                <div class="flex items-center gap-4 mt-4">
                    <div class="flex items-center gap-2 text-white/90">
                        <div class="w-2 h-2 rounded-full bg-green-400 animate-pulse"></div>
                        <span class="text-sm font-medium">Sistema Ativo</span>
                    </div>
                    <div class="text-white/70 text-sm">
                        <span id="lastUpdate">Atualizado agora</span>
                    </div>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <a href="{{ route('admin.users.create') }}" class="group relative overflow-hidden bg-white text-red-600 hover:text-white px-5 py-3 rounded-xl font-semibold shadow-lg hover:shadow-xl transition-all duration-300 flex items-center gap-2">
                    <span class="absolute inset-0 bg-gradient-to-r from-red-600 to-red-700 translate-y-full group-hover:translate-y-0 transition-transform duration-300"></span>
                    <svg class="w-5 h-5 relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                    </svg>
                    <span class="relative z-10">Novo Usuário</span>
                </a>
                <a href="{{ route('admin.restaurantes.create') }}" class="bg-white/10 backdrop-blur-sm text-white border-2 border-white/30 hover:bg-white/20 px-5 py-3 rounded-xl font-semibold shadow-lg hover:shadow-xl transition-all duration-300 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    <span>Novo Restaurante</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Cards de Estatísticas com Animação Stagger -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8" x-data="{ loaded: false }" x-init="setTimeout(() => loaded = true, 100)">
        <!-- Total de Usuários -->
        <a href="{{ route('admin.users.index') }}"
           class="group bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-100 dark:border-gray-700 hover:shadow-2xl hover:-translate-y-2 hover:border-red-300 dark:hover:border-red-700 transition-all duration-300 cursor-pointer block relative overflow-hidden"
           x-bind:class="loaded ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-4'"
           x-bind:style="`transition-delay: ${0 * 100}ms`">
            <!-- Decorative Background -->
            <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-red-100 to-transparent dark:from-red-900/20 rounded-full -mr-16 -mt-16 group-hover:scale-150 transition-transform duration-500"></div>

            <div class="flex items-center justify-between mb-4 relative z-10">
                <div class="p-3 bg-gradient-to-br from-red-100 to-red-200 dark:from-red-900/50 dark:to-red-800/40 rounded-xl group-hover:scale-110 group-hover:rotate-6 transition-all duration-300 shadow-lg">
                    <svg class="w-6 h-6 text-red-700 dark:text-red-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
                @if($crescimentoUsuarios != 0)
                    <span class="text-xs font-bold {{ $crescimentoUsuarios > 0 ? 'text-green-600 dark:text-green-400 bg-green-50 dark:bg-green-900/20' : 'text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-900/20' }} px-3 py-1.5 rounded-lg shadow-sm group-hover:shadow-md transition-shadow">
                        {{ $crescimentoUsuarios > 0 ? '↗' : '↘' }} {{ $crescimentoUsuarios > 0 ? '+' : '' }}{{ $crescimentoUsuarios }}%
                    </span>
                @endif
            </div>
            <h3 class="text-gray-600 dark:text-gray-300 text-sm font-semibold mb-2 uppercase tracking-wider relative z-10">Total de Usuários</h3>
            <div class="flex items-end gap-2 mb-3 relative z-10">
                <p class="text-4xl font-extrabold text-gray-900 dark:text-white group-hover:text-red-600 dark:group-hover:text-red-300 transition-colors duration-300">{{ $totalUsers }}</p>
                @if($crescimentoUsuarios != 0)
                    <span class="text-xs {{ $crescimentoUsuarios > 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }} font-medium mb-1 flex items-center">
                        @if($crescimentoUsuarios > 0)
                            <svg class="w-3 h-3 mr-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                        @else
                            <svg class="w-3 h-3 mr-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M14.707 10.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L9 12.586V5a1 1 0 012 0v7.586l2.293-2.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        @endif
                        {{ abs($crescimentoUsuarios) }}%
                    </span>
                @endif
            </div>
            <div class="flex items-center gap-3 text-xs relative z-10">
                <div class="flex items-center gap-1.5 px-2.5 py-1.5 bg-green-100 dark:bg-green-900/40 rounded-lg">
                    <div class="w-2 h-2 rounded-full bg-green-600 dark:bg-green-400"></div>
                    <span class="text-green-800 dark:text-green-300 font-bold">{{ $totalUsersActive }}</span>
                    <span class="text-green-700 dark:text-green-300">ativos</span>
                </div>
                <div class="flex items-center gap-1.5 px-2.5 py-1.5 bg-red-100 dark:bg-red-900/40 rounded-lg">
                    <div class="w-2 h-2 rounded-full bg-red-600 dark:bg-red-400"></div>
                    <span class="text-red-800 dark:text-red-300 font-bold">{{ $totalUsersInactive }}</span>
                    <span class="text-red-700 dark:text-red-300">inativos</span>
                </div>
            </div>
        </a>

        <!-- Administradores -->
        <a href="{{ route('admin.users.index', ['role' => 'admin']) }}"
           class="group bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-100 dark:border-gray-700 hover:shadow-2xl hover:-translate-y-2 hover:border-red-300 dark:hover:border-red-700 transition-all duration-300 cursor-pointer block relative overflow-hidden"
           x-bind:class="loaded ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-4'"
           x-bind:style="`transition-delay: ${1 * 100}ms`">
            <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-purple-100 to-transparent dark:from-purple-900/20 rounded-full -mr-16 -mt-16 group-hover:scale-150 transition-transform duration-500"></div>

            <div class="flex items-center justify-between mb-4 relative z-10">
                <div class="p-3 bg-gradient-to-br from-purple-100 to-purple-200 dark:from-purple-900/50 dark:to-purple-800/40 rounded-xl group-hover:scale-110 group-hover:rotate-6 transition-all duration-300 shadow-lg">
                    <svg class="w-6 h-6 text-purple-700 dark:text-purple-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
            </div>
            <h3 class="text-gray-600 dark:text-gray-300 text-sm font-semibold mb-2 uppercase tracking-wider relative z-10">Administradores</h3>
            <p class="text-4xl font-extrabold text-gray-900 dark:text-white mb-3 relative z-10 group-hover:text-purple-600 dark:group-hover:text-purple-300 transition-colors duration-300">{{ $totalAdmins }}</p>
            <p class="text-xs text-gray-700 dark:text-gray-300 relative z-10 flex items-center gap-2">
                <span class="inline-flex items-center px-2.5 py-1.5 bg-blue-100 dark:bg-blue-900/40 rounded-lg">
                    <svg class="w-3 h-3 text-blue-700 dark:text-blue-300 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                    </svg>
                    <span class="text-blue-800 dark:text-blue-300 font-bold">{{ $totalRegularUsers }}</span>
                    <span class="text-blue-700 dark:text-blue-300">usuários</span>
                </span>
            </p>
        </a>

        <!-- Restaurantes -->
        <a href="{{ route('admin.restaurantes.index') }}"
           class="group bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-100 dark:border-gray-700 hover:shadow-2xl hover:-translate-y-2 hover:border-red-300 dark:hover:border-red-700 transition-all duration-300 cursor-pointer block relative overflow-hidden"
           x-bind:class="loaded ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-4'"
           x-bind:style="`transition-delay: ${2 * 100}ms`">
            <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-amber-100 to-transparent dark:from-amber-900/20 rounded-full -mr-16 -mt-16 group-hover:scale-150 transition-transform duration-500"></div>

            <div class="flex items-center justify-between mb-4 relative z-10">
                <div class="p-3 bg-gradient-to-br from-amber-100 to-amber-200 dark:from-amber-900/50 dark:to-amber-800/40 rounded-xl group-hover:scale-110 group-hover:rotate-6 transition-all duration-300 shadow-lg">
                    <svg class="w-6 h-6 text-amber-700 dark:text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                @if($crescimentoRestaurantes != 0)
                    <span class="text-xs font-bold {{ $crescimentoRestaurantes > 0 ? 'text-green-600 dark:text-green-400 bg-green-50 dark:bg-green-900/20' : 'text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-900/20' }} px-3 py-1.5 rounded-lg shadow-sm group-hover:shadow-md transition-shadow">
                        {{ $crescimentoRestaurantes > 0 ? '↗' : '↘' }} {{ $crescimentoRestaurantes > 0 ? '+' : '' }}{{ $crescimentoRestaurantes }}%
                    </span>
                @endif
            </div>
            <h3 class="text-gray-600 dark:text-gray-300 text-sm font-semibold mb-2 uppercase tracking-wider relative z-10">Restaurantes</h3>
            <div class="flex items-baseline gap-3 mb-3 relative z-10">
                <p class="text-4xl font-extrabold text-gray-900 dark:text-white group-hover:text-amber-600 dark:group-hover:text-amber-300 transition-colors duration-300">{{ $totalRestaurantes }}</p>
                <span class="text-xs text-blue-700 dark:text-blue-300 font-bold px-2.5 py-1.5 bg-blue-100 dark:bg-blue-900/40 rounded-lg">
                    +{{ $restaurantesNovosEsteMes }} este mês
                </span>
            </div>
            <div class="flex items-center gap-3 text-xs relative z-10">
                <div class="flex items-center gap-1.5 px-2.5 py-1.5 bg-green-100 dark:bg-green-900/40 rounded-lg">
                    <div class="w-2 h-2 rounded-full bg-green-600 dark:bg-green-400 animate-pulse"></div>
                    <span class="text-green-800 dark:text-green-300 font-bold">{{ $restaurantesAtivos }}</span>
                    <span class="text-green-700 dark:text-green-300">ativos</span>
                </div>
                <div class="flex items-center gap-1.5 px-2.5 py-1.5 bg-gray-100 dark:bg-gray-700 rounded-lg">
                    <div class="w-2 h-2 rounded-full bg-gray-600 dark:bg-gray-400"></div>
                    <span class="text-gray-800 dark:text-gray-300 font-bold">{{ $restaurantesInativos }}</span>
                    <span class="text-gray-700 dark:text-gray-300">inativos</span>
                </div>
            </div>
        </a>

        <!-- Logs de Auditoria -->
        <a href="{{ route('admin.audit-logs.index') }}" class="group bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-100 dark:border-gray-700 hover:shadow-2xl hover:-translate-y-2 hover:border-red-300 dark:hover:border-red-700 transition-all duration-300 cursor-pointer block relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-blue-100 to-transparent dark:from-blue-900/20 rounded-full -mr-16 -mt-16 group-hover:scale-150 transition-transform duration-500"></div>

            <div class="flex items-center justify-between mb-4 relative z-10">
                <div class="p-3 bg-blue-100 dark:bg-blue-900/50 rounded-lg shadow-lg group-hover:scale-110 group-hover:rotate-6 transition-all duration-300">
                    <svg class="w-6 h-6 text-blue-700 dark:text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
            </div>
            <h3 class="text-gray-600 dark:text-gray-300 text-sm font-semibold mb-1 relative z-10">Logs de Auditoria</h3>
            <p class="text-3xl font-bold text-gray-900 dark:text-white relative z-10 group-hover:text-blue-600 dark:group-hover:text-blue-300 transition-colors duration-300">{{ $totalAuditLogs }}</p>
            <p class="text-xs text-gray-600 dark:text-gray-300 mt-2 relative z-10">Registros de atividades</p>
        </a>

        <!-- Pedidos Hoje -->
        <a href="#" class="group bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-100 dark:border-gray-700 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 cursor-pointer block">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-red-100 dark:bg-red-900/50 rounded-lg shadow-lg">
                    <svg class="w-6 h-6 text-red-700 dark:text-red-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                </div>
                <span class="text-xs font-bold text-blue-700 dark:text-blue-300 bg-blue-100 dark:bg-blue-900/40 px-2.5 py-1.5 rounded">Hoje</span>
            </div>
            <h3 class="text-gray-600 dark:text-gray-300 text-sm font-semibold mb-1">Pedidos Hoje</h3>
            <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $pedidosHoje }}</p>
            @if($pedidosPendentes > 0)
                <p class="text-xs text-orange-700 dark:text-orange-300 mt-2">
                    <span class="font-bold">{{ $pedidosPendentes }}</span> pendentes
                </p>
            @endif
        </a>

        <!-- Insumos -->
        <a href="#" class="group bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-100 dark:border-gray-700 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 cursor-pointer block">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-purple-100 dark:bg-purple-900/50 rounded-lg shadow-lg group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-6 h-6 text-purple-700 dark:text-purple-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </div>
                @if($insumosEstoqueBaixo > 0)
                    <span class="text-xs font-bold text-orange-700 dark:text-orange-300 bg-orange-100 dark:bg-orange-900/50 px-2.5 py-1.5 rounded">
                        <svg class="w-3 h-3 inline-block" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                    </span>
                @endif
            </div>
            <h3 class="text-gray-600 dark:text-gray-300 text-sm font-semibold mb-1">Insumos</h3>
            <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $totalInsumos }}</p>
            @if($insumosEstoqueBaixo > 0)
                <p class="text-xs text-orange-700 dark:text-orange-300 mt-2">
                    <span class="font-bold">{{ $insumosEstoqueBaixo }}</span> com estoque baixo
                </p>
            @endif
        </a>

        <!-- Cardápio -->
        <a href="#" class="group bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-100 dark:border-gray-700 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 cursor-pointer block">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-green-100 dark:bg-green-900/50 rounded-lg shadow-lg group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-6 h-6 text-green-700 dark:text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
            </div>
            <h3 class="text-gray-600 dark:text-gray-300 text-sm font-semibold mb-1">Itens do Cardápio</h3>
            <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $totalCardapioItens }}</p>
        </a>

        <!-- Total de Pedidos -->
        <a href="#" class="group bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-100 dark:border-gray-700 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 cursor-pointer block">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-indigo-100 dark:bg-indigo-900/50 rounded-lg shadow-lg group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-6 h-6 text-indigo-700 dark:text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                    </svg>
                </div>
            </div>
            <h3 class="text-gray-600 dark:text-gray-300 text-sm font-semibold mb-1">Total de Pedidos</h3>
            <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $totalPedidos }}</p>
            <p class="text-xs text-gray-600 dark:text-gray-300 mt-2">
                <span class="text-blue-700 dark:text-blue-300 font-bold">{{ $pedidosEsteMes }}</span> este mês
            </p>
        </a>
    </div>

    <!-- Widget de Alertas Críticos - Melhorado -->
    @if($insumosEstoqueBaixo > 0 || $pedidosPendentes > 0)
    <div class="relative mb-8 overflow-hidden rounded-2xl bg-gradient-to-br from-orange-100 via-red-100 to-pink-100 dark:from-orange-900/40 dark:via-red-900/40 dark:to-pink-900/40 p-8 shadow-xl border-2 border-orange-400 dark:border-orange-600 animate-pulse-glow">
        <!-- Animated Background Pattern -->
        <div class="absolute inset-0 opacity-20">
            <div class="absolute top-0 left-0 w-64 h-64 bg-orange-500 rounded-full mix-blend-multiply filter blur-3xl animate-float"></div>
            <div class="absolute bottom-0 right-0 w-64 h-64 bg-red-500 rounded-full mix-blend-multiply filter blur-3xl animate-float" style="animation-delay: 1s;"></div>
        </div>

        <div class="relative flex items-start gap-6">
            <div class="flex-shrink-0">
                <div class="p-4 bg-gradient-to-br from-orange-600 to-red-700 rounded-2xl shadow-2xl transform hover:scale-110 transition-transform duration-300">
                    <svg class="w-8 h-8 text-white animate-pulse" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                </div>
            </div>
            <div class="flex-1">
                <div class="flex items-center gap-3 mb-4">
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white">
                        Alertas Críticos
                    </h3>
                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold bg-red-700 text-white shadow-lg animate-pulse">
                        REQUER ATENÇÃO
                    </span>
                </div>
                <p class="text-sm text-gray-700 dark:text-gray-200 mb-4 font-medium">Itens que precisam de ação imediata</p>
                <div class="grid gap-3">
                    @if($insumosEstoqueBaixo > 0)
                    <a href="#" class="group flex items-center gap-4 p-4 bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-xl border border-orange-300 dark:border-orange-600 hover:bg-white dark:hover:bg-gray-800 hover:shadow-xl hover:-translate-x-2 transition-all duration-300">
                        <div class="flex-shrink-0 p-3 bg-orange-200 dark:bg-orange-900/60 rounded-lg shadow-md">
                            <svg class="w-6 h-6 text-orange-700 dark:text-orange-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="text-2xl font-bold text-orange-800 dark:text-orange-200">{{ $insumosEstoqueBaixo }}</span>
                                <span class="text-sm text-orange-700 dark:text-orange-300 font-semibold">insumo(s)</span>
                            </div>
                            <p class="text-sm text-gray-800 dark:text-gray-200 font-semibold">Estoque baixo - Reposição necessária</p>
                        </div>
                        <svg class="w-5 h-5 text-orange-600 dark:text-orange-400 group-hover:translate-x-2 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                        </svg>
                    </a>
                    @endif
                    @if($pedidosPendentes > 0)
                    <a href="#" class="group flex items-center gap-4 p-4 bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-xl border border-red-300 dark:border-red-600 hover:bg-white dark:hover:bg-gray-800 hover:shadow-xl hover:-translate-x-2 transition-all duration-300">
                        <div class="flex-shrink-0 p-3 bg-red-200 dark:bg-red-900/60 rounded-lg shadow-md">
                            <svg class="w-6 h-6 text-red-700 dark:text-red-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="text-2xl font-bold text-red-800 dark:text-red-200">{{ $pedidosPendentes }}</span>
                                <span class="text-sm text-red-700 dark:text-red-300 font-semibold">pedido(s)</span>
                            </div>
                            <p class="text-sm text-gray-800 dark:text-gray-200 font-semibold">Aguardando processamento</p>
                        </div>
                        <svg class="w-5 h-5 text-red-600 dark:text-red-400 group-hover:translate-x-2 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                        </svg>
                    </a>
                    @endif
                </div>
            </div>
        </div>
    @endif

    <!-- Novos Widgets: Health Score e Top Restaurantes -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Health Score dos Restaurantes -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Health Score</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">Status geral dos restaurantes</p>
                </div>
                <div class="p-3 bg-blue-100 dark:bg-blue-900/50 rounded-lg shadow-md">
                    <svg class="w-6 h-6 text-blue-700 dark:text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-6">
                <div class="bg-green-100 dark:bg-green-900/40 rounded-lg p-4 border-l-4 border-green-600 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-2xl font-bold text-green-800 dark:text-green-200">{{ $restaurantesHealth['saudavel'] }}</p>
                            <p class="text-xs text-green-700 dark:text-green-300 mt-1 font-semibold">✓ Saudáveis</p>
                        </div>
                        <svg class="w-8 h-8 text-green-600 dark:text-green-400 opacity-60" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                </div>

                <div class="bg-yellow-100 dark:bg-yellow-900/40 rounded-lg p-4 border-l-4 border-yellow-600 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-2xl font-bold text-yellow-800 dark:text-yellow-200">{{ $restaurantesHealth['atencao'] }}</p>
                            <p class="text-xs text-yellow-700 dark:text-yellow-300 mt-1 font-semibold">⚠ Atenção</p>
                        </div>
                        <svg class="w-8 h-8 text-yellow-600 dark:text-yellow-400 opacity-60" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                </div>

                <div class="bg-red-100 dark:bg-red-900/40 rounded-lg p-4 border-l-4 border-red-600 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-2xl font-bold text-red-800 dark:text-red-200">{{ $restaurantesHealth['critico'] }}</p>
                            <p class="text-xs text-red-700 dark:text-red-300 mt-1 font-semibold">🚨 Críticos</p>
                        </div>
                        <svg class="w-8 h-8 text-red-600 dark:text-red-400 opacity-60" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                </div>

                <div class="bg-gray-100 dark:bg-gray-700 rounded-lg p-4 border-l-4 border-gray-600 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-2xl font-bold text-gray-800 dark:text-gray-200">{{ $restaurantesHealth['inativo'] }}</p>
                            <p class="text-xs text-gray-700 dark:text-gray-300 mt-1 font-semibold">💤 Inativos</p>
                        </div>
                        <svg class="w-8 h-8 text-gray-600 dark:text-gray-400 opacity-60" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM7 9a1 1 0 000 2h6a1 1 0 100-2H7z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                </div>
            </div>

            @if(count($restaurantesHealth['detalhes']) > 0)
            <div class="border-t border-gray-200 dark:border-gray-600 pt-4">
                <h4 class="text-sm font-semibold text-gray-800 dark:text-gray-200 mb-3">Requerem Atenção</h4>
                <div class="space-y-2 max-h-40 overflow-y-auto">
                    @foreach($restaurantesHealth['detalhes'] as $detalhe)
                    <div class="flex items-start gap-3 p-3 bg-gray-100 dark:bg-gray-800 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors">
                        <div class="flex-shrink-0 mt-0.5">
                            <span class="inline-flex items-center justify-center w-6 h-6 rounded-full {{ $detalhe['status'] === 'critico' ? 'bg-red-200 dark:bg-red-900/50 text-red-800 dark:text-red-200' : 'bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200' }} text-xs font-bold">
                                {{ $detalhe['score'] }}
                            </span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-gray-900 dark:text-white truncate">{{ $detalhe['restaurante'] }}</p>
                            <p class="text-xs text-gray-600 dark:text-gray-300 mt-1">{{ implode(', ', $detalhe['motivos']) }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <!-- Top Restaurantes -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Top Restaurantes</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">Melhores performances do período</p>
                </div>
                <div class="p-3 bg-amber-100 dark:bg-amber-900/50 rounded-lg shadow-md">
                    <svg class="w-6 h-6 text-amber-700 dark:text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                    </svg>
                </div>
            </div>

            @if(count($topRestaurantesPedidos) > 0)
            <div class="space-y-3">
                @foreach($topRestaurantesPedidos as $index => $restaurante)
                <div class="flex items-center gap-4 p-4 bg-gradient-to-r from-gray-50 to-transparent dark:from-gray-900/50 dark:to-transparent rounded-lg hover:from-red-50 dark:hover:from-red-900/20 transition-all duration-300 border border-transparent hover:border-red-200 dark:hover:border-red-900/50">
                    <div class="flex-shrink-0">
                        @if($index === 0)
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-amber-400 to-amber-600 flex items-center justify-center text-white font-bold text-lg shadow-lg">
                            🏆
                        </div>
                        @elseif($index === 1)
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-gray-300 to-gray-500 flex items-center justify-center text-white font-bold text-lg shadow-lg">
                            🥈
                        </div>
                        @elseif($index === 2)
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-orange-400 to-orange-600 flex items-center justify-center text-white font-bold text-lg shadow-lg">
                            🥉
                        </div>
                        @else
                        <div class="w-10 h-10 rounded-full bg-gray-300 dark:bg-gray-600 flex items-center justify-center text-gray-800 dark:text-gray-200 font-bold shadow-md">
                            {{ $index + 1 }}
                        </div>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold text-gray-900 dark:text-white truncate">{{ $restaurante->nome }}</p>
                        <div class="flex items-center gap-4 mt-1 text-xs text-gray-600 dark:text-gray-300">
                            <span class="flex items-center gap-1 font-medium">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                </svg>
                                {{ $restaurante->pedidos_count ?? 0 }} pedidos
                            </span>
                            <span class="flex items-center gap-1 font-medium">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                </svg>
                                {{ $restaurante->users_count }} usuários
                            </span>
                        </div>
                    </div>
                    <div class="flex-shrink-0">
                        <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold bg-green-200 dark:bg-green-900/50 text-green-900 dark:text-green-200">
                            {{ $restaurante->status }}
                        </span>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                <svg class="w-12 h-12 mx-auto mb-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
                <p class="text-sm">Nenhum dado disponível ainda</p>
            </div>
            @endif

            <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-600">
                <div class="grid grid-cols-3 gap-4 text-center">
                    <div>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $mediaUsuariosPorRestaurante }}</p>
                        <p class="text-xs text-gray-600 dark:text-gray-300 mt-1 font-medium">Média usuários</p>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $restaurantesSemUsuarios }}</p>
                        <p class="text-xs text-gray-600 dark:text-gray-300 mt-1 font-medium">Sem usuários</p>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $restaurantesNovosEstaSemana }}</p>
                        <p class="text-xs text-gray-600 dark:text-gray-300 mt-1 font-medium">Novos esta semana</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Relatórios de Vendas: Itens Mais/Menos Vendidos e Tendências -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Itens Mais Vendidos</h3>
            <ul class="space-y-2">
                @foreach($itensMaisVendidos as $item)
                    <li class="flex justify-between items-center">
                        <span class="font-medium text-gray-700 dark:text-gray-200">{{ $item->nome }}</span>
                        <span class="text-green-700 dark:text-green-300 font-bold">{{ $item->total_vendido }} vendidos</span>
                    </li>
                @endforeach
            </ul>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Itens Menos Vendidos</h3>
            <ul class="space-y-2">
                @foreach($itensMenosVendidos as $item)
                    <li class="flex justify-between items-center">
                        <span class="font-medium text-gray-700 dark:text-gray-200">{{ $item->nome }}</span>
                        <span class="text-red-700 dark:text-red-300 font-bold">{{ $item->total_vendido }} vendidos</span>
                    </li>
                @endforeach
            </ul>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Tendências de Vendas</h3>
            <ul class="space-y-2">
                @foreach($tendencias as $item)
                    <li class="flex justify-between items-center">
                        <span class="font-medium text-gray-700 dark:text-gray-200">{{ $item->nome }}</span>
                        @if(!is_null($item->tendencia))
                            <span class="font-bold {{ $item->tendencia > 0 ? 'text-green-700 dark:text-green-300' : 'text-red-700 dark:text-red-300' }}">
                                {{ $item->tendencia > 0 ? '+' : '' }}{{ $item->tendencia }}%
                            </span>
                        @else
                            <span class="text-gray-500">Sem dados</span>
                        @endif
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

    <!-- Gráficos -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <!-- Gráfico de Atividade -->
        <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Atividade do Sistema</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">Usuários ativos nos últimos dias</p>
                </div>
                <div x-data="{ period: '7d' }" class="flex gap-2">
                    <button @click="period = '7d'; updateChart('7d')" :class="period === '7d' ? 'btn-toggle-active' : 'btn-toggle-inactive'" class="px-3 py-1.5 text-xs font-medium rounded-lg transition-colors">7 dias</button>
                    <button @click="period = '30d'; updateChart('30d')" :class="period === '30d' ? 'btn-toggle-active' : 'btn-toggle-inactive'" class="px-3 py-1.5 text-xs font-medium rounded-lg transition-colors">30 dias</button>
                    <button @click="period = '90d'; updateChart('90d')" :class="period === '90d' ? 'btn-toggle-active' : 'btn-toggle-inactive'" class="px-3 py-1.5 text-xs font-medium rounded-lg transition-colors">90 dias</button>
                </div>
            </div>
            <div class="h-64">
                <canvas id="activityChart"></canvas>
            </div>
        </div>

        <!-- Gráfico de Pizza - Distribuição -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Distribuição</h3>
                <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">Usuários por tipo</p>
            </div>
            <div class="h-64 flex items-center justify-center">
                <canvas id="distributionChart"></canvas>
            </div>
            <div class="mt-4 space-y-2">
                <div class="flex items-center justify-between text-sm">
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 rounded-full bg-red-600"></div>
                        <span class="text-gray-700 dark:text-gray-300 font-medium">Administradores</span>
                    </div>
                    <span class="font-bold text-gray-900 dark:text-white">{{ $totalAdmins }}</span>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 rounded-full bg-blue-600"></div>
                        <span class="text-gray-700 dark:text-gray-300 font-medium">Usuários</span>
                    </div>
                    <span class="font-bold text-gray-900 dark:text-white">{{ $totalRegularUsers }}</span>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 rounded-full bg-green-600"></div>
                        <span class="text-gray-700 dark:text-gray-300 font-medium">Ativos</span>
                    </div>
                    <span class="font-bold text-gray-900 dark:text-white">{{ $totalUsersActive }}</span>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 rounded-full bg-gray-600"></div>
                        <span class="text-gray-700 dark:text-gray-300 font-medium">Inativos</span>
                    </div>
                    <span class="font-bold text-gray-900 dark:text-white">{{ $totalUsersInactive }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Timeline de Atividades e Dados Recentes -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Timeline de Atividades Recentes -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Atividades Recentes</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">Timeline de eventos do sistema</p>
                </div>
                <div class="p-2 bg-purple-100 dark:bg-purple-900/50 rounded-lg shadow-sm">
                    <svg class="w-5 h-5 text-purple-700 dark:text-purple-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>

            <div class="space-y-4 max-h-96 overflow-y-auto">
                @forelse($atividadesRecentes as $atividade)
                <div class="flex items-start gap-3 group">
                    <div class="flex-shrink-0 mt-1">
                        @if($atividade['tipo'] === 'restaurante_novo')
                        <div class="w-8 h-8 rounded-full bg-red-200 dark:bg-red-900/50 flex items-center justify-center shadow-sm">
                            <svg class="w-4 h-4 text-red-700 dark:text-red-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                        @elseif($atividade['tipo'] === 'usuario_novo')
                        <div class="w-8 h-8 rounded-full bg-blue-200 dark:bg-blue-900/50 flex items-center justify-center shadow-sm">
                            <svg class="w-4 h-4 text-blue-700 dark:text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        @else
                        <div class="w-8 h-8 rounded-full bg-gray-200 dark:bg-gray-600 flex items-center justify-center shadow-sm">
                            <svg class="w-4 h-4 text-gray-700 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0 pb-4 border-b border-gray-200 dark:border-gray-600 group-last:border-0">
                        <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $atividade['titulo'] }}</p>
                        <p class="text-sm text-gray-700 dark:text-gray-300 mt-1 truncate">{{ $atividade['descricao'] }}</p>
                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-2">{{ $atividade['tempo'] }}</p>
                    </div>
                </div>
                @empty
                <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                    <svg class="w-12 h-12 mx-auto mb-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-sm">Nenhuma atividade recente</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Métricas Adicionais -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Insights Rápidos</h3>
                <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">Métricas em destaque</p>
            </div>

            <div class="space-y-4">
                <div class="p-4 bg-gradient-to-r from-blue-100 to-blue-200 dark:from-blue-900/40 dark:to-blue-800/40 rounded-lg border border-blue-300 dark:border-blue-700 shadow-sm">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs font-bold text-blue-800 dark:text-blue-200 uppercase tracking-wider">Novos este Mês</span>
                        <svg class="w-5 h-5 text-blue-700 dark:text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                        </svg>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-2xl font-bold text-blue-900 dark:text-blue-100">{{ $restaurantesNovosEsteMes }}</p>
                            <p class="text-xs text-blue-800 dark:text-blue-200 font-semibold">Restaurantes</p>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-blue-900 dark:text-blue-100">{{ $usersEsteMes ?? 0 }}</p>
                            <p class="text-xs text-blue-800 dark:text-blue-200 font-semibold">Usuários</p>
                        </div>
                    </div>
                </div>

                <div class="p-4 bg-gradient-to-r from-amber-100 to-amber-200 dark:from-amber-900/40 dark:to-amber-800/40 rounded-lg border border-amber-300 dark:border-amber-700 shadow-sm">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs font-bold text-amber-800 dark:text-amber-200 uppercase tracking-wider">Média por Restaurante</span>
                        <svg class="w-5 h-5 text-amber-700 dark:text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <p class="text-3xl font-bold text-amber-900 dark:text-amber-100">{{ $mediaUsuariosPorRestaurante }}</p>
                    <p class="text-xs text-amber-800 dark:text-amber-200 font-semibold">usuários ativos</p>
                </div>

                @if($restaurantesSemUsuarios > 0)
                <div class="p-4 bg-gradient-to-r from-orange-100 to-red-100 dark:from-orange-900/40 dark:to-red-900/40 rounded-lg border border-orange-300 dark:border-orange-700 shadow-sm">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs font-bold text-orange-800 dark:text-orange-200 uppercase tracking-wider">Atenção Necessária</span>
                        <svg class="w-5 h-5 text-orange-700 dark:text-orange-300" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <p class="text-2xl font-bold text-orange-900 dark:text-orange-100">{{ $restaurantesSemUsuarios }}</p>
                    <p class="text-xs text-orange-800 dark:text-orange-200 font-semibold">restaurantes sem usuários</p>
                </div>
                @endif

                <div class="p-4 bg-gradient-to-r from-green-100 to-emerald-200 dark:from-green-900/40 dark:to-emerald-800/40 rounded-lg border border-green-300 dark:border-green-700 shadow-sm">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs font-bold text-green-800 dark:text-green-200 uppercase tracking-wider">Taxa de Ativação</span>
                        <svg class="w-5 h-5 text-green-700 dark:text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <p class="text-3xl font-bold text-green-900 dark:text-green-100">{{ $totalRestaurantes > 0 ? round(($restaurantesAtivos / $totalRestaurantes) * 100) : 0 }}%</p>
                    <p class="text-xs text-green-800 dark:text-green-200 font-semibold">restaurantes ativos</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Usuários Recentes -->
        <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden border border-gray-100 dark:border-gray-700">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-600 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Usuários Recentes</h3>
                <a href="{{ route('admin.users.index') }}" class="text-sm text-red-700 dark:text-red-300 hover:text-red-800 dark:hover:text-red-200 font-bold">Ver todos →</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 dark:bg-gray-900">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Nome</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Papel</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($usuariosRecentes as $usuario)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors {{ $usuario->trashed() ? 'bg-red-50/50 dark:bg-red-900/10' : '' }}">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div class="h-10 w-10 rounded-full bg-red-100 dark:bg-red-900/30 flex items-center justify-center">
                                                <span class="text-red-600 dark:text-red-400 font-semibold text-sm">{{ strtoupper(substr($usuario->name, 0, 2)) }}</span>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $usuario->name }}</div>
                                            @if($usuario->trashed())
                                                <span class="text-xs text-red-600 dark:text-red-400 font-medium">Desativado</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">{{ $usuario->email }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $usuario->isAdmin() ? 'bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-400' : 'bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-400' }}">
                                        {{ $usuario->isAdmin() ? 'Admin' : 'Usuário' }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-8 text-center text-gray-500 text-sm">Nenhum usuário encontrado</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Usuários por Restaurante -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden border border-gray-100 dark:border-gray-700">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Por Restaurante</h3>
            </div>
            <div class="p-2">
                @forelse($usuariosPorRestaurante as $item)
                    <div class="px-4 py-3 flex items-center justify-between hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg transition-colors">
                        <div class="flex items-center space-x-3">
                            <div class="flex-shrink-0 h-8 w-8 bg-red-50 dark:bg-red-900/20 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ Str::limit($item->restaurante?->nome ?? 'Sem restaurante', 20) }}</span>
                        </div>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300">
                            {{ $item->total }}
                        </span>
                    </div>
                @empty
                    <div class="px-6 py-8 text-center text-gray-500 text-sm">
                        Nenhum dado disponível
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Sugestão de Pratos do Dia -->
    @if($pratosDoDia && count($pratosDoDia) > 0)
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-red-700 mb-4 flex items-center gap-2">
            <svg class="w-6 h-6 text-red-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            Pratos do Dia (Sugestão)
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($pratosDoDia as $item)
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 border border-gray-100 dark:border-gray-700 flex flex-col items-center">
                @if($item->imagem)
                    <img src="{{ asset('storage/' . $item->imagem) }}" alt="{{ $item->nome }}" class="mb-3 rounded-lg shadow-md" style="max-width:120px; max-height:120px;">
                @endif
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-1">{{ $item->nome }}</h3>
                <p class="text-sm text-gray-600 dark:text-gray-300 mb-2">R$ {{ number_format($item->preco_venda, 2, ',', '.') }}</p>
                @if($item->promocao)
                    <span class="inline-block bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-semibold mb-2">{{ $item->promocao['descricao'] ?? 'Promoção' }}</span>
                @endif
                <p class="text-xs text-gray-500 dark:text-gray-400 mb-2">Estoque Essenciais: <span class="font-bold">{{ $item->soma_estoque_essenciais }}</span></p>
                <ul class="text-xs text-gray-700 dark:text-gray-300 mb-2">
                    @foreach($item->ingredientes as $ing)
                        <li>{{ $ing }}</li>
                    @endforeach
                </ul>
                <a href="{{ route('admin.cardapio.edit', $item->id) }}" class="mt-2 btn btn-sm btn-primary">Editar</a>
            </div>
            @endforeach
        </div>
    </div>
    @endif
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    let activityChart;
    let distributionChart;
    let isLoading = false;

    // Inicializar gráfico de pizza
    function initDistributionChart() {
        const isDark = document.documentElement.classList.contains('dark');
        const textColor = isDark ? '#e5e7eb' : '#374151';

        const ctx = document.getElementById('distributionChart').getContext('2d');
        distributionChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Admins', 'Usuários'],
                datasets: [{
                    data: [{{ $totalAdmins }}, {{ $totalRegularUsers }}],
                    backgroundColor: [
                        'rgb(220, 38, 38)',
                        'rgb(59, 130, 246)'
                    ],
                    borderWidth: 0,
                    hoverOffset: 10
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: isDark ? '#1f2937' : '#ffffff',
                        titleColor: isDark ? '#e5e7eb' : '#111827',
                        bodyColor: isDark ? '#e5e7eb' : '#374151',
                        borderColor: isDark ? '#374151' : '#e5e7eb',
                        borderWidth: 1,
                        padding: 12,
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.parsed;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = ((value / total) * 100).toFixed(1);
                                return `${label}: ${value} (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });
    }

    // Inicializar gráfico de atividade
    async function initChart() {
        const isDark = document.documentElement.classList.contains('dark');
        const textColor = isDark ? '#e5e7eb' : '#374151';
        const gridColor = isDark ? '#374151' : '#e5e7eb';

        const ctx = document.getElementById('activityChart').getContext('2d');
        activityChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: [],
                datasets: []
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: 'index',
                    intersect: false
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            color: textColor,
                            usePointStyle: true,
                            padding: 15
                        }
                    },
                    tooltip: {
                        backgroundColor: isDark ? '#1f2937' : '#ffffff',
                        titleColor: isDark ? '#e5e7eb' : '#111827',
                        bodyColor: isDark ? '#e5e7eb' : '#374151',
                        borderColor: isDark ? '#374151' : '#e5e7eb',
                        borderWidth: 1,
                        padding: 12,
                        displayColors: true
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: gridColor,
                            drawBorder: false
                        },
                        ticks: {
                            color: textColor,
                            precision: 0
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            color: textColor
                        }
                    }
                }
            });
        }

        // Carregar dados iniciais
        await loadChartData('7d');
    }

    // Carregar dados do backend
    async function loadChartData(period) {
        if (isLoading) return;

        isLoading = true;
        const canvas = document.getElementById('activityChart');
        canvas.style.opacity = '0.5';

        try {
            const response = await fetch(`{{ route('admin.dashboard.activity-data') }}?period=${period}`);
            const data = await response.json();

            activityChart.data.labels = data.labels;
            activityChart.data.datasets = data.datasets;
            activityChart.update();
        } catch (error) {
            console.error('Erro ao carregar dados do gráfico:', error);
        } finally {
            canvas.style.opacity = '1';
            isLoading = false;
        }
    }

    // Atualizar gráfico quando mudar o período
    window.updateChart = function(period) {
        loadChartData(period);
    };

    // Inicializar quando a página carregar
    document.addEventListener('DOMContentLoaded', () => {
        initChart();
        initDistributionChart();

        // Auto-refresh a cada 5 minutos
        setInterval(() => {
            const currentPeriod = document.querySelector('[class*="btn-toggle-active"]')?.dataset?.period || '7d';
            loadChartData(currentPeriod);
            updateLastUpdateTime();
        }, 300000); // 5 minutos

        // Recarregar quando o modo escuro mudar
        const observer = new MutationObserver(() => {
            if (activityChart) {
                activityChart.destroy();
                initChart();
            }
            if (distributionChart) {
                distributionChart.destroy();
                initDistributionChart();
            }
        });
        observer.observe(document.documentElement, {
            attributes: true,
            attributeFilter: ['class']
        });
    });

    // Atualizar timestamp de última atualização
    function updateLastUpdateTime() {
        const lastUpdateEl = document.getElementById('lastUpdate');
        if (lastUpdateEl) {
            const now = new Date();
            const timeStr = now.toLocaleTimeString('pt-BR', { hour: '2-digit', minute: '2-digit' });
            lastUpdateEl.textContent = `Atualizado às ${timeStr}`;
        }
    }
</script>
@endsection
