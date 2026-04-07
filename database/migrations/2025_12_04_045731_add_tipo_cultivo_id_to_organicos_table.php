<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('organicos', function (Blueprint $table) {
            $table->unsignedBigInteger('tipo_cultivo_id')->nullable()->after('categoria_id');

            $table->foreign('tipo_cultivo_id')
                  ->references('id')
                  ->on('tipo_cultivos')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('organicos', function (Blueprint $table) {
            $table->dropForeign(['tipo_cultivo_id']);
            $table->dropColumn('tipo_cultivo_id');
        });
    }
};
