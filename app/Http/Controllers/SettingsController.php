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
            'app_name' => Setting::get('app_name', 'StockOne'),
            'app_email' => Setting::get('app_email', 'contato@stockone.com'),
            'max_users' => Setting::get('max_users', 'Ilimitado'),
            'allow_user_registration' => Setting::get('allow_user_registration', '0'),
            'maintenance_mode' => Setting::get('maintenance_mode', '0'),
            'pagination_size' => Setting::get('pagination_size', '15'),
            'log_retention_days' => Setting::get('log_retention_days', '90'),
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
            'app_name' => ['required', 'string', 'max:255'],
            'app_email' => ['required', 'email'],
            'max_users' => ['required', 'string'],
            'allow_user_registration' => ['in:0,1'],
            'maintenance_mode' => ['in:0,1'],
            'pagination_size' => ['required', 'integer', 'min:5', 'max:100'],
            'log_retention_days' => ['required', 'integer', 'min:7', 'max:365'],
        ]);

        // Rastrear mudanças
        $changes = [];

        foreach ($validated as $key => $value) {
            $oldValue = Setting::get($key);
            if ($oldValue !== $value) {
                $changes[$key] = [
                    'old' => $oldValue,
                    'new' => $value,
                ];
            }
            Setting::set($key, $value);
        }

        // Registrar auditoria
        if (!empty($changes)) {
            AuditLog::log('update', 'Setting', 0, $changes);
        }

        return redirect()->route('admin.settings')
            ->with('success', 'Configurações atualizadas com sucesso!');
    }
}
