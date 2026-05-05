<?php

namespace App\Http\Controllers;

use App\Models\Ganado;
use App\Models\Categoria;
use App\Models\TipoAnimal;
use App\Models\Raza;
use App\Models\GanadoImagen;
use App\Services\GeocodificacionService;
use App\Http\Requests\StoreGanadoRequest;
use App\Http\Requests\UpdateGanadoRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;

class GanadoController extends Controller
{
    /**
     * Muestra la lista de ganado.
     */
    public function index()
    {
        // Purgados los modelos viejos (tipoPeso y datoSanitario)
        $ganados = Ganado::with(['categoria', 'raza', 'tipoAnimal', 'imagenes'])
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('ganados.index', compact('ganados'));
    }

    /**
     * Muestra el detalle de un ganado.
     */
    public function show(Ganado $ganado)
    {
        $ganado->load(['categoria', 'tipoAnimal', 'raza', 'imagenes', 'user.role']);
        return view('ganados.show', compact('ganado'));
    }

    /**
     * Muestra el formulario de creación.
     */
    public function create()
    {
        // Solo cargamos lo estrictamente necesario para el frontend nuevo
        $tipo_animals = TipoAnimal::orderBy('nombre')->get();
        $razas        = Raza::orderBy('nombre')->get();

        return view('ganados.create', compact('tipo_animals', 'razas'));
    }

    /**
     * Guarda un nuevo registro.
     */
    public function store(StoreGanadoRequest $request)
    {
        $validated = $request->validated();

        // 1. Calcular edad total en meses
        $edadMeses = ($validated['edad_anos'] * 12) + $validated['edad_meses'];

        // 2. Asignación Automática de Categoría (Buscamos la categoría "Animales" o asumimos ID 1)
        $categoria = Categoria::where('nombre', 'like', '%Animal%')->orWhere('nombre', 'like', '%Ganado%')->first();
        $categoria_id = $categoria ? $categoria->id : 1;

        // 3. Procesar Archivos de Certificación
        $rutaSanidad = null;
        if ($request->hasFile('archivo_sanidad')) {
            $rutaSanidad = $request->file('archivo_sanidad')->store('certificados', 'public');
        }

        $rutaGenetica = null;
        if ($request->hasFile('archivo_genetica')) {
            $rutaGenetica = $request->file('archivo_genetica')->store('certificados', 'public');
        }

        // 4. Preparar la data con la nueva arquitectura
        $data = [
            'user_id'           => auth()->id(),
            'categoria_id'      => $categoria_id,
            'nombre'            => $validated['nombre'],
            'tipo_animal_id'    => $validated['tipo_animal_id'],
            'raza_id'           => $validated['raza_id'] ?? null,
            'sexo'              => $validated['sexo'] ?? null,
            'edad'              => $edadMeses,
            'peso_actual'       => $validated['peso_actual'] ?? null,
            
            // Lógica Comercial
            'tipo_venta'        => $validated['tipo_venta'],
            'tipo_precio'       => $validated['tipo_precio'],
            // Si es genética, bloqueamos el stock a 1 por seguridad
            'stock'             => $validated['tipo_venta'] === 'genetica' ? 1 : $validated['stock'],
            'precio'            => $validated['precio'],
            
            // Certificaciones
            'tiene_sanidad'     => $request->boolean('tiene_sanidad'),
            'archivo_sanidad'   => $rutaSanidad,
            'es_campeon'        => $request->boolean('es_campeon'),
            'archivo_genetica'  => $rutaGenetica,

            // Textos y Ubicación
            'descripcion'       => $validated['descripcion'] ?? null,
            'fecha_publicacion' => now(),
            'ubicacion'         => $validated['ubicacion'] ?? null,
            'latitud'           => $validated['latitud'] ?? null,
            'longitud'          => $validated['longitud'] ?? null,
            'departamento'      => $validated['departamento'] ?? null,
            'municipio'         => $validated['municipio'] ?? null,
            'provincia'         => $validated['provincia'] ?? null,
            'ciudad'            => $validated['ciudad'] ?? null,
        ];

        // Crear el ganado
        $ganado = Ganado::create($data);

        // Guardar las imágenes si existen (máximo 3)
        if ($request->hasFile('imagenes')) {
            $orden = 0;
            $imagenes = array_slice($request->file('imagenes'), 0, 3);
            foreach ($imagenes as $imagen) {
                if ($imagen && $imagen->isValid()) {
                    $ruta = $imagen->store('ganados', 'public');
                    GanadoImagen::create([
                        'ganado_id' => $ganado->id,
                        'ruta' => $ruta,
                        'orden' => $orden++,
                    ]);
                }
            }
        }

        return redirect()->route('ganados.index')
            ->with('success', 'Ganado publicado correctamente.');
    }

