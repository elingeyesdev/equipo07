<?php

namespace App\Http\Controllers;

use App\Models\Ganado;
use App\Models\Categoria;
use App\Models\TipoAnimal;
use App\Models\TipoPeso;
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
        $categorias   = Categoria::orderBy('nombre')->get();
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
    public function store(StoreGanadoRequest $request)
    {
        $validated = $request->validated();

        // Calcular edad total en meses (años*12 + meses, los días se redondean a meses si >= 15 días)
        $edadMeses = ($validated['edad_anos'] * 12) + $validated['edad_meses'];
        if ($validated['edad_dias'] >= 15) {
            $edadMeses += 1; // Redondear hacia arriba si tiene 15 o más días
        }

        $data = [
            'nombre' => $validated['nombre'],
            'tipo_animal_id' => $validated['tipo_animal_id'],
            'raza_id' => $validated['raza_id'] ?? null,
            'edad' => $edadMeses,
            'tipo_peso_id' => $validated['tipo_peso_id'],
            'peso_actual' => $validated['peso_actual'] ?? null,
            'sexo' => $validated['sexo'] ?? null,
            'cantidad_leche_dia' => $validated['cantidad_leche_dia'] ?? null,
            'descripcion' => $validated['descripcion'] ?? null,
            'precio' => $validated['precio'] ?? null,
            'stock' => $validated['stock'],
            'categoria_id' => $validated['categoria_id'],
            'fecha_publicacion' => now(), // Fecha automática al crear
            'ubicacion' => $validated['ubicacion'] ?? null,
            'latitud' => $validated['latitud'] ?? null,
            'longitud' => $validated['longitud'] ?? null,
            'departamento' => $validated['departamento'] ?? null,
            'municipio' => $validated['municipio'] ?? null,
            'provincia' => $validated['provincia'] ?? null,
            'ciudad' => $validated['ciudad'] ?? null,
            'dato_sanitario_id' => $validated['dato_sanitario_id'] ?? null,
        ];

        // Asignar el usuario autenticado
        $data['user_id'] = auth()->id();

        // Crear el ganado
        $ganado = Ganado::create($data);

        // Guardar las imágenes si existen (máximo 3)
        if ($request->hasFile('imagenes')) {
            $orden = 0;
            $imagenes = array_slice($request->file('imagenes'), 0, 3); // Limitar a 3 imágenes
            foreach ($imagenes as $imagen) {
                if ($imagen && $imagen->isValid()) {
                    $ruta = Ganado::normalizeStoredPathValue($imagen->store('ganados', 'public'));
                    GanadoImagen::create([
                        'ganado_id' => $ganado->id,
                        'ruta' => $ruta,
                        'orden' => $orden++,
                    ]);
                }
            }
        }

        $this->sincronizarImagenPrincipal($ganado);

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
        $categorias   = Categoria::orderBy('nombre')->get();
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
    public function update(UpdateGanadoRequest $request, Ganado $ganado)
    {
        // Verificar permisos: solo el dueño o admin puede actualizar
        if (!auth()->user()->isAdmin() && $ganado->user_id !== auth()->id()) {
            return redirect()->route('ganados.index')
                ->with('error', 'No tienes permisos para editar este anuncio.');
        }

        $validated = $request->validated();

        // Calcular edad total en meses (años*12 + meses, los días se redondean a meses si >= 15 días)
        $edadMeses = ($validated['edad_anos'] * 12) + $validated['edad_meses'];
        if ($validated['edad_dias'] >= 15) {
            $edadMeses += 1; // Redondear hacia arriba si tiene 15 o más días
        }

        $data = [
            'nombre' => $validated['nombre'],
            'tipo_animal_id' => $validated['tipo_animal_id'],
            'raza_id' => $validated['raza_id'] ?? null,
            'edad' => $edadMeses,
            'tipo_peso_id' => $validated['tipo_peso_id'],
            'peso_actual' => $validated['peso_actual'] ?? null,
            'sexo' => $validated['sexo'] ?? null,
            'cantidad_leche_dia' => $validated['cantidad_leche_dia'] ?? null,
            'descripcion' => $validated['descripcion'] ?? null,
            'precio' => $validated['precio'] ?? null,
            'stock' => $validated['stock'],
            'categoria_id' => $validated['categoria_id'],
            'ubicacion' => $validated['ubicacion'] ?? null,
            'latitud' => $validated['latitud'] ?? null,
            'longitud' => $validated['longitud'] ?? null,
            'departamento' => $validated['departamento'] ?? null,
            'municipio' => $validated['municipio'] ?? null,
            'provincia' => $validated['provincia'] ?? null,
            'ciudad' => $validated['ciudad'] ?? null,
            // Mantener la fecha de publicación existente, o establecerla si no existe
            'fecha_publicacion' => $ganado->fecha_publicacion ?? now(),
            'dato_sanitario_id' => $validated['dato_sanitario_id'] ?? null,
        ];

        // Eliminar imágenes marcadas para eliminar
        if ($request->has('imagenes_eliminar')) {
            foreach ($request->imagenes_eliminar as $imagenId) {
                $imagen = GanadoImagen::find($imagenId);
                if ($imagen && $imagen->ganado_id === $ganado->id) {
                    $rutaImagen = GanadoImagen::normalizeStoredPathValue($imagen->ruta);
                    if ($rutaImagen && Storage::disk('public')->exists($rutaImagen)) {
                        Storage::disk('public')->delete($rutaImagen);
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
                        $ruta = Ganado::normalizeStoredPathValue($imagen->store('ganados', 'public'));
                        GanadoImagen::create([
                            'ganado_id' => $ganado->id,
                            'ruta' => $ruta,
                            'orden' => $orden++,
                        ]);
                    }
                }
            }
        }

        $ganado->update($data);
        $this->sincronizarImagenPrincipal($ganado);

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
            $rutaImagen = GanadoImagen::normalizeStoredPathValue($imagen->ruta);
            if ($rutaImagen && Storage::disk('public')->exists($rutaImagen)) {
                Storage::disk('public')->delete($rutaImagen);
            }
        }

        // Eliminar imagen antigua si existe
        $rutaImagenPrincipal = Ganado::normalizeStoredPathValue($ganado->imagen);
        if ($rutaImagenPrincipal && Storage::disk('public')->exists($rutaImagenPrincipal)) {
            Storage::disk('public')->delete($rutaImagenPrincipal);
        }

        $ganado->delete();
        return redirect()->route('ganados.index')
            ->with('success', 'Ganado eliminado correctamente.');
    }

    private function sincronizarImagenPrincipal(Ganado $ganado): void
    {
        $rutaPrincipal = $ganado->imagenes()->orderBy('orden')->value('ruta');
        $rutaPrincipal = Ganado::normalizeStoredPathValue($rutaPrincipal);

        if ($ganado->imagen !== $rutaPrincipal) {
            $ganado->updateQuietly(['imagen' => $rutaPrincipal]);
        }
    }
}
