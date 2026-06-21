@csrf
@php
    $edadModo = old(
        'edad_modo',
        isset($ganado) && $ganado?->caracteristica?->fecha_nacimiento ? 'fecha_nacimiento' : 'edad'
    );
    $fechaNacimiento = old(
        'fecha_nacimiento',
        isset($ganado) && $ganado?->caracteristica?->fecha_nacimiento
            ? $ganado->caracteristica->fecha_nacimiento->format('Y-m-d')
            : ''
    );
    $initialGanadoForm = [
        'modalidad' => old('modalidad', $ganado->modalidad ?? ''),
        'tipo_animal_id' => old('tipo_animal_id', $ganado->tipo_animal_id ?? ''),
        'raza_id' => old('raza_id', $ganado->raza_id ?? ''),
        'proposito' => old('proposito', $ganado->proposito ?? ''),
        'tipo_genetica' => old('tipo_genetica', $ganado->tipo_genetica ?? ''),
        'sexo' => old('sexo', $ganado->caracteristica->sexo ?? ''),
        'forma_cobro' => old('forma_cobro', $ganado->datoComercial->forma_cobro ?? 'Contacto directo'),
    ];
    $propositosFormulario = ($propositos ?? collect())->pluck('nombre')->values();
    if ($propositosFormulario->isEmpty()) {
        $propositosFormulario = collect([
            'Carne',
            'Lechería',
            'Doble Propósito',
            'Reproducción / Padrillos',
        ]);
    }
    $datoSanitario = isset($ganado) && $ganado ? $ganado->datoSanitario : null;
@endphp
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

<style>
    .ganado-age-panel,
    .ganado-info-panel {
        background: #f8fbf8;
        border: 1px solid rgba(46, 171, 91, 0.14);
        border-radius: 12px;
    }

    .modality-btn {
        align-items: center;
        background: #fff;
        border: 1px solid rgba(46, 171, 91, 0.16);
        border-radius: 10px;
        cursor: pointer;
        display: flex;
        gap: 0.8rem;
        height: 100%;
        min-height: 86px;
        padding: 1rem;
        transition: all 0.18s ease;
    }

    .modality-btn:hover {
        border-color: rgba(46, 171, 91, 0.42);
        box-shadow: 0 12px 26px rgba(31, 42, 27, 0.07);
        transform: translateY(-1px);
    }

    .modality-btn.selected {
        background: #f5fbf2;
        border-color: rgba(46, 171, 91, 0.62);
        box-shadow: 0 16px 32px rgba(46, 171, 91, 0.12);
    }

    .modality-btn .icon {
        align-items: center;
        background: rgba(46, 171, 91, 0.1);
        border-radius: 10px;
        color: #238647;
        display: inline-flex;
        flex: 0 0 42px;
        font-size: 1.35rem;
        height: 42px;
        justify-content: center;
        width: 42px;
    }

    .modality-btn .label {
        color: #263522;
        font-weight: 700;
        line-height: 1.2;
    }
</style>

