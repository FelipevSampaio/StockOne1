@extends('layouts.admin')
@section('content')
<h1>Cadastrar Função</h1>
<form method="POST" action="{{ route('roles.store') }}">
    @csrf
    <div class="mb-3">
        <label for="name" class="form-label">Nome</label>
        <input type="text" name="name" id="name" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-success">Salvar</button>
</form>
@endsection
