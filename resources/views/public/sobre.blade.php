@extends('layouts.public')

@section('title', 'Sobre o StockOne')

@section('content')
<div class="container mx-auto px-4 py-12 max-w-2xl">
    <h1 class="text-3xl font-bold mb-4">Sobre o StockOne</h1>
    <p class="mb-4">O StockOne é um sistema completo para gestão de estoque, operações e processos de restaurantes. Nosso objetivo é facilitar o controle de insumos, pedidos, produção e desempenho, tornando a administração mais eficiente e inteligente.</p>
    <ul class="list-disc pl-6 mb-4">
        <li>Controle de estoque em tempo real</li>
        <li>Gestão de pedidos e produção</li>
        <li>Relatórios detalhados</li>
        <li>Interface amigável e responsiva</li>
    </ul>
    <a href="{{ url('/') }}" class="inline-block bg-gray-200 px-4 py-2 rounded hover:bg-gray-300 transition">Voltar para a Home</a>
</div>
@endsection
