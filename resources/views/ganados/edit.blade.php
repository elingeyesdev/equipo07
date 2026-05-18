@extends('layouts.adminlte')

@section('title', 'Editar Ganado')

@section('content')
    <style>
        .form-section-title {
            color: #28a745;
            font-weight: 600;
            font-size: 1rem;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #28a745;
        }

        .card-header {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        }

        .form-group label {
            color: #495057;
            margin-bottom: 0.5rem;
        }

        .input-group .btn {
            border-color: #28a745;
        }

        .input-group .btn:hover {
            background-color: #28a745;
            color: white;
        }

        .form-control:focus {
            border-color: #28a745;
            box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
        }

        select.form-control:focus {
            border-color: #28a745;
            box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
        }
    </style>

    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1 class="h3 mb-0 text-success">
                <i class="fas fa-edit"></i> Editar Registro de Ganado
            </h1>
            <a href="{{ url()->previous() !== url()->current() ? url()->previous() : route('ganados.index') }}"
                class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>

        <div class="card shadow-lg border-success border-3">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="fas fa-cow"></i> Información del Animal</h5>
            </div>
            <div class="card-body">

                <form action="{{ route('ganados.update', $ganado->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <!-- COLUMNA IZQUIERDA -->
                        <div class="col-lg-6">
                            <h6 class="text-success border-bottom pb-2 mb-3"><i class="fas fa-info-circle"></i> Datos
                                Básicos</h6>

                            <div class="form-group mb-3">
                                <label class="font-weight-bold">Nombre *</label>
                                <input type="text" name="nombre" class="form-control" value="{{ $ganado->nombre }}"
                                    required>
                            </div>

                            <div class="form-group mb-3">
                                <label class="font-weight-bold">Tipo de Animal *</label>
                                <select name="tipo_animal_id" id="tipo_animal_id" class="form-control" required>
                                    @foreach ($tipo_animals as $item)
                                        <option value="{{ $item->id }}"
                                            {{ $ganado->tipo_animal_id == $item->id ? 'selected' : '' }}>
                                            {{ $item->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group mb-3">
                                <label class="font-weight-bold">Raza</label>
                                <select name="raza_id" id="raza_id" class="form-control">
                                    @foreach ($razas as $raza)
                                        <option value="{{ $raza->id }}"
                                            {{ $ganado->raza_id == $raza->id ? 'selected' : '' }}>
                                            {{ $raza->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group mb-3">
                                <label class="font-weight-bold">Edad</label>
                                @php
                                    $totalMeses = $ganado->edad ?? 0;
                                    $anos = floor($totalMeses / 12);
                                    $mesesRestantes = $totalMeses % 12;
                                    $dias = 0;
                                @endphp
                                <div class="row">
                                    <div class="col-4">
                                        <label class="small text-muted mb-1">Años</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <button class="btn btn-outline-secondary btn-sm" type="button"
                                                    onclick="decrementValue('edad_anos')">
                                                    <i class="fas fa-minus"></i>
                                                </button>
                                            </div>
                                            <input type="number" name="edad_anos" id="edad_anos"
                                                class="form-control text-center" value="{{ old('edad_anos', $anos) }}"
                                                min="0" max="25" required>
                                            <div class="input-group-append">
                                                <button class="btn btn-outline-secondary btn-sm" type="button"
                                                    onclick="incrementValue('edad_anos', 25)">
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <label class="small text-muted mb-1">Meses</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <button class="btn btn-outline-secondary btn-sm" type="button"
                                                    onclick="decrementValue('edad_meses')">
                                                    <i class="fas fa-minus"></i>
                                                </button>
                                            </div>
                                            <input type="number" name="edad_meses" id="edad_meses"
                                                class="form-control text-center"
                                                value="{{ old('edad_meses', $mesesRestantes) }}" min="0"
                                                max="11" required>
                                            <div class="input-group-append">
                                                <button class="btn btn-outline-secondary btn-sm" type="button"
                                                    onclick="incrementValue('edad_meses', 11)">
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <label class="small text-muted mb-1">Días</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <button class="btn btn-outline-secondary btn-sm" type="button"
                                                    onclick="decrementValue('edad_dias')">
                                                    <i class="fas fa-minus"></i>
                                                </button>
                                            </div>
                                            <input type="number" name="edad_dias" id="edad_dias"
                                                class="form-control text-center" value="{{ old('edad_dias', 0) }}"
                                                min="0" max="30" required>
                                            <div class="input-group-append">
                                                <button class="btn btn-outline-secondary btn-sm" type="button"
                                                    onclick="incrementValue('edad_dias', 30)">
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                <label class="font-weight-bold">Sexo</label>
                                <select name="sexo" class="form-control">
                                    <option value="">Seleccione</option>
                                    <option value="Macho" {{ $ganado->sexo == 'Macho' ? 'selected' : '' }}>Macho</option>
                                    <option value="Hembra" {{ $ganado->sexo == 'Hembra' ? 'selected' : '' }}>Hembra
                                    </option>
                                </select>
                            </div>

                            <div class="form-group mb-3">
                                <label class="font-weight-bold">Categoría *</label>
                                <select name="categoria_id" class="form-control" required>
                                    @foreach ($categorias as $cat)
                                        <option value="{{ $cat->id }}"
                                            {{ $ganado->categoria_id == $cat->id ? 'selected' : '' }}>
                                            {{ $cat->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- COLUMNA DERECHA -->
                        <div class="col-lg-6">
                            <h6 class="text-success border-bottom pb-2 mb-3"><i class="fas fa-chart-line"></i> Información
                                Comercial</h6>

                            <div class="form-group mb-3">
                                <label class="font-weight-bold">Método de Venta / Tipo de Peso *</label>
                                <select name="tipo_peso_id" class="form-control" required>
                                    @foreach ($tipoPesos as $peso)
                                        <option value="{{ $peso->id }}"
                                            {{ $ganado->tipo_peso_id == $peso->id ? 'selected' : '' }}>
                                            {{ $peso->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group mb-3">
                                <label for="peso_actual" class="font-weight-bold">Peso Actual (kg)</label>
                                <div class="input-group">
                                    <input type="number" name="peso_actual" id="peso_actual" class="form-control"
                                        step="0.01" min="0"
                                        value="{{ old('peso_actual', $ganado->peso_actual) }}" placeholder="Ej: 250.50">
                                    <div class="input-group-append">
                                        <span class="input-group-text">kg</span>
                                    </div>
                                </div>
                                <small class="form-text text-muted">Ingrese el peso actual del animal en kilogramos</small>
                            </div>

                            <div class="form-group mb-3">
                                <label for="precio" class="font-weight-bold">Precio (Bs)</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Bs</span>
                                    </div>
                                    <input type="number" name="precio" id="precio" class="form-control"
                                        step="0.01" min="0" value="{{ old('precio', $ganado->precio) }}"
                                        placeholder="0.00">
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                <label for="stock" class="font-weight-bold">Stock (Cantidad) *</label>
                                <input type="number" name="stock" id="stock" class="form-control" min="0"
                                    value="{{ old('stock', $ganado->stock) }}" required>
                                <small class="form-text text-muted">Ingrese la cantidad disponible de ganado</small>
                            </div>

                            <div class="form-group mb-3">
                                <label for="dato_sanitario_id" class="font-weight-bold">Datos Sanitarios</label>
                                <select name="dato_sanitario_id" class="form-control">
                                    <option value="">Sin registro sanitario</option>
                                    @foreach ($datosSanitarios as $ds)
                                        <option value="{{ $ds->id }}"
                                            {{ $ganado->dato_sanitario_id == $ds->id ? 'selected' : '' }}>
                                            {{ $ds->vacuna ?? 'Sin vacuna' }} - {{ $ds->fecha_aplicacion ?? 'Sin fecha' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- SECCIÓN DE DESCRIPCIÓN (ANCHO COMPLETO) -->
                    <div class="row mt-3">
                        <div class="col-12">
                            <h6 class="text-success border-bottom pb-2 mb-3"><i class="fas fa-align-left"></i> Descripción
                            </h6>
                            <div class="form-group mb-3">
                                <label for="descripcion" class="font-weight-bold">Descripción del Animal</label>
                                <textarea name="descripcion" id="descripcion" class="form-control" rows="4"
                                    placeholder="Describa las características del animal...">{{ old('descripcion', $ganado->descripcion) }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- SECCIÓN DE UBICACIÓN (ANCHO COMPLETO) -->
                    <div class="row mt-3">
                        <div class="col-12">
                            <h6 class="text-success border-bottom pb-2 mb-3"><i class="fas fa-map-marker-alt"></i>
                                Ubicación</h6>
                            <div class="form-group mb-3">
                                <label class="font-weight-bold">Seleccione la ubicación en el mapa</label>
                                <input type="text" id="ubicacion" name="ubicacion" class="form-control mb-2"
                                    value="{{ $ganado->ubicacion }}" readonly placeholder="Ubicación seleccionada">

                                <div id="map"
                                    style="height: 400px; border-radius: 10px; border: 2px solid #28a745;"></div>

                                <input type="hidden" name="latitud" id="latitud" value="{{ $ganado->latitud }}">
                                <input type="hidden" name="longitud" id="longitud" value="{{ $ganado->longitud }}">
                                <input type="hidden" name="departamento" id="departamento"
                                    value="{{ $ganado->departamento }}">
                                <input type="hidden" name="municipio" id="municipio" value="{{ $ganado->municipio }}">
                                <input type="hidden" name="provincia" id="provincia" value="{{ $ganado->provincia }}">
                                <input type="hidden" name="ciudad" id="ciudad" value="{{ $ganado->ciudad }}">

                                <div id="info-ubicacion" class="mt-3"
                                    style="display: {{ $ganado->ciudad || $ganado->municipio ? 'block' : 'none' }};">
                                    <div class="card border-success">
                                        <div class="card-body">
                                            <h6 class="mb-3 text-success"><strong><i class="fas fa-info-circle"></i>
                                                    Ubicación Seleccionada</strong></h6>
                                            <div class="row mb-2">
                                                <div class="col-md-3">
                                                    <strong>Ciudad:</strong>
                                                </div>
                                                <div class="col-md-9" id="ciudad-texto">
                                                    {{ $ganado->ciudad ?? ($ganado->municipio ?? '-') }}
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <strong>Dirección:</strong>
                                                </div>
                                                <div class="col-md-9" id="direccion-texto">
                                                    @php
                                                        $direccion = [];
                                                        if ($ganado->municipio) {
                                                            $direccion[] = $ganado->municipio;
                                                        }
                                                        if ($ganado->provincia) {
                                                            $direccion[] = 'Provincia ' . $ganado->provincia;
                                                        }
                                                        if ($ganado->departamento) {
                                                            $direccion[] = $ganado->departamento;
                                                        }
                                                        $direccion[] = 'Bolivia';
                                                        $direccionCompleta = implode(', ', $direccion);
                                                    @endphp
                                                    {{ $direccionCompleta }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- SECCIÓN DE IMÁGENES (ANCHO COMPLETO) -->
                    <div class="row mt-3">
                        <div class="col-12">
                            <h6 class="text-success border-bottom pb-2 mb-3"><i class="fas fa-images"></i> Imágenes del
                                Animal</h6>
                            <div class="form-group mb-3">
                                <label class="font-weight-bold">Imágenes (máximo 3)</label>

                                @if ($ganado->imagenes && $ganado->imagenes->count() > 0)
                                    <div class="mb-3">
                                        <p class="text-muted"><strong>Imágenes actuales:</strong></p>
                                        <div class="row" id="imagenes-actuales">
                                            @foreach ($ganado->imagenes as $imagen)
                                                <div class="col-md-4 mb-3 imagen-item"
                                                    data-imagen-id="{{ $imagen->id }}">
                                                    <div class="position-relative">
                                                        <img src="{{ asset('storage/' . $imagen->ruta) }}"
                                                            alt="Imagen {{ $loop->iteration }}" class="img-thumbnail"
                                                            style="width: 100%; height: 150px; object-fit: cover;">
                                                        <button type="button"
                                                            class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1 eliminar-imagen"
                                                            data-imagen-id="{{ $imagen->id }}">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    </div>
                                                    <input type="hidden" name="imagenes_eliminar[]" value=""
                                                        class="imagen-eliminar-input">
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                <div id="preview-container" class="row mb-3"></div>

                                <input type="file" name="imagenes[]" class="form-control" accept="image/*" multiple
                                    id="imagenes-input">
                                <small class="form-text text-muted">Puedes seleccionar hasta 3 imágenes. Formatos
                                    permitidos: JPG, PNG, GIF. Tamaño máximo por imagen: 2MB</small>
                                <div id="imagenes-count" class="text-muted mt-2"></div>
                            </div>
                        </div>
                    </div>

                    <!-- BOTÓN DE ACTUALIZAR -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('ganados.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> Cancelar
                                </a>
                                <button type="submit" class="btn btn-success btn-lg shadow-sm">
                                    <i class="fas fa-save"></i> Actualizar Registro
                                </button>
                            </div>
                        </div>
                    </div>

                </form>

            </div>
        </div>

    </div>

    {{-- Leaflet Map --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        var map = L.map('map').setView([{{ $ganado->latitud ?? -17.7833 }}, {{ $ganado->longitud ?? -63.1821 }}], 7);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

        var marker = L.marker([{{ $ganado->latitud ?? -17.7833 }}, {{ $ganado->longitud ?? -63.1821 }}]).addTo(map);

        map.on('click', function(e) {
            var lat = e.latlng.lat.toFixed(7);
            var lng = e.latlng.lng.toFixed(7);

            marker.setLatLng([lat, lng]);

            document.getElementById('latitud').value = lat;
            document.getElementById('longitud').value = lng;
            document.getElementById('ubicacion').value = "Lat: " + lat + " - Lng: " + lng;

            // Obtener información geográfica
            obtenerInformacionGeografica(lat, lng);
        });

        // Función para obtener información geográfica
        function obtenerInformacionGeografica(lat, lng) {
            // Mostrar contenedor de información
            document.getElementById('info-ubicacion').style.display = 'block';
            document.getElementById('ciudad-texto').textContent = 'Cargando...';
            document.getElementById('direccion-texto').textContent = 'Cargando...';

            fetch('/api/geocodificacion?latitud=' + lat + '&longitud=' + lng)
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.data) {
                        var info = data.data;

                        // Guardar en campos ocultos
                        document.getElementById('departamento').value = info.departamento || '';
                        document.getElementById('municipio').value = info.municipio || '';
                        document.getElementById('provincia').value = info.provincia || '';
                        document.getElementById('ciudad').value = info.ciudad || '';

                        // Mostrar en la interfaz
                        document.getElementById('ciudad-texto').textContent = info.ciudad || info.municipio ||
                            'No disponible';

                        // Construir dirección completa: Municipio, Provincia, Departamento, Bolivia
                        var direccion = [];
                        if (info.municipio) direccion.push(info.municipio);
                        if (info.provincia) direccion.push('Provincia ' + info.provincia);
                        if (info.departamento) direccion.push(info.departamento);
                        direccion.push('Bolivia');

                        var direccionCompleta = direccion.join(', ');
                        document.getElementById('direccion-texto').textContent = direccionCompleta || 'No disponible';

                        // Actualizar campo ubicación
                        if (direccionCompleta) {
                            document.getElementById('ubicacion').value = direccionCompleta;
                        }
                    } else {
                        document.getElementById('ciudad-texto').textContent = 'No disponible';
                        document.getElementById('direccion-texto').textContent = 'No disponible';
                    }
                })
                .catch(error => {
                    console.error('Error al obtener información geográfica:', error);
                    document.getElementById('ciudad-texto').textContent = 'Error';
                    document.getElementById('direccion-texto').textContent = 'Error';
                });
        }
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const input = document.getElementById('imagenes-input');
            const previewContainer = document.getElementById('preview-container');
            const countDisplay = document.getElementById('imagenes-count');
            const imagenesActuales = {{ $ganado->imagenes ? $ganado->imagenes->count() : 0 }};
            let imagenesNuevas = 0;
            let imagenesAEliminar = [];

            // Manejar eliminación de imágenes existentes
            document.querySelectorAll('.eliminar-imagen').forEach(btn => {
                btn.addEventListener('click', function() {
                    const imagenId = this.getAttribute('data-imagen-id');
                    const imagenItem = this.closest('.imagen-item');
                    const inputEliminar = imagenItem.querySelector('.imagen-eliminar-input');

                    if (inputEliminar.value === '') {
                        inputEliminar.value = imagenId;
                        imagenItem.style.opacity = '0.5';
                        this.innerHTML = '<i class="fas fa-undo"></i>';
                        imagenesAEliminar.push(imagenId);
                    } else {
                        inputEliminar.value = '';
                        imagenItem.style.opacity = '1';
                        this.innerHTML = '<i class="fas fa-times"></i>';
                        imagenesAEliminar = imagenesAEliminar.filter(id => id !== imagenId);
                    }
                    updateCount();
                });
            });

            function updateCount() {
                const total = imagenesActuales - imagenesAEliminar.length + imagenesNuevas;
                countDisplay.textContent = `Total de imágenes: ${total} / 3`;

                if (total > 3) {
                    countDisplay.className = 'text-danger mt-2';
                    countDisplay.textContent += ' (Excede el límite de 3 imágenes)';
                } else {
                    countDisplay.className = 'text-muted mt-2';
                }
            }

            let fileMap = new Map();

            input.addEventListener('change', function(e) {
                previewContainer.innerHTML = '';
                imagenesNuevas = 0;
                fileMap.clear();

                const files = Array.from(e.target.files);
                const maxFiles = 3 - (imagenesActuales - imagenesAEliminar.length);

                files.slice(0, maxFiles).forEach((file, index) => {
                    if (file.type.startsWith('image/')) {
                        const fileId = Date.now() + '-' + index;
                        fileMap.set(fileId, file);

                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const col = document.createElement('div');
                            col.className = 'col-md-4 mb-3';
                            col.setAttribute('data-file-id', fileId);
                            col.innerHTML = `
                        <div class="position-relative">
                            <img src="${e.target.result}" 
                                 alt="Preview ${index + 1}" 
                                 class="img-thumbnail" 
                                 style="width: 100%; height: 150px; object-fit: cover;">
                            <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1 eliminar-preview" 
                                    data-file-id="${fileId}">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    `;
                            previewContainer.appendChild(col);
                            imagenesNuevas++;
                            updateCount();

                            // Agregar evento para eliminar preview
                            col.querySelector('.eliminar-preview').addEventListener('click',
                                function() {
                                    const fileIdToRemove = this.getAttribute(
                                    'data-file-id');
                                    fileMap.delete(fileIdToRemove);

                                    const dataTransfer = new DataTransfer();
                                    fileMap.forEach(file => dataTransfer.items.add(file));
                                    input.files = dataTransfer.files;

                                    col.remove();
                                    imagenesNuevas--;
                                    updateCount();
                                });
                        };
                        reader.readAsDataURL(file);
                    }
                });

                updateCount();
            });

            updateCount();
        });
    </script>

    <script>
        function incrementValue(fieldId, max) {
            const field = document.getElementById(fieldId);
            let value = parseInt(field.value) || 0;
            if (value < max) {
                value++;
                field.value = value;
            }
        }

        function decrementValue(fieldId) {
            const field = document.getElementById(fieldId);
            let value = parseInt(field.value) || 0;
            if (value > 0) {
                value--;
                field.value = value;
            }
        }
    </script>
@endsection
