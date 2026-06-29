<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $categoriaId = DB::table('categorias')
            ->whereRaw('LOWER(nombre) = ?', ['organicos'])
            ->value('id');

        if ($categoriaId) {
            DB::table('organicos')->update(['categoria_id' => $categoriaId]);
        }
    }

    public function down(): void
    {
        // La categoría anterior de cada producto no puede inferirse.
    }
};
