@extends('admin.layouts.app')

@section('content')
<div class="container">
    <h1>Novo Item do Cardápio</h1>
    <form action="{{ route('admin.cardapio.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="nome" class="form-label">Nome</label>
            <input type="text" name="nome" id="nome" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="preco_venda" class="form-label">Preço</label>
            <input type="number" step="0.01" name="preco_venda" id="preco_venda" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="disponibilidade" class="form-label">Disponível</label>
            <select name="disponibilidade" id="disponibilidade" class="form-control">
                <option value="1">Sim</option>
                <option value="0">Não</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="ingredientes" class="form-label">Ingredientes (separados por vírgula)</label>
            <input type="text" name="ingredientes" id="ingredientes" class="form-control">
        </div>
        <div class="mb-3">
            <label for="promocao" class="form-label">Promoção</label>
            <input type="text" name="promocao" id="promocao" class="form-control" placeholder="Ex: 10% de desconto">
        </div>
        <div class="mb-3">
            <label for="imagem" class="form-label">Imagem</label>
            <input type="file" name="imagem" id="imagem" class="form-control">
        </div>
        <button type="submit" class="btn btn-success">Salvar</button>
        <a href="{{ route('admin.cardapio.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
