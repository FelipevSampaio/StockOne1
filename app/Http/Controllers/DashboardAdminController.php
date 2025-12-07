<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Restaurante;
use App\Models\Pedido;

class DashboardAdminController extends Controller
{
    /**
     * Exibir dashboard administrativo
     */
    public function index()
    {
        // Sugestão de pratos do dia baseada em estoque
        $pratosDoDia = [];
        try {
            $pratosDoDia = \App\Models\CardapioItem::sugestaoPratosDoDia(3);
        } catch (\Exception $e) {}
        // Estatísticas gerais
        $totalUsers = User::count();
        $totalUsersActive = User::whereNull('deleted_at')->count();
        $totalUsersInactive = User::whereNotNull('deleted_at')->count();
        $totalAdmins = User::where('role', 'admin')->whereNull('deleted_at')->count();
        $totalRegularUsers = User::where('role', 'user')->whereNull('deleted_at')->count();

        // Restaurantes
        $totalRestaurantes = Restaurante::count();
        $restaurantesAtivos = Restaurante::where('status', 'ativo')->count();
        $restaurantesInativos = Restaurante::where('status', 'inativo')->count();

        // Métricas avançadas de restaurantes
        $restaurantesNovosEsteMes = Restaurante::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
        $restaurantesNovosEstaSemana = Restaurante::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count();
        $restaurantesSemUsuarios = Restaurante::doesntHave('users')->count();
        $mediaUsuariosPorRestaurante = $totalRestaurantes > 0 ? round($totalUsersActive / $totalRestaurantes, 1) : 0;

        // Pedidos (se a tabela existir)
        $totalPedidos = 0;
        $pedidosHoje = 0;
        $pedidosEsteMes = 0;
        $pedidosPendentes = 0;

        try {
            $totalPedidos = Pedido::count();
            $pedidosHoje = Pedido::whereDate('created_at', today())->count();
            $pedidosEsteMes = Pedido::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count();
            $pedidosPendentes = Pedido::where('status', 'pendente')->count();
        } catch (\Exception $e) {
            // Tabela pode não existir ainda
        }

        // Dados adicionais para novos cards
        $totalInsumos = 0;
        $insumosEstoqueBaixo = 0;
        $totalCardapioItens = 0;
        $totalAuditLogs = 0;

        try {
            $totalInsumos = \App\Models\Insumo::count();
            $totalCardapioItens = \App\Models\CardapioItem::count();
            $totalAuditLogs = \App\Models\AuditLog::count();

            // Insumos com estoque baixo (menos de 20% do estoque total)
            $insumosEstoqueBaixo = \App\Models\Estoque::whereRaw('quantidade < quantidade_minima')->count();
        } catch (\Exception $e) {
            // Ignorar se as tabelas não existirem
        }

        // Usuários recentes
        $usuariosRecentes = User::withTrashed()
            ->with('restaurante')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Usuários por restaurante
        $usuariosPorRestaurante = User::selectRaw('restaurante_id, COUNT(*) as total')
            ->whereNull('deleted_at')
            ->with('restaurante')
            ->groupBy('restaurante_id')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        // Top Restaurantes por pedidos
        $topRestaurantesPedidos = [];
        try {
            $topRestaurantesPedidos = Restaurante::withCount(['users', 'pedidos'])
                ->orderByDesc('pedidos_count')
                ->limit(5)
                ->get();
        } catch (\Exception $e) {
            // Ignorar se não houver relação
        }

        // Health Score dos Restaurantes
        $restaurantesHealth = $this->calcularHealthScore();

        // Atividades recentes
        $atividadesRecentes = $this->obterAtividadesRecentes();

        // Comparação com mês anterior
        $restaurantesMesAnterior = Restaurante::whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->count();
        $crescimentoRestaurantes = $restaurantesMesAnterior > 0
            ? round((($restaurantesNovosEsteMes - $restaurantesMesAnterior) / $restaurantesMesAnterior) * 100, 1)
            : 0;

        $usersMesAnterior = User::whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->count();
        $usersEsteMes = User::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
        $crescimentoUsuarios = $usersMesAnterior > 0
            ? round((($usersEsteMes - $usersMesAnterior) / $usersMesAnterior) * 100, 1)
            : 0;

            // Relatório de itens mais/menos vendidos
            $itensMaisVendidos = \App\Models\CardapioItem::select('cardapio_itens.id', 'cardapio_itens.nome')
                ->join('pedido_itens', 'cardapio_itens.id', '=', 'pedido_itens.cardapio_item_id')
                ->selectRaw('SUM(pedido_itens.quantidade) as total_vendido')
                ->groupBy('cardapio_itens.id', 'cardapio_itens.nome')
                ->orderByDesc('total_vendido')
                ->limit(5)
                ->get();

            $itensMenosVendidos = \App\Models\CardapioItem::select('cardapio_itens.id', 'cardapio_itens.nome')
                ->join('pedido_itens', 'cardapio_itens.id', '=', 'pedido_itens.cardapio_item_id')
                ->selectRaw('SUM(pedido_itens.quantidade) as total_vendido')
                ->groupBy('cardapio_itens.id', 'cardapio_itens.nome')
                ->orderBy('total_vendido', 'asc')
                ->limit(5)
                ->get();

            // Tendências de vendas (crescimento ou queda nos últimos 30 dias)
            $tendencias = \App\Models\CardapioItem::select('cardapio_itens.id', 'cardapio_itens.nome')
                ->join('pedido_itens', 'cardapio_itens.id', '=', 'pedido_itens.cardapio_item_id')
                ->selectRaw('SUM(CASE WHEN pedido_itens.created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY) THEN pedido_itens.quantidade ELSE 0 END) as vendas_30d')
                ->selectRaw('SUM(CASE WHEN pedido_itens.created_at < DATE_SUB(NOW(), INTERVAL 30 DAY) THEN pedido_itens.quantidade ELSE 0 END) as vendas_anteriores')
                ->groupBy('cardapio_itens.id', 'cardapio_itens.nome')
                ->get()
                ->map(function($item) {
                    $item->tendencia = $item->vendas_anteriores > 0
                        ? round((($item->vendas_30d - $item->vendas_anteriores) / $item->vendas_anteriores) * 100, 1)
                        : null;
                    return $item;
                });
        return view('admin.dashboard', compact(
            'totalUsers',
            'totalUsersActive',
            'totalUsersInactive',
            'totalAdmins',
            'totalRegularUsers',
            'totalRestaurantes',
            'restaurantesAtivos',
            'restaurantesInativos',
            'restaurantesNovosEsteMes',
            'pratosDoDia',
            'restaurantesNovosEstaSemana',
            'restaurantesSemUsuarios',
            'mediaUsuariosPorRestaurante',
            'crescimentoRestaurantes',
            'crescimentoUsuarios',
            'totalPedidos',
            'pedidosHoje',
            'pedidosEsteMes',
            'pedidosPendentes',
            'totalInsumos',
            'insumosEstoqueBaixo',
            'totalCardapioItens',
            'totalAuditLogs',
            'usuariosRecentes',
            'usuariosPorRestaurante',
            'topRestaurantesPedidos',
            'restaurantesHealth',
            'atividadesRecentes'
                , 'itensMaisVendidos', 'itensMenosVendidos', 'tendencias'
        ));
    }

