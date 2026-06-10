<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transporte_accesos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pedido_detalle_id')
                ->unique()
                ->constrained('pedido_detalles')
                ->cascadeOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('codigo_hash', 64)->unique();
            $table->text('codigo_cifrado');
            $table->string('estado', 20)->default('activo');
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('last_access_at')->nullable();
            $table->timestamps();

            $table->index(['estado', 'expires_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transporte_accesos');
    }
};
