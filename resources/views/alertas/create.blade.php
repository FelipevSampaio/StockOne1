@extends('layouts.app')

@section('title', 'Novo alerta')
@section('subtitle', 'Notifique o time sobre riscos e situações críticas')

@section('content')
    <div class="max-w-4xl mx-auto" x-data="alertaForm()">
        <form action="{{ route('alertas.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 md:p-8">
                <div class="mb-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Informações do Alerta</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Preencha os dados para criar um novo alerta</p>
                </div>

                <div class="grid gap-6 md:grid-cols-2">
                    <!-- Insumo -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            Insumo <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                            <select name="insumo_id" 
                                    required
                                    x-model="selectedInsumo"
                                    @change="updateMensagemSugerida()"
                                    class="pl-10 w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 px-4 py-3 text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors">
                                <option value="">Selecione um insumo...</option>
                                @foreach ($insumos as $id => $nome)
                                    <option value="{{ $id }}" @selected(old('insumo_id') == $id)>{{ $nome }}</option>
                                @endforeach
                            </select>
                        </div>
                        @error('insumo_id')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tipo de Alerta -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            Tipo de Alerta <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                            </svg>
                            <select name="tipo_alerta" 
                                    required
                                    x-model="tipoAlerta"
                                    @change="updateMensagemSugerida()"
                                    class="pl-10 w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 px-4 py-3 text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors">
                                <option value="">Selecione o tipo...</option>
                                <option value="estoque_baixo" @selected(old('tipo_alerta') === 'estoque_baixo')>Estoque Baixo</option>
                                <option value="validade_proxima" @selected(old('tipo_alerta') === 'validade_proxima')>Validade Próxima</option>
                                <option value="validade_vencida" @selected(old('tipo_alerta') === 'validade_vencida')>Validade Vencida</option>
                                <option value="necessidade_compra" @selected(old('tipo_alerta') === 'necessidade_compra')>Necessidade de Compra</option>
                                <option value="problema_qualidade" @selected(old('tipo_alerta') === 'problema_qualidade')>Problema de Qualidade</option>
                                <option value="outro" @selected(old('tipo_alerta') === 'outro')>Outro</option>
                            </select>
                        </div>
                        @error('tipo_alerta')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                        
                        <!-- Badge de tipo selecionado -->
                        <div x-show="tipoAlerta" x-transition class="mt-2">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium"
                                  :class="{
                                      'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400': tipoAlerta === 'estoque_baixo' || tipoAlerta === 'validade_vencida',
                                      'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400': tipoAlerta === 'validade_proxima',
                                      'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400': tipoAlerta === 'necessidade_compra',
                                      'bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-400': tipoAlerta === 'problema_qualidade',
                                      'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300': tipoAlerta === 'outro'
                                  }">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                </svg>
                                <span x-text="getTipoLabel(tipoAlerta)"></span>
                            </span>
                        </div>
                    </div>

                    <!-- Data e Hora -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            Data e Hora do Alerta
                        </label>
                        <div class="relative">
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <input type="datetime-local" 
                                   name="data_hora_alerta" 
                                   x-model="dataHora"
                                   value="{{ old('data_hora_alerta', now()->format('Y-m-d\TH:i')) }}" 
                                   class="pl-10 w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 px-4 py-3 text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors">
                        </div>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Deixe em branco para usar a data/hora atual</p>
                        @error('data_hora_alerta')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Mensagem -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            Mensagem <span class="text-red-500">*</span>
                            <span class="text-xs font-normal text-gray-500 dark:text-gray-400 ml-2">(mínimo 10 caracteres)</span>
                        </label>
                        <textarea name="mensagem" 
                                  rows="4" 
                                  required
                                  x-model="mensagem"
                                  minlength="10"
                                  placeholder="Descreva o alerta de forma clara e objetiva..."
                                  class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 px-4 py-3 text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors resize-none"
                                  @input="updateContador()"></textarea>
                        <div class="flex items-center justify-between mt-1">
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                <span x-text="contadorCaracteres"></span> caracteres
                                <span x-show="mensagem.length < 10" class="text-red-500 ml-2">(mínimo 10)</span>
                            </p>
                            <button type="button" 
                                    @click="usarMensagemSugerida()"
                                    x-show="mensagemSugerida && mensagem.length < 10"
                                    class="text-xs text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                                Usar sugestão
                            </button>
                        </div>
                        @error('mensagem')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status Inicial -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">
                            Status Inicial
                        </label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <label class="flex items-center gap-3 p-4 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50 hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer transition-colors">
                                <input type="checkbox" 
                                       name="visualizado" 
                                       value="1" 
                                       @checked(old('visualizado', false))
                                       class="h-5 w-5 rounded border-gray-300 text-red-600 focus:ring-red-500 focus:ring-offset-0">
                                <div class="flex-1">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        <span class="font-medium text-gray-900 dark:text-white">Marcar como visualizado</span>
                                    </div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">O alerta já foi visto pelo time</p>
                                </div>
                            </label>
                            
                            <label class="flex items-center gap-3 p-4 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50 hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer transition-colors">
                                <input type="checkbox" 
                                       name="resolvido" 
                                       value="1" 
                                       @checked(old('resolvido', false))
                                       class="h-5 w-5 rounded border-gray-300 text-red-600 focus:ring-red-500 focus:ring-offset-0">
                                <div class="flex-1">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        <span class="font-medium text-gray-900 dark:text-white">Marcar como resolvido</span>
                                    </div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">O problema já foi solucionado</p>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Preview do Alerta -->
            <div x-show="mensagem && tipoAlerta && selectedInsumo" 
                 x-transition
                 class="bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-900 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center gap-2 mb-4">
                    <svg class="w-5 h-5 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Preview do Alerta</h3>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-lg border-l-4 border-red-500 p-4 shadow-sm">
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 bg-red-100 dark:bg-red-900/30 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h4 class="font-semibold text-gray-900 dark:text-white mb-1" x-text="getInsumoNome()"></h4>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-2">
                                <span x-text="getTipoLabel(tipoAlerta)"></span> • 
                                <span x-text="dataHora ? new Date(dataHora).toLocaleString('pt-BR') : 'Agora'"></span>
                            </p>
                            <p class="text-sm text-gray-700 dark:text-gray-300" x-text="mensagem"></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Ações -->
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-6 border-t border-gray-200 dark:border-gray-700">
                <a href="{{ route('alertas.index') }}" 
                   class="w-full sm:w-auto px-6 py-3 rounded-lg border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors text-sm font-medium text-center">
                    Cancelar
                </a>
                <button type="submit" 
                        :disabled="!isFormValid()"
                        class="w-full sm:w-auto px-6 py-3 rounded-lg bg-red-600 text-white font-semibold shadow-sm hover:bg-red-500 disabled:opacity-50 disabled:cursor-not-allowed transition-colors text-sm flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Criar Alerta
                </button>
            </div>
        </form>
    </div>

    <script>
        function alertaForm() {
            return {
                selectedInsumo: '{{ old('insumo_id') }}',
                tipoAlerta: '{{ old('tipo_alerta') }}',
                mensagem: '{{ old('mensagem') }}',
                dataHora: '{{ old('data_hora_alerta', now()->format('Y-m-d\TH:i')) }}',
                mensagemSugerida: '',
                contadorCaracteres: 0,
                insumos: @json($insumos),

                init() {
                    this.updateContador();
                    if (this.tipoAlerta && this.selectedInsumo) {
                        this.updateMensagemSugerida();
                    }
                },

                updateContador() {
                    this.contadorCaracteres = this.mensagem.length;
                },

                getTipoLabel(tipo) {
                    const labels = {
                        'estoque_baixo': 'Estoque Baixo',
                        'validade_proxima': 'Validade Próxima',
                        'validade_vencida': 'Validade Vencida',
                        'necessidade_compra': 'Necessidade de Compra',
                        'problema_qualidade': 'Problema de Qualidade',
                        'outro': 'Outro'
                    };
                    return labels[tipo] || tipo;
                },

                getInsumoNome() {
                    if (!this.selectedInsumo) return 'Selecione um insumo';
                    const insumo = Object.entries(this.insumos).find(([id]) => id == this.selectedInsumo);
                    return insumo ? insumo[1] : 'Insumo não encontrado';
                },

                updateMensagemSugerida() {
                    if (!this.tipoAlerta || !this.selectedInsumo) {
                        this.mensagemSugerida = '';
                        return;
                    }

                    const insumoNome = this.getInsumoNome();
                    const sugestoes = {
                        'estoque_baixo': `Atenção! O estoque de ${insumoNome} está abaixo do nível mínimo recomendado. É necessário fazer reposição urgente.`,
                        'validade_proxima': `Atenção! ${insumoNome} está próximo da data de validade. Verificar e utilizar prioritariamente.`,
                        'validade_vencida': `URGENTE! ${insumoNome} está com validade vencida. Remover do estoque imediatamente.`,
                        'necessidade_compra': `É necessário realizar a compra de ${insumoNome} para manter o estoque adequado.`,
                        'problema_qualidade': `Foi identificado um problema de qualidade com ${insumoNome}. Verificar e tomar as medidas necessárias.`,
                        'outro': `Alerta relacionado a ${insumoNome}. Verificar situação.`
                    };

                    this.mensagemSugerida = sugestoes[this.tipoAlerta] || '';
                },

                usarMensagemSugerida() {
                    if (this.mensagemSugerida) {
                        this.mensagem = this.mensagemSugerida;
                        this.updateContador();
                    }
                },

                isFormValid() {
                    return this.selectedInsumo && 
                           this.tipoAlerta && 
                           this.mensagem.length >= 10;
                }
            }
        }
    </script>
@endsection
