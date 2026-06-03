@csrf

@php
    $categoriaSeleccionada = old('categoria_id', $organico->categoria_id ?? optional($categorias->first())->id);
    $registrosCertificados = collect($organico?->certificadoRegistros ?? [])->keyBy('certificado_organico_id');
    $obligatorios = $certificados->where('es_obligatorio', true);
    $opcionales = $certificados->where('es_obligatorio', false);
    $unidadSeleccionadaId = old('unidad_id', $organico->unidad_id ?? '');
    $unidadSeleccionada = $unidades->first(fn($unidad) => (string) $unidad->id === (string) $unidadSeleccionadaId);
    $precioLabel = $unidadSeleccionada ? 'Precio por ' . \Illuminate\Support\Str::lower($unidadSeleccionada->nombre) : 'Precio';
    $wizardSteps = [
        [
            'icon' => 'fas fa-leaf',
            'title' => 'Producto',
            'description' => 'Identifica el producto orgánico, precio, unidad y stock.',
        ],
        [
            'icon' => 'fas fa-route',
            'title' => 'Trazabilidad',
            'description' => 'Registra finca, cosecha, tratamientos y observaciones.',
        ],
        [
            'icon' => 'fas fa-map-marker-alt',
            'title' => 'Origen',
            'description' => 'Marca la ubicación de origen y agrega una referencia.',
        ],
        [
            'icon' => 'fas fa-certificate',
            'title' => 'Certificados',
            'description' => 'Adjunta certificados obligatorios, opcionales o adicionales.',
        ],
        [
            'icon' => 'fas fa-images',
            'title' => 'Imágenes',
            'description' => 'Agrega o administra las fotografías de la publicación.',
        ],
    ];
@endphp

<style>
    .cert-file-panel {
        display: none;
    }

    .cert-file-panel.is-active {
        display: block;
    }
</style>

<input type="hidden" name="categoria_id" value="{{ $categoriaSeleccionada }}">

