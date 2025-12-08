@extends('layouts.public')

@section('title', 'Contato')

@section('content')
<div class="container mx-auto px-4 py-12 max-w-lg">
    <h1 class="text-3xl font-bold mb-4">Contato</h1>
    <form method="POST" action="{{ route('public.contato.enviar') }}" class="space-y-4">
        @csrf
        <div>
            <label class="block mb-1 font-semibold">Nome</label>
            <input type="text" name="nome" class="w-full border rounded px-3 py-2" required>
        </div>
        <div>
            <label class="block mb-1 font-semibold">E-mail</label>
            <input type="email" name="email" class="w-full border rounded px-3 py-2" required>
        </div>
        <div>
            <label class="block mb-1 font-semibold">Mensagem</label>
            <textarea name="mensagem" class="w-full border rounded px-3 py-2" rows="4" required></textarea>
        </div>
        <div>
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 transition">Enviar</button>
        </div>
    </form>
    @if(session('success'))
        <div class="mt-4 p-2 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
    @endif
</div>
@endsection
