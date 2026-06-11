<?php

namespace Database\Seeders;

use App\Models\Categoria;
use App\Models\EstadoMaquinaria;
use App\Models\Ganado;
use App\Models\GanadoImagen;
use App\Models\Maquinaria;
use App\Models\MaquinariaImagen;
use App\Models\MarcaMaquinaria;
use App\Models\Organico;
use App\Models\OrganicoImagen;
use App\Models\OrganicoTrazabilidad;
use App\Models\Raza;
use App\Models\Role;
use App\Models\TipoAnimal;
use App\Models\TipoCultivo;
use App\Models\TipoMaquinaria;
use App\Models\TipoPeso;
use App\Models\UnidadOrganico;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            TipoCultivoSeeder::class,
        ]);

        $this->seedCatalogos();

        User::query()
            ->where('email', 'like', 'vendedor%@agrovida.test')
            ->delete();

        $rolVendedorId = Role::query()->where('nombre', Role::VENDEDOR)->value('id');

        $vendedores = User::factory()
            ->count(6)
            ->sequence(fn ($sequence) => [
                'name' => 'Vendedor Demo ' . ($sequence->index + 1),
                'email' => 'vendedor' . ($sequence->index + 1) . '@agrovida.test',
                'role_id' => $rolVendedorId,
                'password' => Hash::make('password'),
            ])
            ->create();

        $ganados = Ganado::factory()->count(24)->create();
        $maquinarias = Maquinaria::factory()->count(18)->create();
        $organicos = Organico::factory()->count(20)->create();

        foreach ($ganados as $ganado) {
            GanadoImagen::updateOrCreate(
                ['ganado_id' => $ganado->id, 'orden' => 0],
                ['ruta' => 'demo/ganado.svg']
            );
        }

        foreach ($maquinarias as $maquinaria) {
            MaquinariaImagen::updateOrCreate(
                ['maquinaria_id' => $maquinaria->id, 'orden' => 0],
                ['ruta' => 'demo/maquinaria.svg']
            );
        }

        foreach ($organicos as $organico) {
            OrganicoImagen::updateOrCreate(
                ['organico_id' => $organico->id, 'orden' => 0],
                ['ruta' => 'demo/organico.svg']
            );

            OrganicoTrazabilidad::updateOrCreate(
                ['organico_id' => $organico->id],
                [
                    'origen' => $organico->origen ?? 'Bolivia',
                    'finca' => 'Finca Demo ' . $organico->id,
                    'ubicacion' => collect([
                        $organico->ciudad_origen,
                        $organico->municipio_origen,
                        $organico->departamento_origen,
                    ])->filter()->implode(', ') ?: 'Bolivia',
                    'fecha_siembra' => now()->subDays(rand(45, 180))->format('Y-m-d'),
                    'fecha_cosecha' => $organico->fecha_cosecha ?? now()->format('Y-m-d'),
                    'tratamientos_utilizados' => 'Abono organico, compost y control biologico de plagas.',
                    'certificaciones' => 'Produccion agroecologica local.',
                    'observaciones' => 'Registro generado automaticamente para entorno de prueba.',
                ]
            );
        }

        $this->command?->info('DemoDataSeeder completado.');
        $this->command?->line('Usuarios vendedores creados: ' . $vendedores->count());
        $this->command?->line('Ganados creados: ' . $ganados->count());
        $this->command?->line('Maquinarias creadas: ' . $maquinarias->count());
        $this->command?->line('Organicos creados: ' . $organicos->count());
    }

    private function seedCatalogos(): void
    {
        foreach ([
            ['nombre' => 'Animales', 'descripcion' => 'Categoria para ganado y otros animales.'],
            ['nombre' => 'Maquinarias', 'descripcion' => 'Categoria para equipos y herramientas agricolas.'],
            ['nombre' => 'Organicos', 'descripcion' => 'Categoria para productos organicos y naturales.'],
        ] as $categoria) {
            Categoria::updateOrCreate(['nombre' => $categoria['nombre']], $categoria);
        }

        foreach ([
            ['nombre' => 'Bovino', 'descripcion' => 'Ganado vacuno.'],
            ['nombre' => 'Ovino', 'descripcion' => 'Ganado ovino.'],
            ['nombre' => 'Caprino', 'descripcion' => 'Ganado caprino.'],
            ['nombre' => 'Porcino', 'descripcion' => 'Ganado porcino.'],
            ['nombre' => 'Avicola', 'descripcion' => 'Aves de granja.'],
        ] as $tipoAnimal) {
            TipoAnimal::updateOrCreate(['nombre' => $tipoAnimal['nombre']], $tipoAnimal);
        }

        $razasPorTipo = [
            'Bovino' => ['Holstein', 'Nelore', 'Brahman', 'Gir'],
            'Ovino' => ['Merino', 'Corriedale'],
            'Caprino' => ['Saanen', 'Boer'],
            'Porcino' => ['Landrace', 'Yorkshire'],
            'Avicola' => ['Criolla', 'Plymouth Rock'],
        ];

        foreach ($razasPorTipo as $tipoNombre => $razas) {
            $tipoId = TipoAnimal::query()->where('nombre', $tipoNombre)->value('id');
            foreach ($razas as $raza) {
                Raza::updateOrCreate(
                    ['nombre' => $raza, 'tipo_animal_id' => $tipoId],
                    ['descripcion' => 'Raza demo de ' . strtolower($tipoNombre) . '.']
                );
            }
        }

        foreach ([
            ['nombre' => 'Por unidad', 'descripcion' => 'Venta por animal individual.'],
            ['nombre' => 'Por kilogramo', 'descripcion' => 'Venta basada en peso vivo.'],
            ['nombre' => 'Por lote', 'descripcion' => 'Venta de varios animales juntos.'],
        ] as $tipoPeso) {
            TipoPeso::updateOrCreate(['nombre' => $tipoPeso['nombre']], $tipoPeso);
        }

        foreach ([
            ['nombre' => 'Tractor', 'descripcion' => 'Maquinaria pesada de campo.'],
            ['nombre' => 'Cosechadora', 'descripcion' => 'Equipo para cosecha.'],
            ['nombre' => 'Sembradora', 'descripcion' => 'Equipo para siembra.'],
            ['nombre' => 'Arado', 'descripcion' => 'Implemento de labranza.'],
        ] as $tipoMaquinaria) {
            TipoMaquinaria::updateOrCreate(['nombre' => $tipoMaquinaria['nombre']], $tipoMaquinaria);
        }

        foreach ([
            ['nombre' => 'John Deere', 'descripcion' => 'Marca demo.'],
            ['nombre' => 'Massey Ferguson', 'descripcion' => 'Marca demo.'],
            ['nombre' => 'New Holland', 'descripcion' => 'Marca demo.'],
            ['nombre' => 'Case IH', 'descripcion' => 'Marca demo.'],
        ] as $marca) {
            MarcaMaquinaria::updateOrCreate(['nombre' => $marca['nombre']], $marca);
        }

        foreach ([
            ['nombre' => 'disponible', 'descripcion' => 'Disponible para uso o alquiler.'],
            ['nombre' => 'en_mantenimiento', 'descripcion' => 'En mantenimiento.'],
            ['nombre' => 'dado_baja', 'descripcion' => 'Fuera de servicio.'],
        ] as $estado) {
            EstadoMaquinaria::updateOrCreate(['nombre' => $estado['nombre']], $estado);
        }

        foreach ([
            ['nombre' => 'Kilogramo', 'descripcion' => 'Unidad de peso.'],
            ['nombre' => 'Arroba', 'descripcion' => 'Unidad tradicional de venta.'],
            ['nombre' => 'Caja', 'descripcion' => 'Unidad de empaque.'],
            ['nombre' => 'Saco', 'descripcion' => 'Unidad de almacenamiento.'],
        ] as $unidad) {
            UnidadOrganico::updateOrCreate(['nombre' => $unidad['nombre']], $unidad);
        }

        foreach ([
            ['nombre' => 'Hortalizas', 'descripcion' => 'Cultivos de hortalizas.'],
            ['nombre' => 'Tuberculos', 'descripcion' => 'Cultivos de tuberculos.'],
            ['nombre' => 'Frutales', 'descripcion' => 'Cultivos frutales.'],
            ['nombre' => 'Cereales', 'descripcion' => 'Cultivos de cereal.'],
        ] as $tipoCultivo) {
            TipoCultivo::updateOrCreate(['nombre' => $tipoCultivo['nombre']], $tipoCultivo);
        }
    }
}
