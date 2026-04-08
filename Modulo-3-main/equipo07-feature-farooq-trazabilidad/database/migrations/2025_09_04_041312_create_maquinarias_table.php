<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('maquinarias', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('tipo');
            $table->string('marca');
            $table->string('modelo')->nullable();
            $table->decimal('precio_dia', 10, 2)->default(0);
            $table->string('estado')->default('disponible');
            $table->text('descripcion')->nullable();

            // 🔥 PARAMETRIZACIÓN: Relación con categorías
            $table->foreignId('categoria_id')->constrained('categorias')->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('maquinarias');
    }
};
