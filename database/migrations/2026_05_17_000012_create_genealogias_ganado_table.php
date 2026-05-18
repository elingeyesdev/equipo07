<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('genealogias_ganado', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ganado_id')->unique()->constrained('ganados')->onDelete('cascade');
            $table->foreignId('madre_id')->nullable()->constrained('ganados')->onDelete('set null');
            $table->foreignId('padre_id')->nullable()->constrained('ganados')->onDelete('set null');
            $table->timestamps();
        });

        DB::table('ganados')
            ->select('id', 'madre_id', 'padre_id')
            ->orderBy('id')
            ->chunk(100, function ($ganados) {
                $now = now();

                foreach ($ganados as $ganado) {
                    DB::table('genealogias_ganado')->insert([
                        'ganado_id' => $ganado->id,
                        'madre_id' => $ganado->madre_id,
                        'padre_id' => $ganado->padre_id,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);
                }
            });
    }

    public function down(): void
    {
        Schema::dropIfExists('genealogias_ganado');
    }
};
