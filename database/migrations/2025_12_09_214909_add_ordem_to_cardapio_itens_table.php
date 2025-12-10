<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('cardapio_itens', function (Blueprint $table) {
            // Adicionar campo de ordem dentro da categoria
            // A ordem será única por categoria e restaurante
            $table->integer('ordem')->nullable()->after('categoria')->default(0);
            
            // Índice composto para otimizar ordenação por categoria
            $table->index(['restaurante_id', 'categoria', 'ordem'], 'idx_cardapio_ordem');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cardapio_itens', function (Blueprint $table) {
            $table->dropIndex('idx_cardapio_ordem');
            $table->dropColumn('ordem');
        });
    }
};
