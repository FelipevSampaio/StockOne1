@extends('layouts.admin')

@section('content')
    <h1>Mesas</h1>
    <a href="{{ route('admin.mesas.create') }}" class="btn btn-primary mb-3">Nova Mesa</a>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Número</th>
                <th>Status</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach($mesas as $mesa)
                <tr>
                    <td>{{ $mesa->id }}</td>
                    <td>{{ $mesa->numero }}</td>
                    <td>{{ ucfirst($mesa->status) }}</td>
                    <td>
                        <a href="{{ route('admin.mesas.edit', $mesa) }}" class="btn btn-sm btn-warning">Editar</a>
                        <form action="{{ route('admin.mesas.destroy', $mesa) }}" method="POST" style="display:inline-block;">
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
