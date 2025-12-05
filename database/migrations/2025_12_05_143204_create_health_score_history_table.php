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
        Schema::create('health_score_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restaurante_id')->constrained('restaurantes')->onDelete('cascade');
            $table->integer('score');
            $table->string('status', 20);
            $table->string('color', 20);
            $table->string('risk', 20);
            $table->date('recorded_at');
            $table->timestamps();

            // Índices para busca rápida
            $table->index(['restaurante_id', 'recorded_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('health_score_history');
    }
};
