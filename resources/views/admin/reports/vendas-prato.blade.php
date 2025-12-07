@extends('admin.layouts.app')

@section('content')
<div class="container">
    <h2>Relatório de Vendas por Prato</h2>
    <form method="GET" action="{{ route('admin.reports.vendas-prato') }}" class="mb-4">
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

    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>Prato</th>
                <th>Quantidade Vendida</th>
                <th>Total em Vendas</th>
            </tr>
        </thead>
        <tbody>
            @foreach($relatorio as $prato => $dados)
                <tr>
                    <td>{{ $prato }}</td>
                    <td>{{ $dados['quantidade'] }}</td>
                    <td>R$ {{ number_format($dados['total'], 2, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
