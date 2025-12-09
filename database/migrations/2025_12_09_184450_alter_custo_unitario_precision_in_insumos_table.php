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
        Schema::table('insumos', function (Blueprint $table) {
            // Alterar custo_unitario de decimal(10, 2) para decimal(10, 6) para suportar valores muito pequenos
            // Ex: sal vendido por grama pode custar R$ 0,005 por grama
            $table->decimal('custo_unitario', 10, 6)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('insumos', function (Blueprint $table) {
            // Reverter para decimal(10, 2)
            $table->decimal('custo_unitario', 10, 2)->nullable()->change();
        });
    }
};
