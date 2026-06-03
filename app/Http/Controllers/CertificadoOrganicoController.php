<?php

namespace App\Http\Controllers;

use App\Models\CertificadoOrganico;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CertificadoOrganicoController extends Controller
{
    public function index()
    {
        $q = request('q');
        $obligatorios = CertificadoOrganico::where('es_obligatorio', true)
            ->orderBy('orden')
            ->get();
        $items = CertificadoOrganico::where('es_obligatorio', false)
            ->when($q, fn($qb) => $qb->where('nombre', 'ilike', "%$q%"))
            ->orderBy('orden')
            ->orderBy('id')
            ->paginate(10)
            ->withQueryString();

        return view('certificados_organicos.index', compact('items', 'obligatorios', 'q'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:255|unique:certificados_organicos,nombre',
            'descripcion' => 'required|string|max:5000',
        ]);

        CertificadoOrganico::create([
            'slug' => $this->uniqueSlug($data['nombre']),
            'nombre' => $data['nombre'],
            'descripcion' => $data['descripcion'],
            'tipo' => 'opcional',
            'es_obligatorio' => false,
            'activo' => true,
            'orden' => (CertificadoOrganico::max('orden') ?? 0) + 1,
        ]);

        return redirect()->route('admin.certificados_organicos.index')->with('ok', 'Certificado opcional creado.');
    }

    public function update(Request $request, CertificadoOrganico $certificadosOrganico)
    {
        abort_if($certificadosOrganico->es_obligatorio, 403);

        $data = $request->validate([
            'nombre' => 'required|string|max:255|unique:certificados_organicos,nombre,' . $certificadosOrganico->id,
            'descripcion' => 'required|string|max:5000',
            'activo' => 'nullable|boolean',
        ]);

        $certificadosOrganico->update([
            'nombre' => $data['nombre'],
            'descripcion' => $data['descripcion'],
            'activo' => (bool) ($data['activo'] ?? false),
        ]);

        return redirect()->route('admin.certificados_organicos.index')->with('ok', 'Certificado opcional actualizado.');
    }

    public function destroy(CertificadoOrganico $certificadosOrganico)
    {
        abort_if($certificadosOrganico->es_obligatorio, 403);

        $certificadosOrganico->delete();

        return redirect()->route('admin.certificados_organicos.index')->with('ok', 'Certificado opcional eliminado.');
    }

    private function uniqueSlug(string $nombre): string
    {
        $base = Str::slug($nombre) ?: 'certificado';
        $slug = $base;
        $counter = 2;

        while (CertificadoOrganico::where('slug', $slug)->exists()) {
            $slug = $base . '-' . $counter;
            $counter++;
        }

        return $slug;
    }
}
