<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ubicacion_ganado', function (Blueprint $table) {
            $table->id();
            $table->string('ubicacion')->nullable();
            $table->decimal('latitud', 10, 7)->nullable();
            $table->decimal('longitud', 10, 7)->nullable();
            $table->foreignId('ubicacion_geografica_ganado_id')
                ->nullable()
                ->constrained('ubicacion_geografica_ganados')
                ->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ubicacion_ganado');
    }
};
