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
        Schema::table('organicos', function (Blueprint $table) {
            $table->string('origen')->nullable()->after('descripcion');
            $table->decimal('latitud_origen', 10, 7)->nullable()->after('origen');
            $table->decimal('longitud_origen', 10, 7)->nullable()->after('latitud_origen');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('organicos', function (Blueprint $table) {
            $table->dropColumn(['origen', 'latitud_origen', 'longitud_origen']);
        });
    }
};
