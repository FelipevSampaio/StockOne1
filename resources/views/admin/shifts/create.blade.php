@extends('layouts.admin')
@section('content')
<h1>Cadastrar Turno</h1>
<form method="POST" action="{{ route('shifts.store') }}">
    @csrf
    <div class="mb-3">
        <label for="name" class="form-label">Nome</label>
        <input type="text" name="name" id="name" class="form-control" required>
    </div>
    <div class="mb-3">
        <label for="start_time" class="form-label">Início</label>
        <input type="time" name="start_time" id="start_time" class="form-control" required>
    </div>
    <div class="mb-3">
        <label for="end_time" class="form-label">Fim</label>
        <input type="time" name="end_time" id="end_time" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-success">Salvar</button>
</form>
@endsection
