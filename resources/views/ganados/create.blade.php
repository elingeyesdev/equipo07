@extends('layouts.adminlte')

@section('title', 'Registrar Ganado')

@section('content')
    <style>
        .form-section-title {
            color: #28a745;
            font-weight: 600;
            font-size: 1rem;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #28a745;
        }

        .card-header {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        }

        .form-group label {
            color: #495057;
            margin-bottom: 0.5rem;
        }

        .input-group .btn {
            border-color: #28a745;
        }

        .input-group .btn:hover {
            background-color: #28a745;
            color: white;
        }

        .form-control:focus {
            border-color: #28a745;
            box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
        }

        select.form-control:focus {
            border-color: #28a745;
            box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
        }
    </style>

    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1 class="h3 mb-0 text-success">
                <i class="fas fa-cow"></i> Registrar Nuevo Ganado
            </h1>
            <a href="{{ route('ganados.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>

        <div class="card shadow-lg border-success border-3">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="fas fa-cow"></i> Información del Animal</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('ganados.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row">
                        <!-- COLUMNA IZQUIERDA -->
                        <div class="col-lg-6">
                            <h6 class="text-success border-bottom pb-2 mb-3"><i class="fas fa-info-circle"></i> Datos
                                Básicos</h6>

                            <div class="form-group mb-3">
                                <label for="nombre" class="font-weight-bold">Nombre *</label>
                                <input type="text" name="nombre" id="nombre" class="form-control"
                                    value="{{ old('nombre') }}" required>
                            </div>

                            <div class="form-group mb-3">
                                <label for="tipo_animal_id" class="font-weight-bold">Tipo de Animal *</label>
                                <select name="tipo_animal_id" id="tipo_animal_id" class="form-control" required>
                                    <option value="">Seleccione...</option>
                                    @foreach ($tipo_animals as $item)
                                        <option value="{{ $item->id }}">{{ $item->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group mb-3">
                                <label for="raza_id" class="font-weight-bold">Raza</label>
                                <select name="raza_id" id="raza_id" class="form-control" disabled>
                                    <option value="">Seleccione un tipo de animal primero</option>
                                </select>
                            </div>

                            <div class="form-group mb-3">
                                <label class="font-weight-bold">Edad</label>
                                <div class="row">
                                    <div class="col-4">
                                        <label class="small text-muted mb-1">Años</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <button class="btn btn-outline-secondary btn-sm" type="button"
                                                    onclick="decrementValue('edad_anos')">
                                                    <i class="fas fa-minus"></i>
                                                </button>
                                            </div>
                                            <input type="number" name="edad_anos" id="edad_anos"
                                                class="form-control text-center" value="{{ old('edad_anos', 0) }}"
                                                min="0" max="25" required>
                                            <div class="input-group-append">
                                                <button class="btn btn-outline-secondary btn-sm" type="button"
                                                    onclick="incrementValue('edad_anos', 25)">
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <label class="small text-muted mb-1">Meses</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <button class="btn btn-outline-secondary btn-sm" type="button"
                                                    onclick="decrementValue('edad_meses')">
                                                    <i class="fas fa-minus"></i>
                                                </button>
                                            </div>
                                            <input type="number" name="edad_meses" id="edad_meses"
                                                class="form-control text-center" value="{{ old('edad_meses', 0) }}"
                                                min="0" max="11" required>
                                            <div class="input-group-append">
                                                <button class="btn btn-outline-secondary btn-sm" type="button"
                                                    onclick="incrementValue('edad_meses', 11)">
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <label class="small text-muted mb-1">Días</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <button class="btn btn-outline-secondary btn-sm" type="button"
                                                    onclick="decrementValue('edad_dias')">
                                                    <i class="fas fa-minus"></i>
                                                </button>
                                            </div>
                                            <input type="number" name="edad_dias" id="edad_dias"
                                                class="form-control text-center" value="{{ old('edad_dias', 0) }}"
                                                min="0" max="30" required>
                                            <div class="input-group-append">
                                                <button class="btn btn-outline-secondary btn-sm" type="button"
                                                    onclick="incrementValue('edad_dias', 30)">
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                <label for="sexo" class="font-weight-bold">Sexo</label>
                                <select name="sexo" id="sexo" class="form-control">
                                    <option value="">Seleccione</option>
                                    <option value="Macho">Macho</option>
                                    <option value="Hembra">Hembra</option>
                                </select>
                            </div>

                            <div class="form-group mb-3" id="cantidad_leche_group" style="display: none;">
                                <label for="cantidad_leche_dia" class="font-weight-bold">Cantidad de Leche por Día
                                    (litros)</label>
                                <div class="input-group">
                                    <input type="number" name="cantidad_leche_dia" id="cantidad_leche_dia"
                                        class="form-control" step="0.01" min="0"
                                        value="{{ old('cantidad_leche_dia') }}" placeholder="Ej: 15.5">
                                    <div class="input-group-append">
                                        <span class="input-group-text">litros/día</span>
                                    </div>
                                </div>
                                <small class="form-text text-muted">Ingrese la producción diaria de leche (solo para
                                    hembras en producción)</small>
                            </div>

                            <div class="form-group mb-3">
                                <label for="categoria_id" class="font-weight-bold">Categoría *</label>
                                <select name="categoria_id" id="categoria_id" class="form-control" required>
                                    <option value="">Seleccione una categoría</option>
                                    @foreach ($categorias as $categoria)
                                        <option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- COLUMNA DERECHA -->
                        <div class="col-lg-6">
                            <h6 class="text-success border-bottom pb-2 mb-3"><i class="fas fa-chart-line"></i> Información
                                Comercial</h6>

                            <div class="form-group mb-3">
                                <label for="tipo_peso_id" class="font-weight-bold">Método de Venta / Tipo de Peso
                                    *</label>
                                <select name="tipo_peso_id" class="form-control" required>
                                    @foreach ($tipoPesos as $peso)
                                        <option value="{{ $peso->id }}">{{ $peso->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group mb-3">
                                <label for="peso_actual" class="font-weight-bold">Peso Actual (kg)</label>
                                <div class="input-group">
                                    <input type="number" name="peso_actual" id="peso_actual" class="form-control"
                                        step="0.01" min="0" value="{{ old('peso_actual') }}"
                                        placeholder="Ej: 250.50">
                                    <div class="input-group-append">
                                        <span class="input-group-text">kg</span>
                                    </div>
                                </div>
                                <small class="form-text text-muted">Ingrese el peso actual del animal en kilogramos</small>
                            </div>

                            <div class="form-group mb-3">
                                <label for="precio" class="font-weight-bold">Precio (Bs)</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Bs</span>
                                    </div>
                                    <input type="number" name="precio" id="precio" class="form-control"
                                        step="0.01" min="0" value="{{ old('precio') }}" placeholder="0.00">
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                <label for="stock" class="font-weight-bold">Stock (Cantidad) *</label>
                                <input type="number" name="stock" id="stock" class="form-control" min="0"
                                    value="{{ old('stock', 0) }}" required>
                                <small class="form-text text-muted">Ingrese la cantidad disponible de ganado</small>
                            </div>

                            <div class="form-group mb-3">
                                <label for="dato_sanitario_id" class="font-weight-bold">Datos Sanitarios</label>
                                <select name="dato_sanitario_id" class="form-control">
                                    <option value="">Sin registro sanitario</option>
                                    @foreach ($datosSanitarios as $ds)
                                        <option value="{{ $ds->id }}">
                                            {{ $ds->vacuna ?? 'Sin vacuna' }} - {{ $ds->fecha_aplicacion }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- SECCIÓN DE DESCRIPCIÓN (ANCHO COMPLETO) -->
                    <div class="row mt-3">
                        <div class="col-12">
                            <h6 class="text-success border-bottom pb-2 mb-3"><i class="fas fa-align-left"></i> Descripción
                            </h6>
                            <div class="form-group mb-3">
                                <label for="descripcion" class="font-weight-bold">Descripción del Animal</label>
                                <textarea name="descripcion" id="descripcion" class="form-control" rows="4"
                                    placeholder="Describa las características del animal...">{{ old('descripcion') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="form-group mt-3">
                    </div>

                    <!-- SECCIÓN DE UBICACIÓN (ANCHO COMPLETO) -->
                    <div class="row mt-3">
                        <div class="col-12">
                            <h6 class="text-success border-bottom pb-2 mb-3"><i class="fas fa-map-marker-alt"></i>
                                Ubicación</h6>
                            <div class="form-group mb-3">
                                <label for="map" class="font-weight-bold">Seleccione la ubicación en el mapa</label>
                                <div id="map"
                                    style="height: 400px; border-radius: 10px; border: 2px solid #28a745;"></div>

                                <input type="hidden" name="latitud" id="latitud">
                                <input type="hidden" name="longitud" id="longitud">
                                <input type="hidden" name="departamento" id="departamento">
                                <input type="hidden" name="municipio" id="municipio">
                                <input type="hidden" name="provincia" id="provincia">
                                <input type="hidden" name="ciudad" id="ciudad">

                                <div id="info-ubicacion" class="mt-3" style="display: none;">
                                    <div class="card border-success">
                                        <div class="card-body">
                                            <h6 class="mb-3 text-success"><strong><i class="fas fa-info-circle"></i>
                                                    Ubicación Seleccionada</strong></h6>
                                            <div class="row mb-2">
                                                <div class="col-md-3">
                                                    <strong>Ciudad:</strong>
                                                </div>
                                                <div class="col-md-9" id="ciudad-texto">
                                                    -
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <strong>Dirección:</strong>
                                                </div>
                                                <div class="col-md-9" id="direccion-texto">
                                                    -
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <input type="text" name="ubicacion" id="ubicacion" class="form-control mt-2"
                                    placeholder="Ubicación seleccionada" readonly>
                            </div>
                        </div>
                    </div>

                    <!-- SECCIÓN DE IMÁGENES (ANCHO COMPLETO) -->
                    <div class="row mt-3">
                        <div class="col-12">
                            <h6 class="text-success border-bottom pb-2 mb-3"><i class="fas fa-images"></i> Imágenes del
                                Animal</h6>
                            <div class="form-group mb-3">
                                <label class="font-weight-bold">Imágenes (máximo 3)</label>

                                <div id="preview-container" class="row mb-3"></div>

                                <input type="file" name="imagenes[]" class="form-control" accept="image/*" multiple
                                    id="imagenes-input">
                                <small class="form-text text-muted">Puedes seleccionar hasta 3 imágenes. Formatos
                                    permitidos: JPG, PNG, GIF. Tamaño máximo por imagen: 2MB</small>
                                <div id="imagenes-count" class="text-muted mt-2"></div>
                            </div>
                        </div>
                    </div>

                    <!-- BOTÓN DE GUARDAR -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('ganados.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> Cancelar
                                </a>
                                <button type="submit" class="btn btn-success btn-lg shadow-sm">
                                    <i class="fas fa-save"></i> Guardar Registro
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        document.getElementById('tipo_animal_id').addEventListener('change', function() {
            let tipoID = this.value;

            fetch('/razas-por-tipo/' + tipoID)
                .then(response => response.json())
                .then(data => {
                    let selectRaza = document.getElementById('raza_id');
                    selectRaza.innerHTML = '<option value="">Seleccione raza</option>';

                    data.forEach(function(raza) {
                        selectRaza.innerHTML += `<option value="${raza.id}">${raza.nombre}</option>`;
                    });
                });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tipoAnimalSelect = document.getElementById('tipo_animal_id');
            const razaSelect = document.getElementById('raza_id');

            // Cargar todas las razas desde PHP (ya las enviaste desde el controlador)
            const razas = @json($razas);

            tipoAnimalSelect.addEventListener('change', function() {
                const tipoAnimalID = this.value;

                // Limpiar el select de raza
                razaSelect.innerHTML = '';

                if (!tipoAnimalID) {
                    razaSelect.disabled = true;
                    razaSelect.innerHTML = '<option value="">Seleccione un tipo de animal primero</option>';
                    return;
                }

                // Filtrar razas del tipo seleccionado
                const filtradas = razas.filter(r => r.tipo_animal_id == tipoAnimalID);

                // Rellenar el select
                if (filtradas.length > 0) {
                    razaSelect.disabled = false;
                    razaSelect.innerHTML = '<option value="">Seleccione una raza...</option>';

                    filtradas.forEach(r => {
                        razaSelect.innerHTML += `<option value="${r.id}">${r.nombre}</option>`;
                    });

                } else {
                    razaSelect.disabled = true;
                    razaSelect.innerHTML =
                        '<option value="">No hay razas registradas para este tipo</option>';
                }
            });
        });
    </script>
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        // Crear el mapa centrado en Bolivia
        var map = L.map('map').setView([-17.7833, -63.1821], 6);

        // Capa gratuita de OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: 'OpenStreetMap'
        }).addTo(map);

        var marker;

        // Evento click en mapa
        map.on('click', function(e) {
            var lat = e.latlng.lat.toFixed(7);
            var lng = e.latlng.lng.toFixed(7);

            if (marker) map.removeLayer(marker);

            marker = L.marker([lat, lng]).addTo(map);

            document.getElementById('latitud').value = lat;
            document.getElementById('longitud').value = lng;
            document.getElementById('ubicacion').value = "Lat: " + lat + " - Lng: " + lng;

            // Obtener información geográfica
            obtenerInformacionGeografica(lat, lng);
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const input = document.getElementById('imagenes-input');
            const previewContainer = document.getElementById('preview-container');
            const countDisplay = document.getElementById('imagenes-count');
            let imagenesNuevas = 0;

            function updateCount() {
                countDisplay.textContent = `Total de imágenes: ${imagenesNuevas} / 3`;

                if (imagenesNuevas > 3) {
                    countDisplay.className = 'text-danger mt-2';
                    countDisplay.textContent += ' (Excede el límite de 3 imágenes)';
                } else {
                    countDisplay.className = 'text-muted mt-2';
                }
            }

            let fileMap = new Map();

            input.addEventListener('change', function(e) {
                previewContainer.innerHTML = '';
                imagenesNuevas = 0;
                fileMap.clear();

                const files = Array.from(e.target.files);
                const maxFiles = 3;

                files.slice(0, maxFiles).forEach((file, index) => {
                    if (file.type.startsWith('image/')) {
                        const fileId = Date.now() + '-' + index;
                        fileMap.set(fileId, file);

                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const col = document.createElement('div');
                            col.className = 'col-md-4 mb-3';
                            col.setAttribute('data-file-id', fileId);
                            col.innerHTML = `
                        <div class="position-relative">
                            <img src="${e.target.result}" 
                                 alt="Preview ${index + 1}" 
                                 class="img-thumbnail" 
                                 style="width: 100%; height: 150px; object-fit: cover;">
                            <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1 eliminar-preview" 
                                    data-file-id="${fileId}">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    `;
                            previewContainer.appendChild(col);
                            imagenesNuevas++;
                            updateCount();

                            // Agregar evento para eliminar preview
                            col.querySelector('.eliminar-preview').addEventListener('click',
                                function() {
                                    const fileIdToRemove = this.getAttribute(
                                    'data-file-id');
                                    fileMap.delete(fileIdToRemove);

                                    const dataTransfer = new DataTransfer();
                                    fileMap.forEach(file => dataTransfer.items.add(file));
                                    input.files = dataTransfer.files;

                                    col.remove();
                                    imagenesNuevas--;
                                    updateCount();
                                });
                        };
                        reader.readAsDataURL(file);
                    }
                });

                updateCount();
            });

            updateCount();
        });
    </script>

    <script>
        function incrementValue(fieldId, max) {
            const field = document.getElementById(fieldId);
            let value = parseInt(field.value) || 0;
            if (value < max) {
                value++;
                field.value = value;
            }
        }

        function decrementValue(fieldId) {
            const field = document.getElementById(fieldId);
            let value = parseInt(field.value) || 0;
            if (value > 0) {
                value--;
                field.value = value;
            }
        }
    </script>
    <script>
        // Mostrar/ocultar campo de cantidad de leche según el sexo
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

            // Ejecutar al cargar la página si hay un valor previo
            toggleCantidadLeche();
        });
    </script>
@endsection
