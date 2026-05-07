<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // No-op: esta migración no puede renombrar columnas con nombres idénticos
        // sin arriesgar errores en el motor de BD durante migrate/rollback.
        return;
    }

    public function down(): void
    {
        // No-op por simetría con up().
        return;
    }
};
