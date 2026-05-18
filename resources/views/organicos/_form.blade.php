@csrf

{{-- ====== CARD 1: DATOS DEL PRODUCTO ====== --}}
<div class="card card-outline card-success shadow-sm mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title mb-0">
            <i class="fas fa-leaf mr-2"></i>Datos del producto orgánico
        </h3>
        <span class="badge badge-success">Nuevo / Edición</span>
    </div>

    <div class="card-body">
        <div class="row">

            {{-- COLUMNA IZQUIERDA: DATOS BÁSICOS --}}
            <div class="col-md-6">
                <h6 class="text-muted text-uppercase mb-3">
                    <i class="fas fa-info-circle mr-1"></i> Datos básicos
                </h6>

                <div class="form-group">
                    <label class="mb-1">Nombre *</label>
                    <input type="text" name="nombre" class="form-control"
                        placeholder="Ej: Zanahoria orgánica fresca" value="{{ old('nombre', $organico->nombre ?? '') }}"
                        required>
                </div>

                <div class="form-group">
                    <label for="categoria_id" class="mb-1">Categoría *</label>
                    <select name="categoria_id" id="categoria_id" class="form-control" required>
                        <option value="">Seleccione una categoría</option>
                        @foreach ($categorias as $categoria)
                            <option value="{{ $categoria->id }}"
                                {{ old('categoria_id', $organico->categoria_id ?? '') == $categoria->id ? 'selected' : '' }}>
                                {{ $categoria->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="tipo_cultivo_id" class="mb-1">Tipo de Cultivo *</label>
                    <select name="tipo_cultivo_id" id="tipo_cultivo_id" class="form-control" required>
                        <option value="">Seleccione un tipo</option>
                        @foreach ($tiposCultivo as $tipo)
                            <option value="{{ $tipo->id }}"
                                {{ old('tipo_cultivo_id', $organico->tipo_cultivo_id ?? '') == $tipo->id ? 'selected' : '' }}>
                                {{ $tipo->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group mb-0">
                    <label for="unidad_id" class="mb-1">Unidad de Medida</label>
                    <select name="unidad_id" id="unidad_id" class="form-control">
                        <option value="">Seleccione una unidad</option>
                        @foreach ($unidades as $unidad)
                            <option value="{{ $unidad->id }}"
                                {{ old('unidad_id', $organico->unidad_id ?? '') == $unidad->id ? 'selected' : '' }}>
                                {{ $unidad->nombre }}
                            </option>
                        @endforeach
                    </select>
                    <small class="form-text text-muted">
                        Ejemplos: Kg, Libras, Atados, Saco 50kg, Docena
                    </small>
                </div>
            </div>

            {{-- COLUMNA DERECHA: INFORMACIÓN COMERCIAL --}}
            <div class="col-md-6">
                <h6 class="text-muted text-uppercase mb-3">
                    <i class="fas fa-chart-line mr-1"></i> Información comercial
                </h6>

                <div class="form-group">
                    <label class="mb-1">Precio *</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Bs</span>
                        </div>
                        <input type="number" step="0.01" name="precio" class="form-control" placeholder="0.00"
                            value="{{ old('precio', $organico->precio ?? 0) }}" required>
                    </div>
                    <small class="form-text text-muted">
                        Ingrese el precio unitario según la unidad seleccionada.
                    </small>
                </div>

                <div class="form-group">
                    <label class="mb-1">Stock *</label>
                    <input type="number" name="stock" class="form-control" placeholder="Cantidad disponible"
                        value="{{ old('stock', $organico->stock ?? 0) }}" required>
                    <small class="form-text text-muted">
                        Cantidad disponible para la venta.
                    </small>
                </div>

                <div class="form-group mb-0">
                    <label class="mb-1">Fecha de cosecha</label>
                    <input type="date" name="fecha_cosecha" class="form-control"
                        value="{{ old('fecha_cosecha', $organico->fecha_cosecha ?? '') }}">
                    <small class="form-text text-muted">
                        Opcional, pero ayuda a dar confianza al comprador.
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ====== CARD 2: DESCRIPCIÓN + ORIGEN ====== --}}
<div class="card card-outline card-success shadow-sm mb-4">
    <div class="card-header">
        <h3 class="card-title mb-0">
            <i class="fas fa-map-marker-alt mr-2"></i>Descripción y origen del producto
        </h3>
    </div>

    <div class="card-body">
        <div class="row">

            {{-- DESCRIPCIÓN --}}
            <div class="col-md-5">
                <div class="form-group mb-0">
                    <label class="mb-1">Descripción</label>
                    <textarea name="descripcion" class="form-control" rows="6"
                        placeholder="Describe características, certificaciones, forma de cultivo, etc.">{{ old('descripcion', $organico->descripcion ?? '') }}</textarea>
                    <small class="form-text text-muted">
                        Esta información aparecerá en la ficha del producto.
                    </small>
                </div>
            </div>

            {{-- ORIGEN / MAPA (MISMO ESTILO QUE MAQUINARIA) --}}
            <div class="col-md-7 mt-3 mt-md-0">
                <h6 class="text-muted text-uppercase mb-2">
                    <i class="fas fa-map-marked-alt mr-1"></i> Origen del producto
                </h6>

                <div class="form-group mb-2">
                    <label class="mb-1">Ubicación (seleccione en el mapa)</label>
                    <div id="map-origen" style="height: 320px; border-radius: 8px; border: 1px solid #e0e0e0;"></div>
                </div>

                {{-- BARRA CON DIRECCIÓN RESUMIDA (como en maquinaria) --}}
                <input type="text" id="origen" name="origen" class="form-control mb-3"
                    value="{{ old('origen', $organico->origen ?? '') }}" readonly>

                {{-- DETALLE DE UBICACIÓN (CIUDAD + DIRECCIÓN) --}}
                <div id="info-origen" class="mt-1"
                    style="display: {{ isset($organico) && ($organico->origen ?? false) ? 'block' : 'none' }};">
                    <div class="card border">
                        <div class="card-body py-3">
                            <h6 class="mb-3 text-muted text-uppercase">
                                <i class="fas fa-map mr-1"></i> Detalle de ubicación
                            </h6>

                            <div class="row mb-2">
                                <div class="col-md-3">
                                    <strong>Ciudad:</strong>
                                </div>
                                <div class="col-md-9" id="ciudad-origen-texto">
                                    {{ isset($organico) ? $organico->ciudad_origen ?? '-' : '-' }}
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-3">
                                    <strong>Dirección:</strong>
                                </div>
                                <div class="col-md-9" id="direccion-origen-texto">
                                    @if (isset($organico) && ($organico->origen ?? false))
                                        {{ $organico->origen }}
                                    @else
                                        -
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- CAMPOS OCULTOS PARA GUARDAR COORDENADAS Y DATOS --}}
                <input type="hidden" name="latitud_origen" id="latitud_origen"
                    value="{{ old('latitud_origen', $organico->latitud_origen ?? '') }}">
                <input type="hidden" name="longitud_origen" id="longitud_origen"
                    value="{{ old('longitud_origen', $organico->longitud_origen ?? '') }}">

                <input type="hidden" name="departamento_origen" id="departamento_origen"
                    value="{{ old('departamento_origen', $organico->departamento_origen ?? '') }}">
                <input type="hidden" name="municipio_origen" id="municipio_origen"
                    value="{{ old('municipio_origen', $organico->municipio_origen ?? '') }}">
                <input type="hidden" name="provincia_origen" id="provincia_origen"
                    value="{{ old('provincia_origen', $organico->provincia_origen ?? '') }}">
                <input type="hidden" name="ciudad_origen" id="ciudad_origen"
                    value="{{ old('ciudad_origen', $organico->ciudad_origen ?? '') }}">
            </div>
        </div>
    </div>
