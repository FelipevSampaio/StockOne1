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
        Schema::create('comandas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mesa_id')->nullable()->constrained('mesas')->onDelete('set null');
            $table->foreignId('cliente_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('codigo', 20)->unique();
            $table->enum('status', ['aberta', 'fechada', 'cancelada'])->default('aberta');
            $table->timestamps();
        });
        // Adiciona coluna comanda_id em pedidos
        Schema::table('pedidos', function (Blueprint $table) {
            $table->foreignId('comanda_id')->nullable()->constrained('comandas')->after('usuario_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pedidos', function (Blueprint $table) {
            $table->dropForeign(['comanda_id']);
            $table->dropColumn('comanda_id');
        });
        Schema::dropIfExists('comandas');
    }
};
