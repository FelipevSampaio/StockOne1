D@extends('layouts.admin')

@section('content')
    <h1>Nova Reserva</h1>
    <form action="{{ route('admin.reservas.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="mesa_id" class="form-label">Mesa</label>
            <select name="mesa_id" id="mesa_id" class="form-control" required>
                @foreach($mesas as $mesa)
                    <option value="{{ $mesa->id }}">Mesa {{ $mesa->numero }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="cliente" class="form-label">Cliente</label>
            <input type="text" name="cliente" id="cliente" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="horario" class="form-label">Horário</label>
            <input type="datetime-local" name="horario" id="horario" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-success">Salvar</button>
        <a href="{{ route('admin.reservas.index') }}" class="btn btn-secondary">Voltar</a>
    </form>
@endsection
