<?php

namespace App\Http\Controllers;

use App\Models\Ganado;
use App\Models\Categoria;
use App\Models\TipoAnimal;
use App\Models\TipoPeso;
use App\Models\Raza;
use App\Models\DatoSanitario;
use App\Models\GanadoImagen;
use App\Models\UbicacionGanado;
use App\Models\UbicacionGeograficaGanado;
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
        $ganados = Ganado::with($this->relacionesGanado())
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('ganados.index', compact('ganados'));
    }

    /**
     * Muestra el detalle de un ganado.
     */
    public function show(Ganado $ganado)
    {
        $ganado->load(array_merge($this->relacionesGanado(), ['user.role']));
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
        $datosSanitarios = DatoSanitario::with(['vacunacion', 'tratamientoMedicamento'])
            ->orderBy('id', 'desc')
            ->get();
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
        // 1. Validación alineada al Mockup
        $request->validate([
            'modalidad'      => 'required|string',
            'tipo_animal_id' => 'required|exists:tipo_animals,id',
            'raza_id'        => 'required',
            'nombre'         => 'required|string|max:255',
            'stock'          => 'required|integer|min:1',
            'descripcion'    => 'required|string',
            'precio'         => 'required|numeric|min:0',
            'forma_cobro'    => 'required|string',
            'imagenes.*'     => 'nullable|image|max:10240',
            'documento_pdf'  => 'nullable|mimes:pdf|max:10240',
            'latitud'        => 'required|numeric',
            'longitud'       => 'required|numeric',
        ]);

        // Calcular edad en meses para compatibilidad legacy
        $edadMeses = 0;
        if ($request->modalidad !== 'Genetica') {
            $edadMeses = $request->unidad_edad === 'Años' ? ($request->edad_valor * 12) : $request->edad_valor;
        }

        // 2. Guardar datos principales
        $data = [
            'nombre'         => $request->nombre,
            'user_id'        => auth()->id(),
            'tipo_animal_id' => $request->tipo_animal_id,
            'raza_id'        => $request->raza_id !== 'Cruce/Mestizo' ? $request->raza_id : null,
            'modalidad'      => $request->modalidad,
            'proposito'      => $request->proposito,
            'tipo_genetica'  => $request->tipo_genetica,
        ];

        $this->agregarDatosUbicacion($data, $request);
        $this->sincronizarUbicacionNormalizada($data);

        $ganado = Ganado::create($data);

        // 3. Sincronizar Relaciones con los nuevos campos
        $ganado->caracteristica()->create([
            'edad'        => $edadMeses,
            'edad_valor'  => $request->edad_valor,
            'unidad_edad' => $request->unidad_edad,
            'sexo'        => $request->sexo,
            'descripcion' => $request->descripcion,
        ]);

        $ganado->datoProductivo()->create([
            'peso_actual' => $request->peso_actual,
            'unidad_peso' => $request->unidad_peso,
            'tipo_pesaje' => $request->tipo_pesaje,
        ]);

        $ganado->datoComercial()->create([
            'precio'            => $request->precio,
            'stock'             => $request->stock,
            'forma_cobro'       => $request->forma_cobro,
            'fecha_publicacion' => now(),
        ]);

        // 4. Manejo del PDF de Sanidad
        if ($request->has_sanity === '1' || $request->has_sanity === 'true') {
            $pdfPath = null;
            if ($request->hasFile('documento_pdf')) {
                $pdfPath = $request->file('documento_pdf')->store('sanidad_pdfs', 'public');
            }
            $ganado->datosSanitarios()->create([
                'has_sanity'    => true,
                'documento_pdf' => $pdfPath,
            ]);
        }

        // 5. Manejo de Imágenes (Max 5 según mockup)
        if ($request->hasFile('imagenes')) {
            $orden = 0;
            $imagenes = array_slice($request->file('imagenes'), 0, 5);
            foreach ($imagenes as $imagen) {
                if ($imagen->isValid()) {
                    $ruta = $imagen->store('ganados', 'public');
                    GanadoImagen::create([
                        'ganado_id' => $ganado->id,
                        'ruta'      => $ruta,
                        'orden'     => $orden++,
                    ]);
                }
            }
        }

        return redirect()->route('ganados.index')
            ->with('success', 'Publicación creada exitosamente.');
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

        $ganado->load($this->relacionesGanado());
        $tipo_animals = TipoAnimal::orderBy('nombre')->get();
        $categorias   = Categoria::orderBy('nombre')->get();
        $tipoPesos    = TipoPeso::orderBy('nombre')->get();
        $razas        = Raza::where('tipo_animal_id', $ganado->tipo_animal_id)->get();
        $datosSanitarios = DatoSanitario::with(['vacunacion', 'tratamientoMedicamento'])
            ->orderBy('id', 'desc')
            ->get();
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
            'modalidad'      => 'required|string',
            'tipo_animal_id' => 'required|exists:tipo_animals,id',
            'raza_id'        => 'required',
            'nombre'         => 'required|string|max:255',
            'stock'          => 'required|integer|min:1',
            'descripcion'    => 'required|string',
            'precio'         => 'required|numeric|min:0',
            'forma_cobro'    => 'required|string',
            'imagenes.*'     => 'nullable|image|max:10240',
            'documento_pdf'  => 'nullable|mimes:pdf|max:10240',
            'latitud'        => 'required|numeric',
            'longitud'       => 'required|numeric',
        ]);

        // Calcular edad en meses para compatibilidad legacy
        $edadMeses = 0;
        if ($request->modalidad !== 'Genetica') {
            $edadMeses = $request->unidad_edad === 'Años' ? ($request->edad_valor * 12) : $request->edad_valor;
        }

        $data = [
            'nombre'         => $request->nombre,
            'tipo_animal_id' => $request->tipo_animal_id,
            'raza_id'        => $request->raza_id !== 'Cruce/Mestizo' ? $request->raza_id : null,
            'modalidad'      => $request->modalidad,
            'proposito'      => $request->proposito,
            'tipo_genetica'  => $request->tipo_genetica,
        ];

        $this->agregarDatosUbicacion($data, $request);
        $this->sincronizarUbicacionNormalizada($data, $ganado);

        // Manejo del PDF de Sanidad
        if ($request->has_sanity === '1' || $request->has_sanity === 'true') {
            $datosSanitario = $ganado->datosSanitarios()->latest('id')->first();
            $pdfPath = $datosSanitario->documento_pdf ?? null;

            if ($request->hasFile('documento_pdf')) {
                if ($pdfPath && Storage::disk('public')->exists($pdfPath)) {
                    Storage::disk('public')->delete($pdfPath);
                }
                $pdfPath = $request->file('documento_pdf')->store('sanidad_pdfs', 'public');
            }

            $ganado->datosSanitarios()->updateOrCreate(
                ['ganado_id' => $ganado->id],
                [
                    'has_sanity'    => true,
                    'documento_pdf' => $pdfPath,
                ]
            );
        }

        // Manejo de Imágenes (Max 5 según mockup)
        if ($request->hasFile('imagenes')) {
            $totalImagenesActuales = $ganado->imagenes()->count();
            $maxOrden = $ganado->imagenes()->max('orden') ?? -1;

            if ($totalImagenesActuales < 5) {
                $espaciosDisponibles = 5 - $totalImagenesActuales;
                $orden = $maxOrden + 1;
                $imagenes = array_slice($request->file('imagenes'), 0, $espaciosDisponibles);
                foreach ($imagenes as $imagen) {
                    if ($imagen->isValid()) {
                        $ruta = $imagen->store('ganados', 'public');
                        GanadoImagen::create([
                            'ganado_id' => $ganado->id,
                            'ruta'      => $ruta,
                            'orden'     => $orden++,
                        ]);
                    }
                }
            }
        }

        $ganado->update($data);

        $ganado->caracteristica()->updateOrCreate(
            ['ganado_id' => $ganado->id],
            [
                'edad'        => $edadMeses,
                'edad_valor'  => $request->edad_valor,
                'unidad_edad' => $request->unidad_edad,
                'sexo'        => $request->sexo,
                'descripcion' => $request->descripcion,
            ]
        );

        $ganado->datoProductivo()->updateOrCreate(
            ['ganado_id' => $ganado->id],
            [
                'peso_actual' => $request->peso_actual,
                'unidad_peso' => $request->unidad_peso,
                'tipo_pesaje' => $request->tipo_pesaje,
            ]
        );

        $ganado->datoComercial()->updateOrCreate(
            ['ganado_id' => $ganado->id],
            [
                'precio'            => $request->precio,
                'stock'             => $request->stock,
                'forma_cobro'       => $request->forma_cobro,
                'fecha_publicacion' => $ganado->fecha_publicacion ?? now(),
            ]
        );

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

        $ganado->delete();
        return redirect()->route('ganados.index')
            ->with('success', 'Ganado eliminado correctamente.');
    }

    private function sincronizarUbicacionNormalizada(array &$data, ?Ganado $ganado = null): void
    {
        $campos = ['ubicacion', 'latitud', 'longitud', 'departamento', 'municipio', 'provincia', 'ciudad'];

        $recibioCamposUbicacion = collect($campos)->contains(fn($campo) => array_key_exists($campo, $data));

        if (!$recibioCamposUbicacion) {
            return;
        }

        foreach ($campos as $campo) {
            if (array_key_exists($campo, $data) && $data[$campo] === '') {
                $data[$campo] = null;
            }
        }

        $tieneDatosUbicacion = collect($campos)->contains(fn($campo) => !empty($data[$campo]));

        if (!$tieneDatosUbicacion) {
            $data['ubicacion_ganado_id'] = null;
            $this->quitarCamposUbicacionAntiguos($data);
            return;
        }

        $ubicacionGeografica = UbicacionGeograficaGanado::firstOrCreate([
            'departamento' => $data['departamento'] ?? null,
            'municipio' => $data['municipio'] ?? null,
            'provincia' => $data['provincia'] ?? null,
            'ciudad' => $data['ciudad'] ?? null,
        ]);

        $ubicacionData = [
            'ubicacion' => $data['ubicacion'] ?? null,
            'latitud' => $data['latitud'] ?? null,
            'longitud' => $data['longitud'] ?? null,
            'ubicacion_geografica_ganado_id' => $ubicacionGeografica->id,
        ];

        if ($ganado && $ganado->ubicacion_ganado_id) {
            $ubicacion = UbicacionGanado::find($ganado->ubicacion_ganado_id);

            if ($ubicacion) {
                $ubicacion->update($ubicacionData);
                $data['ubicacion_ganado_id'] = $ubicacion->id;
                $this->quitarCamposUbicacionAntiguos($data);
                return;
            }
        }

        $data['ubicacion_ganado_id'] = UbicacionGanado::create($ubicacionData)->id;
        $this->quitarCamposUbicacionAntiguos($data);
    }

    private function agregarDatosUbicacion(array &$data, Request $request): void
    {
        foreach (['ubicacion', 'latitud', 'longitud', 'departamento', 'municipio', 'provincia', 'ciudad'] as $campo) {
            $data[$campo] = $request->{$campo};
        }
    }

    private function quitarCamposUbicacionAntiguos(array &$data): void
    {
        foreach (['ubicacion', 'latitud', 'longitud', 'departamento', 'municipio', 'provincia', 'ciudad'] as $campo) {
            unset($data[$campo]);
        }
    }

    private function relacionesGanado(): array
    {
        return [
            'categoria',
            'raza',
            'tipoAnimal',
            'tipoPeso',
            'datoSanitario',
            'datosSanitarios',
            'imagenes',
            'ubicacionGanado.ubicacionGeografica',
            'datoProductivo.tipoPeso',
            'datoComercial',
            'caracteristica',
            'genealogia.madre',
            'genealogia.padre',
        ];
    }

    private function sincronizarDatosNormalizados(Ganado $ganado, array $data): void
    {
        $ganado->caracteristica()->updateOrCreate(
            ['ganado_id' => $ganado->id],
            [
                'edad' => $data['edad'] ?? null,
                'sexo' => $data['sexo'] ?? null,
                'descripcion' => $data['descripcion'] ?? null,
            ]
        );

        $ganado->datoProductivo()->updateOrCreate(
            ['ganado_id' => $ganado->id],
            [
                'tipo_peso_id' => $data['tipo_peso_id'] ?? null,
                'peso_actual' => $data['peso_actual'] ?? null,
                'cantidad_leche_dia' => $data['cantidad_leche_dia'] ?? null,
            ]
        );

        $ganado->datoComercial()->updateOrCreate(
            ['ganado_id' => $ganado->id],
            [
                'precio' => $data['precio'] ?? null,
                'stock' => $data['stock'] ?? 0,
                'fecha_publicacion' => $data['fecha_publicacion'] ?? null,
            ]
        );

        $ganado->genealogia()->updateOrCreate(
            ['ganado_id' => $ganado->id],
            [
                'madre_id' => $data['madre_id'] ?? null,
                'padre_id' => $data['padre_id'] ?? null,
            ]
        );
    }
}
