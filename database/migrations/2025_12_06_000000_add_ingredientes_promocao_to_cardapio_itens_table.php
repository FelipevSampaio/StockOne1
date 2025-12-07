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
            $table->boolean('disponibilidade')->default(true)->after('ativo_online');
            $table->json('ingredientes')->nullable()->after('imagem');
            $table->json('promocao')->nullable()->after('ingredientes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cardapio_itens', function (Blueprint $table) {
            $table->dropColumn(['disponibilidade', 'ingredientes', 'promocao']);
        });
    }
};
