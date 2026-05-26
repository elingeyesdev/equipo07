@csrf

@php
    $wizardSteps = [
        [
            'icon' => 'fas fa-cow',
            'title' => 'Datos del animal',
            'description' => 'Identificación, raza, sexo y edad.',
        ],
        [
            'icon' => 'fas fa-tag',
            'title' => 'Producción y venta',
            'description' => 'Sanidad, peso, precio y stock.',
        ],
        [
            'icon' => 'fas fa-map-marker-alt',
            'title' => 'Ubicación',
            'description' => 'Dónde se encuentra el ganado.',
        ],
        [
            'icon' => 'fas fa-images',
            'title' => 'Imágenes',
            'description' => 'Fotografías del animal.',
        ],
    ];
@endphp

<!-- CARGAR CSS GENÉRICO DEL WIZARD -->
<link rel="stylesheet" href="{{ asset('css/wizard-form.css') }}">

<div class="agro-wizard" data-agro-wizard>
    <div class="agro-wizard__shell">
    <div class="agro-wizard__hero">
        <div>
            <span class="agro-wizard__eyebrow">Registro de Ganado</span>
            <h3 class="agro-wizard__title mb-1">
                <i class="fas fa-cow mr-2"></i>{{ isset($ganado) ? 'Editar ganado' : 'Nuevo ganado' }}
            </h3>
            <p class="agro-wizard__subtitle mb-0">
                Completa la información paso a paso. Los datos se conservan al avanzar o retroceder.
            </p>
        </div>
        <span class="badge badge-success agro-wizard__badge" data-wizard-current-label>
            Paso 1 de {{ count($wizardSteps) }}
        </span>
    </div>

    <div class="agro-wizard__progress" role="tablist">
        @foreach ($wizardSteps as $index => $step)
            <button type="button" class="agro-wizard__step-indicator {{ $index === 0 ? 'is-active' : '' }}"
                data-wizard-go-to="{{ $index }}" aria-current="{{ $index === 0 ? 'step' : 'false' }}">
                <span class="agro-wizard__step-number">{{ $index + 1 }}</span>
                <span class="agro-wizard__step-icon"><i class="{{ $step['icon'] }}"></i></span>
                <span class="agro-wizard__step-copy">
                    <span class="agro-wizard__step-title">{{ $step['title'] }}</span>
                    <span class="agro-wizard__step-description">{{ $step['description'] }}</span>
                    <span class="agro-wizard__step-status" data-wizard-step-status>Pendiente</span>
                </span>
            </button>
        @endforeach
    </div>

    <div class="agro-wizard__progressbar" aria-hidden="true">
        <span data-wizard-progressbar style="width: {{ 100 / count($wizardSteps) }}%;"></span>
    </div>

    <div class="alert alert-danger agro-wizard__error-summary d-none" data-wizard-error-summary></div>

    <div class="agro-wizard__content">

    {{-- ========== PASO 1: DATOS DEL ANIMAL ========== --}}
    <section class="card card-outline card-success shadow-sm mb-4 agro-wizard-step is-active" data-wizard-step="0">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <h3 class="card-title mb-0"><i class="fas fa-info-circle mr-2"></i> Información Básica</h3>
            </div>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Nombre *</label>
                        <input type="text" name="nombre" class="form-control" value="{{ old('nombre', $ganado->nombre ?? '') }}" required>
                    </div>
                    <div class="form-group">
                        <label>Tipo de Animal *</label>
                        <select name="tipo_animal_id" id="tipo_animal_id" class="form-control" required>
                            <option value="">Seleccione...</option>
                            @foreach ($tipo_animals as $item)
                                <option value="{{ $item->id }}" {{ old('tipo_animal_id', $ganado->tipo_animal_id ?? '') == $item->id ? 'selected' : '' }}>
                                    {{ $item->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Raza</label>
                        <select name="raza_id" id="raza_id" class="form-control" {{ !isset($ganado) && !old('tipo_animal_id') ? 'disabled' : '' }}>
                            <option value="">Seleccione un tipo de animal primero</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label>Sexo</label>
                        <select name="sexo" id="sexo" class="form-control">
                            <option value="">Seleccione</option>
                            <option value="Macho" {{ old('sexo', $ganado->sexo ?? '') == 'Macho' ? 'selected' : '' }}>Macho</option>
                            <option value="Hembra" {{ old('sexo', $ganado->sexo ?? '') == 'Hembra' ? 'selected' : '' }}>Hembra</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>Edad</label>
                        <div class="row">
                            <div class="col-4">
                                <label class="small text-muted mb-1">Años</label>
                                <div class="input-group">
                                    <div class="input-group-prepend"><button class="btn btn-outline-secondary btn-sm" type="button" onclick="decrementValue('edad_anos')"><i class="fas fa-minus"></i></button></div>
                                    <input type="number" name="edad_anos" id="edad_anos" class="form-control text-center" value="{{ old('edad_anos', $ganado->edad_anos ?? 0) }}" min="0" max="25" required>
                                    <div class="input-group-append"><button class="btn btn-outline-secondary btn-sm" type="button" onclick="incrementValue('edad_anos', 25)"><i class="fas fa-plus"></i></button></div>
                                </div>
                            </div>
                            <div class="col-4">
                                <label class="small text-muted mb-1">Meses</label>
                                <div class="input-group">
                                    <div class="input-group-prepend"><button class="btn btn-outline-secondary btn-sm" type="button" onclick="decrementValue('edad_meses')"><i class="fas fa-minus"></i></button></div>
                                    <input type="number" name="edad_meses" id="edad_meses" class="form-control text-center" value="{{ old('edad_meses', $ganado->edad_meses ?? 0) }}" min="0" max="11" required>
                                    <div class="input-group-append"><button class="btn btn-outline-secondary btn-sm" type="button" onclick="incrementValue('edad_meses', 11)"><i class="fas fa-plus"></i></button></div>
                                </div>
                            </div>
                            <div class="col-4">
                                <label class="small text-muted mb-1">Días</label>
                                <div class="input-group">
                                    <div class="input-group-prepend"><button class="btn btn-outline-secondary btn-sm" type="button" onclick="decrementValue('edad_dias')"><i class="fas fa-minus"></i></button></div>
                                    <input type="number" name="edad_dias" id="edad_dias" class="form-control text-center" value="{{ old('edad_dias', $ganado->edad_dias ?? 0) }}" min="0" max="30" required>
                                    <div class="input-group-append"><button class="btn btn-outline-secondary btn-sm" type="button" onclick="incrementValue('edad_dias', 30)"><i class="fas fa-plus"></i></button></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Categoría *</label>
                        <select name="categoria_id" class="form-control" required>
                            <option value="">Seleccione una categoría</option>
                            @foreach ($categorias as $categoria)
                                <option value="{{ $categoria->id }}" {{ old('categoria_id', $ganado->categoria_id ?? '') == $categoria->id ? 'selected' : '' }}>
                                    {{ $categoria->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ========== PASO 2: PRODUCCIÓN Y VENTA ========== --}}
    <section class="card card-outline card-success shadow-sm mb-4 agro-wizard-step" data-wizard-step="1">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div><h3 class="card-title mb-0"><i class="fas fa-chart-line mr-2"></i> Información Comercial</h3></div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Método de Venta / Tipo de Peso *</label>
                        <select name="tipo_peso_id" class="form-control" required>
                            <option value="">Seleccione...</option>
                            @foreach ($tipoPesos as $peso)
                                <option value="{{ $peso->id }}" {{ old('tipo_peso_id', $ganado->tipo_peso_id ?? '') == $peso->id ? 'selected' : '' }}>
                                    {{ $peso->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>Peso Actual (kg)</label>
                        <div class="input-group">
                            <input type="number" name="peso_actual" class="form-control" step="0.01" min="0" value="{{ old('peso_actual', $ganado->peso_actual ?? '') }}" placeholder="Ej: 250.50">
                            <div class="input-group-append"><span class="input-group-text">kg</span></div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Precio (Bs)</label>
                        <div class="input-group">
                            <div class="input-group-prepend"><span class="input-group-text">Bs</span></div>
                            <input type="number" name="precio" class="form-control" step="0.01" min="0" value="{{ old('precio', $ganado->precio ?? '') }}" placeholder="0.00">
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Stock (Cantidad) *</label>
                        <input type="number" name="stock" class="form-control" min="0" value="{{ old('stock', $ganado->stock ?? 0) }}" required>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group" id="cantidad_leche_group" style="display: none;">
                        <label>Cantidad de Leche por Día</label>
                        <div class="input-group">
                            <input type="number" name="cantidad_leche_dia" id="cantidad_leche_dia" class="form-control" step="0.01" min="0" value="{{ old('cantidad_leche_dia', $ganado->cantidad_leche_dia ?? '') }}" placeholder="Ej: 15.5">
                            <div class="input-group-append"><span class="input-group-text">litros/día</span></div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Datos Sanitarios</label>
                        <select name="dato_sanitario_id" class="form-control">
                            <option value="">Sin registro sanitario</option>
                            @foreach ($datosSanitarios as $ds)
                                <option value="{{ $ds->id }}" {{ old('dato_sanitario_id', $ganado->dato_sanitario_id ?? '') == $ds->id ? 'selected' : '' }}>
                                    {{ $ds->vacuna ?? 'Sin vacuna' }} - {{ $ds->fecha_aplicacion }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Descripción del Animal</label>
                        <textarea name="descripcion" class="form-control" rows="4" placeholder="Describa las características del animal...">{{ old('descripcion', $ganado->descripcion ?? '') }}</textarea>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ========== PASO 3: UBICACION ========== --}}
    <section class="card card-outline card-success shadow-sm mb-4 agro-wizard-step" data-wizard-step="2">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div><h3 class="card-title mb-0"><i class="fas fa-map-marker-alt mr-2"></i> Ubicación</h3></div>
        </div>
        <div class="card-body">
            <div class="form-group mb-3">
                <label>Seleccione la ubicación en el mapa</label>
                <div id="map" class="agro-wizard__map"></div>

                <input type="hidden" name="latitud" id="latitud" value="{{ old('latitud', $ganado->latitud ?? '') }}">
                <input type="hidden" name="longitud" id="longitud" value="{{ old('longitud', $ganado->longitud ?? '') }}">
                <input type="hidden" name="departamento" id="departamento" value="{{ old('departamento', $ganado->departamento ?? '') }}">
                <input type="hidden" name="municipio" id="municipio" value="{{ old('municipio', $ganado->municipio ?? '') }}">
                <input type="hidden" name="provincia" id="provincia" value="{{ old('provincia', $ganado->provincia ?? '') }}">
                <input type="hidden" name="ciudad" id="ciudad" value="{{ old('ciudad', $ganado->ciudad ?? '') }}">

                <input type="text" id="ubicacion" name="ubicacion" class="form-control mt-2" value="{{ old('ubicacion', $ganado->ubicacion ?? '') }}" readonly>
            </div>

            <div id="info-ubicacion" class="agro-wizard__location-detail mt-2" style="display: {{ isset($ganado) && ($ganado->ciudad || $ganado->municipio) ? 'block' : 'none' }};">
                <h6 class="mb-3 text-muted text-uppercase"><i class="fas fa-map mr-1"></i> Detalle de ubicación</h6>
                <div class="row mb-2">
                    <div class="col-md-3"><strong>Ciudad:</strong></div>
                    <div class="col-md-9" id="ciudad-texto">{{ isset($ganado) ? $ganado->ciudad ?? ($ganado->municipio ?? '-') : '-' }}</div>
                </div>
                <div class="row">
                    <div class="col-md-3"><strong>Dirección:</strong></div>
                    <div class="col-md-9" id="direccion-texto">
                        @if (isset($ganado) && ($ganado->municipio || $ganado->provincia || $ganado->departamento))
                            @php
                                $direccion = [];
                                if ($ganado->municipio) $direccion[] = $ganado->municipio;
                                if ($ganado->provincia) $direccion[] = 'Provincia ' . $ganado->provincia;
                                if ($ganado->departamento) $direccion[] = $ganado->departamento;
                                $direccion[] = 'Bolivia';
                            @endphp
                            {{ implode(', ', $direccion) }}
                        @else - @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ========== PASO 4: IMAGENES ========== --}}
    <section class="card card-outline card-success shadow-sm mb-4 agro-wizard-step" data-wizard-step="3">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div><h3 class="card-title mb-0"><i class="fas fa-images mr-2"></i> Imágenes del Animal</h3></div>
        </div>
        <div class="card-body">
            <div class="form-group mb-0">
                <label class="mb-2 d-block">Imágenes</label>

                @if (isset($ganado) && $ganado->imagenes->count() > 0)
                    <div class="mb-3">
                        <p class="text-muted mb-2">Imágenes actuales:</p>
                        <div class="row" id="imagenes-actuales" data-count="{{ $ganado->imagenes->count() }}">
                            @foreach ($ganado->imagenes as $imagen)
                                <div class="col-md-3 mb-3 imagen-item" data-imagen-id="{{ $imagen->id }}">
                                    <div class="position-relative">
                                        <img src="{{ asset('storage/' . $imagen->ruta) }}" class="img-thumbnail" style="width: 100%; height: 150px; object-fit: cover; border-radius: 8px;">
                                        <button type="button" class="btn btn-sm btn-danger position-absolute eliminar-imagen" data-imagen-id="{{ $imagen->id }}"><i class="fas fa-times"></i></button>
                                    </div>
                                    <input type="hidden" name="imagenes_eliminar[]" value="" class="imagen-eliminar-input">
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <label for="imagenes-input" class="agro-upload-zone" data-upload-zone>
                    <span class="agro-upload-zone__icon"><i class="fas fa-cloud-upload-alt"></i></span>
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
        <a href="{{ route('ganados.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left mr-1"></i> Volver
        </a>
        <div class="agro-wizard__action-group">
            <button type="button" class="btn btn-outline-agro" data-wizard-prev disabled><i class="fas fa-chevron-left mr-1"></i> Anterior</button>
            <button type="button" class="btn btn-success" data-wizard-next>Siguiente <i class="fas fa-chevron-right ml-1"></i></button>
            <button type="submit" class="btn btn-success d-none" data-wizard-submit><i class="fas fa-save mr-1"></i> Guardar</button>
        </div>
    </div>
    </div>
</div>

{{-- ========== LEAFLET ========== --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    var initialLat = {{ old('latitud', $ganado->latitud ?? -17.7833) }};
    var initialLng = {{ old('longitud', $ganado->longitud ?? -63.1821) }};
    var initialZoom = {{ isset($ganado) && $ganado->latitud ? 12 : 6 }};

    var map = L.map('map').setView([initialLat, initialLng], initialZoom);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: 'OpenStreetMap' }).addTo(map);

    var marker;
    @if (isset($ganado) && $ganado->latitud && $ganado->longitud)
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
        fetch('/api/geocodificacion?latitud=' + lat + '&longitud=' + lng)
            .then(r => r.json())
            .then(data => {
                if (data.success && data.data) {
                    var info = data.data;
                    document.getElementById('departamento').value = info.departamento || '';
                    document.getElementById('municipio').value = info.municipio || '';
                    document.getElementById('provincia').value = info.provincia || '';
                    document.getElementById('ciudad').value = info.ciudad || '';
                    document.getElementById('ciudad-texto').textContent = info.ciudad || info.municipio || 'No disponible';

                    var dir = [];
                    if (info.municipio) dir.push(info.municipio);
                    if (info.provincia) dir.push('Provincia ' + info.provincia);
                    if (info.departamento) dir.push(info.departamento);
                    dir.push('Bolivia');
                    var direccionCompleta = dir.join(', ');
                    document.getElementById('direccion-texto').textContent = direccionCompleta || 'No disponible';
                    if (direccionCompleta) document.getElementById('ubicacion').value = direccionCompleta;
                }
            });
    }
