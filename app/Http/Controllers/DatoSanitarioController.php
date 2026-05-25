<?php

namespace App\Http\Controllers;

use App\Models\DatoSanitario;
use App\Models\Ganado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DatoSanitarioController extends Controller
{
    public function index()
    {
        // Si es admin, mostrar todos. Si es vendedor, solo los de sus ganados o los que creó
        // Cargar relaciones para mostrar información completa
        if (auth()->user()->isAdmin()) {
            $items = DatoSanitario::with($this->relacionesDatoSanitario())
                ->orderBy('id', 'desc')
                ->get();
        } else {
            $ganadoIds = Ganado::where('user_id', auth()->id())->pluck('id');
            $items = DatoSanitario::with($this->relacionesDatoSanitario())
                ->where(function ($query) use ($ganadoIds) {
                    $query->whereIn('ganado_id', $ganadoIds)
                        ->orWhere('user_id', auth()->id());
                })
                ->orderBy('id', 'desc')
                ->get();
        }
        return view('datos_sanitarios.index', compact('items'));
    }

    public function create()
    {
        // Si es admin, mostrar todos los ganados. Si es vendedor, solo los suyos
        // Incluir todos los animales registrados, con o sin fecha de publicación
        if (auth()->user()->isAdmin()) {
            $ganados = Ganado::with(['tipoAnimal', 'raza'])
                ->orderBy('nombre')
                ->get();
        } else {
            $ganados = Ganado::with(['tipoAnimal', 'raza'])
                ->where('user_id', auth()->id())
                ->orderBy('nombre')
                ->get();
        }
        return view('datos_sanitarios.create', compact('ganados'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'ganado_id' => 'nullable|exists:ganados,id',
            'vacuna' => 'nullable|string',
            'vacunado_fiebre_aftosa' => 'nullable|boolean',
            'vacunado_antirabica' => 'nullable|boolean',
            'tratamiento' => 'nullable|string',
            'medicamento' => 'nullable|string',
            'fecha_aplicacion' => 'nullable|date|before_or_equal:today',
            'proxima_fecha' => 'nullable|date|after:today',
            'veterinario' => 'nullable|string',
            'observaciones' => 'nullable|string',
            'certificado_imagen' => 'nullable|image|max:5120', // 5MB máximo
            'certificado_campeon_imagen' => 'nullable|image|max:5120', // 5MB máximo
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
            'arbol_genealogico' => 'nullable|file|mimes:pdf,jpg,jpeg,png,gif|max:10240', // 10MB máximo
            'marca_ganado' => 'nullable|string|max:255',
            'marca_ganado_foto' => 'nullable|image|max:5120', // 5MB máximo
            'senal_numero' => 'nullable|string|max:255',
            'nombre_dueno' => 'nullable|string|max:255',
            'carnet_dueno_foto' => 'nullable|image|max:5120', // 5MB máximo
        ], $this->mensajesValidacionFechas());

        // Verificar que el ganado pertenece al vendedor (si no es admin y hay ganado_id)
        if (!auth()->user()->isAdmin() && $request->ganado_id) {
            $ganado = Ganado::findOrFail($request->ganado_id);
            if ($ganado->user_id !== auth()->id()) {
                return redirect()->route('admin.datos-sanitarios.create')
                    ->with('error', 'No tienes permisos para crear datos sanitarios para este animal.');
            }
        }

        $datosNormalizados = $request->only([
            'ganado_id',
            'vacuna',
            'tratamiento',
            'medicamento',
            'fecha_aplicacion',
            'proxima_fecha',
            'veterinario',
            'observaciones',
            'marca_ganado',
            'senal_numero',
            'nombre_dueno'
        ]);

        $data = $request->only(['ganado_id']);
        $data['user_id'] = auth()->id();

        // Convertir checkboxes a boolean (si vienen como null, serán false)
        $datosNormalizados['vacunado_fiebre_aftosa'] = $request->has('vacunado_fiebre_aftosa') ? true : false;
        $datosNormalizados['vacunado_antirabica'] = $request->has('vacunado_antirabica') ? true : false;

        $this->agregarLogrosReconocimientos($datosNormalizados, $request);

        // Manejar la imagen del certificado
        if ($request->hasFile('certificado_imagen')) {
            $datosNormalizados['certificado_imagen'] = $request->file('certificado_imagen')->store('certificados_senasag', 'public');
        }

        // Manejar la imagen del certificado de campeón
        if ($request->hasFile('certificado_campeon_imagen')) {
            $datosNormalizados['certificado_campeon_imagen'] = $request->file('certificado_campeon_imagen')->store('certificados_campeon', 'public');
        }

        // Manejar el archivo del árbol genealógico
        if ($request->hasFile('arbol_genealogico')) {
            $datosNormalizados['arbol_genealogico'] = $request->file('arbol_genealogico')->store('arboles_genealogicos', 'public');
        }

        // Manejar la imagen de la marca del ganado
        if ($request->hasFile('marca_ganado_foto')) {
            $datosNormalizados['marca_ganado_foto'] = $request->file('marca_ganado_foto')->store('marcas_ganado', 'public');
        }

        // Manejar la imagen del carnet del dueño
        if ($request->hasFile('carnet_dueno_foto')) {
            $datosNormalizados['carnet_dueno_foto'] = $request->file('carnet_dueno_foto')->store('carnets_dueños', 'public');
        }

        // Crear el dato sanitario
        $datoSanitario = DatoSanitario::create($data);
        $this->sincronizarDatosNormalizados($datoSanitario, $datosNormalizados);

        return redirect()->route('admin.datos-sanitarios.index')
            ->with('success', 'Registro sanitario guardado correctamente.');
    }


    public function edit(DatoSanitario $datos_sanitario)
    {
        $datos_sanitario->load([
            'tratamientoMedicamento',
            'vacunacion.imagenPrincipal',
            'marcaAnimal.imagenPrincipal',
            'datoDueno',
            'imagenCertificadoCampeonPrincipal',
            'archivoArbolGenealogicoPrincipal',
        ]);

        // Si es admin, mostrar todos los ganados. Si es vendedor, solo los suyos
        // Incluir todos los animales registrados, con o sin fecha de publicación
        if (auth()->user()->isAdmin()) {
            $ganados = Ganado::with(['tipoAnimal', 'raza'])
                ->orderBy('nombre')
                ->get();
        } else {
            $ganados = Ganado::with(['tipoAnimal', 'raza'])
                ->where('user_id', auth()->id())
                ->orderBy('nombre')
                ->get();
        }

        return view('datos_sanitarios.edit', [
            'datoSanitario' => $datos_sanitario, // renombramos aquí
            'ganados' => $ganados
        ]);
    }


    public function update(Request $request, DatoSanitario $datos_sanitario)
    {
        $request->validate([
            'ganado_id' => 'nullable|exists:ganados,id',
            'vacuna' => 'nullable|string',
            'vacunado_fiebre_aftosa' => 'nullable|boolean',
            'vacunado_antirabica' => 'nullable|boolean',
            'tratamiento' => 'nullable|string',
            'medicamento' => 'nullable|string',
            'fecha_aplicacion' => 'nullable|date|before_or_equal:today',
            'proxima_fecha' => 'nullable|date|after:today',
            'veterinario' => 'nullable|string',
            'observaciones' => 'nullable|string',
            'certificado_imagen' => 'nullable|image|max:5120', // 5MB máximo
            'certificado_campeon_imagen' => 'nullable|image|max:5120', // 5MB máximo
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
            'arbol_genealogico' => 'nullable|file|mimes:pdf,jpg,jpeg,png,gif|max:10240', // 10MB máximo
            'marca_ganado' => 'nullable|string|max:255',
            'marca_ganado_foto' => 'nullable|image|max:5120', // 5MB máximo
            'senal_numero' => 'nullable|string|max:255',
            'nombre_dueno' => 'nullable|string|max:255',
            'carnet_dueno_foto' => 'nullable|image|max:5120', // 5MB máximo
        ], $this->mensajesValidacionFechas());

        // Verificar que el ganado pertenece al vendedor (si no es admin y hay ganado_id)
        if (!auth()->user()->isAdmin() && $request->ganado_id) {
            $ganado = Ganado::findOrFail($request->ganado_id);
            if ($ganado->user_id !== auth()->id()) {
                return redirect()->route('admin.datos-sanitarios.edit', $datos_sanitario)
                    ->with('error', 'No tienes permisos para editar datos sanitarios de este animal.');
            }
        }

        $datosNormalizados = $request->only([
            'ganado_id',
            'vacuna',
            'tratamiento',
            'medicamento',
            'fecha_aplicacion',
            'proxima_fecha',
            'veterinario',
            'observaciones',
            'marca_ganado',
            'senal_numero',
            'nombre_dueno'
        ]);

        $data = $request->only(['ganado_id']);

        // Convertir checkboxes a boolean
        $datosNormalizados['vacunado_fiebre_aftosa'] = $request->has('vacunado_fiebre_aftosa') ? true : false;
        $datosNormalizados['vacunado_antirabica'] = $request->has('vacunado_antirabica') ? true : false;

        $this->agregarLogrosReconocimientos($datosNormalizados, $request);

        // Manejar la imagen del certificado
        if ($request->hasFile('certificado_imagen')) {
            // Eliminar la imagen anterior si existe
            if ($datos_sanitario->certificado_imagen && Storage::disk('public')->exists($datos_sanitario->certificado_imagen)) {
                Storage::disk('public')->delete($datos_sanitario->certificado_imagen);
            }
            $datosNormalizados['certificado_imagen'] = $request->file('certificado_imagen')->store('certificados_senasag', 'public');
        }

        // Manejar la imagen del certificado de campeón
        if ($request->hasFile('certificado_campeon_imagen')) {
            // Eliminar la imagen anterior si existe
            if ($datos_sanitario->certificado_campeon_imagen && Storage::disk('public')->exists($datos_sanitario->certificado_campeon_imagen)) {
                Storage::disk('public')->delete($datos_sanitario->certificado_campeon_imagen);
            }
            $datosNormalizados['certificado_campeon_imagen'] = $request->file('certificado_campeon_imagen')->store('certificados_campeon', 'public');
        }

        // Manejar el archivo del árbol genealógico
        if ($request->hasFile('arbol_genealogico')) {
            // Eliminar el archivo anterior si existe
            if ($datos_sanitario->arbol_genealogico && Storage::disk('public')->exists($datos_sanitario->arbol_genealogico)) {
                Storage::disk('public')->delete($datos_sanitario->arbol_genealogico);
            }
            $datosNormalizados['arbol_genealogico'] = $request->file('arbol_genealogico')->store('arboles_genealogicos', 'public');
        }

        // Manejar la imagen de la marca del ganado
        if ($request->hasFile('marca_ganado_foto')) {
            // Eliminar la imagen anterior si existe
            if ($datos_sanitario->marca_ganado_foto && Storage::disk('public')->exists($datos_sanitario->marca_ganado_foto)) {
                Storage::disk('public')->delete($datos_sanitario->marca_ganado_foto);
            }
            $datosNormalizados['marca_ganado_foto'] = $request->file('marca_ganado_foto')->store('marcas_ganado', 'public');
        }

        // Manejar la imagen del carnet del dueño
        if ($request->hasFile('carnet_dueno_foto')) {
            // Eliminar la imagen anterior si existe
            if ($datos_sanitario->carnet_dueno_foto && Storage::disk('public')->exists($datos_sanitario->carnet_dueno_foto)) {
                Storage::disk('public')->delete($datos_sanitario->carnet_dueno_foto);
            }
            $datosNormalizados['carnet_dueno_foto'] = $request->file('carnet_dueno_foto')->store('carnets_dueños', 'public');
        }

        $datos_sanitario->update($data);
        $this->sincronizarDatosNormalizados($datos_sanitario, $datosNormalizados);

        return redirect()->route('admin.datos-sanitarios.index')
            ->with('success', 'Registro sanitario actualizado correctamente.');
    }


    public function destroy(DatoSanitario $datos_sanitario)
    {
        // Verificar permisos: solo el dueño del ganado, el creador del registro o admin puede eliminar
        if (!auth()->user()->isAdmin()) {
            $tienePermiso = false;

            // Verificar si el usuario creó el registro
            if ($datos_sanitario->user_id === auth()->id()) {
                $tienePermiso = true;
            }

            // Verificar si el ganado pertenece al usuario
            if (!$tienePermiso && $datos_sanitario->ganado) {
                if ($datos_sanitario->ganado->user_id === auth()->id()) {
                    $tienePermiso = true;
                }
            }

            if (!$tienePermiso) {
                return redirect()->route('admin.datos-sanitarios.index')
                    ->with('error', 'No tienes permisos para eliminar este registro sanitario.');
            }
        }

        // Eliminar las imágenes si existen
        if ($datos_sanitario->certificado_imagen && Storage::disk('public')->exists($datos_sanitario->certificado_imagen)) {
            Storage::disk('public')->delete($datos_sanitario->certificado_imagen);
        }
        if ($datos_sanitario->marca_ganado_foto && Storage::disk('public')->exists($datos_sanitario->marca_ganado_foto)) {
            Storage::disk('public')->delete($datos_sanitario->marca_ganado_foto);
        }
        if ($datos_sanitario->carnet_dueno_foto && Storage::disk('public')->exists($datos_sanitario->carnet_dueno_foto)) {
            Storage::disk('public')->delete($datos_sanitario->carnet_dueno_foto);
        }

        $datos_sanitario->delete();

        return redirect()->route('admin.datos-sanitarios.index')
            ->with('success', 'Registro sanitario eliminado.');
    }

    private function relacionesDatoSanitario(): array
    {
        return [
            'ganado.tipoAnimal',
            'ganado.raza',
            'tratamientoMedicamento',
            'vacunacion.imagenPrincipal',
            'marcaAnimal.imagenPrincipal',
            'datoDueno',
            'logroReconocimiento.bellezaEstructura',
            'logroReconocimiento.produccionLeche',
            'logroReconocimiento.produccionCarne',
            'logroReconocimiento.reproduccionLogro',
            'imagenCertificadoCampeonPrincipal',
            'archivoArbolGenealogicoPrincipal',
        ];
    }

    private function sincronizarDatosNormalizados(DatoSanitario $datoSanitario, array $data): void
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

        if (!empty($data['certificado_imagen'])) {
            foreach ($vacunacion->imagenes as $imagen) {
                if (Storage::disk('public')->exists($imagen->ruta)) {
                    Storage::disk('public')->delete($imagen->ruta);
                }
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

            if (filled($marcaFoto) && (array_key_exists('marca_ganado_foto', $data) || !$marcaAnimal->imagenes()->exists())) {
                foreach ($marcaAnimal->imagenes as $imagen) {
                    if (Storage::disk('public')->exists($imagen->ruta)) {
                        Storage::disk('public')->delete($imagen->ruta);
                    }
                    $imagen->delete();
                }

                $marcaAnimal->imagenes()->create([
                    'ruta' => $marcaFoto,
                    'orden' => 0,
                ]);
            }
        }

        $duenoData = [
            'nombre_dueno' => $data['nombre_dueno'] ?? null,
        ];

        if (array_key_exists('carnet_dueno_foto', $data)) {
            $duenoData['carnet_dueno_foto'] = $data['carnet_dueno_foto'];
        }

        if ($datoSanitario->datoDueno || $this->tieneDatos($duenoData)) {
            $datoSanitario->datoDueno()->updateOrCreate(
                ['dato_sanitario_id' => $datoSanitario->id],
                $duenoData
            );
        }

        if (!empty($data['certificado_campeon_imagen'])) {
            foreach ($datoSanitario->imagenesCertificadoCampeon as $imagen) {
                if (Storage::disk('public')->exists($imagen->ruta)) {
                    Storage::disk('public')->delete($imagen->ruta);
                }
                $imagen->delete();
            }

            $datoSanitario->imagenesCertificadoCampeon()->create([
                'ruta' => $data['certificado_campeon_imagen'],
                'orden' => 0,
            ]);
        }

        if (!empty($data['arbol_genealogico'])) {
            foreach ($datoSanitario->archivosArbolGenealogico as $archivo) {
                if (Storage::disk('public')->exists($archivo->ruta)) {
                    Storage::disk('public')->delete($archivo->ruta);
                }
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

    private function agregarLogrosReconocimientos(array &$data, Request $request): void
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

    private function mensajesValidacionFechas(): array
    {
        return [
            'fecha_aplicacion.before_or_equal' => 'La fecha de aplicación debe ser hoy o una fecha pasada.',
            'proxima_fecha.after' => 'La próxima fecha debe ser una fecha futura.',
        ];
    }

    private function tieneDatos(array $data): bool
    {
        return collect($data)->contains(fn($value) => filled($value));
    }
}
