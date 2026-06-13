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
        Schema::create('ganado_premios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ganado_id')->constrained('ganados')->onDelete('cascade');
            $table->string('nombre_evento');
            $table->string('titulo_galardon');
            $table->string('ruta_imagen');
            $table->enum('estado_auditoria', ['pendiente', 'aprobado', 'rechazado'])->default('pendiente');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ganado_premios');
    }
};
