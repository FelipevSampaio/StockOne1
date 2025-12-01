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
        // Estatísticas gerais
        $totalUsers = User::count();
        $totalUsersActive = User::whereNull('deleted_at')->count();
        $totalUsersInactive = User::whereNotNull('deleted_at')->count();
        $totalAdmins = User::where('role', 'admin')->whereNull('deleted_at')->count();
        $totalRegularUsers = User::where('role', 'user')->whereNull('deleted_at')->count();

        // Restaurantes
        $totalRestaurantes = Restaurante::count();

        // Pedidos (se a tabela existir)
        $totalPedidos = 0;
        $pedidosHoje = 0;
        $pedidosEsteMes = 0;

        try {
            $totalPedidos = Pedido::count();
            $pedidosHoje = Pedido::whereDate('created_at', today())->count();
            $pedidosEsteMes = Pedido::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count();
        } catch (\Exception $e) {
            // Tabela pode não existir ainda
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
            ->get();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalUsersActive',
            'totalUsersInactive',
            'totalAdmins',
            'totalRegularUsers',
            'totalRestaurantes',
            'totalPedidos',
            'pedidosHoje',
            'pedidosEsteMes',
            'usuariosRecentes',
            'usuariosPorRestaurante'
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
}
