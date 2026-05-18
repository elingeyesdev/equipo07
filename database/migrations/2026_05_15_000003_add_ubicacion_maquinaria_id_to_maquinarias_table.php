<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('maquinarias', function (Blueprint $table) {
            $table->foreignId('ubicacion_maquinaria_id')
                ->nullable()
                ->after('estado_maquinaria_id')
                ->constrained('ubicacion_maquinaria')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('maquinarias', function (Blueprint $table) {
            $table->dropForeign(['ubicacion_maquinaria_id']);
            $table->dropColumn('ubicacion_maquinaria_id');
        });
    }
};
