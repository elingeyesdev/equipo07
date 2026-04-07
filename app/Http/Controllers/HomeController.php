<?php

namespace App\Http\Controllers;

use App\Models\Ganado;
use App\Models\Maquinaria;
use App\Models\Organico;
use App\Models\Categoria;
use App\Models\TipoAnimal;
use App\Models\TipoMaquinaria;
use App\Models\MarcaMaquinaria;
use App\Models\TipoCultivo;
use App\Models\Raza;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


class HomeController extends Controller
{
    /**
     * Mostrar página de inicio con búsqueda y filtros
     */
    public function index(Request $request)
    {
        $categorias = Categoria::orderBy('nombre')->get();
        $tiposAnimales = TipoAnimal::orderBy('nombre')->get();
        $razas = Raza::orderBy('nombre')->get();

        $tiposMaquinaria   = TipoMaquinaria::orderBy('nombre')->get();
        $marcasMaquinaria  = MarcaMaquinaria::orderBy('nombre')->get();
        $tiposCultivo      = TipoCultivo::orderBy('nombre')->get();

        // Parámetros de búsqueda
        $q = $request->get('q', '');
        $categoria_id = $request->get('categoria_id', '');
        $tipo_animal_id = $request->get('tipo_animal_id', '');
        $raza_id = $request->get('raza_id', '');
        $tipo = $request->get('tipo', ''); // ganados, maquinarias, organicos

        // Inicializar resultados
        $ganados = collect();
        $maquinarias = collect();
        $organicos = collect();

        // Si hay búsqueda o filtros, buscar (siempre mostrar todos los tipos)
        if ($q || $categoria_id || $tipo_animal_id || $raza_id) {
            // Búsqueda en Ganados (siempre mostrar)
            $ganadosQuery = Ganado::with(['categoria', 'tipoAnimal', 'raza', 'datoSanitario', 'imagenes'])
                ->where(function ($query) use ($q) {
                    if ($q) {
                        $query->where('nombre', 'ilike', "%{$q}%")
                            ->orWhere('descripcion', 'ilike', "%{$q}%")
                            ->orWhere('ubicacion', 'ilike', "%{$q}%");
                    }
                });

            if ($categoria_id) {
                $ganadosQuery->where('categoria_id', $categoria_id);
            }

            if ($tipo_animal_id) {
                $ganadosQuery->where('tipo_animal_id', $tipo_animal_id);
            }

            if ($raza_id) {
                $ganadosQuery->where('raza_id', $raza_id);
            }

            $ganados = $ganadosQuery->orderBy('created_at', 'desc')->paginate(12);

            // Búsqueda en Maquinarias (siempre mostrar)
            $maquinariasQuery = Maquinaria::with(['categoria', 'tipoMaquinaria', 'marcaMaquinaria', 'estadoMaquinaria', 'imagenes'])
                ->where(function ($query) use ($q) {
                    if ($q) {
                        $query->where('nombre', 'ilike', "%{$q}%")
                            ->orWhere('descripcion', 'ilike', "%{$q}%")
                            ->orWhereHas('tipoMaquinaria', function ($subQuery) use ($q) {
                                $subQuery->where('nombre', 'ilike', "%{$q}%");
                            })
                            ->orWhereHas('marcaMaquinaria', function ($subQuery) use ($q) {
                                $subQuery->where('nombre', 'ilike', "%{$q}%");
                            });
                    }
                });

            if ($categoria_id) {
                $maquinariasQuery->where('categoria_id', $categoria_id);
            }

            $maquinarias = $maquinariasQuery->orderBy('created_at', 'desc')->paginate(12);

            // Búsqueda en Orgánicos (siempre mostrar)
            $organicosQuery = Organico::with(['categoria', 'imagenes', 'unidad'])
                ->where(function ($query) use ($q) {
                    if ($q) {
                        $query->where('nombre', 'ilike', "%{$q}%")
                            ->orWhere('descripcion', 'ilike', "%{$q}%");
                    }
                });

            if ($categoria_id) {
                $organicosQuery->where('categoria_id', $categoria_id);
            }

            $organicos = $organicosQuery->orderBy('created_at', 'desc')->paginate(12);
        } else {
            // Sin búsqueda: mostrar productos destacados/recientes (últimos 3 de cada tipo)
            $ganados = Ganado::with(['categoria', 'tipoAnimal', 'raza', 'datoSanitario', 'imagenes'])
                ->orderBy('created_at', 'desc')
                ->take(3)
                ->get();

            $maquinarias = Maquinaria::with(['categoria', 'tipoMaquinaria', 'marcaMaquinaria', 'estadoMaquinaria', 'imagenes'])
                ->orderBy('created_at', 'desc')
                ->take(3)
                ->get();

            $organicos = Organico::with(['categoria', 'imagenes', 'unidad'])
                ->orderBy('created_at', 'desc')
                ->take(3)
                ->get();
        }

        return view('public.home', compact(
            'categorias',
            'tiposAnimales',
            'razas',
            'tiposMaquinaria',
            'marcasMaquinaria',
            'tiposCultivo',
            'ganados',
            'maquinarias',
            'organicos',
            'q',
            'categoria_id',
            'tipo_animal_id',
            'raza_id',
            'tipo'
        ));
    }

