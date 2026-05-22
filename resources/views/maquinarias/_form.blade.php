@csrf

@php
    $wizardSteps = [
        [
            'icon' => 'fas fa-tractor',
            'title' => 'Datos básicos',
            'description' => 'Identifica la maquinaria y su clasificación principal.',
        ],
        [
            'icon' => 'fas fa-hand-holding-usd',
            'title' => 'Alquiler y estado',
            'description' => 'Define contacto, precio, condición y detalles para el anuncio.',
        ],
        [
            'icon' => 'fas fa-map-marker-alt',
            'title' => 'Ubicación',
            'description' => 'Marca en el mapa dónde se encuentra disponible la maquinaria.',
        ],
        [
            'icon' => 'fas fa-images',
            'title' => 'Imágenes',
            'description' => 'Agrega o administra las fotografías de la publicación.',
        ],
    ];
@endphp

<!-- CARGAR CSS GENÉRICO -->
<link rel="stylesheet" href="{{ asset('css/wizard-form.css') }}">

<div class="agro-wizard" data-agro-wizard>
    <div class="agro-wizard__shell">
    <div class="agro-wizard__hero">
        <div>
            <span class="agro-wizard__eyebrow">Registro de maquinaria agrícola</span>
            <h3 class="agro-wizard__title mb-1">
                <i class="fas fa-tractor mr-2"></i>{{ isset($maquinaria) ? 'Editar maquinaria' : 'Nueva maquinaria' }}
            </h3>
            <p class="agro-wizard__subtitle mb-0">
                Completa la información paso a paso. Los datos se conservan al avanzar o retroceder.
            </p>
        </div>
        <span class="badge badge-success agro-wizard__badge" data-wizard-current-label>
            Paso 1 de {{ count($wizardSteps) }}
        </span>
    </div>

    <div class="agro-wizard__progress" role="tablist" aria-label="Pasos del registro">
        @foreach ($wizardSteps as $index => $step)
            <button type="button" class="agro-wizard__step-indicator {{ $index === 0 ? 'is-active' : '' }}"
                data-wizard-go-to="{{ $index }}" aria-current="{{ $index === 0 ? 'step' : 'false' }}">
                <span class="agro-wizard__step-number">{{ $index + 1 }}</span>
                <span class="agro-wizard__step-icon">
                    <i class="{{ $step['icon'] }}"></i>
                </span>
                <span class="agro-wizard__step-copy">
                    <span class="agro-wizard__step-title">{{ $step['title'] }}</span>
                    <span class="agro-wizard__step-description">{{ $step['description'] }}</span>
                    <span class="agro-wizard__step-status" data-wizard-step-status>Pendiente</span>
                </span>
            </button>
        @endforeach
    </div>

    <div class="agro-wizard__progressbar" aria-hidden="true">
        <span data-wizard-progressbar style="width: {{ 100 / max(count($wizardSteps), 1) }}%;"></span>
    </div>

    <div class="alert alert-danger agro-wizard__error-summary d-none" data-wizard-error-summary></div>

    <div class="agro-wizard__content">

    {{-- ========== PASO 1: DATOS BASICOS ========== --}}
    <section class="card card-outline card-success shadow-sm mb-4 agro-wizard-step is-active" data-wizard-step="0">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <h3 class="card-title mb-0">
                    <i class="fas fa-tractor mr-2"></i> Datos de la maquinaria
                </h3>
                <small class="text-muted">Identificación, categoría, tipo, marca y modelo.</small>
            </div>
            <span class="badge badge-success">Paso 1 de {{ count($wizardSteps) }}</span>
        </div>

        <div class="card-body">
            <h6 class="text-muted text-uppercase mb-3">
                <i class="fas fa-info-circle mr-1"></i> Información básica
            </h6>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="mb-1">Nombre *</label>
                        <input name="nombre" class="form-control @error('nombre') is-invalid @enderror"
                            placeholder="Ej: Tractor John Deere 5050E"
                            value="{{ old('nombre', $maquinaria->nombre ?? '') }}" required>
                    </div>

                    <div class="form-group">
                        <label class="mb-1">Categoría *</label>
                        <select name="categoria_id" class="form-control @error('categoria_id') is-invalid @enderror" required>
                            <option value="">Seleccione una categoría</option>
                            @foreach ($categorias as $categoria)
                                <option value="{{ $categoria->id }}"
                                    {{ old('categoria_id', $maquinaria->categoria_id ?? '') == $categoria->id ? 'selected' : '' }}>
                                    {{ $categoria->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group mb-md-0">
                        <label class="mb-1">Tipo de Maquinaria *</label>
                        <select name="tipo_maquinaria_id"
                            class="form-control @error('tipo_maquinaria_id') is-invalid @enderror" required>
                            <option value="">Seleccione un tipo de maquinaria</option>
                            @foreach ($tipo_maquinarias as $tipo)
                                <option value="{{ $tipo->id }}"
                                    {{ old('tipo_maquinaria_id', $maquinaria->tipo_maquinaria_id ?? '') == $tipo->id ? 'selected' : '' }}>
                                    {{ $tipo->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="mb-1">Marca de Maquinaria *</label>
                        <select name="marca_maquinaria_id"
                            class="form-control @error('marca_maquinaria_id') is-invalid @enderror" required>
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
                        <input name="modelo" class="form-control @error('modelo') is-invalid @enderror"
                            placeholder="Ej: 5050E" value="{{ old('modelo', $maquinaria->modelo ?? '') }}">
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ========== PASO 2: CONTACTO Y ALQUILER ========== --}}
    <section class="card card-outline card-success shadow-sm mb-4 agro-wizard-step" data-wizard-step="1">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <h3 class="card-title mb-0">
                    <i class="fas fa-hand-holding-usd mr-2"></i> Contacto y alquiler
                </h3>
                <small class="text-muted">Datos comerciales, estado y descripción del servicio.</small>
            </div>
            <span class="badge badge-success">Paso 2 de {{ count($wizardSteps) }}</span>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="mb-1">Teléfono</label>
                        <input type="tel" name="telefono"
                            class="form-control @error('telefono') is-invalid @enderror"
                            placeholder="Ej: +591 700 00000"
                            value="{{ old('telefono', $maquinaria->telefono ?? '') }}">
                    </div>

                    <div class="form-group mb-md-0">
                        <label class="mb-1">Precio por día *</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Bs/día</span>
                            </div>
                            <input type="number" step="0.01" name="precio_dia"
                                class="form-control @error('precio_dia') is-invalid @enderror" placeholder="0.00"
                                value="{{ old('precio_dia', $maquinaria->precio_dia ?? 0) }}" min="0" required>
                        </div>
                        <small class="form-text text-muted">
                            Monto a cobrar por cada día de alquiler.
                        </small>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="mb-1">Estado *</label>
                        <select name="estado_maquinaria_id"
                            class="form-control @error('estado_maquinaria_id') is-invalid @enderror" required>
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
                        <textarea name="descripcion" class="form-control @error('descripcion') is-invalid @enderror" rows="4"
                            placeholder="Condiciones de uso, características técnicas, recomendaciones, etc.">{{ old('descripcion', $maquinaria->descripcion ?? '') }}</textarea>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ========== PASO 3: UBICACION ========== --}}
    <section class="card card-outline card-success shadow-sm mb-4 agro-wizard-step" data-wizard-step="2">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <h3 class="card-title mb-0">
                    <i class="fas fa-map-marker-alt mr-2"></i> Ubicación de la maquinaria
                </h3>
                <small class="text-muted">Selecciona el punto exacto en el mapa si deseas mostrar ubicación.</small>
            </div>
            <span class="badge badge-success">Paso 3 de {{ count($wizardSteps) }}</span>
        </div>

        <div class="card-body">
            <div class="form-group mb-3">
                <label class="mb-1">Ubicación (seleccione en el mapa)</label>
                <div id="map" class="agro-wizard__map"></div>

                <input type="hidden" name="latitud" id="latitud" value="{{ old('latitud', $maquinaria->latitud ?? '') }}">
                <input type="hidden" name="longitud" id="longitud" value="{{ old('longitud', $maquinaria->longitud ?? '') }}">
                <input type="hidden" name="departamento" id="departamento" value="{{ old('departamento', $maquinaria->departamento ?? '') }}">
                <input type="hidden" name="municipio" id="municipio" value="{{ old('municipio', $maquinaria->municipio ?? '') }}">
                <input type="hidden" name="provincia" id="provincia" value="{{ old('provincia', $maquinaria->provincia ?? '') }}">
                <input type="hidden" name="ciudad" id="ciudad" value="{{ old('ciudad', $maquinaria->ciudad ?? '') }}">

                <input type="text" id="ubicacion" name="ubicacion"
                    class="form-control mt-2 @error('ubicacion') is-invalid @enderror"
                    value="{{ old('ubicacion', $maquinaria->ubicacion ?? '') }}" readonly>
            </div>

            <div id="info-ubicacion" class="agro-wizard__location-detail mt-2"
                style="display: {{ isset($maquinaria) && ($maquinaria->ciudad || $maquinaria->municipio) ? 'block' : 'none' }};">
                <h6 class="mb-3 text-muted text-uppercase">
                    <i class="fas fa-map mr-1"></i> Detalle de ubicación
                </h6>
                <div class="row mb-2">
                    <div class="col-md-3"><strong>Ciudad:</strong></div>
                    <div class="col-md-9" id="ciudad-texto">{{ isset($maquinaria) ? $maquinaria->ciudad ?? ($maquinaria->municipio ?? '-') : '-' }}</div>
                </div>
                <div class="row">
                    <div class="col-md-3"><strong>Dirección:</strong></div>
                    <div class="col-md-9" id="direccion-texto">
                        @if (isset($maquinaria) && ($maquinaria->municipio || $maquinaria->provincia || $maquinaria->departamento))
                            @php
                                $direccion = [];
                                if ($maquinaria->municipio) $direccion[] = $maquinaria->municipio;
                                if ($maquinaria->provincia) $direccion[] = 'Provincia ' . $maquinaria->provincia;
                                if ($maquinaria->departamento) $direccion[] = $maquinaria->departamento;
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
    </section>

    {{-- ========== PASO 4: IMAGENES ========== --}}
    <section class="card card-outline card-success shadow-sm mb-4 agro-wizard-step" data-wizard-step="3">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <h3 class="card-title mb-0">
                    <i class="fas fa-images mr-2"></i> Imágenes de la maquinaria
                </h3>
                <small class="text-muted">Máximo 3 imágenes por publicación.</small>
            </div>
            <span class="badge badge-success">Paso 4 de {{ count($wizardSteps) }}</span>
        </div>

        <div class="card-body">
            <div class="form-group mb-0">
                <label class="mb-2 d-block">Imágenes</label>

                @if (isset($maquinaria) && $maquinaria->imagenes->count() > 0)
                    <div class="mb-3">
                        <p class="text-muted mb-2">Imágenes actuales:</p>
                        <div class="row" id="imagenes-actuales" data-count="{{ $maquinaria->imagenes->count() }}">
                            @foreach ($maquinaria->imagenes as $imagen)
                                <div class="col-md-3 mb-3 imagen-item" data-imagen-id="{{ $imagen->id }}">
                                    <div class="position-relative">
                                        <img src="{{ asset('storage/' . $imagen->ruta) }}"
                                            alt="Imagen {{ $loop->iteration }}" class="img-thumbnail"
                                            style="width: 100%; height: 150px; object-fit: cover; border-radius: 8px;">
                                        <button type="button"
                                            class="btn btn-sm btn-danger position-absolute eliminar-imagen"
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

                <label for="imagenes-input"
                    class="agro-upload-zone @error('imagenes') is-invalid @enderror @error('imagenes.*') is-invalid @enderror"
                    data-upload-zone>
                    <span class="agro-upload-zone__icon">
                        <i class="fas fa-cloud-upload-alt"></i>
                    </span>
                    <span class="agro-upload-zone__content">
                        <strong>Haz clic para subir imágenes</strong>
                        <small>También puedes arrastrar tus archivos aquí. JPG, PNG o GIF, máximo 2MB por imagen.</small>
                    </span>
                    <span class="agro-upload-zone__cta">Seleccionar archivos</span>
                </label>

                <input type="file" name="imagenes[]" class="agro-upload-input" accept="image/*" multiple id="imagenes-input">

                <div id="imagenes-count" class="agro-upload-count mt-3"></div>
                <div id="preview-container" class="row mt-3 mb-0"></div>
            </div>
        </div>
    </section>

    </div>

    {{-- BOTONES --}}
    <div class="agro-wizard__actions">
        <a href="{{ url()->previous() !== url()->current() ? url()->previous() : route('maquinarias.index') }}"
            class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left mr-1"></i> Volver
        </a>
        <div class="agro-wizard__action-group">
            <button type="button" class="btn btn-outline-agro" data-wizard-prev disabled>
                <i class="fas fa-chevron-left mr-1"></i> Anterior
            </button>
            <button type="button" class="btn btn-success" data-wizard-next>
                Siguiente <i class="fas fa-chevron-right ml-1"></i>
            </button>
            <button type="submit" class="btn btn-success d-none" data-wizard-submit>
                <i class="fas fa-save mr-1"></i> Guardar
            </button>
        </div>
    </div>
    </div>
