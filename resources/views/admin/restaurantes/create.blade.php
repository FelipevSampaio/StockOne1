@extends('layouts.admin')

@section('title', 'Criar Restaurante')
@section('page-title', 'Criar Novo Restaurante')

@section('topbar-actions')
    <a href="{{ route('admin.restaurantes.index') }}" class="btn-ghost">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Voltar
    </a>
@endsection

@section('content')
<div class="max-w-4xl mx-auto" x-data="restauranteCreateForm()">

    <!-- Progress Steps -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 mb-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center flex-1" :class="step >= 1 ? 'text-red-600 dark:text-red-400' : 'text-gray-400'">
                <div class="flex items-center justify-center w-10 h-10 rounded-full border-2 transition-all duration-300"
                     :class="step >= 1 ? 'border-red-600 dark:border-red-400 bg-red-50 dark:bg-red-900/20' : 'border-gray-300 dark:border-gray-600'">
                    <span class="text-sm font-semibold">1</span>
                </div>
                <span class="ml-3 text-sm font-medium hidden sm:inline">Informações Básicas</span>
            </div>
            <div class="flex-1 h-1 mx-4 transition-all duration-300"
                 :class="step >= 2 ? 'bg-red-600 dark:bg-red-400' : 'bg-gray-200 dark:bg-gray-700'"></div>
            <div class="flex items-center flex-1" :class="step >= 2 ? 'text-red-600 dark:text-red-400' : 'text-gray-400'">
                <div class="flex items-center justify-center w-10 h-10 rounded-full border-2 transition-all duration-300"
                     :class="step >= 2 ? 'border-red-600 dark:border-red-400 bg-red-50 dark:bg-red-900/20' : 'border-gray-300 dark:border-gray-600'">
                    <span class="text-sm font-semibold">2</span>
                </div>
                <span class="ml-3 text-sm font-medium hidden sm:inline">Contato</span>
            </div>
            <div class="flex-1 h-1 mx-4 transition-all duration-300"
                 :class="step >= 3 ? 'bg-red-600 dark:bg-red-400' : 'bg-gray-200 dark:bg-gray-700'"></div>
            <div class="flex items-center flex-1" :class="step >= 3 ? 'text-red-600 dark:text-red-400' : 'text-gray-400'">
                <div class="flex items-center justify-center w-10 h-10 rounded-full border-2 transition-all duration-300"
                     :class="step >= 3 ? 'border-red-600 dark:border-red-400 bg-red-50 dark:bg-red-900/20' : 'border-gray-300 dark:border-gray-600'">
                    <span class="text-sm font-semibold">3</span>
                </div>
                <span class="ml-3 text-sm font-medium hidden sm:inline">Confirmação</span>
            </div>
        </div>
    </div>

    <!-- Form Card -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700">
        <form method="POST" action="{{ route('admin.restaurantes.store') }}" @submit="handleSubmit">
            @csrf

            <!-- Step 1: Informações Básicas -->
            <div x-show="step === 1" x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 transform translate-x-4"
                 x-transition:enter-end="opacity-100 transform translate-x-0"
                 class="p-6 space-y-6">

                <div class="flex items-center gap-3 pb-4 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-center w-12 h-12 rounded-xl bg-red-100 dark:bg-red-900/30">
                        <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Informações Básicas</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Dados principais do restaurante</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nome -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <span class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                                Nome do Restaurante
                                <span class="text-red-500">*</span>
                            </span>
                        </label>
                        <input type="text"
                               name="nome"
                               x-model="formData.nome"
                               required
                               placeholder="Digite o nome do restaurante"
                               class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 transition-all @error('nome') border-red-500 ring-2 ring-red-500 @enderror">
                        @error('nome')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Mínimo de 3 caracteres</p>
                    </div>

                    <!-- CNPJ -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <span class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                CNPJ
                                <span class="text-red-500">*</span>
                            </span>
                        </label>
                        <input type="text"
                               name="cnpj"
                               x-model="formData.cnpj"
                               required
                               placeholder="XX.XXX.XXX/XXXX-XX"
                               x-mask="99.999.999/9999-99"
                               class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 transition-all @error('cnpj') border-red-500 ring-2 ring-red-500 @enderror">
                        @error('cnpj')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <span class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Status
                                <span class="text-red-500">*</span>
                            </span>
                        </label>
                        <select name="status"
                                x-model="formData.status"
                                required
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 transition-all @error('status') border-red-500 ring-2 ring-red-500 @enderror">
                            <option value="">Selecione o status</option>
                            <option value="ativo" {{ old('status') == 'ativo' ? 'selected' : '' }}>Ativo</option>
                            <option value="inativo" {{ old('status') == 'inativo' ? 'selected' : '' }}>Inativo</option>
                        </select>
                        @error('status')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>

                <!-- Navigation -->
                <div class="flex justify-end pt-6 border-t border-gray-200 dark:border-gray-700">
                    <button type="button"
                            @click="nextStep()"
                            class="btn-primary">
                        Próximo
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Step 2: Contato -->
            <div x-show="step === 2" x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 transform translate-x-4"
                 x-transition:enter-end="opacity-100 transform translate-x-0"
                 class="p-6 space-y-6">

                <div class="flex items-center gap-3 pb-4 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-center w-12 h-12 rounded-xl bg-blue-100 dark:bg-blue-900/30">
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Informações de Contato</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Email, telefone e endereço</p>
                    </div>
                </div>

                <div class="space-y-6">
                    <!-- Email -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <span class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                Email
                                <span class="text-red-500">*</span>
                            </span>
                        </label>
                        <input type="email"
                               name="email"
                               x-model="formData.email"
                               required
                               placeholder="restaurante@exemplo.com"
                               class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 transition-all @error('email') border-red-500 ring-2 ring-red-500 @enderror">
                        @error('email')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Telefone -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <span class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                                Telefone
                            </span>
                        </label>
                        <input type="text"
                               name="telefone"
                               x-model="formData.telefone"
                               placeholder="(XX) XXXXX-XXXX"
                               x-mask="(99) 99999-9999"
                               class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 transition-all">
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Campo opcional</p>
                    </div>

                    <!-- Endereço -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <span class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                Endereço
                            </span>
                        </label>
                        <textarea name="endereco"
                                  x-model="formData.endereco"
                                  rows="3"
                                  placeholder="Rua, número, bairro, cidade - UF"
                                  class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 transition-all resize-none"></textarea>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Campo opcional</p>
                    </div>
                </div>

                <!-- Navigation -->
                <div class="flex justify-between pt-6 border-t border-gray-200 dark:border-gray-700">
                    <button type="button"
                            @click="prevStep()"
                            class="btn-ghost">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Voltar
                    </button>
                    <button type="button"
                            @click="nextStep()"
                            class="btn-primary">
                        Próximo
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Step 3: Confirmação -->
            <div x-show="step === 3" x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 transform translate-x-4"
                 x-transition:enter-end="opacity-100 transform translate-x-0"
                 class="p-6 space-y-6">

                <div class="flex items-center gap-3 pb-4 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-center w-12 h-12 rounded-xl bg-green-100 dark:bg-green-900/30">
                        <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Confirmar Informações</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Revise os dados antes de criar</p>
                    </div>
                </div>

                <!-- Resumo -->
                <div class="bg-gray-50 dark:bg-gray-900/50 rounded-lg p-6 space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Nome</p>
                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100" x-text="formData.nome || '-'"></p>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">CNPJ</p>
                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100" x-text="formData.cnpj || '-'"></p>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Email</p>
                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100" x-text="formData.email || '-'"></p>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Telefone</p>
                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100" x-text="formData.telefone || '-'"></p>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Status</p>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                  :class="formData.status === 'ativo' ? 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-400' : 'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300'"
                                  x-text="formData.status === 'ativo' ? 'Ativo' : 'Inativo'"></span>
                        </div>
                        <div class="md:col-span-2">
                            <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Endereço</p>
                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100" x-text="formData.endereco || '-'"></p>
                        </div>
                    </div>
                </div>

                <!-- Navigation -->
                <div class="flex justify-between pt-6 border-t border-gray-200 dark:border-gray-700">
                    <button type="button"
                            @click="prevStep()"
                            class="btn-ghost">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Voltar
                    </button>
                    <button type="submit"
                            class="btn-primary">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Criar Restaurante
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/@alpinejs/mask@3.x.x/dist/cdn.min.js"></script>
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('restauranteCreateForm', () => ({
            step: 1,
            formData: {
                nome: '{{ old('nome') }}',
                cnpj: '{{ old('cnpj') }}',
                email: '{{ old('email') }}',
                telefone: '{{ old('telefone') }}',
                endereco: '{{ old('endereco') }}',
                status: '{{ old('status', 'ativo') }}'
            },

            nextStep() {
                if (this.validateCurrentStep()) {
                    if (this.step < 3) {
                        this.step++;
                    }
                }
            },

            prevStep() {
                if (this.step > 1) {
                    this.step--;
                }
            },

            validateCurrentStep() {
                if (this.step === 1) {
                    if (!this.formData.nome || this.formData.nome.length < 3) {
                        this.showToast('O nome do restaurante deve ter no mínimo 3 caracteres', 'error');
                        return false;
                    }
                    if (!this.formData.cnpj) {
                        this.showToast('O CNPJ é obrigatório', 'error');
                        return false;
                    }
                    if (!this.formData.status) {
                        this.showToast('Selecione o status do restaurante', 'error');
                        return false;
                    }
                }
                if (this.step === 2) {
                    if (!this.formData.email) {
                        this.showToast('O email é obrigatório', 'error');
                        return false;
                    }
                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    if (!emailRegex.test(this.formData.email)) {
                        this.showToast('Digite um email válido', 'error');
                        return false;
                    }
                }
                return true;
            },

            handleSubmit(e) {
                if (!this.validateCurrentStep()) {
                    e.preventDefault();
                    return false;
                }
            },

            showToast(message, type = 'error') {
                const toast = document.createElement('div');
                toast.className = `fixed top-4 right-4 z-50 px-6 py-4 rounded-lg shadow-lg transition-all duration-300 ${
                    type === 'error'
                        ? 'bg-red-600 text-white'
                        : 'bg-green-600 text-white'
                }`;
                toast.innerHTML = `
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            ${type === 'error'
                                ? '<path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>'
                                : '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>'
                            }
                        </svg>
                        <p class="font-medium">${message}</p>
                    </div>
                `;
                document.body.appendChild(toast);
                setTimeout(() => {
                    toast.classList.add('opacity-0', 'translate-x-full');
                    setTimeout(() => toast.remove(), 300);
                }, 3000);
            }
        }));
    });
</script>
@endsection
