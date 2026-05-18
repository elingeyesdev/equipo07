<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ganados', function (Blueprint $table) {
            if (Schema::hasColumn('ganados', 'tipo_peso_id')) {
                $table->dropForeign(['tipo_peso_id']);
            }

            if (Schema::hasColumn('ganados', 'madre_id')) {
                $table->dropForeign(['madre_id']);
            }

            if (Schema::hasColumn('ganados', 'padre_id')) {
                $table->dropForeign(['padre_id']);
            }
        });

        Schema::table('ganados', function (Blueprint $table) {
            $columnas = [
                'tipo_peso_id',
                'peso_actual',
                'cantidad_leche_dia',
                'precio',
                'stock',
                'fecha_publicacion',
                'edad',
                'sexo',
                'descripcion',
                'madre_id',
                'padre_id',
            ];

            $existentes = array_values(array_filter(
                $columnas,
                fn ($columna) => Schema::hasColumn('ganados', $columna)
            ));

            if ($existentes) {
                $table->dropColumn($existentes);
            }
        });
    }

    public function down(): void
    {
        Schema::table('ganados', function (Blueprint $table) {
            if (!Schema::hasColumn('ganados', 'tipo_peso_id')) {
                $table->foreignId('tipo_peso_id')->nullable()->constrained('tipo_pesos')->nullOnDelete();
            }

            if (!Schema::hasColumn('ganados', 'peso_actual')) {
                $table->decimal('peso_actual', 10, 2)->nullable();
            }

            if (!Schema::hasColumn('ganados', 'cantidad_leche_dia')) {
                $table->decimal('cantidad_leche_dia', 8, 2)->nullable();
            }

            if (!Schema::hasColumn('ganados', 'precio')) {
                $table->decimal('precio', 10, 2)->nullable();
            }

            if (!Schema::hasColumn('ganados', 'stock')) {
                $table->integer('stock')->default(0);
            }

            if (!Schema::hasColumn('ganados', 'fecha_publicacion')) {
                $table->date('fecha_publicacion')->nullable();
            }

            if (!Schema::hasColumn('ganados', 'edad')) {
                $table->integer('edad')->nullable();
            }

            if (!Schema::hasColumn('ganados', 'sexo')) {
                $table->enum('sexo', ['Macho', 'Hembra'])->nullable();
            }

            if (!Schema::hasColumn('ganados', 'descripcion')) {
                $table->text('descripcion')->nullable();
            }

            if (!Schema::hasColumn('ganados', 'madre_id')) {
                $table->foreignId('madre_id')->nullable()->constrained('ganados')->nullOnDelete();
            }

            if (!Schema::hasColumn('ganados', 'padre_id')) {
                $table->foreignId('padre_id')->nullable()->constrained('ganados')->nullOnDelete();
            }
        });

        DB::statement("
            UPDATE ganados
            SET
                tipo_peso_id = datos_productivos_ganado.tipo_peso_id,
                peso_actual = datos_productivos_ganado.peso_actual,
                cantidad_leche_dia = datos_productivos_ganado.cantidad_leche_dia
            FROM datos_productivos_ganado
            WHERE datos_productivos_ganado.ganado_id = ganados.id
        ");

        DB::statement("
            UPDATE ganados
            SET
                precio = datos_comerciales_ganado.precio,
                stock = datos_comerciales_ganado.stock,
                fecha_publicacion = datos_comerciales_ganado.fecha_publicacion
            FROM datos_comerciales_ganado
            WHERE datos_comerciales_ganado.ganado_id = ganados.id
        ");

        DB::statement("
            UPDATE ganados
            SET
                edad = caracteristicas_ganado.edad,
                sexo = caracteristicas_ganado.sexo,
                descripcion = caracteristicas_ganado.descripcion
            FROM caracteristicas_ganado
            WHERE caracteristicas_ganado.ganado_id = ganados.id
        ");

        DB::statement("
            UPDATE ganados
            SET
                madre_id = genealogias_ganado.madre_id,
                padre_id = genealogias_ganado.padre_id
            FROM genealogias_ganado
            WHERE genealogias_ganado.ganado_id = ganados.id
        ");
    }
};
