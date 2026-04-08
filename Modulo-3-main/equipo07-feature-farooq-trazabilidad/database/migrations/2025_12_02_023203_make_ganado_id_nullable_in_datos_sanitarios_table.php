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
        Schema::table('datos_sanitarios', function (Blueprint $table) {
            $table->dropForeign(['ganado_id']);
            
            $table->unsignedBigInteger('ganado_id')->nullable()->change();
            
            $table->foreign('ganado_id')
                  ->references('id')
                  ->on('ganados')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('datos_sanitarios', function (Blueprint $table) {
            $table->dropForeign(['ganado_id']);
            
            $table->unsignedBigInteger('ganado_id')->nullable(false)->change();
            
            $table->foreign('ganado_id')
                  ->references('id')
                  ->on('ganados')
                  ->onDelete('cascade');
        });
    }
};