<div class="maquinaria-wizard" data-maquinaria-wizard>
    <div class="maquinaria-wizard__shell">
        <div class="maquinaria-wizard__hero">
            <div>
                <span class="maquinaria-wizard__eyebrow">Registro de producto orgánico</span>
                <h3 class="maquinaria-wizard__title mb-1">
                    <i class="fas fa-leaf mr-2"></i>{{ isset($organico) ? 'Editar orgánico' : 'Nuevo orgánico' }}
                </h3>
                <p class="maquinaria-wizard__subtitle mb-0">
                    Completa la información paso a paso. Los datos se conservan al avanzar o retroceder.
                </p>
            </div>
            <span class="badge badge-success maquinaria-wizard__badge" data-wizard-current-label>
                Paso 1 de {{ count($wizardSteps) }}
            </span>
        </div>

        <div class="maquinaria-wizard__progress" role="tablist" aria-label="Pasos del registro de producto orgánico">
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

    <section class="maquinaria-wizard-step is-active" data-wizard-step="0">
        <div class="card card-outline card-success shadow-sm mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="card-title mb-0"><i class="fas fa-leaf mr-2"></i>Datos del producto</h3>
                    <small class="text-muted">Identificación, cultivo, precio y disponibilidad.</small>
                </div>
                <span class="badge badge-success">Paso 1 de {{ count($wizardSteps) }}</span>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nombre">Nombre *</label>
                            <input type="text" name="nombre" id="nombre" class="form-control"
                                placeholder="Ej: Zanahoria organica fresca"
                                value="{{ old('nombre', $organico->nombre ?? '') }}" required>
                        </div>

                        <div class="form-group">
                            <label for="tipo_cultivo_id">Tipo de cultivo *</label>
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
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="unidad_id">Unidad de medida</label>
                            <select name="unidad_id" id="unidad_id" class="form-control">
                                <option value="">Seleccione una unidad</option>
                                @foreach ($unidades as $unidad)
                                    <option value="{{ $unidad->id }}"
                                        {{ old('unidad_id', $organico->unidad_id ?? '') == $unidad->id ? 'selected' : '' }}>
                                        {{ $unidad->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="stock">Stock *</label>
                            <input type="number" name="stock" id="stock" class="form-control" placeholder="Cantidad disponible"
                                value="{{ old('stock', $organico->stock ?? 0) }}" required min="0">
                        </div>

                        <div class="form-group mb-md-0">
                            <label for="precio" id="precio-label">{{ $precioLabel }} *</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Bs</span>
                                </div>
                                <input type="number" step="0.01" name="precio" id="precio" class="form-control"
                                    placeholder="0.00" value="{{ old('precio', $organico->precio ?? 0) }}" required min="0">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="maquinaria-wizard-step" data-wizard-step="1">
        <div class="card card-outline card-success shadow-sm mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="card-title mb-0"><i class="fas fa-route mr-2"></i>Trazabilidad del producto</h3>
                    <small class="text-muted">Finca, fechas de siembra y cosecha, tratamientos y observaciones.</small>
                </div>
                <span class="badge badge-success">Paso 2 de {{ count($wizardSteps) }}</span>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Finca o productor</label>
                            <input type="text" name="finca" class="form-control" placeholder="Ej: Finca Las Palmeras"
                                value="{{ old('finca', $organico?->trazabilidad?->finca ?? '') }}">
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Fecha de siembra</label>
                            <input type="date" name="fecha_siembra" class="form-control"
                                value="{{ old('fecha_siembra', optional($organico?->trazabilidad?->fecha_siembra ?? null)->format('Y-m-d')) }}">
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Fecha de cosecha</label>
                            <input type="date" name="fecha_cosecha" class="form-control"
                                value="{{ old('fecha_cosecha', $organico->fecha_cosecha ?? '') }}">
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Tratamientos utilizados</label>
                            <input type="text" name="tratamientos_utilizados" class="form-control"
                                placeholder="Ej: Compost, control biologico"
                                value="{{ old('tratamientos_utilizados', $organico?->trazabilidad?->tratamientos_utilizados ?? '') }}">
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-group mb-0">
                            <label>Observaciones</label>
                            <textarea name="observaciones_trazabilidad" class="form-control" rows="4"
                                placeholder="Notas sobre manejo, cosecha, empaque o transporte">{{ old('observaciones_trazabilidad', $organico?->trazabilidad?->observaciones ?? '') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="maquinaria-wizard-step" data-wizard-step="2">
        <div class="card card-outline card-success shadow-sm mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="card-title mb-0"><i class="fas fa-map-marker-alt mr-2"></i>Descripción y origen</h3>
                    <small class="text-muted">Describe el producto y selecciona su punto de origen.</small>
                </div>
                <span class="badge badge-success">Paso 3 de {{ count($wizardSteps) }}</span>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-5">
                        <div class="form-group">
                            <label>Descripcion del producto</label>
                            <textarea name="descripcion" class="form-control" rows="8"
                                placeholder="Describe caracteristicas, forma de cultivo y detalles de venta">{{ old('descripcion', $organico->descripcion ?? '') }}</textarea>
                        </div>
                    </div>

                    <div class="col-md-7">
                        <div class="form-group mb-2">
                            <label>Ubicacion del producto</label>
                            <div id="map-origen" class="maquinaria-wizard__map" style="height: 320px; border-radius: 8px; border: 1px solid #e0e0e0;"></div>
                        </div>

                        <input type="text" id="origen" name="origen" class="form-control mb-3"
                            value="{{ old('origen', $organico->origen ?? '') }}" readonly>

                        <div id="info-origen" class="mt-1"
                            style="display: {{ isset($organico) && ($organico->origen ?? false) ? 'block' : 'none' }};">
                            <div class="maquinaria-wizard__location-detail">
                                <div class="card-body py-3">
                                    <div class="row mb-2">
                                        <div class="col-md-3"><strong>Ciudad:</strong></div>
                                        <div class="col-md-9" id="ciudad-origen-texto">
                                            {{ isset($organico) ? $organico->ciudad_origen ?? '-' : '-' }}
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3"><strong>Direccion:</strong></div>
                                        <div class="col-md-9" id="direccion-origen-texto">
                                            {{ isset($organico) && ($organico->origen ?? false) ? $organico->origen : '-' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

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

                        <div class="form-group mt-3 mb-0">
                            <label>Referencia de ubicacion</label>
                            <input type="text" name="referencia_ubicacion" class="form-control"
                                placeholder="Ej: Comunidad, zona, feria o referencia cercana"
                                value="{{ old('referencia_ubicacion', $organico?->ubicacionUnificada?->referencia ?? '') }}">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="maquinaria-wizard-step" data-wizard-step="3">
        <div class="card card-outline card-success shadow-sm mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="card-title mb-0"><i class="fas fa-certificate mr-2"></i>Certificados</h3>
                    <small class="text-muted">Documentos obligatorios, opcionales y adicionales.</small>
                </div>
                <span class="badge badge-success">Paso 4 de {{ count($wizardSteps) }}</span>
            </div>
            <div class="card-body">
                <h6 class="text-muted text-uppercase mb-3">Certificados obligatorios</h6>
                <div class="row">
                    @foreach ($obligatorios as $certificado)
                        @php
                            $registro = $registrosCertificados->get($certificado->id);
                            $sinCertificado = old("certificados.{$certificado->id}.sin_certificado", $registro && !$registro->archivo ? 1 : 0);
                            $estadoCertificado = $registro?->archivo ? ($registro->estado === 'verificado' ? 'aprobado' : ($registro->estado ?? 'pendiente')) : 'no subido';
                            $badgeCertificado = !$registro?->archivo ? 'secondary' : ($registro?->estado === 'verificado' ? 'success' : ($registro?->estado === 'rechazado' ? 'danger' : 'warning'));
                        @endphp
                        <div class="col-md-6 mb-3">
                            <div class="border rounded p-3 h-100">
                                <input type="hidden" name="certificados[{{ $certificado->id }}][incluido]" value="1">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div>
                                        <strong>{{ $certificado->nombre }}</strong>
                                        <small class="d-block text-muted">{{ $certificado->descripcion }}</small>
                                    </div>
                                    <span class="badge badge-danger">Obligatorio</span>
                                </div>

                                <div class="custom-control custom-radio">
                                    <input type="radio" id="cert_{{ $certificado->id }}_sin" class="custom-control-input cert-mode"
                                        name="certificados[{{ $certificado->id }}][sin_certificado]" value="1"
                                        data-cert="{{ $certificado->id }}" {{ $sinCertificado ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="cert_{{ $certificado->id }}_sin">Sin certificado</label>
                                </div>

                                <div class="custom-control custom-radio mb-2">
                                    <input type="radio" id="cert_{{ $certificado->id }}_con" class="custom-control-input cert-mode"
                                        name="certificados[{{ $certificado->id }}][sin_certificado]" value="0"
                                        data-cert="{{ $certificado->id }}" {{ !$sinCertificado ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="cert_{{ $certificado->id }}_con">Subir certificado</label>
                                </div>

                                <div id="cert_file_{{ $certificado->id }}" class="cert-file-panel {{ !$sinCertificado ? 'is-active' : '' }}">
                                    <input type="file" name="certificados[{{ $certificado->id }}][archivo]"
                                        class="form-control mb-2" accept=".pdf,image/*">
                                    @if ($registro?->archivo)
                                        <small class="form-text text-muted mb-2">Archivo actual: {{ basename($registro->archivo) }}</small>
                                    @endif
                                </div>

                                <textarea name="certificados[{{ $certificado->id }}][observaciones]" class="form-control form-control-sm"
                                    rows="2" placeholder="Observaciones">{{ old("certificados.{$certificado->id}.observaciones", $registro?->observaciones) }}</textarea>
                                <small class="d-block mt-2">Estado:
                                    <span class="badge badge-{{ $badgeCertificado }}">
                                        {{ $estadoCertificado }}
                                    </span>
                                </small>
                            </div>
                        </div>
                    @endforeach
                </div>

                <h6 class="text-muted text-uppercase mt-3 mb-3">Certificados y membresias opcionales</h6>
                <div class="row">
                    @foreach ($opcionales as $certificado)
                        @php $registro = $registrosCertificados->get($certificado->id); @endphp
                        <div class="col-md-6 mb-3">
                            <div class="border rounded p-3 h-100">
                                <div class="custom-control custom-checkbox mb-2">
                                    <input type="checkbox" class="custom-control-input" id="certificado_{{ $certificado->id }}"
                                        name="certificados[{{ $certificado->id }}][incluido]" value="1"
                                        {{ old("certificados.{$certificado->id}.incluido", $registro ? 1 : 0) ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="certificado_{{ $certificado->id }}">
                                        <strong>{{ $certificado->nombre }}</strong>
                                    </label>
                                </div>
                                <small class="d-block text-muted mb-2">{{ $certificado->descripcion }}</small>
                                <input type="file" name="certificados[{{ $certificado->id }}][archivo]"
                                    class="form-control mb-2" accept=".pdf,image/*">
                                @if ($registro?->archivo)
                                    <small class="form-text text-muted mb-2">Archivo actual: {{ basename($registro->archivo) }}</small>
                                @endif
                                <textarea name="certificados[{{ $certificado->id }}][observaciones]" class="form-control form-control-sm"
                                    rows="2" placeholder="Observaciones">{{ old("certificados.{$certificado->id}.observaciones", $registro?->observaciones) }}</textarea>
                            </div>
                        </div>
                    @endforeach
                </div>

                <h6 class="text-muted text-uppercase mt-3 mb-3">Certificados adicionales</h6>
                @if (isset($organico))
                    @foreach ($organico->certificadoRegistros->whereNull('certificado_organico_id') as $adicional)
                        <div class="alert alert-light border">
                            <strong>{{ $adicional->nombre_adicional }}</strong>
                            <span class="badge badge-{{ $adicional->estado === 'verificado' ? 'success' : 'warning' }}">
                                {{ $adicional->estado }}
                            </span>
                            @if ($adicional->archivo)
                                <small class="d-block text-muted">Archivo: {{ basename($adicional->archivo) }}</small>
                            @endif
                        </div>
                    @endforeach
                @endif

                <div class="row">
                    <div class="col-md-4 mb-2">
                        <input type="text" name="certificados_adicionales[0][nombre]" class="form-control"
                            placeholder="Nombre del certificado adicional">
                    </div>
                    <div class="col-md-4 mb-2">
                        <input type="file" name="certificados_adicionales[0][archivo]" class="form-control"
                            accept=".pdf,image/*">
                    </div>
                    <div class="col-md-4 mb-2">
                        <input type="text" name="certificados_adicionales[0][observaciones]" class="form-control"
                            placeholder="Observaciones">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="maquinaria-wizard-step" data-wizard-step="4">
        <div class="card card-outline card-success shadow-sm mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="card-title mb-0"><i class="fas fa-images mr-2"></i>Imágenes del producto</h3>
                    <small class="text-muted">Máximo 3 imágenes por publicación.</small>
                </div>
                <span class="badge badge-success">Paso 5 de {{ count($wizardSteps) }}</span>
            </div>
            <div class="card-body">
                @if (isset($organico) && $organico->imagenes && $organico->imagenes->count() > 0)
                    <div class="mb-3">
                        <p class="text-muted mb-2">Imagenes actuales:</p>
                        <div class="row" id="imagenes-actuales">
                            @foreach ($organico->imagenes as $imagen)
                                <div class="col-md-3 mb-3 imagen-item" data-imagen-id="{{ $imagen->id }}">
                                    <div class="position-relative">
                                        <img src="{{ asset('storage/' . $imagen->ruta) }}" alt="Imagen {{ $loop->iteration }}"
                                            class="img-thumbnail" style="width: 100%; height: 150px; object-fit: cover; border-radius: 8px;">
                                        <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1 eliminar-imagen"
                                            data-imagen-id="{{ $imagen->id }}">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                    <input type="hidden" name="imagenes_eliminar[]" value="" class="imagen-eliminar-input">
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div id="preview-container" class="row mb-3"></div>

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
                <input type="file" name="imagenes[]" class="maquinaria-upload-input" accept="image/*" multiple id="imagenes-input">
                <div id="imagenes-count" class="maquinaria-upload-count text-muted mt-2"></div>
            </div>
        </div>
    </section>

        </div>

        <div class="maquinaria-wizard__actions">
            <a href="{{ url()->previous() !== url()->current() ? url()->previous() : route('organicos.index') }}"
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

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const wizard = document.querySelector('[data-maquinaria-wizard]');
        if (!wizard) return;

        const form = wizard.closest('form');
        const steps = Array.from(wizard.querySelectorAll('[data-wizard-step]'));
        const indicators = Array.from(wizard.querySelectorAll('[data-wizard-go-to]'));
        const prevBtn = wizard.querySelector('[data-wizard-prev]');
        const nextBtn = wizard.querySelector('[data-wizard-next]');
        const submitBtn = wizard.querySelector('[data-wizard-submit]');
        const errorSummary = wizard.querySelector('[data-wizard-error-summary]');
        const progressBar = wizard.querySelector('[data-wizard-progressbar]');
        const currentLabel = wizard.querySelector('[data-wizard-current-label]');
        const serverErrors = @json($errors->messages());
        let currentStep = 0;
        let mapOrigen = null;
        let markerOrigen = null;

        form.setAttribute('novalidate', 'novalidate');

        const unidadSelect = document.getElementById('unidad_id');
        const precioLabel = document.getElementById('precio-label');

        function updatePrecioLabel() {
            if (!unidadSelect || !precioLabel) return;

            const selectedOption = unidadSelect.options[unidadSelect.selectedIndex];
            const unidad = selectedOption && selectedOption.value ? selectedOption.textContent.trim().toLowerCase() : '';
            precioLabel.textContent = unidad ? `Precio por ${unidad} *` : 'Precio *';
        }

        if (unidadSelect) {
            unidadSelect.addEventListener('change', updatePrecioLabel);
            updatePrecioLabel();
        }

        const fieldStepMap = {
            nombre: 0,
            categoria_id: 0,
            tipo_cultivo_id: 0,
            unidad_id: 0,
            stock: 0,
            precio: 0,
            finca: 1,
            fecha_siembra: 1,
            fecha_cosecha: 1,
            tratamientos_utilizados: 1,
            observaciones_trazabilidad: 1,
            descripcion: 2,
            origen: 2,
            latitud_origen: 2,
            longitud_origen: 2,
            departamento_origen: 2,
            municipio_origen: 2,
            provincia_origen: 2,
            ciudad_origen: 2,
            referencia_ubicacion: 2,
            certificados: 3,
            certificados_adicionales: 3,
            imagenes: 4,
            imagenes_eliminar: 4,
        };

        function normalizeFieldName(name) {
            return name.replace(/\[\]$/, '').replace(/\.\d+$/, '').split('.')[0].replace(/\[.*$/, '');
        }

        function findControlByErrorKey(key) {
            const normalized = normalizeFieldName(key);
            return form.querySelector(`[name="${normalized}"]`) ||
                form.querySelector(`[name="${normalized}[]"]`) ||
                form.querySelector(`[name^="${normalized}["]`);
        }

        function getStepForField(key) {
            return fieldStepMap[normalizeFieldName(key)] ?? 0;
        }

        function fieldLabel(control) {
            const group = control.closest('.form-group') || control.closest('.card-body');
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
            const holder = control.closest('.input-group') || control;
            const feedback = holder.nextElementSibling;
            if (feedback && feedback.classList.contains('wizard-field-error')) {
                feedback.textContent = '';
            }
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

            indicators.forEach((indicator, stepIndex) => {
                indicator.classList.toggle('is-active', stepIndex === currentStep);
                indicator.classList.toggle('is-complete', stepIndex < currentStep);
                indicator.setAttribute('aria-current', stepIndex === currentStep ? 'step' : 'false');

                const status = indicator.querySelector('[data-wizard-step-status]');
                if (status) {
                    if (stepIndex < currentStep) {
                        status.textContent = 'Completado';
                    } else if (stepIndex === currentStep) {
                        status.textContent = 'En progreso';
                    } else {
                        status.textContent = 'Pendiente';
                    }
                }
            });

            prevBtn.disabled = currentStep === 0;
            nextBtn.classList.toggle('d-none', currentStep === steps.length - 1);
            submitBtn.classList.toggle('d-none', currentStep !== steps.length - 1);
            errorSummary.classList.add('d-none');

            if (progressBar) {
                progressBar.style.width = `${((currentStep + 1) / steps.length) * 100}%`;
            }

            if (currentLabel) {
                currentLabel.textContent = `Paso ${currentStep + 1} de ${steps.length}`;
            }

            if (currentStep === 2) {
                setTimeout(function() {
                    initMapOrigen();
                    if (mapOrigen) {
                        mapOrigen.invalidateSize();
                    }
                }, 180);
            }

            wizard.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }

        indicators.forEach(indicator => {
            indicator.addEventListener('click', function() {
                const target = Number(this.getAttribute('data-wizard-go-to'));
                if (target <= currentStep || validateUntil(target)) {
                    showStep(target);
                }
            });
        });

        prevBtn.addEventListener('click', () => showStep(currentStep - 1));
        nextBtn.addEventListener('click', () => {
            if (validateStep(currentStep)) {
                showStep(currentStep + 1);
            }
        });

        form.addEventListener('submit', function(event) {
            for (let index = 0; index < steps.length; index++) {
                if (!validateStep(index, false)) {
                    event.preventDefault();
                    showStep(index);
                    validateStep(index, true);
                    return;
                }
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

        wizard.querySelectorAll('.cert-mode').forEach(input => {
            input.addEventListener('change', function() {
                const panel = document.getElementById('cert_file_' + this.dataset.cert);
                if (panel) {
                    panel.classList.toggle('is-active', this.value === '0' && this.checked);
                }
            });
        });

        function initMapOrigen() {
            if (mapOrigen || typeof L === 'undefined') return;

            const initialLat = {{ old('latitud_origen', $organico->latitud_origen ?? -17.7833) }};
            const initialLng = {{ old('longitud_origen', $organico->longitud_origen ?? -63.1821) }};
            const initialZoom = {{ isset($organico) && $organico->latitud_origen ? 12 : 6 }};

            mapOrigen = L.map('map-origen').setView([initialLat, initialLng], initialZoom);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: 'OpenStreetMap'
            }).addTo(mapOrigen);

            @if (isset($organico) && $organico->latitud_origen && $organico->longitud_origen)
                markerOrigen = L.marker([initialLat, initialLng]).addTo(mapOrigen);
            @endif

            mapOrigen.on('click', function(e) {
                const lat = e.latlng.lat.toFixed(7);
                const lng = e.latlng.lng.toFixed(7);

                if (markerOrigen) {
                    markerOrigen.setLatLng([lat, lng]);
                } else {
                    markerOrigen = L.marker([lat, lng]).addTo(mapOrigen);
                }

                document.getElementById('latitud_origen').value = lat;
                document.getElementById('longitud_origen').value = lng;
                document.getElementById('origen').value = 'Lat: ' + lat + ' - Lng: ' + lng;
                obtenerInformacionOrigen(lat, lng);
            });
        }

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
                        const info = data.data;
                        document.getElementById('departamento_origen').value = info.departamento || '';
                        document.getElementById('municipio_origen').value = info.municipio || '';
                        document.getElementById('provincia_origen').value = info.provincia || '';
                        document.getElementById('ciudad_origen').value = info.ciudad || '';

                        ciudadTexto.textContent = info.ciudad || info.municipio || 'No disponible';

                        const direccion = [];
                        if (info.municipio) direccion.push(info.municipio);
                        if (info.provincia) direccion.push('Provincia ' + info.provincia);
                        if (info.departamento) direccion.push(info.departamento);
                        direccion.push('Bolivia');

                        const direccionCompleta = direccion.join(', ');
                        direccionTexto.textContent = direccionCompleta || 'No disponible';
                        document.getElementById('origen').value = direccionCompleta || document.getElementById('origen').value;
                    } else {
                        ciudadTexto.textContent = 'No disponible';
                        direccionTexto.textContent = 'No disponible';
                    }
                })
                .catch(() => {
                    ciudadTexto.textContent = 'Error';
                    direccionTexto.textContent = 'Error';
                });
        }

        const input = document.getElementById('imagenes-input');
        const uploadZone = wizard.querySelector('[data-upload-zone]');
        const previewContainer = document.getElementById('preview-container');
        const countDisplay = document.getElementById('imagenes-count');
        const imagenesActuales = {{ isset($organico) && $organico->imagenes ? $organico->imagenes->count() : 0 }};
        let imagenesAEliminar = [];
        let selectedFiles = [];

        function refreshInputFiles() {
            const dataTransfer = new DataTransfer();
            selectedFiles.forEach(item => dataTransfer.items.add(item.file));
            input.files = dataTransfer.files;
        }

        function liveExistingImagesCount() {
            return imagenesActuales - imagenesAEliminar.length;
        }

        wizard.querySelectorAll('.eliminar-imagen').forEach(btn => {
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
            const total = liveExistingImagesCount() + selectedFiles.length;
            countDisplay.textContent = 'Total de imágenes: ' + total + ' / 3';
            uploadZone.classList.toggle('has-files', total > 0);

            if (total > 3) {
                countDisplay.className = 'maquinaria-upload-count text-danger mt-2';
                countDisplay.textContent += ' (Excede el límite de 3 imágenes)';
                input.setCustomValidity('Puedes publicar máximo 3 imágenes.');
            } else {
                countDisplay.className = 'maquinaria-upload-count text-muted mt-2';
                input.setCustomValidity('');
            }
        }

        function renderSelectedFiles() {
            previewContainer.innerHTML = '';

            selectedFiles.forEach((item, index) => {
                const col = document.createElement('div');
                col.className = 'col-md-3 mb-3';
                col.setAttribute('data-file-id', item.id);
                col.innerHTML = `
                    <div class="position-relative">
                        <img src="${item.url}" alt="Preview ${index + 1}" class="img-thumbnail"
                            style="width: 100%; height: 150px; object-fit: cover; border-radius: 8px;">
                        <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1 eliminar-preview"
                            data-file-id="${item.id}">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                `;
                previewContainer.appendChild(col);
            });

            refreshInputFiles();
            updateCount();
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
                if (!file.type.startsWith('image/')) return;

                selectedFiles.push({
                    id: Date.now() + '-' + index + '-' + file.name,
                    file: file,
                    url: URL.createObjectURL(file),
                });
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

        const firstServerError = Object.keys(serverErrors)[0];
        if (firstServerError) {
            showStep(getStepForField(firstServerError));
            errorSummary.textContent = 'Hay datos por corregir antes de guardar el producto orgánico.';
            errorSummary.classList.remove('d-none');
        } else {
            showStep(0);
        }

        if (currentStep === 2) {
            initMapOrigen();
        }

            updateCount();
    });
</script>
