<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('caracteristicas_ganado', function (Blueprint $table) {
            if (! Schema::hasColumn('caracteristicas_ganado', 'fecha_nacimiento')) {
                $table->date('fecha_nacimiento')->nullable()->after('unidad_edad');
            }
        });
    }

    public function down(): void
    {
        Schema::table('caracteristicas_ganado', function (Blueprint $table) {
            if (Schema::hasColumn('caracteristicas_ganado', 'fecha_nacimiento')) {
                $table->dropColumn('fecha_nacimiento');
            }
        });
    }
};
