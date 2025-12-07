@extends('layouts.admin')
@section('content')
<h1>Cadastrar Desempenho</h1>
<form method="POST" action="{{ route('performances.store') }}">
    @csrf
    <div class="mb-3">
        <label for="user_id" class="form-label">Usuário</label>
        <select name="user_id" id="user_id" class="form-control" required>
            @foreach(App\Models\User::all() as $user)
                <option value="{{ $user->id }}">{{ $user->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label for="shift_id" class="form-label">Turno</label>
        <select name="shift_id" id="shift_id" class="form-control" required>
            @foreach(App\Models\Shift::all() as $shift)
                <option value="{{ $shift->id }}">{{ $shift->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label for="role_id" class="form-label">Função</label>
        <select name="role_id" id="role_id" class="form-control" required>
            @foreach(App\Models\Role::all() as $role)
                <option value="{{ $role->id }}">{{ $role->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label for="date" class="form-label">Data</label>
        <input type="date" name="date" id="date" class="form-control" required>
    </div>
    <div class="mb-3">
        <label for="tasks_completed" class="form-label">Tarefas Concluídas</label>
        <input type="number" name="tasks_completed" id="tasks_completed" class="form-control" required>
    </div>
    <div class="mb-3">
        <label for="notes" class="form-label">Observações</label>
        <textarea name="notes" id="notes" class="form-control"></textarea>
    </div>
    <button type="submit" class="btn btn-success">Salvar</button>
</form>
@endsection
