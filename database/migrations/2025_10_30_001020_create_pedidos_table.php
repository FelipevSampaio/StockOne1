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
        // Tabela 'pedidos' (anteriormente 'pedido')
        Schema::create('pedidos', function (Blueprint $table) {
            $table->id();

            // FKs no padrão Eloquent
            $table->foreignId('restaurante_id')->constrained('restaurantes');
            // Assumindo que 'usuario' será unificado com 'users' ou será o modelo de funcionário
            $table->foreignId('usuario_id')->nullable()->constrained('users');

            // Adicionado em migration de comandas: $table->foreignId('comanda_id')->nullable()->constrained('comandas')->after('usuario_id');

            $table->string('numero_pedido_externo', 255)->nullable();
            $table->string('plataforma_origem', 100);
            $table->dateTime('data_hora_pedido'); // Mantendo este campo de negócio
            $table->enum('status', ['recebido', 'em_preparo', 'pronto', 'entregue', 'cancelado'])->default('recebido');
            $table->decimal('valor_total', 10, 2)->nullable();
            $table->integer('tempo_preparo_estimado')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pedidos');
    }
};
