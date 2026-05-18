<?php

namespace App\Http\Controllers;

use App\Models\Organico;
use App\Models\OrganicoImagen;
use App\Models\Categoria;
use App\Models\UnidadOrganico;
use App\Models\TipoCultivo;
use App\Models\UbicacionGeograficaOrganico;
use App\Models\UbicacionOrganico;
use App\Http\Requests\StoreOrganicoRequest;
use App\Http\Requests\UpdateOrganicoRequest;
use Illuminate\Support\Facades\Storage;

class OrganicoController extends Controller
{
    public function index()
    {
        $q = request('q');

        $organicos = Organico::with($this->relacionesOrganico())
            ->when($q, function ($qb) use ($q) {
                $qb->where('nombre', 'ilike', "%$q%")
                    ->orWhereHas('categoria', function ($q2) use ($q) {
                        $q2->where('nombre', 'ilike', "%$q%");
                    })
                    ->orWhereHas('tipoCultivo', function ($q3) use ($q) {
                        $q3->where('nombre', 'ilike', "%$q%");
                    });
            })
            ->orderBy('id', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('organicos.index', compact('organicos', 'q'));
    }

    public function create()
    {
        $categorias   = Categoria::orderBy('nombre')->get();
        $unidades     = UnidadOrganico::orderBy('nombre')->get();
        $tiposCultivo = TipoCultivo::orderBy('nombre')->get();

        return view('organicos.create', compact('categorias', 'unidades', 'tiposCultivo'));
    }

    public function store(StoreOrganicoRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();
        $datosComerciales = $this->extraerDatosComerciales($data);
        $this->sincronizarUbicacionNormalizada($data);

        // Crear el orgánico
        $organico = Organico::create($data);
        $this->sincronizarDatosComerciales($organico, $datosComerciales);

        // Guardar las imágenes si existen (máximo 3)
        if ($request->hasFile('imagenes')) {
            $orden    = 0;
            $imagenes = array_slice($request->file('imagenes'), 0, 3); // Limitar a 3 imágenes

            foreach ($imagenes as $imagen) {
                if ($imagen && $imagen->isValid()) {
                    $ruta = $imagen->store('organicos', 'public');

                    OrganicoImagen::create([
                        'organico_id' => $organico->id,
                        'ruta'        => $ruta,
                        'orden'       => $orden++,
                    ]);
                }
            }
        }

        return redirect()->route('organicos.index')->with('ok', 'Orgánico creado');
    }

    public function show(Organico $organico)
    {
        $organico->load($this->relacionesOrganico());

        return view('organicos.show', compact('organico'));
    }

    public function edit(Organico $organico)
    {
        // Verificar permisos: solo el dueño o admin puede editar
        if (!auth()->user()->isAdmin() && $organico->user_id !== auth()->id()) {
            return redirect()->route('organicos.index')
                ->with('error', 'No tienes permisos para editar este anuncio.');
        }

        $organico->load($this->relacionesOrganico());

        $categorias   = Categoria::orderBy('nombre')->get();
        $unidades     = UnidadOrganico::orderBy('nombre')->get();
        $tiposCultivo = TipoCultivo::orderBy('nombre')->get();

        return view('organicos.edit', compact('organico', 'categorias', 'unidades', 'tiposCultivo'));
    }

    public function update(UpdateOrganicoRequest $request, Organico $organico)
    {
        // Verificar permisos: solo el dueño o admin puede actualizar
        if (!auth()->user()->isAdmin() && $organico->user_id !== auth()->id()) {
            return redirect()->route('organicos.index')
                ->with('error', 'No tienes permisos para editar este anuncio.');
        }

        $data = $request->validated();
        $datosComerciales = $this->extraerDatosComerciales($data);
        $this->sincronizarUbicacionNormalizada($data, $organico);

        $organico->update($data);
        $this->sincronizarDatosComerciales($organico, $datosComerciales);

        // Eliminar imágenes marcadas para eliminar
        if ($request->has('imagenes_eliminar')) {
            foreach ($request->imagenes_eliminar as $imagenId) {
                $imagen = OrganicoImagen::find($imagenId);

                if ($imagen && $imagen->organico_id === $organico->id) {
                    if (Storage::disk('public')->exists($imagen->ruta)) {
                        Storage::disk('public')->delete($imagen->ruta);
                    }
                    $imagen->delete();
                }
            }
        }

        // Agregar nuevas imágenes
        if ($request->hasFile('imagenes')) {
            $totalImagenesActuales = $organico->imagenes()->count();
            $maxOrden              = $organico->imagenes()->max('orden') ?? -1;
            $orden                 = $maxOrden + 1;
            $espaciosDisponibles   = 3 - $totalImagenesActuales;

            if ($espaciosDisponibles > 0) {
                $imagenes = array_slice($request->file('imagenes'), 0, $espaciosDisponibles);

                foreach ($imagenes as $imagen) {
                    if ($imagen && $imagen->isValid()) {
                        $ruta = $imagen->store('organicos', 'public');

                        OrganicoImagen::create([
                            'organico_id' => $organico->id,
                            'ruta'        => $ruta,
                            'orden'       => $orden++,
                        ]);
                    }
                }
            }
        }

        return redirect()->route('organicos.index')->with('ok', 'Orgánico actualizado');
    }

    public function destroy(Organico $organico)
    {
        // Verificar permisos: solo el dueño o admin puede eliminar
        if (!auth()->user()->isAdmin() && $organico->user_id !== auth()->id()) {
            return redirect()->route('organicos.index')
                ->with('error', 'No tienes permisos para eliminar este anuncio.');
        }

        // Eliminar las imágenes físicas
        foreach ($organico->imagenes as $imagen) {
            if (Storage::disk('public')->exists($imagen->ruta)) {
                Storage::disk('public')->delete($imagen->ruta);
            }
        }

        $organico->delete();

        return redirect()->route('organicos.index')->with('ok', 'Orgánico eliminado');
    }

    private function relacionesOrganico(): array
    {
        return [
            'categoria',
            'unidad',
            'tipoCultivo',
            'user',
            'imagenes',
            'datoComercial.unidad',
            'ubicacionOrganico.ubicacionGeografica',
        ];
    }

    private function extraerDatosComerciales(array &$data): array
    {
        $campos = ['unidad_id', 'precio', 'stock'];
        $datos = [];

        foreach ($campos as $campo) {
            if (array_key_exists($campo, $data)) {
                $datos[$campo] = $data[$campo];
                unset($data[$campo]);
            }
        }

        return $datos;
    }

    private function sincronizarDatosComerciales(Organico $organico, array $data): void
    {
        if (!$data) {
            return;
        }

        $organico->datoComercial()->updateOrCreate(
            ['organico_id' => $organico->id],
            [
                'unidad_id' => $data['unidad_id'] ?? $organico->unidad_id,
                'precio' => $data['precio'] ?? $organico->precio,
                'stock' => $data['stock'] ?? $organico->stock ?? 0,
            ]
        );
    }

    private function sincronizarUbicacionNormalizada(array &$data, ?Organico $organico = null): void
    {
        $campos = [
            'origen',
            'latitud_origen',
            'longitud_origen',
            'departamento_origen',
            'municipio_origen',
            'provincia_origen',
            'ciudad_origen',
        ];

        $recibioCamposUbicacion = collect($campos)->contains(fn ($campo) => array_key_exists($campo, $data));

        if (!$recibioCamposUbicacion) {
            return;
        }

        foreach ($campos as $campo) {
            if (array_key_exists($campo, $data) && $data[$campo] === '') {
                $data[$campo] = null;
            }
        }

        $tieneDatosUbicacion = collect($campos)->contains(fn ($campo) => !empty($data[$campo]));

        if (!$tieneDatosUbicacion) {
            $data['ubicacion_organico_id'] = null;
            $this->quitarCamposUbicacionAntiguos($data);
            return;
        }

        $ubicacionGeografica = UbicacionGeograficaOrganico::firstOrCreate([
            'departamento' => $data['departamento_origen'] ?? null,
            'municipio' => $data['municipio_origen'] ?? null,
            'provincia' => $data['provincia_origen'] ?? null,
            'ciudad' => $data['ciudad_origen'] ?? null,
        ]);

        $ubicacionData = [
            'ubicacion' => $data['origen'] ?? null,
            'latitud' => $data['latitud_origen'] ?? null,
            'longitud' => $data['longitud_origen'] ?? null,
            'ubicacion_geografica_organico_id' => $ubicacionGeografica->id,
        ];

        if ($organico && $organico->ubicacion_organico_id) {
            $ubicacion = UbicacionOrganico::find($organico->ubicacion_organico_id);

            if ($ubicacion) {
                $ubicacion->update($ubicacionData);
                $data['ubicacion_organico_id'] = $ubicacion->id;
                $this->quitarCamposUbicacionAntiguos($data);
                return;
            }
        }

        $data['ubicacion_organico_id'] = UbicacionOrganico::create($ubicacionData)->id;
        $this->quitarCamposUbicacionAntiguos($data);
    }

    private function quitarCamposUbicacionAntiguos(array &$data): void
    {
        foreach ([
            'origen',
            'latitud_origen',
            'longitud_origen',
            'departamento_origen',
            'municipio_origen',
            'provincia_origen',
            'ciudad_origen',
        ] as $campo) {
            unset($data[$campo]);
        }
    }
}
