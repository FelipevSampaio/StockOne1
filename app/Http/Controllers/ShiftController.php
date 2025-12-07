<?php
namespace App\Http\Controllers;

use App\Models\Shift;
use Illuminate\Http\Request;

class ShiftController extends Controller
{
    public function index() {
        $shifts = Shift::all();
        return view('admin.shifts.index', compact('shifts'));
    }
    public function create() {
        return view('admin.shifts.create');
    }
    public function store(Request $request) {
        Shift::create($request->all());
        return redirect()->route('shifts.index');
    }
}
