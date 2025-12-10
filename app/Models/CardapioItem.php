<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CardapioItem extends Model
{
    /**
     * Sugere pratos do dia com base no estoque dos ingredientes essenciais.
     * Retorna os itens disponíveis ordenados pela soma do estoque dos ingredientes essenciais.
     */
    public static function sugestaoPratosDoDia($limite = 3)
    {
        // Busca itens ativos e disponíveis
        $itens = self::where('ativo_online', true)
            ->where('disponibilidade', true)
            ->get();

        // Calcula a soma do estoque dos ingredientes essenciais para cada item
        $itens = $itens->map(function ($item) {
            $somaEstoque = 0;
            foreach ($item->receitas()->where('essencial', true)->get() as $receita) {
                $insumo = $receita->insumo;
                $estoque = $insumo ? $insumo->estoque : null;
                if ($estoque) {
                    $somaEstoque += floatval($estoque->quantidade_atual);
                }
            }
            $item->soma_estoque_essenciais = $somaEstoque;
            return $item;
        });

        // Ordena por maior estoque e retorna os top N
        return $itens->sortByDesc('soma_estoque_essenciais')->take($limite);
    }
    use HasFactory;

    protected $table = 'cardapio_itens';

    protected $fillable = [
        'restaurante_id',
        'nome',
        'descricao',
        'preco_venda',
        'tempo_preparo_minutos',
        'complexidade_preparo',
        'categoria',
        'ativo_online',
        'imagem',
        'disponibilidade', // novo campo
        'ingredientes',    // novo campo
        'promocao',        // novo campo
        'insumo_id',       // vinculação direta ao estoque (para bebidas)
        'quantidade_por_unidade', // quantidade do insumo por unidade vendida
        'ordem',           // ordem dentro da categoria
    ];

    protected $casts = [
        'preco_venda' => 'decimal:2',
        'ativo_online' => 'boolean',
        'disponibilidade' => 'boolean',
        'ingredientes' => 'array',
        'promocao' => 'array',
        'quantidade_por_unidade' => 'decimal:6',
    ];

    public function restaurante()
    {
        return $this->belongsTo(Restaurante::class);
    }

    public function receitas()
    {
        return $this->hasMany(Receita::class);
    }

    public function pedidoItens()
    {
        return $this->hasMany(PedidoItem::class);
    }

    /**
     * Relacionamento direto com insumo (para bebidas e itens sem receita)
     */
    public function insumo()
    {
        return $this->belongsTo(Insumo::class);
    }

    /**
     * Calcula o custo total do item considerando receitas ou insumo direto
     */
    public function calcularCustoTotal(): float
    {
        // Se tem insumo direto vinculado, usar esse
        if ($this->insumo_id && $this->quantidade_por_unidade) {
            $custoUnitario = $this->insumo?->custo_unitario ?? 0;
            return $this->quantidade_por_unidade * $custoUnitario;
        }

        // Caso contrário, calcular pela receita
        $custoTotal = 0;
        foreach ($this->receitas as $receita) {
            if ($receita->insumo && $receita->insumo->custo_unitario) {
                $custoTotal += $receita->quantidade_necessaria * $receita->insumo->custo_unitario;
            }
        }
        return $custoTotal;
    }

    /**
     * Calcula a margem de lucro em percentual
     */
    public function calcularMargemLucro(): float
    {
        $custoTotal = $this->calcularCustoTotal();
        if ($custoTotal <= 0 || $this->preco_venda <= 0) {
            return 0;
        }
        return (($this->preco_venda - $custoTotal) / $this->preco_venda) * 100;
    }

    /**
     * Retorna o lucro em reais
     */
    public function calcularLucro(): float
    {
        return $this->preco_venda - $this->calcularCustoTotal();
    }
}
