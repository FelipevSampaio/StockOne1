@extends('layouts.admin')

@section('content')
    <h1>Nova Mesa</h1>
    <form action="{{ route('admin.mesas.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="numero" class="form-label">Número da Mesa</label>
            <input type="number" name="numero" id="numero" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select name="status" id="status" class="form-control">
                <option value="livre">Livre</option>
                <option value="ocupada">Ocupada</option>
                <option value="reservada">Reservada</option>
                <option value="limpeza">Limpeza</option>
            </select>
        </div>
        <button type="submit" class="btn btn-success">Salvar</button>
        <a href="{{ route('admin.mesas.index') }}" class="btn btn-secondary">Voltar</a>
    </form>
@endsection