    /**
     * Mostrar página de anuncios con búsqueda y filtros
     */
    public function anuncios(Request $request)
    {
        $categorias   = Categoria::orderBy('nombre')->get();

        // Parámetros de búsqueda
        $q            = $request->get('q', '');
        $categoria_id = $request->get('categoria_id', '');
        $fecha_publicacion = $request->get('fecha_publicacion', '');

        // Determinar tipo seleccionado a partir del nombre de la categoría
        $tipoSeleccionado = null; // 'ganados', 'maquinarias', 'organicos'

        if ($categoria_id) {
            $cat = $categorias->firstWhere('id', (int) $categoria_id);

            if ($cat) {
                $nombre = Str::lower($cat->nombre);

                if (Str::contains($nombre, 'animal')) {
                    $tipoSeleccionado = 'ganados';
                } elseif (Str::contains($nombre, 'maquinaria')) {
                    $tipoSeleccionado = 'maquinarias';
                } elseif (Str::contains($nombre, ['organico', 'orgánico'])) {
                    $tipoSeleccionado = 'organicos';
                }
            }
        }

        // ================= GANADOS =================
        if (!$tipoSeleccionado || $tipoSeleccionado === 'ganados') {
            $ganadosQuery = Ganado::with(['categoria', 'tipoAnimal', 'raza', 'datoSanitario', 'imagenes'])
                ->where(function ($query) use ($q) {
                    if ($q) {
                        $query->where('nombre', 'ilike', "%{$q}%")
                            ->orWhere('descripcion', 'ilike', "%{$q}%")
                            ->orWhere('ubicacion', 'ilike', "%{$q}%");
                    }
                });

            // Filtro por fecha de publicación
            if ($fecha_publicacion) {
                $fechaQuery = null;
                if ($fecha_publicacion === 'hoy') {
                    $fechaQuery = now()->startOfDay();
                } elseif ($fecha_publicacion === 'semana') {
                    $fechaQuery = now()->subWeek();
                } elseif ($fecha_publicacion === 'mes') {
                    $fechaQuery = now()->subMonth();
                } elseif ($fecha_publicacion === '3meses') {
                    $fechaQuery = now()->subMonths(3);
                } elseif ($fecha_publicacion === '6meses') {
                    $fechaQuery = now()->subMonths(6);
                }
                
                if ($fechaQuery) {
                    $ganadosQuery->where(function($query) use ($fechaQuery) {
                        $query->where('fecha_publicacion', '>=', $fechaQuery)
                            ->orWhere(function($q) use ($fechaQuery) {
                                $q->whereNull('fecha_publicacion')
                                  ->where('created_at', '>=', $fechaQuery);
                            });
                    });
                }
            }

            // (Opcional) si igual quieres usar categoria_id para subcategorías:
            // if ($categoria_id) {
            //     $ganadosQuery->where('categoria_id', $categoria_id);
            // }

            $ganados = $ganadosQuery
                ->orderBy('created_at', 'desc')
                ->paginate(12, ['*'], 'ganados_page');
        } else {
            // No se deben mostrar Ganados
            $ganados = collect();
        }

        // ================= MAQUINARIAS =================
        if (!$tipoSeleccionado || $tipoSeleccionado === 'maquinarias') {
            $maquinariasQuery = Maquinaria::with(['categoria', 'tipoMaquinaria', 'marcaMaquinaria', 'estadoMaquinaria', 'imagenes'])
                ->where(function ($query) use ($q) {
                    if ($q) {
                        $query->where('nombre', 'ilike', "%{$q}%")
                            ->orWhere('descripcion', 'ilike', "%{$q}%")
                            ->orWhereHas('tipoMaquinaria', function ($subQuery) use ($q) {
                                $subQuery->where('nombre', 'ilike', "%{$q}%");
                            })
                            ->orWhereHas('marcaMaquinaria', function ($subQuery) use ($q) {
                                $subQuery->where('nombre', 'ilike', "%{$q}%");
                            });
                    }
                });

            // Filtro por fecha de publicación
            if ($fecha_publicacion) {
                $fechaQuery = null;
                if ($fecha_publicacion === 'hoy') {
                    $fechaQuery = now()->startOfDay();
                } elseif ($fecha_publicacion === 'semana') {
                    $fechaQuery = now()->subWeek();
                } elseif ($fecha_publicacion === 'mes') {
                    $fechaQuery = now()->subMonth();
                } elseif ($fecha_publicacion === '3meses') {
                    $fechaQuery = now()->subMonths(3);
                } elseif ($fecha_publicacion === '6meses') {
                    $fechaQuery = now()->subMonths(6);
                }
                
                if ($fechaQuery) {
                    $maquinariasQuery->where('created_at', '>=', $fechaQuery);
                }
            }

            // if ($categoria_id) {
            //     $maquinariasQuery->where('categoria_id', $categoria_id);
            // }

            $maquinarias = $maquinariasQuery
                ->orderBy('created_at', 'desc')
                ->paginate(12, ['*'], 'maquinarias_page');
        } else {
            $maquinarias = collect();
        }

        // ================= ORGÁNICOS =================
        if (!$tipoSeleccionado || $tipoSeleccionado === 'organicos') {
            $organicosQuery = Organico::with(['categoria', 'imagenes', 'unidad'])
                ->where(function ($query) use ($q) {
                    if ($q) {
                        $query->where('nombre', 'ilike', "%{$q}%")
                            ->orWhere('descripcion', 'ilike', "%{$q}%");
                    }
                });

            // Filtro por fecha de publicación
            if ($fecha_publicacion) {
                $fechaQuery = null;
                if ($fecha_publicacion === 'hoy') {
                    $fechaQuery = now()->startOfDay();
                } elseif ($fecha_publicacion === 'semana') {
                    $fechaQuery = now()->subWeek();
                } elseif ($fecha_publicacion === 'mes') {
                    $fechaQuery = now()->subMonth();
                } elseif ($fecha_publicacion === '3meses') {
                    $fechaQuery = now()->subMonths(3);
                } elseif ($fecha_publicacion === '6meses') {
                    $fechaQuery = now()->subMonths(6);
                }
                
                if ($fechaQuery) {
                    $organicosQuery->where('created_at', '>=', $fechaQuery);
                }
            }

            // if ($categoria_id) {
            //     $organicosQuery->where('categoria_id', $categoria_id);
            // }

            $organicos = $organicosQuery
                ->orderBy('created_at', 'desc')
                ->paginate(12, ['*'], 'organicos_page');
        } else {
            $organicos = collect();
        }

        return view('public.ads.index', compact(
            'categorias',
            'ganados',
            'maquinarias',
            'organicos',
            'q',
            'categoria_id',
            'fecha_publicacion'
        ));
    }
}
