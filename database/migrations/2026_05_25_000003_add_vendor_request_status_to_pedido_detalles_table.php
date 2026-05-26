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
            $table->foreignId('vendedor_id')->nullable()->after('pedido_id')->constrained('users')->nullOnDelete();
            $table->string('estado_solicitud')->default('pendiente')->after('vendedor_id');
            $table->timestamp('respondido_at')->nullable()->after('estado_solicitud');
        });

        DB::statement("UPDATE pedido_detalles SET vendedor_id = (SELECT user_id FROM ganados WHERE ganados.id = pedido_detalles.product_id) WHERE product_type = 'ganado'");
        DB::statement("UPDATE pedido_detalles SET vendedor_id = (SELECT user_id FROM maquinarias WHERE maquinarias.id = pedido_detalles.product_id) WHERE product_type = 'maquinaria'");
        DB::statement("UPDATE pedido_detalles SET vendedor_id = (SELECT user_id FROM organicos WHERE organicos.id = pedido_detalles.product_id) WHERE product_type = 'organico'");
    }

    public function down(): void
    {
        Schema::table('pedido_detalles', function (Blueprint $table) {
            $table->dropForeign(['vendedor_id']);
            $table->dropColumn(['vendedor_id', 'estado_solicitud', 'respondido_at']);
        });
    }
};
