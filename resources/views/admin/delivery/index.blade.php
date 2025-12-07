@extends('layouts.admin')
@section('content')
<h1>Pedidos de Delivery</h1>
<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Pedido</th>
            <th>Status</th>
            <th>Horário Entrega</th>
            <th>Rastreamento</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        @foreach($orders as $order)
        <tr>
            <td>{{ $order->id }}</td>
            <td>{{ $order->pedido_id }}</td>
            <td>{{ $order->status }}</td>
            <td>{{ $order->horario_entrega }}</td>
            <td>{{ $order->rota }}</td>
            <td>
                <a href="{{ route('admin.delivery.show', $order->id) }}" class="btn btn-primary">Detalhes</a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
