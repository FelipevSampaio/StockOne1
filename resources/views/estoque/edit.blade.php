@extends('layouts.app')

@section('title', 'Editar estoque')
@section('subtitle', $estoque->insumo?->nome ?? 'Insumo')

@section('content')
    <div class="space-y-6">
        <!-- Estatísticas do Estoque -->
        @if(isset($stats))
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Valor Total</p>
                        <p class="text-2xl font-bold text-green-600 dark:text-green-400 mt-2">
                            R$ {{ number_format($stats['valor_total'], 2, ',', '.') }}
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Percentual do Mínimo</p>
                        <p class="text-2xl font-bold {{ $stats['percentual_minimo'] <= 100 ? 'text-red-600 dark:text-red-400' : 'text-green-600 dark:text-green-400' }} mt-2">
                            {{ number_format($stats['percentual_minimo'], 0) }}%
                        </p>
                        @if($estoque->insumo && $estoque->insumo->ponto_reposicao_minimo)
                            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2 mt-2">
                                <div class="h-2 rounded-full {{ $stats['percentual_minimo'] <= 100 ? 'bg-red-500' : 'bg-green-500' }}" 
                                     style="width: {{ min(100, $stats['percentual_minimo']) }}%"></div>
                            </div>
                        @endif
                    </div>
                    <div class="w-12 h-12 bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <form id="form-update-estoque"
              action="{{ route('estoque.update', $estoque) }}" 
              method="POST" 
              class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6"
              x-data="{ isSubmitting: false }"
              @submit="isSubmitting = true">
            @csrf
            @method('PUT')

            @include('estoque._form', ['estoque' => $estoque, 'insumos' => $insumos, 'localizacoes' => $localizacoes ?? collect()])

            <div class="flex items-center justify-between gap-3 mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                <a href="{{ route('estoque.index') }}" 
                   class="text-sm font-medium text-gray-600 dark:text-gray-400 hover:text-red-600 dark:hover:text-red-400 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Voltar
                </a>

                <div class="flex items-center gap-3">
                    <button type="button"
                            id="btn-delete-estoque"
                            onclick="confirmDelete()"
                            class="px-5 py-2.5 text-sm font-medium text-red-600 dark:text-red-400 bg-white dark:bg-gray-700 border border-red-200 dark:border-red-800 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                        Excluir
                    </button>
                    <button type="submit" 
                            id="btn-update-estoque"
                            form="form-update-estoque"
                            :disabled="isSubmitting"
                            class="px-5 py-2.5 text-sm font-semibold text-white bg-red-600 rounded-lg shadow-sm hover:bg-red-500 transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2">
                        <svg x-show="!isSubmitting" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <svg x-show="isSubmitting" class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span x-text="isSubmitting ? 'Salvando...' : 'Atualizar'"></span>
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const insumoSelect = document.getElementById('insumo_id');
        const unidadeDisplay = document.getElementById('unidade-display');

        if (insumoSelect && unidadeDisplay) {
            function updateUnidade() {
                const selectedOption = insumoSelect.options[insumoSelect.selectedIndex];
                if (selectedOption && selectedOption.value) {
                    const unidade = selectedOption.getAttribute('data-unidade');
                    if (unidade) {
                        unidadeDisplay.textContent = unidade;
                    } else {
                        unidadeDisplay.textContent = '';
                    }
                } else {
                    unidadeDisplay.textContent = '';
                }
            }

            updateUnidade();
            insumoSelect.addEventListener('change', updateUnidade);
        }

        // Garantir que o formulário de update não seja afetado pelo formulário de delete
        const formUpdate = document.getElementById('form-update-estoque');
        if (formUpdate) {
            formUpdate.addEventListener('submit', function(e) {
                // Garantir que este é o formulário correto
                if (e.target.id !== 'form-update-estoque') {
                    e.preventDefault();
                    return false;
                }
            });
        }
    });

    function confirmDelete() {
        if (confirm('⚠️ Atenção: Esta ação é permanente e não pode ser desfeita. O registro de estoque será removido completamente. Deseja realmente excluir?')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route('estoque.destroy', $estoque) }}';
            
            const csrf = document.createElement('input');
            csrf.type = 'hidden';
            csrf.name = '_token';
            csrf.value = '{{ csrf_token() }}';
            form.appendChild(csrf);
            
            const method = document.createElement('input');
            method.type = 'hidden';
            method.name = '_method';
            method.value = 'DELETE';
            form.appendChild(method);
            
            document.body.appendChild(form);
            form.submit();
        }
    }
</script>
@endsection
