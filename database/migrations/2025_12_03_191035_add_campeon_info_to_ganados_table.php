<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('ganados', function (Blueprint $table) {
            $table->boolean('es_campeon')->default(false)->after('sexo');
            $table->foreignId('madre_id')->nullable()->after('es_campeon')->constrained('ganados')->onDelete('set null');
            $table->foreignId('padre_id')->nullable()->after('madre_id')->constrained('ganados')->onDelete('set null');
            $table->decimal('cantidad_leche_dia', 8, 2)->nullable()->after('padre_id')->comment('Cantidad de leche por día en litros');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ganados', function (Blueprint $table) {
            $table->dropForeign(['madre_id']);
            $table->dropForeign(['padre_id']);
            $table->dropColumn(['es_campeon', 'madre_id', 'padre_id', 'cantidad_leche_dia']);
        });
    }
};
