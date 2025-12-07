@extends('admin.layouts.app')

@section('content')
    <h1>Gestão Inteligente de Estoque</h1>
    <h2>Alertas de Baixo Estoque</h2>
    <ul>
        @forelse($alertas as $alerta)
            <li>
                <strong>{{ $alerta['mensagem'] }}</strong> ({{ $alerta['data_hora_alerta'] }})
            </li>
        @empty
            <li>Nenhum alerta de baixo estoque.</li>
        @endforelse
    </ul>

    <h2>Sugestões de Compras</h2>
    <table class="table">
        <thead>
            <tr>
                <th>Insumo</th>
                <th>Quantidade Sugerida</th>
                <th>Justificativa</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sugestoes as $sugestao)
                <tr>
                    <td>{{ \App\Models\Insumo::find($sugestao['insumo_id'])->nome }}</td>
                    <td>{{ $sugestao['quantidade_sugerida'] }}</td>
                    <td>{{ $sugestao['justificativa'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
