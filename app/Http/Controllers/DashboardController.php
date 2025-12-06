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

            // Taxa de Conversão (pedidos concluídos vs total - excluindo cancelados)
            $pedidosConcluidos = Pedido::where('restaurante_id', $restauranteId)
                ->whereDate('data_hora_pedido', today())
                ->where('status', 'concluido')
                ->count();

            $pedidosCancelados = Pedido::where('restaurante_id', $restauranteId)
                ->whereDate('data_hora_pedido', today())
                ->where('status', 'cancelado')
                ->count();

            $taxaConversao = $pedidosHoje > 0
                ? (($pedidosConcluidos / $pedidosHoje) * 100)
                : 0;

            // Tempo Médio de Preparo (em minutos)
            // Como não temos data_hora_conclusao, usamos o tempo_preparo_estimado médio
            $tempoMedioPreparo = Pedido::where('restaurante_id', $restauranteId)
                ->whereDate('data_hora_pedido', today())
                ->where('status', 'concluido')
                ->whereNotNull('tempo_preparo_estimado')
                ->avg('tempo_preparo_estimado') ?? 0;

            // Taxa de Recompra (últimos 30 dias - clientes que fizeram 2+ pedidos)
            // NOTA: Desabilitado temporariamente - campo cliente_nome não existe na tabela pedidos
            // TODO: Adicionar campo para identificação de clientes (CPF, telefone, email, etc)
            $clientesUnicos = 0;
            $clientesRecorrentes = 0;
            $taxaRecompra = 0;

            /* Código original - reativar quando campo cliente for adicionado:
            $clientesUnicos = Pedido::where('restaurante_id', $restauranteId)
                ->where('data_hora_pedido', '>=', now()->subDays(30))
                ->whereNotNull('cliente_nome')
                ->distinct('cliente_nome')
                ->count('cliente_nome');

            $clientesRecorrentes = Pedido::where('restaurante_id', $restauranteId)
                ->where('data_hora_pedido', '>=', now()->subDays(30))
                ->whereNotNull('cliente_nome')
                ->selectRaw('cliente_nome, COUNT(*) as total')
                ->groupBy('cliente_nome')
                ->having('total', '>=', 2)
                ->count();

            $taxaRecompra = $clientesUnicos > 0
                ? (($clientesRecorrentes / $clientesUnicos) * 100)
                : 0;
            */

            // Margem de Lucro Estimada (receita - custo estimado de insumos)
            // Calculando custo total dos insumos usados nos pedidos de hoje
            $custoInsumosHoje = DB::table('pedido_itens')
                ->join('pedidos', 'pedido_itens.pedido_id', '=', 'pedidos.id')
                ->join('receitas', 'pedido_itens.cardapio_item_id', '=', 'receitas.cardapio_item_id')
                ->join('insumos', 'receitas.insumo_id', '=', 'insumos.id')
                ->where('pedidos.restaurante_id', $restauranteId)
                ->whereDate('pedidos.data_hora_pedido', today())
                ->where('pedidos.status', 'concluido')
                ->selectRaw('SUM(pedido_itens.quantidade * receitas.quantidade_necessaria * COALESCE(insumos.custo_unitario, 0)) as custo_total')
                ->value('custo_total') ?? 0;

            $margemLucro = $receitaHoje > 0
                ? ((($receitaHoje - $custoInsumosHoje) / $receitaHoje) * 100)
                : 0;

            // Itens com Estoque Crítico que podem faltar HOJE (baseado na velocidade de venda)
            $itensRiscoCriticoHoje = 0;
            try {
                // Pegar itens vendidos hoje e suas quantidades
                $itensCriticosHoje = DB::table('estoque')
                    ->join('insumos', 'estoque.insumo_id', '=', 'insumos.id')
                    ->join('receitas', 'insumos.id', '=', 'receitas.insumo_id')
                    ->join('pedido_itens', 'receitas.cardapio_item_id', '=', 'pedido_itens.cardapio_item_id')
                    ->join('pedidos', 'pedido_itens.pedido_id', '=', 'pedidos.id')
                    ->where('insumos.restaurante_id', $restauranteId)
                    ->whereDate('pedidos.data_hora_pedido', today())
                    ->whereNotNull('insumos.ponto_reposicao_minimo')
                    ->selectRaw('insumos.id, estoque.quantidade_atual, insumos.ponto_reposicao_minimo, SUM(pedido_itens.quantidade * receitas.quantidade_necessaria) as consumo_hoje')
                    ->groupBy('insumos.id', 'estoque.quantidade_atual', 'insumos.ponto_reposicao_minimo')
                    ->havingRaw('quantidade_atual - consumo_hoje <= ponto_reposicao_minimo')
                    ->count();

                $itensRiscoCriticoHoje = $itensCriticosHoje;
            } catch (\Exception $e) {
                \Log::warning('Erro ao calcular itens em risco crítico hoje: ' . $e->getMessage());
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
                // Novas métricas KPI
                'taxa_conversao' => round($taxaConversao ?? 0, 1),
                'pedidos_concluidos' => $pedidosConcluidos ?? 0,
                'pedidos_cancelados' => $pedidosCancelados ?? 0,
                'tempo_medio_preparo' => round($tempoMedioPreparo ?? 0, 0),
                'taxa_recompra' => round($taxaRecompra ?? 0, 1),
                'clientes_recorrentes' => $clientesRecorrentes ?? 0,
                'clientes_unicos' => $clientesUnicos ?? 0,
                'margem_lucro' => round($margemLucro ?? 0, 1),
                'custo_insumos_hoje' => $custoInsumosHoje ?? 0,
                'itens_risco_critico_hoje' => $itensRiscoCriticoHoje ?? 0,
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

        // Insight 6: Taxa de conversão baixa
        if (isset($stats['taxa_conversao']) && $stats['taxa_conversao'] < 70 && $stats['pedidos_hoje'] > 5) {
            $insights[] = [
                'type' => 'warning',
                'icon' => 'alert-triangle',
                'message' => "Taxa de conversão em {$stats['taxa_conversao']}%. Verifique pedidos cancelados e qualidade do atendimento"
            ];
        }

        // Insight 7: Tempo de preparo elevado (estimado)
        if (isset($stats['tempo_medio_preparo']) && $stats['tempo_medio_preparo'] > 45) {
            $insights[] = [
                'type' => 'warning',
                'icon' => 'clock',
                'message' => "Tempo estimado de preparo em " . round($stats['tempo_medio_preparo']) . " minutos. Considere otimizar processos"
            ];
        } elseif (isset($stats['tempo_medio_preparo']) && $stats['tempo_medio_preparo'] > 0 && $stats['tempo_medio_preparo'] <= 25) {
            $insights[] = [
                'type' => 'success',
                'icon' => 'trending-up',
                'message' => "Excelente! Tempo estimado de preparo em apenas " . round($stats['tempo_medio_preparo']) . " minutos"
            ];
        }

        // Insight 8: Itens em risco de acabar hoje
        if (isset($stats['itens_risco_critico_hoje']) && $stats['itens_risco_critico_hoje'] > 0) {
            $insights[] = [
                'type' => 'warning',
                'icon' => 'alert-triangle',
                'message' => "⚠️ {$stats['itens_risco_critico_hoje']} " . ($stats['itens_risco_critico_hoje'] === 1 ? 'item pode' : 'itens podem') . " acabar ainda hoje baseado no ritmo de vendas"
            ];
        }

        // Insight 9: Margem de lucro
        if (isset($stats['margem_lucro'])) {
            if ($stats['margem_lucro'] < 30 && $stats['receita_hoje'] > 100) {
                $insights[] = [
                    'type' => 'warning',
                    'icon' => 'dollar-sign',
                    'message' => "Margem de lucro em {$stats['margem_lucro']}%. Revise preços ou custos de insumos"
                ];
            } elseif ($stats['margem_lucro'] >= 60) {
                $insights[] = [
                    'type' => 'success',
                    'icon' => 'trophy',
                    'message' => "Ótima margem de lucro! {$stats['margem_lucro']}% de rentabilidade hoje"
                ];
            }
        }

        // Insight 10: Taxa de recompra (Desabilitado - aguardando implementação de cadastro de clientes)
        // TODO: Reativar quando campo cliente for adicionado
        /*
        if (isset($stats['taxa_recompra']) && $stats['taxa_recompra'] >= 40 && $stats['clientes_unicos'] >= 10) {
            $insights[] = [
                'type' => 'success',
                'icon' => 'target',
                'message' => "Parabéns! {$stats['taxa_recompra']}% dos clientes voltaram nos últimos 30 dias"
            ];
        }
        */

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
