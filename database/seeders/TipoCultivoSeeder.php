<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TipoCultivo;

class TipoCultivoSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['nombre' => 'Hortalizas', 'descripcion' => 'Verduras de hoja, raíz y fruto'],
            ['nombre' => 'Frutas', 'descripcion' => 'Frutas frescas de temporada'],
            ['nombre' => 'Tubérculos', 'descripcion' => 'Papa, yuca, camote, etc.'],
            ['nombre' => 'Legumbres', 'descripcion' => 'Frejol, lenteja, garbanzo, etc.'],
            ['nombre' => 'Cereales', 'descripcion' => 'Maíz, trigo, arroz, etc.'],
            ['nombre' => 'Aromáticas', 'descripcion' => 'Hierbas aromáticas y medicinales'],
        ];

        foreach ($data as $item) {
            TipoCultivo::firstOrCreate(['nombre' => $item['nombre']], $item);
        }
    }
}
