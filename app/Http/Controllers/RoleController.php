<?php
namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index() {
        $roles = Role::all();
        return view('admin.roles.index', compact('roles'));
    }
    public function create() {
        return view('admin.roles.create');
    }
    public function store(Request $request) {
        Role::create($request->all());
        return redirect()->route('roles.index');
    }
}
