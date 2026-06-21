<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('datos_sanitarios', function (Blueprint $table) {
            $table->string('estado_revision_certificado')->default('pendiente')->after('documento_pdf');
            $table->text('motivo_rechazo_certificado')->nullable()->after('estado_revision_certificado');
            $table->timestamp('revisado_at')->nullable()->after('motivo_rechazo_certificado');
            $table->foreignId('revisado_por_id')->nullable()->after('revisado_at')->constrained('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('datos_sanitarios', function (Blueprint $table) {
            $table->dropConstrainedForeignId('revisado_por_id');
            $table->dropColumn([
                'estado_revision_certificado',
                'motivo_rechazo_certificado',
                'revisado_at',
            ]);
        });
    }
};
