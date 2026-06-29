<?php

namespace App\Http\Controllers;

use App\Models\Ganado;
use App\Models\Categoria;
use App\Models\TipoAnimal;
use App\Models\TipoPeso;
use App\Models\Raza;
use App\Models\DatoSanitario;
use App\Models\GanadoImagen;
use App\Models\Proposito;
use App\Models\UbicacionGanado;
use App\Models\UbicacionGeograficaGanado;
use App\Services\GeocodificacionService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\Rule;

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
        $ganado->load(array_merge($this->relacionesGanado(), ['user.role', 'resenas.comprador']));
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
        $propositos   = Proposito::orderBy('nombre')->get();
        $datosSanitarios = DatoSanitario::with(['vacunacion', 'tratamientoMedicamento'])
            ->orderBy('id', 'desc')
            ->get();
        return view('ganados.create', compact(
            'tipo_animals',
            'categorias',
            'tipoPesos',
            'razas',
            'propositos',
            'datosSanitarios'
        ));
    }


    /**
     * Guarda un nuevo registro.
     */
    public function store(Request $request)
    {
        // 1. Validación alineada al Mockup
        $request->validate(array_merge([
            'modalidad'      => 'required|string',
            'tipo_animal_id' => 'required|exists:tipo_animals,id',
            'raza_id'        => 'required',
            'nombre'         => 'required|string|max:255',
            'stock'          => 'required|integer|min:1',
            'descripcion'    => 'required|string',
            'precio'         => 'required|numeric|min:0',
            'forma_cobro'    => 'nullable|string|max:255',
            'edad_modo'      => 'nullable|in:edad,fecha_nacimiento',
            'edad_valor'     => [
                Rule::requiredIf(fn () => $request->modalidad !== 'Genetica' && $request->input('edad_modo', 'edad') === 'edad'),
                'nullable',
                'integer',
                'min:0',
            ],
            'unidad_edad'    => [
                Rule::requiredIf(fn () => $request->modalidad !== 'Genetica' && $request->input('edad_modo', 'edad') === 'edad'),
                'nullable',
                'in:Meses,Años',
            ],
            'fecha_nacimiento' => [
                Rule::requiredIf(fn () => $request->modalidad !== 'Genetica' && $request->input('edad_modo') === 'fecha_nacimiento'),
                'nullable',
                'date',
                'before_or_equal:today',
            ],
            'imagenes.*'     => 'nullable|file|mimes:jpg,jpeg,png,gif,webp|max:10240',
            'documento_pdf'  => 'nullable|mimes:pdf|max:10240',
            'latitud'        => 'required|numeric',
            'longitud'       => 'required|numeric',
        ], $this->reglasValidacionSanitaria()), $this->mensajesValidacionSanitaria());

        $edadGanado = $this->resolverEdadGanado($request);

        // 2. Guardar datos principales
        $data = [
            'nombre'         => $request->nombre,
            'user_id'        => auth()->id(),
            'categoria_id'   => $this->categoriaAnimales()->id,
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
            'edad'             => $edadGanado['edad'],
            'edad_valor'       => $edadGanado['edad_valor'],
            'unidad_edad'      => $edadGanado['unidad_edad'],
            'fecha_nacimiento' => $edadGanado['fecha_nacimiento'],
            'sexo'             => $request->sexo,
            'descripcion'      => $request->descripcion,
        ]);

        $ganado->datoProductivo()->create([
            'peso_actual' => $request->peso_actual,
            'unidad_peso' => $request->unidad_peso,
            'tipo_pesaje' => $request->tipo_pesaje,
        ]);

        $ganado->datoComercial()->create([
            'precio'            => $request->precio,
            'stock'             => $request->stock,
            'forma_cobro'       => $request->input('forma_cobro', 'Contacto directo') ?: 'Contacto directo',
            'fecha_publicacion' => now(),
        ]);

        // 4. Manejo completo de datos sanitarios y certificados
        $this->guardarDatosSanitariosGanado($ganado, $request);

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
        $propositos   = Proposito::orderBy('nombre')->get();
        $datosSanitarios = DatoSanitario::with(['vacunacion', 'tratamientoMedicamento'])
            ->orderBy('id', 'desc')
            ->get();
        return view('ganados.edit', compact(
            'ganado',
            'tipo_animals',
            'categorias',
            'tipoPesos',
            'razas',
            'propositos',
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

        $request->validate(array_merge([
            'modalidad'      => 'required|string',
            'tipo_animal_id' => 'required|exists:tipo_animals,id',
            'raza_id'        => 'required',
            'nombre'         => 'required|string|max:255',
            'stock'          => 'required|integer|min:1',
            'descripcion'    => 'required|string',
            'precio'         => 'required|numeric|min:0',
            'forma_cobro'    => 'nullable|string|max:255',
            'edad_modo'      => 'nullable|in:edad,fecha_nacimiento',
            'edad_valor'     => [
                Rule::requiredIf(fn () => $request->modalidad !== 'Genetica' && $request->input('edad_modo', 'edad') === 'edad'),
                'nullable',
                'integer',
                'min:0',
            ],
            'unidad_edad'    => [
                Rule::requiredIf(fn () => $request->modalidad !== 'Genetica' && $request->input('edad_modo', 'edad') === 'edad'),
                'nullable',
                'in:Meses,Años',
            ],
            'fecha_nacimiento' => [
                Rule::requiredIf(fn () => $request->modalidad !== 'Genetica' && $request->input('edad_modo') === 'fecha_nacimiento'),
                'nullable',
                'date',
                'before_or_equal:today',
            ],
            'imagenes.*'     => 'nullable|file|mimes:jpg,jpeg,png,gif,webp|max:10240',
            'documento_pdf'  => 'nullable|mimes:pdf|max:10240',
            'latitud'        => 'required|numeric',
            'longitud'       => 'required|numeric',
        ], $this->reglasValidacionSanitaria()), $this->mensajesValidacionSanitaria());

        $edadGanado = $this->resolverEdadGanado($request);

        $data = [
            'nombre'         => $request->nombre,
            'categoria_id'   => $this->categoriaAnimales()->id,
            'tipo_animal_id' => $request->tipo_animal_id,
            'raza_id'        => $request->raza_id !== 'Cruce/Mestizo' ? $request->raza_id : null,
            'modalidad'      => $request->modalidad,
            'proposito'      => $request->proposito,
            'tipo_genetica'  => $request->tipo_genetica,
        ];

        $this->agregarDatosUbicacion($data, $request);
        $this->sincronizarUbicacionNormalizada($data, $ganado);

        // Manejo completo de datos sanitarios y certificados
        $this->guardarDatosSanitariosGanado($ganado, $request);

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
                'edad'             => $edadGanado['edad'],
                'edad_valor'       => $edadGanado['edad_valor'],
                'unidad_edad'      => $edadGanado['unidad_edad'],
                'fecha_nacimiento' => $edadGanado['fecha_nacimiento'],
                'sexo'             => $request->sexo,
                'descripcion'      => $request->descripcion,
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
                'forma_cobro'       => $request->input('forma_cobro', 'Contacto directo') ?: 'Contacto directo',
                'fecha_publicacion' => $ganado->fecha_publicacion ?? now(),
            ]
        );

        return redirect()->route('ganados.index')
            ->with('success', 'Registro actualizado correctamente.');
    }

    private function categoriaAnimales(): Categoria
    {
        return Categoria::firstOrCreate(
            ['nombre' => 'Animales'],
            [
                'tipo' => 'ganado',
                'descripcion' => 'Categoría para publicaciones de animales.',
            ]
        );
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
            'datoSanitario.tratamientoMedicamento',
            'datoSanitario.vacunacion.imagenPrincipal',
            'datoSanitario.marcaAnimal.imagenPrincipal',
            'datoSanitario.datoDueno',
            'datoSanitario.logroReconocimiento.bellezaEstructura',
            'datoSanitario.logroReconocimiento.produccionLeche',
            'datoSanitario.logroReconocimiento.produccionCarne',
            'datoSanitario.logroReconocimiento.reproduccionLogro',
            'datoSanitario.imagenCertificadoCampeonPrincipal',
            'datoSanitario.archivoArbolGenealogicoPrincipal',
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

    private function reglasValidacionSanitaria(): array
    {
        return [
            'has_sanity' => 'nullable|boolean',
            'vacuna' => 'nullable|string',
            'vacunado_fiebre_aftosa' => 'nullable|boolean',
            'vacunado_antirabica' => 'nullable|boolean',
            'tratamiento' => 'nullable|string',
            'medicamento' => 'nullable|string',
            'fecha_aplicacion' => 'nullable|date|before_or_equal:today',
            'proxima_fecha' => 'nullable|date|after:today',
            'veterinario' => 'nullable|string',
            'observaciones' => 'nullable|string',
            'certificado_imagen' => 'nullable|file|mimes:jpg,jpeg,png,gif,webp|max:5120',
            'certificado_campeon_imagen' => 'nullable|file|mimes:jpg,jpeg,png,gif,webp|max:5120',
            'arbol_genealogico' => 'nullable|file|mimes:pdf,jpg,jpeg,png,gif,webp|max:10240',
            'marca_ganado' => 'nullable|string|max:255',
            'marca_ganado_foto' => 'nullable|file|mimes:jpg,jpeg,png,gif,webp|max:5120',
            'senal_numero' => 'nullable|string|max:255',
            'nombre_dueno' => 'nullable|string|max:255',
            'carnet_dueno_foto' => 'nullable|file|mimes:jpg,jpeg,png,gif,webp|max:5120',
            'logro_campeon_raza' => 'nullable|boolean',
            'logro_gran_campeon_macho' => 'nullable|boolean',
            'logro_gran_campeon_hembra' => 'nullable|boolean',
            'logro_mejor_ubre' => 'nullable|boolean',
            'logro_campeona_litros_dia' => 'nullable|boolean',
            'logro_mejor_lactancia' => 'nullable|boolean',
            'logro_mejor_calidad_leche' => 'nullable|boolean',
            'logro_mejor_novillo' => 'nullable|boolean',
            'logro_gran_campeon_carne' => 'nullable|boolean',
            'logro_mejor_semental' => 'nullable|boolean',
            'logro_mejor_madre' => 'nullable|boolean',
            'logro_mejor_padre' => 'nullable|boolean',
            'logro_mejor_fertilidad' => 'nullable|boolean',
        ];
    }

    private function mensajesValidacionSanitaria(): array
    {
        return [
            'fecha_aplicacion.before_or_equal' => 'La fecha de aplicación debe ser hoy o una fecha pasada.',
            'proxima_fecha.after' => 'La próxima fecha debe ser una fecha futura.',
            'imagenes.*.mimes' => 'Las fotos del ganado deben ser JPG, PNG, GIF o WEBP. No se aceptan HEIC, HEIF ni AVIF.',
            'certificado_imagen.mimes' => 'El certificado SENASAG debe ser una imagen JPG, PNG, GIF o WEBP.',
            'certificado_campeon_imagen.mimes' => 'El certificado de campeón debe ser una imagen JPG, PNG, GIF o WEBP.',
            'marca_ganado_foto.mimes' => 'La foto de la marca debe ser JPG, PNG, GIF o WEBP.',
            'carnet_dueno_foto.mimes' => 'La foto del carnet debe ser JPG, PNG, GIF o WEBP.',
            'arbol_genealogico.mimes' => 'El árbol genealógico debe ser PDF, JPG, PNG, GIF o WEBP.',
        ];
    }

    private function guardarDatosSanitariosGanado(Ganado $ganado, Request $request): void
    {
        $datoSanitario = $ganado->datoSanitario()->first();
        $debeGuardar = $request->boolean('has_sanity') || $datoSanitario || $this->requestTieneDatosSanitarios($request);

        if (! $debeGuardar) {
            return;
        }

        $pdfPath = $datoSanitario->documento_pdf ?? null;
        if ($request->hasFile('documento_pdf')) {
            $this->eliminarArchivoPublico($pdfPath);
            $pdfPath = $request->file('documento_pdf')->store('sanidad_pdfs', 'public');
        }

        $datoSanitario = $ganado->datosSanitarios()->updateOrCreate(
            ['ganado_id' => $ganado->id],
            [
                'has_sanity' => $request->boolean('has_sanity') || $this->requestTieneDatosSanitarios($request),
                'documento_pdf' => $pdfPath,
            ]
        );

        $datosNormalizados = $request->only([
            'vacuna',
            'tratamiento',
            'medicamento',
            'fecha_aplicacion',
            'proxima_fecha',
            'veterinario',
            'observaciones',
            'marca_ganado',
            'senal_numero',
            'nombre_dueno',
        ]);

        $datosNormalizados['ganado_id'] = $ganado->id;
        $datosNormalizados['vacunado_fiebre_aftosa'] = $request->has('vacunado_fiebre_aftosa');
        $datosNormalizados['vacunado_antirabica'] = $request->has('vacunado_antirabica');

        $this->agregarLogrosReconocimientosSanitarios($datosNormalizados, $request);

        if ($request->hasFile('certificado_imagen')) {
            $this->eliminarArchivoPublico($datoSanitario->certificado_imagen);
            $datosNormalizados['certificado_imagen'] = $request->file('certificado_imagen')->store('certificados_senasag', 'public');
        }

        if ($request->hasFile('certificado_campeon_imagen')) {
            $this->eliminarArchivoPublico($datoSanitario->certificado_campeon_imagen);
            $datosNormalizados['certificado_campeon_imagen'] = $request->file('certificado_campeon_imagen')->store('certificados_campeon', 'public');
        }

        if ($request->hasFile('arbol_genealogico')) {
            $this->eliminarArchivoPublico($datoSanitario->arbol_genealogico);
            $datosNormalizados['arbol_genealogico'] = $request->file('arbol_genealogico')->store('arboles_genealogicos', 'public');
        }

        if ($request->hasFile('marca_ganado_foto')) {
            $this->eliminarArchivoPublico($datoSanitario->marca_ganado_foto);
            $datosNormalizados['marca_ganado_foto'] = $request->file('marca_ganado_foto')->store('marcas_ganado', 'public');
        }

        if ($request->hasFile('carnet_dueno_foto')) {
            $this->eliminarArchivoPublico($datoSanitario->carnet_dueno_foto);
            $datosNormalizados['carnet_dueno_foto'] = $request->file('carnet_dueno_foto')->store('carnets_duenos', 'public');
        }

        $this->sincronizarDatosSanitariosNormalizados($datoSanitario, $datosNormalizados);
    }

    private function requestTieneDatosSanitarios(Request $request): bool
    {
        $campos = [
            'vacuna',
            'tratamiento',
            'medicamento',
            'fecha_aplicacion',
            'proxima_fecha',
            'veterinario',
            'observaciones',
            'marca_ganado',
            'senal_numero',
            'nombre_dueno',
        ];

        $archivos = [
            'documento_pdf',
            'certificado_imagen',
            'certificado_campeon_imagen',
            'arbol_genealogico',
            'marca_ganado_foto',
            'carnet_dueno_foto',
        ];

        $checks = [
            'vacunado_fiebre_aftosa',
            'vacunado_antirabica',
            'logro_campeon_raza',
            'logro_gran_campeon_macho',
            'logro_gran_campeon_hembra',
            'logro_mejor_ubre',
            'logro_campeona_litros_dia',
            'logro_mejor_lactancia',
            'logro_mejor_calidad_leche',
            'logro_mejor_novillo',
            'logro_gran_campeon_carne',
            'logro_mejor_semental',
            'logro_mejor_madre',
            'logro_mejor_padre',
            'logro_mejor_fertilidad',
        ];

        return collect($campos)->contains(fn ($campo) => filled($request->input($campo)))
            || collect($archivos)->contains(fn ($campo) => $request->hasFile($campo))
            || collect($checks)->contains(fn ($campo) => $request->has($campo));
    }

    private function agregarLogrosReconocimientosSanitarios(array &$data, Request $request): void
    {
        foreach ([
            'logro_campeon_raza',
            'logro_gran_campeon_macho',
            'logro_gran_campeon_hembra',
            'logro_mejor_ubre',
            'logro_campeona_litros_dia',
            'logro_mejor_lactancia',
            'logro_mejor_calidad_leche',
            'logro_mejor_novillo',
            'logro_gran_campeon_carne',
            'logro_mejor_semental',
            'logro_mejor_madre',
            'logro_mejor_padre',
            'logro_mejor_fertilidad',
        ] as $campo) {
            $data[$campo] = $request->has($campo);
        }
    }

    private function sincronizarDatosSanitariosNormalizados(DatoSanitario $datoSanitario, array $data): void
    {
        $tratamientoData = [
            'tratamiento' => $data['tratamiento'] ?? null,
            'medicamento' => $data['medicamento'] ?? null,
            'fecha_aplicacion' => $data['fecha_aplicacion'] ?? null,
            'proxima_fecha' => $data['proxima_fecha'] ?? null,
            'veterinario' => $data['veterinario'] ?? null,
            'observaciones' => $data['observaciones'] ?? null,
        ];

        if ($datoSanitario->tratamientoMedicamento || $this->tieneDatos($tratamientoData)) {
            $datoSanitario->tratamientoMedicamento()->updateOrCreate(
                ['dato_sanitario_id' => $datoSanitario->id],
                $tratamientoData
            );
        }

        $vacunacion = $datoSanitario->vacunacion()->updateOrCreate(
            ['dato_sanitario_id' => $datoSanitario->id],
            [
                'vacuna' => $data['vacuna'] ?? null,
                'vacunado_fiebre_aftosa' => $data['vacunado_fiebre_aftosa'] ?? false,
                'vacunado_antirabica' => $data['vacunado_antirabica'] ?? false,
            ]
        );

        if (! empty($data['certificado_imagen'])) {
            foreach ($vacunacion->imagenes as $imagen) {
                $this->eliminarArchivoPublico($imagen->ruta);
                $imagen->delete();
            }

            $vacunacion->imagenes()->create([
                'ruta' => $data['certificado_imagen'],
                'orden' => 0,
            ]);
        }

        $marcaData = [
            'marca_ganado' => $data['marca_ganado'] ?? null,
            'senal_numero' => $data['senal_numero'] ?? null,
        ];
        $marcaFoto = $data['marca_ganado_foto'] ?? null;

        if ($datoSanitario->marcaAnimal || $this->tieneDatos($marcaData) || filled($marcaFoto)) {
            $marcaAnimal = $datoSanitario->marcaAnimal()->updateOrCreate(
                ['dato_sanitario_id' => $datoSanitario->id],
                $marcaData
            );

            if (filled($marcaFoto)) {
                foreach ($marcaAnimal->imagenes as $imagen) {
                    $this->eliminarArchivoPublico($imagen->ruta);
                    $imagen->delete();
                }

                $marcaAnimal->imagenes()->create([
                    'ruta' => $marcaFoto,
                    'orden' => 0,
                ]);
            }
        }

        $duenoData = ['nombre_dueno' => $data['nombre_dueno'] ?? null];
        if (array_key_exists('carnet_dueno_foto', $data)) {
            $duenoData['carnet_dueno_foto'] = $data['carnet_dueno_foto'];
        }

        if ($datoSanitario->datoDueno || $this->tieneDatos($duenoData)) {
            $datoSanitario->datoDueno()->updateOrCreate(
                ['dato_sanitario_id' => $datoSanitario->id],
                $duenoData
            );
        }

        if (! empty($data['certificado_campeon_imagen'])) {
            foreach ($datoSanitario->imagenesCertificadoCampeon as $imagen) {
                $this->eliminarArchivoPublico($imagen->ruta);
                $imagen->delete();
            }

            $datoSanitario->imagenesCertificadoCampeon()->create([
                'ruta' => $data['certificado_campeon_imagen'],
                'orden' => 0,
            ]);
        }

        if (! empty($data['arbol_genealogico'])) {
            foreach ($datoSanitario->archivosArbolGenealogico as $archivo) {
                $this->eliminarArchivoPublico($archivo->ruta);
                $archivo->delete();
            }

            $datoSanitario->archivosArbolGenealogico()->create([
                'ruta' => $data['arbol_genealogico'],
                'orden' => 0,
            ]);
        }

        $logroReconocimiento = $datoSanitario->logroReconocimiento()->updateOrCreate(
            ['dato_sanitario_id' => $datoSanitario->id],
            []
        );

        $logroReconocimiento->bellezaEstructura()->updateOrCreate(
            ['logro_reconocimiento_id' => $logroReconocimiento->id],
            [
                'logro_campeon_raza' => $data['logro_campeon_raza'] ?? false,
                'logro_gran_campeon_macho' => $data['logro_gran_campeon_macho'] ?? false,
                'logro_gran_campeon_hembra' => $data['logro_gran_campeon_hembra'] ?? false,
                'logro_mejor_ubre' => $data['logro_mejor_ubre'] ?? false,
            ]
        );

        $logroReconocimiento->produccionLeche()->updateOrCreate(
            ['logro_reconocimiento_id' => $logroReconocimiento->id],
            [
                'logro_campeona_litros_dia' => $data['logro_campeona_litros_dia'] ?? false,
                'logro_mejor_lactancia' => $data['logro_mejor_lactancia'] ?? false,
                'logro_mejor_calidad_leche' => $data['logro_mejor_calidad_leche'] ?? false,
            ]
        );

        $logroReconocimiento->produccionCarne()->updateOrCreate(
            ['logro_reconocimiento_id' => $logroReconocimiento->id],
            [
                'logro_mejor_novillo' => $data['logro_mejor_novillo'] ?? false,
                'logro_gran_campeon_carne' => $data['logro_gran_campeon_carne'] ?? false,
                'logro_mejor_semental' => $data['logro_mejor_semental'] ?? false,
            ]
        );

        $logroReconocimiento->reproduccionLogro()->updateOrCreate(
            ['logro_reconocimiento_id' => $logroReconocimiento->id],
            [
                'logro_mejor_madre' => $data['logro_mejor_madre'] ?? false,
                'logro_mejor_padre' => $data['logro_mejor_padre'] ?? false,
                'logro_mejor_fertilidad' => $data['logro_mejor_fertilidad'] ?? false,
            ]
        );
    }

    private function eliminarArchivoPublico(?string $path): void
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }

    private function tieneDatos(array $data): bool
    {
        return collect($data)->contains(fn ($value) => filled($value));
    }

    private function resolverEdadGanado(Request $request): array
    {
        if ($request->modalidad === 'Genetica') {
            return [
                'edad' => 0,
                'edad_valor' => null,
                'unidad_edad' => null,
                'fecha_nacimiento' => null,
            ];
        }

        if ($request->input('edad_modo', 'edad') === 'fecha_nacimiento' && $request->filled('fecha_nacimiento')) {
            $fechaNacimiento = Carbon::parse($request->fecha_nacimiento)->startOfDay();
            $edadMeses = (int) max(0, $fechaNacimiento->diffInMonths(now()->startOfDay()));

            return [
                'edad' => $edadMeses,
                'edad_valor' => $edadMeses,
                'unidad_edad' => 'Meses',
                'fecha_nacimiento' => $fechaNacimiento->toDateString(),
            ];
        }

        $edadValor = (int) $request->edad_valor;
        $edadMeses = $request->unidad_edad === 'Años' ? $edadValor * 12 : $edadValor;

        return [
            'edad' => $edadMeses,
            'edad_valor' => $edadValor,
            'unidad_edad' => $request->unidad_edad ?: 'Meses',
            'fecha_nacimiento' => null,
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
