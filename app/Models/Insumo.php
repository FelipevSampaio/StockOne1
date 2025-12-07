<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Insumo extends Model
{
    /**
     * Gera alertas automáticos para insumos próximos do vencimento
     */
    public static function gerarAlertasVencimento($dias = 7)
    {
        $insumos = self::proximosDoVencimento($dias);
        foreach ($insumos as $insumo) {
            // Verifica se já existe alerta não resolvido
            $alertaExistente = $insumo->alertas()->where('tipo_alerta', 'validade_proxima')->where('resolvido', false)->first();
            if (!$alertaExistente) {
                $insumo->alertas()->create([
                    'tipo_alerta' => 'validade_proxima',
                    'mensagem' => "O insumo '{$insumo->nome}' está próximo do vencimento.",
                    'data_hora_alerta' => now(),
                    'visualizado' => false,
                    'resolvido' => false,
                ]);
            }
        }
    }
    /**
     * Retorna insumos próximos do vencimento em X dias
     */
    public static function proximosDoVencimento($dias = 7)
    {
        $hoje = now();
        $limite = $hoje->copy()->addDays($dias);
        return self::whereDate('data_validade_minima', '<=', $limite)
            ->whereDate('data_validade_minima', '>=', $hoje)
            ->get();
    }
    /**
     * Gera sugestão de compra baseada em consumo histórico e previsão de demanda
     * @param int $diasAnalise
     * @return array
     */
    public function gerarSugestaoCompra(int $diasAnalise = 30)
    {
        // Consumo histórico: soma dos itens usados em receitas/pedidos nos últimos X dias
        $consumo = $this->receitas()
            ->whereHas('pedidos', function($q) use ($diasAnalise) {
                $q->where('created_at', '>=', now()->subDays($diasAnalise));
            })
            ->sum('quantidade_utilizada');

        // Previsão de demanda simples: média diária dos últimos X dias multiplicada por X dias
        $mediaDiaria = $diasAnalise > 0 ? $consumo / $diasAnalise : 0;
        $previsaoDemanda = $mediaDiaria * $diasAnalise;

        // Quantidade sugerida: suficiente para cobrir previsão menos estoque atual
        $estoqueAtual = $this->estoque ? $this->estoque->quantidade_atual : 0;
        $quantidadeSugerida = max(0, $previsaoDemanda - $estoqueAtual);

        return [
            'insumo_id' => $this->id,
            'quantidade_sugerida' => round($quantidadeSugerida, 2),
            'justificativa' => "Baseado no consumo histórico de {$diasAnalise} dias.",
            'periodo_analise_dias' => $diasAnalise,
            'data_geracao' => now(),
        ];
    }
    use HasFactory;

    protected $fillable = [
        'restaurante_id',
        'nome',
        'descricao',
        'categoria',
        'unidade_medida',
        'ponto_reposicao_minimo',
        'custo_unitario',
        'data_validade_minima',
    ];

    protected $casts = [
        'data_validade_minima' => 'date',
        'ponto_reposicao_minimo' => 'decimal:2',
        'custo_unitario' => 'decimal:2',
    ];

    public function restaurante()
    {
        return $this->belongsTo(Restaurante::class);
    }

    public function estoque()
    {
        return $this->hasOne(Estoque::class);
    }

    public function alertas()
    {
        return $this->hasMany(Alerta::class);
    }

    public function comprasSugestoes()
    {
        return $this->hasMany(CompraSugestao::class);
    }

    public function receitas()
    {
        return $this->hasMany(Receita::class);
    }

        /**
         * Verifica se o insumo está abaixo do ponto de reposição mínimo e cria alerta automático
         */
        public function verificarBaixoEstoqueEAlertar()
        {
            $estoque = $this->estoque;
            if ($estoque && $estoque->quantidade_atual <= $this->ponto_reposicao_minimo) {
                // Verifica se já existe alerta não resolvido
                $alertaExistente = $this->alertas()->where('tipo_alerta', 'baixo_estoque')->where('resolvido', false)->first();
                if (!$alertaExistente) {
                    $this->alertas()->create([
                        'tipo_alerta' => 'baixo_estoque',
                        'mensagem' => "O insumo '{$this->nome}' está abaixo do estoque mínimo.",
                        'data_hora_alerta' => now(),
                        'visualizado' => false,
                        'resolvido' => false,
                    ]);
                }
            }
        }
}
