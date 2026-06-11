<?php

namespace Database\Seeders;

use App\Models\Ganado;
use App\Models\Maquinaria;
use App\Models\Organico;
use Illuminate\Database\Seeder;

class DemoImageSeeder extends Seeder
{
    public function run(): void
    {
        Ganado::query()
            ->doesntHave('imagenes')
            ->each(fn (Ganado $ganado) => $ganado->imagenes()->create([
                'ruta' => 'demo/ganado.svg',
                'orden' => 0,
            ]));

        Maquinaria::query()
            ->doesntHave('imagenes')
            ->each(fn (Maquinaria $maquinaria) => $maquinaria->imagenes()->create([
                'ruta' => 'demo/maquinaria.svg',
                'orden' => 0,
            ]));

        Organico::query()
            ->doesntHave('imagenes')
            ->each(fn (Organico $organico) => $organico->imagenes()->create([
                'ruta' => 'demo/organico.svg',
                'orden' => 0,
            ]));

        $this->command?->info('Portadas demo asociadas correctamente.');
    }
}
