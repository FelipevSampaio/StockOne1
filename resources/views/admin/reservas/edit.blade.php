@extends('layouts.admin')

@section('content')
    <h1>Editar Reserva</h1>
    <form action="{{ route('admin.reservas.update', $reserva) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="mesa_id" class="form-label">Mesa</label>
            <select name="mesa_id" id="mesa_id" class="form-control" required disabled>
                <option value="{{ $reserva->mesa->id }}">Mesa {{ $reserva->mesa->numero }}</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="cliente" class="form-label">Cliente</label>
            <input type="text" name="cliente" id="cliente" class="form-control" value="{{ $reserva->cliente }}" required>
        </div>
        <div class="mb-3">
            <label for="horario" class="form-label">Horário</label>
            <input type="datetime-local" name="horario" id="horario" class="form-control" value="{{ date('Y-m-d\TH:i', strtotime($reserva->horario)) }}" required>
        </div>
        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select name="status" id="status" class="form-control">
                <option value="pendente" {{ $reserva->status == 'pendente' ? 'selected' : '' }}>Pendente</option>
                <option value="confirmada" {{ $reserva->status == 'confirmada' ? 'selected' : '' }}>Confirmada</option>
                <option value="cancelada" {{ $reserva->status == 'cancelada' ? 'selected' : '' }}>Cancelada</option>
            </select>
        </div>
        <button type="submit" class="btn btn-success">Salvar</button>
        <a href="{{ route('admin.reservas.index') }}" class="btn btn-secondary">Voltar</a>
    </form>
@endsection
