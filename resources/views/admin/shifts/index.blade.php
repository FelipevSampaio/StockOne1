@extends('layouts.admin')
@section('content')
<h1>Turnos</h1>
<a href="{{ route('shifts.create') }}" class="btn btn-primary">Novo Turno</a>
<table class="table mt-3">
    <thead>
        <tr>
            <th>Nome</th>
            <th>Início</th>
            <th>Fim</th>
        </tr>
    </thead>
    <tbody>
        @foreach($shifts as $shift)
        <tr>
            <td>{{ $shift->name }}</td>
            <td>{{ $shift->start_time }}</td>
            <td>{{ $shift->end_time }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