    /**
     * Muestra el formulario de edición.
     */
    public function edit(Ganado $ganado)
    {
        if (!auth()->user()->isAdmin() && $ganado->user_id !== auth()->id()) {
            return redirect()->route('ganados.index')->with('error', 'No tienes permisos.');
        }

        $tipo_animals = TipoAnimal::orderBy('nombre')->get();
        $razas        = Raza::where('tipo_animal_id', $ganado->tipo_animal_id)->get();

        return view('ganados.edit', compact('ganado', 'tipo_animals', 'razas'));
    }

    /**
     * Actualiza un registro existente.
     */
    public function update(UpdateGanadoRequest $request, Ganado $ganado)
    {
        // Aquí iría la misma lógica de "store" adaptada para actualización. 
        // (Copilot puede autocompletarla basándose en el nuevo store)
    }

    /**
     * Obtiene información geográfica desde coordenadas (API)
     */
    public function obtenerGeocodificacion(Request $request)
    {
        // Función mantenida igual (funciona perfecto)
        $request->validate([
            'latitud'  => 'required|numeric',
            'longitud' => 'required|numeric',
        ]);

        $lat = $request->latitud;
        $lng = $request->longitud;

        try {
            $response = Http::withoutVerifying()
                ->timeout(10)
                ->withHeaders([
                    'User-Agent' => 'ProyectoAgricola/1.0',
                ])->get('https://nominatim.openstreetmap.org/reverse', [
                    'lat'            => $lat,
                    'lon'            => $lng,
                    'format'         => 'json',
                    'addressdetails' => 1,
                ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error API'], 500);
        }

        if ($response->failed()) {
            return response()->json(['success' => false], 500);
        }

        $json    = $response->json();
        $address = $json['address'] ?? [];

        return response()->json([
            'success' => true,
            'data'    => [
                'departamento' => $address['state'] ?? null,
                'provincia'    => $address['county'] ?? null,
                'municipio'    => $address['municipality'] ?? $address['town'] ?? $address['village'] ?? null,
                'ciudad'       => $address['city'] ?? $address['town'] ?? $address['village'] ?? null,
            ],
        ]);
    }

    /**
     * Elimina un registro.
     */
    public function destroy(Ganado $ganado)
    {
        if (!auth()->user()->isAdmin() && $ganado->user_id !== auth()->id()) {
            return redirect()->route('ganados.index')->with('error', 'No tienes permisos.');
        }

        // Eliminar certificados PDF si existen
        if ($ganado->archivo_sanidad && Storage::disk('public')->exists($ganado->archivo_sanidad)) {
            Storage::disk('public')->delete($ganado->archivo_sanidad);
        }
        if ($ganado->archivo_genetica && Storage::disk('public')->exists($ganado->archivo_genetica)) {
            Storage::disk('public')->delete($ganado->archivo_genetica);
        }

        foreach ($ganado->imagenes as $imagen) {
            if (Storage::disk('public')->exists($imagen->ruta)) {
                Storage::disk('public')->delete($imagen->ruta);
            }
        }

        $ganado->delete();
        return redirect()->route('ganados.index')->with('success', 'Ganado eliminado.');
    }
}