</div>

{{-- ====== CARD 3: IMÁGENES ====== --}}
<div class="card card-outline card-success shadow-sm mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title mb-0">
            <i class="fas fa-images mr-2"></i>Imágenes del producto
        </h3>
        <small class="text-muted">Máximo 3 imágenes por publicación</small>
    </div>

    <div class="card-body">
        <div class="form-group mb-0">
            <label class="mb-2 d-block">Imágenes</label>

            @if (isset($organico) && $organico->imagenes && $organico->imagenes->count() > 0)
                <div class="mb-3">
                    <p class="text-muted mb-2">Imágenes actuales:</p>
                    <div class="row" id="imagenes-actuales">
                        @foreach ($organico->imagenes as $imagen)
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
                Formatos permitidos: JPG, PNG, GIF. Tamaño máximo por imagen: 2MB.
            </small>
            <div id="imagenes-count" class="text-muted mt-2"></div>
        </div>
    </div>
</div>

{{-- BOTONES --}}
<div class="d-flex justify-content-end mb-2">
    <a href="{{ url()->previous() !== url()->current() ? url()->previous() : route('organicos.index') }}"
        class="btn btn-outline-secondary mr-2">
        <i class="fas fa-arrow-left mr-1"></i> Volver
    </a>
    <button class="btn btn-success">
        <i class="fas fa-save mr-1"></i> Guardar
    </button>