    /**
     * Retornar dados de atividade para gráfico
     */
    public function activityData()
    {
        $period = request('period', '7d');

        $days = match($period) {
            '7d' => 7,
            '30d' => 30,
            '90d' => 90,
            default => 7
        };

        $dates = [];
        $userData = [];
        $orderData = [];

        for ($i = $days - 1; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $dates[] = $date->format('d/m');

            // Contar usuários criados nesse dia
            $userData[] = User::whereDate('created_at', $date->toDateString())->count();

            // Contar pedidos do dia (se existir)
            try {
                $orderData[] = Pedido::whereDate('created_at', $date->toDateString())->count();
            } catch (\Exception $e) {
                $orderData[] = 0;
            }
        }

        return response()->json([
            'labels' => $dates,
            'datasets' => [
                [
                    'label' => 'Novos Usuários',
                    'data' => $userData,
                    'borderColor' => 'rgb(220, 38, 38)',
                    'backgroundColor' => 'rgba(220, 38, 38, 0.1)',
                    'tension' => 0.4
                ],
                [
                    'label' => 'Pedidos',
                    'data' => $orderData,
                    'borderColor' => 'rgb(59, 130, 246)',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                    'tension' => 0.4
                ]
            ]
        ]);
    }

    /**
     * Calcular Health Score dos restaurantes
     */
    private function calcularHealthScore()
    {
        $restaurantes = Restaurante::withCount('users')->get();
        $healthData = [
            'saudavel' => 0,
            'atencao' => 0,
            'critico' => 0,
            'inativo' => 0,
            'detalhes' => []
        ];

        foreach ($restaurantes as $restaurante) {
            $score = 0;
            $status = '';
            $motivos = [];

            // Critérios de saúde
            if ($restaurante->status === 'ativo') {
                $score += 25;
            } else {
                $motivos[] = 'Status inativo';
            }

            // Tem usuários?
            if ($restaurante->users_count > 0) {
                $score += 25;
            } else {
                $motivos[] = 'Sem usuários';
            }

            // Atividade recente
            try {
                $pedidosRecentes = \App\Models\Pedido::where('restaurante_id', $restaurante->id)
                    ->where('created_at', '>=', now()->subDays(30))
                    ->count();

                if ($pedidosRecentes > 10) {
                    $score += 30;
                } elseif ($pedidosRecentes > 0) {
                    $score += 15;
                } else {
                    $motivos[] = 'Sem pedidos recentes';
                }
            } catch (\Exception $e) {
                // Ignorar se não houver tabela de pedidos
            }

            // Tem itens no cardápio?
            try {
                $itensCardapio = \App\Models\CardapioItem::where('restaurante_id', $restaurante->id)->count();
                if ($itensCardapio > 0) {
                    $score += 20;
                } else {
                    $motivos[] = 'Sem itens no cardápio';
                }
            } catch (\Exception $e) {
                // Ignorar
            }

            // Definir status baseado no score
            if ($score >= 80) {
                $status = 'saudavel';
                $healthData['saudavel']++;
            } elseif ($score >= 50) {
                $status = 'atencao';
                $healthData['atencao']++;
            } elseif ($score >= 25) {
                $status = 'critico';
                $healthData['critico']++;
            } else {
                $status = 'inativo';
                $healthData['inativo']++;
            }

            if ($status === 'critico' || $status === 'inativo') {
                $healthData['detalhes'][] = [
                    'restaurante' => $restaurante->nome,
                    'status' => $status,
                    'score' => $score,
                    'motivos' => $motivos
                ];
            }
        }

        return $healthData;
    }

