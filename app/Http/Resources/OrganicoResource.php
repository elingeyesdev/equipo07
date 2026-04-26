<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property \App\Models\Organico $resource
 */
class OrganicoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nombre' => $this->nombre,
            'precio' => $this->precio,
            'stock' => $this->stock,
            'fecha_cosecha' => $this->fecha_cosecha,
            'descripcion' => $this->descripcion,
            'origen' => $this->origen,
            'latitud_origen' => $this->latitud_origen,
            'longitud_origen' => $this->longitud_origen,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            'categoria' => $this->whenLoaded('categoria', fn () => [
                'id' => $this->categoria?->id,
                'nombre' => $this->categoria?->nombre,
            ]),
            'unidad' => $this->whenLoaded('unidadOrganico', fn () => [
                'id' => $this->unidadOrganico?->id,
                'nombre' => $this->unidadOrganico?->nombre,
            ]),
            'tipo_cultivo' => $this->whenLoaded('tipoCultivo', fn () => [
                'id' => $this->tipoCultivo?->id,
                'nombre' => $this->tipoCultivo?->nombre,
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

