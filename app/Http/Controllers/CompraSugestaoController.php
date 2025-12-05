<?php

namespace App\Http\Controllers;

use App\Models\CompraSugestao;
use App\Models\Insumo;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CompraSugestaoController extends Controller
{
    public function index(Request $request)
    {
        $restauranteId = $this->restauranteId();

        $query = CompraSugestao::with('insumo')
            ->whereHas('insumo', fn ($q) => $q->where('restaurante_id', $restauranteId));

        // Filtro de busca
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('insumo', fn ($q2) => $q2->where('nome', 'like', "%{$search}%"))
                  ->orWhere('justificativa', 'like', "%{$search}%")
                  ->orWhere('status', 'like', "%{$search}%");
            });
        }

        // Filtro por status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filtro por período
        if ($request->filled('data_inicio')) {
            $query->whereDate('data_geracao', '>=', $request->data_inicio);
        }
        if ($request->filled('data_fim')) {
            $query->whereDate('data_geracao', '<=', $request->data_fim);
        }

        // Ordenação
        $sortField = $request->get('sort', 'data_geracao');
        $sortDirection = $request->get('direction', 'desc');
        $query->orderBy($sortField, $sortDirection);

        $perPage = $request->get('per_page', 15);
        $sugestoes = $query->paginate($perPage)->withQueryString();

        // Estatísticas
        $totalSugestoes = CompraSugestao::whereHas('insumo', fn ($q) => $q->where('restaurante_id', $restauranteId))->count();

        $pendentes = CompraSugestao::whereHas('insumo', fn ($q) => $q->where('restaurante_id', $restauranteId))
            ->where('status', 'pendente')
            ->count();

        $aprovadas = CompraSugestao::whereHas('insumo', fn ($q) => $q->where('restaurante_id', $restauranteId))
            ->where('status', 'aprovada')
            ->count();

        $valorTotal = CompraSugestao::whereHas('insumo', fn ($q) => $q->where('restaurante_id', $restauranteId))
            ->join('insumos', 'compras_sugestoes.insumo_id', '=', 'insumos.id')
            ->sum(\DB::raw('compras_sugestoes.quantidade_sugerida * insumos.custo_unitario'));

        $stats = [
            'total' => $totalSugestoes,
            'pendentes' => $pendentes,
            'aprovadas' => $aprovadas,
            'valor_total' => $valorTotal,
        ];

        // Lista de status únicos
        $statusList = CompraSugestao::whereHas('insumo', fn ($q) => $q->where('restaurante_id', $restauranteId))
            ->distinct('status')
            ->pluck('status');

        return view('compras_sugestoes.index', compact('sugestoes', 'stats', 'statusList'));
    }

    public function create()
    {
        $insumos = $this->insumosDoRestaurante();

        return view('compras_sugestoes.create', compact('insumos'));
    }

    public function store(Request $request)
    {
        $restauranteId = $this->restauranteId();

        $data = $request->validate([
            'insumo_id' => [
                'required',
                Rule::exists('insumos', 'id')->where('restaurante_id', $restauranteId),
            ],
            'quantidade_sugerida' => ['required', 'numeric'],
            'justificativa' => ['nullable', 'string'],
            'status' => ['required', 'string', 'max:50'],
            'periodo_analise_dias' => ['nullable', 'integer', 'min:0'],
            'data_geracao' => ['required', 'date'],
        ]);

        CompraSugestao::create($data);

        return redirect()->route('compras-sugestoes.index')->with('success', 'Sugestão de compra registrada com sucesso.');
    }

    public function edit(CompraSugestao $compraSugestao)
    {
        $this->authorizeSugestao($compraSugestao);

        $insumos = $this->insumosDoRestaurante();

        return view('compras_sugestoes.edit', [
            'sugestao' => $compraSugestao,
            'insumos' => $insumos,
        ]);
    }

    public function update(Request $request, CompraSugestao $compraSugestao)
    {
        $this->authorizeSugestao($compraSugestao);

        $restauranteId = $this->restauranteId();

        $data = $request->validate([
            'insumo_id' => [
                'required',
                Rule::exists('insumos', 'id')->where('restaurante_id', $restauranteId),
            ],
            'quantidade_sugerida' => ['required', 'numeric'],
            'justificativa' => ['nullable', 'string'],
            'status' => ['required', 'string', 'max:50'],
            'periodo_analise_dias' => ['nullable', 'integer', 'min:0'],
            'data_geracao' => ['required', 'date'],
        ]);

        $compraSugestao->update($data);

        return redirect()->route('compras-sugestoes.index')->with('success', 'Sugestão de compra atualizada com sucesso.');
    }

    public function destroy(CompraSugestao $compraSugestao)
    {
        $this->authorizeSugestao($compraSugestao);

        $compraSugestao->delete();

        return redirect()->route('compras-sugestoes.index')->with('success', 'Sugestão de compra removida com sucesso.');
    }

    protected function authorizeSugestao(CompraSugestao $compraSugestao): void
    {
        abort_unless(optional($compraSugestao->insumo)->restaurante_id === $this->restauranteId(), 403);
    }

    protected function insumosDoRestaurante()
    {
        return Insumo::where('restaurante_id', $this->restauranteId())
            ->orderBy('nome')
            ->pluck('nome', 'id');
    }
}
