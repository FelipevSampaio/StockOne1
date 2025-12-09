@extends('layouts.app')

@section('title', 'Novo insumo')
@section('subtitle', 'Cadastre insumos por restaurante')

@section('content')
    <div class="space-y-6">
        <!-- Banner Informativo -->
        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl p-4">
            <div class="flex items-start gap-3">
                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div class="flex-1">
                    <h4 class="text-sm font-semibold text-blue-900 dark:text-blue-300 mb-1">Dicas para cadastrar insumos</h4>
                    <ul class="text-xs text-blue-800 dark:text-blue-400 space-y-1">
                        <li>• Use categorias consistentes para facilitar a organização (ex: Grãos, Laticínios, Carnes)</li>
                        <li>• Defina o ponto de reposição mínimo para receber alertas automáticos</li>
                        <li>• Informe o custo unitário para cálculo automático do valor total do estoque</li>
                    </ul>
                </div>
            </div>
        </div>

        <form action="{{ route('insumos.store') }}" 
              method="POST" 
              class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6"
              x-data="{ isSubmitting: false }"
              @submit="isSubmitting = true">
            @csrf

            @include('insumos._form', ['insumo' => new \App\Models\Insumo(), 'categorias' => $categorias ?? collect()])

            <div class="flex items-center justify-end gap-3 mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                <a href="{{ route('insumos.index') }}" 
                   class="px-5 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                    Cancelar
                </a>
                <button type="submit" 
                        :disabled="isSubmitting"
                        class="px-5 py-2.5 text-sm font-semibold text-white bg-red-600 rounded-lg shadow-sm hover:bg-red-500 transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2">
                    <svg x-show="!isSubmitting" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <svg x-show="isSubmitting" class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span x-text="isSubmitting ? 'Salvando...' : 'Salvar'"></span>
                </button>
            </div>
        </form>
    </div>
@endsection
