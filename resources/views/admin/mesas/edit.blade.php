@extends('layouts.admin')

@section('content')
    <h1>Editar Mesa</h1>
    <form action="{{ route('admin.mesas.update', $mesa) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="numero" class="form-label">Número da Mesa</label>
            <input type="number" name="numero" id="numero" class="form-control" value="{{ $mesa->numero }}" required disabled>
        </div>
        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select name="status" id="status" class="form-control">
                <option value="livre" {{ $mesa->status == 'livre' ? 'selected' : '' }}>Livre</option>
                <option value="ocupada" {{ $mesa->status == 'ocupada' ? 'selected' : '' }}>Ocupada</option>
                <option value="reservada" {{ $mesa->status == 'reservada' ? 'selected' : '' }}>Reservada</option>
                <option value="limpeza" {{ $mesa->status == 'limpeza' ? 'selected' : '' }}>Limpeza</option>
            </select>
        </div>
        <button type="submit" class="btn btn-success">Salvar</button>
        <a href="{{ route('admin.mesas.index') }}" class="btn btn-secondary">Voltar</a>
    </form>
@endsection
