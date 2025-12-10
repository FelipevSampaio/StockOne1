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
        Schema::table('estoque', function (Blueprint $table) {
            // Aumentar precisão para suportar valores em gramas
            // decimal(15, 6) = 15 dígitos totais, 6 casas decimais
            // Permite valores como 999999999.999999 (9 dígitos inteiros + 6 decimais)
            $table->decimal('quantidade_atual', 15, 6)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('estoque', function (Blueprint $table) {
            // Reverter para a precisão anterior
            $table->decimal('quantidade_atual', 10, 3)->change();
        });
    }
};
