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
        Schema::create('ganado_documentos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ganado_id')->constrained('ganados')->onDelete('cascade');
            $table->string('tipo_documento');
            $table->string('ruta');
            $table->text('descripcion')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ganado_documentos');
    }
};
