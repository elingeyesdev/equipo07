<?php

namespace App\Http\Controllers;

use App\Models\Maquinaria;
use App\Models\MaquinariaImagen;
use App\Models\UbicacionGeograficaMaquinaria;
use App\Models\UbicacionMaquinaria;
use App\Models\Categoria;
use App\Models\EstadoMaquinaria;
use App\Services\GeocodificacionService;
use App\Http\Requests\StoreMaquinariaRequest;
use App\Http\Requests\UpdateMaquinariaRequest;
use Illuminate\Support\Facades\Storage;

class MaquinariaController extends Controller
{
    public function index()
    {
        $q = request('q');
        $maquinarias = Maquinaria::with(['tipoMaquinaria', 'marcaMaquinaria', 'categoria', 'user', 'estadoMaquinaria', 'ubicacionMaquinaria.ubicacionGeografica'])
            ->when($q, fn($qb) =>
            $qb->where('nombre', 'ilike', "%$q%")
                ->orWhereHas('tipoMaquinaria', function ($query) use ($q) {
                    $query->where('nombre', 'ilike', "%$q%");
                })
                ->orWhereHas('marcaMaquinaria', function ($query) use ($q) {
                    $query->where('nombre', 'ilike', "%$q%");
                }))
            ->orderBy('id', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('maquinarias.index', compact('maquinarias', 'q'));
    }

    public function create()
    {
        $categoriaMaquinaria = $this->categoriaMaquinaria();
        $estadoDisponible = $this->estadoDisponible();
        $tipo_maquinarias = \App\Models\TipoMaquinaria::orderBy('nombre')->get();
        $marcas_maquinarias = \App\Models\MarcaMaquinaria::orderBy('nombre')->get();
        $estado_maquinarias = \App\Models\EstadoMaquinaria::orderBy('nombre')->get();
        return view('maquinarias.create', compact('categoriaMaquinaria', 'estadoDisponible', 'tipo_maquinarias', 'marcas_maquinarias', 'estado_maquinarias'));
    }

    public function store(StoreMaquinariaRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();
        $data['categoria_id'] = $this->categoriaMaquinaria()?->id;
        $data['estado_maquinaria_id'] = $this->estadoDisponible()?->id;
        $imagenPortada = $data['imagen_portada'] ?? null;
        unset($data['imagen_portada']);

        // Obtener información geográfica si hay coordenadas
        if ($request->latitud && $request->longitud) {
            $geocodificacionService = new GeocodificacionService();
            $infoGeografica = $geocodificacionService->obtenerInformacionGeografica(
                (float) $request->latitud,
                (float) $request->longitud
            );

            if ($infoGeografica) {
                $data['departamento'] = $infoGeografica['departamento'];
                $data['municipio'] = $infoGeografica['municipio'];
                $data['provincia'] = $infoGeografica['provincia'];
                $data['ciudad'] = $infoGeografica['ciudad'];

                // Si no hay ubicación escrita, usar la dirección completa
                if (empty($data['ubicacion']) && isset($infoGeografica['direccion_completa'])) {
                    $data['ubicacion'] = $infoGeografica['direccion_completa'];
                }
            }
        }

        $this->sincronizarUbicacionNormalizada($data);

        // Crear la maquinaria
        $maquinaria = Maquinaria::create($data);

        // Guardar las imágenes si existen (máximo 3)
        if ($request->hasFile('imagenes')) {
            $orden = 0;
            $imagenes = array_slice($request->file('imagenes'), 0, 3); // Limitar a 3 imágenes
            foreach ($imagenes as $imagen) {
                if ($imagen && $imagen->isValid()) {
                    $ruta = $imagen->store('maquinarias', 'public');
                    $imagenCreada = MaquinariaImagen::create([
                        'maquinaria_id' => $maquinaria->id,
                        'ruta' => $ruta,
                        'orden' => $orden++,
                    ]);

                    if ($imagenPortada === 'new:' . ($orden - 1)) {
                        $imagenPortada = 'existing:' . $imagenCreada->id;
                    }
                }
            }
        }

        $this->aplicarPortada($maquinaria, $imagenPortada);

        return redirect()->route('maquinarias.index')->with('ok', 'Maquinaria creada');
    }

    public function show(Maquinaria $maquinaria)
    {
        $maquinaria->load(['tipoMaquinaria', 'marcaMaquinaria', 'categoria', 'user.role', 'estadoMaquinaria', 'imagenes', 'ubicacionMaquinaria.ubicacionGeografica']);
        return view('maquinarias.show', compact('maquinaria'));
    }

    public function edit(Maquinaria $maquinaria)
    {
        // Verificar permisos: solo el dueño o admin puede editar
        if (!auth()->user()->isAdmin() && $maquinaria->user_id !== auth()->id()) {
            return redirect()->route('maquinarias.index')
                ->with('error', 'No tienes permisos para editar este anuncio.');
        }

        $maquinaria->load(['imagenes', 'ubicacionMaquinaria.ubicacionGeografica']);
        $categoriaMaquinaria = $this->categoriaMaquinaria();
        $estadoDisponible = $this->estadoDisponible();
        $tipo_maquinarias = \App\Models\TipoMaquinaria::orderBy('nombre')->get();
        $marcas_maquinarias = \App\Models\MarcaMaquinaria::orderBy('nombre')->get();
        $estado_maquinarias = \App\Models\EstadoMaquinaria::orderBy('nombre')->get();
        return view('maquinarias.edit', compact('maquinaria', 'categoriaMaquinaria', 'estadoDisponible', 'tipo_maquinarias', 'marcas_maquinarias', 'estado_maquinarias'));
    }


    public function update(UpdateMaquinariaRequest $request, Maquinaria $maquinaria)
    {
        // Verificar permisos: solo el dueño o admin puede actualizar
        if (!auth()->user()->isAdmin() && $maquinaria->user_id !== auth()->id()) {
            return redirect()->route('maquinarias.index')
                ->with('error', 'No tienes permisos para editar este anuncio.');
        }

        $data = $request->validated();
        $data['categoria_id'] = $maquinaria->categoria_id ?: $this->categoriaMaquinaria()?->id;
        $imagenPortada = $data['imagen_portada'] ?? null;
        unset($data['imagen_portada']);

        // Obtener información geográfica si hay coordenadas (y si cambiaron)
        if (
            $request->latitud && $request->longitud &&
            ($maquinaria->latitud != $request->latitud || $maquinaria->longitud != $request->longitud)
        ) {
            $geocodificacionService = new GeocodificacionService();
            $infoGeografica = $geocodificacionService->obtenerInformacionGeografica(
                (float) $request->latitud,
                (float) $request->longitud
            );

            if ($infoGeografica) {
                $data['departamento'] = $infoGeografica['departamento'];
                $data['municipio'] = $infoGeografica['municipio'];
                $data['provincia'] = $infoGeografica['provincia'];
                $data['ciudad'] = $infoGeografica['ciudad'];

                // Si no hay ubicación escrita, usar la dirección completa
                if (empty($data['ubicacion']) && isset($infoGeografica['direccion_completa'])) {
                    $data['ubicacion'] = $infoGeografica['direccion_completa'];
                }
            }
        }

        $this->sincronizarUbicacionNormalizada($data, $maquinaria);

        $maquinaria->update($data);

        // Eliminar imágenes marcadas para eliminar
        if ($request->has('imagenes_eliminar')) {
            foreach ($request->imagenes_eliminar as $imagenId) {
                $imagen = MaquinariaImagen::find($imagenId);
                if ($imagen && $imagen->maquinaria_id === $maquinaria->id) {
                    if (Storage::disk('public')->exists($imagen->ruta)) {
                        Storage::disk('public')->delete($imagen->ruta);
                    }
                    $imagen->delete();
                }
            }
        }

        // Agregar nuevas imágenes
        if ($request->hasFile('imagenes')) {
            $totalImagenesActuales = $maquinaria->imagenes()->count();
            $maxOrden = $maquinaria->imagenes()->max('orden') ?? -1;
            $orden = $maxOrden + 1;
            $espaciosDisponibles = 3 - $totalImagenesActuales;
            $newIndex = 0;

            if ($espaciosDisponibles > 0) {
                $imagenes = array_slice($request->file('imagenes'), 0, $espaciosDisponibles);
                foreach ($imagenes as $imagen) {
                    if ($imagen && $imagen->isValid()) {
                        $ruta = $imagen->store('maquinarias', 'public');
                        $imagenCreada = MaquinariaImagen::create([
                            'maquinaria_id' => $maquinaria->id,
                            'ruta' => $ruta,
                            'orden' => $orden++,
                        ]);

                        if ($imagenPortada === 'new:' . $newIndex) {
                            $imagenPortada = 'existing:' . $imagenCreada->id;
                        }

                        $newIndex++;
                    }
                }
            }
        }

        $this->aplicarPortada($maquinaria, $imagenPortada);

        return redirect()->route('maquinarias.index')->with('ok', 'Maquinaria actualizada');
    }

    public function destroy(Maquinaria $maquinaria)
    {
        // Verificar permisos: solo el dueño o admin puede eliminar
        if (!auth()->user()->isAdmin() && $maquinaria->user_id !== auth()->id()) {
            return redirect()->route('maquinarias.index')
                ->with('error', 'No tienes permisos para eliminar este anuncio.');
        }

        // Eliminar las imágenes físicas
        foreach ($maquinaria->imagenes as $imagen) {
            if (Storage::disk('public')->exists($imagen->ruta)) {
                Storage::disk('public')->delete($imagen->ruta);
            }
        }

        $maquinaria->delete();
        return redirect()->route('maquinarias.index')->with('ok', 'Maquinaria eliminada');
    }

    private function sincronizarUbicacionNormalizada(array &$data, ?Maquinaria $maquinaria = null): void
    {
        $campos = ['ubicacion', 'latitud', 'longitud', 'departamento', 'municipio', 'provincia', 'ciudad'];

        foreach ($campos as $campo) {
            if (array_key_exists($campo, $data) && $data[$campo] === '') {
                $data[$campo] = null;
            }
        }

        $tieneDatosUbicacion = collect($campos)->contains(fn($campo) => !empty($data[$campo]));

        if (!$tieneDatosUbicacion) {
            $this->quitarCamposUbicacionAntiguos($data);
            return;
        }

        $ubicacionGeografica = UbicacionGeograficaMaquinaria::firstOrCreate([
            'departamento' => $data['departamento'] ?? null,
            'municipio' => $data['municipio'] ?? null,
            'provincia' => $data['provincia'] ?? null,
            'ciudad' => $data['ciudad'] ?? null,
        ]);

        $ubicacionData = [
            'ubicacion' => $data['ubicacion'] ?? null,
            'latitud' => $data['latitud'] ?? null,
            'longitud' => $data['longitud'] ?? null,
            'ubicacion_geografica_maquinaria_id' => $ubicacionGeografica->id,
        ];

        if ($maquinaria && $maquinaria->ubicacion_maquinaria_id) {
            $ubicacion = UbicacionMaquinaria::find($maquinaria->ubicacion_maquinaria_id);

            if ($ubicacion) {
                $ubicacion->update($ubicacionData);
                $data['ubicacion_maquinaria_id'] = $ubicacion->id;
                $this->quitarCamposUbicacionAntiguos($data);
                return;
            }
        }

        $data['ubicacion_maquinaria_id'] = UbicacionMaquinaria::create($ubicacionData)->id;
        $this->quitarCamposUbicacionAntiguos($data);
    }

    private function quitarCamposUbicacionAntiguos(array &$data): void
    {
        foreach (['ubicacion', 'latitud', 'longitud', 'departamento', 'municipio', 'provincia', 'ciudad'] as $campo) {
            unset($data[$campo]);
        }
    }

    private function categoriaMaquinaria(): ?Categoria
    {
        $categoria = Categoria::query()
            ->where('tipo', 'maquinaria')
            ->orWhere('nombre', 'ilike', '%maquinaria%')
            ->orderByRaw("CASE WHEN tipo = 'maquinaria' THEN 0 ELSE 1 END")
            ->first();

        return $categoria ?: Categoria::create([
            'nombre' => 'Maquinaria',
            'tipo' => 'maquinaria',
            'descripcion' => 'Categoría para publicaciones de maquinaria.',
        ]);
    }

    private function estadoDisponible(): ?EstadoMaquinaria
    {
        $estado = EstadoMaquinaria::query()
            ->where('nombre', 'ilike', 'disponible')
            ->first();

        return $estado ?: EstadoMaquinaria::create([
            'nombre' => 'disponible',
            'descripcion' => 'Maquinaria disponible para alquiler',
        ]);
    }

    private function aplicarPortada(Maquinaria $maquinaria, ?string $imagenPortada): void
    {
        if (!$imagenPortada || !str_starts_with($imagenPortada, 'existing:')) {
            return;
        }

        $imagenId = (int) str_replace('existing:', '', $imagenPortada);
        $portada = $maquinaria->imagenes()->whereKey($imagenId)->first();

        if (!$portada) {
            return;
        }

        $imagenes = $maquinaria->imagenes()->orderBy('orden')->orderBy('id')->get();
        $orden = 0;

        $portada->update(['orden' => $orden++]);

        foreach ($imagenes as $imagen) {
            if ($imagen->id === $portada->id) {
                continue;
            }

            $imagen->update(['orden' => $orden++]);
        }
    }
}