    /**
     * Obter atividades recentes do sistema
     */
    private function obterAtividadesRecentes()
    {
        $atividades = [];

        // Novos restaurantes
        $novosRestaurantes = Restaurante::orderBy('created_at', 'desc')
            ->limit(3)
            ->get()
            ->map(fn($r) => [
                'tipo' => 'restaurante_novo',
                'icone' => 'building',
                'titulo' => 'Novo restaurante cadastrado',
                'descricao' => $r->nome,
                'tempo' => $r->created_at->diffForHumans(),
                'created_at' => $r->created_at
            ]);

        // Novos usuários
        $novosUsuarios = User::orderBy('created_at', 'desc')
            ->limit(3)
            ->get()
            ->map(fn($u) => [
                'tipo' => 'usuario_novo',
                'icone' => 'user',
                'titulo' => 'Novo usuário registrado',
                'descricao' => $u->name . ' (' . $u->email . ')',
                'tempo' => $u->created_at->diffForHumans(),
                'created_at' => $u->created_at
            ]);

        // Logs de auditoria recentes
        try {
            $logsRecentes = \App\Models\AuditLog::with('user')
                ->orderBy('created_at', 'desc')
                ->limit(3)
                ->get()
                ->map(fn($log) => [
                    'tipo' => 'auditoria',
                    'icone' => 'clipboard',
                    'titulo' => $log->action ?? 'Ação administrativa',
                    'descricao' => ($log->user ? $log->user->name : 'Sistema') . ' - ' . ($log->description ?? ''),
                    'tempo' => $log->created_at->diffForHumans(),
                    'created_at' => $log->created_at
                ]);

            $atividades = $novosRestaurantes->concat($novosUsuarios)->concat($logsRecentes);
        } catch (\Exception $e) {
            $atividades = $novosRestaurantes->concat($novosUsuarios);
        }

        // Ordenar por data e limitar
        return $atividades->sortByDesc('created_at')->take(10)->values()->all();
    }
}
