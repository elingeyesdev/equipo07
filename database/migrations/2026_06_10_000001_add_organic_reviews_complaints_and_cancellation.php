<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pedido_detalles', function (Blueprint $table) {
            $table->text('cancelacion_motivo')->nullable();
            $table->timestamp('cancelado_at')->nullable();
        });

        Schema::create('resenas_organicos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pedido_detalle_id')->unique()->constrained('pedido_detalles')->cascadeOnDelete();
            $table->foreignId('comprador_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('vendedor_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('organico_id')->constrained('organicos')->cascadeOnDelete();
            $table->unsignedTinyInteger('estrellas');
            $table->text('comentario');
            $table->timestamps();
        });

        Schema::create('reclamos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pedido_detalle_id')->constrained('pedido_detalles')->cascadeOnDelete();
            $table->foreignId('creador_id')->constrained('users')->cascadeOnDelete();
            $table->string('creador_rol', 20);
            $table->string('tipo', 40);
            $table->text('descripcion');
            $table->string('estado', 30)->default('recibida');
            $table->text('respuesta_admin')->nullable();
            $table->timestamps();

            $table->unique(['pedido_detalle_id', 'creador_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reclamos');
        Schema::dropIfExists('resenas_organicos');

        Schema::table('pedido_detalles', function (Blueprint $table) {
            $table->dropColumn(['cancelacion_motivo', 'cancelado_at']);
        });
    }
};
