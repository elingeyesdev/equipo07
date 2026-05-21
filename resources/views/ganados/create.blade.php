@extends('layouts.adminlte')

@section('title', 'Publicar Ganado')

@section('content')
<style>
    /* Estilos del Stepper Moderno (Verde y Blanco) idéntico a tu versión */
    .form-step { display: none; animation: fadeIn 0.5s ease-in-out; }
    .form-step.active { display: block; }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .step-indicator {
        width: 40px; height: 40px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-weight: bold; border: 2px solid #e9ecef;
        background-color: white; color: #6c757d; transition: all 0.3s;
        z-index: 2;
    }
    .step-indicator.active {
        background-color: #28a745; color: white; border-color: #28a745;
        box-shadow: 0 0 10px rgba(40, 167, 69, 0.4); transform: scale(1.1);
    }
    .step-indicator.completed {
        background-color: #20c997; color: white; border-color: #20c997;
    }
    .progress-line {
        height: 4px; background-color: #e9ecef; flex-grow: 1; margin: 0 -10px; border-radius: 2px; z-index: 1;
    }
    .progress-line .fill {
        height: 100%; background-color: #28a745; width: 0%; transition: width 0.4s ease;
    }

    /* Tarjetas de Selección (Botones Gigantes) */
    .selection-card {
        border: 2px solid #e9ecef; border-radius: 15px; padding: 20px;
        cursor: pointer; transition: all 0.3s; background: white; text-align: center; height: 100%;
    }
    .selection-card:hover { border-color: #28a745; background-color: #f8fff9; }
    .selection-card.selected {
        border-color: #28a745; background-color: #eafaf1; box-shadow: 0 4px 15px rgba(40, 167, 69, 0.15);
    }
    
    .card-modern { border-radius: 15px; border: none; box-shadow: 0 4px 20px rgba(0,0,0,0.05); }
    .input-modern { border-radius: 10px; border: 1px solid #ced4da; padding: 10px 15px; }
    .input-modern:focus { border-color: #28a745; box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25); }
</style>

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-success font-weight-bold"><i class="fas fa-bullhorn"></i> Nueva Publicación</h2>
        <a href="{{ route('ganados.index') }}" class="btn btn-outline-secondary rounded-pill">Cancelar</a>
    </div>

    <div class="card card-modern mb-4 p-4">
        <div class="d-flex align-items-center justify-content-between position-relative">
            <div class="step-indicator active" id="indicator-1">1</div>
            <div class="progress-line"><div class="fill" id="line-1"></div></div>
            <div class="step-indicator" id="indicator-2">2</div>
            <div class="progress-line"><div class="fill" id="line-2"></div></div>
            <div class="step-indicator" id="indicator-3">3</div>
            <div class="progress-line"><div class="fill" id="line-3"></div></div>
            <div class="step-indicator" id="indicator-4">4</div>
        </div>
        <div class="d-flex justify-content-between mt-2 text-muted small font-weight-bold">
            <span>Categoría</span>
            <span>Detalles</span>
            <span>Precio</span>
            <span>Sanidad</span>
        </div>
    </div>

    <form action="/api/ganados" method="POST" enctype="multipart/form-data" id="ganadoForm" class="card card-modern p-4 p-md-5">
        @csrf
        <input type="hidden" name="modality" id="modalityInput" value="">
        <input type="hidden" name="species" id="speciesInput" value="">

        <div class="form-step active" id="step-1">
            <h4 class="text-success font-weight-bold mb-4"><i class="fas fa-tags"></i> 1. Selecciona la Modalidad</h4>
            
            <div class="row mb-5">
                <div class="col-md-4 mb-3">
                    <div class="selection-card" id="card-lote" onclick="selectModality('Lote')">
                        <h1 class="mb-3">🐄</h1>
                        <h5 class="font-weight-bold">Venta por Lote</h5>
                        <p class="text-muted small mb-0">Grupo de animales</p>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="selection-card" id="card-indiv" onclick="selectModality('Individual')">
                        <h1 class="mb-3">🐂</h1>
                        <h5 class="font-weight-bold">Animal Individual</h5>
                        <p class="text-muted small mb-0">Un solo animal</p>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="selection-card" id="card-gen" onclick="selectModality('Genetica')">
                        <h1 class="mb-3">🧬</h1>
                        <h5 class="font-weight-bold">Material Genético</h5>
                        <p class="text-muted small mb-0">Semen o Embriones</p>
                    </div>
                </div>
            </div>

            <div id="especie-container" class="d-none">
                <h5 class="font-weight-bold mb-3">Especie del Animal</h5>
                <div class="d-flex flex-wrap gap-2 mb-4" id="botones-especie">
                    <button type="button" class="btn btn-outline-success rounded-pill px-4 btn-especie" onclick="selectSpecies(this, 'Bovino')">Bovino</button>
                    <button type="button" class="btn btn-outline-success rounded-pill px-4 btn-especie" onclick="selectSpecies(this, 'Equino')">Equino</button>
                    <button type="button" class="btn btn-outline-success rounded-pill px-4 btn-especie" onclick="selectSpecies(this, 'Ovino')">Ovino</button>
                    <button type="button" class="btn btn-outline-success rounded-pill px-4 btn-especie" onclick="selectSpecies(this, 'Porcino')">Porcino</button>
                </div>

                <div class="row d-none" id="detalles-categoria">
                    <div class="col-md-6 form-group" id="caja-proposito">
                        <label class="font-weight-bold">Propósito Principal *</label>
                        <select name="purpose" id="purposeInput" class="form-control input-modern">
                            <option value="">Seleccione...</option>
                        </select>
                    </div>
                    <div class="col-md-6 form-group d-none" id="caja-genetica">
                        <label class="font-weight-bold">Tipo de Material *</label>
                        <select name="geneticType" id="geneticTypeInput" class="form-control input-modern">
                            <option value="">Seleccione...</option>
                            <option value="Semen">Semen</option>
                            <option value="Embrion">Embrión</option>
                        </select>
                    </div>
                    <div class="col-md-6 form-group">
                        <label class="font-weight-bold">Raza Principal *</label>
                        <select name="breed" id="breedInput" class="form-control input-modern">
                            <option value="">Seleccione raza...</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-step" id="step-2">
            <h4 class="text-success font-weight-bold mb-4"><i class="fas fa-info-circle"></i> 2. Detalles de la Publicación</h4>
            
            <div class="form-group mb-4">
                <label class="font-weight-bold">Título de la publicación *</label>
                <input type="text" name="title" id="titleInput" class="form-control input-modern" placeholder="Ej: Lote de Novillos Brahman" required>
            </div>

            <div class="row">
                <div class="col-md-6 form-group mb-4">
                    <label class="font-weight-bold">Cantidad / Stock *</label>
                    <input type="number" name="stock" id="stockInput" class="form-control input-modern" min="1" required>
                </div>
                <div class="col-md-6 form-group mb-4" id="caja-sexo">
                    <label class="font-weight-bold">Sexo *</label>
                    <select name="sex" id="sexInput" class="form-control input-modern">
                        <option value="">Seleccione...</option>
                        <option value="Macho">Macho</option>
                        <option value="Hembra">Hembra</option>
                        <option value="Mixto">Mixto</option>
                    </select>
                </div>
            </div>

            <div class="card bg-light border-0 mb-4" id="caja-edad">
                <div class="card-body">
                    <label class="font-weight-bold">Edad del animal (o promedio del lote) *</label>
                    <div class="d-flex align-items-center">
                        <input type="number" name="ageValue" id="ageValueInput" class="form-control input-modern w-50 mr-2" placeholder="Ej: 15">
                        <select name="ageUnit" class="form-control input-modern w-50">
                            <option value="Meses">Meses</option>
                            <option value="Años">Años</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="font-weight-bold">Descripción Adicional</label>
                <textarea name="description" class="form-control input-modern" rows="3" placeholder="Dietas, historial, etc..."></textarea>
            </div>
        </div>

        <div class="form-step" id="step-3">
            <h4 class="text-success font-weight-bold mb-4"><i class="fas fa-dollar-sign"></i> 3. Precio y Pesaje</h4>
            
            <div class="card border-0 bg-light mb-4 p-3" id="caja-peso">
                <h5 class="font-weight-bold"><i class="fas fa-weight"></i> Información Física</h5>
                <div class="row mt-3">
                    <div class="col-md-4 form-group">
                        <label>Peso Actual *</label>
                        <input type="number" name="weight" id="weightInput" class="form-control input-modern" placeholder="Ej: 250">
                    </div>
                    <div class="col-md-4 form-group">
                        <label>Unidad</label>
                        <select name="weightUnit" class="form-control input-modern">
                            <option value="kg">Kilogramos (kg)</option>
                            <option value="lb">Libras (lb)</option>
                            <option value="@">Arroba (@)</option>
                        </select>
                    </div>
                    <div class="col-md-4 form-group">
                        <label>Tipo</label>
                        <select name="weightType" class="form-control input-modern">
                            <option value="Vivo Estimado">Vivo Estimado</option>
                            <option value="En Báscula">En Báscula</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row align-items-center">
                <div class="col-md-6 form-group">
                    <label class="font-weight-bold">Precio Base (Bs) *</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-white border-right-0"><i class="fas fa-dollar-sign text-success"></i></span>
                        </div>
                        <input type="number" name="price" id="priceInput" class="form-control input-modern border-left-0 pl-0 text-success font-weight-bold text-lg" placeholder="0.00" required>
                    </div>
                </div>
                <div class="col-md-6 form-group">
                    <label class="font-weight-bold">Tipo de Cobro *</label>
                    <select name="chargeType" id="chargeTypeInput" class="form-control input-modern" required>
                        <option value="">Seleccione...</option>
                        </select>
                </div>
            </div>
        </div>

        <div class="form-step" id="step-4">
            <h4 class="text-success font-weight-bold mb-4"><i class="fas fa-shield-alt"></i> 4. Sanidad y Multimedia</h4>

            <div class="card border-success mb-4" id="sanidad-card">
                <div class="card-body">
                    <div class="custom-control custom-switch custom-switch-lg mb-2">
                        <input type="checkbox" class="custom-control-input" id="switch_sanidad" name="hasSanity">
                        <label class="custom-control-label font-weight-bold h5 text-success" for="switch_sanidad" id="label-sanidad">¿Cuenta con Sanidad al día?</label>
                    </div>
                    
                    <div id="caja_archivo_sanidad" class="mt-3 d-none p-3 bg-light rounded border border-success">
                        <label class="font-weight-bold"><i class="fas fa-file-pdf text-danger"></i> Sube el documento (PDF/Foto) *</label>
                        <input type="file" name="sanityFiles[]" id="sanityFileInput" class="form-control-file" accept=".pdf,image/*">
                    </div>
                </div>
            </div>

            <div class="form-group mb-4">
                <label class="font-weight-bold"><i class="fas fa-camera"></i> Galería Multimedia *</label>
                <div class="border p-4 rounded text-center bg-light">
                    <p class="text-muted mb-2">Sube al menos 1 fotografía clara del animal o lote.</p>
                    <input type="file" name="media[]" id="mediaInput" class="form-control-file mx-auto" accept="image/*,video/mp4" multiple required>
                </div>
            </div>
        </div>

        <hr class="my-4">
        
        <div class="d-flex justify-content-between">
            <button type="button" class="btn btn-secondary px-4 py-2 font-weight-bold rounded-pill" id="btn-prev" onclick="changeStep(-1)" style="display: none;">
                <i class="fas fa-arrow-left"></i> Atrás
            </button>
            <button type="button" class="btn btn-success px-5 py-2 font-weight-bold rounded-pill ml-auto" id="btn-next" onclick="changeStep(1)" disabled>
                Siguiente <i class="fas fa-arrow-right"></i>
            </button>
            <button type="submit" class="btn btn-dark px-5 py-2 font-weight-bold rounded-pill ml-auto" id="btn-submit" style="display: none;" disabled>
                Finalizar y Publicar <i class="fas fa-check"></i>
            </button>
        </div>
    </form>
</div>

<script>
    // --- DATOS SIMULADOS (Reemplazar por variables de Blade $especies, $razas) ---
    const dataCat = {
        'Bovino': {
            propositos: ['Carne', 'Lechería', 'Doble Propósito'],
            razas: ['Brahman', 'Nelore', 'Angus']
        },
        'Equino': {
            propositos: ['Trabajo', 'Deporte'],
            razas: ['Cuarto de Milla', 'Paso Fino']
        }
    };

    // --- VARIABLES Y ELEMENTOS ---
    let currentStep = 1;
    const totalSteps = 4;
    const btnNext = document.getElementById('btn-next');
    const btnSubmit = document.getElementById('btn-submit');

    // --- FUNCIONES DEL STEPPER ---
    function changeStep(direction) {
        document.getElementById(`step-${currentStep}`).classList.remove('active');
        document.getElementById(`indicator-${currentStep}`).classList.remove('active');
        
        if(direction === 1) {
            document.getElementById(`indicator-${currentStep}`).classList.add('completed');
            document.getElementById(`line-${currentStep}`).style.width = '100%';
        } else {
            document.getElementById(`indicator-${currentStep-1}`).classList.remove('completed');
            document.getElementById(`line-${currentStep-1}`).style.width = '0%';
        }

        currentStep += direction;

        document.getElementById(`step-${currentStep}`).classList.add('active');
        document.getElementById(`indicator-${currentStep}`).classList.add('active');

        document.getElementById('btn-prev').style.display = currentStep === 1 ? 'none' : 'block';
        
        if(currentStep === totalSteps) {
            btnNext.style.display = 'none';
            btnSubmit.style.display = 'block';
        } else {
            btnNext.style.display = 'block';
            btnSubmit.style.display = 'none';
        }
        validarPaso();
    }

    // --- LÓGICA DE NEGOCIO ---
    function selectModality(tipo) {
        document.getElementById('modalityInput').value = tipo;
        document.querySelectorAll('.selection-card').forEach(c => c.classList.remove('selected'));
        if(tipo === 'Lote') document.getElementById('card-lote').classList.add('selected');
        if(tipo === 'Individual') document.getElementById('card-indiv').classList.add('selected');
        if(tipo === 'Genetica') document.getElementById('card-gen').classList.add('selected');

        document.getElementById('especie-container').classList.remove('d-none');
        
        // Reglas de negocio según modalidad
        const stockInput = document.getElementById('stockInput');
        const selectCobro = document.getElementById('chargeTypeInput');

        if(tipo === 'Genetica') {
            document.getElementById('caja-proposito').classList.add('d-none');
            document.getElementById('caja-genetica').classList.remove('d-none');
            document.getElementById('caja-edad').classList.add('d-none');
            document.getElementById('caja-sexo').classList.add('d-none');
            document.getElementById('caja-peso').classList.add('d-none');
            
            document.getElementById('label-sanidad').innerText = '¿Cuenta con Registro Genealógico?';
            
            stockInput.value = ''; stockInput.readOnly = false;
            selectCobro.innerHTML = '<option value="Por Dosis/Embrión">Por Dosis / Embrión</option>';
        } else {
            document.getElementById('caja-proposito').classList.remove('d-none');
            document.getElementById('caja-genetica').classList.add('d-none');
            document.getElementById('caja-edad').classList.remove('d-none');
            document.getElementById('caja-sexo').classList.remove('d-none');
            document.getElementById('caja-peso').classList.remove('d-none');
            
            document.getElementById('label-sanidad').innerText = '¿Cuenta con Sanidad al día?';

            if(tipo === 'Individual') {
                stockInput.value = 1; stockInput.readOnly = true;
                selectCobro.innerHTML = '<option value="">Seleccione...</option><option value="Por Cabeza">Por Cabeza</option><option value="Por Kilo">Por Kilo Vivo</option>';
            } else {
                stockInput.value = ''; stockInput.readOnly = false;
                selectCobro.innerHTML = '<option value="">Seleccione...</option><option value="Por Cabeza">Por Cabeza</option><option value="Por Kilo">Por Kilo Vivo</option><option value="Por Lote">Por Lote Completo</option>';
            }
        }
        validarPaso();
    }

    function selectSpecies(btn, especie) {
        document.getElementById('speciesInput').value = especie;
        document.querySelectorAll('.btn-especie').forEach(b => {
            b.classList.remove('btn-success', 'text-white');
            b.classList.add('btn-outline-success');
        });
        btn.classList.remove('btn-outline-success');
        btn.classList.add('btn-success', 'text-white');

        document.getElementById('detalles-categoria').classList.remove('d-none');

        // Llenar selects
        const propSelect = document.getElementById('purposeInput');
        const razaSelect = document.getElementById('breedInput');
        
        propSelect.innerHTML = '<option value="">Seleccione...</option>';
        razaSelect.innerHTML = '<option value="">Seleccione...</option>';

        if(dataCat[especie]) {
            dataCat[especie].propositos.forEach(p => propSelect.innerHTML += `<option value="${p}">${p}</option>`);
            dataCat[especie].razas.forEach(r => razaSelect.innerHTML += `<option value="${r}">${r}</option>`);
        }
        validarPaso();
    }

    // Toggle Sanidad
    document.getElementById('switch_sanidad').addEventListener('change', function() {
        document.getElementById('caja_archivo_sanidad').classList.toggle('d-none', !this.checked);
        validarPaso();
    });

    // --- VALIDACIÓN EN TIEMPO REAL ---
    function validarPaso() {
        let valido = true;
        const mod = document.getElementById('modalityInput').value;

        if(currentStep === 1) {
            if(!mod || !document.getElementById('speciesInput').value || !document.getElementById('breedInput').value) valido = false;
            if(mod === 'Genetica' && !document.getElementById('geneticTypeInput').value) valido = false;
            if(mod !== 'Genetica' && !document.getElementById('purposeInput').value) valido = false;
        }
        else if(currentStep === 2) {
            if(!document.getElementById('titleInput').value || !document.getElementById('stockInput').value) valido = false;
            if(mod !== 'Genetica' && (!document.getElementById('sexInput').value || !document.getElementById('ageValueInput').value)) valido = false;
        }
        else if(currentStep === 3) {
            if(!document.getElementById('priceInput').value || !document.getElementById('chargeTypeInput').value) valido = false;
            if(mod !== 'Genetica' && !document.getElementById('weightInput').value) valido = false;
        }
        else if(currentStep === 4) {
            if(document.getElementById('mediaInput').files.length === 0) valido = false;
            if(document.getElementById('switch_sanidad').checked && document.getElementById('sanityFileInput').files.length === 0) valido = false;
        }

        btnNext.disabled = !valido;
        btnSubmit.disabled = !valido;
    }

    document.querySelectorAll('input, select').forEach(el => {
        el.addEventListener('input', validarPaso);
        el.addEventListener('change', validarPaso);
    });
</script>
@endsection