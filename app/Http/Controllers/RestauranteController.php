<?php

namespace App\Http\Controllers;

use App\Models\Restaurante;
use Illuminate\Http\Request;

class RestauranteController extends Controller
{
    public function index()
    {
        $restaurantes = Restaurante::orderBy('nome')->paginate(12);

        return view('restaurantes.index', compact('restaurantes'));
    }

    public function create()
    {
        return view('restaurantes.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nome' => ['required', 'string', 'max:255'],
            'cnpj' => ['nullable', 'string', 'max:18'],
            'endereco' => ['nullable', 'string'],
            'telefone' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255'],
            'status' => ['required', 'string', 'max:50'],
        ]);

        Restaurante::create($data);

        return redirect()->route('restaurantes.index')->with('success', 'Restaurante cadastrado com sucesso.');
    }

    public function edit(Restaurante $restaurante)
    {
        return view('restaurantes.edit', compact('restaurante'));
    }

    public function update(Request $request, Restaurante $restaurante)
    {
        $data = $request->validate([
            'nome' => ['required', 'string', 'max:255'],
            'cnpj' => ['nullable', 'string', 'max:18'],
            'endereco' => ['nullable', 'string'],
            'telefone' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255'],
            'status' => ['required', 'string', 'max:50'],
        ]);

        $restaurante->update($data);

        return redirect()->route('restaurantes.index')->with('success', 'Restaurante atualizado com sucesso.');
    }

    public function destroy(Restaurante $restaurante)
    {
        $restaurante->delete();

        return redirect()->route('restaurantes.index')->with('success', 'Restaurante removido com sucesso.');
    }
        public function dashboard()
        {
            // Mesas ocupadas/livres
            $mesasOcupadas = \App\Models\Mesa::where('status', 'ocupada')->count();
            $mesasLivres = \App\Models\Mesa::where('status', 'livre')->count();

            // Ocupação por horário (exemplo: últimas 12 horas)
            $horarios = [];
            $ocupacaoPorHorario = [];
            $now = now();
            for ($i = 11; $i >= 0; $i--) {
                $hora = $now->copy()->subHours($i);
                $horarios[] = $hora->format('H:00');
                $ocupacaoPorHorario[] = \App\Models\Mesa::where('updated_at', '>=', $hora->copy()->startOfHour())
                    ->where('updated_at', '<', $hora->copy()->endOfHour())
                    ->where('status', 'ocupada')
                    ->count();
            }

            // Alertas de reservas e fila de espera
            $alertas = [];
            $reservasPendentes = \App\Models\Reserva::where('status', 'pendente')->count();
            if ($reservasPendentes > 0) {
                $alertas[] = "$reservasPendentes reservas pendentes";
            }
            $filaEspera = \App\Models\Reserva::where('status', 'fila_espera')->count();
            if ($filaEspera > 0) {
                $alertas[] = "$filaEspera na fila de espera";
            }

            return view('admin.restaurantes.dashboard', compact(
                'mesasOcupadas', 'mesasLivres', 'horarios', 'ocupacaoPorHorario', 'alertas'
            ));
        }
}
