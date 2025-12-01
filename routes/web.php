<?php

use App\Http\Controllers\AlertaController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CardapioItemController;
use App\Http\Controllers\CompraSugestaoController;
use App\Http\Controllers\EstoqueController;
use App\Http\Controllers\FilaProducaoController;
use App\Http\Controllers\InsumoController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\PedidoItemController;
use App\Http\Controllers\PublicCartController;
use App\Http\Controllers\PublicMenuController;
use App\Http\Controllers\ReceitaController;
use App\Http\Controllers\UserAdminController;
use App\Http\Controllers\RestauranteAdminController;
use App\Http\Controllers\DashboardAdminController;
use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return session()->has('restaurante_id')
        ? redirect()->route('dashboard')
        : redirect()->route('auth.login');
});

Route::get('/menu', [\App\Http\Controllers\PublicMenuController::class, 'index'])->name('public.menu');

Route::get('/login', [AuthController::class, 'showLogin'])->name('auth.login');
Route::post('/login', [AuthController::class, 'authenticate'])->name('auth.login.submit');

Route::post('/carrinho', [PublicCartController::class, 'store'])->name('public.cart.store');
Route::patch('/carrinho/{cardapioItemId}', [PublicCartController::class, 'update'])->name('public.cart.update');
Route::delete('/carrinho/{cardapioItemId}', [PublicCartController::class, 'destroy'])->name('public.cart.destroy');
Route::post('/carrinho/finalizar', [PublicCartController::class, 'checkout'])->name('public.cart.checkout');

Route::middleware('auth')->group(function () {
    // Rotas administrativas (requer ser Admin)
    Route::middleware('admin')->group(function () {
        // Dashboard administrativo
        Route::get('admin/dashboard', [DashboardAdminController::class, 'index'])->name('admin.dashboard');
        Route::get('admin/dashboard/activity-data', [DashboardAdminController::class, 'activityData'])->name('admin.dashboard.activity-data');

        // Notificações
        Route::get('admin/notifications', [NotificationController::class, 'index'])->name('admin.notifications.index');
        Route::post('admin/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('admin.notifications.read');
        Route::post('admin/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('admin.notifications.mark-all-read');

        // Logs de auditoria
        Route::get('admin/audit-logs', [AuditLogController::class, 'index'])->name('admin.audit-logs.index');
        Route::delete('admin/audit-logs/{id}', [AuditLogController::class, 'destroy'])->name('admin.audit-logs.destroy');
        Route::post('admin/audit-logs/bulk-delete', [AuditLogController::class, 'bulkDestroy'])->name('admin.audit-logs.bulkDestroy');

        // Configurações
        Route::get('admin/settings', [SettingsController::class, 'index'])->name('admin.settings');
        Route::put('admin/settings', [SettingsController::class, 'update'])->name('admin.settings.update');

        // Rotas de admin (gerenciamento de usuários e restaurantes)
        Route::resource('admin/users', UserAdminController::class)->names('admin.users')->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
        Route::post('admin/users/{id}/restore', [UserAdminController::class, 'restore'])->name('admin.users.restore');
        Route::delete('admin/users/{id}/force-delete', [UserAdminController::class, 'forceDelete'])->name('admin.users.forceDelete');
        Route::get('admin/users/export/csv', [UserAdminController::class, 'export'])->name('admin.users.export');
        Route::get('admin/users/live-search', [UserAdminController::class, 'liveSearch'])->name('admin.users.live-search');
        Route::get('admin/users/{user}/quick-view', [UserAdminController::class, 'quickView'])->name('admin.users.quick-view');
        Route::post('admin/users/bulk-action', [UserAdminController::class, 'bulkAction'])->name('admin.users.bulk-action');

        Route::resource('admin/restaurantes', RestauranteAdminController::class)->names('admin.restaurantes')->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
        Route::get('admin/restaurantes/export/csv', [RestauranteAdminController::class, 'export'])->name('admin.restaurantes.export');
        Route::post('admin/restaurantes/bulk-action', [RestauranteAdminController::class, 'bulkAction'])->name('admin.restaurantes.bulk-action');

        Route::get('admin/audit-logs/export/csv', [AuditLogController::class, 'export'])->name('admin.audit-logs.export');

        // Busca global
        Route::get('admin/search', [\App\Http\Controllers\SearchController::class, 'search'])->name('admin.search');
    });

    // Rotas do restaurante
    Route::middleware('restaurante.session')->group(function () {
        Route::view('/dashboard', 'dashboard')->name('dashboard');

        Route::resource('insumos', InsumoController::class)->except(['show']);
        Route::resource('cardapio-itens', CardapioItemController::class)
            ->parameters(['cardapio-itens' => 'cardapio_item'])
            ->except(['show']);
        Route::resource('pedidos', PedidoController::class)->except(['show']);
        Route::resource('estoque', EstoqueController::class)->except(['show']);
        Route::resource('alertas', AlertaController::class)->except(['show']);
        Route::resource('compras-sugestoes', CompraSugestaoController::class)->except(['show']);
        Route::resource('receitas', ReceitaController::class)->except(['show']);
        Route::resource('pedido-itens', PedidoItemController::class)->except(['show']);
        Route::resource('fila-producao', FilaProducaoController::class)->except(['show']);
    });

    Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');
});
