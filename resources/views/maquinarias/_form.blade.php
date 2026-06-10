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

<div class="maquinaria-wizard" data-maquinaria-wizard>
    <div class="maquinaria-wizard__shell">
    <div class="maquinaria-wizard__hero">
        <div>
            <span class="maquinaria-wizard__eyebrow">Registro de maquinaria agrícola</span>
            <h3 class="maquinaria-wizard__title mb-1">
                <i class="fas fa-tractor mr-2"></i>{{ isset($maquinaria) ? 'Editar maquinaria' : 'Nueva maquinaria' }}
            </h3>
            <p class="maquinaria-wizard__subtitle mb-0">
                Completa la información paso a paso. Los datos se conservan al avanzar o retroceder.
            </p>
        </div>
        <span class="badge badge-success maquinaria-wizard__badge" data-wizard-current-label>
            Paso 1 de {{ count($wizardSteps) }}
        </span>
    </div>

    <div class="maquinaria-wizard__progress" role="tablist" aria-label="Pasos del registro de maquinaria">
        @foreach ($wizardSteps as $index => $step)
            <button type="button" class="maquinaria-wizard__step-indicator {{ $index === 0 ? 'is-active' : '' }}"
                data-wizard-go-to="{{ $index }}" aria-current="{{ $index === 0 ? 'step' : 'false' }}">
                <span class="maquinaria-wizard__step-number">{{ $index + 1 }}</span>
                <span class="maquinaria-wizard__step-icon">
                    <i class="{{ $step['icon'] }}"></i>
                </span>
                <span class="maquinaria-wizard__step-copy">
                    <span class="maquinaria-wizard__step-title">{{ $step['title'] }}</span>
                    <span class="maquinaria-wizard__step-description">{{ $step['description'] }}</span>
                    <span class="maquinaria-wizard__step-status" data-wizard-step-status>Pendiente</span>
                </span>
            </button>
        @endforeach
    </div>

    <div class="maquinaria-wizard__progressbar" aria-hidden="true">
        <span data-wizard-progressbar style="width: {{ 100 / max(count($wizardSteps), 1) }}%;"></span>
    </div>

    <div class="alert alert-danger maquinaria-wizard__error-summary d-none" data-wizard-error-summary></div>

    <div class="maquinaria-wizard__content">

    {{-- ========== PASO 1: DATOS BASICOS ========== --}}
    <section class="card card-outline card-success shadow-sm mb-4 maquinaria-wizard-step is-active" data-wizard-step="0">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <h3 class="card-title mb-0">
                    <i class="fas fa-tractor mr-2"></i> Datos de la maquinaria
                </h3>
                <small class="text-muted">Identificación, tipo, marca y modelo.</small>
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

                    <input type="hidden" name="categoria_id"
                        value="{{ old('categoria_id', $maquinaria->categoria_id ?? ($categoriaMaquinaria->id ?? '')) }}">

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
    <section class="card card-outline card-success shadow-sm mb-4 maquinaria-wizard-step" data-wizard-step="1">
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
                        <label class="mb-1">Tarifa por hora *</label>
                        <input type="hidden" name="tarifa_unidad" value="hora">
                        <div class="input-group maquinaria-hourly-rate">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Bs/hora</span>
                            </div>
                            <input type="number" step="0.01" name="precio_dia"
                                class="form-control @error('precio_dia') is-invalid @enderror" placeholder="0.00"
                                value="{{ old('precio_dia', $maquinaria->precio_dia ?? 0) }}" min="0" required
                                data-hourly-price>
                        </div>
                        <small class="form-text text-muted">
                            Ingresa el costo de una hora de alquiler. El estimado diario se calcula abajo.
                        </small>

                        <div class="maquinaria-price-info mt-3" data-price-day-info>
                            <div class="maquinaria-price-info__icon">
                                <i class="fas fa-calculator"></i>
                            </div>
                            <div class="maquinaria-price-info__body">
                                <div class="maquinaria-price-info__header">
                                    <span>Estimación por día</span>
                                    <strong data-day-price-result>Bs 0.00</strong>
                                </div>
                                <div class="maquinaria-price-info__controls">
                                    <label for="horas-dia-estimadas" class="mb-0">Horas de trabajo al día</label>
                                    <input type="number" id="horas-dia-estimadas" class="form-control"
                                        min="1" max="24" step="1" value="8" data-hours-per-day>
                                </div>
                                <small data-day-price-detail>
                                    Calculado con Bs 0.00 por hora durante 8 horas.
                                </small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="mb-1">Estado *</label>
                        @if (isset($maquinaria))
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
                        @else
                            <input type="hidden" name="estado_maquinaria_id"
                                value="{{ old('estado_maquinaria_id', $estadoDisponible->id ?? '') }}">
                            <select class="form-control @error('estado_maquinaria_id') is-invalid @enderror" disabled>
                                <option selected>{{ $estadoDisponible ? ucfirst(str_replace('_', ' ', $estadoDisponible->nombre)) : 'Disponible' }}</option>
                            </select>
                        @endif
                    </div>

                    <div class="form-group mb-0">
                        <label class="mb-1">Descripción</label>
                        <textarea name="descripcion" class="form-control maquinaria-rental-description @error('descripcion') is-invalid @enderror" rows="6" style="resize: none;"
                            placeholder="Condiciones de uso, características técnicas, recomendaciones, etc.">{{ old('descripcion', $maquinaria->descripcion ?? '') }}</textarea>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ========== PASO 3: UBICACION ========== --}}
    <section class="card card-outline card-success shadow-sm mb-4 maquinaria-wizard-step" data-wizard-step="2">
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
                <div class="maquinaria-location-search mb-2">
                    <div class="maquinaria-location-search__bar">
                        <span class="maquinaria-location-search__icon">
                            <i class="fas fa-search"></i>
                        </span>
                        <input type="search" id="buscar-ubicacion" class="form-control"
                            placeholder="Buscar dirección, zona o ciudad">
                        <button type="button" class="maquinaria-location-search__submit" id="btn-buscar-ubicacion">
                            Buscar
                        </button>
                    </div>
                    <button type="button" class="maquinaria-location-search__current" id="btn-ubicacion-actual">
                        <i class="fas fa-location-arrow mr-1"></i> Usar mi ubicación actual
                    </button>
                    <div id="sugerencias-ubicacion" class="maquinaria-location-suggestions" style="display: none;"></div>
                </div>
                <div class="maquinaria-map-shell">
                    <button type="button" class="maquinaria-map-expand" id="btn-expandir-mapa">
                        <i class="fas fa-expand-alt mr-1"></i> Ver mapa grande
                    </button>
                    <div id="map" class="maquinaria-wizard__map"
                        style="height: 400px; border-radius: 8px; border: 1px solid #e0e0e0;">
                    </div>
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

                <input type="text" id="ubicacion" name="ubicacion"
                    class="form-control mt-2 @error('ubicacion') is-invalid @enderror"
                    value="{{ old('ubicacion', $maquinaria->ubicacion ?? '') }}" readonly>
                <button type="button" class="btn btn-success btn-sm mt-2" id="btn-confirmar-ubicacion">
                    <i class="fas fa-check mr-1"></i> Confirmar ubicación
                </button>
                <small id="ubicacion-confirmada" class="form-text text-success" style="display: none;">
                    Ubicación confirmada.
                </small>
            </div>

            <div id="info-ubicacion" class="maquinaria-wizard__location-detail mt-2"
                style="display: {{ isset($maquinaria) && ($maquinaria->ciudad || $maquinaria->municipio) ? 'block' : 'none' }};">
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
    </section>

    {{-- ========== PASO 4: IMAGENES ========== --}}
    <section class="card card-outline card-success shadow-sm mb-4 maquinaria-wizard-step" data-wizard-step="3">
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
                        <div class="row" id="imagenes-actuales">
                            @foreach ($maquinaria->imagenes as $imagen)
                                <div class="col-md-3 mb-3 imagen-item" data-imagen-id="{{ $imagen->id }}">
                                    <div class="mb-2">
                                        <img src="{{ asset('storage/' . $imagen->ruta) }}"
                                            alt="Imagen {{ $loop->iteration }}" class="img-thumbnail"
                                            style="width: 100%; height: 150px; object-fit: cover; border-radius: 8px;">
                                    </div>
                                    <div class="btn-group btn-group-sm d-flex" role="group">
                                        <button type="button" class="btn btn-outline-danger eliminar-imagen"
                                            data-imagen-id="{{ $imagen->id }}">
                                            <i class="fas fa-trash mr-1"></i> Eliminar
                                        </button>
                                        <label class="btn btn-outline-success mb-0">
                                            <input type="radio" name="imagen_portada" value="existing:{{ $imagen->id }}"
                                                {{ old('imagen_portada', $loop->first ? 'existing:' . $imagen->id : '') === 'existing:' . $imagen->id ? 'checked' : '' }}>
                                            Portada
                                        </label>
                                    </div>
                                    <input type="hidden" name="imagenes_eliminar[]" value=""
                                        class="imagen-eliminar-input">
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <label for="imagenes-input"
                    class="maquinaria-upload-zone @error('imagenes') is-invalid @enderror @error('imagenes.*') is-invalid @enderror"
                    data-upload-zone>
                    <span class="maquinaria-upload-zone__icon">
                        <i class="fas fa-cloud-upload-alt"></i>
                    </span>
                    <span class="maquinaria-upload-zone__content">
                        <strong>Haz clic para subir imágenes</strong>
                        <small>También puedes arrastrar tus archivos aquí. JPG, PNG o GIF, máximo 2MB por imagen.</small>
                    </span>
                    <span class="maquinaria-upload-zone__cta">Seleccionar archivos</span>
                </label>

                <input type="file" name="imagenes[]" class="maquinaria-upload-input" accept="image/*" multiple
                    id="imagenes-input">

                <div id="imagenes-count" class="maquinaria-upload-count mt-3"></div>
                <div id="preview-container" class="row mt-3 mb-0"></div>
            </div>
        </div>
    </section>

    </div>

    {{-- BOTONES --}}
    <div class="maquinaria-wizard__actions">
        <a href="{{ url()->previous() !== url()->current() ? url()->previous() : route('maquinarias.index') }}"
            class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left mr-1"></i> Volver
        </a>
        <div class="maquinaria-wizard__action-group">
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

