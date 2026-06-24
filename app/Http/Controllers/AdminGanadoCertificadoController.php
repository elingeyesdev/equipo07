<?php

namespace App\Http\Controllers;

use App\Models\DatoSanitario;
use App\Notifications\GanadoCertificadoRechazado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminGanadoCertificadoController extends Controller
{
    public function index()
    {
        $certificados = DatoSanitario::query()
            ->with([
                'ganado.user',
                'vacunacion.imagenPrincipal',
                'imagenCertificadoCampeonPrincipal',
                'archivoArbolGenealogicoPrincipal',
            ])
            ->whereHas('ganado')
            ->where('estado_revision_certificado', 'pendiente')
            ->where(function ($query) {
                $query->whereNotNull('documento_pdf')
                    ->orWhereHas('vacunacion.imagenPrincipal')
                    ->orWhereHas('imagenCertificadoCampeonPrincipal')
                    ->orWhereHas('archivoArbolGenealogicoPrincipal');
            })
            ->latest()
            ->paginate(15);

        return view('admin.ganados.certificados-pendientes', compact('certificados'));
    }

    public function aprobar(DatoSanitario $datoSanitario)
    {
        $datoSanitario->update([
            'estado_revision_certificado' => 'aprobado',
            'motivo_rechazo_certificado' => null,
            'revisado_at' => now(),
            'revisado_por_id' => auth()->id(),
        ]);

        return back()->with('ok', 'Certificado de ganado aprobado.');
    }

    public function rechazar(Request $request, DatoSanitario $datoSanitario)
    {
        $data = $request->validate([
            'motivo' => ['required', 'string', 'min:10', 'max:1000'],
        ], [
            'motivo.required' => 'Indica el motivo del rechazo para notificar al productor.',
            'motivo.min' => 'El motivo debe tener al menos 10 caracteres.',
        ]);

        $datoSanitario->load('ganado.user');
        $ganado = $datoSanitario->ganado;

        if (! $ganado) {
            return back()->with('error', 'La publicación ya no existe.');
        }

        $productor = $ganado->user;
        $nombreProducto = $ganado->nombre;

        $datoSanitario->update([
            'estado_revision_certificado' => 'rechazado',
            'motivo_rechazo_certificado' => $data['motivo'],
            'revisado_at' => now(),
            'revisado_por_id' => auth()->id(),
        ]);

        if ($productor) {
            $productor->notify(new GanadoCertificadoRechazado($nombreProducto, $data['motivo']));
        }

        foreach ($ganado->imagenes as $imagen) {
            if (Storage::disk('public')->exists($imagen->ruta)) {
                Storage::disk('public')->delete($imagen->ruta);
            }
        }

        $ganado->delete();

        return redirect()
            ->route('admin.ganados.certificados.pendientes')
            ->with('ok', 'Certificado rechazado. La publicación fue eliminada y el productor fue notificado.');
    }
}
