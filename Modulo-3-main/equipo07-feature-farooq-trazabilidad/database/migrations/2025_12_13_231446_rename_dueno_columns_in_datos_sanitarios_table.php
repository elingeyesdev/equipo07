<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (
            Schema::hasColumn('datos_sanitarios', 'nombre_dueno') &&
            Schema::hasColumn('datos_sanitarios', 'carnet_dueno_foto')
        ) {
            return;
        }

        if (Schema::hasColumn('datos_sanitarios', 'nombre_dueno')) {
            Schema::table('datos_sanitarios', function (Blueprint $table) {
                $table->renameColumn('nombre_dueno', 'nombre_dueno');
            });
        }

        if (Schema::hasColumn('datos_sanitarios', 'carnet_dueno_foto')) {
            Schema::table('datos_sanitarios', function (Blueprint $table) {
                $table->renameColumn('carnet_dueno_foto', 'carnet_dueno_foto');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('datos_sanitarios', 'nombre_dueno')) {
            Schema::table('datos_sanitarios', function (Blueprint $table) {
                $table->renameColumn('nombre_dueno', 'nombre_dueno');
            });
        }

        if (Schema::hasColumn('datos_sanitarios', 'carnet_dueno_foto')) {
            Schema::table('datos_sanitarios', function (Blueprint $table) {
                $table->renameColumn('carnet_dueno_foto', 'carnet_dueno_foto');
            });
        }
    }
};
