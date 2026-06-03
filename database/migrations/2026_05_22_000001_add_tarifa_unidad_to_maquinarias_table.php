<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('maquinarias', function (Blueprint $table) {
            if (! Schema::hasColumn('maquinarias', 'tarifa_unidad')) {
                $table->string('tarifa_unidad', 10)->default('dia')->after('precio_dia');
            }
        });
    }

    public function down(): void
    {
        Schema::table('maquinarias', function (Blueprint $table) {
            if (Schema::hasColumn('maquinarias', 'tarifa_unidad')) {
                $table->dropColumn('tarifa_unidad');
            }
        });
    }
};
