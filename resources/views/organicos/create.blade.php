@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto p-6 bg-white rounded-2xl shadow-xl mt-10">
    <h2 class="text-3xl font-bold text-gray-800 mb-6 text-center">Publicar Cultivo / Cosecha</h2>

    <form action="{{ route('organicos.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div>
                <label class="block text-sm font-medium text-gray-700">Nombre del Producto</label>
                <input type="text" name="nombre" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500" required>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700">Tipo de Cultivo</label>
                <select name="tipo_cultivo_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500" required>
                    <option value="">Seleccione el tipo...</option>
                    @foreach($tipos_cultivos ?? [] as $tipo)
                        <option value="{{ $tipo->id }}">{{ $tipo->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Cantidad (Stock)</label>
                <input type="number" name="stock" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Unidad de Medida</label>
                <select name="unidad_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500" required>
                    <option value="">Seleccione unidad...</option>
                    @foreach($unidades ?? [] as $unidad)
                        <option value="{{ $unidad->id }}">{{ $unidad->nombre }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="bg-gray-50 border-2 border-gray-200 rounded-xl p-6 mb-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h4 class="text-lg font-bold text-gray-800">Trazabilidad y Certificación</h4>
                    <p class="text-sm text-gray-500">Activa esto si tu producto es orgánico certificado.</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" id="toggleTrazabilidad" class="sr-only peer" onchange="toggleCampos()">
                    <div class="w-14 h-7 bg-gray-300 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-green-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-green-600"></div>
                </label>
            </div>

            <div id="panelTrazabilidad" class="hidden opacity-0 transition-opacity duration-300">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4 border-t border-gray-200 pt-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Fecha de Siembra</label>
                        <input type="date" name="fecha_siembra" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Fecha de Cosecha</label>
                        <input type="date" name="fecha_cosecha" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    </div>
                </div>
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded-lg font-medium shadow-md hover:bg-green-700">
                Publicar Cultivo
            </button>
        </div>
    </form>
</div>

<script>
    function toggleCampos() {
        const toggle = document.getElementById('toggleTrazabilidad');
        const panel = document.getElementById('panelTrazabilidad');
        
        if (toggle.checked) {
            panel.classList.remove('hidden');
            setTimeout(() => panel.classList.remove('opacity-0'), 10);
        } else {
            panel.classList.add('opacity-0');
            setTimeout(() => panel.classList.add('hidden'), 300);
        }
    }
</script>
@endsection