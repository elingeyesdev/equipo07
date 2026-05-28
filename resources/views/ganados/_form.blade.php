@csrf
<link rel="stylesheet" href="{{ asset('css/ganado-form.css') }}">
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

<div class="agro-wizard-page">
    <div class="agro-wizard">
        <div class="agro-wizard__shell">
            
            <div class="agro-wizard__hero">
                <div>
                    <span class="agro-wizard__eyebrow">Marketplace</span>
                    <h2 class="agro-wizard__title">
                        <i><i class="fas fa-chart-line"></i></i> 
                        {{ isset($ganado) ? 'Editar Publicación' : 'Publicar Inventario Ganadero' }}
                    </h2>
                    <p class="agro-wizard__subtitle">Completa los datos paso a paso. No podrás avanzar si faltan datos requeridos.</p>
                </div>
                <span class="agro-wizard__badge" id="stepCounterBadge">Paso 1 de 4</span>
            </div>

            <div class="agro-wizard__progress">
                <div class="agro-wizard__step-indicator is-active" id="ind-0">
                    <div class="agro-wizard__step-icon"><span class="agro-wizard__step-number">1</span></div>
                    <div class="agro-wizard__step-copy">
                        <span class="agro-wizard__step-title">Categoría y Especie</span>
                        <span class="agro-wizard__step-status">En progreso</span>
                    </div>
                </div>
                <div class="agro-wizard__step-indicator" id="ind-1">
                    <div class="agro-wizard__step-icon"><span class="agro-wizard__step-number">2</span></div>
                    <div class="agro-wizard__step-copy">
                        <span class="agro-wizard__step-title">Detalles y Ficha</span>
                        <span class="agro-wizard__step-status">Pendiente</span>
                    </div>
                </div>
                <div class="agro-wizard__step-indicator" id="ind-2">
                    <div class="agro-wizard__step-icon"><span class="agro-wizard__step-number">3</span></div>
                    <div class="agro-wizard__step-copy">
                        <span class="agro-wizard__step-title">Precio y Peso</span>
                        <span class="agro-wizard__step-status">Pendiente</span>
                    </div>
                </div>
                <div class="agro-wizard__step-indicator" id="ind-3">
                    <div class="agro-wizard__step-icon"><span class="agro-wizard__step-number">4</span></div>
                    <div class="agro-wizard__step-copy">
                        <span class="agro-wizard__step-title">Sanidad y Ubicación</span>
                        <span class="agro-wizard__step-status">Pendiente</span>
                    </div>
                </div>
            </div>

            <div class="agro-wizard__progressbar">
                <span id="progressBar" style="width: 0%;"></span>
            </div>

            <div class="agro-wizard__content">
                
                <div class="agro-wizard-step is-active" id="step-0">
                    <div class="agro-wizard-step-header">
                        <div>
                            <h3>1. Definición Inicial</h3>
                            <small>Selecciona cómo y qué tipo de ganado deseas comercializar.</small>
                        </div>
                    </div>
                    <div class="agro-wizard-step-body">
                        
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
                            <select name="tipo_animal_id" id="tipo_animal_id" class="form-select">
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
                                <select name="raza_id" id="raza_id" class="form-select">
                                    <option value="">Selecciona la raza...</option>
                                </select>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="agro-wizard-step" id="step-1">
                    <div class="agro-wizard-step-header">
                        <div>
                            <h3>2. Ficha y Detalles</h3>
                            <small>Proporciona información descriptiva sobre tu publicación.</small>
                        </div>
                    </div>
                    <div class="agro-wizard-step-body">
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
                                <select name="sexo" id="sexo" class="form-select">
                                    <option value="">Selecciona el sexo...</option>
                                </select>
                            </div>

                            <div class="col-md-12 mb-3 p-3 rounded" style="background: #f8fafc; border: 1px solid #e2e8f0;" id="div_edad">
                                <label>Edad (Promedio o Exacta) <span class="text-danger">*</span></label>
                                <div class="d-flex gap-3">
                                    <input type="number" name="edad_valor" id="edad_valor" class="form-control w-75" placeholder="Ej: 15" value="{{ old('edad_valor', $ganado->caracteristica->edad_valor ?? '') }}">
                                    <select name="unidad_edad" id="unidad_edad" class="form-select w-25">
                                        <option value="Meses" {{ old('unidad_edad', $ganado->caracteristica->unidad_edad ?? '') == 'Meses' ? 'selected' : '' }}>Meses</option>
                                        <option value="Años" {{ old('unidad_edad', $ganado->caracteristica->unidad_edad ?? '') == 'Años' ? 'selected' : '' }}>Años</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-12 mb-3">
                                <label>Descripción Completa <span class="text-danger">*</span></label>
                                <textarea name="descripcion" id="descripcion" class="form-control" rows="4" placeholder="Agrega todos los detalles relevantes, historial, etc.">{{ old('descripcion', $ganado->caracteristica->descripcion ?? '') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="agro-wizard-step" id="step-2">
                    <div class="agro-wizard-step-header">
                        <div>
                            <h3>3. Valor y Pesaje</h3>
                            <small>Fija tus precios y medidas para la venta.</small>
                        </div>
                    </div>
                    <div class="agro-wizard-step-body">
                        
                        <div class="p-3 mb-4 rounded" style="background: #f8fafc; border: 1px solid #e2e8f0;" id="div_peso_wrapper">
                            <h6 class="font-weight-bold mb-3 text-success"><i class="fas fa-balance-scale"></i> Información de Peso</h6>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label>Peso (Promedio/Actual) <span class="text-danger">*</span></label>
                                    <input type="number" name="peso_actual" id="peso_actual" class="form-control" step="0.01" value="{{ old('peso_actual', $ganado->datoProductivo->peso_actual ?? '') }}">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label>Unidad <span class="text-danger">*</span></label>
                                    <select name="unidad_peso" id="unidad_peso" class="form-select">
                                        <option value="kg" {{ old('unidad_peso', $ganado->datoProductivo->unidad_peso ?? '') == 'kg' ? 'selected' : '' }}>Kilogramos (kg)</option>
                                        <option value="lb" {{ old('unidad_peso', $ganado->datoProductivo->unidad_peso ?? '') == 'lb' ? 'selected' : '' }}>Libras (lb)</option>
                                        <option value="@" {{ old('unidad_peso', $ganado->datoProductivo->unidad_peso ?? '') == '@' ? 'selected' : '' }}>Arrobas (@)</option>
                                    </select>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label>Tipo de Pesaje <span class="text-danger">*</span></label>
                                    <select name="tipo_pesaje" id="tipo_pesaje" class="form-select">
                                        <option value="Peso Vivo Estimado" {{ old('tipo_pesaje', $ganado->datoProductivo->tipo_pesaje ?? '') == 'Peso Vivo Estimado' ? 'selected' : '' }}>Peso Vivo (Estimado)</option>
                                        <option value="Peso en Báscula" {{ old('tipo_pesaje', $ganado->datoProductivo->tipo_pesaje ?? '') == 'Peso en Báscula' ? 'selected' : '' }}>Peso Real (Báscula)</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row p-3 rounded" style="background: #f0fdf4; border: 1px solid #bbf7d0;">
                            <div class="col-md-6 mb-3">
                                <label>Precio Base <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text font-weight-bold text-success">Bs</span>
                                    <input type="number" name="precio" id="precio" class="form-control font-weight-bold" step="0.01" value="{{ old('precio', $ganado->precio ?? '') }}" placeholder="0.00">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Forma de Cobro <span class="text-danger">*</span></label>
                                <select name="forma_cobro" id="forma_cobro" class="form-select">
                                    <option value="">Selecciona cobro...</option>
                                </select>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="agro-wizard-step" id="step-3">
                    <div class="agro-wizard-step-header">
                        <div>
                            <h3>4. Multimedia y Ubicación</h3>
                            <small>Sube imágenes claras y define la ubicación de la propiedad.</small>
                        </div>
                    </div>
                    <div class="agro-wizard-step-body">

                        <div class="p-3 mb-4 rounded" style="background: #f8fafc; border: 2px solid #e2e8f0;" id="div_sanidad">
                            <div class="form-check custom-switch">
                                <input type="checkbox" class="form-check-input" id="has_sanity" name="has_sanity" value="1" {{ old('has_sanity', isset($ganado) && $ganado->datosSanitarios()->where('has_sanity', true)->exists() ? 'checked' : '') }}>
                                <label class="form-check-label font-weight-bold ml-2" for="has_sanity" id="label_sanity">¿Cuenta con cartillas de sanidad al día?</label>
                            </div>
                            
                            <div id="sanidad_upload_zone" class="mt-3" style="display: none;">
                                <label for="pdf-input" class="agro-upload-zone bg-white">
                                    <span class="agro-upload-zone__icon" style="background: #fff; color: var(--agro); border: 1px solid #bbf7d0;"><i class="fas fa-file-pdf"></i></span>
                                    <div class="d-flex flex-column text-left">
                                        <strong class="text-dark">Documentos Sanitarios (Opcional)</strong>
                                        <small class="text-muted">Sube el certificado PDF.</small>
                                    </div>
                                    <span class="agro-upload-zone__cta ml-auto">Seleccionar PDF</span>
                                </label>
                                <input type="file" name="documento_pdf" id="pdf-input" class="agro-upload-input" accept=".pdf">
                                <span id="pdf-file-name" class="text-success small mt-1 d-block"></span>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label>Fotografías del Ganado</label>
                            <label for="imagenes-input" class="agro-upload-zone">
                                <span class="agro-upload-zone__icon"><i class="fas fa-image"></i></span>
                                <div class="d-flex flex-column text-left">
                                    <strong class="text-dark">Sube hasta 5 fotos</strong>
                                    <small class="text-muted">Formatos: JPG, PNG. Máximo 10MB por archivo.</small>
                                </div>
                                <span class="agro-upload-zone__cta ml-auto">Explorar Galería</span>
                            </label>
                            <input type="file" name="imagenes[]" id="imagenes-input" class="agro-upload-input" accept="image/*" multiple>
                            <div id="preview-container" class="row mt-3"></div>
                        </div>

                        <div>
                            <label>Ubicación de la Propiedad <span class="text-danger">*</span></label>
                            <div id="map" style="height: 350px; border-radius: 8px; border: 1px solid rgba(44, 91, 31, 0.14);"></div>
                            <input type="hidden" name="latitud" id="latitud" value="{{ old('latitud', $ganado->latitud ?? '') }}">
                            <input type="hidden" name="longitud" id="longitud" value="{{ old('longitud', $ganado->longitud ?? '') }}">
                            <input type="hidden" name="departamento" id="departamento">
                            <input type="hidden" name="municipio" id="municipio">
                            <input type="hidden" name="provincia" id="provincia">
                            <input type="hidden" name="ciudad" id="ciudad">
                            <input type="text" id="ubicacion" name="ubicacion" class="form-control mt-2" readonly placeholder="La ubicación se autocompletará...">
                        </div>

                    </div>
                </div>

            </div>

            <div class="agro-wizard__actions">
                <button type="button" class="btn-agro-outline" id="btnPrev" style="visibility: hidden;">
                    <i class="fas fa-chevron-left mr-2"></i> Atrás
                </button>
                <div>
                    <button type="button" class="btn-agro-primary" id="btnNext">
                        Siguiente <i class="fas fa-chevron-right ml-2"></i>
                    </button>
                    <button type="submit" class="btn-agro-primary" id="btnSubmit" style="display: none; background: #1e293b;">
                        Publicar Ganado <i class="fas fa-check-circle ml-2"></i>
                    </button>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    const dbTipos = @json($tipo_animals);
    const dbRazas = @json($razas);
    const dbPurposes = {
        'Bovino': ['Carne', 'Lechería', 'Doble Propósito', 'Reproducción / Padrillos'],
        'Equino': ['Trabajo', 'Deporte / Exhibición', 'Reproducción / Padrillos'],
        'Ovino': ['Carne', 'Lana', 'Lechería', 'Reproducción / Padrillos'],
        'Porcino': ['Carne', 'Reproducción / Padrillos'],
        'Caprino': ['Carne', 'Lechería', 'Reproducción / Padrillos'],
    };
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let step = 0;
    const totalSteps = 4;
    const stepsDOM = document.querySelectorAll('.agro-wizard-step');
    const indsDOM = document.querySelectorAll('.agro-wizard__step-indicator');
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
    
    // 1. Lógica Visual Wizard
    function updateWizard() {
        stepsDOM.forEach((s, i) => s.classList.toggle('is-active', i === step));
        
        indsDOM.forEach((ind, i) => {
            ind.classList.remove('is-active', 'is-complete');
            const status = ind.querySelector('.agro-wizard__step-status');
            if (i < step) { ind.classList.add('is-complete'); status.textContent = "Completado"; status.style.color = "#fff"; }
            else if (i === step) { ind.classList.add('is-active'); status.textContent = "En progreso"; status.style.color = "var(--agro-700)"; }
            else { status.textContent = "Pendiente"; status.style.color = "#667466"; }
        });

        progressBar.style.width = ((step) / (totalSteps - 1)) * 100 + '%';
        document.getElementById('stepCounterBadge').textContent = `Paso ${step + 1} de 4`;

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
            if (mod !== 'Genetica' && (!selectSexo.value || !document.getElementById('edad_valor').value)) valid = false;
        } else if (step === 2) {
            if (!document.getElementById('precio').value || !selectCobro.value) valid = false;
            if (mod !== 'Genetica' && !document.getElementById('peso_actual').value) valid = false;
        } else if (step === 3) {
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

    // 3. Lógica de Negocio (React -> Vanilla JS)
    
    // Modalidad Click
    document.querySelectorAll('.modality-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.modality-btn').forEach(b => b.classList.remove('selected'));
            this.classList.add('selected');
            const val = this.dataset.value;
            inputMod.value = val;
            
            // Reset cascading states
            divEspecie.style.display = 'block';
            selectTipo.value = '';
            divPropRaza.style.display = 'none';
            
            // Configurar paso 2 y 3 segun modalidad
            if (val === 'Individual') { inputStock.value = 1; inputStock.setAttribute('readonly', true); }
            else { inputStock.value = ''; inputStock.removeAttribute('readonly'); }

            divSexo.style.display = val === 'Genetica' ? 'none' : 'block';
            divEdad.style.display = val === 'Genetica' ? 'none' : 'block';
            divPeso.style.display = val === 'Genetica' ? 'none' : 'block';
            selectCobro.disabled = val === 'Genetica';
            
            document.getElementById('label_sanity').textContent = val === 'Genetica' ? '¿Cuenta con Certificado Genético?' : '¿Cuenta con cartillas de sanidad al día?';

            buildCobroOptions(val);
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
                <select name="tipo_genetica" id="tipo_genetica" class="form-select">
                    <option value="">Selecciona el tipo...</option>
                    <option value="Semen">Pajuelas de Semen</option>
                    <option value="Embrion">Embriones</option>
                </select>
            `;
            document.getElementById('tipo_genetica').addEventListener('change', function() {
                buildCobroOptions(mod, this.value);
                checkStepValid();
            });
        } else {
            let options = '<option value="">Selecciona el propósito...</option>';
            if (dbPurposes[typeName]) {
                dbPurposes[typeName].forEach(p => options += `<option value="${p}">${p}</option>`);
            }
            colProposito.innerHTML = `
                <label>Propósito del Ganado <span class="text-danger">*</span></label>
                <select name="proposito" id="proposito" class="form-select">${options}</select>
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

    function buildCobroOptions(mod, geneticType = null) {
        selectCobro.innerHTML = '<option value="">Selecciona cobro...</option>';
        let opts = [];
        if (mod === 'Genetica') {
            opts = geneticType === 'Semen' ? ['Por Dosis/Pajuela'] : ['Por Embrión'];
        } else if (mod === 'Individual') {
            opts = ['Por cabeza', 'Por kilo vivo'];
        } else if (mod === 'Lote') {
            opts = ['Por cabeza', 'Por kilo vivo', 'Por lote completo'];
        }
        opts.forEach(o => selectCobro.innerHTML += `<option value="${o}">${o}</option>`);
    }

    // Toggle Sanity PDF
    document.getElementById('has_sanity').addEventListener('change', function() {
        document.getElementById('sanidad_upload_zone').style.display = this.checked ? 'block' : 'none';
    });

    document.getElementById('pdf-input').addEventListener('change', function() {
        if(this.files[0]) document.getElementById('pdf-file-name').textContent = 'Archivo: ' + this.files[0].name;
    });

    // Inicializar visual
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
                        col.innerHTML = `
                            
                                ![Preview](${event.target.result})
                                
                                    ${file.name}
                                

                            

                        `;
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