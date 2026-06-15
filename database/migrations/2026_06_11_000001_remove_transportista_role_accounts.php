<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $transportistaRoleId = DB::table('roles')
            ->where('nombre', 'transportista')
            ->value('id');

        if (!$transportistaRoleId) {
            return;
        }

        $transportistaIds = DB::table('users')
            ->where('role_id', $transportistaRoleId)
            ->pluck('id');

        if ($transportistaIds->isNotEmpty()) {
            DB::table('pedido_detalles')
                ->whereIn('transportista_id', $transportistaIds)
                ->update(['transportista_id' => null]);

            DB::table('users')
                ->whereIn('id', $transportistaIds)
                ->delete();
        }

        DB::table('roles')
            ->where('id', $transportistaRoleId)
            ->delete();
    }

    public function down(): void
    {
        DB::table('roles')->updateOrInsert(
            ['nombre' => 'transportista'],
            ['descripcion' => 'Transportista que gestiona envios asignados']
        );
    }
};
