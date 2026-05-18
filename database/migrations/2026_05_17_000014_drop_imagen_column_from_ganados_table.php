<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ganados', function (Blueprint $table) {
            if (Schema::hasColumn('ganados', 'imagen')) {
                $table->dropColumn('imagen');
            }
        });
    }

    public function down(): void
    {
        Schema::table('ganados', function (Blueprint $table) {
            if (!Schema::hasColumn('ganados', 'imagen')) {
                $table->string('imagen')->nullable();
            }
        });
    }
};
