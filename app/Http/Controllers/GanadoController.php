<?php

namespace App\Http\Controllers;

use App\Models\Ganado;
use App\Models\Categoria;
use App\Models\TipoAnimal;
use App\Models\TipoPeso;
use App\Models\Raza;
use App\Models\GanadoImagen;
use App\Services\GeocodificacionService;
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
        $ganados = Ganado::with(['categoria', 'raza', 'tipoAnimal', 'tipoPeso', 'datoSanitario', 'imagenes'])
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('ganados.index', compact('ganados'));
    }

    /**
     * Muestra el detalle de un ganado.
     */
    public function show(Ganado $ganado)
    {
        $ganado->load(['categoria', 'tipoAnimal', 'tipoPeso', 'raza', 'datoSanitario', 'imagenes', 'user.role']);
        return view('ganados.show', compact('ganado'));
    }

    /**
     * Muestra el formulario de creación.
     */
    public function create()
    {
        $tipo_animals = TipoAnimal::orderBy('nombre')->get();
        $categorias   = Categoria::whereIn('tipo', ['ganado', 'general'])->orderBy('nombre')->get();
        $tipoPesos    = TipoPeso::orderBy('nombre')->get();
        $razas        = Raza::orderBy('nombre')->get();
        $datosSanitarios = \App\Models\DatoSanitario::orderBy('id')->get();

        return view('ganados.create', compact(
            'tipo_animals',
            'categorias',
            'tipoPesos',
            'razas',
            'datosSanitarios'
        ));
    }


    /**
     * Guarda un nuevo registro.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre'          => 'required|string|max:255',
            'tipo_animal_id'  => 'required|exists:tipo_animals,id',
            'raza_id'         => 'nullable|exists:razas,id,tipo_animal_id,' . $request->tipo_animal_id,
            'edad_anos'       => 'required|integer|min:0|max:25',
            'edad_meses'      => 'required|integer|min:0|max:11',
            'edad_dias'       => 'required|integer|min:0|max:30',
            'tipo_peso_id'    => 'required|exists:tipo_pesos,id',
            'peso_actual'     => 'nullable|numeric|min:0',
            'sexo'            => 'nullable|string',
            'cantidad_leche_dia' => 'nullable|numeric|min:0',
            'descripcion'     => 'nullable|string',
            'precio'          => 'nullable|numeric|min:0',
            'stock'            => 'required|integer|min:0',
            'imagenes.*'      => 'nullable|image|max:2048',
            'categoria_id'    => 'required|exists:categorias,id',
            'ubicacion' => 'nullable|string|max:255',
            'latitud' => 'nullable|numeric',
            'longitud' => 'nullable|numeric',
            'madre_id' => 'nullable|exists:ganados,id',
            'padre_id' => 'nullable|exists:ganados,id',
        ]);

        // Calcular edad total en meses (años*12 + meses, los días se redondean a meses si >= 15 días)
        $edadMeses = ($request->edad_anos * 12) + $request->edad_meses;
        if ($request->edad_dias >= 15) {
            $edadMeses += 1; // Redondear hacia arriba si tiene 15 o más días
        }

        // Sincronización automática de tipo_animal_id basado en raza_id para mantener consistencia
        $tipo_animal_id = $request->tipo_animal_id;
        if ($request->raza_id) {
            $raza = \App\Models\Raza::find($request->raza_id);
            if ($raza) {
                $tipo_animal_id = $raza->tipo_animal_id;
            }
        }

        $data = [
            'nombre' => $request->nombre,
            'tipo_animal_id' => $tipo_animal_id,
            'raza_id' => $request->raza_id,
            'edad' => $edadMeses,
            'tipo_peso_id' => $request->tipo_peso_id,
            'peso_actual' => $request->peso_actual,
            'sexo' => $request->sexo,
            'cantidad_leche_dia' => $request->cantidad_leche_dia,
            'descripcion' => $request->descripcion,
            'precio' => $request->precio,
            'stock' => $request->stock,
            'categoria_id' => $request->categoria_id,
            'fecha_publicacion' => now(), // Fecha automática al crear
            'ubicacion' => $request->ubicacion,
            'latitud' => $request->latitud,
            'longitud' => $request->longitud,
            'departamento' => $request->departamento,
            'municipio' => $request->municipio,
            'provincia' => $request->provincia,
            'ciudad' => $request->ciudad,
            'madre_id' => $request->madre_id,
            'padre_id' => $request->padre_id,
        ];

        // Asignar el usuario autenticado
        $data['user_id'] = auth()->id();

        // Fase 1: Creación progresiva de Ubicación normalizada
        $ubicacion = \App\Models\Ubicacion::create([
            'departamento' => $data['departamento'] ?? null,
            'provincia' => $data['provincia'] ?? null,
            'municipio' => $data['municipio'] ?? null,
            'ciudad' => $data['ciudad'] ?? null,
            'direccion' => $data['ubicacion'] ?? null,
            'latitud' => $data['latitud'] ?? null,
            'longitud' => $data['longitud'] ?? null,
        ]);
        $data['ubicacion_id'] = $ubicacion->id;

        // Crear el ganado
        $ganado = Ganado::create($data);

        // Sincronizar madre en tabla normalizada
        if ($ganado->madre_id) {
            \App\Models\GanadoGenealogia::updateOrCreate(
                ['ganado_id' => $ganado->id, 'tipo_relacion' => 'madre'],
                ['pariente_id' => $ganado->madre_id]
            );
        } else {
            \App\Models\GanadoGenealogia::where('ganado_id', $ganado->id)->where('tipo_relacion', 'madre')->delete();
        }

        // Sincronizar padre en tabla normalizada
        if ($ganado->padre_id) {
            \App\Models\GanadoGenealogia::updateOrCreate(
                ['ganado_id' => $ganado->id, 'tipo_relacion' => 'padre'],
                ['pariente_id' => $ganado->padre_id]
            );
        } else {
            \App\Models\GanadoGenealogia::where('ganado_id', $ganado->id)->where('tipo_relacion', 'padre')->delete();
        }

        // Guardar las imágenes si existen (máximo 3)
        if ($request->hasFile('imagenes')) {
            $orden = 0;
            $imagenes = array_slice($request->file('imagenes'), 0, 3); // Limitar a 3 imágenes
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
            ->with('success', 'Ganado registrado correctamente.');
    }


    /**
     * Muestra el formulario de edición.
     */
    public function edit(Ganado $ganado)
    {
        // Verificar permisos: solo el dueño o admin puede editar
        if (!auth()->user()->isAdmin() && $ganado->user_id !== auth()->id()) {
            return redirect()->route('ganados.index')
                ->with('error', 'No tienes permisos para editar este anuncio.');
        }

        $tipo_animals = TipoAnimal::orderBy('nombre')->get();
        $categorias   = Categoria::whereIn('tipo', ['ganado', 'general'])->orderBy('nombre')->get();
        $tipoPesos    = TipoPeso::orderBy('nombre')->get();
        $razas        = Raza::where('tipo_animal_id', $ganado->tipo_animal_id)->get();
        $datosSanitarios = \App\Models\DatoSanitario::orderBy('id')->get();

        return view('ganados.edit', compact(
            'ganado',
            'tipo_animals',
            'categorias',
            'tipoPesos',
            'razas',
            'datosSanitarios'
        ));
    }


    /**
     * Actualiza un registro existente.
     */
    public function update(Request $request, Ganado $ganado)
    {
        // Verificar permisos: solo el dueño o admin puede actualizar
        if (!auth()->user()->isAdmin() && $ganado->user_id !== auth()->id()) {
            return redirect()->route('ganados.index')
                ->with('error', 'No tienes permisos para editar este anuncio.');
        }

        $request->validate([
            'nombre' => 'required|string|max:255',
            'tipo_animal_id' => 'required|exists:tipo_animals,id',
            'raza_id' => 'nullable|exists:razas,id,tipo_animal_id,' . $request->tipo_animal_id,
            'edad_anos' => 'required|integer|min:0|max:25',
            'edad_meses' => 'required|integer|min:0|max:11',
            'edad_dias' => 'required|integer|min:0|max:30',
            'peso_actual' => 'nullable|numeric|min:0',
            'sexo' => 'nullable|string',
            'cantidad_leche_dia' => 'nullable|numeric|min:0',
            'descripcion' => 'nullable|string',
            'precio' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'tipo_peso_id' => 'required|exists:tipo_pesos,id',
            'imagenes.*' => 'nullable|image|max:2048',
            'categoria_id' => 'required|exists:categorias,id',
            'ubicacion' => 'nullable|string|max:255',
            'latitud' => 'nullable|numeric',
            'longitud' => 'nullable|numeric',
            'madre_id' => 'nullable|exists:ganados,id',
            'padre_id' => 'nullable|exists:ganados,id',
        ]);

        // Calcular edad total en meses (años*12 + meses, los días se redondean a meses si >= 15 días)
        $edadMeses = ($request->edad_anos * 12) + $request->edad_meses;
        if ($request->edad_dias >= 15) {
            $edadMeses += 1; // Redondear hacia arriba si tiene 15 o más días
        }

        // Sincronización automática de tipo_animal_id basado en raza_id para mantener consistencia
        $tipo_animal_id = $request->tipo_animal_id;
        if ($request->raza_id) {
            $raza = \App\Models\Raza::find($request->raza_id);
            if ($raza) {
                $tipo_animal_id = $raza->tipo_animal_id;
            }
        }

        $data = [
            'nombre' => $request->nombre,
            'tipo_animal_id' => $tipo_animal_id,
            'raza_id' => $request->raza_id,
            'edad' => $edadMeses,
            'tipo_peso_id' => $request->tipo_peso_id,
            'peso_actual' => $request->peso_actual,
            'sexo' => $request->sexo,
            'cantidad_leche_dia' => $request->cantidad_leche_dia,
            'descripcion' => $request->descripcion,
            'precio' => $request->precio,
            'stock' => $request->stock,
            'categoria_id' => $request->categoria_id,
            'ubicacion' => $request->ubicacion,
            'latitud' => $request->latitud,
            'longitud' => $request->longitud,
            'departamento' => $request->departamento,
            'municipio' => $request->municipio,
            'provincia' => $request->provincia,
            'ciudad' => $request->ciudad,
            'madre_id' => $request->madre_id,
            'padre_id' => $request->padre_id,
            // Mantener la fecha de publicación existente, o establecerla si no existe
            'fecha_publicacion' => $ganado->fecha_publicacion ?? now(),
        ];

        // Eliminar imágenes marcadas para eliminar
        if ($request->has('imagenes_eliminar')) {
            foreach ($request->imagenes_eliminar as $imagenId) {
                $imagen = GanadoImagen::find($imagenId);
                if ($imagen && $imagen->ganado_id === $ganado->id) {
                    if (Storage::disk('public')->exists($imagen->ruta)) {
                        Storage::disk('public')->delete($imagen->ruta);
                    }
                    $imagen->delete();
                }
            }
        }

        // Agregar nuevas imágenes (máximo 3 en total)
        if ($request->hasFile('imagenes')) {
            $totalImagenesActuales = $ganado->imagenes()->count();
            $maxOrden = $ganado->imagenes()->max('orden') ?? -1;

            if ($totalImagenesActuales < 3) {
                $espaciosDisponibles = 3 - $totalImagenesActuales;
                $orden = $maxOrden + 1;
                $imagenes = array_slice($request->file('imagenes'), 0, $espaciosDisponibles);
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
        }

        // Fase 1: Actualización progresiva de Ubicación normalizada
        if ($ganado->ubicacion_id) {
            $ubicacion = \App\Models\Ubicacion::find($ganado->ubicacion_id);
            if ($ubicacion) {
                $ubicacion->update([
                    'departamento' => $data['departamento'] ?? null,
                    'provincia' => $data['provincia'] ?? null,
                    'municipio' => $data['municipio'] ?? null,
                    'ciudad' => $data['ciudad'] ?? null,
                    'direccion' => $data['ubicacion'] ?? null,
                    'latitud' => $data['latitud'] ?? null,
                    'longitud' => $data['longitud'] ?? null,
                ]);
            }
        } else {
            $ubicacion = \App\Models\Ubicacion::create([
                'departamento' => $data['departamento'] ?? null,
                'provincia' => $data['provincia'] ?? null,
                'municipio' => $data['municipio'] ?? null,
                'ciudad' => $data['ciudad'] ?? null,
                'direccion' => $data['ubicacion'] ?? null,
                'latitud' => $data['latitud'] ?? null,
                'longitud' => $data['longitud'] ?? null,
            ]);
            $data['ubicacion_id'] = $ubicacion->id;
        }

        $ganado->update($data);

        // Sincronizar madre en tabla normalizada
        if ($ganado->madre_id) {
            \App\Models\GanadoGenealogia::updateOrCreate(
                ['ganado_id' => $ganado->id, 'tipo_relacion' => 'madre'],
                ['pariente_id' => $ganado->madre_id]
            );
        } else {
            \App\Models\GanadoGenealogia::where('ganado_id', $ganado->id)->where('tipo_relacion', 'madre')->delete();
        }

        // Sincronizar padre en tabla normalizada
        if ($ganado->padre_id) {
            \App\Models\GanadoGenealogia::updateOrCreate(
                ['ganado_id' => $ganado->id, 'tipo_relacion' => 'padre'],
                ['pariente_id' => $ganado->padre_id]
            );
        } else {
            \App\Models\GanadoGenealogia::where('ganado_id', $ganado->id)->where('tipo_relacion', 'padre')->delete();
        }

        return redirect()->route('ganados.index')
            ->with('success', 'Registro actualizado correctamente.');
    }


    /**
     * Obtiene información geográfica desde coordenadas (API)
     */
    public function obtenerGeocodificacion(Request $request)
    {
        $request->validate([
            'latitud'  => 'required|numeric',
            'longitud' => 'required|numeric',
        ]);

        $lat = $request->latitud;
        $lng = $request->longitud;

        try {
            $response = Http::withoutVerifying()   // evita problemas SSL en local
                ->timeout(10)                     // evita demoras
                ->withHeaders([
                    'User-Agent' => 'ProyectoAgricola/1.0',
                ])->get('https://nominatim.openstreetmap.org/reverse', [
                    'lat'            => $lat,
                    'lon'            => $lng,
                    'format'         => 'json',
                    'addressdetails' => 1,
                ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al conectar con Nominatim: ' . $e->getMessage(),
            ], 500);
        }

        if ($response->failed()) {
            return response()->json([
                'success' => false,
                'message' => 'Error en la respuesta de Nominatim. Código: ' . $response->status(),
                'body'    => $response->body(),
            ], 500);
        }

        $json    = $response->json();
        $address = $json['address'] ?? [];

        $departamento = $address['state'] ?? null;
        $provincia    = $address['county'] ?? null;
        $municipio    = $address['municipality']
            ?? $address['town']
            ?? $address['village']
            ?? null;
        $ciudad       = $address['city']
            ?? $address['town']
            ?? $address['village']
            ?? $municipio;

        return response()->json([
            'success' => true,
            'data'    => [
                'departamento' => $departamento,
                'provincia'    => $provincia,
                'municipio'    => $municipio,
                'ciudad'       => $ciudad,
            ],
        ]);
    }

    /**
     * Elimina un registro.
     */
    public function destroy(Ganado $ganado)
    {
        // Verificar permisos: solo el dueño o admin puede eliminar
        if (!auth()->user()->isAdmin() && $ganado->user_id !== auth()->id()) {
            return redirect()->route('ganados.index')
                ->with('error', 'No tienes permisos para eliminar este anuncio.');
        }

        // Eliminar todas las imágenes asociadas
        foreach ($ganado->imagenes as $imagen) {
            if (Storage::disk('public')->exists($imagen->ruta)) {
                Storage::disk('public')->delete($imagen->ruta);
            }
        }

        // Eliminar imagen antigua si existe
        if ($ganado->imagen && Storage::disk('public')->exists($ganado->imagen)) {
            Storage::disk('public')->delete($ganado->imagen);
        }

        $ganado->delete();
        return redirect()->route('ganados.index')
            ->with('success', 'Ganado eliminado correctamente.');
    }
}
