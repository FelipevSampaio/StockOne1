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
    ];

    protected $casts = [
        'preco_venda' => 'decimal:2',
        'ativo_online' => 'boolean',
        'disponibilidade' => 'boolean',
        'ingredientes' => 'array',
        'promocao' => 'array',
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
}
