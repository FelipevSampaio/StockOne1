@extends('layouts.admin')
@section('content')
<h1>Desempenho</h1>
<a href="{{ route('performances.create') }}" class="btn btn-primary">Novo Registro</a>
<table class="table mt-3">
    <thead>
        <tr>
            <th>Usuário</th>
            <th>Turno</th>
            <th>Função</th>
            <th>Data</th>
            <th>Tarefas Concluídas</th>
            <th>Observações</th>
        </tr>
    </thead>
    <tbody>
        @foreach($performances as $performance)
        <tr>
            <td>{{ $performance->user->name }}</td>
            <td>{{ $performance->shift->name }}</td>
            <td>{{ $performance->role->name }}</td>
            <td>{{ $performance->date }}</td>
            <td>{{ $performance->tasks_completed }}</td>
            <td>{{ $performance->notes }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
