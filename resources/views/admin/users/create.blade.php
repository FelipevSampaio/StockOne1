@extends('layouts.admin')

@section('title', 'Criar Usuário')
@section('page-title', 'Criar Novo Usuário')

@section('topbar-actions')
    <a href="{{ route('admin.users.index') }}" class="btn-ghost">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Voltar
    </a>
@endsection

@section('content')
<div class="max-w-4xl mx-auto" x-data="userCreateForm()">

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
                <span class="ml-3 text-sm font-medium hidden sm:inline">Segurança</span>
            </div>
            <div class="flex-1 h-1 mx-4 transition-all duration-300"
                 :class="step >= 3 ? 'bg-red-600 dark:bg-red-400' : 'bg-gray-200 dark:bg-gray-700'"></div>
            <div class="flex items-center flex-1" :class="step >= 3 ? 'text-red-600 dark:text-red-400' : 'text-gray-400'">
                <div class="flex items-center justify-center w-10 h-10 rounded-full border-2 transition-all duration-300"
                     :class="step >= 3 ? 'border-red-600 dark:border-red-400 bg-red-50 dark:bg-red-900/20' : 'border-gray-300 dark:border-gray-600'">
                    <span class="text-sm font-semibold">3</span>
                </div>
                <span class="ml-3 text-sm font-medium hidden sm:inline">Configurações</span>
            </div>
        </div>
    </div>

    <!-- Form Card -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700">
        <form method="POST" action="{{ route('admin.users.store') }}" @submit="handleSubmit">
            @csrf

            <!-- Step 1: Informações Básicas -->
            <div x-show="step === 1" x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 transform translate-x-4"
                 x-transition:enter-end="opacity-100 transform translate-x-0"
                 class="p-6 space-y-6">

                <div class="flex items-center gap-3 pb-4 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-center w-12 h-12 rounded-xl bg-red-100 dark:bg-red-900/30">
                        <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Informações Básicas</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Preencha os dados pessoais do usuário</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nome -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <span class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                Nome Completo
                                <span class="text-red-500">*</span>
                            </span>
                        </label>
                        <input type="text"
                               name="name"
                               x-model="formData.name"
                               required
                               placeholder="Digite o nome completo"
                               class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 transition-all @error('name') border-red-500 ring-2 ring-red-500 @enderror">
                        @error('name')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Mínimo de 3 caracteres</p>
                    </div>

                    <!-- Email -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <span class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                E-mail
                                <span class="text-red-500">*</span>
                            </span>
                        </label>
                        <input type="email"
                               name="email"
                               x-model="formData.email"
                               required
                               placeholder="usuario@exemplo.com"
                               class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 transition-all @error('email') border-red-500 ring-2 ring-red-500 @enderror">
                        @error('email')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Será usado para login no sistema</p>
                    </div>
                </div>
            </div>

            <!-- Step 2: Segurança -->
            <div x-show="step === 2" x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 transform translate-x-4"
                 x-transition:enter-end="opacity-100 transform translate-x-0"
                 class="p-6 space-y-6">

                <div class="flex items-center gap-3 pb-4 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-center w-12 h-12 rounded-xl bg-yellow-100 dark:bg-yellow-900/30">
                        <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Segurança</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Defina a senha de acesso do usuário</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Senha -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <span class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                                </svg>
                                Senha
                                <span class="text-red-500">*</span>
                            </span>
                        </label>
                        <div class="relative">
                            <input :type="showPassword ? 'text' : 'password'"
                                   name="password"
                                   x-model="formData.password"
                                   @input="checkPasswordStrength()"
                                   required
                                   placeholder="••••••••"
                                   class="w-full px-4 py-3 pr-12 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 transition-all @error('password') border-red-500 ring-2 ring-red-500 @enderror">
                            <button type="button" @click="showPassword = !showPassword"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                                <svg x-show="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                <svg x-show="showPassword" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                                </svg>
                            </button>
                        </div>
                        @error('password')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror

                        <!-- Password Strength -->
                        <div x-show="formData.password.length > 0" x-cloak class="mt-2">
                            <div class="flex items-center gap-2 mb-1">
                                <div class="flex-1 h-2 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden">
                                    <div class="h-full transition-all duration-300"
                                         :class="{
                                             'bg-red-500': passwordStrength === 'weak',
                                             'bg-yellow-500': passwordStrength === 'medium',
                                             'bg-green-500': passwordStrength === 'strong'
                                         }"
                                         :style="`width: ${passwordStrength === 'weak' ? '33%' : passwordStrength === 'medium' ? '66%' : '100%'}`"></div>
                                </div>
                                <span class="text-xs font-medium"
                                      :class="{
                                          'text-red-600 dark:text-red-400': passwordStrength === 'weak',
                                          'text-yellow-600 dark:text-yellow-400': passwordStrength === 'medium',
                                          'text-green-600 dark:text-green-400': passwordStrength === 'strong'
                                      }"
                                      x-text="passwordStrength === 'weak' ? 'Fraca' : passwordStrength === 'medium' ? 'Média' : 'Forte'"></span>
                            </div>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Mínimo 8 caracteres, incluindo letras e números</p>
                        </div>
                    </div>

                    <!-- Confirmar Senha -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <span class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Confirmar Senha
                                <span class="text-red-500">*</span>
                            </span>
                        </label>
                        <div class="relative">
                            <input :type="showPasswordConfirm ? 'text' : 'password'"
                                   name="password_confirmation"
                                   x-model="formData.password_confirmation"
                                   required
                                   placeholder="••••••••"
                                   class="w-full px-4 py-3 pr-12 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 transition-all"
                                   :class="formData.password_confirmation && formData.password !== formData.password_confirmation ? 'border-red-500 ring-2 ring-red-500' : ''">
                            <button type="button" @click="showPasswordConfirm = !showPasswordConfirm"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                                <svg x-show="!showPasswordConfirm" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                <svg x-show="showPasswordConfirm" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                                </svg>
                            </button>
                        </div>
                        <div x-show="formData.password_confirmation && formData.password === formData.password_confirmation" x-cloak
                             class="mt-2 text-sm text-green-600 dark:text-green-400 flex items-center gap-1">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            Senhas coincidem
                        </div>
                        <div x-show="formData.password_confirmation && formData.password !== formData.password_confirmation" x-cloak
                             class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center gap-1">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            As senhas não coincidem
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 3: Configurações -->
            <div x-show="step === 3" x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 transform translate-x-4"
                 x-transition:enter-end="opacity-100 transform translate-x-0"
                 class="p-6 space-y-6">

                <div class="flex items-center gap-3 pb-4 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-center w-12 h-12 rounded-xl bg-blue-100 dark:bg-blue-900/30">
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Configurações</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Defina o restaurante e permissões</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Restaurante -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <span class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                                Restaurante
                                <span class="text-red-500">*</span>
                            </span>
                        </label>
                        <select name="restaurante_id"
                                x-model="formData.restaurante_id"
                                required
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 transition-all @error('restaurante_id') border-red-500 ring-2 ring-red-500 @enderror">
                            <option value="" class="dark:bg-gray-700">Selecione um restaurante</option>
                            @foreach ($restaurantes as $restaurante)
                                <option value="{{ $restaurante->id }}" class="dark:bg-gray-700" {{ old('restaurante_id') == $restaurante->id ? 'selected' : '' }}>
                                    {{ $restaurante->nome }}
                                    @if($restaurante->cnpj)
                                        - CNPJ: {{ $restaurante->cnpj }}
                                    @endif
                                </option>
                            @endforeach
                        </select>
                        @error('restaurante_id')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Restaurante ao qual o usuário estará vinculado</p>
                    </div>

                    <!-- Papel/Role -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                            <span class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                </svg>
                                Nível de Permissão
                                <span class="text-red-500">*</span>
                            </span>
                        </label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Usuário -->
                            <label class="relative flex items-start p-4 border-2 rounded-lg cursor-pointer transition-all hover:border-red-300 dark:hover:border-red-500"
                                   :class="formData.role === 'user' ? 'border-red-500 dark:border-red-500 bg-red-50 dark:bg-red-900/20' : 'border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700/50'">
                                <input type="radio"
                                       name="role"
                                       value="user"
                                       x-model="formData.role"
                                       required
                                       class="mt-1 text-red-600 focus:ring-red-500 dark:bg-gray-600 dark:border-gray-500">
                                <div class="ml-3 flex-1">
                                    <div class="flex items-center gap-2 mb-1">
                                        <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                        <span class="font-semibold text-gray-900 dark:text-gray-100">Usuário</span>
                                    </div>
                                    <p class="text-xs text-gray-600 dark:text-gray-400">Acesso básico ao sistema do restaurante</p>
                                </div>
                            </label>

                            <!-- Administrador -->
                            <label class="relative flex items-start p-4 border-2 rounded-lg cursor-pointer transition-all hover:border-red-300 dark:hover:border-red-500"
                                   :class="formData.role === 'admin' ? 'border-red-500 dark:border-red-500 bg-red-50 dark:bg-red-900/20' : 'border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700/50'">
                                <input type="radio"
                                       name="role"
                                       value="admin"
                                       x-model="formData.role"
                                       required
                                       class="mt-1 text-red-600 focus:ring-red-500 dark:bg-gray-600 dark:border-gray-500">
                                <div class="ml-3 flex-1">
                                    <div class="flex items-center gap-2 mb-1">
                                        <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                        </svg>
                                        <span class="font-semibold text-gray-900 dark:text-gray-100">Administrador</span>
                                    </div>
                                    <p class="text-xs text-gray-600 dark:text-gray-400">Acesso total ao painel administrativo</p>
                                </div>
                            </label>
                        </div>
                        @error('role')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>

                <!-- Summary Card -->
                <div class="mt-6 p-4 bg-gray-50 dark:bg-gray-800/50 rounded-lg border border-gray-200 dark:border-gray-700">
                    <h4 class="text-sm font-semibold text-gray-900 dark:text-gray-100 mb-3 flex items-center gap-2">
                        <svg class="w-4 h-4 text-gray-700 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Resumo do Cadastro
                    </h4>
                    <div class="grid grid-cols-2 gap-3 text-sm">
                        <div>
                            <span class="text-gray-500 dark:text-gray-400 block mb-1">Nome:</span>
                            <p class="font-medium text-gray-900 dark:text-gray-100" x-text="formData.name || '-'"></p>
                        </div>
                        <div>
                            <span class="text-gray-500 dark:text-gray-400 block mb-1">E-mail:</span>
                            <p class="font-medium text-gray-900 dark:text-gray-100 break-all" x-text="formData.email || '-'"></p>
                        </div>
                        <div>
                            <span class="text-gray-500 dark:text-gray-400 block mb-1">Restaurante:</span>
                            <p class="font-medium text-gray-900 dark:text-gray-100" x-text="getRestauranteName()"></p>
                        </div>
                        <div>
                            <span class="text-gray-500 dark:text-gray-400 block mb-1">Permissão:</span>
                            <p class="font-medium text-gray-900 dark:text-gray-100" x-text="formData.role === 'admin' ? 'Administrador' : 'Usuário'"></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Navigation Buttons -->
            <div class="px-6 py-4 bg-gray-50 dark:bg-gray-800/50 border-t border-gray-200 dark:border-gray-700 flex items-center justify-between rounded-b-xl">
                <button type="button"
                        x-show="step > 1"
                        @click="step--"
                        class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Voltar
                </button>

                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.users.index') }}"
                       class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                        Cancelar
                    </a>

                    <button type="button"
                            x-show="step < 3"
                            @click="nextStep()"
                            class="btn-ripple inline-flex items-center px-6 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 hover:scale-105 transition-all duration-200 shadow-sm hover:shadow-md">
                        Próximo
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>

                    <button type="submit"
                            x-show="step === 3"
                            x-cloak
                            class="btn-ripple inline-flex items-center px-6 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 hover:scale-105 transition-all duration-200 shadow-sm hover:shadow-md">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Criar Usuário
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('userCreateForm', () => ({
            step: 1,
            showPassword: false,
            showPasswordConfirm: false,
            passwordStrength: 'weak',
            formData: {
                name: '{{ old('name') }}',
                email: '{{ old('email') }}',
                password: '',
                password_confirmation: '',
                restaurante_id: '{{ old('restaurante_id') }}',
                role: '{{ old('role', 'user') }}'
            },

            nextStep() {
                if (this.step === 1) {
                    if (!this.formData.name || !this.formData.email) {
                        window.dispatchEvent(new CustomEvent('show-toast', {
                            detail: { message: 'Preencha todos os campos obrigatórios', type: 'warning' }
                        }));
                        return;
                    }
                }

                if (this.step === 2) {
                    if (!this.formData.password || this.formData.password.length < 8) {
                        window.dispatchEvent(new CustomEvent('show-toast', {
                            detail: { message: 'A senha deve ter no mínimo 8 caracteres', type: 'warning' }
                        }));
                        return;
                    }
                    if (this.formData.password !== this.formData.password_confirmation) {
                        window.dispatchEvent(new CustomEvent('show-toast', {
                            detail: { message: 'As senhas não coincidem', type: 'error' }
                        }));
                        return;
                    }
                }

                this.step++;
            },

            checkPasswordStrength() {
                const password = this.formData.password;
                let strength = 0;

                if (password.length >= 8) strength++;
                if (password.match(/[a-z]/) && password.match(/[A-Z]/)) strength++;
                if (password.match(/\d/)) strength++;
                if (password.match(/[^a-zA-Z\d]/)) strength++;

                if (strength <= 1) this.passwordStrength = 'weak';
                else if (strength <= 2) this.passwordStrength = 'medium';
                else this.passwordStrength = 'strong';
            },

            getRestauranteName() {
                if (!this.formData.restaurante_id) return '-';
                const select = document.querySelector('select[name="restaurante_id"]');
                const option = select?.querySelector(`option[value="${this.formData.restaurante_id}"]`);
                return option?.textContent || '-';
            },

            handleSubmit(e) {
                window.dispatchEvent(new CustomEvent('show-loading'));
            }
        }));
    });
</script>
@endsection
