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
            // Adicionar campos para vinculação direta ao estoque (útil para bebidas)
            $table->foreignId('insumo_id')->nullable()->after('categoria')->constrained('insumos')->onDelete('set null');
            $table->decimal('quantidade_por_unidade', 10, 6)->nullable()->after('insumo_id')->comment('Quantidade do insumo por unidade vendida (ex: 1 lata = 350ml)');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cardapio_itens', function (Blueprint $table) {
            $table->dropForeign(['insumo_id']);
            $table->dropColumn(['insumo_id', 'quantidade_por_unidade']);
        });
    }
};
