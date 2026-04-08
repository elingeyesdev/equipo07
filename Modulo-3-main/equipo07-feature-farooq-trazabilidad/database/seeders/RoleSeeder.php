<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'nombre' => 'admin',
                'descripcion' => 'Administrador con control total del sistema',
            ],
            [
                'nombre' => 'vendedor',
                'descripcion' => 'Vendedor que puede publicar productos',
            ],
            [
                'nombre' => 'cliente',
                'descripcion' => 'Cliente que puede ver productos y solicitar ser vendedor',
            ],
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(
                ['nombre' => $role['nombre']],
                $role
            );
        }

        $this->command->info('✅ Roles creados exitosamente!');

        $clienteRole = Role::where('nombre', 'cliente')->first();
        if ($clienteRole) {
            DB::table('users')
                ->whereNull('role_id')
                ->update(['role_id' => $clienteRole->id]);
            $this->command->info('✅ Usuarios sin rol asignado ahora tienen rol cliente por defecto!');
        }
    }
}
