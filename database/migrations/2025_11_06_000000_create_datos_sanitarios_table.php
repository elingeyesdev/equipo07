<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::create('datos_sanitarios', function (Blueprint $table) {
        $table->id();

        $table->unsignedBigInteger('ganado_id');
        $table->foreign('ganado_id')
              ->references('id')
              ->on('ganados')
              ->onDelete('cascade');

        $table->string('vacuna')->nullable();
        $table->string('tratamiento')->nullable();
        $table->string('medicamento')->nullable();
        $table->date('fecha_aplicacion')->nullable();
        $table->date('proxima_fecha')->nullable();
        $table->string('veterinario')->nullable();
        $table->text('observaciones')->nullable();

        $table->timestamps();
    });
}


    public function down(): void
    {
        Schema::dropIfExists('datos_sanitarios');
    }
};
