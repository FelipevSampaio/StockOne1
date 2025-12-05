<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Restaurante extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'cnpj',
        'endereco',
        'telefone',
        'email',
        'status',
    ];

    public function insumos()
    {
        return $this->hasMany(Insumo::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function cardapioItens()
    {
        return $this->hasMany(CardapioItem::class);
    }

    public function pedidos()
    {
        return $this->hasMany(Pedido::class);
    }

    /**
     * Calcula o Health Score do restaurante (0-100)
     * Lógica simples: quanto mais ativo, maior a pontuação
     */
    public function calculateHealthScore()
    {
        $score = 0;
        $alerts = [];

        // 1. Último Login (40 pontos) - Principal indicador
        $lastLogin = $this->users()->orderBy('last_login_at', 'desc')->value('last_login_at');
        $daysSinceLogin = $lastLogin ? abs((int) now()->diffInDays($lastLogin)) : 999;

        if ($daysSinceLogin <= 7) {
            $score += 40;
        } elseif ($daysSinceLogin <= 15) {
            $score += 25;
        } elseif ($daysSinceLogin <= 30) {
            $score += 10;
        } else {
            $alerts[] = '⚠️ Sem login há mais de 30 dias';
        }

        // 2. Pedidos no Mês (30 pontos)
        $pedidosMes = $this->pedidos()->where('created_at', '>=', now()->subDays(30))->count();

        if ($pedidosMes >= 10) {
            $score += 30;
        } elseif ($pedidosMes >= 5) {
            $score += 20;
        } elseif ($pedidosMes >= 1) {
            $score += 10;
        } else {
            $alerts[] = '⚠️ Nenhum pedido nos últimos 30 dias';
        }

        // 3. Tem Insumos e Cardápio? (20 pontos)
        $temInsumos = $this->insumos()->count() > 0;
        $temCardapio = $this->cardapioItens()->where('ativo_online', true)->count() > 0;

        if ($temInsumos && $temCardapio) {
            $score += 20;
        } elseif ($temInsumos || $temCardapio) {
            $score += 10;
        } else {
            $alerts[] = '⚠️ Sistema não configurado';
        }

        // 4. Tem Usuários? (10 pontos)
        $temUsuarios = $this->users()->count() > 0;

        if ($temUsuarios) {
            $score += 10;
        } else {
            $alerts[] = '⚠️ Nenhum usuário vinculado';
        }

        // Determinar status baseado no score
        if ($score >= 80) {
            $status = 'Ativo';
            $color = 'green';
            $risk = 'baixo';
        } elseif ($score >= 60) {
            $status = 'Moderado';
            $color = 'yellow';
            $risk = 'médio';
        } elseif ($score >= 30) {
            $status = 'Em Risco';
            $color = 'orange';
            $risk = 'alto';
        } else {
            $status = 'Inativo';
            $color = 'red';
            $risk = 'crítico';
        }

        return [
            'score' => $score,
            'status' => $status,
            'color' => $color,
            'risk' => $risk,
            'last_login_days' => $daysSinceLogin < 999 ? $daysSinceLogin : null,
            'pedidos_mes' => $pedidosMes,
            'tem_insumos' => $temInsumos,
            'tem_cardapio' => $temCardapio,
            'tem_usuarios' => $temUsuarios,
            'alerts' => $alerts,
        ];
    }

    /**
     * Salvar histórico de health score (executar diariamente via cron)
     */
    public function saveHealthScoreHistory()
    {
        $healthData = $this->calculateHealthScore();

        \DB::table('health_score_history')->insert([
            'restaurante_id' => $this->id,
            'score' => $healthData['score'],
            'status' => $healthData['status'],
            'color' => $healthData['color'],
            'risk' => $healthData['risk'],
            'recorded_at' => now()->toDateString(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Obter tendência comparando com mês anterior
     */
    public function getHealthTrend()
    {
        $currentScore = $this->calculateHealthScore()['score'];

        $lastMonthScore = \DB::table('health_score_history')
            ->where('restaurante_id', $this->id)
            ->where('recorded_at', '>=', now()->subDays(30))
            ->where('recorded_at', '<=', now()->subDays(25))
            ->avg('score');

        if (!$lastMonthScore) {
            return ['trend' => 'stable', 'difference' => 0, 'icon' => '→'];
        }

        $difference = $currentScore - $lastMonthScore;

        if ($difference > 5) {
            return ['trend' => 'up', 'difference' => round($difference), 'icon' => '↗️', 'color' => 'green'];
        } elseif ($difference < -5) {
            return ['trend' => 'down', 'difference' => round(abs($difference)), 'icon' => '↘️', 'color' => 'red'];
        } else {
            return ['trend' => 'stable', 'difference' => 0, 'icon' => '→', 'color' => 'gray'];
        }
    }

    /**
     * Obter histórico dos últimos 90 dias
     */
    public function getHealthHistory($days = 90)
    {
        return \DB::table('health_score_history')
            ->where('restaurante_id', $this->id)
            ->where('recorded_at', '>=', now()->subDays($days))
            ->orderBy('recorded_at', 'asc')
            ->get(['recorded_at', 'score', 'status'])
            ->map(function ($item) {
                return [
                    'date' => \Carbon\Carbon::parse($item->recorded_at)->format('d/m'),
                    'score' => $item->score,
                    'status' => $item->status,
                ];
            });
    }

    /**
     * Gerar ações recomendadas baseadas nos problemas
     */
    public function getRecommendedActions()
    {
        $healthData = $this->calculateHealthScore();
        $actions = [];

        // Sem login recente
        if ($healthData['last_login_days'] > 7) {
            $actions[] = [
                'icon' => '📧',
                'title' => 'Enviar Email de Engajamento',
                'description' => 'Cliente sem login há ' . $healthData['last_login_days'] . ' dias',
                'priority' => 'high',
                'type' => 'email'
            ];
        }

        // Sem pedidos
        if ($healthData['pedidos_mes'] === 0) {
            $actions[] = [
                'icon' => '📞',
                'title' => 'Contato Telefônico',
                'description' => 'Entender por que não está fazendo pedidos',
                'priority' => 'high',
                'type' => 'call'
            ];
        } elseif ($healthData['pedidos_mes'] < 5) {
            $actions[] = [
                'icon' => '💡',
                'title' => 'Incentivar Uso',
                'description' => 'Poucos pedidos no mês, oferecer suporte',
                'priority' => 'medium',
                'type' => 'support'
            ];
        }

        // Sistema não configurado
        if (!$healthData['tem_insumos']) {
            $actions[] = [
                'icon' => '🎓',
                'title' => 'Treinamento - Gestão de Estoque',
                'description' => 'Ajudar a cadastrar insumos no sistema',
                'priority' => 'medium',
                'type' => 'training'
            ];
        }

        if (!$healthData['tem_cardapio']) {
            $actions[] = [
                'icon' => '🎓',
                'title' => 'Treinamento - Cardápio Online',
                'description' => 'Configurar cardápio para vendas online',
                'priority' => 'medium',
                'type' => 'training'
            ];
        }

        // Score crítico
        if ($healthData['score'] < 30) {
            $actions[] = [
                'icon' => '🚨',
                'title' => 'Reunião Urgente',
                'description' => 'Cliente em risco crítico de churn',
                'priority' => 'critical',
                'type' => 'meeting'
            ];
        }

        return $actions;
    }

    /**
     * Identificar ciclo de vida do cliente
     */
    public function getLifecycleStage()
    {
        $daysSinceCreation = now()->diffInDays($this->created_at);
        $healthData = $this->calculateHealthScore();
        $score = $healthData['score'];

        // Novo (até 30 dias)
        if ($daysSinceCreation <= 30) {
            if ($score >= 50) {
                return ['stage' => 'Onboarding Ativo', 'color' => 'blue', 'icon' => '🌱'];
            } else {
                return ['stage' => 'Onboarding em Risco', 'color' => 'orange', 'icon' => '⚠️'];
            }
        }

        // Ativo (31-90 dias)
        if ($daysSinceCreation <= 90) {
            if ($score >= 70) {
                return ['stage' => 'Cliente Ativo', 'color' => 'green', 'icon' => '✅'];
            } elseif ($score >= 40) {
                return ['stage' => 'Em Declínio', 'color' => 'yellow', 'icon' => '📉'];
            } else {
                return ['stage' => 'Em Risco', 'color' => 'red', 'icon' => '🚨'];
            }
        }

        // Maduro (90+ dias)
        if ($score >= 70) {
            return ['stage' => 'Cliente Estabelecido', 'color' => 'green', 'icon' => '⭐'];
        } elseif ($score >= 40) {
            return ['stage' => 'Reativação Necessária', 'color' => 'orange', 'icon' => '🔄'];
        } else {
            return ['stage' => 'Churn Iminente', 'color' => 'red', 'icon' => '💔'];
        }
    }

    /**
     * Accessor para health_data
     */
    public function getHealthDataAttribute()
    {
        return $this->calculateHealthScore();
    }
}
