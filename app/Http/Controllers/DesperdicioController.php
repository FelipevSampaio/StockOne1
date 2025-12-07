<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Desperdicio;
use App\Models\Insumo;

class DesperdicioController extends Controller
{
    // Registrar uma perda
    public function store(Request $request)
    {
        $validated = $request->validate([
            'insumo_id' => 'required|exists:insumos,id',
            'quantidade' => 'required|numeric|min:0.01',
            'motivo' => 'required|string',
            'data' => 'required|date',
        ]);
        $desperdicio = Desperdicio::create($validated);
        return response()->json($desperdicio, 201);
    }

    // Listar perdas registradas
    public function index(Request $request)
    {
        $query = Desperdicio::with('insumo');
        if ($request->has('insumo_id')) {
            $query->where('insumo_id', $request->insumo_id);
        }
        if ($request->has('data')) {
            $query->whereDate('data', $request->data);
        }
        return response()->json($query->orderByDesc('data')->get());
    }

    // Análise simples: total de perdas por insumo
    public function analise()
    {
        $analise = Desperdicio::selectRaw('insumo_id, SUM(quantidade) as total_perda')
            ->groupBy('insumo_id')
            ->with('insumo')
            ->get()
            ->map(function($item) {
                $acao = null;
                if ($item->total_perda > 10) {
                    $acao = 'Rever processos de armazenamento e treinamento da equipe.';
                } elseif ($item->total_perda > 0) {
                    $acao = 'Monitorar perdas e buscar causas específicas.';
                }
                return [
                    'insumo' => $item->insumo->nome ?? null,
                    'total_perda' => $item->total_perda,
                    'acao_sugerida' => $acao,
                ];
            });
        return response()->json($analise);
    }
}
