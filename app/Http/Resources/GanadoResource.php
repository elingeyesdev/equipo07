<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property \App\Models\Ganado $resource
 */
class GanadoResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nombre' => $this->nombre,
            'edad' => $this->edad,
            'peso_actual' => $this->peso_actual,
            'sexo' => $this->sexo,
            'cantidad_leche_dia' => $this->cantidad_leche_dia,
            'precio' => $this->precio,
            'stock' => $this->stock,
            'imagen' => $this->imagen,
            'descripcion' => $this->descripcion,
            'fecha_publicacion' => $this->fecha_publicacion,
            'ubicacion' => $this->ubicacion,
            'departamento' => $this->departamento,
            'municipio' => $this->municipio,
            'provincia' => $this->provincia,
            'ciudad' => $this->ciudad,
            'latitud' => $this->latitud,
            'longitud' => $this->longitud,
            'es_campeon' => $this->es_campeon,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            'categoria' => $this->whenLoaded('categoria', fn () => [
                'id' => $this->categoria?->id,
                'nombre' => $this->categoria?->nombre,
            ]),
            'tipo_animal' => $this->whenLoaded('tipoAnimal', fn () => [
                'id' => $this->tipoAnimal?->id,
                'nombre' => $this->tipoAnimal?->nombre,
            ]),
            'tipo_peso' => $this->whenLoaded('tipoPeso', fn () => [
                'id' => $this->tipoPeso?->id,
                'nombre' => $this->tipoPeso?->nombre,
            ]),
            'raza' => $this->whenLoaded('raza', fn () => [
                'id' => $this->raza?->id,
                'nombre' => $this->raza?->nombre,
            ]),
            'dato_sanitario' => $this->whenLoaded('datoSanitario', fn () => $this->datoSanitario),
            'datos_sanitarios' => $this->whenLoaded('datosSanitarios', fn () => $this->datosSanitarios),
            'user' => $this->whenLoaded('user', fn () => [
                'id' => $this->user?->id,
                'name' => $this->user?->name,
            ]),
            'imagenes' => $this->whenLoaded('imagenes', fn () => $this->imagenes->map(fn ($img) => [
                'id' => $img->id,
                'ruta' => $img->ruta,
                'orden' => $img->orden,
            ])),
        ];
    }
}
