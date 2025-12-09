<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoriaInsumo extends Model
{
    use HasFactory;

    protected $table = 'categoria_insumos';

    protected $fillable = [
        'restaurante_id',
        'nome',
        'descricao',
        'predefinida',
        'ordem',
    ];

    protected $casts = [
        'predefinida' => 'boolean',
    ];

    public function restaurante()
    {
        return $this->belongsTo(Restaurante::class);
    }

    public function insumos()
    {
        return Insumo::where('categoria', $this->nome)
            ->where('restaurante_id', $this->restaurante_id);
    }

    /**
     * Verifica se a categoria está em uso
     */
    public function estaEmUso(): bool
    {
        return Insumo::where('categoria', $this->nome)
            ->where('restaurante_id', $this->restaurante_id)
            ->count() > 0;
    }

    /**
     * Cria categorias predefinidas para um restaurante
     */
    public static function criarPredefinidas(int $restauranteId): void
    {
        $categoriasPredefinidas = [
            ['nome' => 'Grãos e Cereais', 'ordem' => 1],
            ['nome' => 'Laticínios', 'ordem' => 2],
            ['nome' => 'Carnes', 'ordem' => 3],
            ['nome' => 'Frutas', 'ordem' => 4],
            ['nome' => 'Verduras e Legumes', 'ordem' => 5],
            ['nome' => 'Temperos e Condimentos', 'ordem' => 6],
            ['nome' => 'Óleos e Gorduras', 'ordem' => 7],
            ['nome' => 'Bebidas', 'ordem' => 8],
            ['nome' => 'Outros', 'ordem' => 9],
        ];

        foreach ($categoriasPredefinidas as $categoria) {
            self::firstOrCreate(
                [
                    'restaurante_id' => $restauranteId,
                    'nome' => $categoria['nome'],
                ],
                [
                    'predefinida' => true,
                    'ordem' => $categoria['ordem'],
                ]
            );
        }
    }
}
