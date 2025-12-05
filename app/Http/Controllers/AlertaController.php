<?php

namespace App\Http\Controllers;

use App\Models\Alerta;
use App\Models\Insumo;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AlertaController extends Controller
{
    public function index(Request $request)
    {
        $restauranteId = $this->restauranteId();

        $query = Alerta::with('insumo')
            ->whereHas('insumo', fn ($q) => $q->where('restaurante_id', $restauranteId));

        // Filtro de busca
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('mensagem', 'like', "%{$search}%")
                  ->orWhere('tipo_alerta', 'like', "%{$search}%")
                  ->orWhereHas('insumo', fn($q2) => $q2->where('nome', 'like', "%{$search}%"));
            });
        }

        // Filtro por tipo
        if ($request->filled('tipo')) {
            $query->where('tipo_alerta', $request->get('tipo'));
        }

        // Filtro por status
        if ($request->filled('status')) {
            $status = $request->get('status');
            if ($status === 'pendente') {
                $query->where('visualizado', false);
            } elseif ($status === 'visualizado') {
                $query->where('visualizado', true);
            } elseif ($status === 'aberto') {
                $query->where('resolvido', false);
            } elseif ($status === 'resolvido') {
                $query->where('resolvido', true);
            }
        }

        $sortBy = $request->get('sort', 'data_hora_alerta');
        $sortOrder = $request->get('order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $perPage = $request->get('per_page', 15);
        $alertas = $query->paginate($perPage)->withQueryString();

        // Estatísticas
        $stats = [
            'total' => Alerta::whereHas('insumo', fn($q) => $q->where('restaurante_id', $restauranteId))->count(),
            'pendentes' => Alerta::whereHas('insumo', fn($q) => $q->where('restaurante_id', $restauranteId))
                ->where('visualizado', false)->count(),
            'abertos' => Alerta::whereHas('insumo', fn($q) => $q->where('restaurante_id', $restauranteId))
                ->where('resolvido', false)->count(),
            'criticos' => Alerta::whereHas('insumo', fn($q) => $q->where('restaurante_id', $restauranteId))
                ->where('tipo_alerta', 'estoque_baixo')->where('resolvido', false)->count(),
        ];

        // Tipos disponíveis
        $tipos = Alerta::whereHas('insumo', fn($q) => $q->where('restaurante_id', $restauranteId))
            ->distinct()
            ->orderBy('tipo_alerta')
            ->pluck('tipo_alerta');

        return view('alertas.index', compact('alertas', 'stats', 'tipos'));
    }

    public function create()
    {
        $insumos = $this->insumosDoRestaurante();

        return view('alertas.create', compact('insumos'));
    }

    public function store(Request $request)
    {
        $restauranteId = $this->restauranteId();

        $data = $request->validate([
            'insumo_id' => [
                'required',
                Rule::exists('insumos', 'id')->where('restaurante_id', $restauranteId),
            ],
            'tipo_alerta' => ['required', 'string', 'max:100'],
            'mensagem' => ['required', 'string'],
            'data_hora_alerta' => ['nullable', 'date'],
            'visualizado' => ['nullable', 'boolean'],
            'resolvido' => ['nullable', 'boolean'],
        ]);

        $data['visualizado'] = $request->boolean('visualizado');
        $data['resolvido'] = $request->boolean('resolvido');

        Alerta::create($data);

        return redirect()->route('alertas.index')->with('success', 'Alerta criado com sucesso.');
    }

    public function edit(Alerta $alerta)
    {
        $this->authorizeAlerta($alerta);

        $insumos = $this->insumosDoRestaurante();

        return view('alertas.edit', compact('alerta', 'insumos'));
    }

    public function update(Request $request, Alerta $alerta)
    {
        $this->authorizeAlerta($alerta);

        $restauranteId = $this->restauranteId();

        $data = $request->validate([
            'insumo_id' => [
                'required',
                Rule::exists('insumos', 'id')->where('restaurante_id', $restauranteId),
            ],
            'tipo_alerta' => ['required', 'string', 'max:100'],
            'mensagem' => ['required', 'string'],
            'data_hora_alerta' => ['nullable', 'date'],
            'visualizado' => ['nullable', 'boolean'],
            'resolvido' => ['nullable', 'boolean'],
        ]);

        $data['visualizado'] = $request->boolean('visualizado');
        $data['resolvido'] = $request->boolean('resolvido');

        $alerta->update($data);

        return redirect()->route('alertas.index')->with('success', 'Alerta atualizado com sucesso.');
    }

    public function destroy(Alerta $alerta)
    {
        $this->authorizeAlerta($alerta);

        $alerta->delete();

        return redirect()->route('alertas.index')->with('success', 'Alerta removido com sucesso.');
    }

    protected function authorizeAlerta(Alerta $alerta): void
    {
        abort_unless(optional($alerta->insumo)->restaurante_id === $this->restauranteId(), 403);
    }

    protected function insumosDoRestaurante()
    {
        return Insumo::where('restaurante_id', $this->restauranteId())
            ->orderBy('nome')
            ->pluck('nome', 'id');
    }
}
