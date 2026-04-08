<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('organicos', function (Blueprint $table) {
            $table->string('departamento_origen')->nullable()->after('longitud_origen');
            $table->string('municipio_origen')->nullable()->after('departamento_origen');
            $table->string('provincia_origen')->nullable()->after('municipio_origen');
            $table->string('ciudad_origen')->nullable()->after('provincia_origen');
        });
    }

    public function down(): void
    {
        Schema::table('organicos', function (Blueprint $table) {
            $table->dropColumn([
                'departamento_origen',
                'municipio_origen',
                'provincia_origen',
                'ciudad_origen',
            ]);
        });
    }
};
