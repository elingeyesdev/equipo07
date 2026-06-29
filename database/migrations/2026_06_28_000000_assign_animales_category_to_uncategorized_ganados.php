<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $categoriaId = DB::table('categorias')
            ->whereRaw('LOWER(nombre) = ?', ['animales'])
            ->value('id');

        if ($categoriaId) {
            DB::table('ganados')
                ->whereNull('categoria_id')
                ->update(['categoria_id' => $categoriaId]);
        }
    }

    public function down(): void
    {
        // La categoría original de los registros reparados no puede inferirse.
    }
};
