<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ganados', function (Blueprint $table) {
            $table->id();

            // Nombre
            $table->string('nombre');

            // Relaciones
            $table->foreignId('tipo_animal_id')->constrained('tipo_animals')->onDelete('cascade');
            $table->foreignId('raza_id')->nullable()->constrained('razas')->onDelete('set null');
            $table->foreignId('tipo_peso_id')->constrained('tipo_pesos')->onDelete('cascade');
            $table->foreignId('categoria_id')->constrained('categorias')->onDelete('cascade');

            // Edad en meses
            $table->integer('edad')->nullable();

            // Sexo
            $table->enum('sexo', ['Macho', 'Hembra'])->nullable();

            // Detalles
            $table->text('descripcion')->nullable();
            $table->decimal('precio', 10, 2)->nullable();

            // Imagen
            $table->string('imagen')->nullable();

            // Ubicación GPS
            $table->string('ubicacion')->nullable();
            $table->decimal('latitud', 10, 7)->nullable();
            $table->decimal('longitud', 10, 7)->nullable();

            // Fecha publicación
            $table->date('fecha_publicacion')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ganados');
    }
};
