<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('resenas_organicos', function (Blueprint $table) {
            $table->string('product_type', 30)->nullable()->after('vendedor_id');
            $table->unsignedBigInteger('product_id')->nullable()->after('product_type');
            $table->index(['product_type', 'product_id'], 'resenas_producto_index');
        });

        DB::table('resenas_organicos')->update([
            'product_type' => 'organico',
            'product_id' => DB::raw('organico_id'),
        ]);

        Schema::table('resenas_organicos', function (Blueprint $table) {
            $table->dropForeign(['organico_id']);
            $table->unsignedBigInteger('organico_id')->nullable()->change();
            $table->foreign('organico_id')->references('id')->on('organicos')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('resenas_organicos', function (Blueprint $table) {
            $table->dropForeign(['organico_id']);
        });

        DB::table('resenas_organicos')->whereNull('organico_id')->delete();

        Schema::table('resenas_organicos', function (Blueprint $table) {
            $table->unsignedBigInteger('organico_id')->nullable(false)->change();
            $table->foreign('organico_id')->references('id')->on('organicos')->cascadeOnDelete();
            $table->dropIndex('resenas_producto_index');
            $table->dropColumn(['product_type', 'product_id']);
        });
    }
};
