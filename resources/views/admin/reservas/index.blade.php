@extends('layouts.admin')

@section('content')
    <h1>Reservas</h1>
    <a href="{{ route('admin.reservas.create') }}" class="btn btn-primary mb-3">Nova Reserva</a>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Mesa</th>
                <th>Cliente</th>
                <th>Horário</th>
                <th>Status</th>
                <th>Confirmação Automática</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reservas as $reserva)
                <tr>
                    <td>{{ $reserva->id }}</td>
                    <td>{{ $reserva->mesa->numero ?? '-' }}</td>
                    <td>{{ $reserva->cliente }}</td>
                    <td>{{ $reserva->horario }}</td>
                    <td>{{ ucfirst($reserva->status) }}</td>
                    <td>{{ $reserva->confirmacao_automatica ? 'Sim' : 'Não' }}</td>
                    <td>
                        <a href="{{ route('admin.reservas.edit', $reserva) }}" class="btn btn-sm btn-warning">Editar</a>
                        <form action="{{ route('admin.reservas.destroy', $reserva) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Excluir</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
