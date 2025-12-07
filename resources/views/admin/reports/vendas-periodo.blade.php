@extends('admin.layouts.app')

@section('content')
<div class="container">
    <h2>Relatório de Vendas por Período</h2>
    <form method="GET" action="{{ route('admin.reports.vendas-periodo') }}" class="mb-4">
        <div class="row">
            <div class="col-md-4">
                <label for="start_date">Data inicial:</label>
                <input type="date" name="start_date" id="start_date" class="form-control" value="{{ request('start_date') }}">
            </div>
            <div class="col-md-4">
                <label for="end_date">Data final:</label>
                <input type="date" name="end_date" id="end_date" class="form-control" value="{{ request('end_date') }}">
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <button type="submit" class="btn btn-primary">Filtrar</button>
            </div>
        </div>
    </form>

    <h4>Total de vendas: R$ {{ number_format($totalVendas, 2, ',', '.') }}</h4>

    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>ID Pedido</th>
                <th>Data</th>
                <th>Funcionário</th>
                <th>Valor Total</th>
                <th>Itens</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pedidos as $pedido)
                <tr>
                    <td>{{ $pedido->id }}</td>
                    <td>{{ $pedido->created_at->format('d/m/Y H:i') }}</td>
                    <td>{{ $pedido->usuario->name ?? '-' }}</td>
                    <td>R$ {{ number_format($pedido->valor_total, 2, ',', '.') }}</td>
                    <td>
                        <ul>
                        @foreach($pedido->itens as $item)
                            <li>{{ $item->cardapioItem->nome ?? '-' }} ({{ $item->quantidade }})</li>
                        @endforeach
                        </ul>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