</div>

{{-- ====== LEAFLET CSS / JS ====== --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    // Coordenadas iniciales (centro de Bolivia)
    var initialLat = {{ old('latitud_origen', $organico->latitud_origen ?? -17.7833) }};
    var initialLng = {{ old('longitud_origen', $organico->longitud_origen ?? -63.1821) }};
    var initialZoom = {{ isset($organico) && $organico->latitud_origen ? 12 : 6 }};

    // Crear el mapa
    var mapOrigen = L.map('map-origen').setView([initialLat, initialLng], initialZoom);

    // Capa gratuita de OpenStreetMap
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: 'OpenStreetMap'
    }).addTo(mapOrigen);

    var markerOrigen;

    // Si hay coordenadas existentes, mostrar el marcador
    @if (isset($organico) && $organico->latitud_origen && $organico->longitud_origen)
        markerOrigen = L.marker([initialLat, initialLng]).addTo(mapOrigen);
    @endif

    // Evento click en mapa
    mapOrigen.on('click', function(e) {
        var lat = e.latlng.lat.toFixed(7);
        var lng = e.latlng.lng.toFixed(7);

        if (markerOrigen) {
            markerOrigen.setLatLng([lat, lng]);
        } else {
            markerOrigen = L.marker([lat, lng]).addTo(mapOrigen);
        }

        document.getElementById('latitud_origen').value = lat;
        document.getElementById('longitud_origen').value = lng;

        // Valor provisional mientras llega la geocodificación
        document.getElementById('origen').value = "Lat: " + lat + " - Lng: " + lng;

        obtenerInformacionOrigen(lat, lng);
    });

    // Función similar a la de MAQUINARIA
    function obtenerInformacionOrigen(lat, lng) {
        const infoContainer = document.getElementById('info-origen');
        const ciudadTexto = document.getElementById('ciudad-origen-texto');
        const direccionTexto = document.getElementById('direccion-origen-texto');

        if (!infoContainer) return;

        infoContainer.style.display = 'block';
        ciudadTexto.textContent = 'Cargando...';
        direccionTexto.textContent = 'Cargando...';

        fetch('/api/geocodificacion?latitud=' + lat + '&longitud=' + lng)
            .then(response => response.json())
            .then(data => {
                if (data.success && data.data) {
                    var info = data.data;

                    // Guardar en campos ocultos
                    document.getElementById('departamento_origen').value = info.departamento || '';
                    document.getElementById('municipio_origen').value = info.municipio || '';
                    document.getElementById('provincia_origen').value = info.provincia || '';
                    document.getElementById('ciudad_origen').value = info.ciudad || '';

                    // Mostrar en interfaz
                    ciudadTexto.textContent = info.ciudad || info.municipio || 'No disponible';

                    var direccion = [];
                    if (info.municipio) direccion.push(info.municipio);
                    if (info.provincia) direccion.push('Provincia ' + info.provincia);
                    if (info.departamento) direccion.push(info.departamento);
                    direccion.push('Bolivia');

                    var direccionCompleta = direccion.join(', ');
                    direccionTexto.textContent = direccionCompleta || 'No disponible';

                    // Actualizar campo origen con la dirección bonita
                    if (direccionCompleta) {
                        document.getElementById('origen').value = direccionCompleta;
                    }
                } else {
                    ciudadTexto.textContent = 'No disponible';
                    direccionTexto.textContent = 'No disponible';
                }
            })
            .catch(error => {
                console.error('Error al obtener información geográfica:', error);
                ciudadTexto.textContent = 'Error';
                direccionTexto.textContent = 'Error';
            });
    }
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const input = document.getElementById('imagenes-input');
        const previewContainer = document.getElementById('preview-container');
        const countDisplay = document.getElementById('imagenes-count');
        const imagenesActuales =
            {{ isset($organico) && $organico->imagenes ? $organico->imagenes->count() : 0 }};
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