<div class="maquinaria-wizard" data-ganado-wizard>
    <div class="maquinaria-wizard__shell">
            
            <div class="maquinaria-wizard__hero">
                <div>
                    <span class="maquinaria-wizard__eyebrow">Registro de ganado</span>
                    <h3 class="maquinaria-wizard__title mb-1">
                        <i class="fas fa-paw mr-2"></i>
                        {{ isset($ganado) ? 'Editar Publicación' : 'Publicar Inventario Ganadero' }}
                    </h3>
                    <p class="maquinaria-wizard__subtitle mb-0">Completa los datos paso a paso. No podrás avanzar si faltan datos requeridos.</p>
                </div>
                <span class="badge badge-success maquinaria-wizard__badge" id="stepCounterBadge">Paso 1 de 5</span>
            </div>

            <div class="maquinaria-wizard__progress" role="tablist" aria-label="Pasos del registro de ganado">
                <div class="maquinaria-wizard__step-indicator is-active" id="ind-0" data-ganado-step-indicator="0">
                    <span class="maquinaria-wizard__step-number">1</span>
                    <span class="maquinaria-wizard__step-icon"><i class="fas fa-paw"></i></span>
                    <div class="maquinaria-wizard__step-copy">
                        <span class="maquinaria-wizard__step-title">Categoría y especie</span>
                        <span class="maquinaria-wizard__step-description">Modalidad, especie, propósito y raza.</span>
                        <span class="maquinaria-wizard__step-status">En progreso</span>
                    </div>
                </div>
                <div class="maquinaria-wizard__step-indicator" id="ind-1" data-ganado-step-indicator="1">
                    <span class="maquinaria-wizard__step-number">2</span>
                    <span class="maquinaria-wizard__step-icon"><i class="fas fa-clipboard-list"></i></span>
                    <div class="maquinaria-wizard__step-copy">
                        <span class="maquinaria-wizard__step-title">Ficha</span>
                        <span class="maquinaria-wizard__step-description">Título, stock, sexo, edad y descripción.</span>
                        <span class="maquinaria-wizard__step-status">Pendiente</span>
                    </div>
                </div>
                <div class="maquinaria-wizard__step-indicator" id="ind-2" data-ganado-step-indicator="2">
                    <span class="maquinaria-wizard__step-number">3</span>
                    <span class="maquinaria-wizard__step-icon"><i class="fas fa-balance-scale"></i></span>
                    <div class="maquinaria-wizard__step-copy">
                        <span class="maquinaria-wizard__step-title">Precio y peso</span>
                        <span class="maquinaria-wizard__step-description">Pesaje y precio de referencia.</span>
                        <span class="maquinaria-wizard__step-status">Pendiente</span>
                    </div>
                </div>
                <div class="maquinaria-wizard__step-indicator" id="ind-3" data-ganado-step-indicator="3">
                    <span class="maquinaria-wizard__step-number">4</span>
                    <span class="maquinaria-wizard__step-icon"><i class="fas fa-certificate"></i></span>
                    <div class="maquinaria-wizard__step-copy">
                        <span class="maquinaria-wizard__step-title">Sanidad y certificados</span>
                        <span class="maquinaria-wizard__step-description">Vacunas, certificados y documentos.</span>
                        <span class="maquinaria-wizard__step-status">Pendiente</span>
                    </div>
                </div>
                <div class="maquinaria-wizard__step-indicator" id="ind-4" data-ganado-step-indicator="4">
                    <span class="maquinaria-wizard__step-number">5</span>
                    <span class="maquinaria-wizard__step-icon"><i class="fas fa-map-marker-alt"></i></span>
                    <div class="maquinaria-wizard__step-copy">
                        <span class="maquinaria-wizard__step-title">Fotos y ubicación</span>
                        <span class="maquinaria-wizard__step-description">Fotografías y localización.</span>
                        <span class="maquinaria-wizard__step-status">Pendiente</span>
                    </div>
                </div>
            </div>

            <div class="maquinaria-wizard__progressbar" aria-hidden="true">
                <span id="progressBar" style="width: 0%;"></span>
            </div>

            <div class="maquinaria-wizard__content">
                
                <section class="card card-outline card-success shadow-sm mb-4 maquinaria-wizard-step is-active" id="step-0">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="card-title mb-0"><i class="fas fa-paw mr-2"></i>Definición inicial</h3>
                            <small>Selecciona cómo y qué tipo de ganado deseas comercializar.</small>
                        </div>
                        <span class="badge badge-success">Paso 1 de 5</span>
                    </div>
                    <div class="card-body">
                        
                        <div class="mb-4">
                            <label>Modalidad de Venta <span class="text-danger">*</span></label>
                            <div class="row">
                                <div class="col-4">
                                    <div class="modality-btn" data-value="Lote">
                                        <span class="icon">🐄</span><span class="label">Lote Comercial</span>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="modality-btn" data-value="Individual">
                                        <span class="icon">🐂</span><span class="label">Animal Individual</span>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="modality-btn" data-value="Genetica">
                                        <span class="icon">🧬</span><span class="label">Material Genético</span>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="modalidad" id="modalidad" value="{{ old('modalidad', $ganado->modalidad ?? '') }}" required>
                        </div>

                        <div class="mb-4" id="div_especie" style="display: none;">
                            <label>Especie Principal <span class="text-danger">*</span></label>
                            <select name="tipo_animal_id" id="tipo_animal_id" class="form-control">
                                <option value="">Selecciona la especie...</option>
                                @foreach ($tipo_animals as $item)
                                    <option value="{{ $item->id }}" data-name="{{ $item->nombre }}" {{ old('tipo_animal_id', $ganado->tipo_animal_id ?? '') == $item->id ? 'selected' : '' }}>
                                        {{ $item->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="row" id="div_proposito_raza" style="display: none;">
                            <div class="col-md-6 mb-3" id="col_proposito">
                                </div>
                            <div class="col-md-6 mb-3">
                                <label>Raza <span class="text-danger">*</span></label>
                                <select name="raza_id" id="raza_id" class="form-control">
                                    <option value="">Selecciona la raza...</option>
                                </select>
                            </div>
                        </div>

                    </div>
                </section>

                <section class="card card-outline card-success shadow-sm mb-4 maquinaria-wizard-step" id="step-1">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="card-title mb-0"><i class="fas fa-clipboard-list mr-2"></i>Ficha y detalles</h3>
                            <small>Proporciona información descriptiva sobre tu publicación.</small>
                        </div>
                        <span class="badge badge-success">Paso 2 de 5</span>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label>Título de la Publicación <span class="text-danger">*</span></label>
                                <input type="text" name="nombre" id="nombre" class="form-control" value="{{ old('nombre', $ganado->nombre ?? '') }}" placeholder="Ej: Lote de 15 Vacas Lecheras Holstein...">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>Stock / Cantidad <span class="text-danger">*</span></label>
                                <input type="number" name="stock" id="stock" class="form-control" value="{{ old('stock', $ganado->stock ?? '') }}">
                            </div>

                            <div class="col-md-6 mb-3" id="div_sexo">
                                <label>Sexo <span class="text-danger">*</span></label>
                                <select name="sexo" id="sexo" class="form-control">
                                    <option value="">Selecciona el sexo...</option>
                                </select>
                            </div>

                            <div class="col-md-12 mb-3 p-3 ganado-age-panel" id="div_edad">
                                <label>Edad o fecha de nacimiento <span class="text-danger">*</span></label>
                                <div class="custom-control custom-switch mb-3">
                                    <input type="checkbox" class="custom-control-input" id="edad_modo_switch"
                                        {{ $edadModo === 'fecha_nacimiento' ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="edad_modo_switch">
                                        Usar fecha de nacimiento
                                    </label>
                                </div>
                                <input type="hidden" name="edad_modo" id="edad_modo" value="{{ $edadModo }}">

                                <div class="d-flex gap-3" id="edad_manual_group">
                                    <input type="number" name="edad_valor" id="edad_valor" class="form-control w-75"
                                        placeholder="Ej: 15" min="0"
                                        value="{{ old('edad_valor', $ganado->caracteristica->edad_valor ?? '') }}">
                                    <select name="unidad_edad" id="unidad_edad" class="form-control w-25">
                                        <option value="Meses" {{ old('unidad_edad', $ganado->caracteristica->unidad_edad ?? '') == 'Meses' ? 'selected' : '' }}>Meses</option>
                                        <option value="Años" {{ old('unidad_edad', $ganado->caracteristica->unidad_edad ?? '') == 'Años' ? 'selected' : '' }}>Años</option>
                                    </select>
                                </div>

                                <div id="fecha_nacimiento_group">
                                    <input type="date" name="fecha_nacimiento" id="fecha_nacimiento"
                                        class="form-control" max="{{ now()->format('Y-m-d') }}"
                                        value="{{ $fechaNacimiento }}">
                                    <small class="form-text text-muted" id="edad_calculada_texto">
                                        La edad se calculará automáticamente al guardar.
                                    </small>
                                </div>
                            </div>

                            <div class="col-md-12 mb-3">
                                <label>Descripción Completa <span class="text-danger">*</span></label>
                                <textarea name="descripcion" id="descripcion" class="form-control" rows="4" placeholder="Agrega todos los detalles relevantes, historial, etc.">{{ old('descripcion', $ganado->caracteristica->descripcion ?? '') }}</textarea>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="card card-outline card-success shadow-sm mb-4 maquinaria-wizard-step" id="step-2">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="card-title mb-0"><i class="fas fa-balance-scale mr-2"></i>Valor y pesaje</h3>
                            <small>Fija tus precios y medidas para la venta.</small>
                        </div>
                        <span class="badge badge-success">Paso 3 de 5</span>
                    </div>
                    <div class="card-body">
                        
                        <div class="p-3 mb-4 ganado-info-panel" id="div_peso_wrapper">
                            <h6 class="font-weight-bold mb-3 text-success"><i class="fas fa-balance-scale"></i> Información de Peso</h6>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label>Peso (Promedio/Actual) <span class="text-danger">*</span></label>
                                    <input type="number" name="peso_actual" id="peso_actual" class="form-control" step="0.01" value="{{ old('peso_actual', $ganado->datoProductivo->peso_actual ?? '') }}">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label>Unidad <span class="text-danger">*</span></label>
                                    <select name="unidad_peso" id="unidad_peso" class="form-control">
                                        <option value="kg" {{ old('unidad_peso', $ganado->datoProductivo->unidad_peso ?? '') == 'kg' ? 'selected' : '' }}>Kilogramos (kg)</option>
                                        <option value="lb" {{ old('unidad_peso', $ganado->datoProductivo->unidad_peso ?? '') == 'lb' ? 'selected' : '' }}>Libras (lb)</option>
                                        <option value="@" {{ old('unidad_peso', $ganado->datoProductivo->unidad_peso ?? '') == '@' ? 'selected' : '' }}>Arrobas (@)</option>
                                    </select>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label>Tipo de Pesaje <span class="text-danger">*</span></label>
                                    <select name="tipo_pesaje" id="tipo_pesaje" class="form-control">
                                        <option value="Peso Vivo Estimado" {{ old('tipo_pesaje', $ganado->datoProductivo->tipo_pesaje ?? '') == 'Peso Vivo Estimado' ? 'selected' : '' }}>Peso Vivo (Estimado)</option>
                                        <option value="Peso en Báscula" {{ old('tipo_pesaje', $ganado->datoProductivo->tipo_pesaje ?? '') == 'Peso en Báscula' ? 'selected' : '' }}>Peso Real (Báscula)</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row p-3 ganado-info-panel">
                            <div class="col-md-6 mb-3">
                                <label>Precio Base <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text font-weight-bold text-success">Bs</span>
                                    <input type="number" name="precio" id="precio" class="form-control font-weight-bold" step="0.01" value="{{ old('precio', $ganado->precio ?? '') }}" placeholder="0.00">
                                </div>
                            </div>
                            <input type="hidden" name="forma_cobro" id="forma_cobro" value="{{ old('forma_cobro', $ganado->datoComercial->forma_cobro ?? 'Contacto directo') }}">
                        </div>

                    </div>
                </section>

                <section class="card card-outline card-success shadow-sm mb-4 maquinaria-wizard-step" id="step-3">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="card-title mb-0"><i class="fas fa-certificate mr-2"></i>Certificados y sanidad</h3>
                            <small>Registra vacunas, certificados, identificación y reconocimientos disponibles.</small>
                        </div>
                        <span class="badge badge-success">Paso 4 de 5</span>
                    </div>
                    <div class="card-body">
                        @include('ganados.partials._sanidad_certificados', ['datoSanitario' => $datoSanitario])
                    </div>
                </section>

                <section class="card card-outline card-success shadow-sm mb-4 maquinaria-wizard-step" id="step-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="card-title mb-0"><i class="fas fa-map-marker-alt mr-2"></i>Multimedia y ubicación</h3>
                            <small>Sube imágenes claras y define la ubicación de la propiedad.</small>
                        </div>
                        <span class="badge badge-success">Paso 5 de 5</span>
                    </div>
                    <div class="card-body">
                        <div class="mb-4">
                            <label>Fotografías del Ganado</label>
                            <label for="imagenes-input" class="maquinaria-upload-zone">
                                <span class="maquinaria-upload-zone__icon"><i class="fas fa-image"></i></span>
                                <div class="d-flex flex-column text-left">
                                    <strong class="text-dark">Sube hasta 5 fotos</strong>
                                    <small class="text-muted">Formatos: JPG, PNG. Máximo 10MB por archivo.</small>
                                </div>
                                <span class="maquinaria-upload-zone__cta ml-auto">Explorar Galería</span>
                            </label>
                            <input type="file" name="imagenes[]" id="imagenes-input" class="maquinaria-upload-input" accept="image/*" multiple>
                            <div id="preview-container" class="row mt-3"></div>
                        </div>

                        <div>
                            <label>Ubicación de la Propiedad <span class="text-danger">*</span></label>
                            <div id="map" class="maquinaria-wizard__map" style="height: 350px;"></div>
                            <input type="hidden" name="latitud" id="latitud" value="{{ old('latitud', $ganado->latitud ?? '') }}">
                            <input type="hidden" name="longitud" id="longitud" value="{{ old('longitud', $ganado->longitud ?? '') }}">
                            <input type="hidden" name="departamento" id="departamento">
                            <input type="hidden" name="municipio" id="municipio">
                            <input type="hidden" name="provincia" id="provincia">
                            <input type="hidden" name="ciudad" id="ciudad">
                            <input type="text" id="ubicacion" name="ubicacion" class="form-control mt-2" readonly placeholder="La ubicación se autocompletará...">
                        </div>

                    </div>
                </section>

            </div>

            <div class="maquinaria-wizard__actions">
		                <button type="button" class="btn btn-outline-agro" id="btnPrev" style="visibility: hidden;">
		                    <i class="fas fa-chevron-left mr-2"></i> Atrás
		                </button>
		                <div class="maquinaria-wizard__action-group">
		                    <button type="button" class="btn btn-outline-secondary" data-draft-save>
		                        <i class="fas fa-save mr-2"></i> Guardar borrador
		                    </button>
		                    <button type="button" class="btn btn-outline-danger" data-draft-discard>
		                        <i class="fas fa-trash-alt mr-2"></i> Descartar borrador
		                    </button>
		                    <button type="button" class="btn btn-success" id="btnNext">
		                        Siguiente <i class="fas fa-chevron-right ml-2"></i>
		                    </button>
                    <button type="submit" class="btn btn-success" id="btnSubmit" style="display: none;">
                        Publicar Ganado <i class="fas fa-check-circle ml-2"></i>
                    </button>
                </div>
	            </div>
	            <small class="text-success d-none mt-2 d-block" data-draft-status></small>

	        </div>
</div>

<script>
    const dbTipos = @json($tipo_animals);
    const dbRazas = @json($razas);
    const dbPurposes = @json($propositosFormulario);
    const initialGanadoForm = @json($initialGanadoForm);
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let step = 0;
    let maxVisitedStep = 0;
    const totalSteps = 5;
    const stepsDOM = document.querySelectorAll('.maquinaria-wizard-step');
    const indsDOM = document.querySelectorAll('.maquinaria-wizard__step-indicator');
    const btnNext = document.getElementById('btnNext');
    const btnPrev = document.getElementById('btnPrev');
    const btnSubmit = document.getElementById('btnSubmit');
    const progressBar = document.getElementById('progressBar');
    
    // DOM Elements - Formularios
    const inputMod = document.getElementById('modalidad');
    const divEspecie = document.getElementById('div_especie');
    const divPropRaza = document.getElementById('div_proposito_raza');
    const colProposito = document.getElementById('col_proposito');
    const selectTipo = document.getElementById('tipo_animal_id');
    const selectRaza = document.getElementById('raza_id');
    const selectSexo = document.getElementById('sexo');
    const divSexo = document.getElementById('div_sexo');
	    const selectCobro = document.getElementById('forma_cobro');
	    const divPeso = document.getElementById('div_peso_wrapper');
	    const divEdad = document.getElementById('div_edad');
	    const inputStock = document.getElementById('stock');
	    const edadModo = document.getElementById('edad_modo');
	    const edadModoSwitch = document.getElementById('edad_modo_switch');
	    const edadManualGroup = document.getElementById('edad_manual_group');
	    const fechaNacimientoGroup = document.getElementById('fecha_nacimiento_group');
	    const fechaNacimientoInput = document.getElementById('fecha_nacimiento');
	    const edadCalculadaTexto = document.getElementById('edad_calculada_texto');
    
    // 1. Lógica Visual Wizard
    function updateWizard() {
        maxVisitedStep = Math.max(maxVisitedStep, step);
        stepsDOM.forEach((s, i) => s.classList.toggle('is-active', i === step));
        
        indsDOM.forEach((ind, i) => {
            ind.classList.remove('is-active', 'is-complete');
            const status = ind.querySelector('.maquinaria-wizard__step-status');
            if (i < step) {
                ind.classList.add('is-complete');
                status.textContent = "Completado";
            } else if (i === step) {
                ind.classList.add('is-active');
                status.textContent = "En progreso";
            } else {
                status.textContent = "Pendiente";
            }
            ind.classList.toggle('is-available', i <= maxVisitedStep);
        });

        progressBar.style.width = ((step) / (totalSteps - 1)) * 100 + '%';
        document.getElementById('stepCounterBadge').textContent = `Paso ${step + 1} de ${totalSteps}`;

        btnPrev.style.visibility = step === 0 ? 'hidden' : 'visible';
        if (step === totalSteps - 1) {
            btnNext.style.display = 'none';
            btnSubmit.style.display = 'inline-flex';
            setTimeout(() => { if(window.map) map.invalidateSize(); }, 200); // Fix Leaflet map render in hidden div
        } else {
            btnNext.style.display = 'inline-flex';
            btnSubmit.style.display = 'none';
        }
        checkStepValid();
    }

    // 2. Validación por Paso
    function checkStepValid() {
        let valid = true;
        const mod = inputMod.value;
        const esp = selectTipo.value;

        if (step === 0) {
            if (!mod || !esp || !selectRaza.value) valid = false;
            if (mod === 'Genetica' && !document.getElementById('tipo_genetica')?.value) valid = false;
            if (mod !== 'Genetica' && !document.getElementById('proposito')?.value) valid = false;
        } else if (step === 1) {
            if (!document.getElementById('nombre').value || !inputStock.value || !document.getElementById('descripcion').value) valid = false;
	            if (mod !== 'Genetica' && !selectSexo.value) valid = false;
	            if (mod !== 'Genetica' && edadModo.value === 'fecha_nacimiento' && !fechaNacimientoInput.value) valid = false;
	            if (mod !== 'Genetica' && edadModo.value !== 'fecha_nacimiento' && !document.getElementById('edad_valor').value) valid = false;
        } else if (step === 2) {
            if (!document.getElementById('precio').value) valid = false;
            if (mod !== 'Genetica' && !document.getElementById('peso_actual').value) valid = false;
        } else if (step === 4) {
            if (!document.getElementById('latitud').value) valid = false;
        }

        btnNext.disabled = !valid;
        btnSubmit.disabled = !valid;
    }

    // Attach listeners a todos los inputs para validación reactiva
    document.querySelectorAll('input, select, textarea').forEach(el => {
        el.addEventListener('input', checkStepValid);
        el.addEventListener('change', checkStepValid);
    });

	    btnNext.addEventListener('click', () => { if (!btnNext.disabled) { step++; updateWizard(); } });
	    btnPrev.addEventListener('click', () => { if (step > 0) { step--; updateWizard(); } });

        indsDOM.forEach((indicator, index) => {
            indicator.addEventListener('click', function() {
                if (index <= maxVisitedStep) {
                    step = index;
                    updateWizard();
                }
            });
        });

	    function updateEdadMode() {
	        const usarFecha = edadModoSwitch.checked;
	        edadModo.value = usarFecha ? 'fecha_nacimiento' : 'edad';
	        edadManualGroup.style.display = usarFecha ? 'none' : 'flex';
	        fechaNacimientoGroup.style.display = usarFecha ? 'block' : 'none';
	        document.getElementById('edad_valor').required = !usarFecha && inputMod.value !== 'Genetica';
	        document.getElementById('unidad_edad').required = !usarFecha && inputMod.value !== 'Genetica';
	        fechaNacimientoInput.required = usarFecha && inputMod.value !== 'Genetica';
	        updateEdadCalculada();
	        checkStepValid();
	    }

	    function updateEdadCalculada() {
	        if (!fechaNacimientoInput.value) {
	            edadCalculadaTexto.textContent = 'La edad se calculará automáticamente al guardar.';
	            return;
	        }

	        const birthDate = new Date(fechaNacimientoInput.value + 'T00:00:00');
	        const today = new Date();
	        let months = (today.getFullYear() - birthDate.getFullYear()) * 12 + today.getMonth() - birthDate.getMonth();
	        if (today.getDate() < birthDate.getDate()) months -= 1;
	        months = Math.max(months, 0);
	        edadCalculadaTexto.textContent = `Edad aproximada calculada: ${months} ${months === 1 ? 'mes' : 'meses'}.`;
	    }

	    edadModoSwitch.addEventListener('change', updateEdadMode);
	    fechaNacimientoInput.addEventListener('change', updateEdadCalculada);

	    function syncModalityLayout(val) {
	        if (!val) return;

	        document.querySelectorAll('.modality-btn').forEach(b => {
	            b.classList.toggle('selected', b.dataset.value === val);
	        });

	        divEspecie.style.display = 'block';
	        divSexo.style.display = val === 'Genetica' ? 'none' : 'block';
	        divEdad.style.display = val === 'Genetica' ? 'none' : 'block';
	        divPeso.style.display = val === 'Genetica' ? 'none' : 'block';
	        selectCobro.value = selectCobro.value || 'Contacto directo';
            const labelSanity = document.getElementById('label_sanity');
            if (labelSanity) {
                labelSanity.textContent = val === 'Genetica'
                    ? '¿Cuenta con certificado genético o genealogía?'
                    : '¿Cuenta con certificados o datos sanitarios del animal?';
            }
	        updateEdadMode();
	    }

	    function applySavedDynamicState(values) {
	        const mod = values.modalidad || inputMod.value;

	        if (!mod) return;

	        inputMod.value = mod;
	        syncModalityLayout(mod);

	        if (values.tipo_animal_id) {
	            selectTipo.value = values.tipo_animal_id;
	            selectTipo.dispatchEvent(new Event('change', { bubbles: true }));
	        }

	        if (values.raza_id) {
	            selectRaza.value = values.raza_id;
	        }

	        if (values.tipo_genetica && document.getElementById('tipo_genetica')) {
	            document.getElementById('tipo_genetica').value = values.tipo_genetica;
	        }

	        if (values.proposito && document.getElementById('proposito')) {
	            document.getElementById('proposito').value = values.proposito;
	            buildSexoOptions(mod, values.proposito);
	        }

	        if (values.sexo) {
	            selectSexo.value = values.sexo;
	        }

	        selectCobro.value = values.forma_cobro || 'Contacto directo';

	        updateEdadMode();
	        checkStepValid();
	    }

	    inputMod.closest('form')?.addEventListener('form-draft:restored', function(event) {
	        applySavedDynamicState(event.detail?.draft || {});
	    });

    // 3. Lógica de Negocio (React -> Vanilla JS)
    
    // Modalidad Click
    document.querySelectorAll('.modality-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.modality-btn').forEach(b => b.classList.remove('selected'));
            this.classList.add('selected');
	            const val = this.dataset.value;
	            inputMod.value = val;
	            syncModalityLayout(val);

	            // Reset cascading states
	            selectTipo.value = '';
	            divPropRaza.style.display = 'none';
            
            // Configurar paso 2 y 3 segun modalidad
            if (val === 'Individual') { inputStock.value = 1; inputStock.setAttribute('readonly', true); }
            else { inputStock.value = ''; inputStock.removeAttribute('readonly'); }

	            checkStepValid();
	        });
    });

    // Especie Change
    selectTipo.addEventListener('change', function() {
        const typeName = this.options[this.selectedIndex].dataset.name;
        const mod = inputMod.value;
        
        divPropRaza.style.display = 'flex';
        
        // Render Proposito o Genetica
        if (mod === 'Genetica') {
            colProposito.innerHTML = `
                <label>Tipo de Material Genético <span class="text-danger">*</span></label>
                <select name="tipo_genetica" id="tipo_genetica" class="form-control">
                    <option value="">Selecciona el tipo...</option>
                    <option value="Semen">Pajuelas de Semen</option>
                    <option value="Embrion">Embriones</option>
                </select>
            `;
            document.getElementById('tipo_genetica').addEventListener('change', function() {
                checkStepValid();
            });
        } else {
	            let options = '<option value="">Selecciona el propósito...</option>';
	            dbPurposes.forEach(p => options += `<option value="${p}">${p}</option>`);
            colProposito.innerHTML = `
                <label>Propósito del Ganado <span class="text-danger">*</span></label>
                <select name="proposito" id="proposito" class="form-control">${options}</select>
            `;
            document.getElementById('proposito').addEventListener('change', function() {
                buildSexoOptions(mod, this.value);
                checkStepValid();
            });
        }

        // Render Razas de la BD
        selectRaza.innerHTML = '<option value="">Selecciona la raza...</option>';
        const filtradas = dbRazas.filter(r => r.tipo_animal_id == this.value);
        filtradas.forEach(r => selectRaza.innerHTML += `<option value="${r.id}">${r.nombre}</option>`);
        selectRaza.innerHTML += `<option value="Cruce/Mestizo">Cruce / Mestizo</option>`;
        
        checkStepValid();
    });

    function buildSexoOptions(mod, purpose) {
        selectSexo.innerHTML = '<option value="">Selecciona el sexo...</option>';
        if (mod === 'Genetica') return;
        if (mod === 'Individual') {
            selectSexo.innerHTML += '<option value="Macho">Macho</option><option value="Hembra">Hembra</option>';
        } else if (mod === 'Lote' && purpose === 'Lechería') {
            selectSexo.innerHTML += '<option value="Hembra">Hembra</option>';
        } else {
            selectSexo.innerHTML += '<option value="Macho">Macho</option><option value="Hembra">Hembra</option><option value="Mixto">Mixto</option>';
        }
    }

    // Toggle Sanity details
    const hasSanity = document.getElementById('has_sanity');
    const sanidadDetailZone = document.getElementById('sanidad_detail_zone');
    if (hasSanity && sanidadDetailZone) {
	    hasSanity.addEventListener('change', function() {
	        sanidadDetailZone.style.display = this.checked ? 'block' : 'none';
	    });
	    hasSanity.dispatchEvent(new Event('change', { bubbles: true }));
    }

    document.getElementById('pdf-input')?.addEventListener('change', function() {
        if(this.files[0]) document.getElementById('pdf-file-name').textContent = 'Archivo: ' + this.files[0].name;
    });

	    // Inicializar visual
	    applySavedDynamicState(initialGanadoForm);
	    updateEdadMode();
	    updateWizard();

    // --- LÓGICA DE PREVISUALIZACIÓN DE IMÁGENES ---
    const imagenesInput = document.getElementById('imagenes-input');
    const previewContainer = document.getElementById('preview-container');

    if (imagenesInput && previewContainer) {
        imagenesInput.addEventListener('change', function(e) {
            // Limpiar el contenedor antes de mostrar las nuevas imágenes
            previewContainer.innerHTML = ''; 
            
            // Tomar los archivos y limitar visualmente a 5 (como dice el mockup)
            const files = Array.from(e.target.files).slice(0, 5);

            if (files.length > 0) {
                files.forEach((file) => {
                    // Validar que sea realmente una imagen
                    if (!file.type.startsWith('image/')) return;

                    const reader = new FileReader();
                    
                    reader.onload = function(event) {
                        // Crear el elemento visual para la imagen
                        const col = document.createElement('div');
                        col.className = 'col-md-4 col-sm-6 mb-3'; // Diseño de cuadrícula

                        const card = document.createElement('div');
                        card.className = 'card card-outline card-success h-100 mb-0 maquinaria-image-card';

                        const image = document.createElement('img');
                        image.src = event.target.result;
                        image.alt = `Vista previa de ${file.name}`;
                        image.className = 'card-img-top maquinaria-image-card__image';

                        const caption = document.createElement('span');
                        caption.className = 'card-footer bg-white p-2 text-muted small font-weight-bold text-truncate';
                        caption.textContent = file.name;

                        card.appendChild(image);
                        card.appendChild(caption);
                        col.appendChild(card);
                        previewContainer.appendChild(col);
                    };
                    
                    // Leer el archivo como URL de datos
                    reader.readAsDataURL(file);
                });
            }
        });
    }

	});
	</script>
