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
                        <label class="mb-1">Precio *</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                @php
                                    $tarifaUnidad = old('tarifa_unidad', $maquinaria->tarifa_unidad ?? 'dia');
                                @endphp
                                <select name="tarifa_unidad"
                                    class="custom-select @error('tarifa_unidad') is-invalid @enderror"
                                    style="border-top-right-radius: 0; border-bottom-right-radius: 0;">
                                    <option value="hora" {{ $tarifaUnidad === 'hora' ? 'selected' : '' }}>Bs/hora</option>
                                    <option value="dia" {{ $tarifaUnidad === 'dia' ? 'selected' : '' }}>Bs/día</option>
                                </select>
                            </div>
                            <input type="number" step="0.01" name="precio_dia"
                                class="form-control @error('precio_dia') is-invalid @enderror" placeholder="0.00"
                                value="{{ old('precio_dia', $maquinaria->precio_dia ?? 0) }}" min="0" required>
                        </div>
                        <small class="form-text text-muted">
                            Monto a cobrar según la unidad seleccionada.
                        </small>
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
                        <textarea name="descripcion" class="form-control @error('descripcion') is-invalid @enderror" rows="4" style="resize: none;"
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
                <div class="maquinaria-location-search mb-2" data-location-search>
                    <div class="input-group">
                        <input type="search" id="buscar-ubicacion" class="form-control"
                            placeholder="Buscar destino o dirección" autocomplete="off"
                            aria-describedby="ubicacion-search-help" aria-expanded="false"
                            aria-controls="sugerencias-ubicacion">
                        <div class="input-group-append">
                            <button type="button" class="btn btn-outline-secondary" id="btn-buscar-ubicacion">
                                <i class="fas fa-search mr-1"></i> Buscar
                            </button>
                            <button type="button" class="btn btn-outline-secondary" id="btn-ubicacion-actual">
                                <i class="fas fa-location-arrow mr-1"></i> Ubicación actual
                            </button>
                        </div>
                    </div>
                    <div id="sugerencias-ubicacion" class="list-group maquinaria-location-suggestions"
                        role="listbox" style="display: none;"></div>
                    <small id="ubicacion-search-help" class="form-text text-muted">
                        Escribe al menos 3 letras y elige una sugerencia, como en una app de transporte.
                    </small>
                    <small id="ubicacion-search-status" class="form-text text-muted" style="display: none;"></small>
                </div>
                <div class="maquinaria-map-panel mt-2">
                    <div class="maquinaria-map-panel__toolbar">
                        <span>
                            <i class="fas fa-map-marked-alt mr-1"></i> Mapa de ubicación
                        </span>
                        <button type="button" class="btn btn-outline-success btn-sm" id="btn-ampliar-mapa"
                            data-toggle="modal" data-target="#modal-mapa-maquinaria">
                            <i class="fas fa-expand-alt mr-1"></i> Ampliar mapa
                        </button>
                    </div>
                    <div id="map-home">
                        <div id="map" class="maquinaria-wizard__map"
                            style="height: 400px; margin-top: 10px; border-radius: 8px; border: 1px solid #e0e0e0;">
                        </div>
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
                <input type="hidden" id="ubicacion_confirmada"
                    value="{{ old('latitud', $maquinaria->latitud ?? '') && old('longitud', $maquinaria->longitud ?? '') ? '1' : '' }}">

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
                                    <div class="maquinaria-image-card">
                                        <div class="maquinaria-image-card__preview">
                                        <img src="{{ asset('storage/' . $imagen->ruta) }}"
                                                alt="Imagen {{ $loop->iteration }}">
                                        </div>
                                        <div class="maquinaria-image-card__actions">
                                            <button type="button" class="maquinaria-image-card__delete eliminar-imagen"
                                                data-imagen-id="{{ $imagen->id }}">
                                                <i class="fas fa-trash mr-1"></i> Eliminar
                                            </button>
                                            <label class="maquinaria-image-card__cover mb-0">
                                                <input type="radio" name="imagen_portada" value="existing:{{ $imagen->id }}"
                                                    {{ old('imagen_portada', $loop->first ? 'existing:' . $imagen->id : '') === 'existing:' . $imagen->id ? 'checked' : '' }}>
                                                <span><i class="fas fa-star mr-1"></i> Portada</span>
                                            </label>
                                        </div>
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

