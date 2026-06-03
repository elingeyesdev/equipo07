<?php

namespace Database\Factories;

use App\Models\Categoria;
use App\Models\EstadoMaquinaria;
use App\Models\Maquinaria;
use App\Models\MarcaMaquinaria;
use App\Models\TipoMaquinaria;
use App\Models\UbicacionGeograficaMaquinaria;
use App\Models\UbicacionMaquinaria;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Maquinaria>
 */
class MaquinariaFactory extends Factory
{
    protected $model = Maquinaria::class;

    public function configure(): static
    {
        return $this->afterCreating(function (Maquinaria $maquinaria) {
            $ubicacionGeografica = UbicacionGeograficaMaquinaria::firstOrCreate([
                'departamento' => fake()->randomElement(['La Paz', 'Santa Cruz', 'Cochabamba', 'Tarija', 'Beni']),
                'municipio' => fake()->city(),
                'provincia' => fake()->lastName(),
                'ciudad' => fake()->city(),
            ]);

            $ubicacion = UbicacionMaquinaria::create([
                'ubicacion' => fake()->city() . ', Bolivia',
                'latitud' => fake()->randomFloat(7, -22.9, -9.6),
                'longitud' => fake()->randomFloat(7, -69.7, -57.4),
                'ubicacion_geografica_maquinaria_id' => $ubicacionGeografica->id,
            ]);

            $maquinaria->update(['ubicacion_maquinaria_id' => $ubicacion->id]);
        });
    }

    public function definition(): array
    {
        $tipoNombre = TipoMaquinaria::query()->inRandomOrder()->value('nombre') ?? 'Tractor';
        $marcaNombre = MarcaMaquinaria::query()->inRandomOrder()->value('nombre') ?? 'John Deere';
        $estadoNombre = EstadoMaquinaria::query()->inRandomOrder()->value('nombre') ?? 'disponible';

        return [
            'nombre' => $tipoNombre . ' ' . fake()->randomElement(['Serie', 'Modelo', 'Pro', 'Max']) . ' ' . fake()->numberBetween(100, 999),
            'user_id' => User::query()->inRandomOrder()->value('id'),
            'tipo_maquinaria_id' => TipoMaquinaria::query()->where('nombre', $tipoNombre)->value('id'),
            'marca_maquinaria_id' => MarcaMaquinaria::query()->where('nombre', $marcaNombre)->value('id'),
            'modelo' => strtoupper(fake()->bothify('??-###')),
            'telefono' => fake()->numerify('7#######'),
            'precio_dia' => fake()->randomFloat(2, 150, 3500),
            'tarifa_unidad' => fake()->randomElement(['hora', 'dia']),
            'estado_maquinaria_id' => EstadoMaquinaria::query()->where('nombre', $estadoNombre)->value('id'),
            'descripcion' => fake()->sentence(18),
            'categoria_id' => Categoria::query()->where('nombre', 'like', '%maquinaria%')->value('id')
                ?? Categoria::query()->inRandomOrder()->value('id'),
        ];
    }
}
