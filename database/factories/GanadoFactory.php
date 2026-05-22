<?php

namespace Database\Factories;

use App\Models\Categoria;
use App\Models\DatoComercialGanado;
use App\Models\DatoProductivoGanado;
use App\Models\Ganado;
use App\Models\CaracteristicaGanado;
use App\Models\Raza;
use App\Models\TipoAnimal;
use App\Models\TipoPeso;
use App\Models\UbicacionGanado;
use App\Models\UbicacionGeograficaGanado;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ganado>
 */
class GanadoFactory extends Factory
{
    protected $model = Ganado::class;

    public function configure(): static
    {
        return $this->afterCreating(function (Ganado $ganado) {
            $sexo = fake()->randomElement(['Macho', 'Hembra']);
            $esLechero = $sexo === 'Hembra' && fake()->boolean(45);
            $departamento = fake()->randomElement(['La Paz', 'Santa Cruz', 'Cochabamba', 'Tarija', 'Chuquisaca']);
            $municipio = fake()->city();
            $provincia = fake()->lastName();
            $ciudad = fake()->city();

            CaracteristicaGanado::create([
                'ganado_id' => $ganado->id,
                'edad' => fake()->numberBetween(6, 96),
                'sexo' => $sexo,
                'descripcion' => fake()->sentence(16),
            ]);

            DatoProductivoGanado::create([
                'ganado_id' => $ganado->id,
                'tipo_peso_id' => TipoPeso::query()->inRandomOrder()->value('id'),
                'peso_actual' => fake()->randomFloat(2, 40, 950),
                'cantidad_leche_dia' => $esLechero ? fake()->randomFloat(2, 4, 28) : null,
            ]);

            DatoComercialGanado::create([
                'ganado_id' => $ganado->id,
                'precio' => fake()->randomFloat(2, 800, 18000),
                'stock' => fake()->numberBetween(1, 18),
                'fecha_publicacion' => fake()->dateTimeBetween('-8 months', 'now')->format('Y-m-d'),
            ]);

            $ubicacionGeografica = UbicacionGeograficaGanado::firstOrCreate([
                'departamento' => $departamento,
                'municipio' => $municipio,
                'provincia' => $provincia,
                'ciudad' => $ciudad,
            ]);

            $ubicacion = UbicacionGanado::create([
                'ubicacion' => fake()->city() . ', Bolivia',
                'latitud' => fake()->randomFloat(7, -22.9, -9.6),
                'longitud' => fake()->randomFloat(7, -69.7, -57.4),
                'ubicacion_geografica_ganado_id' => $ubicacionGeografica->id,
            ]);

            $ganado->update(['ubicacion_ganado_id' => $ubicacion->id]);
        });
    }

    public function definition(): array
    {
        $tipoAnimalId = TipoAnimal::query()->inRandomOrder()->value('id');
        $razaId = $tipoAnimalId
            ? Raza::query()->where('tipo_animal_id', $tipoAnimalId)->inRandomOrder()->value('id')
            : null;

        return [
            'nombre' => fake()->randomElement([
                'Toro',
                'Vaca',
                'Novillo',
                'Ternera',
                'Becerro',
                'Cabra',
                'Oveja',
                'Cerdo',
                'Gallina',
                'Caballo',
            ]) . ' ' . fake()->unique()->numberBetween(100, 999),
            'user_id' => User::query()->inRandomOrder()->value('id'),
            'tipo_animal_id' => $tipoAnimalId,
            'raza_id' => $razaId,
            'categoria_id' => Categoria::query()->where('nombre', 'like', '%animal%')->value('id')
                ?? Categoria::query()->inRandomOrder()->value('id'),
            'es_campeon' => fake()->boolean(12),
        ];
    }
}
