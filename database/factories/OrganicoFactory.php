<?php

namespace Database\Factories;

use App\Models\Categoria;
use App\Models\Organico;
use App\Models\TipoCultivo;
use App\Models\UnidadOrganico;
use App\Models\User;
use App\Models\CertificadoOrganico;
use App\Models\DatoComercialOrganico;
use App\Models\OrganicoCertificado;
use App\Models\UbicacionOrganicoUnificada;
use App\Models\UbicacionOrganico;
use App\Models\UbicacionGeograficaOrganico;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Organico>
 */
class OrganicoFactory extends Factory
{
    protected $model = Organico::class;

    public function definition(): array
    {
        $fechaCosecha = fake()->dateTimeBetween('-3 months', '+2 months');

        return [
            'nombre' => fake()->randomElement([
                'Papa organica',
                'Tomate organico',
                'Lechuga fresca',
                'Quinua premium',
                'Zanahoria natural',
                'Acelga verde',
                'Manzana campesina',
                'Frutilla de valle',
                'Pepino agroecologico',
                'Cebolla roja natural',
            ]) . ' ' . fake()->numberBetween(10, 999),
            'user_id' => User::query()->inRandomOrder()->value('id'),
            'categoria_id' => Categoria::query()->where('nombre', 'like', '%org%')->value('id')
                ?? Categoria::query()->inRandomOrder()->value('id'),
            'fecha_cosecha' => $fechaCosecha->format('Y-m-d'),
            'descripcion' => fake()->sentence(16),
            'tipo_cultivo_id' => TipoCultivo::query()->inRandomOrder()->value('id'),
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Organico $organico) {
            DatoComercialOrganico::create([
                'organico_id' => $organico->id,
                'unidad_id' => UnidadOrganico::query()->inRandomOrder()->value('id'),
                'precio' => fake()->randomFloat(2, 5, 220),
                'stock' => fake()->numberBetween(10, 250),
            ]);

            $geografica = UbicacionGeograficaOrganico::firstOrCreate([
                'departamento' => fake()->randomElement(['La Paz', 'Santa Cruz', 'Cochabamba', 'Oruro', 'Potosi']),
                'municipio' => fake()->city(),
                'provincia' => fake()->lastName(),
                'ciudad' => fake()->city(),
            ]);

            $ubicacion = UbicacionOrganico::create([
                'ubicacion' => fake()->city() . ', Bolivia',
                'latitud' => fake()->randomFloat(7, -22.9, -9.6),
                'longitud' => fake()->randomFloat(7, -69.7, -57.4),
                'ubicacion_geografica_organico_id' => $geografica->id,
            ]);

            $organico->ubicacion_organico_id = $ubicacion->id;
            $organico->save();

            UbicacionOrganicoUnificada::updateOrCreate(
                ['organico_id' => $organico->id],
                [
                    'direccion' => $ubicacion->ubicacion,
                    'latitud' => $ubicacion->latitud,
                    'longitud' => $ubicacion->longitud,
                    'departamento' => $geografica->departamento,
                    'municipio' => $geografica->municipio,
                    'provincia' => $geografica->provincia,
                    'ciudad' => $geografica->ciudad,
                    'referencia' => null,
                ]
            );

            CertificadoOrganico::query()
                ->where('es_obligatorio', true)
                ->get()
                ->each(function (CertificadoOrganico $certificado) use ($organico) {
                    OrganicoCertificado::updateOrCreate(
                        [
                            'organico_id' => $organico->id,
                            'certificado_organico_id' => $certificado->id,
                        ],
                        ['estado' => OrganicoCertificado::ESTADO_PENDIENTE]
                    );
                });
        });
    }
}
