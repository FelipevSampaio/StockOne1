// Mini sparkline charts for cards
// Requires Chart.js or similar library
// Example for Chart.js

document.addEventListener('DOMContentLoaded', function() {
    if (window.Chart) {
        // Receita
        if (document.getElementById('sparkline-receita')) {
            new Chart(document.getElementById('sparkline-receita').getContext('2d'), {
                type: 'line',
                data: {
                    labels: window.sparklineLabels || [],
                    datasets: [{
                        data: window.sparklineReceita || [],
                        borderColor: '#f59e0b',
                        backgroundColor: 'rgba(245, 158, 11, 0.1)',
                        fill: true,
                        tension: 0.4,
                        pointRadius: 0,
                        borderWidth: 2,
                    }]
                },
                options: {
                    plugins: { legend: { display: false } },
                    scales: { x: { display: false }, y: { display: false } },
                    elements: { line: { borderJoinStyle: 'round' } },
                    responsive: true,
                }
            });
        }
        // Ticket
        if (document.getElementById('sparkline-ticket')) {
            new Chart(document.getElementById('sparkline-ticket').getContext('2d'), {
                type: 'line',
                data: {
                    labels: window.sparklineLabels || [],
                    datasets: [{
                        data: window.sparklineTicket || [],
                        borderColor: '#16a34a',
                        backgroundColor: 'rgba(22, 163, 74, 0.1)',
                        fill: true,
                        tension: 0.4,
                        pointRadius: 0,
                        borderWidth: 2,
                    }]
                },
                options: {
                    plugins: { legend: { display: false } },
                    scales: { x: { display: false }, y: { display: false } },
                    elements: { line: { borderJoinStyle: 'round' } },
                    responsive: true,
                }
            });
        }
        // Pedidos
        if (document.getElementById('sparkline-pedidos')) {
            new Chart(document.getElementById('sparkline-pedidos').getContext('2d'), {
                type: 'line',
                data: {
                    labels: window.sparklineLabels || [],
                    datasets: [{
                        data: window.sparklinePedidos || [],
                        borderColor: '#2563eb',
                        backgroundColor: 'rgba(37, 99, 235, 0.1)',
                        fill: true,
                        tension: 0.4,
                        pointRadius: 0,
                        borderWidth: 2,
                    }]
                },
                options: {
                    plugins: { legend: { display: false } },
                    scales: { x: { display: false }, y: { display: false } },
                    elements: { line: { borderJoinStyle: 'round' } },
                    responsive: true,
                }
            });
        }
        // Total
        if (document.getElementById('sparkline-total')) {
            new Chart(document.getElementById('sparkline-total').getContext('2d'), {
                type: 'line',
                data: {
                    labels: window.sparklineLabels || [],
                    datasets: [{
                        data: window.sparklineTotal || [],
                        borderColor: '#dc2626',
                        backgroundColor: 'rgba(220, 38, 38, 0.1)',
                        fill: true,
                        tension: 0.4,
                        pointRadius: 0,
                        borderWidth: 2,
                    }]
                },
                options: {
                    plugins: { legend: { display: false } },
                    scales: { x: { display: false }, y: { display: false } },
                    elements: { line: { borderJoinStyle: 'round' } },
                    responsive: true,
                }
            });
        }
    }
});
