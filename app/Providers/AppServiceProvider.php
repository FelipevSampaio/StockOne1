<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \App\Models\Insumo::observe(\App\Observers\InsumoObserver::class);
        \App\Models\PedidoItem::observe(\App\Observers\PedidoItemObserver::class);
        \App\Models\Desperdicio::observe(\App\Observers\DesperdicioObserver::class);
        \App\Models\FilaProducao::observe(\App\Observers\FilaProducaoObserver::class);
        \App\Models\CompraSugestao::observe(\App\Observers\CompraSugestaoObserver::class);
        \App\Models\Estoque::observe(\App\Observers\EstoqueObserver::class);
        \App\Models\Pedido::observe(\App\Observers\PedidoObserver::class);
    }
}
