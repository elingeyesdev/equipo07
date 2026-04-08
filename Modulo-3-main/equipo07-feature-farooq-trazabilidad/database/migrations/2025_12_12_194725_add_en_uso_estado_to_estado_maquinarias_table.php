<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\EstadoMaquinaria;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        EstadoMaquinaria::firstOrCreate(
            ['nombre' => 'en_uso'],
            ['descripcion' => 'Maquinaria actualmente en uso']
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        EstadoMaquinaria::where('nombre', 'en_uso')->delete();
    }
};