<div class="modal fade maquinaria-preview-modal" id="modal-preview-publicacion-maquinaria" tabindex="-1" role="dialog"
    aria-labelledby="modal-preview-publicacion-maquinaria-title" aria-hidden="true" data-publication-preview>
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header align-items-start">
                <div>
                    <h5 class="modal-title font-weight-bold" id="modal-preview-publicacion-maquinaria-title">
                        <i class="fas fa-eye mr-2 text-success"></i>Vista previa de la publicación
                    </h5>
                    <small class="text-muted">Revisa cómo verá el comprador tu maquinaria antes de publicarla.</small>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="maquinaria-preview-shell">
                    <div class="maquinaria-preview-note">
                        <span><i class="fas fa-check-circle"></i></span>
                        <div>
                            <strong>Revisión final antes de guardar</strong>
                            <small>Si necesitas corregir algo, vuelve al formulario con el botón Editar datos.</small>
                        </div>
                    </div>

                    <div class="maquinaria-publication-preview">
                        <div class="maquinaria-publication-preview__media">
                            <img src="{{ asset('img/maquinaria-placeholder.jpg') }}" alt="Vista previa de maquinaria"
                                data-preview-image data-preview-image-empty="{{ asset('img/maquinaria-placeholder.jpg') }}">
                            <span class="maquinaria-publication-preview__status" data-preview-status>
                                Disponible
                            </span>
                        </div>

                        <div class="maquinaria-publication-preview__body">
                            <span class="maquinaria-publication-preview__category">
                                <i class="fas fa-tractor mr-1"></i> Maquinaria
                            </span>
                            <h4 data-preview-name>Nombre de la maquinaria</h4>
                            <p data-preview-description>
                                La descripción aparecerá aquí para que puedas revisar el mensaje principal del anuncio.
                            </p>

                            <div class="maquinaria-publication-preview__meta">
                                <span><i class="fas fa-cog"></i><strong data-preview-type>Tipo</strong></span>
                                <span><i class="fas fa-tag"></i><strong data-preview-brand>Marca</strong></span>
                                <span><i class="fas fa-map-marker-alt"></i><strong data-preview-location>Ubicación</strong></span>
                            </div>

                            <div class="maquinaria-publication-preview__footer">
                                <div>
                                    <small>Precio de alquiler</small>
                                    <strong data-preview-price>Bs 0.00/día</strong>
                                </div>
                                <span>
                                    <i class="fas fa-eye mr-1"></i> Vista del anuncio
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="maquinaria-preview-summary">
                        <div>
                            <small>Modelo</small>
                            <strong data-preview-model>Sin modelo</strong>
                        </div>
                        <div>
                            <small>Teléfono</small>
                            <strong data-preview-phone>Sin teléfono</strong>
                        </div>
                        <div>
                            <small>Imágenes</small>
                            <strong data-preview-images-count>0 de 3</strong>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">
                    <i class="fas fa-edit mr-1"></i> Editar datos
                </button>
                <button type="button" class="btn btn-success" data-confirm-publication>
                    <i class="fas fa-check-circle mr-1"></i> Confirmar publicación
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade maquinaria-map-modal" id="modal-mapa-maquinaria" tabindex="-1" role="dialog"
    aria-labelledby="modal-mapa-maquinaria-title" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header align-items-start">
                <div>
                    <h5 class="modal-title font-weight-bold" id="modal-mapa-maquinaria-title">
                        <i class="fas fa-map-marked-alt mr-2 text-success"></i>Ubicación de la maquinaria
                    </h5>
                    <small class="text-muted">Haz zoom, arrastra el mapa o selecciona un punto con mayor precisión.</small>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="maquinaria-map-modal__body" id="map-modal-target"></div>
            </div>
            <div class="modal-footer justify-content-between">
                <div class="maquinaria-map-modal__summary">
                    <i class="fas fa-map-marker-alt text-success mr-1"></i>
                    <span id="modal-ubicacion-resumen">Selecciona una ubicación en el mapa.</span>
                </div>
                <div>
                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">
                        Cerrar
                    </button>
                    <button type="button" class="btn btn-success" id="btn-confirmar-ubicacion-modal">
                        <i class="fas fa-check mr-1"></i> Confirmar ubicación
                    </button>
                </div>
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
    var locationSearchTimer = null;
    var lastLocationQuery = '';
    var locationSearchAbort = null;
    var locationSearchCache = {};
    var locationSearchRequestId = 0;
    var locationSuggestions = [];
    var highlightedSuggestionIndex = -1;
    var lastSelectedLocation = null;
    var accuracyCircle = null;
    var lastAccuracy = null;

    var searchInput = document.getElementById('buscar-ubicacion');
    var searchButton = document.getElementById('btn-buscar-ubicacion');
    var currentLocationButton = document.getElementById('btn-ubicacion-actual');
    var confirmLocationButton = document.getElementById('btn-confirmar-ubicacion');
    var enlargeMapButton = document.getElementById('btn-ampliar-mapa');
    var modalConfirmLocationButton = document.getElementById('btn-confirmar-ubicacion-modal');
    var modalLocationSummary = document.getElementById('modal-ubicacion-resumen');
    var mapHome = document.getElementById('map-home');
    var mapModalTarget = document.getElementById('map-modal-target');
    var suggestionsContainer = document.getElementById('sugerencias-ubicacion');
    var searchStatus = document.getElementById('ubicacion-search-status');
    var confirmedInput = document.getElementById('ubicacion_confirmada');
    var maquinariaMarkerIcon = L.divIcon({
        className: 'maquinaria-map-marker',
        html: '<span><i class="fas fa-tractor"></i></span>',
        iconSize: [42, 42],
        iconAnchor: [21, 42],
        popupAnchor: [0, -38]
    });

    var centerMarkerControl = L.control({ position: 'topright' });
    centerMarkerControl.onAdd = function() {
        var button = L.DomUtil.create('button', 'maquinaria-map-control');
        button.type = 'button';
        button.title = 'Centrar en la ubicación seleccionada';
        button.innerHTML = '<i class="fas fa-crosshairs"></i>';

        L.DomEvent.disableClickPropagation(button);
        L.DomEvent.on(button, 'click', function(event) {
            L.DomEvent.preventDefault(event);
            centerSelectedLocation();
        });

        return button;
    };
    centerMarkerControl.addTo(map);

    // Si hay coordenadas existentes, mostrar el marcador
    @if (isset($maquinaria) && $maquinaria->latitud && $maquinaria->longitud)
        marker = L.marker([initialLat, initialLng], {
            icon: maquinariaMarkerIcon
        }).addTo(map);
    @endif

    function setSearchStatus(message, type) {
        if (!searchStatus) return;
        searchStatus.textContent = message || '';
        searchStatus.style.display = message ? 'block' : 'none';
        searchStatus.className = 'form-text ' + (type === 'error' ? 'text-danger' : type === 'success' ? 'text-success' : 'text-muted');
    }

    function escapeHtml(value) {
        return String(value || '').replace(/[&<>"']/g, function(character) {
            return {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;'
            }[character];
        });
    }

    function setButtonLoading(button, isLoading, text) {
        if (!button) return;

        if (isLoading) {
            if (!button.dataset.originalHtml) {
                button.dataset.originalHtml = button.innerHTML;
            }
            button.disabled = true;
            button.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> ' + text;
            return;
        }

        button.disabled = false;
        if (button.dataset.originalHtml) {
            button.innerHTML = button.dataset.originalHtml;
            delete button.dataset.originalHtml;
        }
    }

    function resetLocationConfirmation() {
        confirmedInput.value = '';
        confirmLocationButton.classList.remove('btn-success');
        confirmLocationButton.classList.add('btn-outline-success');
        confirmLocationButton.innerHTML = '<i class="fas fa-check mr-1"></i> Confirmar ubicación';
        document.getElementById('ubicacion-confirmada').style.display = 'none';
    }

    function enableLocationConfirmation() {
        confirmLocationButton.disabled = false;
        resetLocationConfirmation();
    }

    function locationLabel() {
        return document.getElementById('ubicacion').value || (lastSelectedLocation ? lastSelectedLocation.label : '') || 'Ubicación seleccionada';
    }

    function popupContent() {
        var lat = document.getElementById('latitud').value;
        var lng = document.getElementById('longitud').value;
        var label = escapeHtml(locationLabel());
        var confirmed = confirmedInput.value === '1';

        return [
            '<div class="maquinaria-map-popup">',
                '<strong>', confirmed ? 'Ubicación confirmada' : 'Ubicación seleccionada', '</strong>',
                '<span>', label, '</span>',
                '<small>Lat ', escapeHtml(lat), ' / Lng ', escapeHtml(lng), '</small>',
                '<button type="button" class="btn btn-sm ', confirmed ? 'btn-success' : 'btn-outline-success', ' btn-block mt-2" data-map-confirm-location>',
                    '<i class="fas ', confirmed ? 'fa-check-circle' : 'fa-check', ' mr-1"></i>',
                    confirmed ? 'Confirmada' : 'Confirmar aquí',
                '</button>',
            '</div>'
        ].join('');
    }

    function openMarkerPopup() {
        if (!marker) return;
        marker.bindPopup(popupContent(), {
            closeButton: true,
            minWidth: 230
        }).openPopup();
    }

    function refreshExpandedMapSummary() {
        if (!modalLocationSummary) return;
        modalLocationSummary.textContent = document.getElementById('ubicacion').value || 'Selecciona una ubicación en el mapa.';
    }

    function refreshMapSize(delay) {
        setTimeout(function() {
            map.invalidateSize();
            if (marker) {
                map.panTo(marker.getLatLng(), {
                    animate: false
                });
            }
        }, delay || 160);
    }

    function centerSelectedLocation() {
        if (!marker) {
            setSearchStatus('Todavía no hay una ubicación seleccionada para centrar.', 'error');
            return;
        }

        map.setView(marker.getLatLng(), Math.max(map.getZoom(), 15));
        openMarkerPopup();
    }

    function drawAccuracyCircle(lat, lng, accuracy) {
        if (accuracyCircle) {
            map.removeLayer(accuracyCircle);
            accuracyCircle = null;
        }

        lastAccuracy = accuracy || null;
        if (!accuracy) return;

        accuracyCircle = L.circle([lat, lng], {
            radius: accuracy,
            color: '#2f621f',
            weight: 1,
            fillColor: '#4f8f2f',
            fillOpacity: 0.14
        }).addTo(map);
    }

    function setMapLocation(lat, lng, label, zoom, skipReverse, accuracy) {
        if (marker) {
            marker.setLatLng([lat, lng]);
        } else {
            marker = L.marker([lat, lng], {
                icon: maquinariaMarkerIcon
            }).addTo(map);
        }

        document.getElementById('latitud').value = lat;
        document.getElementById('longitud').value = lng;
        document.getElementById('ubicacion').value = label || ("Lat: " + lat + " - Lng: " + lng);
        refreshExpandedMapSummary();
        lastSelectedLocation = {
            lat: lat,
            lng: lng,
            label: label || ''
        };
        enableLocationConfirmation();

        if (zoom) {
            map.setView([lat, lng], zoom);
        }

        drawAccuracyCircle(lat, lng, accuracy);
        openMarkerPopup();

        if (!skipReverse) {
            obtenerInformacionGeografica(lat, lng);
        }
    }

    // Evento click en mapa
    map.on('click', function(e) {
        var lat = e.latlng.lat.toFixed(7);
        var lng = e.latlng.lng.toFixed(7);

        setSearchStatus('Punto seleccionado en el mapa. Confírmalo para continuar.', 'success');
        setMapLocation(lat, lng, null);
    });

    function closeLocationSuggestions() {
        suggestionsContainer.innerHTML = '';
        suggestionsContainer.style.display = 'none';
        searchInput.setAttribute('aria-expanded', 'false');
        highlightedSuggestionIndex = -1;
    }

    function suggestionTitle(result) {
        return result.name || result.city || result.municipality || result.display_name.split(',')[0] || 'Ubicación';
    }

    function suggestionSubtitle(result) {
        if (result.address_line) return result.address_line;
        var parts = result.display_name.split(',').map(function(part) {
            return part.trim();
        }).filter(Boolean);
        return parts.slice(1, 5).join(', ');
    }

    function highlightSuggestion(index) {
        var items = Array.from(suggestionsContainer.querySelectorAll('[data-location-suggestion]'));
        highlightedSuggestionIndex = Math.max(-1, Math.min(index, items.length - 1));

        items.forEach(function(item, itemIndex) {
            var active = itemIndex === highlightedSuggestionIndex;
            item.classList.toggle('active', active);
            item.setAttribute('aria-selected', active ? 'true' : 'false');
        });
    }

    function selectLocationSuggestion(result) {
        var lat = Number(result.lat).toFixed(7);
        var lng = Number(result.lon).toFixed(7);
        var label = result.display_name;

        closeLocationSuggestions();
        searchInput.value = label;
        setSearchStatus('Destino seleccionado. Confirma la ubicación para usarla en la publicación.', 'success');
        setMapLocation(lat, lng, label, 15, true);

        document.getElementById('info-ubicacion').style.display = 'block';
        document.getElementById('ciudad-texto').textContent = result.city || result.municipality || 'No disponible';
        document.getElementById('direccion-texto').textContent = result.address_line || result.display_name;
        document.getElementById('departamento').value = result.state || '';
        document.getElementById('municipio').value = result.municipality || result.city || '';
        document.getElementById('provincia').value = result.county || '';
        document.getElementById('ciudad').value = result.city || result.municipality || '';
    }

    function renderLocationSuggestions(results) {
        locationSuggestions = results || [];
        suggestionsContainer.innerHTML = '';

        if (!results.length) {
            closeLocationSuggestions();
            setSearchStatus('No encontramos coincidencias. Prueba con una zona, avenida o ciudad cercana.', 'error');
            return;
        }

        results.forEach(function(result, index) {
            var option = document.createElement('button');
            option.type = 'button';
            option.className = 'list-group-item list-group-item-action maquinaria-location-suggestion';
            option.setAttribute('role', 'option');
            option.setAttribute('aria-selected', 'false');
            option.setAttribute('data-location-suggestion', index);
            option.innerHTML = [
                '<span class="maquinaria-location-suggestion__icon"><i class="fas fa-map-marker-alt"></i></span>',
                '<span class="maquinaria-location-suggestion__copy">',
                    '<strong>', escapeHtml(suggestionTitle(result)), '</strong>',
                    '<small>', escapeHtml(suggestionSubtitle(result)), '</small>',
                '</span>'
            ].join('');
            option.addEventListener('click', function() {
                selectLocationSuggestion(result);
            });
            suggestionsContainer.appendChild(option);
        });

        suggestionsContainer.style.display = 'block';
        searchInput.setAttribute('aria-expanded', 'true');
        highlightSuggestion(0);
        setSearchStatus(results.length + ' sugerencia(s) encontrada(s).', 'success');
    }

    function buscarUbicacion(force) {
        var query = searchInput.value.trim();

        if (query.length < 3) {
            closeLocationSuggestions();
            setSearchStatus(query ? 'Escribe al menos 3 letras para buscar.' : '', 'muted');
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
        var requestId = ++locationSearchRequestId;

        setButtonLoading(searchButton, true, 'Buscando');
        setSearchStatus('Buscando destinos...', 'muted');

        fetch('/api/geocodificacion/buscar?q=' + encodeURIComponent(query), {
                signal: locationSearchAbort.signal
            })
            .then(response => response.json())
            .then(data => {
                if (requestId !== locationSearchRequestId) return;
                var results = data.success ? data.data : [];
                locationSearchCache[query] = results;
                renderLocationSuggestions(results);
            })
            .catch(error => {
                if (error.name !== 'AbortError') {
                    console.error('Error al buscar ubicación:', error);
                    setSearchStatus('No se pudo buscar la ubicación. Intenta nuevamente.', 'error');
                }
            })
            .finally(function() {
                if (requestId === locationSearchRequestId) {
                    setButtonLoading(searchButton, false);
                }
            });
    }

    searchInput.addEventListener('input', function() {
        clearTimeout(locationSearchTimer);
        resetLocationConfirmation();
        locationSearchTimer = setTimeout(function() {
            buscarUbicacion(false);
        }, 350);
    });

    searchInput.addEventListener('keydown', function(event) {
        if (suggestionsContainer.style.display === 'none') return;

        if (event.key === 'ArrowDown') {
            event.preventDefault();
            highlightSuggestion(highlightedSuggestionIndex + 1);
        } else if (event.key === 'ArrowUp') {
            event.preventDefault();
            highlightSuggestion(highlightedSuggestionIndex - 1);
        } else if (event.key === 'Enter') {
            event.preventDefault();
            var selected = locationSuggestions[highlightedSuggestionIndex] || locationSuggestions[0];
            if (selected) selectLocationSuggestion(selected);
        } else if (event.key === 'Escape') {
            closeLocationSuggestions();
        }
    });

    document.addEventListener('click', function(event) {
        if (!event.target.closest('[data-location-search]')) {
            closeLocationSuggestions();
        }
    });

    searchButton.addEventListener('click', function() {
        clearTimeout(locationSearchTimer);
        buscarUbicacion(true);
    });

    enlargeMapButton.addEventListener('click', function() {
        refreshExpandedMapSummary();
    });

    window.addEventListener('load', function() {
        if (!window.jQuery) return;

        $('#modal-mapa-maquinaria').on('shown.bs.modal', function() {
            mapModalTarget.appendChild(document.getElementById('map'));
            refreshMapSize(120);
            if (marker) {
                openMarkerPopup();
            }
        });

        $('#modal-mapa-maquinaria').on('hidden.bs.modal', function() {
            mapHome.appendChild(document.getElementById('map'));
            refreshMapSize(120);
        });
    });

    modalConfirmLocationButton.addEventListener('click', function() {
        confirmLocationButton.click();
        refreshExpandedMapSummary();
    });

    currentLocationButton.addEventListener('click', function() {
        if (!navigator.geolocation) {
            setSearchStatus('Tu navegador no permite obtener la ubicación actual.', 'error');
            return;
        }

        setButtonLoading(currentLocationButton, true, 'Ubicando');
        setSearchStatus('Solicitando permiso y buscando tu ubicación actual...', 'muted');

        navigator.geolocation.getCurrentPosition(function(position) {
            var lat = position.coords.latitude.toFixed(7);
            var lng = position.coords.longitude.toFixed(7);
            var accuracy = Math.round(position.coords.accuracy || 0);
            setSearchStatus('Ubicación actual detectada' + (accuracy ? ' con precisión aproximada de ' + accuracy + ' m.' : '.') + ' Confírmala para usarla.', 'success');
            setMapLocation(lat, lng, 'Mi ubicación actual', 16, false, position.coords.accuracy || null);
            setButtonLoading(currentLocationButton, false);
        }, function(error) {
            var message = 'No se pudo obtener tu ubicación actual.';
            if (error.code === error.PERMISSION_DENIED) {
                message = 'Permiso de ubicación denegado. Actívalo en el navegador para usar este botón.';
            } else if (error.code === error.TIMEOUT) {
                message = 'La búsqueda de tu ubicación tardó demasiado. Intenta otra vez.';
            }
            setSearchStatus(message, 'error');
            setButtonLoading(currentLocationButton, false);
        }, {
            enableHighAccuracy: true,
            timeout: 12000,
            maximumAge: 30000
        });
    });

    confirmLocationButton.addEventListener('click', function() {
        if (!document.getElementById('latitud').value || !document.getElementById('longitud').value) {
            setSearchStatus('Primero selecciona una ubicación en el mapa o desde el buscador.', 'error');
            return;
        }

        confirmedInput.value = '1';
        confirmLocationButton.classList.remove('btn-outline-success');
        confirmLocationButton.classList.add('btn-success');
        confirmLocationButton.innerHTML = '<i class="fas fa-check-circle mr-1"></i> Ubicación confirmada';
        document.getElementById('ubicacion-confirmada').style.display = 'block';
        setSearchStatus('La ubicación quedó confirmada para esta maquinaria.', 'success');
        refreshExpandedMapSummary();
        openMarkerPopup();
    });

    if (confirmedInput.value === '1' && document.getElementById('latitud').value && document.getElementById('longitud').value) {
        confirmLocationButton.classList.remove('btn-outline-success');
        confirmLocationButton.classList.add('btn-success');
        confirmLocationButton.innerHTML = '<i class="fas fa-check-circle mr-1"></i> Ubicación confirmada';
        document.getElementById('ubicacion-confirmada').style.display = 'block';
        openMarkerPopup();
    }

    map.on('popupopen', function(event) {
        var button = event.popup.getElement().querySelector('[data-map-confirm-location]');
        if (!button) return;

        button.addEventListener('click', function() {
            confirmLocationButton.click();
        });
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
                        if (marker && marker.isPopupOpen()) {
                            marker.setPopupContent(popupContent());
                        }
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
        const serverErrors = @json($errors->messages());
        const previewModal = document.getElementById('modal-preview-publicacion-maquinaria');
        const confirmPublicationButton = document.querySelector('[data-confirm-publication]');
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

        function validateStep(index, shouldFocus = true) {
            const controls = Array.from(steps[index].querySelectorAll('input, select, textarea'))
                .filter(control => !control.disabled && control.type !== 'hidden');
            const invalidControls = [];
            const locationNeedsConfirmation = index === 2 &&
                document.getElementById('latitud')?.value &&
                document.getElementById('longitud')?.value &&
                document.getElementById('ubicacion_confirmada')?.value !== '1';

            controls.forEach(control => {
                clearFieldError(control);

                if (!control.checkValidity()) {
                    invalidControls.push(control);
                    setFieldError(control, validationMessage(control));
                }
            });

            steps[index].classList.toggle('has-errors', invalidControls.length > 0 || locationNeedsConfirmation);
            indicators[index].classList.toggle('has-errors', invalidControls.length > 0 || locationNeedsConfirmation);

            if (invalidControls.length > 0 || locationNeedsConfirmation) {
                errorSummary.textContent = locationNeedsConfirmation
                    ? 'Confirma la ubicación seleccionada antes de continuar.'
                    : 'Revisa los campos marcados antes de continuar.';
                errorSummary.classList.remove('d-none');

                if (locationNeedsConfirmation && typeof setSearchStatus === 'function') {
                    setSearchStatus('Confirma la ubicación seleccionada para continuar.', 'error');
                }

                if (shouldFocus && invalidControls.length > 0) {
                    invalidControls[0].focus({
                        preventScroll: true
                    });
                    invalidControls[0].scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                } else if (shouldFocus && locationNeedsConfirmation) {
                    document.getElementById('btn-confirmar-ubicacion')?.focus({
                        preventScroll: true
                    });
                    document.getElementById('btn-confirmar-ubicacion')?.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                }
            } else {
                errorSummary.classList.add('d-none');
            }

            return invalidControls.length === 0 && !locationNeedsConfirmation;
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

        form.addEventListener('submit', function(event) {
            if (allowSubmit) return;

            for (let index = 0; index < steps.length; index++) {
                if (!validateStep(index, false)) {
                    event.preventDefault();
                    showStep(index);
                    validateStep(index, true);
                    return;
                }
            }

            event.preventDefault();
            renderPublicationPreview();

            if (window.jQuery && previewModal) {
                $('#modal-preview-publicacion-maquinaria').modal('show');
            } else if (window.confirm('¿Confirmar la publicación de esta maquinaria?')) {
                allowSubmit = true;
                form.submit();
            }
        });

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

        function formValue(name, fallback = '') {
            const control = form.querySelector(`[name="${name}"]`);
            return control && control.value ? control.value.trim() : fallback;
        }

        function selectedOptionText(name, fallback = '') {
            const control = form.querySelector(`select[name="${name}"]`);
            if (!control || control.selectedIndex < 0) return fallback;
            const option = control.options[control.selectedIndex];
            return option && option.value ? option.textContent.trim() : fallback;
        }

        function setPreviewText(selector, value, fallback) {
            const element = previewModal ? previewModal.querySelector(selector) : document.querySelector(selector);
            if (!element) return;
            element.textContent = value || fallback;
        }

        function formatPreviewPrice() {
            const rawPrice = Number(formValue('precio_dia', '0'));
            const unit = formValue('tarifa_unidad', 'dia') === 'hora' ? 'hora' : 'día';
            const price = Number.isFinite(rawPrice) ? rawPrice : 0;
            return `Bs ${price.toFixed(2)}/${unit}`;
        }

        function activeExistingImageItems() {
            return Array.from(form.querySelectorAll('.imagen-item')).filter(item => {
                const inputEliminar = item.querySelector('.imagen-eliminar-input');
                return !inputEliminar || inputEliminar.value === '';
            });
        }

        function previewImages() {
            const existing = activeExistingImageItems().map(item => {
                const radio = item.querySelector('input[name="imagen_portada"]');
                const image = item.querySelector('.maquinaria-image-card__preview img');
                return {
                    key: radio ? radio.value : '',
                    src: image ? image.src : '',
                };
            }).filter(item => item.src);

            const fresh = selectedFiles.map((item, index) => ({
                key: `new:${index}`,
                src: item.url,
            }));

            return existing.concat(fresh);
        }

        function renderPublicationPreview() {
            const images = previewImages();
            const selectedCover = form.querySelector('input[name="imagen_portada"]:checked');
            const cover = selectedCover
                ? images.find(item => item.key === selectedCover.value)
                : images[0];
            const previewImage = previewModal ? previewModal.querySelector('[data-preview-image]') : document.querySelector('[data-preview-image]');

            if (previewImage) {
                previewImage.src = cover ? cover.src : previewImage.dataset.previewImageEmpty;
            }

            setPreviewText('[data-preview-name]', formValue('nombre'), 'Nombre de la maquinaria');
            setPreviewText('[data-preview-description]', formValue('descripcion'), 'Sin descripción adicional.');
            setPreviewText('[data-preview-type]', selectedOptionText('tipo_maquinaria_id'), 'Tipo sin seleccionar');
            setPreviewText('[data-preview-brand]', selectedOptionText('marca_maquinaria_id'), 'Marca sin seleccionar');
            setPreviewText('[data-preview-location]', formValue('ubicacion'), 'Ubicación sin confirmar');
            setPreviewText('[data-preview-price]', formatPreviewPrice(), 'Bs 0.00/día');
            setPreviewText('[data-preview-model]', formValue('modelo'), 'Sin modelo');
            setPreviewText('[data-preview-phone]', formValue('telefono'), 'Sin teléfono');
            setPreviewText('[data-preview-images-count]', `${images.length} de 3`, '0 de 3');

            const statusText = selectedOptionText('estado_maquinaria_id') ||
                form.querySelector('select[disabled] option:checked')?.textContent.trim() ||
                'Disponible';
            setPreviewText('[data-preview-status]', statusText, 'Disponible');
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
                    <div class="maquinaria-image-card">
                        <div class="maquinaria-image-card__preview">
                            <img src="${item.url}" alt="Preview ${index + 1}">
                        </div>
                        <div class="maquinaria-image-card__actions">
                            <button type="button"
                                    class="maquinaria-image-card__delete eliminar-preview"
                                    data-file-id="${item.id}">
                                <i class="fas fa-trash mr-1"></i> Eliminar
                            </button>
                            <label class="maquinaria-image-card__cover mb-0">
                                <input type="radio" name="imagen_portada" value="new:${index}">
                                <span><i class="fas fa-star mr-1"></i> Portada</span>
                            </label>
                        </div>
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
                renderPublicationPreview();
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
            renderPublicationPreview();
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
            renderPublicationPreview();
        });

        updateCount();
        renderPublicationPreview();

        if (confirmPublicationButton) {
            confirmPublicationButton.addEventListener('click', function() {
                allowSubmit = true;
                this.disabled = true;
                this.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Publicando';
                form.submit();
            });
        }
    });
</script>
