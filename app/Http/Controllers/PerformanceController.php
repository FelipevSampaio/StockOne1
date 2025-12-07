<?php
namespace App\Http\Controllers;

use App\Models\Performance;
use Illuminate\Http\Request;

class PerformanceController extends Controller
{
    public function index() {
        $performances = Performance::with(['user', 'shift', 'role'])->get();
        return view('admin.performances.index', compact('performances'));
    }
    public function create() {
        return view('admin.performances.create');
    }
    public function store(Request $request) {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'shift_id' => 'required|exists:shifts,id',
            'role_id' => 'required|exists:roles,id',
            'date' => 'required|date',
            'tasks_completed' => 'required|integer|min:0',
            'notes' => 'nullable|string',
        ]);

        // Exemplo de cálculo simples de produtividade
        // Pode ser expandido conforme regras do negócio
        $productivity = $validated['tasks_completed'];

        $performance = Performance::create(array_merge($validated, [
            'productivity' => $productivity
        ]));

        return redirect()->route('performances.index')->with('success', 'Registro salvo com sucesso!');
    }
}
