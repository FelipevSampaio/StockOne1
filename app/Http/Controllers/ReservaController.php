<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReservaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reservas = \App\Models\Reserva::with('mesa')->get();
        return view('admin.reservas.index', compact('reservas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $mesas = \App\Models\Mesa::all();
        return view('admin.reservas.create', compact('mesas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'mesa_id' => 'required|exists:mesas,id',
            'cliente' => 'required|string',
            'horario' => 'required|date_format:Y-m-d H:i:s',
        ]);
        $data['status'] = 'pendente';
        $data['confirmacao_automatica'] = false;
        // Confirmação automática se a mesa estiver livre
        $mesa = \App\Models\Mesa::find($data['mesa_id']);
        if ($mesa && $mesa->status === 'livre') {
            $data['status'] = 'confirmada';
            $data['confirmacao_automatica'] = true;
            $mesa->update(['status' => 'reservada']);
        }
        $reserva = \App\Models\Reserva::create($data);
        return response()->json($reserva, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $reserva = \App\Models\Reserva::with('mesa')->findOrFail($id);
        return view('admin.reservas.edit', compact('reserva'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
