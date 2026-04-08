<?php

namespace App\Http\Controllers;

use App\Models\Maquinaria;
use App\Models\MaquinariaImagen;
use App\Services\GeocodificacionService;
use App\Http\Requests\StoreMaquinariaRequest;
use App\Http\Requests\UpdateMaquinariaRequest;
use Illuminate\Support\Facades\Storage;

class MaquinariaController extends Controller
{
    public function index()
    {
        $q = request('q');
        $maquinarias = Maquinaria::with(['tipoMaquinaria', 'marcaMaquinaria', 'categoria', 'user', 'estadoMaquinaria'])
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
        $categorias = \App\Models\Categoria::orderBy('nombre')->get();
        $tipo_maquinarias = \App\Models\TipoMaquinaria::orderBy('nombre')->get();
        $marcas_maquinarias = \App\Models\MarcaMaquinaria::orderBy('nombre')->get();
        $estado_maquinarias = \App\Models\EstadoMaquinaria::orderBy('nombre')->get();
        return view('maquinarias.create', compact('categorias', 'tipo_maquinarias', 'marcas_maquinarias', 'estado_maquinarias'));
    }

    public function store(StoreMaquinariaRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();

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

        // Crear la maquinaria
        $maquinaria = Maquinaria::create($data);

        // Guardar las imágenes si existen (máximo 3)
        if ($request->hasFile('imagenes')) {
            $orden = 0;
            $imagenes = array_slice($request->file('imagenes'), 0, 3); // Limitar a 3 imágenes
            foreach ($imagenes as $imagen) {
                if ($imagen && $imagen->isValid()) {
                    $ruta = $imagen->store('maquinarias', 'public');
                    MaquinariaImagen::create([
                        'maquinaria_id' => $maquinaria->id,
                        'ruta' => $ruta,
                        'orden' => $orden++,
                    ]);
                }
            }
        }

        return redirect()->route('maquinarias.index')->with('ok', 'Maquinaria creada');
    }

    public function show(Maquinaria $maquinaria)
    {
        $maquinaria->load(['tipoMaquinaria', 'marcaMaquinaria', 'categoria', 'user.role', 'estadoMaquinaria', 'imagenes']);
        return view('maquinarias.show', compact('maquinaria'));
    }

    public function edit(Maquinaria $maquinaria)
    {
        // Verificar permisos: solo el dueño o admin puede editar
        if (!auth()->user()->isAdmin() && $maquinaria->user_id !== auth()->id()) {
            return redirect()->route('maquinarias.index')
                ->with('error', 'No tienes permisos para editar este anuncio.');
        }

        $maquinaria->load('imagenes');
        $categorias = \App\Models\Categoria::orderBy('nombre')->get();
        $tipo_maquinarias = \App\Models\TipoMaquinaria::orderBy('nombre')->get();
        $marcas_maquinarias = \App\Models\MarcaMaquinaria::orderBy('nombre')->get();
        $estado_maquinarias = \App\Models\EstadoMaquinaria::orderBy('nombre')->get();
        return view('maquinarias.edit', compact('maquinaria', 'categorias', 'tipo_maquinarias', 'marcas_maquinarias', 'estado_maquinarias'));
    }


    public function update(UpdateMaquinariaRequest $request, Maquinaria $maquinaria)
    {
        // Verificar permisos: solo el dueño o admin puede actualizar
        if (!auth()->user()->isAdmin() && $maquinaria->user_id !== auth()->id()) {
            return redirect()->route('maquinarias.index')
                ->with('error', 'No tienes permisos para editar este anuncio.');
        }

        $data = $request->validated();

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

            if ($espaciosDisponibles > 0) {
                $imagenes = array_slice($request->file('imagenes'), 0, $espaciosDisponibles);
                foreach ($imagenes as $imagen) {
                    if ($imagen && $imagen->isValid()) {
                        $ruta = $imagen->store('maquinarias', 'public');
                        MaquinariaImagen::create([
                            'maquinaria_id' => $maquinaria->id,
                            'ruta' => $ruta,
                            'orden' => $orden++,
                        ]);
                    }
                }
            }
        }

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
}
