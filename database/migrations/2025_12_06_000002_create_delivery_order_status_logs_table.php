<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('delivery_order_status_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('delivery_order_id')->constrained('delivery_orders');
            $table->string('old_status')->nullable();
            $table->string('new_status');
            $table->timestamp('changed_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('delivery_order_status_logs');
    }
};
