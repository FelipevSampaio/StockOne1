@extends('admin.layouts.app')

@section('content')
<div class="container">
    <h1>Itens do Cardápio</h1>
    <a href="{{ route('admin.cardapio.create') }}" class="btn btn-primary mb-3">Novo Item</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nome</th>
                <th>Preço</th>
                <th>Disponível</th>
                <th>Promoção</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach($itens as $item)
            <tr>
                <td>{{ $item->nome }}</td>
                <td>R$ {{ number_format($item->preco_venda, 2, ',', '.') }}</td>
                <td>{{ $item->disponibilidade ? 'Sim' : 'Não' }}</td>
                <td>
                    @if($item->promocao)
                        {{ $item->promocao['descricao'] ?? 'Promoção ativa' }}
                    @else
                        -
                    @endif
                </td>
                <td>
                    <a href="{{ route('admin.cardapio.edit', $item->id) }}" class="btn btn-sm btn-warning">Editar</a>
                    <form action="{{ route('admin.cardapio.destroy', $item->id) }}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza?')">Excluir</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
