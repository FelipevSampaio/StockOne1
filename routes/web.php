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

Route::get('/login', [AuthController::class, 'showLogin'])->name('login'); // Nome padrão do Laravel
Route::get('/entrar', [AuthController::class, 'showLogin'])->name('auth.login'); // Alias
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
        Route::get('admin/audit-logs/{id}', [AuditLogController::class, 'show'])->name('admin.audit-logs.show');
        Route::delete('admin/audit-logs/{id}', [AuditLogController::class, 'destroy'])->name('admin.audit-logs.destroy');
        Route::post('admin/audit-logs/bulk-destroy', [AuditLogController::class, 'bulkDestroy'])->name('admin.audit-logs.bulkDestroy');
        Route::post('admin/audit-logs/{id}/revert', [AuditLogController::class, 'revert'])->name('admin.audit-logs.revert');
        Route::post('admin/audit-logs/{id}/note', [AuditLogController::class, 'addNote'])->name('admin.audit-logs.add-note');
        Route::get('admin/audit-logs/analytics/data', [AuditLogController::class, 'analytics'])->name('admin.audit-logs.analytics');
        Route::get('admin/audit-logs/search/advanced', [AuditLogController::class, 'advancedSearch'])->name('admin.audit-logs.advanced-search');
        Route::get('admin/audit-logs/trail', [AuditLogController::class, 'trail'])->name('admin.audit-logs.trail');
        Route::post('admin/audit-logs/cache/clear', [AuditLogController::class, 'clearCache'])->name('admin.audit-logs.clear-cache');
        Route::get('admin/audit-logs/export/pdf', [AuditLogController::class, 'exportPdf'])->name('admin.audit-logs.export-pdf');
        Route::get('admin/audit-logs/export/excel', [AuditLogController::class, 'exportExcel'])->name('admin.audit-logs.export-excel');

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
        Route::post('admin/users/{user}/send-password-reset', [UserAdminController::class, 'sendPasswordReset'])->name('admin.users.send-password-reset');
        Route::post('admin/users/{user}/force-logout', [UserAdminController::class, 'forceLogout'])->name('admin.users.force-logout');
        Route::put('admin/users/{user}/notes', [UserAdminController::class, 'updateNotes'])->name('admin.users.update-notes');
        Route::get('admin/users/{user}/active-sessions', [UserAdminController::class, 'getActiveSessions'])->name('admin.users.active-sessions');
        Route::post('admin/users/clear-stats-cache', [UserAdminController::class, 'clearStatsCache'])->name('admin.users.clear-stats-cache');

        Route::resource('admin/restaurantes', RestauranteAdminController::class)->names('admin.restaurantes')->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
        Route::get('admin/restaurantes/export/csv', [RestauranteAdminController::class, 'export'])->name('admin.restaurantes.export');
        Route::post('admin/restaurantes/bulk-action', [RestauranteAdminController::class, 'bulkAction'])->name('admin.restaurantes.bulk-action');
        Route::post('admin/restaurantes/{restaurante}/toggle-status', [RestauranteAdminController::class, 'toggleStatus'])->name('admin.restaurantes.toggle-status');
        Route::get('admin/restaurantes/{restaurante}/quick-view', [RestauranteAdminController::class, 'quickView'])->name('admin.restaurantes.quick-view');

        Route::get('admin/audit-logs/export/csv', [AuditLogController::class, 'export'])->name('admin.audit-logs.export');

        // Busca global
        Route::get('admin/search', [\App\Http\Controllers\SearchController::class, 'search'])->name('admin.search');
    });

    // Rotas do restaurante
    Route::middleware('restaurante.session')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
        Route::get('/dashboard/chart-data', [\App\Http\Controllers\DashboardController::class, 'chartData'])->name('dashboard.chart-data');
        Route::get('/dashboard/refresh-stats', [\App\Http\Controllers\DashboardController::class, 'refreshStats'])->name('dashboard.refresh-stats');
        Route::post('/dashboard/clear-cache', [\App\Http\Controllers\DashboardController::class, 'clearCache'])->name('dashboard.clear-cache');

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
