@csrf

{{-- ========== CARD 1: DATOS DE LA MAQUINARIA ========== --}}
<div class="card card-outline card-success shadow-sm mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title mb-0">
            <i class="fas fa-tractor mr-2"></i> Datos de la maquinaria
        </h3>
        <span class="badge badge-success">Nuevo / Edición</span>
    </div>

    <div class="card-body">
        <div class="row">

            {{-- COLUMNA IZQUIERDA --}}
            <div class="col-md-6">
                <h6 class="text-muted text-uppercase mb-3">
                    <i class="fas fa-info-circle mr-1"></i> Información básica
                </h6>

                <div class="form-group">
                    <label class="mb-1">Nombre *</label>
                    <input name="nombre" class="form-control" placeholder="Ej: Tractor John Deere 5050E"
                        value="{{ old('nombre', $maquinaria->nombre ?? '') }}" required>
                </div>

                <div class="form-group">
                    <label class="mb-1">Categoría *</label>
                    <select name="categoria_id" class="form-control" required>
                        <option value="">Seleccione una categoría</option>
                        @foreach ($categorias as $categoria)
                            <option value="{{ $categoria->id }}"
                                {{ old('categoria_id', $maquinaria->categoria_id ?? '') == $categoria->id ? 'selected' : '' }}>
                                {{ $categoria->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="mb-1">Tipo de Maquinaria *</label>
                    <select name="tipo_maquinaria_id" class="form-control" required>
                        <option value="">Seleccione un tipo de maquinaria</option>
                        @foreach ($tipo_maquinarias as $tipo)
                            <option value="{{ $tipo->id }}"
                                {{ old('tipo_maquinaria_id', $maquinaria->tipo_maquinaria_id ?? '') == $tipo->id ? 'selected' : '' }}>
                                {{ $tipo->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="mb-1">Marca de Maquinaria *</label>
                    <select name="marca_maquinaria_id" class="form-control" required>
                        <option value="">Seleccione una marca de maquinaria</option>
                        @foreach ($marcas_maquinarias as $marca)
                            <option value="{{ $marca->id }}"
                                {{ old('marca_maquinaria_id', $maquinaria->marca_maquinaria_id ?? '') == $marca->id ? 'selected' : '' }}>
                                {{ $marca->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group mb-0">
                    <label class="mb-1">Modelo</label>
                    <input name="modelo" class="form-control" placeholder="Ej: 5050E"
                        value="{{ old('modelo', $maquinaria->modelo ?? '') }}">
                </div>
            </div>

            {{-- COLUMNA DERECHA --}}
            <div class="col-md-6">
                <h6 class="text-muted text-uppercase mb-3">
                    <i class="fas fa-hand-holding-usd mr-1"></i> Contacto y alquiler
                </h6>

                <div class="form-group">
                    <label class="mb-1">Teléfono</label>
                    <input type="tel" name="telefono" class="form-control" placeholder="Ej: +591 700 00000"
                        value="{{ old('telefono', $maquinaria->telefono ?? '') }}">
                </div>

                <div class="form-group">
                    <label class="mb-1">Precio por día *</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Bs/día</span>
                        </div>
                        <input type="number" step="0.01" name="precio_dia" class="form-control" placeholder="0.00"
                            value="{{ old('precio_dia', $maquinaria->precio_dia ?? 0) }}" required>
                    </div>
                    <small class="form-text text-muted">
                        Monto a cobrar por cada día de alquiler.
                    </small>
                </div>

                <div class="form-group">
                    <label class="mb-1">Estado *</label>
                    <select name="estado_maquinaria_id" class="form-control" required>
                        <option value="">Seleccione un estado</option>
                        @foreach ($estado_maquinarias as $estado)
                            <option value="{{ $estado->id }}"
                                {{ old('estado_maquinaria_id', $maquinaria->estado_maquinaria_id ?? '') == $estado->id ? 'selected' : '' }}>
                                {{ $estado->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group mb-0">
                    <label class="mb-1">Descripción</label>
                    <textarea name="descripcion" class="form-control" rows="4"
                        placeholder="Condiciones de uso, características técnicas, recomendaciones, etc.">{{ old('descripcion', $maquinaria->descripcion ?? '') }}</textarea>
                </div>
            </div>

        </div>
    </div>
</div>

{{-- ========== CARD 2: UBICACIÓN ========== --}}
<div class="card card-outline card-success shadow-sm mb-4">
    <div class="card-header">
        <h3 class="card-title mb-0">
            <i class="fas fa-map-marker-alt mr-2"></i> Ubicación de la maquinaria
        </h3>
    </div>

    <div class="card-body">
        <div class="form-group mb-3">
            <label class="mb-1">Ubicación (seleccione en el mapa)</label>
            <div id="map" style="height: 400px; margin-top: 10px; border-radius: 8px; border: 1px solid #e0e0e0;">
            </div>

            <input type="hidden" name="latitud" id="latitud"
                value="{{ old('latitud', $maquinaria->latitud ?? '') }}">
            <input type="hidden" name="longitud" id="longitud"
                value="{{ old('longitud', $maquinaria->longitud ?? '') }}">
            <input type="hidden" name="departamento" id="departamento"
                value="{{ old('departamento', $maquinaria->departamento ?? '') }}">
            <input type="hidden" name="municipio" id="municipio"
                value="{{ old('municipio', $maquinaria->municipio ?? '') }}">
            <input type="hidden" name="provincia" id="provincia"
                value="{{ old('provincia', $maquinaria->provincia ?? '') }}">
            <input type="hidden" name="ciudad" id="ciudad"
                value="{{ old('ciudad', $maquinaria->ciudad ?? '') }}">

            <input type="text" id="ubicacion" name="ubicacion" class="form-control mt-2"
                value="{{ old('ubicacion', $maquinaria->ubicacion ?? '') }}" readonly>
        </div>

        <div id="info-ubicacion" class="mt-2"
            style="display: {{ isset($maquinaria) && ($maquinaria->ciudad || $maquinaria->municipio) ? 'block' : 'none' }};">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-3">
                    <h6 class="mb-3 text-muted text-uppercase">
                        <i class="fas fa-map mr-1"></i> Detalle de ubicación
                    </h6>
                    <div class="row mb-2">
                        <div class="col-md-3">
                            <strong>Ciudad:</strong>
                        </div>
                        <div class="col-md-9" id="ciudad-texto">
                            {{ isset($maquinaria) ? $maquinaria->ciudad ?? ($maquinaria->municipio ?? '-') : '-' }}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <strong>Dirección:</strong>
                        </div>
                        <div class="col-md-9" id="direccion-texto">
                            @if (isset($maquinaria) && ($maquinaria->municipio || $maquinaria->provincia || $maquinaria->departamento))
                                @php
                                    $direccion = [];
                                    if ($maquinaria->municipio) {
                                        $direccion[] = $maquinaria->municipio;
                                    }
                                    if ($maquinaria->provincia) {
                                        $direccion[] = 'Provincia ' . $maquinaria->provincia;
                                    }
                                    if ($maquinaria->departamento) {
                                        $direccion[] = $maquinaria->departamento;
                                    }
                                    $direccion[] = 'Bolivia';
                                    $direccionCompleta = implode(', ', $direccion);
                                @endphp
                                {{ $direccionCompleta }}
                            @else
                                -
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ========== CARD 3: IMÁGENES ========== --}}
<div class="card card-outline card-success shadow-sm mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title mb-0">
            <i class="fas fa-images mr-2"></i> Imágenes de la maquinaria
        </h3>
        <small class="text-muted">Máximo 3 imágenes por publicación</small>
    </div>

    <div class="card-body">
        <div class="form-group mb-0">
            <label class="mb-2 d-block">Imágenes</label>

            @if (isset($maquinaria) && $maquinaria->imagenes->count() > 0)
                <div class="mb-3">
                    <p class="text-muted mb-2">Imágenes actuales:</p>
                    <div class="row" id="imagenes-actuales">
                        @foreach ($maquinaria->imagenes as $imagen)
                            <div class="col-md-3 mb-3 imagen-item" data-imagen-id="{{ $imagen->id }}">
                                <div class="position-relative">
                                    <img src="{{ asset('storage/' . $imagen->ruta) }}"
                                        alt="Imagen {{ $loop->iteration }}" class="img-thumbnail"
                                        style="width: 100%; height: 150px; object-fit: cover; border-radius: 8px;">
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
            <small class="form-text text-muted">
                Puedes seleccionar hasta 3 imágenes. Formatos permitidos: JPG, PNG, GIF. Tamaño máximo por imagen: 2MB.
            </small>
            <div id="imagenes-count" class="text-muted mt-2"></div>
        </div>
    </div>
</div>

{{-- BOTONES --}}
<div class="d-flex justify-content-end mb-2">
    <a href="{{ url()->previous() !== url()->current() ? url()->previous() : route('maquinarias.index') }}"
        class="btn btn-outline-secondary mr-2">
        <i class="fas fa-arrow-left mr-1"></i> Volver
    </a>
    <button class="btn btn-success">
        <i class="fas fa-save mr-1"></i> Guardar
    </button>
</div>

{{-- ========== JS DE IMÁGENES (MISMO FUNCIONAMIENTO) ========== --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const input = document.getElementById('imagenes-input');
        const previewContainer = document.getElementById('preview-container');
        const countDisplay = document.getElementById('imagenes-count');
        const imagenesActuales =
            {{ isset($maquinaria) && $maquinaria->imagenes ? $maquinaria->imagenes->count() : 0 }};
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
                        col.className = 'col-md-3 mb-3';
                        col.setAttribute('data-file-id', fileId);
                        col.innerHTML = `
                        <div class="position-relative">
                            <img src="${e.target.result}"
                                 alt="Preview ${index + 1}"
                                 class="img-thumbnail"
                                 style="width: 100%; height: 150px; object-fit: cover; border-radius: 8px;">
                            <button type="button"
                                    class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1 eliminar-preview"
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

{{-- ========== LEAFLET ========== --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    // Coordenadas iniciales (centro de Bolivia)
    var initialLat = {{ old('latitud', $maquinaria->latitud ?? -17.7833) }};
    var initialLng = {{ old('longitud', $maquinaria->longitud ?? -63.1821) }};
    var initialZoom = {{ isset($maquinaria) && $maquinaria->latitud ? 12 : 6 }};

    // Crear el mapa
    var map = L.map('map').setView([initialLat, initialLng], initialZoom);

    // Capa gratuita de OpenStreetMap
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: 'OpenStreetMap'
    }).addTo(map);

    var marker;

    // Si hay coordenadas existentes, mostrar el marcador
    @if (isset($maquinaria) && $maquinaria->latitud && $maquinaria->longitud)
        marker = L.marker([initialLat, initialLng]).addTo(map);
    @endif

    // Evento click en mapa
    map.on('click', function(e) {
        var lat = e.latlng.lat.toFixed(7);
        var lng = e.latlng.lng.toFixed(7);

        if (marker) {
            marker.setLatLng([lat, lng]);
        } else {
            marker = L.marker([lat, lng]).addTo(map);
        }

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
