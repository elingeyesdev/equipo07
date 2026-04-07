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
        Schema::table('ganados', function (Blueprint $table) {
            $table->string('departamento')->nullable()->after('ubicacion');
            $table->string('municipio')->nullable()->after('departamento');
            $table->string('provincia')->nullable()->after('municipio');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ganados', function (Blueprint $table) {
            $table->dropColumn(['departamento', 'municipio', 'provincia']);
        });
    }
};
