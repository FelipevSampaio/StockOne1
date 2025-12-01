@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard Administrativo')

@section('content')
    <!-- Cards de Estatísticas -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total de Usuários -->
        <div class="group bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-100 dark:border-gray-700 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 cursor-pointer">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-red-50 dark:bg-red-900/20 rounded-lg group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
                <span class="text-xs font-medium text-green-600 dark:text-green-400 bg-green-50 dark:bg-green-900/20 px-2 py-1 rounded">+12%</span>
            </div>
            <h3 class="text-gray-500 dark:text-gray-400 text-sm font-medium mb-1">Total de Usuários</h3>
            <p class="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-2">{{ $totalUsers }}</p>
            <p class="text-xs text-gray-500 dark:text-gray-400">
                <span class="text-green-600 dark:text-green-400 font-medium">{{ $totalUsersActive }}</span> ativos •
                <span class="text-red-600 dark:text-red-400 font-medium">{{ $totalUsersInactive }}</span> inativos
            </p>
        </div>        <!-- Administradores -->
        <div class="group bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-100 dark:border-gray-700 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 cursor-pointer">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-red-50 dark:bg-red-900/20 rounded-lg">
                    <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
            </div>
            <h3 class="text-gray-500 dark:text-gray-400 text-sm font-medium mb-1">Administradores</h3>
            <p class="text-3xl font-bold text-gray-900 dark:text-gray-100">{{ $totalAdmins }}</p>
        </div>

        <!-- Restaurantes -->
        <div class="group bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-100 dark:border-gray-700 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 cursor-pointer">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-red-50 dark:bg-red-900/20 rounded-lg">
                    <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
            </div>
            <h3 class="text-gray-500 dark:text-gray-400 text-sm font-medium mb-1">Restaurantes</h3>
            <p class="text-3xl font-bold text-gray-900 dark:text-gray-100">{{ $totalRestaurantes }}</p>
        </div>

        <!-- Pedidos Hoje -->
        <div class="group bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-100 dark:border-gray-700 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 cursor-pointer">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-red-50 dark:bg-red-900/20 rounded-lg">
                    <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                </div>
                <span class="text-xs font-medium text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/20 px-2 py-1 rounded">Hoje</span>
            </div>
            <h3 class="text-gray-500 dark:text-gray-400 text-sm font-medium mb-1">Pedidos Hoje</h3>
            <p class="text-3xl font-bold text-gray-900 dark:text-gray-100">{{ $pedidosHoje }}</p>
            @if($pedidosPendentes > 0)
                <p class="text-xs text-orange-600 dark:text-orange-400 mt-2">
                    <span class="font-medium">{{ $pedidosPendentes }}</span> pendentes
                </p>
            @endif
        </div>

        <!-- Insumos -->
        <div class="group bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-100 dark:border-gray-700 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 cursor-pointer">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-purple-50 dark:bg-purple-900/20 rounded-lg group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </div>
                @if($insumosEstoqueBaixo > 0)
                    <span class="text-xs font-medium text-orange-600 dark:text-orange-400 bg-orange-50 dark:bg-orange-900/20 px-2 py-1 rounded">
                        <svg class="w-3 h-3 inline-block" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                    </span>
                @endif
            </div>
            <h3 class="text-gray-500 dark:text-gray-400 text-sm font-medium mb-1">Insumos</h3>
            <p class="text-3xl font-bold text-gray-900 dark:text-gray-100">{{ $totalInsumos }}</p>
            @if($insumosEstoqueBaixo > 0)
                <p class="text-xs text-orange-600 dark:text-orange-400 mt-2">
                    <span class="font-medium">{{ $insumosEstoqueBaixo }}</span> com estoque baixo
                </p>
            @endif
        </div>

        <!-- Cardápio -->
        <div class="group bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-100 dark:border-gray-700 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 cursor-pointer">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-green-50 dark:bg-green-900/20 rounded-lg group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
            </div>
            <h3 class="text-gray-500 dark:text-gray-400 text-sm font-medium mb-1">Itens do Cardápio</h3>
            <p class="text-3xl font-bold text-gray-900 dark:text-gray-100">{{ $totalCardapioItens }}</p>
        </div>

        <!-- Logs de Auditoria -->
        <div class="group bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-100 dark:border-gray-700 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 cursor-pointer">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
            </div>
            <h3 class="text-gray-500 dark:text-gray-400 text-sm font-medium mb-1">Logs de Auditoria</h3>
            <p class="text-3xl font-bold text-gray-900 dark:text-gray-100">{{ $totalAuditLogs }}</p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">Registros de atividades</p>
        </div>

        <!-- Total de Pedidos -->
        <div class="group bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-100 dark:border-gray-700 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 cursor-pointer">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-indigo-50 dark:bg-indigo-900/20 rounded-lg group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                    </svg>
                </div>
            </div>
            <h3 class="text-gray-500 dark:text-gray-400 text-sm font-medium mb-1">Total de Pedidos</h3>
            <p class="text-3xl font-bold text-gray-900 dark:text-gray-100">{{ $totalPedidos }}</p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                <span class="text-blue-600 dark:text-blue-400 font-medium">{{ $pedidosEsteMes }}</span> este mês
            </p>
        </div>
    </div>

    <!-- Gráfico de Atividade -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 mb-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Atividade do Sistema</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Usuários ativos nos últimos dias</p>
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

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Usuários Recentes -->
        <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden border border-gray-100 dark:border-gray-700">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Usuários Recentes</h3>
                <a href="{{ route('admin.users.index') }}" class="text-sm text-red-600 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300 font-medium">Ver todos →</a>
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
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    let activityChart;
    let isLoading = false;

    // Inicializar gráfico
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
            }
        });

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

        // Recarregar quando o modo escuro mudar
        const observer = new MutationObserver(() => {
            if (activityChart) {
                activityChart.destroy();
                initChart();
            }
        });
        observer.observe(document.documentElement, {
            attributes: true,
            attributeFilter: ['class']
        });
    });
</script>
@endsection
