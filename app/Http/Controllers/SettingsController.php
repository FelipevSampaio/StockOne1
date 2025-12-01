<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SettingsController extends Controller
{
    /**
     * Verificar se o usuário é administrador
     */
    private function checkAdmin()
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403, 'Acesso negado.');
        }
    }

    /**
     * Exibir página de configurações
     */
    public function index()
    {
        $this->checkAdmin();

        $settings = [
            // Card 1: Informações Gerais
            'app_name' => Setting::get('app_name', 'StockOne'),
            'app_email' => Setting::get('app_email', ''),
            'app_description' => Setting::get('app_description', ''),

            // Card 2: Limites e Restrições
            'max_users' => Setting::get('max_users', ''),
            'max_restaurantes' => Setting::get('max_restaurantes', ''),
            'max_products' => Setting::get('max_products', ''),

            // Card 3: Segurança e Autenticação
            'session_timeout' => Setting::get('session_timeout', '120'),
            'max_login_attempts' => Setting::get('max_login_attempts', '5'),
            'lockout_duration' => Setting::get('lockout_duration', '15'),
            'min_password_length' => Setting::get('min_password_length', '8'),

            // Card 4: Estoque e Alertas
            'low_stock_threshold' => Setting::get('low_stock_threshold', '20'),
            'expiration_warning_days' => Setting::get('expiration_warning_days', '7'),
            'default_unit' => Setting::get('default_unit', 'kg'),
            'order_history_days' => Setting::get('order_history_days', '30'),

            // Card 5: Funcionalidades
            'allow_registration' => Setting::get('allow_registration', '0'),
            'notifications_enabled' => Setting::get('notifications_enabled', '0'),
            'maintenance_mode' => Setting::get('maintenance_mode', '0'),
            'audit_logs_enabled' => Setting::get('audit_logs_enabled', '1'),
            'auto_backup_enabled' => Setting::get('auto_backup_enabled', '0'),
            'advanced_reports' => Setting::get('advanced_reports', '1'),
        ];

        return view('admin.settings', compact('settings'));
    }

    /**
     * Atualizar configurações
     */
    public function update(Request $request)
    {
        $this->checkAdmin();

        $validated = $request->validate([
            // Card 1: Informações Gerais
            'app_name' => ['required', 'string', 'max:255'],
            'app_email' => ['nullable', 'email', 'max:255'],
            'app_description' => ['nullable', 'string', 'max:500'],

            // Card 2: Limites e Restrições
            'max_users' => ['nullable', 'string', 'max:50'],
            'max_restaurantes' => ['nullable', 'string', 'max:50'],
            'max_products' => ['nullable', 'string', 'max:50'],

            // Card 3: Segurança e Autenticação
            'session_timeout' => ['required', 'integer', 'min:5', 'max:1440'],
            'max_login_attempts' => ['required', 'integer', 'min:3', 'max:10'],
            'lockout_duration' => ['required', 'integer', 'min:5', 'max:60'],
            'min_password_length' => ['required', 'integer', 'min:6', 'max:20'],

            // Card 4: Estoque e Alertas
            'low_stock_threshold' => ['required', 'integer', 'min:5', 'max:50'],
            'expiration_warning_days' => ['required', 'integer', 'min:1', 'max:30'],
            'default_unit' => ['required', 'in:kg,g,l,ml,un'],
            'order_history_days' => ['required', 'integer', 'min:7', 'max:365'],

            // Card 5: Funcionalidades (checkboxes)
            'allow_registration' => ['nullable', 'in:0,1'],
            'notifications_enabled' => ['nullable', 'in:0,1'],
            'maintenance_mode' => ['nullable', 'in:0,1'],
            'audit_logs_enabled' => ['nullable', 'in:0,1'],
            'auto_backup_enabled' => ['nullable', 'in:0,1'],
            'advanced_reports' => ['nullable', 'in:0,1'],
        ]);

        // Normalizar checkboxes (se não vier no request, é 0)
        $checkboxes = ['allow_registration', 'notifications_enabled', 'maintenance_mode',
                       'audit_logs_enabled', 'auto_backup_enabled', 'advanced_reports'];

        foreach ($checkboxes as $checkbox) {
            $validated[$checkbox] = $validated[$checkbox] ?? '0';
        }

        // Rastrear mudanças
        $changes = [];

        foreach ($validated as $key => $value) {
            $oldValue = Setting::get($key);
            if ($oldValue !== $value) {
                $changes[$key] = [
                    'old' => $oldValue,
                    'new' => $value,
                ];
                Setting::set($key, $value);
            }
        }

        // Registrar auditoria
        if (!empty($changes)) {
            AuditLog::log('update', 'settings', 0, $changes);
        }

        return redirect()->route('admin.settings')
            ->with('success', 'Configurações atualizadas com sucesso!');
    }
}
