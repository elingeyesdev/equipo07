<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ubicacion_geografica_maquinarias', function (Blueprint $table) {
            $table->id();
            $table->string('departamento')->nullable();
            $table->string('municipio')->nullable();
            $table->string('provincia')->nullable();
            $table->string('ciudad')->nullable();
            $table->timestamps();

            $table->unique(
                ['departamento', 'municipio', 'provincia', 'ciudad'],
                'ubicacion_geo_maq_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ubicacion_geografica_maquinarias');
    }
};
