<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('datos_comerciales_organicos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organico_id')->unique()->constrained('organicos')->onDelete('cascade');
            $table->foreignId('unidad_id')->nullable()->constrained('unidades_organicos')->onDelete('set null');
            $table->decimal('precio', 10, 2)->nullable();
            $table->integer('stock')->default(0);
            $table->timestamps();
        });

        DB::table('organicos')
            ->select('id', 'unidad_id', 'precio', 'stock')
            ->orderBy('id')
            ->chunk(100, function ($organicos) {
                $now = now();

                foreach ($organicos as $organico) {
                    DB::table('datos_comerciales_organicos')->insert([
                        'organico_id' => $organico->id,
                        'unidad_id' => $organico->unidad_id,
                        'precio' => $organico->precio,
                        'stock' => $organico->stock ?? 0,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);
                }
            });
    }

    public function down(): void
    {
        Schema::dropIfExists('datos_comerciales_organicos');
    }
};
