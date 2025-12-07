@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <h1 class="text-2xl font-bold mb-4">Visão Geral do Restaurante</h1>
    <!-- Resumo de Mesas -->
    <div class="grid grid-cols-2 gap-4 mb-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg p-4 shadow">
            <h2 class="text-lg font-semibold">Mesas Ocupadas</h2>
            <p class="text-3xl font-bold text-red-600">{{ $mesasOcupadas }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg p-4 shadow">
            <h2 class="text-lg font-semibold">Mesas Livres</h2>
            <p class="text-3xl font-bold text-green-600">{{ $mesasLivres }}</p>
        </div>
    </div>
    <!-- Gráfico de Ocupação -->
    <div class="bg-white dark:bg-gray-800 rounded-lg p-4 shadow mb-6">
        <h2 class="text-lg font-semibold mb-2">Ocupação por Horário</h2>
        <canvas id="ocupacaoChart"></canvas>
    </div>
    <!-- Alertas de Reservas e Fila de Espera -->
    <div class="bg-white dark:bg-gray-800 rounded-lg p-4 shadow">
        <h2 class="text-lg font-semibold mb-2">Alertas de Reservas e Fila de Espera</h2>
        <ul class="list-disc pl-5">
            @forelse($alertas as $alerta)
                <li class="text-sm text-yellow-700 dark:text-yellow-400">{{ $alerta }}</li>
            @empty
                <li class="text-sm text-gray-500">Nenhum alerta no momento.</li>
            @endforelse
        </ul>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('ocupacaoChart').getContext('2d');
    const ocupacaoChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json($horarios),
            datasets: [{
                label: 'Mesas Ocupadas',
                data: @json($ocupacaoPorHorario),
                borderColor: 'rgb(239,68,68)',
                backgroundColor: 'rgba(239,68,68,0.2)',
                fill: true,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false }
            }
        }
    });
</script>
@endpush
