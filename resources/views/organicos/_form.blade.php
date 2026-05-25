@csrf

@php
    $categoriaSeleccionada = old('categoria_id', $organico->categoria_id ?? optional($categorias->first())->id);
    $registrosCertificados = collect($organico?->certificadoRegistros ?? [])->keyBy('certificado_organico_id');
    $obligatorios = $certificados->where('es_obligatorio', true);
    $opcionales = $certificados->where('es_obligatorio', false);
@endphp

<input type="hidden" name="categoria_id" value="{{ $categoriaSeleccionada }}">

<style>
    .organico-wizard__progress {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: .5rem;
        margin-bottom: 1rem;
    }

    .organico-wizard__step-indicator {
        border: 1px solid #d7ead8;
        border-radius: 8px;
        background: #fff;
        color: #55705a;
        font-size: .85rem;
        padding: .65rem .5rem;
        text-align: center;
    }

    .organico-wizard__step-indicator.is-active {
        background: #28a745;
        border-color: #28a745;
        color: #fff;
        font-weight: 600;
    }

    .organico-wizard__step {
        display: none;
    }

    .organico-wizard__step.is-active {
        display: block;
    }

    .cert-file-panel {
        display: none;
    }

    .cert-file-panel.is-active {
        display: block;
    }

    @media (max-width: 768px) {
        .organico-wizard__progress {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="organico-wizard">
    <div class="organico-wizard__progress" aria-label="Pasos del registro organico">
        <button type="button" class="organico-wizard__step-indicator is-active" data-step-target="0">1. Producto</button>
        <button type="button" class="organico-wizard__step-indicator" data-step-target="1">2. Trazabilidad</button>
        <button type="button" class="organico-wizard__step-indicator" data-step-target="2">3. Origen</button>
        <button type="button" class="organico-wizard__step-indicator" data-step-target="3">4. Certificados</button>
        <button type="button" class="organico-wizard__step-indicator" data-step-target="4">5. Imagenes</button>
    </div>

    <section class="organico-wizard__step is-active" data-step="0">
        <div class="card card-outline card-success shadow-sm mb-4">
            <div class="card-header">
                <h3 class="card-title mb-0"><i class="fas fa-leaf mr-2"></i>Datos del producto</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Nombre *</label>
                            <input type="text" name="nombre" class="form-control"
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

                        <div class="form-group mb-md-0">
                            <label>Stock *</label>
                            <input type="number" name="stock" class="form-control" placeholder="Cantidad disponible"
                                value="{{ old('stock', $organico->stock ?? 0) }}" required min="0">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Precio *</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Bs</span>
                                </div>
                                <input type="number" step="0.01" name="precio" class="form-control"
                                    placeholder="0.00" value="{{ old('precio', $organico->precio ?? 0) }}" required min="0">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="organico-wizard__step" data-step="1">
        <div class="card card-outline card-success shadow-sm mb-4">
            <div class="card-header">
                <h3 class="card-title mb-0"><i class="fas fa-route mr-2"></i>Trazabilidad del producto</h3>
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

    <section class="organico-wizard__step" data-step="2">
        <div class="card card-outline card-success shadow-sm mb-4">
            <div class="card-header">
                <h3 class="card-title mb-0"><i class="fas fa-map-marker-alt mr-2"></i>Descripcion y origen</h3>
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
                            <div id="map-origen" style="height: 320px; border-radius: 8px; border: 1px solid #e0e0e0;"></div>
                        </div>

                        <input type="text" id="origen" name="origen" class="form-control mb-3"
                            value="{{ old('origen', $organico->origen ?? '') }}" readonly>

                        <div id="info-origen" class="mt-1"
                            style="display: {{ isset($organico) && ($organico->origen ?? false) ? 'block' : 'none' }};">
                            <div class="card border">
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

    <section class="organico-wizard__step" data-step="3">
        <div class="card card-outline card-success shadow-sm mb-4">
            <div class="card-header">
                <h3 class="card-title mb-0"><i class="fas fa-certificate mr-2"></i>Certificados</h3>
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

    <section class="organico-wizard__step" data-step="4">
        <div class="card card-outline card-success shadow-sm mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title mb-0"><i class="fas fa-images mr-2"></i>Imagenes del producto</h3>
                <small class="text-muted">Maximo 3 imagenes por publicacion</small>
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
                <input type="file" name="imagenes[]" class="form-control" accept="image/*" multiple id="imagenes-input">
                <small class="form-text text-muted">Formatos permitidos: JPG, PNG, GIF. Tamano maximo por imagen: 2MB.</small>
                <div id="imagenes-count" class="text-muted mt-2"></div>
            </div>
        </div>
    </section>
</div>

<div class="d-flex justify-content-between mb-2">
    <a href="{{ url()->previous() !== url()->current() ? url()->previous() : route('organicos.index') }}"
        class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left mr-1"></i> Volver
    </a>
    <div>
        <button type="button" class="btn btn-outline-success mr-2" id="wizard-prev" disabled>
            <i class="fas fa-chevron-left mr-1"></i> Anterior
        </button>
        <button type="button" class="btn btn-success" id="wizard-next">
            Siguiente <i class="fas fa-chevron-right ml-1"></i>
        </button>
        <button class="btn btn-success d-none" id="wizard-submit">
            <i class="fas fa-save mr-1"></i> Guardar
        </button>
    </div>
</div>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const steps = Array.from(document.querySelectorAll('.organico-wizard__step'));
        const indicators = Array.from(document.querySelectorAll('[data-step-target]'));
        const prevBtn = document.getElementById('wizard-prev');
        const nextBtn = document.getElementById('wizard-next');
        const submitBtn = document.getElementById('wizard-submit');
        let currentStep = 0;
        let mapOrigen = null;

        function showStep(index) {
            currentStep = Math.max(0, Math.min(index, steps.length - 1));
            steps.forEach((step, stepIndex) => step.classList.toggle('is-active', stepIndex === currentStep));
            indicators.forEach((indicator, stepIndex) => indicator.classList.toggle('is-active', stepIndex === currentStep));
            prevBtn.disabled = currentStep === 0;
            nextBtn.classList.toggle('d-none', currentStep === steps.length - 1);
            submitBtn.classList.toggle('d-none', currentStep !== steps.length - 1);

            if (currentStep === 2) {
                setTimeout(initMapOrigen, 80);
            }
        }

        function validateCurrentStep() {
            const fields = Array.from(steps[currentStep].querySelectorAll('input, select, textarea'));
            for (const field of fields) {
                if (!field.checkValidity()) {
                    field.reportValidity();
                    return false;
                }
            }
            return true;
        }

        indicators.forEach(indicator => {
            indicator.addEventListener('click', function() {
                const target = Number(this.dataset.stepTarget);
                if (target <= currentStep || validateCurrentStep()) {
                    showStep(target);
                }
            });
        });

        prevBtn.addEventListener('click', () => showStep(currentStep - 1));
        nextBtn.addEventListener('click', () => {
            if (validateCurrentStep()) {
                showStep(currentStep + 1);
            }
        });

        document.querySelectorAll('.cert-mode').forEach(input => {
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
            let markerOrigen = null;

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
        const previewContainer = document.getElementById('preview-container');
        const countDisplay = document.getElementById('imagenes-count');
        const imagenesActuales = {{ isset($organico) && $organico->imagenes ? $organico->imagenes->count() : 0 }};
        let imagenesNuevas = 0;
        let imagenesAEliminar = [];
        let fileMap = new Map();

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
            countDisplay.textContent = 'Total de imagenes: ' + total + ' / 3';
            countDisplay.className = total > 3 ? 'text-danger mt-2' : 'text-muted mt-2';
        }

        input.addEventListener('change', function(e) {
            previewContainer.innerHTML = '';
            imagenesNuevas = 0;
            fileMap.clear();

            const files = Array.from(e.target.files);
            const maxFiles = 3 - (imagenesActuales - imagenesAEliminar.length);

            files.slice(0, maxFiles).forEach((file, index) => {
                if (!file.type.startsWith('image/')) return;

                const fileId = Date.now() + '-' + index;
                fileMap.set(fileId, file);

                const reader = new FileReader();
                reader.onload = function(event) {
                    const col = document.createElement('div');
                    col.className = 'col-md-3 mb-3';
                    col.setAttribute('data-file-id', fileId);
                    col.innerHTML = `
                        <div class="position-relative">
                            <img src="${event.target.result}" alt="Preview ${index + 1}" class="img-thumbnail"
                                style="width: 100%; height: 150px; object-fit: cover; border-radius: 8px;">
                            <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1 eliminar-preview"
                                data-file-id="${fileId}">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    `;
                    previewContainer.appendChild(col);
                    imagenesNuevas++;
                    updateCount();

                    col.querySelector('.eliminar-preview').addEventListener('click', function() {
                        fileMap.delete(this.getAttribute('data-file-id'));
                        const dataTransfer = new DataTransfer();
                        fileMap.forEach(file => dataTransfer.items.add(file));
                        input.files = dataTransfer.files;
                        col.remove();
                        imagenesNuevas--;
                        updateCount();
                    });
                };
                reader.readAsDataURL(file);
            });

            updateCount();
        });

        updateCount();
        showStep(0);
    });
</script>
