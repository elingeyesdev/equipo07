<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrganicoRequest;
use App\Http\Requests\UpdateOrganicoRequest;
use App\Models\Categoria;
use App\Models\CertificadoOrganico;
use App\Models\Organico;
use App\Models\OrganicoCertificado;
use App\Models\OrganicoImagen;
use App\Models\TipoCultivo;
use App\Models\UnidadOrganico;
use Illuminate\Http\Request;
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
        return view('organicos.create', $this->catalogosFormulario());
    }

    public function store(StoreOrganicoRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();

        $datosComerciales = $this->extraerDatosComerciales($data);
        $ubicacion = $this->extraerDatosUbicacion($data);
        $trazabilidad = $this->extraerDatosTrazabilidad($data);
        $certificados = $this->extraerDatosCertificados($data);

        $organico = Organico::create($data);

        $this->sincronizarDatosComerciales($organico, $datosComerciales);
        $this->sincronizarUbicacion($organico, $ubicacion);
        $this->sincronizarTrazabilidad($organico, $trazabilidad);
        $this->sincronizarCertificados($organico, $certificados, $request);
        $this->guardarImagenes($organico, $request);

        return redirect()->route('organicos.index')->with('ok', 'Organico creado');
    }

    public function show(Organico $organico)
    {
        $organico->load($this->relacionesOrganico());

        return view('organicos.show', compact('organico'));
    }

    public function edit(Organico $organico)
    {
        if (!$this->puedeModificarProducto($organico)) {
            return redirect()->route('organicos.index')
                ->with('error', 'No tienes permisos para editar este anuncio.');
        }

        $organico->load($this->relacionesOrganico());

        return view('organicos.edit', array_merge(
            ['organico' => $organico],
            $this->catalogosFormulario()
        ));
    }

    public function update(UpdateOrganicoRequest $request, Organico $organico)
    {
        if (!$this->puedeModificarProducto($organico)) {
            return redirect()->route('organicos.index')
                ->with('error', 'No tienes permisos para editar este anuncio.');
        }

        $data = $request->validated();

        $datosComerciales = $this->extraerDatosComerciales($data);
        $ubicacion = $this->extraerDatosUbicacion($data);
        $trazabilidad = $this->extraerDatosTrazabilidad($data);
        $certificados = $this->extraerDatosCertificados($data);

        $organico->update($data);

        $this->sincronizarDatosComerciales($organico, $datosComerciales);
        $this->sincronizarUbicacion($organico, $ubicacion);
        $this->sincronizarTrazabilidad($organico, $trazabilidad);
        $this->sincronizarCertificados($organico, $certificados, $request);
        $this->eliminarImagenesMarcadas($organico, $request);
        $this->guardarImagenes($organico, $request);

        return redirect()->route('organicos.index')->with('ok', 'Organico actualizado');
    }

    public function destroy(Organico $organico)
    {
        if (!$this->puedeModificarProducto($organico)) {
            return redirect()->route('organicos.index')
                ->with('error', 'No tienes permisos para eliminar este anuncio.');
        }

        foreach ($organico->imagenes as $imagen) {
            if (Storage::disk('public')->exists($imagen->ruta)) {
                Storage::disk('public')->delete($imagen->ruta);
            }
        }

        $organico->delete();

        return redirect()->route('organicos.index')->with('ok', 'Organico eliminado');
    }

    public function actualizarEstadoCertificado(Request $request, OrganicoCertificado $certificado)
    {
        abort_unless(auth()->user()?->isAdmin(), 403);

        $data = $request->validate([
            'estado' => 'required|in:' . implode(',', [
                OrganicoCertificado::ESTADO_PENDIENTE,
                OrganicoCertificado::ESTADO_VERIFICADO,
                OrganicoCertificado::ESTADO_RECHAZADO,
            ]),
        ]);

        $certificado->update(['estado' => $data['estado']]);

        return back()->with('ok', 'Estado del certificado actualizado.');
    }

    private function catalogosFormulario(): array
    {
        return [
            'categorias' => Categoria::orderBy('nombre')->get(),
            'unidades' => UnidadOrganico::orderBy('nombre')->get(),
            'tiposCultivo' => TipoCultivo::orderBy('nombre')->get(),
            'certificados' => CertificadoOrganico::where('activo', true)->orderBy('orden')->get(),
        ];
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
            'ubicacionUnificada',
            'trazabilidad',
            'certificadoRegistros.certificado',
            'ubicacionOrganico.ubicacionGeografica',
        ];
    }

    private function puedeModificarProducto(Organico $organico): bool
    {
        return $organico->user_id === auth()->id();
    }

    private function extraerDatosComerciales(array &$data): array
    {
        return $this->extraerCampos($data, ['unidad_id', 'precio', 'stock']);
    }

    private function extraerDatosUbicacion(array &$data): array
    {
        return $this->extraerCampos($data, [
            'origen',
            'latitud_origen',
            'longitud_origen',
            'departamento_origen',
            'municipio_origen',
            'provincia_origen',
            'ciudad_origen',
            'referencia_ubicacion',
        ]);
    }

    private function extraerDatosTrazabilidad(array &$data): array
    {
        $trazabilidad = [
            'finca' => $data['finca'] ?? null,
            'fecha_siembra' => $data['fecha_siembra'] ?? null,
            'tratamientos_utilizados' => $data['tratamientos_utilizados'] ?? null,
            'observaciones' => $data['observaciones_trazabilidad'] ?? null,
        ];

        foreach (['finca', 'fecha_siembra', 'tratamientos_utilizados', 'observaciones_trazabilidad'] as $campo) {
            unset($data[$campo]);
        }

        return array_map(fn ($value) => $value === '' ? null : $value, $trazabilidad);
    }

    private function extraerDatosCertificados(array &$data): array
    {
        $certificados = [
            'catalogo' => $data['certificados'] ?? [],
            'adicionales' => $data['certificados_adicionales'] ?? [],
        ];

        unset($data['certificados'], $data['certificados_adicionales']);

        return $certificados;
    }

    private function extraerCampos(array &$data, array $campos): array
    {
        $extraidos = [];

        foreach ($campos as $campo) {
            if (array_key_exists($campo, $data)) {
                $extraidos[$campo] = $data[$campo] === '' ? null : $data[$campo];
                unset($data[$campo]);
            }
        }

        return $extraidos;
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

    private function sincronizarUbicacion(Organico $organico, array $data): void
    {
        if (!$data) {
            return;
        }

        $payload = [
            'direccion' => $data['origen'] ?? null,
            'latitud' => $data['latitud_origen'] ?? null,
            'longitud' => $data['longitud_origen'] ?? null,
            'departamento' => $data['departamento_origen'] ?? null,
            'municipio' => $data['municipio_origen'] ?? null,
            'provincia' => $data['provincia_origen'] ?? null,
            'ciudad' => $data['ciudad_origen'] ?? null,
            'referencia' => $data['referencia_ubicacion'] ?? null,
        ];

        if (!collect($payload)->filter()->isNotEmpty()) {
            $organico->ubicacionUnificada()?->delete();
            return;
        }

        $organico->ubicacionUnificada()->updateOrCreate(
            ['organico_id' => $organico->id],
            $payload
        );
    }

    private function sincronizarTrazabilidad(Organico $organico, array $data): void
    {
        if (!collect($data)->filter()->isNotEmpty()) {
            return;
        }

        $organico->trazabilidad()->updateOrCreate(
            ['organico_id' => $organico->id],
            [
                'origen' => $organico->origen ?? 'Bolivia',
                'finca' => $data['finca'] ?? 'No especificada',
                'ubicacion' => $organico->origen ?? 'Bolivia',
                'fecha_siembra' => $data['fecha_siembra'] ?? $organico->fecha_cosecha,
                'fecha_cosecha' => $organico->fecha_cosecha,
                'tratamientos_utilizados' => $data['tratamientos_utilizados'] ?? 'No especificado',
                'certificaciones' => '',
                'observaciones' => $data['observaciones'] ?? null,
            ]
        );
    }

    private function sincronizarCertificados(Organico $organico, array $data, Request $request): void
    {
        foreach ($data['catalogo'] as $certificadoId => $certificadoData) {
            $certificado = CertificadoOrganico::find($certificadoId);

            if (!$certificado) {
                continue;
            }

            $registro = $organico->certificadoRegistros()
                ->where('certificado_organico_id', $certificadoId)
                ->first();

            $archivo = $registro?->archivo;

            $sinCertificado = (bool) ($certificadoData['sin_certificado'] ?? false);

            if (!$sinCertificado && $request->hasFile("certificados.$certificadoId.archivo")) {
                $archivo = $request->file("certificados.$certificadoId.archivo")
                    ->store('organicos/certificados', 'public');
            }

            $incluido = (bool) ($certificadoData['incluido'] ?? false);
            $debeGuardar = $certificado->es_obligatorio
                || $incluido
                || filled($archivo)
                || filled($certificadoData['observaciones'] ?? null);

            if (!$debeGuardar) {
                $registro?->delete();
                continue;
            }

            $organico->certificadoRegistros()->updateOrCreate(
                ['certificado_organico_id' => $certificadoId],
                [
                    'estado' => $registro?->estado ?? OrganicoCertificado::ESTADO_PENDIENTE,
                    'archivo' => $sinCertificado ? null : $archivo,
                    'fecha_emision' => $certificadoData['fecha_emision'] ?? null,
                    'fecha_vencimiento' => $certificadoData['fecha_vencimiento'] ?? null,
                    'observaciones' => $certificadoData['observaciones'] ?? null,
                ]
            );
        }

        foreach ($data['adicionales'] as $index => $certificadoData) {
            if (blank($certificadoData['nombre'] ?? null) && !$request->hasFile("certificados_adicionales.$index.archivo")) {
                continue;
            }

            $archivo = null;

            if ($request->hasFile("certificados_adicionales.$index.archivo")) {
                $archivo = $request->file("certificados_adicionales.$index.archivo")
                    ->store('organicos/certificados', 'public');
            }

            $organico->certificadoRegistros()->create([
                'nombre_adicional' => $certificadoData['nombre'] ?? 'Certificado adicional',
                'estado' => OrganicoCertificado::ESTADO_PENDIENTE,
                'archivo' => $archivo,
                'fecha_emision' => $certificadoData['fecha_emision'] ?? null,
                'fecha_vencimiento' => $certificadoData['fecha_vencimiento'] ?? null,
                'observaciones' => $certificadoData['observaciones'] ?? null,
            ]);
        }
    }

    private function guardarImagenes(Organico $organico, Request $request): void
    {
        if (!$request->hasFile('imagenes')) {
            return;
        }

        $totalImagenesActuales = $organico->imagenes()->count();
        $maxOrden = $organico->imagenes()->max('orden') ?? -1;
        $espaciosDisponibles = 3 - $totalImagenesActuales;

        if ($espaciosDisponibles <= 0) {
            return;
        }

        $orden = $maxOrden + 1;
        $imagenes = array_slice($request->file('imagenes'), 0, $espaciosDisponibles);

        foreach ($imagenes as $imagen) {
            if ($imagen && $imagen->isValid()) {
                OrganicoImagen::create([
                    'organico_id' => $organico->id,
                    'ruta' => $imagen->store('organicos', 'public'),
                    'orden' => $orden++,
                ]);
            }
        }
    }

    private function eliminarImagenesMarcadas(Organico $organico, Request $request): void
    {
        if (!$request->has('imagenes_eliminar')) {
            return;
        }

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
}
