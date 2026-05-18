<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('maquinarias', function (Blueprint $table) {
            $table->dropColumn([
                'ubicacion',
                'latitud',
                'longitud',
                'departamento',
                'municipio',
                'provincia',
                'ciudad',
            ]);
        });
    }

    public function down(): void
    {
        Schema::table('maquinarias', function (Blueprint $table) {
            $table->string('ubicacion')->nullable()->after('descripcion');
            $table->decimal('latitud', 10, 7)->nullable()->after('ubicacion');
            $table->decimal('longitud', 10, 7)->nullable()->after('latitud');
            $table->string('departamento')->nullable()->after('ubicacion');
            $table->string('municipio')->nullable()->after('departamento');
            $table->string('provincia')->nullable()->after('municipio');
            $table->string('ciudad')->nullable()->after('provincia');
        });
    }
};
