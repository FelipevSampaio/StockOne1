<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pedido;

class ReportController extends Controller
    // Relatório de vendas por funcionário
    public function vendasPorFuncionario(Request $request)
    {
        $start = $request->input('start_date');
        $end = $request->input('end_date');

        $query = Pedido::query();
        if ($start && $end) {
            $query->whereBetween('created_at', [$start, $end]);
        }

        $pedidos = $query->with('usuario')->get();

        // Agrupar por funcionário
        $relatorio = $pedidos->groupBy(function($pedido) {
            return $pedido->usuario->name ?? 'Desconhecido';
        })->map(function($grupo) {
            return [
                'quantidade' => $grupo->count(),
                'total' => $grupo->sum('valor_total'),
            ];
        });

        return view('admin.reports.vendas-funcionario', [
            'relatorio' => $relatorio,
            'start' => $start,
            'end' => $end,
        ]);
    }
{
    // Relatório de vendas por período
    public function vendasPorPeriodo(Request $request)
    {
        $start = $request->input('start_date');
        $end = $request->input('end_date');

        $query = Pedido::query();
        if ($start && $end) {
            $query->whereBetween('created_at', [$start, $end]);
        }

        $pedidos = $query->with(['usuario', 'itens.cardapioItem'])->get();

        // Agrupamento e soma
        $totalVendas = $pedidos->sum('valor_total');

        return view('admin.reports.vendas-periodo', [
            'pedidos' => $pedidos,
            'totalVendas' => $totalVendas,
            'start' => $start,
            'end' => $end,
        ]);
    }
    // Relatório de vendas por prato
    public function vendasPorPrato(Request $request)
    {
        $start = $request->input('start_date');
        $end = $request->input('end_date');

        $query = \App\Models\PedidoItem::query();
        if ($start && $end) {
            $query->whereHas('pedido', function($q) use ($start, $end) {
                $q->whereBetween('created_at', [$start, $end]);
            });
        }

        $itens = $query->with('cardapioItem')->get();

        // Agrupar por prato
        $relatorio = $itens->groupBy('cardapioItem.nome')->map(function($grupo) {
            return [
                'quantidade' => $grupo->sum('quantidade'),
                'total' => $grupo->sum('valor_total'),
            ];
        });

        return view('admin.reports.vendas-prato', [
            'relatorio' => $relatorio,
            'start' => $start,
            'end' => $end,
        ]);
    }
}
