<?php

use App\Models\Role;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Role::updateOrCreate(
            ['nombre' => 'transportista'],
            ['descripcion' => 'Transportista que gestiona envios asignados']
        );
    }

    public function down(): void
    {
        Role::where('nombre', 'transportista')->delete();
    }
};
