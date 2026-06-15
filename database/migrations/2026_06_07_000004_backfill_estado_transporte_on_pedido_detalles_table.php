<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('pedido_detalles')
            ->where('estado_solicitud', 'aceptada')
            ->whereNull('estado_transporte')
            ->update(['estado_transporte' => 'asignado']);
    }

    public function down(): void
    {
        DB::table('pedido_detalles')
            ->where('estado_transporte', 'asignado')
            ->update(['estado_transporte' => null]);
    }
};
