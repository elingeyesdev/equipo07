<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pedido_ubicaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pedido_id')->constrained('pedidos')->onDelete('cascade');
            $table->foreignId('pedido_detalle_id')->nullable()->constrained('pedido_detalles')->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->decimal('latitud', 10, 8);
            $table->decimal('longitud', 11, 8);
            $table->decimal('precision_metros', 8, 2)->nullable();
            $table->decimal('velocidad_m_s', 8, 2)->nullable();
            $table->decimal('rumbo_grados', 8, 2)->nullable();
            $table->string('tipo_recorrido', 30)->default('entrega');
            $table->timestamps();

            $table->index(['pedido_id', 'created_at']);
            $table->index(['pedido_detalle_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pedido_ubicaciones');
    }
};