</div>

{{-- ========== LEAFLET ========== --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    // Coordenadas iniciales (centro de Bolivia)
    var initialLat = {{ old('latitud', $maquinaria->latitud ?? -17.7833) }};
    var initialLng = {{ old('longitud', $maquinaria->longitud ?? -63.1821) }};
    var initialZoom = {{ isset($maquinaria) && $maquinaria->latitud ? 12 : 6 }};

    var map = L.map('map').setView([initialLat, initialLng], initialZoom);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: 'OpenStreetMap' }).addTo(map);

    var marker;
    @if (isset($maquinaria) && $maquinaria->latitud && $maquinaria->longitud)
        marker = L.marker([initialLat, initialLng]).addTo(map);
    @endif

    map.on('click', function(e) {
        var lat = e.latlng.lat.toFixed(7);
        var lng = e.latlng.lng.toFixed(7);
        if (marker) { marker.setLatLng([lat, lng]); } else { marker = L.marker([lat, lng]).addTo(map); }

        document.getElementById('latitud').value = lat;
        document.getElementById('longitud').value = lng;
        document.getElementById('ubicacion').value = "Lat: " + lat + " - Lng: " + lng;
        obtenerInformacionGeografica(lat, lng);
    });

    function obtenerInformacionGeografica(lat, lng) {
        document.getElementById('info-ubicacion').style.display = 'block';
        document.getElementById('ciudad-texto').textContent = 'Cargando...';
        document.getElementById('direccion-texto').textContent = 'Cargando...';

        fetch('/api/geocodificacion?latitud=' + lat + '&longitud=' + lng)
            .then(response => response.json())
            .then(data => {
                if (data.success && data.data) {
                    var info = data.data;
                    document.getElementById('departamento').value = info.departamento || '';
                    document.getElementById('municipio').value = info.municipio || '';
                    document.getElementById('provincia').value = info.provincia || '';
                    document.getElementById('ciudad').value = info.ciudad || '';
                    document.getElementById('ciudad-texto').textContent = info.ciudad || info.municipio || 'No disponible';

                    var direccion = [];
                    if (info.municipio) direccion.push(info.municipio);
                    if (info.provincia) direccion.push('Provincia ' + info.provincia);
                    if (info.departamento) direccion.push(info.departamento);
                    direccion.push('Bolivia');

                    var direccionCompleta = direccion.join(', ');
                    document.getElementById('direccion-texto').textContent = direccionCompleta || 'No disponible';
                    if (direccionCompleta) { document.getElementById('ubicacion').value = direccionCompleta; }
                } else {
                    document.getElementById('ciudad-texto').textContent = 'No disponible';
                    document.getElementById('direccion-texto').textContent = 'No disponible';
                }
            })
            .catch(error => {
                console.error('Error al obtener info geográfica:', error);
                document.getElementById('ciudad-texto').textContent = 'Error';
                document.getElementById('direccion-texto').textContent = 'Error';
            });
    }
</script>
{{-- ========== LÓGICA GENÉRICA DEL AGRO-WIZARD ========== --}}
<script>
    // Le pasamos los errores de validación de Laravel a JavaScript
    window.laravelErrors = @json($errors->messages());
</script>
<script src="{{ asset('js/agro-wizard.js') }}"></script>


    });
