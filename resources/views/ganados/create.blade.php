@extends('layouts.adminlte')

@section('title', 'Publicar Ganado')

@section('content')
<style>
    /* Estilos del Stepper Moderno (Verde y Blanco) */
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
    }
    .step-indicator.active {
        background-color: #28a745; color: white; border-color: #28a745;
        box-shadow: 0 0 10px rgba(40, 167, 69, 0.4); transform: scale(1.1);
    }
    .step-indicator.completed {
        background-color: #20c997; color: white; border-color: #20c997;
    }
    .progress-line {
        height: 4px; background-color: #e9ecef; flex-grow: 1; margin: 0 10px; border-radius: 2px;
    }
    .progress-line .fill {
        height: 100%; background-color: #28a745; width: 0%; transition: width 0.4s ease;
    }

    /* Tarjetas de Selección (Botones Gigantes) */
    .selection-card {
        border: 2px solid #e9ecef; border-radius: 15px; padding: 20px;
        cursor: pointer; transition: all 0.3s; background: white;
    }
    .selection-card:hover { border-color: #28a745; background-color: #f8fff9; }
    .selection-card.selected {
        border-color: #28a745; background-color: #eafaf1; box-shadow: 0 4px 15px rgba(40, 167, 69, 0.15);
    }
    
    .card-modern { border-radius: 15px; border: none; box-shadow: 0 4px 20px rgba(0,0,0,0.05); }
    .input-modern { border-radius: 10px; border: 1px solid #ced4da; padding: 10px 15px; }
    .input-modern:focus { border-color: #28a745; box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25); }
    
    .btn-toggle {
        border: 1px solid #ced4da; background: white; color: #495057; border-radius: 10px; padding: 10px; font-weight: 500;
    }
    .btn-toggle.active { background: #28a745; color: white; border-color: #28a745; }
</style>

<div class="container-fluid py-4">
    <!-- Header y Stepper -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-success font-weight-bold"><i class="fas fa-box"></i> Mercado Ganadero</h2>
        <a href="{{ route('ganados.index') }}" class="btn btn-outline-secondary rounded-pill">Cancelar</a>
    </div>

    <div class="card card-modern mb-4 p-4">
        <div class="d-flex align-items-center justify-content-between">
            <div class="step-indicator active" id="indicator-1">1</div>
            <div class="progress-line"><div class="fill" id="line-1"></div></div>
            <div class="step-indicator" id="indicator-2">2</div>
            <div class="progress-line"><div class="fill" id="line-2"></div></div>
            <div class="step-indicator" id="indicator-3">3</div>
            <div class="progress-line"><div class="fill" id="line-3"></div></div>
            <div class="step-indicator" id="indicator-4">4</div>
            <div class="progress-line"><div class="fill" id="line-4"></div></div>
            <div class="step-indicator" id="indicator-5">5</div>
            <div class="progress-line"><div class="fill" id="line-5"></div></div>
            <div class="step-indicator" id="indicator-6"><i class="fas fa-check"></i></div>
        </div>
    </div>

    <!-- FORMULARIO PRINCIPAL -->
    <form action="{{ route('ganados.store') }}" method="POST" enctype="multipart/form-data" id="ganadoForm" class="card card-modern p-4 p-md-5">
        @csrf
        <!-- Campos Ocultos para la Lógica de los Botones -->
        <input type="hidden" name="tipo_venta" id="tipo_venta" value="lote">
        <input type="hidden" name="sexo" id="sexo_hidden" value="">
        <input type="hidden" name="tipo_precio" id="tipo_precio_hidden" value="kilo_vivo">

        <!-- ================= PASO 1: TIPO DE VENTA ================= -->
        <div class="form-step active" id="step-1">
            <h4 class="text-success font-weight-bold mb-1"><i class="fas fa-tag"></i> Tipo de Venta</h4>
            <p class="text-muted mb-4">Define el objetivo comercial de tu publicación.</p>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="selection-card selected" id="card-lote" onclick="selectTipoVenta('lote')">
                        <i class="fas fa-cubes fa-2x text-success mb-3"></i>
                        <h5 class="font-weight-bold">Venta Comercial</h5>
                        <p class="text-muted small mb-0">Venta por lote. Enfocado en volumen, carne y recría.</p>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="selection-card" id="card-genetica" onclick="selectTipoVenta('genetica')">
                        <i class="fas fa-award fa-2x text-info mb-3"></i>
                        <h5 class="font-weight-bold">Alta Genética</h5>
                        <p class="text-muted small mb-0">Venta individual. Reproductores, cabañas y campeones.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- ================= PASO 2: IDENTIDAD ================= -->
        <div class="form-step" id="step-2">
            <h4 class="text-success font-weight-bold mb-4"><i class="fas fa-info-circle"></i> Identidad del Ganado</h4>
            
            <div class="form-group mb-4">
                <label class="font-weight-bold">Código / Nombre del Lote o Animal *</label>
                <input type="text" name="nombre" class="form-control input-modern" placeholder="Ej. Lote 40 Novillos o Toro RP-320" required>
            </div>

            <div class="row">
                <div class="col-md-6 form-group mb-4">
                    <label class="font-weight-bold">Especie *</label>
                    <select name="tipo_animal_id" id="tipo_animal_id" class="form-control input-modern" required>
                        <option value="">Seleccione una especie...</option>
                        @foreach ($tipo_animals as $item)
                            <option value="{{ $item->id }}">{{ $item->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 form-group mb-4">
                    <label class="font-weight-bold">Raza *</label>
                    <select name="raza_id" id="raza_id" class="form-control input-modern" disabled required>
                        <option value="">Primero seleccione una especie</option>
                    </select>
                </div>
            </div>

            <div class="form-group mb-4">
                <label class="font-weight-bold d-block">Sexo</label>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-toggle flex-fill" onclick="selectSexo(this, 'Macho')">Macho</button>
                    <button type="button" class="btn btn-toggle flex-fill" onclick="selectSexo(this, 'Hembra')">Hembra</button>
                    <button type="button" class="btn btn-toggle flex-fill" onclick="selectSexo(this, 'Mixto')">Mixto</button>
                </div>
            </div>
        </div>

        <!-- ================= PASO 3: FÍSICO Y LOGÍSTICA ================= -->
        <div class="form-step" id="step-3">
            <h4 class="text-success font-weight-bold mb-4"><i class="fas fa-weight"></i> Físico y Logística</h4>
            
            <div class="row">
                <div class="col-md-6 form-group mb-4">
                    <label class="font-weight-bold">Edad Promedio</label>
                    <div class="d-flex align-items-center">
                        <input type="number" name="edad_anos" class="form-control input-modern text-center" placeholder="Años" min="0" value="0">
                        <span class="mx-2 font-weight-bold">Años y</span>
                        <input type="number" name="edad_meses" class="form-control input-modern text-center" placeholder="Meses" min="0" max="11" value="0">
                        <span class="ml-2 font-weight-bold">Meses</span>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 form-group mb-4">
                    <label class="font-weight-bold">Peso Vivo Estimado (KG)</label>
                    <input type="number" name="peso_actual" class="form-control input-modern" placeholder="Ej. 450" step="0.01">
                </div>
                <div class="col-md-6 form-group mb-4">
                    <label class="font-weight-bold">Cantidad (Stock) <span id="badge-genetica" class="badge badge-info d-none ml-2">Bloqueado a 1 (Genética)</span></label>
                    <input type="number" name="stock" id="stock_input" class="form-control input-modern" placeholder="Ej. 50" min="1" required>
                </div>
            </div>
        </div>

        <!-- ================= PASO 4: PRECIO ================= -->
        <div class="form-step" id="step-4">
            <h4 class="text-success font-weight-bold mb-4"><i class="fas fa-dollar-sign"></i> Precio y Cotización</h4>
            
            <div class="row align-items-end">
                <div class="col-md-6 form-group mb-4">
                    <label class="font-weight-bold">Monto (Bs) *</label>
                    <input type="number" name="precio" class="form-control input-modern form-control-lg text-success font-weight-bold" placeholder="0.00" step="0.01" required>
                </div>
                
                <div class="col-md-6 form-group mb-4" id="caja_metodos_lote">
                    <label class="font-weight-bold d-block">Cotización por:</label>
                    <div class="d-flex flex-wrap gap-2">
                        <button type="button" class="btn btn-toggle active flex-fill" onclick="selectPrecio(this, 'kilo_vivo')">Kilo Vivo</button>
                        <button type="button" class="btn btn-toggle flex-fill" onclick="selectPrecio(this, 'kilo_gancho')">Kilo al Gancho</button>
                        <button type="button" class="btn btn-toggle flex-fill" onclick="selectPrecio(this, 'al_barrer')">Al Barrer (Lote)</button>
                    </div>
                </div>
                
                <div class="col-md-6 form-group mb-4 d-none" id="caja_metodo_genetica">
                    <div class="alert alert-info border-info font-weight-bold text-center m-0">
                        <i class="fas fa-award"></i> Precio Fijo (Por Cabeza)
                    </div>
                </div>
            </div>
        </div>

        <!-- ================= PASO 5: CERTIFICACIONES ================= -->
        <div class="form-step" id="step-5">
            <h4 class="text-success font-weight-bold mb-4"><i class="fas fa-shield-alt"></i> Certificaciones Oficiales</h4>
            
            <!-- Sanidad -->
            <div class="card border-success mb-3">
                <div class="card-body">
                    <div class="custom-control custom-switch custom-switch-lg">
                        <input type="checkbox" class="custom-control-input" id="switch_sanidad" name="tiene_sanidad">
                        <label class="custom-control-label font-weight-bold h5 text-success" for="switch_sanidad">Sanidad al Día</label>
                    </div>
                    <p class="text-muted small mt-2">Certifica que el ganado cuenta con Guía de Movimiento SENASAG y vacunas correspondientes.</p>
                    
                    <div id="caja_archivo_sanidad" class="mt-3 d-none">
                        <label class="font-weight-bold">Sube el PDF o Foto del Certificado Sanitario / Guía SENASAG</label>
                        <input type="file" name="archivo_sanidad" class="form-control-file border p-2 rounded bg-light" accept=".pdf,image/*">
                    </div>
                </div>
            </div>

            <!-- Alta Genética -->
            <div class="card border-info mb-3 d-none" id="tarjeta_genetica">
                <div class="card-body">
                    <div class="custom-control custom-switch custom-switch-lg">
                        <input type="checkbox" class="custom-control-input" id="switch_genetica" name="es_campeon">
                        <label class="custom-control-label font-weight-bold h5 text-info" for="switch_genetica">Alta Genética / Campeón</label>
                    </div>
                    <p class="text-muted small mt-2">Cuenta con registro genealógico o premios en ferias.</p>
                    
                    <div id="caja_archivo_genetica" class="mt-3 d-none">
                        <label class="font-weight-bold">Sube el PDF o Foto del Registro Genealógico</label>
                        <input type="file" name="archivo_genetica" class="form-control-file border p-2 rounded bg-light" accept=".pdf,image/*">
                    </div>
                </div>
            </div>
        </div>

        <!-- ================= PASO 6: UBICACIÓN Y FOTOS ================= -->
        <div class="form-step" id="step-6">
            <h4 class="text-success font-weight-bold mb-4"><i class="fas fa-map-marker-alt"></i> Ubicación y Cierre</h4>
            
            <label class="font-weight-bold">Fija la ubicación en el mapa *</label>
            <div id="map" style="height: 300px; border-radius: 15px; border: 2px solid #28a745; margin-bottom: 15px;"></div>
            
            <!-- Inputs ocultos del mapa original -->
            <input type="hidden" name="latitud" id="latitud">
            <input type="hidden" name="longitud" id="longitud">
            <input type="hidden" name="departamento" id="departamento">
            <input type="hidden" name="ciudad" id="ciudad">
            
            <input type="text" name="ubicacion" id="ubicacion" class="form-control input-modern mb-4 bg-light" placeholder="Ubicación autocompletada" readonly>

            <div class="form-group mb-4">
                <label class="font-weight-bold">Descripción Extra</label>
                <textarea name="descripcion" class="form-control input-modern" rows="3" placeholder="Detalles de manejo, pastura, observaciones..."></textarea>
            </div>

            <div class="form-group mb-4">
                <label class="font-weight-bold"><i class="fas fa-camera"></i> Galería Multimedia (Máx 3 imágenes)</label>
                <input type="file" name="imagenes[]" id="imagenes-input" class="form-control-file border p-3 rounded bg-light" accept="image/*" multiple>
                <div id="preview-container" class="row mt-3"></div>
            </div>
        </div>

        <hr class="my-4">
        
        <!-- Botones de Navegación del Stepper -->
        <div class="d-flex justify-content-between">
            <button type="button" class="btn btn-secondary px-4 py-2 font-weight-bold rounded-pill" id="btn-prev" onclick="changeStep(-1)" style="display: none;">
                <i class="fas fa-arrow-left"></i> Atrás
            </button>
            
            <button type="button" class="btn btn-success px-5 py-2 font-weight-bold rounded-pill ml-auto" id="btn-next" onclick="changeStep(1)">
                Siguiente <i class="fas fa-arrow-right"></i>
            </button>
            
            <button type="submit" class="btn btn-success px-5 py-2 font-weight-bold rounded-pill ml-auto" id="btn-submit" style="display: none;">
                Publicar Ganado <i class="fas fa-check"></i>
            </button>
        </div>
    </form>
</div>

<!-- Scripts (Mantenidos y adaptados) -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    // === LÓGICA DEL STEPPER ===
    let currentStep = 1;
    const totalSteps = 6;

    function changeStep(direction) {
        // Validaciones básicas antes de avanzar
        if(direction === 1) {
            if(currentStep === 2 && !document.getElementById('tipo_animal_id').value) {
                alert("Por favor selecciona una Especie"); return;
            }
        }

        document.getElementById(`step-${currentStep}`).classList.remove('active');
        document.getElementById(`indicator-${currentStep}`).classList.remove('active');
        if(direction === 1) document.getElementById(`indicator-${currentStep}`).classList.add('completed');
        if(direction === -1) document.getElementById(`indicator-${currentStep-1}`).classList.remove('completed');
        
        if(direction === 1 && currentStep < totalSteps) {
            document.getElementById(`line-${currentStep}`).style.width = '100%';
        } else if (direction === -1) {
            document.getElementById(`line-${currentStep-1}`).style.width = '0%';
        }

        currentStep += direction;

        document.getElementById(`step-${currentStep}`).classList.add('active');
        document.getElementById(`indicator-${currentStep}`).classList.add('active');

        document.getElementById('btn-prev').style.display = currentStep === 1 ? 'none' : 'block';
        
        if(currentStep === totalSteps) {
            document.getElementById('btn-next').style.display = 'none';
            document.getElementById('btn-submit').style.display = 'block';
            setTimeout(() => map.invalidateSize(), 200); // Refrescar mapa al mostrar
        } else {
            document.getElementById('btn-next').style.display = 'block';
            document.getElementById('btn-submit').style.display = 'none';
        }
    }

    // === LÓGICA DE BOTONES (React to Vanilla JS) ===
    function selectTipoVenta(tipo) {
        document.getElementById('tipo_venta').value = tipo;
        document.getElementById('card-lote').classList.toggle('selected', tipo === 'lote');
        document.getElementById('card-genetica').classList.toggle('selected', tipo === 'genetica');
        
        const stockInput = document.getElementById('stock_input');
        const badgeGen = document.getElementById('badge-genetica');
        const tarjetaGen = document.getElementById('tarjeta_genetica');
        const cajaMetodosLote = document.getElementById('caja_metodos_lote');
        const cajaMetodoGen = document.getElementById('caja_metodo_genetica');

        if(tipo === 'genetica') {
            stockInput.value = 1; stockInput.readOnly = true;
            badgeGen.classList.remove('d-none');
            tarjetaGen.classList.remove('d-none');
            cajaMetodosLote.classList.add('d-none');
            cajaMetodoGen.classList.remove('d-none');
            document.getElementById('tipo_precio_hidden').value = 'precio_fijo';
        } else {
            stockInput.value = ''; stockInput.readOnly = false;
            badgeGen.classList.add('d-none');
            tarjetaGen.classList.add('d-none');
            cajaMetodosLote.classList.remove('d-none');
            cajaMetodoGen.classList.add('d-none');
            document.getElementById('tipo_precio_hidden').value = 'kilo_vivo';
        }
    }

    function selectSexo(btn, valor) {
        document.getElementById('sexo_hidden').value = valor;
        btn.parentElement.querySelectorAll('.btn-toggle').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
    }

    function selectPrecio(btn, valor) {
        document.getElementById('tipo_precio_hidden').value = valor;
        btn.parentElement.querySelectorAll('.btn-toggle').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
    }

    // === SWITCHES CERTIFICACIONES ===
    document.getElementById('switch_sanidad').addEventListener('change', function() {
        document.getElementById('caja_archivo_sanidad').classList.toggle('d-none', !this.checked);
    });
    document.getElementById('switch_genetica').addEventListener('change', function() {
        document.getElementById('caja_archivo_genetica').classList.toggle('d-none', !this.checked);
    });

    // === LÓGICA DE SELECTS ANIMAL/RAZA ===
    document.addEventListener('DOMContentLoaded', function() {
        const razas = @json($razas);
        const tipoSelect = document.getElementById('tipo_animal_id');
        const razaSelect = document.getElementById('raza_id');

        tipoSelect.addEventListener('change', function() {
            const tipoID = this.value;
            razaSelect.innerHTML = '';
            if (!tipoID) {
                razaSelect.disabled = true;
                razaSelect.innerHTML = '<option value="">Primero seleccione una especie</option>';
                return;
            }
            const filtradas = razas.filter(r => r.tipo_animal_id == tipoID);
            if (filtradas.length > 0) {
                razaSelect.disabled = false;
                razaSelect.innerHTML = '<option value="">Seleccione una raza...</option>';
                filtradas.forEach(r => {
                    razaSelect.innerHTML += `<option value="${r.id}">${r.nombre}</option>`;
                });
            } else {
                razaSelect.disabled = true;
                razaSelect.innerHTML = '<option value="">No hay razas registradas</option>';
            }
        });
    });

    // === LÓGICA DEL MAPA ORIGINAL ===
    var map = L.map('map').setView([-17.7833, -63.1821], 6);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: 'OpenStreetMap' }).addTo(map);
    var marker;
    map.on('click', function(e) {
        var lat = e.latlng.lat.toFixed(7); var lng = e.latlng.lng.toFixed(7);
        if (marker) map.removeLayer(marker);
        marker = L.marker([lat, lng]).addTo(map);
        document.getElementById('latitud').value = lat; document.getElementById('longitud').value = lng;
        document.getElementById('ubicacion').value = "Buscando dirección...";
        
        fetch('/api/geocodificacion?latitud=' + lat + '&longitud=' + lng)
            .then(res => res.json())
            .then(data => {
                if (data.success && data.data) {
                    var info = data.data;
                    document.getElementById('departamento').value = info.departamento || '';
                    document.getElementById('ciudad').value = info.ciudad || '';
                    var d = [];
                    if(info.ciudad) d.push(info.ciudad);
                    if(info.departamento) d.push(info.departamento);
                    document.getElementById('ubicacion').value = d.join(', ') || 'Lat: ' + lat;
                }
            });
    });

    // === PREVIEW DE IMÁGENES ORIGINAL ===
    document.getElementById('imagenes-input').addEventListener('change', function(e) {
        const container = document.getElementById('preview-container');
        container.innerHTML = '';
        const files = Array.from(e.target.files).slice(0, 3);
        files.forEach((file, index) => {
            const reader = new FileReader();
            reader.onload = function(ev) {
                container.innerHTML += `
                    <div class="col-4">
                        <img src="${ev.target.result}" class="img-thumbnail" style="height: 100px; width:100%; object-fit:cover;">
                    </div>`;
            };
            reader.readAsDataURL(file);
        });
    });
</script>
@endsection