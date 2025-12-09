@extends('layouts.app')

@section('title', 'Editar item de cardápio')
@section('subtitle', $item->nome)

@section('content')
    <div class="space-y-6">
        <!-- Estatísticas do Item -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-gradient-to-br from-red-50 to-red-100 dark:from-red-900/20 dark:to-red-800/20 rounded-xl border border-red-200 dark:border-red-800/50 p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-red-600 dark:text-red-400 uppercase tracking-wide">Total Vendido</p>
                        <p class="text-2xl font-bold text-red-700 dark:text-red-300 mt-1">{{ isset($stats) ? ($stats['total_vendido'] ?? 0) : 0 }}</p>
                        <p class="text-xs text-red-600/70 dark:text-red-400/70 mt-1">unidades</p>
                    </div>
                    <div class="w-10 h-10 bg-red-200 dark:bg-red-900/50 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-800/20 rounded-xl border border-green-200 dark:border-green-800/50 p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-green-600 dark:text-green-400 uppercase tracking-wide">Receita Total</p>
                        <p class="text-2xl font-bold text-green-700 dark:text-green-300 mt-1">R$ {{ number_format(isset($stats) ? ($stats['receita_total'] ?? 0) : 0, 2, ',', '.') }}</p>
                        <p class="text-xs text-green-600/70 dark:text-green-400/70 mt-1">gerada</p>
                    </div>
                    <div class="w-10 h-10 bg-green-200 dark:bg-green-900/50 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 rounded-xl border border-blue-200 dark:border-blue-800/50 p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-blue-600 dark:text-blue-400 uppercase tracking-wide">Pedidos</p>
                        <p class="text-2xl font-bold text-blue-700 dark:text-blue-300 mt-1">{{ isset($stats) ? ($stats['pedidos_count'] ?? 0) : 0 }}</p>
                        <p class="text-xs text-blue-600/70 dark:text-blue-400/70 mt-1">com este item</p>
                    </div>
                    <div class="w-10 h-10 bg-blue-200 dark:bg-blue-900/50 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900/20 dark:to-purple-800/20 rounded-xl border border-purple-200 dark:border-purple-800/50 p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-purple-600 dark:text-purple-400 uppercase tracking-wide">Status</p>
                        <p class="text-lg font-bold text-purple-700 dark:text-purple-300 mt-1">
                            @if($item->ativo_online)
                                <span class="inline-flex items-center gap-1">
                                    <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                                    Online
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1">
                                    <span class="w-2 h-2 bg-gray-400 rounded-full"></span>
                                    Offline
                                </span>
                            @endif
                        </p>
                        <p class="text-xs text-purple-600/70 dark:text-purple-400/70 mt-1">
                            @if($item->ativo_online)
                                Disponível
                            @else
                                Indisponível
                            @endif
                        </p>
                    </div>
                    <div class="w-10 h-10 bg-purple-200 dark:bg-purple-900/50 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Formulário -->
        <form action="{{ route('cardapio-itens.update', $item) }}" method="POST" enctype="multipart/form-data" 
              class="space-y-6 rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800/50 dark:backdrop-blur-sm p-8 shadow-sm"
              x-data="{ 
                  imagePreview: '{{ $item->imagem ? asset('storage/' . $item->imagem) : '' }}',
                  init() {
                      // Listener para atualizar preview quando imagem for carregada via drag and drop
                      window.addEventListener('image-uploaded', (e) => {
                          this.imagePreview = e.detail.imageData;
                      });
                  },
                  validateForm(event) {
                      // Verificar se há arquivo no input antes de submeter
                      const fileInput = document.getElementById('imagem-input');
                      if (fileInput) {
                          if (fileInput.files && fileInput.files.length > 0) {
                              const file = fileInput.files[0];
                              console.log('✅ Novo arquivo presente no formulário:', file.name, file.size, 'bytes', file.type);
                              console.log('✅ Formulário será enviado com novo arquivo');
                          } else {
                              console.log('ℹ️ Nenhum arquivo novo selecionado (mantendo imagem atual se existir)');
                          }
                      } else {
                          console.error('❌ Input file não encontrado!');
                      }
                  }
              }"
              @submit="validateForm($event)">
            @csrf
            @method('PUT')

            @include('cardapio_itens._form')

            <div class="flex items-center justify-between gap-3 pt-6 border-t border-gray-200 dark:border-gray-700">
                <a href="{{ route('cardapio-itens.index') }}" 
                   class="inline-flex items-center gap-2 text-sm font-medium text-gray-600 dark:text-gray-400 hover:text-red-600 dark:hover:text-red-400 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Voltar para lista
                </a>

                <div class="flex items-center gap-3">
                    <button
                        type="submit"
                        form="delete-item-cardapio"
                        class="inline-flex items-center gap-2 rounded-full border border-red-200 dark:border-red-800 px-5 py-2.5 text-sm font-semibold text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors"
                        data-confirm="Confirma excluir este item? Esta ação não pode ser desfeita."
                        data-confirm-trigger="true"
                        data-confirm-target="delete-item-cardapio"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Excluir
                    </button>
                    <button type="submit" 
                            class="inline-flex items-center gap-2 rounded-full bg-red-600 px-6 py-2.5 text-sm font-semibold text-white shadow hover:bg-red-500 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Salvar alterações
                    </button>
                </div>
            </div>
        </form>

        <form id="delete-item-cardapio" action="{{ route('cardapio-itens.destroy', $item) }}" method="POST" class="hidden">
            @csrf
            @method('DELETE')
        </form>
    </div>
@endsection

