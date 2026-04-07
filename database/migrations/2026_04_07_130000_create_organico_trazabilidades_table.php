<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('organico_trazabilidades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organico_id')
                ->unique()
                ->constrained('organicos')
                ->cascadeOnDelete();
            $table->string('origen');
            $table->string('finca');
            $table->string('ubicacion');
            $table->date('fecha_siembra');
            $table->date('fecha_cosecha');
            $table->text('tratamientos_utilizados');
            $table->text('certificaciones');
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('organico_trazabilidades');
    }
};
