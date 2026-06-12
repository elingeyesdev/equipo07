<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pedido_detalles', function (Blueprint $table) {
            $table->uuid('grupo_envio')->nullable()->after('pedido_id')->index();
            $table->string('origen_direccion', 500)->nullable()->after('grupo_envio');
            $table->decimal('origen_latitud', 10, 8)->nullable()->after('origen_direccion');
            $table->decimal('origen_longitud', 11, 8)->nullable()->after('origen_latitud');
        });

        Schema::table('transporte_accesos', function (Blueprint $table) {
            $table->uuid('grupo_envio')->nullable()->after('pedido_detalle_id')->unique();
        });

        DB::table('pedido_detalles')
            ->orderBy('id')
            ->each(function ($detalle) {
                $grupo = sprintf(
                    '%08x-%04x-4000-8000-%012x',
                    0,
                    0,
                    (int) $detalle->id
                );

                DB::table('pedido_detalles')
                    ->where('id', $detalle->id)
                    ->update(['grupo_envio' => $grupo]);

                DB::table('transporte_accesos')
                    ->where('pedido_detalle_id', $detalle->id)
                    ->update(['grupo_envio' => $grupo]);
            });
    }

    public function down(): void
    {
        Schema::table('transporte_accesos', function (Blueprint $table) {
            $table->dropUnique(['grupo_envio']);
            $table->dropColumn('grupo_envio');
        });

        Schema::table('pedido_detalles', function (Blueprint $table) {
            $table->dropIndex(['grupo_envio']);
            $table->dropColumn([
                'grupo_envio',
                'origen_direccion',
                'origen_latitud',
                'origen_longitud',
            ]);
        });
    }
};
