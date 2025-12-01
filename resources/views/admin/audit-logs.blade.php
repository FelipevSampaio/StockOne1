@extends('layouts.admin')

@section('title', 'Logs de Auditoria')
@section('page-title', 'Logs de Auditoria')

@section('topbar-actions')
    <a href="{{ route('admin.audit-logs.export') }}{{ request()->getQueryString() ? '?' . request()->getQueryString() : '' }}" class="btn-secondary">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
        </svg>
        Exportar CSV
    </a>
@endsection

@section('content')
    <!-- Filtros Minimalistas -->
    <form method="GET" action="{{ route('admin.audit-logs.index') }}" class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-100 dark:border-gray-700 p-4 mb-6">
        <div class="flex flex-wrap items-center gap-3">
            <div class="relative flex-1 min-w-[200px]">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" name="search" placeholder="Buscar..." value="{{ request('search') }}" class="pl-10 w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-1 focus:ring-red-500 focus:border-red-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400">
            </div>

            <select name="action" class="px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-1 focus:ring-red-500 focus:border-red-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                <option value="">Ação</option>
                <option value="create" @selected(request('action') === 'create')>Criação</option>
                <option value="update" @selected(request('action') === 'update')>Atualização</option>
                <option value="delete" @selected(request('action') === 'delete')>Exclusão</option>
                <option value="restore" @selected(request('action') === 'restore')>Restauração</option>
            </select>

            <select name="model" class="px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-1 focus:ring-red-500 focus:border-red-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                <option value="">Modelo</option>
                <option value="User" @selected(request('model') === 'User')>Usuário</option>
                <option value="Restaurante" @selected(request('model') === 'Restaurante')>Restaurante</option>
                <option value="Setting" @selected(request('model') === 'Setting')>Configuração</option>
            </select>

            <select name="user_id" class="px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-1 focus:ring-red-500 focus:border-red-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                <option value="">Usuário</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}" @selected(request('user_id') == $user->id)>{{ $user->name }}</option>
                @endforeach
            </select>

            <input type="date" name="date_from" value="{{ request('date_from') }}" class="px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-1 focus:ring-red-500 focus:border-red-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100" placeholder="De">
            <input type="date" name="date_to" value="{{ request('date_to') }}" class="px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-1 focus:ring-red-500 focus:border-red-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100" placeholder="Até">

            <button type="submit" class="btn-sm">
                Filtrar
            </button>

            @if(request()->anyFilled(['search', 'action', 'model', 'user_id', 'date_from', 'date_to']))
                <a href="{{ route('admin.audit-logs.index') }}" class="px-4 py-2 text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 transition-colors">
                    Limpar
                </a>
            @endif
        </div>
    </form>

    <!-- Timeline de Logs -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Histórico de Ações</h3>
            <p class="text-sm text-gray-500 mt-1">Registro completo de todas as ações realizadas no sistema</p>
        </div>

        <div class="p-6">
            @forelse($logs as $log)
                <div class="relative pb-8 {{ !$loop->last ? 'border-l-2 border-gray-200 dark:border-gray-700' : '' }} pl-8" x-data="{ expanded: false }">
                    <!-- Timeline dot -->
                    <div class="absolute left-0 top-0 -ml-2 flex items-center justify-center">
                        <div class="h-4 w-4 rounded-full border-2 border-white
                            {{ $log->action === 'create' ? 'bg-green-500' : '' }}
                            {{ $log->action === 'update' ? 'bg-blue-500' : '' }}
                            {{ $log->action === 'delete' ? 'bg-red-500' : '' }}
                            {{ $log->action === 'restore' ? 'bg-yellow-500' : '' }}
                            shadow"></div>
                    </div>

                    <!-- Log content -->
                    <div class="bg-gray-50 rounded-lg p-4 hover:bg-gray-100 transition-colors">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center space-x-2 mb-2">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        {{ $log->action === 'create' ? 'bg-green-100 text-green-800' : '' }}
                                        {{ $log->action === 'update' ? 'bg-blue-100 text-blue-800' : '' }}
                                        {{ $log->action === 'delete' ? 'bg-red-100 text-red-800' : '' }}
                                        {{ $log->action === 'restore' ? 'bg-yellow-100 text-yellow-800' : '' }}">
                                        {{ ucfirst($log->action) }}
                                    </span>
                                    <span class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ class_basename($log->model) }}</span>
                                    @if($log->model_id)
                                        <span class="text-sm text-gray-500">#{{ $log->model_id }}</span>
                                    @endif
                                </div>

                                <p class="text-sm text-gray-700 dark:text-gray-300 mb-2">
                                    Por <span class="font-medium">{{ $log->user->name ?? 'Sistema' }}</span>
                                    <span class="text-gray-400">•</span>
                                    <time class="text-gray-500" datetime="{{ $log->created_at }}">
                                        {{ $log->created_at->diffForHumans() }}
                                    </time>
                                </p>

                                @if($log->changes)
                                    <button @click="expanded = !expanded" class="text-sm text-red-600 hover:text-red-700 font-medium flex items-center">
                                        <svg class="w-4 h-4 mr-1 transition-transform" :class="{ 'rotate-90': expanded }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                        <span x-text="expanded ? 'Ocultar detalhes' : 'Ver detalhes'"></span>
                                    </button>

                                    <div x-show="expanded"
                                         x-cloak
                                         x-transition:enter="transition ease-out duration-200"
                                         x-transition:enter-start="opacity-0 transform -translate-y-2"
                                         x-transition:enter-end="opacity-100 transform translate-y-0"
                                         class="mt-3 bg-white dark:bg-gray-900 rounded-lg p-3 border border-gray-200 dark:border-gray-700">
                                        <h4 class="text-xs font-semibold text-gray-700 uppercase mb-2">Alterações:</h4>
                                        <pre class="text-xs text-gray-600 overflow-x-auto">{{ json_encode(json_decode($log->changes), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                    </div>
                                @endif
                            </div>

                            <div class="ml-4">
                                <div class="h-10 w-10 rounded-full bg-red-100 flex items-center justify-center">
                                    <span class="text-red-600 font-semibold text-sm">{{ strtoupper(substr($log->user->name ?? 'S', 0, 2)) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <p class="mt-2 text-sm text-gray-500">Nenhum log encontrado</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Paginação -->
    <div class="mt-6">
        {{ $logs->links() }}
    </div>
@endsection
