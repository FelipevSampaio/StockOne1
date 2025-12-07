<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MesaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $mesas = \App\Models\Mesa::all();
        return view('admin.mesas.index', compact('mesas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.mesas.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'numero' => 'required|integer|unique:mesas,numero',
            'status' => 'in:livre,ocupada,reservada,limpeza',
        ]);
        $mesa = \App\Models\Mesa::create($data);
        return response()->json($mesa, 201);
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
        $mesa = \App\Models\Mesa::findOrFail($id);
        return view('admin.mesas.edit', compact('mesa'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $mesa = \App\Models\Mesa::findOrFail($id);
        $data = $request->validate([
            'status' => 'required|in:livre,ocupada,reservada,limpeza',
        ]);
        $mesa->update($data);
        return response()->json($mesa);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
