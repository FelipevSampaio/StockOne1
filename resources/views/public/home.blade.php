@extends('layouts.public')

@section('title', 'Bem-vindo ao StockOne')

@section('content')
<div class="container mx-auto px-4 py-12">
    <div class="text-center">
        <h1 class="text-4xl font-bold mb-4">Bem-vindo ao StockOne</h1>
        <p class="text-lg mb-6">Sistema de gestão de estoque e operações para restaurantes.</p>
        <a href="{{ url('/sobre') }}" class="inline-block bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 transition">Saiba mais</a>
    </div>
</div>
@endsection
