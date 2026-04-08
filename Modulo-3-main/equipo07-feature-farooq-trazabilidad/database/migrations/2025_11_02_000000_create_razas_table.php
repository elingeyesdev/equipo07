<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('razas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre')->unique();
            $table->text('descripcion')->nullable();

            // Cada raza pertenece a un tipo de animal
            $table->unsignedBigInteger('tipo_animal_id');

            $table->timestamps();

            $table->foreign('tipo_animal_id')
                  ->references('id')
                  ->on('tipo_animals')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('razas');
    }
};
