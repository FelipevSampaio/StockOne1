@extends('layouts.app')

@section('title', 'Restaurantes')
@section('subtitle', 'Gerencie unidades e dados cadastrais')

@section('actions')
    <a href="{{ route('restaurantes.create') }}" class="rounded-full bg-red-600 px-5 py-2 text-sm font-semibold text-white shadow hover:bg-red-500">
        Novo restaurante
    </a>
@endsection

@section('content')
    <div class="max-w-7xl mx-auto py-10">
        <!-- Listagem de restaurantes será refeita do zero -->
    </div>
@endsection

