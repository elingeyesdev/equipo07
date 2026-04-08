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
            $items = DatoSanitario::with(['ganado.tipoAnimal', 'ganado.raza'])
                ->orderBy('id', 'desc')
                ->get();
        } else {
            $ganadoIds = Ganado::where('user_id', auth()->id())->pluck('id');
            $items = DatoSanitario::with(['ganado.tipoAnimal', 'ganado.raza'])
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
            'fecha_aplicacion' => 'nullable|date',
            'proxima_fecha' => 'nullable|date',
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
        ]);

        // Verificar que el ganado pertenece al vendedor (si no es admin y hay ganado_id)
        if (!auth()->user()->isAdmin() && $request->ganado_id) {
            $ganado = Ganado::findOrFail($request->ganado_id);
            if ($ganado->user_id !== auth()->id()) {
                return redirect()->route('admin.datos-sanitarios.create')
                    ->with('error', 'No tienes permisos para crear datos sanitarios para este animal.');
            }
        }

        // Preparar los datos
        $data = $request->only([
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

        // Asignar el user_id del usuario autenticado
        $data['user_id'] = auth()->id();

        // Convertir checkboxes a boolean (si vienen como null, serán false)
        $data['vacunado_fiebre_aftosa'] = $request->has('vacunado_fiebre_aftosa') ? true : false;
        $data['vacunado_antirabica'] = $request->has('vacunado_antirabica') ? true : false;

        // Logros
        $data['logro_campeon_raza'] = $request->has('logro_campeon_raza') ? true : false;
        $data['logro_gran_campeon_macho'] = $request->has('logro_gran_campeon_macho') ? true : false;
        $data['logro_gran_campeon_hembra'] = $request->has('logro_gran_campeon_hembra') ? true : false;
        $data['logro_mejor_ubre'] = $request->has('logro_mejor_ubre') ? true : false;
        $data['logro_campeona_litros_dia'] = $request->has('logro_campeona_litros_dia') ? true : false;
        $data['logro_mejor_lactancia'] = $request->has('logro_mejor_lactancia') ? true : false;
        $data['logro_mejor_calidad_leche'] = $request->has('logro_mejor_calidad_leche') ? true : false;
        $data['logro_mejor_novillo'] = $request->has('logro_mejor_novillo') ? true : false;
        $data['logro_gran_campeon_carne'] = $request->has('logro_gran_campeon_carne') ? true : false;
        $data['logro_mejor_semental'] = $request->has('logro_mejor_semental') ? true : false;
        $data['logro_mejor_madre'] = $request->has('logro_mejor_madre') ? true : false;
        $data['logro_mejor_padre'] = $request->has('logro_mejor_padre') ? true : false;
        $data['logro_mejor_fertilidad'] = $request->has('logro_mejor_fertilidad') ? true : false;

        // Manejar la imagen del certificado
        if ($request->hasFile('certificado_imagen')) {
            $data['certificado_imagen'] = $request->file('certificado_imagen')->store('certificados_senasag', 'public');
        }

        // Manejar la imagen del certificado de campeón
        if ($request->hasFile('certificado_campeon_imagen')) {
            $data['certificado_campeon_imagen'] = $request->file('certificado_campeon_imagen')->store('certificados_campeon', 'public');
        }

        // Manejar el archivo del árbol genealógico
        if ($request->hasFile('arbol_genealogico')) {
            $data['arbol_genealogico'] = $request->file('arbol_genealogico')->store('arboles_genealogicos', 'public');
        }

        // Manejar la imagen de la marca del ganado
        if ($request->hasFile('marca_ganado_foto')) {
            $data['marca_ganado_foto'] = $request->file('marca_ganado_foto')->store('marcas_ganado', 'public');
        }

        // Manejar la imagen del carnet del dueño
        if ($request->hasFile('carnet_dueno_foto')) {
            $data['carnet_dueno_foto'] = $request->file('carnet_dueno_foto')->store('carnets_dueños', 'public');
        }

        // Crear el dato sanitario
        $datoSanitario = DatoSanitario::create($data);

        // Actualizar el ganado con el dato_sanitario_id (solo si hay ganado_id)
        if ($request->ganado_id) {
            $ganado = Ganado::findOrFail($request->ganado_id);
            $ganado->update(['dato_sanitario_id' => $datoSanitario->id]);
        }

        return redirect()->route('admin.datos-sanitarios.index')
            ->with('success', 'Registro sanitario guardado correctamente.');
    }


    public function edit(DatoSanitario $datos_sanitario)
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
            'fecha_aplicacion' => 'nullable|date',
            'proxima_fecha' => 'nullable|date',
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
        ]);

        // Verificar que el ganado pertenece al vendedor (si no es admin y hay ganado_id)
        if (!auth()->user()->isAdmin() && $request->ganado_id) {
            $ganado = Ganado::findOrFail($request->ganado_id);
            if ($ganado->user_id !== auth()->id()) {
                return redirect()->route('admin.datos-sanitarios.edit', $datos_sanitario)
                    ->with('error', 'No tienes permisos para editar datos sanitarios de este animal.');
            }
        }

        // Si cambió el ganado, actualizar el ganado anterior y el nuevo
        if ($datos_sanitario->ganado_id != $request->ganado_id) {
            // Limpiar el dato_sanitario_id del ganado anterior
            if ($datos_sanitario->ganado_id) {
                $ganadoAnterior = Ganado::find($datos_sanitario->ganado_id);
                if ($ganadoAnterior) {
                    $ganadoAnterior->update(['dato_sanitario_id' => null]);
                }
            }

            // Asignar el dato_sanitario_id al nuevo ganado (solo si hay ganado_id)
            if ($request->ganado_id) {
                $ganadoNuevo = Ganado::findOrFail($request->ganado_id);
                $ganadoNuevo->update(['dato_sanitario_id' => $datos_sanitario->id]);
            }
        }

        // Preparar los datos
        $data = $request->only([
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

        // Convertir checkboxes a boolean
        $data['vacunado_fiebre_aftosa'] = $request->has('vacunado_fiebre_aftosa') ? true : false;
        $data['vacunado_antirabica'] = $request->has('vacunado_antirabica') ? true : false;

        // Logros
        $data['logro_campeon_raza'] = $request->has('logro_campeon_raza') ? true : false;
        $data['logro_gran_campeon_macho'] = $request->has('logro_gran_campeon_macho') ? true : false;
        $data['logro_gran_campeon_hembra'] = $request->has('logro_gran_campeon_hembra') ? true : false;
        $data['logro_mejor_ubre'] = $request->has('logro_mejor_ubre') ? true : false;
        $data['logro_campeona_litros_dia'] = $request->has('logro_campeona_litros_dia') ? true : false;
        $data['logro_mejor_lactancia'] = $request->has('logro_mejor_lactancia') ? true : false;
        $data['logro_mejor_calidad_leche'] = $request->has('logro_mejor_calidad_leche') ? true : false;
        $data['logro_mejor_novillo'] = $request->has('logro_mejor_novillo') ? true : false;
        $data['logro_gran_campeon_carne'] = $request->has('logro_gran_campeon_carne') ? true : false;
        $data['logro_mejor_semental'] = $request->has('logro_mejor_semental') ? true : false;
        $data['logro_mejor_madre'] = $request->has('logro_mejor_madre') ? true : false;
        $data['logro_mejor_padre'] = $request->has('logro_mejor_padre') ? true : false;
        $data['logro_mejor_fertilidad'] = $request->has('logro_mejor_fertilidad') ? true : false;

        // Manejar la imagen del certificado
        if ($request->hasFile('certificado_imagen')) {
            // Eliminar la imagen anterior si existe
            if ($datos_sanitario->certificado_imagen && Storage::disk('public')->exists($datos_sanitario->certificado_imagen)) {
                Storage::disk('public')->delete($datos_sanitario->certificado_imagen);
            }
            $data['certificado_imagen'] = $request->file('certificado_imagen')->store('certificados_senasag', 'public');
        }

        // Manejar la imagen del certificado de campeón
        if ($request->hasFile('certificado_campeon_imagen')) {
            // Eliminar la imagen anterior si existe
            if ($datos_sanitario->certificado_campeon_imagen && Storage::disk('public')->exists($datos_sanitario->certificado_campeon_imagen)) {
                Storage::disk('public')->delete($datos_sanitario->certificado_campeon_imagen);
            }
            $data['certificado_campeon_imagen'] = $request->file('certificado_campeon_imagen')->store('certificados_campeon', 'public');
        }

        // Manejar el archivo del árbol genealógico
        if ($request->hasFile('arbol_genealogico')) {
            // Eliminar el archivo anterior si existe
            if ($datos_sanitario->arbol_genealogico && Storage::disk('public')->exists($datos_sanitario->arbol_genealogico)) {
                Storage::disk('public')->delete($datos_sanitario->arbol_genealogico);
            }
            $data['arbol_genealogico'] = $request->file('arbol_genealogico')->store('arboles_genealogicos', 'public');
        }

        // Manejar la imagen de la marca del ganado
        if ($request->hasFile('marca_ganado_foto')) {
            // Eliminar la imagen anterior si existe
            if ($datos_sanitario->marca_ganado_foto && Storage::disk('public')->exists($datos_sanitario->marca_ganado_foto)) {
                Storage::disk('public')->delete($datos_sanitario->marca_ganado_foto);
            }
            $data['marca_ganado_foto'] = $request->file('marca_ganado_foto')->store('marcas_ganado', 'public');
        }

        // Manejar la imagen del carnet del dueño
        if ($request->hasFile('carnet_dueno_foto')) {
            // Eliminar la imagen anterior si existe
            if ($datos_sanitario->carnet_dueno_foto && Storage::disk('public')->exists($datos_sanitario->carnet_dueno_foto)) {
                Storage::disk('public')->delete($datos_sanitario->carnet_dueno_foto);
            }
            $data['carnet_dueno_foto'] = $request->file('carnet_dueno_foto')->store('carnets_dueños', 'public');
        }

        $datos_sanitario->update($data);

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

        // Limpiar el dato_sanitario_id del ganado antes de eliminar
        $ganado = $datos_sanitario->ganado;
        if ($ganado) {
            $ganado->update(['dato_sanitario_id' => null]);
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
}
