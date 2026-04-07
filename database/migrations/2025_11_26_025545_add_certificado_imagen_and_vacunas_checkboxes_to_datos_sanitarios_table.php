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
            $table->string('certificado_imagen')->nullable()->after('observaciones');
            $table->boolean('vacunado_fiebre_aftosa')->default(false)->after('vacuna');
            $table->boolean('vacunado_antirabica')->default(false)->after('vacunado_fiebre_aftosa');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('datos_sanitarios', function (Blueprint $table) {
            $table->dropColumn(['certificado_imagen', 'vacunado_fiebre_aftosa', 'vacunado_antirabica']);
        });
    }
};
