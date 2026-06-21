<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pedido_detalles', function (Blueprint $table) {
            $table->longText('firma_transportista')->nullable()->after('recepcion_confirmada_at');
            $table->timestamp('firma_transportista_at')->nullable()->after('firma_transportista');
            $table->longText('firma_comprador')->nullable()->after('firma_transportista_at');
            $table->timestamp('firma_comprador_at')->nullable()->after('firma_comprador');
        });
    }

    public function down(): void
    {
        Schema::table('pedido_detalles', function (Blueprint $table) {
            $table->dropColumn([
                'firma_transportista',
                'firma_transportista_at',
                'firma_comprador',
                'firma_comprador_at',
            ]);
        });
    }
};
