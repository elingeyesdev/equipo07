<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transporte_eventos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pedido_detalle_id')
                ->constrained('pedido_detalles')
                ->cascadeOnDelete();
            $table->foreignId('transporte_acceso_id')
                ->nullable()
                ->constrained('transporte_accesos')
                ->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('actor', 20);
            $table->string('estado_anterior', 50)->nullable();
            $table->string('estado_nuevo', 50);
            $table->json('metadata')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index(['pedido_detalle_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transporte_eventos');
    }
};