</script>

{{-- ========== LÓGICA PERSONALIZADA DE GANADOS ========== --}}
<script>
    // 1. Botones de Edad (+ y -)
    function incrementValue(fieldId, max) {
        const field = document.getElementById(fieldId);
        let value = parseInt(field.value) || 0;
        if (value < max) { field.value = value + 1; }
    }
    function decrementValue(fieldId) {
        const field = document.getElementById(fieldId);
        let value = parseInt(field.value) || 0;
        if (value > 0) { field.value = value - 1; }
    }

    // 2. Lógica de Razas por Tipo de Animal
    document.addEventListener('DOMContentLoaded', function() {
        const tipoAnimalSelect = document.getElementById('tipo_animal_id');
        const razaSelect = document.getElementById('raza_id');
        const razas = @json($razas ?? []);
        const razaGuardada = '{{ old('raza_id', $ganado->raza_id ?? '') }}';

        function filtrarRazas() {
            const tipoID = tipoAnimalSelect.value;
            razaSelect.innerHTML = '';

            if (!tipoID) {
                razaSelect.disabled = true;
                razaSelect.innerHTML = '<option value="">Seleccione un tipo de animal primero</option>';
                return;
            }

            const filtradas = razas.filter(r => r.tipo_animal_id == tipoID);
            if (filtradas.length > 0) {
                razaSelect.disabled = false;
                razaSelect.innerHTML = '<option value="">Seleccione una raza...</option>';
                filtradas.forEach(r => {
                    const isSelected = (r.id == razaGuardada) ? 'selected' : '';
                    razaSelect.innerHTML += `<option value="${r.id}" ${isSelected}>${r.nombre}</option>`;
                });
            } else {
                razaSelect.disabled = true;
                razaSelect.innerHTML = '<option value="">No hay razas registradas</option>';
            }
        }

        tipoAnimalSelect.addEventListener('change', filtrarRazas);
        // Disparar en carga por si es Edición o si hubo error de validación
        if(tipoAnimalSelect.value) filtrarRazas();
    });

    // 3. Mostrar/Ocultar Cantidad de Leche
    document.addEventListener('DOMContentLoaded', function() {
        const sexoSelect = document.getElementById('sexo');
        const cantidadLecheGroup = document.getElementById('cantidad_leche_group');

        function toggleCantidadLeche() {
            if (sexoSelect.value === 'Hembra') {
                cantidadLecheGroup.style.display = 'block';
            } else {
                cantidadLecheGroup.style.display = 'none';
                document.getElementById('cantidad_leche_dia').value = '';
            }
        }
        sexoSelect.addEventListener('change', toggleCantidadLeche);
        toggleCantidadLeche();
    });
</script>

{{-- ========== LÓGICA GENÉRICA DEL AGRO-WIZARD ========== --}}
<script>
    window.laravelErrors = @json($errors->messages());
</script>
<script src="{{ asset('js/agro-wizard.js') }}"></script>