<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pedido_ubicaciones', function (Blueprint $table) {
            $table->foreignId('transporte_acceso_id')
                ->nullable()
                ->after('user_id')
                ->constrained('transporte_accesos')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('pedido_ubicaciones', function (Blueprint $table) {
            $table->dropConstrainedForeignId('transporte_acceso_id');
        });
    }
};
