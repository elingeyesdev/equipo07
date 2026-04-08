<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('datos_sanitarios', function (Blueprint $table) {
            $table->string('certificado_campeon_imagen')->nullable()->after('certificado_imagen')->comment('Imagen del certificado de campeón');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('datos_sanitarios', function (Blueprint $table) {
            $table->dropColumn('certificado_campeon_imagen');
        });
    }
};
