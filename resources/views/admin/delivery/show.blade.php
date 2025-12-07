@extends('layouts.admin')
@section('content')
<h1>Detalhes do Pedido de Delivery #{{ $order->id }}</h1>
<ul>
    <li><strong>Pedido:</strong> {{ $order->pedido_id }}</li>
    <li><strong>Status:</strong> {{ \App\Models\DeliveryOrder::statusList()[$order->status] ?? $order->status }}</li>
    <li><strong>Horário Entrega:</strong> {{ $order->horario_entrega }}</li>
    <li><strong>Rastreamento:</strong> {{ $order->rota }}</li>
    <li><strong>Tempo Preparo:</strong> {{ $order->tempo_preparo }} min</li>
    <li><strong>Tempo Despacho:</strong> {{ $order->tempo_despacho }} min</li>
</ul>
<form method="POST" action="{{ route('admin.delivery.update', $order->id) }}">
    @csrf
    @method('PATCH')
    <div class="mb-3">
        <label for="status" class="form-label">Status</label>
        <select name="status" id="status" class="form-control">
            @foreach(\App\Models\DeliveryOrder::statusList() as $key => $label)
                <option value="{{ $key }}" @if($order->status == $key) selected @endif>{{ $label }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label for="rota" class="form-label">Rastreamento</label>
        <input type="text" name="rota" id="rota" value="{{ $order->rota }}" class="form-control">
    </div>
    <div class="mb-3">
        <label for="horario_entrega" class="form-label">Horário Entrega</label>
        <input type="datetime-local" name="horario_entrega" id="horario_entrega" value="{{ $order->horario_entrega }}" class="form-control">
    </div>
    <button type="submit" class="btn btn-success">Atualizar</button>
</form>
<h2 class="mt-4">Histórico de Status</h2>
@php
    $logs = \App\Models\DeliveryOrderStatusLog::where('delivery_order_id', $order->id)->orderByDesc('changed_at')->get();
@endphp
<table class="table">
    <thead>
        <tr>
            <th>Data</th>
            <th>Status Anterior</th>
            <th>Status Novo</th>
        </tr>
    </thead>
    <tbody>
        @foreach($logs as $log)
        <tr>
            <td>{{ $log->changed_at }}</td>
            <td>{{ \App\Models\DeliveryOrder::statusList()[$log->old_status] ?? $log->old_status }}</td>
            <td>{{ \App\Models\DeliveryOrder::statusList()[$log->new_status] ?? $log->new_status }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
<a href="{{ route('admin.delivery.index') }}" class="btn btn-secondary mt-3">Voltar</a>
@endsection