@include('components.form-draft', [
    'draftKey' => isset($ganado) && $ganado ? 'ganados.edit.' . $ganado->id : 'ganados.create',
])

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    var map = L.map('map').setView([-17.7833, -63.1821], 6);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
    var marker;
    map.on('click', function(e) {
        var lat = e.latlng.lat.toFixed(7);
        var lng = e.latlng.lng.toFixed(7);
        if (marker) marker.setLatLng([lat, lng]); else marker = L.marker([lat, lng]).addTo(map);
        document.getElementById('latitud').value = lat; document.getElementById('longitud').value = lng;
        document.getElementById('ubicacion').value = "Cargando coordenadas: " + lat + ", " + lng;
        
        // Peticion a Nominatim tal como la tenias (Fetch geocodificacion API)
        fetch('/api/geocodificacion?latitud=' + lat + '&longitud=' + lng)
            .then(r => r.json())
            .then(data => {
                if(data.success && data.data) {
                    document.getElementById('departamento').value = data.data.departamento || '';
                    document.getElementById('municipio').value = data.data.municipio || '';
                    document.getElementById('provincia').value = data.data.provincia || '';
                    document.getElementById('ciudad').value = data.data.ciudad || '';
                    let dir = [data.data.municipio, data.data.provincia ? 'Provincia '+data.data.provincia : null, data.data.departamento, 'Bolivia'].filter(Boolean);
                    document.getElementById('ubicacion').value = dir.join(', ');
                }
            });
    });
</script>
