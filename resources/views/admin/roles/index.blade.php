@extends('layouts.admin')
@section('content')
<h1>Funções</h1>
<a href="{{ route('roles.create') }}" class="btn btn-primary">Nova Função</a>
<table class="table mt-3">
    <thead>
        <tr>
            <th>Nome</th>
        </tr>
    </thead>
    <tbody>
        @foreach($roles as $role)
        <tr>
            <td>{{ $role->name }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
