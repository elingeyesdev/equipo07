<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('datos_sanitarios', function (Blueprint $table) {

            if (!Schema::hasColumn('datos_sanitarios', 'nombre_dueno')) {
                $table->string('nombre_dueno')->nullable();
            }

            if (!Schema::hasColumn('datos_sanitarios', 'carnet_dueno_foto')) {
                $table->string('carnet_dueno_foto')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('datos_sanitarios', function (Blueprint $table) {

            if (Schema::hasColumn('datos_sanitarios', 'carnet_dueno_foto')) {
                $table->dropColumn('carnet_dueno_foto');
            }

            if (Schema::hasColumn('datos_sanitarios', 'nombre_dueno')) {
                $table->dropColumn('nombre_dueno');
            }
        });
    }
};
