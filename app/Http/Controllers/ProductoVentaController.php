<?php

namespace App\Http\Controllers;

use App\Models\Ganado;
use App\Models\Maquinaria;
use App\Models\Organico;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductoVentaController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $tiposPermitidos = ['ganado', 'maquinaria', 'organico'];
        $tipos = collect($request->input('tipos', []))
            ->when(
                $request->filled('tipo'),
                fn ($seleccionados) => $seleccionados->push($request->string('tipo')->toString())
            )
            ->filter(fn ($tipo) => in_array($tipo, $tiposPermitidos, true))
            ->unique()
            ->values()
            ->all();
        $q = trim($request->string('q')->toString());
        $productos = collect();

        if ($tipos === [] || in_array('ganado', $tipos, true)) {
            $query = Ganado::with(['user', 'imagenes', 'datoComercial', 'tipoAnimal']);
            $this->aplicarPropietario($query, $user);
            $this->aplicarBusqueda($query, $q);

            $productos = $productos->concat($query->get()->map(fn ($item) => [
                'id' => $item->id,
                'tipo' => 'ganado',
                'tipo_label' => 'Ganado',
                'nombre' => $item->nombre,
                'propietario' => $item->user?->name,
                'precio' => $item->precio,
                'stock' => $item->stock,
                'estado' => ($item->stock ?? 0) > 0 ? 'En venta' : 'Sin stock',
                'estado_color' => ($item->stock ?? 0) > 0 ? 'success' : 'secondary',
                'imagen' => $item->imagenes->first()?->ruta,
                'show_url' => route('ganados.show', $item),
                'edit_url' => route('ganados.edit', $item),
                'created_at' => $item->created_at,
            ]));
        }

        if ($tipos === [] || in_array('maquinaria', $tipos, true)) {
            $query = Maquinaria::with(['user', 'imagenes', 'estadoMaquinaria']);
            $this->aplicarPropietario($query, $user);
            $this->aplicarBusqueda($query, $q);

            $productos = $productos->concat($query->get()->map(fn ($item) => [
                'id' => $item->id,
                'tipo' => 'maquinaria',
                'tipo_label' => 'Maquinaria',
                'nombre' => $item->nombre,
                'propietario' => $item->user?->name,
                'precio' => $item->precio_dia,
                'stock' => null,
                'estado' => $item->estadoMaquinaria?->nombre ?? 'Publicada',
                'estado_color' => str_contains(strtolower($item->estadoMaquinaria?->nombre ?? ''), 'disponible') ? 'success' : 'warning',
                'imagen' => $item->imagenes->first()?->ruta,
                'show_url' => route('maquinarias.show', $item),
                'edit_url' => route('maquinarias.edit', $item),
                'created_at' => $item->created_at,
            ]));
        }

        if ($tipos === [] || in_array('organico', $tipos, true)) {
            $query = Organico::with(['user', 'imagenes', 'datoComercial']);
            $this->aplicarPropietario($query, $user);
            $this->aplicarBusqueda($query, $q);

            $productos = $productos->concat($query->get()->map(fn ($item) => [
                'id' => $item->id,
                'tipo' => 'organico',
                'tipo_label' => 'Organico',
                'nombre' => $item->nombre,
                'propietario' => $item->user?->name,
                'precio' => $item->precio,
                'stock' => $item->stock,
                'estado' => ($item->stock ?? 0) > 0 ? 'En venta' : 'Sin stock',
                'estado_color' => ($item->stock ?? 0) > 0 ? 'success' : 'secondary',
                'imagen' => $item->imagenes->first()?->ruta,
                'show_url' => route('organicos.show', $item),
                'edit_url' => route('organicos.edit', $item),
                'created_at' => $item->created_at,
            ]));
        }

        $productos = $productos->sortByDesc('created_at')->values();
        $perPage = 15;
        $page = LengthAwarePaginator::resolveCurrentPage();
        $paginados = new LengthAwarePaginator(
            $productos->forPage($page, $perPage),
            $productos->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('productos_venta.index', [
            'productos' => $paginados,
            'tipos' => $tipos,
            'q' => $q,
            'esAdmin' => $user->isAdmin(),
        ]);
    }

    private function aplicarPropietario($query, $user): void
    {
        if (!$user->isAdmin()) {
            $query->where('user_id', $user->id);
        }
    }

    private function aplicarBusqueda($query, string $q): void
    {
        if ($q !== '') {
            $query->where('nombre', 'ilike', '%' . $q . '%');
        }
    }
}
