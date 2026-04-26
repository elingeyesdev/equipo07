<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property \App\Models\Maquinaria $resource
 */
class MaquinariaResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nombre' => $this->nombre,
            'modelo' => $this->modelo,
            'telefono' => $this->telefono,
            'precio_dia' => $this->precio_dia,

            'descripcion' => $this->descripcion,
            'ubicacion' => $this->ubicacion,
            'departamento' => $this->departamento,
            'municipio' => $this->municipio,
            'provincia' => $this->provincia,
            'ciudad' => $this->ciudad,
            'latitud' => $this->latitud,
            'longitud' => $this->longitud,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            'categoria' => $this->whenLoaded('categoria', fn () => [
                'id' => $this->categoria?->id,
                'nombre' => $this->categoria?->nombre,
            ]),
            'tipo_maquinaria' => $this->whenLoaded('tipoMaquinaria', fn () => [
                'id' => $this->tipoMaquinaria?->id,
                'nombre' => $this->tipoMaquinaria?->nombre,
            ]),
            'marca_maquinaria' => $this->whenLoaded('marcaMaquinaria', fn () => [
                'id' => $this->marcaMaquinaria?->id,
                'nombre' => $this->marcaMaquinaria?->nombre,
            ]),
            'estado_maquinaria' => $this->whenLoaded('estadoMaquinaria', fn () => [
                'id' => $this->estadoMaquinaria?->id,
                'nombre' => $this->estadoMaquinaria?->nombre,
            ]),
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

