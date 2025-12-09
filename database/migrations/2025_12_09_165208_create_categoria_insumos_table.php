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
        Schema::create('categoria_insumos', function (Blueprint $table) {
            $table->id();
            
            // FK para o Restaurante (multi-tenant)
            $table->foreignId('restaurante_id')->constrained('restaurantes')->onDelete('cascade');
            
            $table->string('nome', 100);
            $table->text('descricao')->nullable();
            $table->boolean('predefinida')->default(false); // Categorias do sistema não podem ser deletadas
            $table->integer('ordem')->default(0); // Para ordenação
            
            $table->timestamps();
            
            // Evitar categorias duplicadas por restaurante
            $table->unique(['restaurante_id', 'nome']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categoria_insumos');
    }
};
