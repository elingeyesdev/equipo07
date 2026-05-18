<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('organicos', function (Blueprint $table) {
            if (Schema::hasColumn('organicos', 'unidad_id')) {
                $table->dropForeign(['unidad_id']);
            }
        });

        Schema::table('organicos', function (Blueprint $table) {
            $columnas = [
                'precio',
                'stock',
                'unidad_id',
                'origen',
                'latitud_origen',
                'longitud_origen',
            ];

            $existentes = array_values(array_filter(
                $columnas,
                fn ($columna) => Schema::hasColumn('organicos', $columna)
            ));

            if ($existentes) {
                $table->dropColumn($existentes);
            }
        });
    }

    public function down(): void
    {
        Schema::table('organicos', function (Blueprint $table) {
            if (!Schema::hasColumn('organicos', 'unidad_id')) {
                $table->foreignId('unidad_id')->nullable()->constrained('unidades_organicos')->onDelete('set null');
            }

            if (!Schema::hasColumn('organicos', 'precio')) {
                $table->decimal('precio', 10, 2)->default(0);
            }

            if (!Schema::hasColumn('organicos', 'stock')) {
                $table->integer('stock')->default(0);
            }

            if (!Schema::hasColumn('organicos', 'origen')) {
                $table->string('origen')->nullable();
            }

            if (!Schema::hasColumn('organicos', 'latitud_origen')) {
                $table->decimal('latitud_origen', 10, 7)->nullable();
            }

            if (!Schema::hasColumn('organicos', 'longitud_origen')) {
                $table->decimal('longitud_origen', 10, 7)->nullable();
            }
        });

        DB::statement("
            UPDATE organicos
            SET
                unidad_id = datos_comerciales_organicos.unidad_id,
                precio = COALESCE(datos_comerciales_organicos.precio, 0),
                stock = datos_comerciales_organicos.stock
            FROM datos_comerciales_organicos
            WHERE datos_comerciales_organicos.organico_id = organicos.id
        ");

        DB::statement("
            UPDATE organicos
            SET
                origen = ubicacion_organico.ubicacion,
                latitud_origen = ubicacion_organico.latitud,
                longitud_origen = ubicacion_organico.longitud
            FROM ubicacion_organico
            WHERE ubicacion_organico.id = organicos.ubicacion_organico_id
        ");
    }
};
