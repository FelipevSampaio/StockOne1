<?php

namespace App\Http\Controllers;

use App\Models\Alerta;
use App\Models\CardapioItem;
use App\Models\Insumo;
use App\Models\Pedido;
use App\Models\Estoque;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $restauranteId = session('restaurante_id');

        // Cache de 5 minutos para as estatísticas
        $stats = Cache::remember("dashboard_stats_{$restauranteId}", 300, function () use ($restauranteId) {
            // Estatísticas de hoje
            $pedidosHoje = Pedido::where('restaurante_id', $restauranteId)
                ->whereDate('data_hora_pedido', today())
                ->count();

            $receitaHoje = Pedido::where('restaurante_id', $restauranteId)
                ->whereDate('data_hora_pedido', today())
                ->where('status', 'concluido')
                ->sum('valor_total') ?? 0;

            // Estatísticas de ontem (para comparação)
            $pedidosOntem = Pedido::where('restaurante_id', $restauranteId)
                ->whereDate('data_hora_pedido', today()->subDay())
                ->count();

            $receitaOntem = Pedido::where('restaurante_id', $restauranteId)
                ->whereDate('data_hora_pedido', today()->subDay())
                ->where('status', 'concluido')
                ->sum('valor_total') ?? 0;

            // Estatísticas do mês
            $receitaMes = Pedido::where('restaurante_id', $restauranteId)
                ->whereMonth('data_hora_pedido', now()->month)
                ->whereYear('data_hora_pedido', now()->year)
                ->where('status', 'concluido')
                ->sum('valor_total') ?? 0;

            // Estatísticas do mês passado (para comparação)
            $receitaMesPassado = Pedido::where('restaurante_id', $restauranteId)
                ->whereMonth('data_hora_pedido', now()->subMonth()->month)
                ->whereYear('data_hora_pedido', now()->subMonth()->year)
                ->where('status', 'concluido')
                ->sum('valor_total') ?? 0;

            // Calcular percentuais
            $percentualPedidos = $pedidosOntem > 0
                ? (($pedidosHoje - $pedidosOntem) / $pedidosOntem) * 100
                : ($pedidosHoje > 0 ? 100 : 0);

            $percentualReceita = $receitaOntem > 0
                ? (($receitaHoje - $receitaOntem) / $receitaOntem) * 100
                : ($receitaHoje > 0 ? 100 : 0);

            $percentualMes = $receitaMesPassado > 0
                ? (($receitaMes - $receitaMesPassado) / $receitaMesPassado) * 100
                : ($receitaMes > 0 ? 100 : 0);

            // Ticket médio
            $ticketMedio = $pedidosHoje > 0 ? $receitaHoje / $pedidosHoje : 0;

            // Horário de pico (hoje)
            $horarioPico = Pedido::where('restaurante_id', $restauranteId)
                ->whereDate('data_hora_pedido', today())
                ->selectRaw('HOUR(data_hora_pedido) as hora, COUNT(*) as total')
                ->groupBy('hora')
                ->orderByDesc('total')
                ->first();

            // Meta do mês (exemplo: 20% acima do mês passado)
            $metaMes = $receitaMesPassado * 1.2;
            $progressoMeta = $metaMes > 0 ? ($receitaMes / $metaMes) * 100 : 0;

            $itensEstoqueCritico = 0;
            try {
                $itensEstoqueCritico = Estoque::whereHas('insumo', function($q) use ($restauranteId) {
                        $q->where('restaurante_id', $restauranteId)
                          ->whereNotNull('ponto_reposicao_minimo');
                    })
                    ->whereRaw('quantidade_atual <= (SELECT ponto_reposicao_minimo FROM insumos WHERE insumos.id = estoque.insumo_id)')
                    ->count();
            } catch (\Exception $e) {
                \Log::warning('Erro ao calcular itens de estoque crítico: ' . $e->getMessage());
            }

            return [
                'cardapio_count' => CardapioItem::where('restaurante_id', $restauranteId)->count(),
                'insumos_count' => Insumo::where('restaurante_id', $restauranteId)->count(),
                'pedidos_count' => Pedido::where('restaurante_id', $restauranteId)->count(),
                'alertas_count' => Alerta::where('resolvido', false)
                    ->whereHas('insumo', fn($q) => $q->where('restaurante_id', $restauranteId))
                    ->count(),
                'pedidos_hoje' => $pedidosHoje ?? 0,
                'pedidos_ontem' => $pedidosOntem ?? 0,
                'percentual_pedidos' => round($percentualPedidos ?? 0, 1),
                'receita_hoje' => $receitaHoje ?? 0,
                'receita_ontem' => $receitaOntem ?? 0,
                'percentual_receita_hoje' => round($percentualReceita ?? 0, 1),
                'receita_mes' => $receitaMes ?? 0,
                'receita_mes_passado' => $receitaMesPassado ?? 0,
                'percentual_receita_mes' => round($percentualMes ?? 0, 1),
                'ticket_medio' => $ticketMedio ?? 0,
                'horario_pico' => $horarioPico ? $horarioPico->hora . ':00' : 'N/A',
                'horario_pico_pedidos' => $horarioPico ? $horarioPico->total : 0,
                'meta_mes' => $metaMes ?? 0,
                'progresso_meta' => round(min($progressoMeta ?? 0, 100), 1),
                'itens_estoque_critico' => $itensEstoqueCritico,
            ];
        });

        // Alertas críticos (não cacheados - precisam ser em tempo real)
        $alertasCriticos = Alerta::with('insumo')
            ->where('resolvido', false)
            ->whereHas('insumo', fn($q) => $q->where('restaurante_id', $restauranteId))
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Últimos pedidos
        $ultimosPedidos = Pedido::where('restaurante_id', $restauranteId)
            ->with('restaurante')
            ->orderBy('data_hora_pedido', 'desc')
            ->limit(5)
            ->get();

        // Itens mais vendidos (últimos 30 dias)
        $itensMaisVendidos = DB::table('pedido_itens')
            ->join('pedidos', 'pedido_itens.pedido_id', '=', 'pedidos.id')
            ->join('cardapio_itens', 'pedido_itens.cardapio_item_id', '=', 'cardapio_itens.id')
            ->where('pedidos.restaurante_id', $restauranteId)
            ->where('pedidos.data_hora_pedido', '>=', now()->subDays(30))
            ->select(
                'cardapio_itens.nome',
                DB::raw('SUM(pedido_itens.quantidade) as total_vendido'),
                DB::raw('SUM(pedido_itens.preco_unitario * pedido_itens.quantidade) as receita_total')
            )
            ->groupBy('cardapio_itens.id', 'cardapio_itens.nome')
            ->orderByDesc('total_vendido')
            ->limit(5)
            ->get();

        // Distribuição por plataforma (últimos 30 dias)
        $pedidosPorPlataforma = Pedido::where('restaurante_id', $restauranteId)
            ->where('data_hora_pedido', '>=', now()->subDays(30))
            ->selectRaw('COALESCE(plataforma_origem, "Balcão") as plataforma, COUNT(*) as total')
            ->groupBy('plataforma')
            ->get();

        // Status dos pedidos em tempo real (hoje)
        $statusPedidos = [
            'pendentes' => Pedido::where('restaurante_id', $restauranteId)
                ->whereDate('data_hora_pedido', today())
                ->where('status', 'pendente')
                ->count(),
            'em_preparo' => Pedido::where('restaurante_id', $restauranteId)
                ->whereDate('data_hora_pedido', today())
                ->where('status', 'em_preparo')
                ->count(),
            'pronto' => Pedido::where('restaurante_id', $restauranteId)
                ->whereDate('data_hora_pedido', today())
                ->where('status', 'pronto')
                ->count(),
        ];

        // Gerar insights automáticos
        $insights = $this->generateInsights($restauranteId, $stats);

        return view('dashboard', compact('stats', 'alertasCriticos', 'ultimosPedidos', 'itensMaisVendidos', 'pedidosPorPlataforma', 'statusPedidos', 'insights'));
    }

    /**
     * Retorna dados para gráficos via AJAX
     */
    public function chartData(Request $request)
    {
        $restauranteId = session('restaurante_id');
        $period = $request->get('period', '7'); // 7, 15, 30 dias

        // Criar array de todos os dias do período
        $dates = [];
        for ($i = $period - 1; $i >= 0; $i--) {
            $dates[] = now()->subDays($i)->format('Y-m-d');
        }

        // Vendas por dia
        $salesData = Pedido::where('restaurante_id', $restauranteId)
            ->where('data_hora_pedido', '>=', now()->subDays($period))
            ->where('status', 'concluido')
            ->selectRaw('DATE(data_hora_pedido) as date, COUNT(*) as count, COALESCE(SUM(valor_total), 0) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        // Preencher dias sem vendas com zero
        $chartData = collect($dates)->map(function($date) use ($salesData) {
            $data = $salesData->get($date);
            return [
                'date' => $date,
                'date_formatted' => \Carbon\Carbon::parse($date)->format('d/m'),
                'count' => $data->count ?? 0,
                'total' => $data->total ?? 0,
            ];
        });

        // Pedidos por status
        $statusData = Pedido::where('restaurante_id', $restauranteId)
            ->where('data_hora_pedido', '>=', now()->subDays($period))
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get();

        return response()->json([
            'success' => true,
            'sales' => $chartData,
            'status' => $statusData,
        ]);
    }

    /**
     * Retorna estatísticas atualizadas via AJAX (para auto-refresh)
     */
    public function refreshStats()
    {
        $restauranteId = session('restaurante_id');

        // Buscar estatísticas sem cache
        Cache::forget("dashboard_stats_{$restauranteId}");
        $stats = Cache::remember("dashboard_stats_{$restauranteId}", 300, function () use ($restauranteId) {
            // Mesma lógica do index, mas retornando apenas stats
            $pedidosHoje = Pedido::where('restaurante_id', $restauranteId)
                ->whereDate('data_hora_pedido', today())
                ->count();

            $receitaHoje = Pedido::where('restaurante_id', $restauranteId)
                ->whereDate('data_hora_pedido', today())
                ->where('status', 'concluido')
                ->sum('valor_total') ?? 0;

            $pedidosOntem = Pedido::where('restaurante_id', $restauranteId)
                ->whereDate('data_hora_pedido', today()->subDay())
                ->count();

            $receitaOntem = Pedido::where('restaurante_id', $restauranteId)
                ->whereDate('data_hora_pedido', today()->subDay())
                ->where('status', 'concluido')
                ->sum('valor_total') ?? 0;

            $percentualPedidos = $pedidosOntem > 0
                ? (($pedidosHoje - $pedidosOntem) / $pedidosOntem) * 100
                : ($pedidosHoje > 0 ? 100 : 0);

            $percentualReceita = $receitaOntem > 0
                ? (($receitaHoje - $receitaOntem) / $receitaOntem) * 100
                : ($receitaHoje > 0 ? 100 : 0);

            $ticketMedio = $pedidosHoje > 0 ? $receitaHoje / $pedidosHoje : 0;

            $alertasCount = Alerta::where('resolvido', false)
                ->whereHas('insumo', fn($q) => $q->where('restaurante_id', $restauranteId))
                ->count();

            return [
                'pedidos_hoje' => $pedidosHoje,
                'percentual_pedidos' => round($percentualPedidos, 1),
                'receita_hoje' => $receitaHoje,
                'percentual_receita_hoje' => round($percentualReceita, 1),
                'ticket_medio' => $ticketMedio,
                'alertas_count' => $alertasCount,
            ];
        });

        // Status dos pedidos em tempo real
        $statusPedidos = [
            'pendentes' => Pedido::where('restaurante_id', $restauranteId)
                ->whereDate('data_hora_pedido', today())
                ->where('status', 'pendente')
                ->count(),
            'em_preparo' => Pedido::where('restaurante_id', $restauranteId)
                ->whereDate('data_hora_pedido', today())
                ->where('status', 'em_preparo')
                ->count(),
            'pronto' => Pedido::where('restaurante_id', $restauranteId)
                ->whereDate('data_hora_pedido', today())
                ->where('status', 'pronto')
                ->count(),
        ];

        return response()->json([
            'success' => true,
            'stats' => $stats,
            'statusPedidos' => $statusPedidos,
            'timestamp' => now()->format('H:i:s'),
        ]);
    }

    /**
     * Gerar insights automáticos baseados nos dados
     */
    private function generateInsights($restauranteId, $stats)
    {
        $insights = [];

        // Insight 1: Horário de pico mudou?
        $horarioPicoOntem = Pedido::where('restaurante_id', $restauranteId)
            ->whereDate('data_hora_pedido', today()->subDay())
            ->selectRaw('HOUR(data_hora_pedido) as hora, COUNT(*) as total')
            ->groupBy('hora')
            ->orderByDesc('total')
            ->first();

        if ($horarioPicoOntem && isset($stats['horario_pico']) && $stats['horario_pico'] !== 'N/A' && $horarioPicoOntem->hora != (int)$stats['horario_pico']) {
            $insights[] = [
                'type' => 'info',
                'icon' => 'clock',
                'message' => "Horário de pico mudou de {$horarioPicoOntem->hora}:00 para {$stats['horario_pico']}"
            ];
        }

        // Insight 2: Performance de vendas
        if (isset($stats['percentual_pedidos']) && $stats['percentual_pedidos'] > 20) {
            $insights[] = [
                'type' => 'success',
                'icon' => 'trending-up',
                'message' => "Excelente! Pedidos aumentaram {$stats['percentual_pedidos']}% hoje vs. ontem"
            ];
        } elseif (isset($stats['percentual_pedidos']) && $stats['percentual_pedidos'] < -20) {
            $insights[] = [
                'type' => 'warning',
                'icon' => 'trending-down',
                'message' => "Atenção: Pedidos caíram " . abs($stats['percentual_pedidos']) . "% hoje. Considere ações promocionais"
            ];
        }

        // Insight 3: Itens próximos ao estoque crítico
        $itensProximos = Estoque::whereHas('insumo', function($q) use ($restauranteId) {
                $q->where('restaurante_id', $restauranteId)
                  ->whereNotNull('ponto_reposicao_minimo');
            })
            ->whereRaw('quantidade_atual <= (SELECT ponto_reposicao_minimo FROM insumos WHERE insumos.id = estoque.insumo_id) * 1.2')
            ->whereRaw('quantidade_atual > (SELECT ponto_reposicao_minimo FROM insumos WHERE insumos.id = estoque.insumo_id)')
            ->count();

        if ($itensProximos > 0) {
            $insights[] = [
                'type' => 'warning',
                'icon' => 'alert-triangle',
                'message' => "{$itensProximos} " . ($itensProximos === 1 ? 'item está' : 'itens estão') . " próximos ao estoque mínimo"
            ];
        }

        // Insight 4: Meta do mês
        if (isset($stats['progresso_meta']) && $stats['progresso_meta'] >= 100) {
            $insights[] = [
                'type' => 'success',
                'icon' => 'trophy',
                'message' => "🎉 Parabéns! Meta do mês alcançada ({$stats['progresso_meta']}%)"
            ];
        } elseif (isset($stats['progresso_meta']) && $stats['progresso_meta'] >= 80) {
            $insights[] = [
                'type' => 'info',
                'icon' => 'target',
                'message' => "Quase lá! Faltam R$ " . number_format($stats['meta_mes'] - $stats['receita_mes'], 2, ',', '.') . " para atingir a meta"
            ];
        }

        // Insight 5: Ticket médio
        if (isset($stats['ticket_medio'])) {
            $ticketMedioOntem = Pedido::where('restaurante_id', $restauranteId)
                ->whereDate('data_hora_pedido', today()->subDay())
                ->where('status', 'concluido')
                ->avg('valor_total') ?? 0;

            if ($ticketMedioOntem > 0 && $stats['ticket_medio'] > $ticketMedioOntem * 1.15) {
                $insights[] = [
                    'type' => 'success',
                    'icon' => 'dollar-sign',
                    'message' => "Ticket médio 15% maior que ontem! Seus clientes estão comprando mais"
                ];
            }
        }

        return $insights;
    }

    /**
     * Limpar cache das estatísticas
     */
    public function clearCache()
    {
        $restauranteId = session('restaurante_id');
        Cache::forget("dashboard_stats_{$restauranteId}");

        return response()->json([
            'success' => true,
            'message' => 'Cache limpo com sucesso!'
        ]);
    }
}
