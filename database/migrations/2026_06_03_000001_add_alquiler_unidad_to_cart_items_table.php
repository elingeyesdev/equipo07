<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cart_items', function (Blueprint $table) {
            if (! Schema::hasColumn('cart_items', 'alquiler_unidad')) {
                $table->string('alquiler_unidad', 10)->nullable()->after('cantidad');
            }
        });
    }

    public function down(): void
    {
        Schema::table('cart_items', function (Blueprint $table) {
            if (Schema::hasColumn('cart_items', 'alquiler_unidad')) {
                $table->dropColumn('alquiler_unidad');
            }
        });
    }
};