<div class="modal fade maquinaria-map-modal" id="maquinariaMapModal" tabindex="-1" role="dialog"
    aria-labelledby="maquinariaMapModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h5 class="modal-title" id="maquinariaMapModalLabel">
                        <i class="fas fa-map-marker-alt mr-2"></i>Seleccionar ubicación
                    </h5>
                    <small>Haz clic sobre el mapa para marcar el punto exacto de la maquinaria.</small>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-0">
                <div id="map-maquinaria-modal" class="maquinaria-map-modal__map"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-success" data-dismiss="modal">
                    <i class="fas fa-check mr-1"></i> Usar esta ubicación
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade maquinaria-publication-preview" id="maquinariaPublicationPreviewModal" tabindex="-1"
    role="dialog" aria-labelledby="maquinariaPublicationPreviewLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <span class="maquinaria-publication-preview__eyebrow">Vista previa de publicación</span>
                    <h5 class="modal-title" id="maquinariaPublicationPreviewLabel">
                        Revisa cómo se verá tu maquinaria
                    </h5>
                    <small>Confirma si los datos están correctos o vuelve para editarlos antes de publicar.</small>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="maquinaria-publication-preview__layout">
                    <div class="maquinaria-publication-preview__media" data-publication-preview-media>
                        <img src="" alt="Vista previa de maquinaria" data-publication-preview-image>
                        <div class="maquinaria-publication-preview__empty" data-publication-preview-empty>
                            <i class="fas fa-image"></i>
                            <span>Sin imagen seleccionada</span>
                        </div>
                    </div>
                    <div class="maquinaria-publication-preview__info">
                        <h3 data-publication-preview-name>Nombre de la maquinaria</h3>
                        <div class="maquinaria-publication-preview__badges">
                            <span data-publication-preview-type>Tipo</span>
                            <span data-publication-preview-brand>Marca</span>
                            <span data-publication-preview-status>Estado</span>
                        </div>
                        <div class="maquinaria-publication-preview__price">
                            <small>Tarifa</small>
                            <strong data-publication-preview-price>Bs 0.00/hora</strong>
                        </div>
                        <dl class="maquinaria-publication-preview__details">
                            <div>
                                <dt>Modelo</dt>
                                <dd data-publication-preview-model>-</dd>
                            </div>
                            <div>
                                <dt>Categoría</dt>
                                <dd data-publication-preview-category>-</dd>
                            </div>
                            <div>
                                <dt>Teléfono</dt>
                                <dd data-publication-preview-phone>-</dd>
                            </div>
                            <div>
                                <dt>Estimado diario</dt>
                                <dd data-publication-preview-day-price>-</dd>
                            </div>
                            <div>
                                <dt>Ubicación</dt>
                                <dd data-publication-preview-location>-</dd>
                            </div>
                            <div>
                                <dt>Ciudad / Municipio</dt>
                                <dd data-publication-preview-city>-</dd>
                            </div>
                            <div>
                                <dt>Provincia</dt>
                                <dd data-publication-preview-province>-</dd>
                            </div>
                            <div>
                                <dt>Departamento</dt>
                                <dd data-publication-preview-department>-</dd>
                            </div>
                            <div>
                                <dt>Imágenes</dt>
                                <dd data-publication-preview-images-count>-</dd>
                            </div>
                        </dl>
                        <div class="maquinaria-publication-preview__description">
                            <span>Descripción</span>
                            <p data-publication-preview-description>Sin descripción.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">
                    <i class="fas fa-edit mr-1"></i> Editar datos
                </button>
                <button type="button" class="btn btn-success" data-publication-preview-confirm>
                    <i class="fas fa-check mr-1"></i> Confirmar publicación
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

    // Crear el mapa
    var map = L.map('map').setView([initialLat, initialLng], initialZoom);

    // Capa gratuita de OpenStreetMap
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: 'OpenStreetMap'
    }).addTo(map);

    var marker;
    var modalMap;
    var modalMarker;
    var locationSearchTimer = null;
    var lastLocationQuery = '';
    var locationSearchAbort = null;
    var locationSearchCache = {};

    // Si hay coordenadas existentes, mostrar el marcador
    @if (isset($maquinaria) && $maquinaria->latitud && $maquinaria->longitud)
        marker = L.marker([initialLat, initialLng]).addTo(map);
    @endif

    function syncModalMarker(lat, lng) {
        if (!modalMap) return;

        if (modalMarker) {
            modalMarker.setLatLng([lat, lng]);
        } else {
            modalMarker = L.marker([lat, lng]).addTo(modalMap);
        }
    }

    function setMapLocation(lat, lng, label, zoom) {
        if (marker) {
            marker.setLatLng([lat, lng]);
        } else {
            marker = L.marker([lat, lng]).addTo(map);
        }

        document.getElementById('latitud').value = lat;
        document.getElementById('longitud').value = lng;
        document.getElementById('ubicacion').value = label || ("Lat: " + lat + " - Lng: " + lng);
        document.getElementById('ubicacion-confirmada').style.display = 'none';

        if (zoom) {
            map.setView([lat, lng], zoom);
        }

        syncModalMarker(lat, lng);
        obtenerInformacionGeografica(lat, lng);
    }

    // Evento click en mapa
    map.on('click', function(e) {
        var lat = e.latlng.lat.toFixed(7);
        var lng = e.latlng.lng.toFixed(7);

        setMapLocation(lat, lng, null);
    });

    function renderLocationSuggestions(results) {
        var container = document.getElementById('sugerencias-ubicacion');
        container.innerHTML = '';

        if (!results.length) {
            container.style.display = 'none';
            return;
        }

        results.forEach(function(result) {
            var option = document.createElement('button');
            var pin = document.createElement('span');
            var copy = document.createElement('span');
            var title = document.createElement('strong');
            var detail = document.createElement('small');

            option.type = 'button';
            option.className = 'maquinaria-location-suggestions__item';
            pin.className = 'maquinaria-location-suggestions__pin';
            pin.innerHTML = '<i class="fas fa-map-marker-alt"></i>';
            copy.className = 'maquinaria-location-suggestions__copy';
            title.textContent = result.display_name.split(',')[0] || 'Ubicación encontrada';
            detail.textContent = result.display_name;
            copy.appendChild(title);
            copy.appendChild(detail);
            option.appendChild(pin);
            option.appendChild(copy);
            option.addEventListener('click', function() {
                var lat = Number(result.lat).toFixed(7);
                var lng = Number(result.lon).toFixed(7);
                container.style.display = 'none';
                document.getElementById('buscar-ubicacion').value = result.display_name;
                setMapLocation(lat, lng, result.display_name, 14);
            });
            container.appendChild(option);
        });

        container.style.display = 'block';
    }

    function buscarUbicacion(force) {
        var input = document.getElementById('buscar-ubicacion');
        var query = input.value.trim();
        var suggestions = document.getElementById('sugerencias-ubicacion');

        if (query.length < 3) {
            suggestions.style.display = 'none';
            return;
        }

        if (!force && query === lastLocationQuery) {
            return;
        }

        lastLocationQuery = query;

        if (locationSearchCache[query]) {
            renderLocationSuggestions(locationSearchCache[query]);
            return;
        }

        if (locationSearchAbort) {
            locationSearchAbort.abort();
        }

        locationSearchAbort = new AbortController();

        fetch('/api/geocodificacion/buscar?q=' + encodeURIComponent(query), {
                signal: locationSearchAbort.signal
            })
            .then(response => response.json())
            .then(data => {
                var results = data.success ? data.data : [];
                locationSearchCache[query] = results;
                renderLocationSuggestions(results);
            })
            .catch(error => {
                if (error.name !== 'AbortError') {
                    console.error('Error al buscar ubicación:', error);
                }
            });
    }

    document.getElementById('buscar-ubicacion').addEventListener('input', function() {
        clearTimeout(locationSearchTimer);
        locationSearchTimer = setTimeout(function() {
            buscarUbicacion(false);
        }, 700);
    });

    document.getElementById('buscar-ubicacion').addEventListener('keydown', function(event) {
        if (event.key === 'Enter') {
            event.preventDefault();
            clearTimeout(locationSearchTimer);
            buscarUbicacion(true);
        }
    });

    document.getElementById('btn-buscar-ubicacion').addEventListener('click', function() {
        clearTimeout(locationSearchTimer);
        buscarUbicacion(true);
    });

    document.getElementById('btn-ubicacion-actual').addEventListener('click', function() {
        if (!navigator.geolocation) {
            alert('Tu navegador no permite obtener la ubicación actual.');
            return;
        }

        navigator.geolocation.getCurrentPosition(function(position) {
            var lat = position.coords.latitude.toFixed(7);
            var lng = position.coords.longitude.toFixed(7);
            setMapLocation(lat, lng, null, 14);
        }, function() {
            alert('No se pudo obtener tu ubicación actual.');
        }, {
            enableHighAccuracy: true,
            timeout: 10000
        });
    });

    var expandMapButton = document.getElementById('btn-expandir-mapa');

    function ensureModalMap() {
        var selectedLat = parseFloat(document.getElementById('latitud').value) || map.getCenter().lat;
        var selectedLng = parseFloat(document.getElementById('longitud').value) || map.getCenter().lng;
        var selectedZoom = map.getZoom() || 14;

        if (!modalMap) {
            modalMap = L.map('map-maquinaria-modal').setView([selectedLat, selectedLng], selectedZoom);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: 'OpenStreetMap'
            }).addTo(modalMap);

            modalMap.on('click', function(e) {
                var lat = e.latlng.lat.toFixed(7);
                var lng = e.latlng.lng.toFixed(7);
                setMapLocation(lat, lng, null, 14);
                modalMap.setView([lat, lng], 14);
            });
        } else {
            modalMap.setView([selectedLat, selectedLng], selectedZoom);
        }

        if (document.getElementById('latitud').value && document.getElementById('longitud').value) {
            syncModalMarker(selectedLat, selectedLng);
        }

        setTimeout(function() {
            modalMap.invalidateSize();
        }, 220);
    }

    if (expandMapButton) {
        expandMapButton.addEventListener('click', function() {
            if (typeof $ !== 'undefined' && $.fn.modal) {
                $('#maquinariaMapModal').modal('show');
                setTimeout(ensureModalMap, 350);
            } else {
                document.getElementById('maquinariaMapModal').style.display = 'block';
                ensureModalMap();
            }
        });
    }

    if (typeof $ !== 'undefined' && $.fn.modal) {
        $('#maquinariaMapModal').on('shown.bs.modal', ensureModalMap);
    }

    document.getElementById('btn-confirmar-ubicacion').addEventListener('click', function() {
        if (!document.getElementById('latitud').value || !document.getElementById('longitud').value) {
            alert('Primero selecciona una ubicación en el mapa.');
            return;
        }

        document.getElementById('ubicacion-confirmada').style.display = 'block';
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

{{-- ========== JS DEL WIZARD E IMAGENES ========== --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const wizard = document.querySelector('[data-maquinaria-wizard]');
        if (!wizard) return;

        const form = wizard.closest('form');
        const steps = Array.from(wizard.querySelectorAll('[data-wizard-step]'));
        const indicators = Array.from(wizard.querySelectorAll('[data-wizard-go-to]'));
        const prevButton = wizard.querySelector('[data-wizard-prev]');
        const nextButton = wizard.querySelector('[data-wizard-next]');
        const submitButton = wizard.querySelector('[data-wizard-submit]');
        const errorSummary = wizard.querySelector('[data-wizard-error-summary]');
        const progressBar = wizard.querySelector('[data-wizard-progressbar]');
        const currentLabel = wizard.querySelector('[data-wizard-current-label]');
        const hourlyPriceInput = wizard.querySelector('[data-hourly-price]');
        const hoursPerDayInput = wizard.querySelector('[data-hours-per-day]');
        const dayPriceResult = wizard.querySelector('[data-day-price-result]');
        const dayPriceDetail = wizard.querySelector('[data-day-price-detail]');
        const serverErrors = @json($errors->messages());
        let currentStep = 0;
        let allowSubmit = false;

        form.setAttribute('novalidate', 'novalidate');

        const fieldStepMap = {
            nombre: 0,
            categoria_id: 0,
            tipo_maquinaria_id: 0,
            marca_maquinaria_id: 0,
            modelo: 0,
            telefono: 1,
            precio_dia: 1,
            tarifa_unidad: 1,
            estado_maquinaria_id: 1,
            descripcion: 1,
            ubicacion: 2,
            latitud: 2,
            longitud: 2,
            departamento: 2,
            municipio: 2,
            provincia: 2,
            ciudad: 2,
            imagenes: 3,
            imagenes_eliminar: 3,
            imagen_portada: 3,
        };

        function normalizeFieldName(name) {
            return name.replace(/\[\]$/, '').replace(/\.\d+$/, '');
        }

        function findControlByErrorKey(key) {
            const normalized = normalizeFieldName(key);
            return form.querySelector(`[name="${normalized}"]`) ||
                form.querySelector(`[name="${normalized}[]"]`);
        }

        function getStepForField(key) {
            const normalized = normalizeFieldName(key);
            return fieldStepMap[normalized] ?? 0;
        }

        function fieldLabel(control) {
            const group = control.closest('.form-group');
            const label = group ? group.querySelector('label') : null;
            return label ? label.textContent.replace('*', '').trim() : 'este campo';
        }

        function validationMessage(control) {
            if (control.validity.valueMissing) {
                return `Completa ${fieldLabel(control).toLowerCase()} para continuar.`;
            }

            if (control.validity.rangeUnderflow) {
                return `Ingresa un valor igual o mayor a ${control.min}.`;
            }

            if (control.validity.rangeOverflow) {
                return `Ingresa un valor igual o menor a ${control.max}.`;
            }

            if (control.validity.typeMismatch) {
                return 'Ingresa un valor con el formato correcto.';
            }

            return control.validationMessage || 'Revisa este campo antes de continuar.';
        }

        function feedbackElement(control) {
            const holder = control.closest('.input-group') || control;
            let feedback = holder.nextElementSibling;

            if (!feedback || !feedback.classList.contains('wizard-field-error')) {
                feedback = document.createElement('div');
                feedback.className = 'invalid-feedback wizard-field-error';
                holder.insertAdjacentElement('afterend', feedback);
            }

            return feedback;
        }

        function setFieldError(control, message) {
            control.classList.add('is-invalid');
            feedbackElement(control).textContent = message;
        }

        function clearFieldError(control) {
            control.classList.remove('is-invalid');
            const group = control.closest('.input-group') || control;
            const feedback = group.nextElementSibling;
            if (feedback && feedback.classList.contains('wizard-field-error')) {
                feedback.textContent = '';
            }
        }

        function bolivianoAmount(value) {
            return `Bs ${value.toLocaleString('es-BO', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            })}`;
        }

        function updateDayPriceEstimate() {
            if (!hourlyPriceInput || !hoursPerDayInput || !dayPriceResult || !dayPriceDetail) return;

            const hourlyPrice = Math.max(parseFloat(hourlyPriceInput.value) || 0, 0);
            const hoursPerDay = Math.min(Math.max(parseInt(hoursPerDayInput.value, 10) || 1, 1), 24);
            const dayPrice = hourlyPrice * hoursPerDay;

            if (String(hoursPerDayInput.value) !== String(hoursPerDay)) {
                hoursPerDayInput.value = hoursPerDay;
            }

            dayPriceResult.textContent = bolivianoAmount(dayPrice);
            dayPriceDetail.textContent =
                `Calculado con ${bolivianoAmount(hourlyPrice)} por hora durante ${hoursPerDay} ${hoursPerDay === 1 ? 'hora' : 'horas'}.`;
        }

        function validateStep(index, shouldFocus = true) {
            const controls = Array.from(steps[index].querySelectorAll('input, select, textarea'))
                .filter(control => !control.disabled && control.type !== 'hidden');
            const invalidControls = [];

            controls.forEach(control => {
                clearFieldError(control);

                if (!control.checkValidity()) {
                    invalidControls.push(control);
                    setFieldError(control, validationMessage(control));
                }
            });

            steps[index].classList.toggle('has-errors', invalidControls.length > 0);
            indicators[index].classList.toggle('has-errors', invalidControls.length > 0);

            if (invalidControls.length > 0) {
                errorSummary.textContent = 'Revisa los campos marcados antes de continuar.';
                errorSummary.classList.remove('d-none');

                if (shouldFocus) {
                    invalidControls[0].focus({
                        preventScroll: true
                    });
                    invalidControls[0].scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                }
            } else {
                errorSummary.classList.add('d-none');
            }

            return invalidControls.length === 0;
        }

        function validateUntil(targetStep) {
            for (let index = 0; index < targetStep; index++) {
                if (!validateStep(index, false)) {
                    showStep(index);
                    validateStep(index, true);
                    return false;
                }
            }

            return true;
        }

        function refreshMap() {
            if (typeof map !== 'undefined') {
                setTimeout(function() {
                    map.invalidateSize();
                }, 180);
            }
        }

        function showStep(index) {
            const previousStep = currentStep;
            currentStep = Math.max(0, Math.min(index, steps.length - 1));
            const direction = currentStep >= previousStep ? 'forward' : 'backward';

            steps.forEach((step, stepIndex) => {
                const isActive = stepIndex === currentStep;
                step.classList.toggle('is-active', isActive);
                step.classList.toggle('is-forward', isActive && direction === 'forward');
                step.classList.toggle('is-backward', isActive && direction === 'backward');
                step.style.display = isActive ? 'block' : 'none';
            });

            indicators.forEach((indicator, indicatorIndex) => {
                indicator.classList.toggle('is-active', indicatorIndex === currentStep);
                indicator.classList.toggle('is-complete', indicatorIndex < currentStep);
                indicator.setAttribute('aria-current', indicatorIndex === currentStep ? 'step' : 'false');

                const status = indicator.querySelector('[data-wizard-step-status]');
                if (status) {
                    if (indicatorIndex < currentStep) {
                        status.textContent = 'Completado';
                    } else if (indicatorIndex === currentStep) {
                        status.textContent = 'En progreso';
                    } else {
                        status.textContent = 'Pendiente';
                    }
                }
            });

            prevButton.disabled = currentStep === 0;
            nextButton.classList.toggle('d-none', currentStep === steps.length - 1);
            submitButton.classList.toggle('d-none', currentStep !== steps.length - 1);
            errorSummary.classList.add('d-none');

            if (progressBar) {
                progressBar.style.width = `${((currentStep + 1) / steps.length) * 100}%`;
            }

            if (currentLabel) {
                currentLabel.textContent = `Paso ${currentStep + 1} de ${steps.length}`;
            }

            if (steps[currentStep].querySelector('#map')) {
                refreshMap();
            }

            wizard.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }

        indicators.forEach(indicator => {
            indicator.addEventListener('click', function() {
                const targetStep = Number(this.getAttribute('data-wizard-go-to'));

                if (targetStep <= currentStep || validateUntil(targetStep)) {
                    showStep(targetStep);
                }
            });
        });

        prevButton.addEventListener('click', function() {
            showStep(currentStep - 1);
        });

        nextButton.addEventListener('click', function() {
            if (validateStep(currentStep)) {
                showStep(currentStep + 1);
            }
        });

        if (hourlyPriceInput && hoursPerDayInput) {
            hourlyPriceInput.addEventListener('input', updateDayPriceEstimate);
            hoursPerDayInput.addEventListener('input', updateDayPriceEstimate);
            updateDayPriceEstimate();
        }

        form.addEventListener('submit', function(event) {
            if (allowSubmit) {
                return;
            }

            event.preventDefault();

            for (let index = 0; index < steps.length; index++) {
                if (!validateStep(index, false)) {
                    showStep(index);
                    validateStep(index, true);
                    return;
                }
            }

            openPublicationPreview();
        });

        const publicationConfirmButton = document.querySelector('[data-publication-preview-confirm]');
        if (publicationConfirmButton) {
            publicationConfirmButton.addEventListener('click', function() {
                allowSubmit = true;
                publicationConfirmButton.disabled = true;
                publicationConfirmButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Guardando...';
                form.submit();
            });
        }

        Object.entries(serverErrors).forEach(([key, messages]) => {
            const control = findControlByErrorKey(key);
            if (!control) return;

            setFieldError(control, messages[0]);
            const stepIndex = getStepForField(key);
            steps[stepIndex].classList.add('has-errors');
            indicators[stepIndex].classList.add('has-errors');
        });

        const firstServerError = Object.keys(serverErrors)[0];
        if (firstServerError) {
            showStep(getStepForField(firstServerError));
            errorSummary.textContent = 'Hay datos por corregir antes de guardar la maquinaria.';
            errorSummary.classList.remove('d-none');
        } else {
            showStep(0);
        }

        const input = document.getElementById('imagenes-input');
        const uploadZone = wizard.querySelector('[data-upload-zone]');
        const previewContainer = document.getElementById('preview-container');
        const countDisplay = document.getElementById('imagenes-count');
        const imagenesActuales =
            {{ isset($maquinaria) && $maquinaria->imagenes ? $maquinaria->imagenes->count() : 0 }};
        let imagenesAEliminar = [];
        let selectedFiles = [];

        function fieldValue(name) {
            const control = form.querySelector(`[name="${name}"]`);
            return control ? control.value.trim() : '';
        }

        function selectedText(name, fallbackSelector = null) {
            const control = form.querySelector(`select[name="${name}"]`) ||
                (fallbackSelector ? form.querySelector(fallbackSelector) : null);

            if (!control) return '-';
            const option = control.options[control.selectedIndex];
            return option ? option.textContent.trim() : '-';
        }

        function setPreviewText(selector, value, fallback = '-') {
            const element = document.querySelector(selector);
            if (element) {
                element.textContent = value && String(value).trim() ? value : fallback;
            }
        }

        function coverImageUrl() {
            const checkedCover = form.querySelector('input[name="imagen_portada"]:checked');

            if (checkedCover && checkedCover.value.startsWith('new:')) {
                const index = Number(checkedCover.value.replace('new:', ''));
                return selectedFiles[index] ? selectedFiles[index].url : '';
            }

            if (checkedCover && checkedCover.value.startsWith('existing:')) {
                const imageId = checkedCover.value.replace('existing:', '');
                const item = form.querySelector(`.imagen-item[data-imagen-id="${imageId}"]`);
                if (item && !imagenesAEliminar.includes(imageId)) {
                    const image = item.querySelector('img');
                    return image ? image.src : '';
                }
            }

            if (selectedFiles.length > 0) {
                return selectedFiles[0].url;
            }

            const existingImage = Array.from(form.querySelectorAll('.imagen-item'))
                .find(item => !imagenesAEliminar.includes(item.getAttribute('data-imagen-id')));

            return existingImage ? existingImage.querySelector('img')?.src || '' : '';
        }

        function buildPublicationPreview() {
            const price = Math.max(parseFloat(fieldValue('precio_dia')) || 0, 0);
            const hoursPerDay = Math.min(Math.max(parseInt(hoursPerDayInput?.value, 10) || 1, 1), 24);
            const dayPrice = price * hoursPerDay;
            const imageUrl = coverImageUrl();
            const previewImage = document.querySelector('[data-publication-preview-image]');
            const previewEmpty = document.querySelector('[data-publication-preview-empty]');
            const visibleImagesCount = liveExistingImagesCount() + selectedFiles.length;
            const lat = fieldValue('latitud');
            const lng = fieldValue('longitud');

            setPreviewText('[data-publication-preview-name]', fieldValue('nombre'), 'Nombre de la maquinaria');
            setPreviewText('[data-publication-preview-type]', selectedText('tipo_maquinaria_id'), 'Tipo');
            setPreviewText('[data-publication-preview-brand]', selectedText('marca_maquinaria_id'), 'Marca');
            setPreviewText(
                '[data-publication-preview-status]',
                selectedText('estado_maquinaria_id', '[data-wizard-step="1"] select[disabled]'),
                'Disponible'
            );
            setPreviewText('[data-publication-preview-price]', `${bolivianoAmount(price)}/hora`, 'Bs 0.00/hora');
            setPreviewText('[data-publication-preview-model]', fieldValue('modelo'), 'Sin modelo');
            setPreviewText('[data-publication-preview-category]', 'Maquinaria agrícola', 'Maquinaria agrícola');
            setPreviewText('[data-publication-preview-phone]', fieldValue('telefono'), 'Sin teléfono');
            setPreviewText(
                '[data-publication-preview-day-price]',
                `${bolivianoAmount(dayPrice)} por ${hoursPerDay} ${hoursPerDay === 1 ? 'hora' : 'horas'}`,
                '-'
            );
            setPreviewText('[data-publication-preview-location]', fieldValue('ubicacion'), 'Sin ubicación seleccionada');
            setPreviewText('[data-publication-preview-city]', fieldValue('ciudad') || fieldValue('municipio'), 'Sin ciudad o municipio');
            setPreviewText('[data-publication-preview-province]', fieldValue('provincia'), 'Sin provincia');
            setPreviewText('[data-publication-preview-department]', fieldValue('departamento'), 'Sin departamento');
            setPreviewText(
                '[data-publication-preview-images-count]',
                `${visibleImagesCount} ${visibleImagesCount === 1 ? 'imagen' : 'imágenes'} cargadas`,
                'Sin imágenes'
            );
            setPreviewText('[data-publication-preview-description]', fieldValue('descripcion'), 'Sin descripción.');

            if (previewImage && previewEmpty) {
                previewImage.classList.toggle('d-none', !imageUrl);
                previewEmpty.classList.toggle('d-none', Boolean(imageUrl));
                if (imageUrl) {
                    previewImage.src = imageUrl;
                }
            }
        }

        function openPublicationPreview() {
            buildPublicationPreview();

            if (typeof $ !== 'undefined' && $.fn.modal) {
                $('#maquinariaPublicationPreviewModal').modal('show');
            }
        }

        function refreshInputFiles() {
            const dataTransfer = new DataTransfer();
            selectedFiles.forEach(item => dataTransfer.items.add(item.file));
            input.files = dataTransfer.files;
        }

        function liveExistingImagesCount() {
            return imagenesActuales - imagenesAEliminar.length;
        }

        function ensureCoverSelection() {
            const checked = form.querySelector('input[name="imagen_portada"]:checked');
            if (checked && !(checked.value.startsWith('existing:') && imagenesAEliminar.includes(checked.value.replace('existing:', '')))) {
                return;
            }

            const firstExisting = Array.from(form.querySelectorAll('input[name="imagen_portada"][value^="existing:"]'))
                .find(radio => !imagenesAEliminar.includes(radio.value.replace('existing:', '')));
            const firstNew = form.querySelector('input[name="imagen_portada"][value^="new:"]');

            if (firstExisting) {
                firstExisting.checked = true;
            } else if (firstNew) {
                firstNew.checked = true;
            }
        }

        function renderSelectedFiles() {
            previewContainer.innerHTML = '';

            selectedFiles.forEach((item, index) => {
                const col = document.createElement('div');
                col.className = 'col-md-3 mb-3';
                col.setAttribute('data-file-id', item.id);
                col.innerHTML = `
                    <div class="mb-2">
                        <img src="${item.url}"
                             alt="Preview ${index + 1}"
                             class="img-thumbnail"
                             style="width: 100%; height: 150px; object-fit: cover; border-radius: 8px;">
                    </div>
                    <div class="btn-group btn-group-sm d-flex" role="group">
                        <button type="button"
                                class="btn btn-outline-danger eliminar-preview"
                                data-file-id="${item.id}">
                            <i class="fas fa-trash mr-1"></i> Eliminar
                        </button>
                        <label class="btn btn-outline-success mb-0">
                            <input type="radio" name="imagen_portada" value="new:${index}">
                            Portada
                        </label>
                    </div>
                `;
                previewContainer.appendChild(col);
            });

            refreshInputFiles();
            ensureCoverSelection();
            updateCount();
        }

        // Manejar eliminación de imágenes existentes
        document.querySelectorAll('.eliminar-imagen').forEach(btn => {
            btn.addEventListener('click', function() {
                const imagenId = this.getAttribute('data-imagen-id');
                const imagenItem = this.closest('.imagen-item');
                const inputEliminar = imagenItem.querySelector('.imagen-eliminar-input');

                if (inputEliminar.value === '') {
                    inputEliminar.value = imagenId;
                    imagenItem.style.opacity = '0.5';
                    this.innerHTML = '<i class="fas fa-undo mr-1"></i> Restaurar';
                    imagenesAEliminar.push(imagenId);
                } else {
                    inputEliminar.value = '';
                    imagenItem.style.opacity = '1';
                    this.innerHTML = '<i class="fas fa-trash mr-1"></i> Eliminar';
                    imagenesAEliminar = imagenesAEliminar.filter(id => id !== imagenId);
                }

                ensureCoverSelection();
                updateCount();
            });
        });

        function updateCount() {
            const total = liveExistingImagesCount() + selectedFiles.length;
            countDisplay.textContent = `Total de imágenes: ${total} / 3`;
            uploadZone.classList.toggle('has-files', total > 0);

            if (total > 3) {
                countDisplay.className = 'text-danger mt-2';
                countDisplay.textContent += ' (Excede el límite de 3 imágenes)';
                input.setCustomValidity('Puedes publicar máximo 3 imágenes.');
            } else {
                countDisplay.className = 'text-muted mt-2';
                input.setCustomValidity('');
            }
        }

        if (uploadZone) {
            ['dragenter', 'dragover'].forEach(eventName => {
                uploadZone.addEventListener(eventName, function(event) {
                    event.preventDefault();
                    uploadZone.classList.add('is-dragover');
                });
            });

            ['dragleave', 'drop'].forEach(eventName => {
                uploadZone.addEventListener(eventName, function(event) {
                    event.preventDefault();
                    uploadZone.classList.remove('is-dragover');
                });
            });

            uploadZone.addEventListener('drop', function(event) {
                const files = Array.from(event.dataTransfer.files).filter(file => file.type.startsWith('image/'));
                const dataTransfer = new DataTransfer();
                files.forEach(file => dataTransfer.items.add(file));
                input.files = dataTransfer.files;
                input.dispatchEvent(new Event('change', {
                    bubbles: true
                }));
            });
        }

        input.addEventListener('change', function(e) {
            const files = Array.from(e.target.files);
            const slots = Math.max(0, 3 - liveExistingImagesCount() - selectedFiles.length);

            files.slice(0, slots).forEach((file, index) => {
                if (file.type.startsWith('image/')) {
                    selectedFiles.push({
                        id: Date.now() + '-' + index + '-' + file.name,
                        file: file,
                        url: URL.createObjectURL(file),
                    });
                }
            });

            renderSelectedFiles();
        });

        previewContainer.addEventListener('click', function(event) {
            const button = event.target.closest('.eliminar-preview');
            if (!button) return;

            const fileId = button.getAttribute('data-file-id');
            const removed = selectedFiles.find(item => item.id === fileId);
            if (removed) {
                URL.revokeObjectURL(removed.url);
            }

            selectedFiles = selectedFiles.filter(item => item.id !== fileId);
            renderSelectedFiles();
        });

        updateCount();
    });
</script>
