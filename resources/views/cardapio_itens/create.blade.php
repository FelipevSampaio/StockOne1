@extends('layouts.app')

@section('title', 'Novo item de cardápio')
@section('subtitle', 'Configure produtos e pratos do restaurante')

@section('content')
    <div class="space-y-6">
        <!-- Banner Informativo -->
        <div class="rounded-xl border border-blue-200 dark:border-blue-800/50 bg-gradient-to-r from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 px-6 py-4">
            <div class="flex items-start gap-3">
                <div class="flex-shrink-0 w-10 h-10 bg-blue-200 dark:bg-blue-900/50 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="text-sm font-semibold text-blue-900 dark:text-blue-300 mb-1">Criando novo item</h3>
                    <p class="text-xs text-blue-700 dark:text-blue-400">
                        Preencha os campos abaixo para adicionar um novo item ao cardápio. Após salvar, o item poderá ser visualizado no menu público se estiver marcado como "Disponível nos canais online".
                    </p>
                </div>
            </div>
        </div>

        <!-- Formulário -->
        <form action="{{ route('cardapio-itens.store') }}" 
              method="POST" 
              enctype="multipart/form-data" 
              class="space-y-6 rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800/50 dark:backdrop-blur-sm p-8 shadow-sm"
              x-data="{ 
                  imagePreview: '',
                  isSubmitting: false,
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
                              console.log('✅ Arquivo presente no formulário:', file.name, file.size, 'bytes', file.type);
                              console.log('✅ Formulário será enviado com arquivo');
                              
                              // Verificar se o arquivo é válido
                              if (file.size === 0) {
                                  console.warn('⚠️ Arquivo tem tamanho 0, pode não ser válido');
                              }
                              
                              // Criar FormData para verificar se o arquivo pode ser serializado
                              const testFormData = new FormData();
                              testFormData.append('test', file);
                              console.log('✅ Arquivo pode ser adicionado ao FormData');
                          } else {
                              console.log('ℹ️ Nenhum arquivo no input (imagem é opcional)');
                          }
                      } else {
                          console.error('❌ Input file não encontrado!');
                      }
                      this.isSubmitting = true;
                  }
              }"
              @submit="validateForm($event)"
              x-ref="form">
            @csrf

            @include('cardapio_itens._form', ['item' => new \App\Models\CardapioItem()])

            <div class="flex items-center justify-between gap-3 pt-6 border-t border-gray-200 dark:border-gray-700">
                <a href="{{ route('cardapio-itens.index') }}" 
                   class="inline-flex items-center gap-2 text-sm font-medium text-gray-600 dark:text-gray-400 hover:text-red-600 dark:hover:text-red-400 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Voltar para lista
                </a>

                <div class="flex items-center gap-3">
                    <button type="reset" 
                            @click="imagePreview = ''"
                            class="inline-flex items-center gap-2 rounded-full border border-gray-200 dark:border-gray-700 px-5 py-2.5 text-sm font-semibold text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        Limpar formulário
                    </button>
                    <button type="submit" 
                            :disabled="isSubmitting"
                            :class="isSubmitting ? 'opacity-50 cursor-not-allowed' : ''"
                            class="inline-flex items-center gap-2 rounded-full bg-red-600 px-6 py-2.5 text-sm font-semibold text-white shadow hover:bg-red-500 transition-colors">
                        <svg x-show="!isSubmitting" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <svg x-show="isSubmitting" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span x-text="isSubmitting ? 'Salvando...' : 'Salvar item'"></span>
                    </button>
                </div>
            </div>
        </form>

        <!-- Dicas Rápidas -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50 p-4">
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-8 h-8 bg-red-100 dark:bg-red-900/30 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h4 class="text-sm font-semibold text-gray-900 dark:text-white">Preço Competitivo</h4>
                </div>
                <p class="text-xs text-gray-600 dark:text-gray-400">
                    Defina um preço que reflita o valor do produto e seja competitivo no mercado.
                </p>
            </div>

            <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50 p-4">
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-8 h-8 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h4 class="text-sm font-semibold text-gray-900 dark:text-white">Imagem de Qualidade</h4>
                </div>
                <p class="text-xs text-gray-600 dark:text-gray-400">
                    Adicione uma imagem atrativa para aumentar o apelo visual do item no cardápio.
                </p>
            </div>

            <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50 p-4">
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <h4 class="text-sm font-semibold text-gray-900 dark:text-white">Descrição Detalhada</h4>
                </div>
                <p class="text-xs text-gray-600 dark:text-gray-400">
                    Uma boa descrição ajuda os clientes a entenderem melhor o produto e aumenta as vendas.
                </p>
            </div>
        </div>
    </div>
@endsection

