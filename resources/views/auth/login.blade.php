<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>StockOne • Acesso do restaurante</title>
        @if (app()->environment('testing'))
            <style>
                :root {
                    font-family: 'Instrument Sans', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
                }
            </style>
        @else
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif
    </head>
    <body class="bg-gray-50">
        <div class="min-h-screen flex flex-col items-center justify-center px-4">
            <div class="mx-auto w-full max-w-md rounded-3xl border border-gray-100 bg-white p-8 shadow-xl">
                <div class="mb-8 text-center">
                    <p class="text-xs uppercase tracking-[0.3em] text-red-500">StockOne</p>
                    <h1 class="mt-2 text-3xl font-semibold text-gray-900">Acesso ao painel</h1>
                    <p class="mt-2 text-sm text-gray-500">
                        Informe suas credenciais para entrar.
                    </p>
                </div>

                @if (session('success'))
                    <div class="mb-6 rounded-2xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 p-4">
                        <div class="flex items-start gap-3">
                            <div class="flex-shrink-0">
                                @if(session('error_type') === 'restaurante_desativado')
                                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                    </svg>
                                @else
                                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                @endif
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-semibold text-red-800">{{ session('error') }}</p>
                                @if(session('error_details'))
                                    <p class="mt-1 text-sm text-red-700">{{ session('error_details') }}</p>
                                @endif
                                @if(session('error_type') === 'restaurante_desativado')
                                    <div class="mt-3 pt-3 border-t border-red-200">
                                        <p class="text-xs font-medium text-red-800 mb-2">📧 Suporte StockOne</p>
                                        <p class="text-xs text-red-700">Email: <a href="mailto:suporte@stockone.com" class="underline hover:text-red-900">suporte@stockone.com</a></p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif

                <form method="POST" action="{{ route('auth.login.submit') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label class="text-sm font-semibold text-gray-700">
                            E-mail cadastrado
                            <input
                                type="email"
                                name="email"
                                value="{{ old('email') }}"
                                required
                                autofocus
                                class="mt-2 w-full rounded-2xl border border-gray-200 px-4 py-3 text-sm focus:border-red-500 focus:ring-red-500"
                            >
                        </label>
                        @error('email')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="text-sm font-semibold text-gray-700">
                            Senha
                            <input
                                type="password"
                                name="password"
                                required
                                class="mt-2 w-full rounded-2xl border border-gray-200 px-4 py-3 text-sm focus:border-red-500 focus:ring-red-500"
                            >
                        </label>
                        @error('password')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <button
                        type="submit"
                        class="mt-4 w-full rounded-2xl bg-red-600 px-4 py-3 text-sm font-semibold text-white shadow-lg shadow-red-200 transition hover:bg-red-500"
                    >
                        Entrar no painel
                    </button>
                </form>

                <p class="mt-8 text-center text-xs text-gray-400">
                    Em caso de dúvidas, fale com o suporte StockOne.
                </p>
            </div>
        </div>
    </body>
</html>

