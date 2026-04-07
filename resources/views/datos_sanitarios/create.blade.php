@extends('layouts.adminlte')

@section('title', 'Nuevo Registro Sanitario')

@section('content')
    <div class="container-fluid px-3 px-md-4">
        <!-- Header Moderno -->
        <div class="page-header-modern mb-4">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
                <div class="mb-3 mb-md-0">
                    <h1 class="page-title">
                        <span class="icon-wrapper">
                            <i class="fas fa-clipboard-check"></i>
                        </span>
                        <span class="title-text">Nuevo Registro Sanitario</span>
                    </h1>
                    <p class="page-subtitle">Complete el formulario para registrar los datos sanitarios del animal</p>
                </div>
                <a href="{{ route('admin.datos-sanitarios.index') }}" class="btn btn-back">
                    <i class="fas fa-arrow-left mr-2"></i> Volver
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show alert-modern" role="alert">
                <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show alert-modern" role="alert">
                <i class="fas fa-exclamation-circle mr-2"></i> {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <div class="info-card-modern mb-4">
            <div class="d-flex align-items-start">
                <div class="info-icon-wrapper">
                    <i class="fas fa-info-circle"></i>
                </div>
                <div class="info-content">
                    <strong class="info-title">Nota importante</strong>
                    <p class="info-text mb-0">Puede agregar datos sanitarios a cualquier animal registrado en el sistema,
                        independientemente de si tiene fecha de publicación o no. Los animales sin publicar aparecerán
                        marcados con
                        <span class="badge badge-warning">[Sin publicar]</span>.
                    </p>
                </div>
            </div>
        </div>

        <form action="{{ route('admin.datos-sanitarios.store') }}" method="POST" enctype="multipart/form-data"
            id="formDatosSanitarios">
            @csrf

            <!-- Layout de dos columnas -->
            <div class="row">
                <!-- Columna Izquierda -->
                <div class="col-lg-6">
                    <!-- Sección: Información del Animal -->
                    <div class="card-modern card-primary mb-4">
                        <div class="card-header-modern">
                            <div class="card-icon-wrapper">
                                <i class="fas fa-cow"></i>
                            </div>
                            <h5 class="card-title-modern">Información del Animal</h5>
                        </div>
                        <div class="card-body-modern">
                            <div class="form-group-modern">
                                <label class="label-modern">
                                    <i class="fas fa-paw label-icon"></i> Animal
                                </label>
                                <select name="ganado_id" class="form-control-modern form-control-lg">
                                    <option value="">Seleccione un animal (opcional)...</option>
                                    @foreach ($ganados as $g)
                                        <option value="{{ $g->id }}">
                                            {{ $g->nombre }}
                                            @if ($g->tipoAnimal)
                                                - {{ $g->tipoAnimal->nombre }}
                                            @endif
                                            @if ($g->raza)
                                                ({{ $g->raza->nombre }})
                                            @endif
                                            @if ($g->edad)
                                                - {{ $g->edad }} meses
                                            @endif
                                            @if (!$g->fecha_publicacion)
                                                [Sin publicar]
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                                <small class="form-text-modern">
                                    <i class="fas fa-info-circle"></i> Puede seleccionar cualquier animal registrado,
                                    incluso si no tiene fecha de publicación.
                                </small>
                            </div>
                        </div>
                    </div>

                    <!-- Sección: Vacunaciones -->
                    <div class="card-modern card-success mb-4">
                        <div class="card-header-modern">
                            <div class="card-icon-wrapper">
                                <i class="fas fa-syringe"></i>
                            </div>
                            <h5 class="card-title-modern">Vacunaciones</h5>
                        </div>
                        <div class="card-body-modern">
                            <div class="form-group-modern">
                                <label class="label-modern">
                                    <i class="fas fa-vial label-icon"></i> Otras Vacunas
                                </label>
                                <input type="text" name="vacuna" class="form-control-modern"
                                    placeholder="Ej: Triple, Brucelosis, etc. (opcional)">
                                <small class="form-text-modern">Especifique otras vacunas además de las marcadas
                                    abajo</small>
                            </div>

                            <div class="form-group-modern">
                                <label class="label-modern d-block mb-3">
                                    <i class="fas fa-check-double label-icon"></i> Vacunaciones Específicas
                                </label>
                                <div class="checkbox-modern-wrapper">
                                    <div class="checkbox-modern">
                                        <input type="checkbox" class="checkbox-input" name="vacunado_fiebre_aftosa"
                                            id="vacunado_fiebre_aftosa" value="1">
                                        <label class="checkbox-label" for="vacunado_fiebre_aftosa">
                                            <span class="checkbox-custom"></span>
                                            <span class="checkbox-text">
                                                <i class="fas fa-shield-alt"></i>
                                                <strong>Vacunado de Libre de Fiebre Aftosa</strong>
                                            </span>
                                        </label>
                                    </div>
                                    <div class="checkbox-modern">
                                        <input type="checkbox" class="checkbox-input" name="vacunado_antirabica"
                                            id="vacunado_antirabica" value="1">
                                        <label class="checkbox-label" for="vacunado_antirabica">
                                            <span class="checkbox-custom"></span>
                                            <span class="checkbox-text">
                                                <i class="fas fa-shield-alt"></i>
                                                <strong>Vacunado de Antirrábica</strong>
                                            </span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sección: Tratamientos y Medicamentos -->
                    <div class="card-modern card-info mb-4">
                        <div class="card-header-modern">
                            <div class="card-icon-wrapper">
                                <i class="fas fa-pills"></i>
                            </div>
                            <h5 class="card-title-modern">Tratamientos y Medicamentos</h5>
                        </div>
                        <div class="card-body-modern">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group-modern">
                                        <label class="label-modern">
                                            <i class="fas fa-stethoscope label-icon"></i> Tratamiento
                                        </label>
                                        <input type="text" name="tratamiento" class="form-control-modern"
                                            placeholder="Tipo de tratamiento aplicado">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group-modern">
                                        <label class="label-modern">
                                            <i class="fas fa-capsules label-icon"></i> Medicamento
                                        </label>
                                        <input type="text" name="medicamento" class="form-control-modern"
                                            placeholder="Nombre del medicamento">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group-modern">
                                        <label class="label-modern">
                                            <i class="fas fa-calendar-check label-icon"></i> Fecha de Aplicación
                                        </label>
                                        <input type="date" name="fecha_aplicacion" class="form-control-modern">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group-modern">
                                        <label class="label-modern">
                                            <i class="fas fa-calendar-alt label-icon"></i> Próxima Fecha
                                        </label>
                                        <input type="date" name="proxima_fecha" class="form-control-modern">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group-modern">
                                <label class="label-modern">
                                    <i class="fas fa-user-md label-icon"></i> Veterinario
                                </label>
                                <input type="text" name="veterinario" class="form-control-modern"
                                    placeholder="Nombre del veterinario responsable">
                            </div>

                            <div class="form-group-modern">
                                <label class="label-modern">
                                    <i class="fas fa-comment-alt label-icon"></i> Observaciones
                                </label>
                                <textarea name="observaciones" class="form-control-modern" rows="4"
                                    placeholder="Notas adicionales sobre el tratamiento o estado del animal"></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Sección: Marca del Animal -->
                    <div class="card-modern card-primary mb-4">
                        <div class="card-header-modern">
                            <div class="card-icon-wrapper">
                                <i class="fas fa-tag"></i>
                            </div>
                            <h5 class="card-title-modern">Marca del Animal</h5>
                        </div>
                        <div class="card-body-modern">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group-modern">
                                        <label class="label-modern">
                                            <i class="fas fa-tag label-icon"></i> Marca del Ganado
                                        </label>
                                        <input type="text" name="marca_ganado" class="form-control-modern"
                                            placeholder="Ej: Marca registrada del animal">
                                        <small class="form-text-modern">Marca identificadora del ganado</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group-modern">
                                        <label class="label-modern">
                                            <i class="fas fa-hashtag label-icon"></i> Señal o #
                                        </label>
                                        <input type="text" name="senal_numero" class="form-control-modern"
                                            placeholder="Ej: #12345, Señal A-001">
                                        <small class="form-text-modern">Número de señal o identificación</small>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group-modern">
                                <label class="label-modern">
                                    <i class="fas fa-image label-icon"></i> Foto de la Marca
                                </label>
                                <div class="file-upload-modern">
                                    <input type="file" name="marca_ganado_foto" class="file-input-modern"
                                        id="marca_ganado_foto" accept="image/*">
                                    <label class="file-label-modern" for="marca_ganado_foto">
                                        <i class="fas fa-cloud-upload-alt"></i>
                                        <span class="file-text">Seleccione una imagen de la marca...</span>
                                    </label>
                                </div>
                                <small class="form-text-modern">
                                    <i class="fas fa-info-circle"></i> Tamaño máximo: 5MB. Formatos: JPG, PNG, GIF
                                </small>
                            </div>
                        </div>
                    </div>

                    <!-- Sección: Información del Dueño -->
                    <div class="card-modern card-info mb-4">
                        <div class="card-header-modern">
                            <div class="card-icon-wrapper">
                                <i class="fas fa-user"></i>
                            </div>
                            <h5 class="card-title-modern">Información del Dueño</h5>
                        </div>
                        <div class="card-body-modern">
                            <div class="form-group-modern">
                                <label class="label-modern">
                                    <i class="fas fa-user-circle label-icon"></i> Nombre del Dueño
                                </label>
                                <input type="text" name="nombre_dueno" class="form-control-modern"
                                    placeholder="Ej: Juan Pérez, María González">
                                <small class="form-text-modern">Nombre completo del dueño de los animales</small>
                            </div>

                            <div class="form-group-modern">
                                <label class="label-modern">
                                    <i class="fas fa-id-card label-icon"></i> Foto del Carnet del Dueño
                                </label>
                                <div class="file-upload-modern">
                                    <input type="file" name="carnet_dueno_foto" class="file-input-modern"
                                        id="carnet_dueno_foto" accept="image/*">
                                    <label class="file-label-modern" for="carnet_dueno_foto">
                                        <i class="fas fa-cloud-upload-alt"></i>
                                        <span class="file-text">Seleccione una imagen...</span>
                                    </label>
                                </div>
                                <small class="form-text-modern">
                                    <i class="fas fa-info-circle"></i> Tamaño máximo: 5MB. Formatos: JPG, PNG, GIF
                                </small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Columna Derecha -->
                <div class="col-lg-6">
                    <!-- Sección: Certificado SENASAG -->
                    <div class="card-modern card-warning mb-4">
                        <div class="card-header-modern">
                            <div class="card-icon-wrapper">
                                <i class="fas fa-certificate"></i>
                            </div>
                            <h5 class="card-title-modern">Certificado de Vacunación SENASAG</h5>
                        </div>
                        <div class="card-body-modern">
                            <div class="form-group-modern">
                                <label class="label-modern">
                                    <i class="fas fa-file-image label-icon"></i> Imagen del Certificado
                                </label>
                                <div class="file-upload-modern">
                                    <input type="file" name="certificado_imagen" class="file-input-modern"
                                        id="certificado_imagen" accept="image/*">
                                    <label class="file-label-modern" for="certificado_imagen">
                                        <i class="fas fa-cloud-upload-alt"></i>
                                        <span class="file-text">Seleccione una imagen...</span>
                                    </label>
                                </div>
                                <small class="form-text-modern">
                                    <i class="fas fa-info-circle"></i> Tamaño máximo: 5MB. Formatos permitidos: JPG, PNG,
                                    GIF
                                </small>
                            </div>
                        </div>
                    </div>

                    <!-- Sección: Certificado de Campeón -->
                    <div class="card-modern card-warning mb-4">
                        <div class="card-header-modern">
                            <div class="card-icon-wrapper">
                                <i class="fas fa-trophy"></i>
                            </div>
                            <h5 class="card-title-modern">Certificado de Campeón</h5>
                        </div>
                        <div class="card-body-modern">
                            <div class="alert-modern alert-info-modern">
                                <i class="fas fa-info-circle"></i>
                                <strong>Nota:</strong> Si el animal ha sido campeón en alguna competencia o exposición,
                                puede subir aquí la imagen del certificado correspondiente.
                            </div>
                            <div class="form-group-modern">
                                <label class="label-modern">
                                    <i class="fas fa-trophy label-icon"></i> Imagen del Certificado de Campeón
                                </label>
                                <div class="file-upload-modern">
                                    <input type="file" name="certificado_campeon_imagen" class="file-input-modern"
                                        id="certificado_campeon_imagen" accept="image/*">
                                    <label class="file-label-modern" for="certificado_campeon_imagen">
                                        <i class="fas fa-cloud-upload-alt"></i>
                                        <span class="file-text">Seleccione una imagen del certificado...</span>
                                    </label>
                                </div>
                                <small class="form-text-modern">
                                    <i class="fas fa-info-circle"></i> Tamaño máximo: 5MB. Formatos permitidos: JPG, PNG,
                                    GIF
                                </small>
                            </div>
                        </div>
                    </div>

                    <!-- Sección: Logros y Reconocimientos -->
                    <div class="card-modern card-primary mb-4">
                        <div class="card-header-modern">
                            <div class="card-icon-wrapper">
                                <i class="fas fa-trophy"></i>
                            </div>
                            <h5 class="card-title-modern">Logros y Reconocimientos</h5>
                        </div>
                        <div class="card-body-modern">
                            <!-- Belleza y Estructura -->
                            <div class="achievement-section mb-4">
                                <h6 class="achievement-title achievement-primary">
                                    <i class="fas fa-star"></i> Belleza y Estructura
                                </h6>
                                <div class="switch-grid">
                                    <div class="switch-modern">
                                        <input type="checkbox" class="switch-input" name="logro_campeon_raza"
                                            id="logro_campeon_raza" value="1"
                                            {{ old('logro_campeon_raza') ? 'checked' : '' }}>
                                        <label class="switch-label" for="logro_campeon_raza">Campeón de Raza</label>
                                    </div>
                                    <div class="switch-modern">
                                        <input type="checkbox" class="switch-input" name="logro_gran_campeon_macho"
                                            id="logro_gran_campeon_macho" value="1"
                                            {{ old('logro_gran_campeon_macho') ? 'checked' : '' }}>
                                        <label class="switch-label" for="logro_gran_campeon_macho">Gran Campeón
                                            Macho</label>
                                    </div>
                                    <div class="switch-modern">
                                        <input type="checkbox" class="switch-input" name="logro_gran_campeon_hembra"
                                            id="logro_gran_campeon_hembra" value="1"
                                            {{ old('logro_gran_campeon_hembra') ? 'checked' : '' }}>
                                        <label class="switch-label" for="logro_gran_campeon_hembra">Gran Campeón
                                            Hembra</label>
                                    </div>
                                    <div class="switch-modern">
                                        <input type="checkbox" class="switch-input" name="logro_mejor_ubre"
                                            id="logro_mejor_ubre" value="1"
                                            {{ old('logro_mejor_ubre') ? 'checked' : '' }}>
                                        <label class="switch-label" for="logro_mejor_ubre">Mejor Ubre</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Producción de Leche -->
                            <div class="achievement-section mb-4">
                                <h6 class="achievement-title achievement-success">
                                    <i class="fas fa-milk"></i> Producción de Leche
                                </h6>
                                <div class="switch-grid">
                                    <div class="switch-modern">
                                        <input type="checkbox" class="switch-input" name="logro_campeona_litros_dia"
                                            id="logro_campeona_litros_dia" value="1"
                                            {{ old('logro_campeona_litros_dia') ? 'checked' : '' }}>
                                        <label class="switch-label" for="logro_campeona_litros_dia">Campeona en
                                            Litros/Día</label>
                                    </div>
                                    <div class="switch-modern">
                                        <input type="checkbox" class="switch-input" name="logro_mejor_lactancia"
                                            id="logro_mejor_lactancia" value="1"
                                            {{ old('logro_mejor_lactancia') ? 'checked' : '' }}>
                                        <label class="switch-label" for="logro_mejor_lactancia">Mejor Lactancia</label>
                                    </div>
                                    <div class="switch-modern">
                                        <input type="checkbox" class="switch-input" name="logro_mejor_calidad_leche"
                                            id="logro_mejor_calidad_leche" value="1"
                                            {{ old('logro_mejor_calidad_leche') ? 'checked' : '' }}>
                                        <label class="switch-label" for="logro_mejor_calidad_leche">Mejor Calidad de
                                            Leche</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Producción de Carne -->
                            <div class="achievement-section mb-4">
                                <h6 class="achievement-title achievement-warning">
                                    <i class="fas fa-drumstick-bite"></i> Producción de Carne
                                </h6>
                                <div class="switch-grid">
                                    <div class="switch-modern">
                                        <input type="checkbox" class="switch-input" name="logro_mejor_novillo"
                                            id="logro_mejor_novillo" value="1"
                                            {{ old('logro_mejor_novillo') ? 'checked' : '' }}>
                                        <label class="switch-label" for="logro_mejor_novillo">Mejor Novillo</label>
                                    </div>
                                    <div class="switch-modern">
                                        <input type="checkbox" class="switch-input" name="logro_gran_campeon_carne"
                                            id="logro_gran_campeon_carne" value="1"
                                            {{ old('logro_gran_campeon_carne') ? 'checked' : '' }}>
                                        <label class="switch-label" for="logro_gran_campeon_carne">Gran Campeón de
                                            Carne</label>
                                    </div>
                                    <div class="switch-modern">
                                        <input type="checkbox" class="switch-input" name="logro_mejor_semental"
                                            id="logro_mejor_semental" value="1"
                                            {{ old('logro_mejor_semental') ? 'checked' : '' }}>
                                        <label class="switch-label" for="logro_mejor_semental">Mejor Semental</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Reproducción -->
                            <div class="achievement-section mb-4">
                                <h6 class="achievement-title achievement-info">
                                    <i class="fas fa-heart"></i> Reproducción
                                </h6>
                                <div class="switch-grid">
                                    <div class="switch-modern">
                                        <input type="checkbox" class="switch-input" name="logro_mejor_madre"
                                            id="logro_mejor_madre" value="1"
                                            {{ old('logro_mejor_madre') ? 'checked' : '' }}>
                                        <label class="switch-label" for="logro_mejor_madre">Mejor Madre</label>
                                    </div>
                                    <div class="switch-modern">
                                        <input type="checkbox" class="switch-input" name="logro_mejor_padre"
                                            id="logro_mejor_padre" value="1"
                                            {{ old('logro_mejor_padre') ? 'checked' : '' }}>
                                        <label class="switch-label" for="logro_mejor_padre">Mejor Padre</label>
                                    </div>
                                    <div class="switch-modern">
                                        <input type="checkbox" class="switch-input" name="logro_mejor_fertilidad"
                                            id="logro_mejor_fertilidad" value="1"
                                            {{ old('logro_mejor_fertilidad') ? 'checked' : '' }}>
                                        <label class="switch-label" for="logro_mejor_fertilidad">Mejor Fertilidad</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Árbol Genealógico -->
                            <div class="achievement-section border-top-modern pt-4 mt-4">
                                <h6 class="achievement-title achievement-secondary mb-3">
                                    <i class="fas fa-sitemap"></i> Árbol Genealógico
                                </h6>
                                <div class="form-group-modern">
                                    <label class="label-modern">
                                        <i class="fas fa-file-upload label-icon"></i> Subir Árbol Genealógico (PDF o
                                        Imagen)
                                    </label>
                                    <div class="file-upload-modern">
                                        <input type="file" name="arbol_genealogico" class="file-input-modern"
                                            id="arbol_genealogico" accept=".pdf,.jpg,.jpeg,.png,.gif">
                                        <label class="file-label-modern" for="arbol_genealogico">
                                            <i class="fas fa-cloud-upload-alt"></i>
                                            <span class="file-text">Seleccione un archivo PDF o imagen...</span>
                                        </label>
                                    </div>
                                    <small class="form-text-modern">
                                        <i class="fas fa-info-circle"></i> Formatos permitidos: PDF, JPG, PNG, GIF. Tamaño
                                        máximo: 10MB
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Botones de Acción -->
            <div class="action-buttons-wrapper">
                <div class="d-flex flex-column flex-md-row justify-content-end gap-3">
                    <a href="{{ route('admin.datos-sanitarios.index') }}" class="btn btn-cancel">
                        <i class="fas fa-times mr-2"></i> Cancelar
                    </a>
                    <button type="submit" class="btn btn-submit">
                        <i class="fas fa-save mr-2"></i> Guardar Registro
                    </button>
                </div>
            </div>
        </form>
    </div>

    <script>
        // Actualizar labels de archivos seleccionados
        const fileInputs = [{
                id: 'certificado_imagen',
                icon: 'fa-file-image'
            },
            {
                id: 'marca_ganado_foto',
                icon: 'fa-file-image'
            },
            {
                id: 'carnet_dueno_foto',
                icon: 'fa-file-image'
            },
            {
                id: 'certificado_campeon_imagen',
                icon: 'fa-trophy'
            },
            {
                id: 'arbol_genealogico',
                icon: 'fa-file-upload'
            }
        ];

        fileInputs.forEach(input => {
            const element = document.getElementById(input.id);
            if (element) {
                element.addEventListener('change', function(e) {
                    const fileName = e.target.files[0]?.name || '';
                    const label = e.target.nextElementSibling;
                    if (label && fileName) {
                        const icon = input.id === 'arbol_genealogico' ?
                            (fileName.endsWith('.pdf') ? 'fa-file-pdf' : 'fa-file-image') :
                            input.icon;
                        label.querySelector('.file-text').textContent = fileName;
                        label.querySelector('i').className = `fas ${icon}`;
                    }
                });
            }
        });
    </script>

    <style>
        /* Variables CSS */
        :root {
            --primary-color: #007bff;
            --primary-dark: #0056b3;
            --success-color: #28a745;
            --info-color: #17a2b8;
            --warning-color: #ffc107;
            --danger-color: #dc3545;
            --secondary-color: #6c757d;
            --light-bg: #f8f9fa;
            --border-radius: 12px;
            --shadow-sm: 0 2px 8px rgba(0, 0, 0, 0.08);
            --shadow-md: 0 4px 16px rgba(0, 0, 0, 0.12);
            --shadow-lg: 0 8px 24px rgba(0, 0, 0, 0.15);
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Page Header */
        .page-header-modern {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 2rem;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-md);
            margin-bottom: 2rem;
        }

        .page-title {
            display: flex;
            align-items: center;
            color: white;
            font-size: 1.75rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .icon-wrapper {
            width: 50px;
            height: 50px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
            font-size: 1.5rem;
            backdrop-filter: blur(10px);
        }

        .page-subtitle {
            color: rgba(255, 255, 255, 0.9);
            margin: 0;
            font-size: 0.95rem;
        }

        .btn-back {
            background: rgba(255, 255, 255, 0.2);
            border: 2px solid rgba(255, 255, 255, 0.3);
            color: white;
            padding: 0.6rem 1.5rem;
            border-radius: 8px;
            font-weight: 500;
            transition: var(--transition);
            backdrop-filter: blur(10px);
        }

        .btn-back:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            color: white;
            text-decoration: none;
        }

        /* Info Card */
        .info-card-modern {
            background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
            border-left: 4px solid var(--info-color);
            padding: 1.5rem;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-sm);
        }

        .info-icon-wrapper {
            width: 40px;
            height: 40px;
            background: var(--info-color);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.2rem;
            margin-right: 1rem;
            flex-shrink: 0;
        }

        .info-content {
            flex: 1;
        }

        .info-title {
            display: block;
            color: #1565c0;
            margin-bottom: 0.5rem;
            font-size: 1rem;
        }

        .info-text {
            color: #424242;
            font-size: 0.9rem;
            line-height: 1.6;
        }

        /* Cards Modernas */
        .card-modern {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-sm);
            border: none;
            overflow: hidden;
            transition: var(--transition);
            margin-bottom: 1.5rem;
        }

        .card-modern:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-lg);
        }

        .card-header-modern {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            padding: 1.25rem 1.5rem;
            border-bottom: 2px solid #e9ecef;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .card-icon-wrapper {
            width: 45px;
            height: 45px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            color: white;
            flex-shrink: 0;
        }

        .card-primary .card-icon-wrapper {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
        }

        .card-success .card-icon-wrapper {
            background: linear-gradient(135deg, var(--success-color) 0%, #1e7e34 100%);
        }

        .card-info .card-icon-wrapper {
            background: linear-gradient(135deg, var(--info-color) 0%, #117a8b 100%);
        }

        .card-warning .card-icon-wrapper {
            background: linear-gradient(135deg, var(--warning-color) 0%, #e0a800 100%);
        }

        .card-title-modern {
            margin: 0;
            font-size: 1.1rem;
            font-weight: 600;
            color: #2c3e50;
        }

        .card-body-modern {
            padding: 1.5rem;
        }

        /* Form Controls */
        .form-group-modern {
            margin-bottom: 1.5rem;
        }

        .label-modern {
            display: flex;
            align-items: center;
            font-weight: 600;
            color: #495057;
            margin-bottom: 0.5rem;
            font-size: 0.95rem;
        }

        .label-icon {
            margin-right: 0.5rem;
            color: var(--primary-color);
            font-size: 0.9rem;
        }

        .form-control-modern {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 0.95rem;
            transition: var(--transition);
            background: white;
        }

        .form-control-modern:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.1);
            transform: translateY(-1px);
        }

        .form-control-modern::placeholder {
            color: #adb5bd;
        }

        .form-text-modern {
            display: block;
            margin-top: 0.5rem;
            font-size: 0.85rem;
            color: #6c757d;
        }

        /* Checkboxes Modernos */
        .checkbox-modern-wrapper {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .checkbox-modern {
            position: relative;
        }

        .checkbox-input {
            position: absolute;
            opacity: 0;
            cursor: pointer;
        }

        .checkbox-label {
            display: flex;
            align-items: center;
            cursor: pointer;
            padding: 0.75rem;
            border-radius: 8px;
            transition: var(--transition);
            border: 2px solid #e9ecef;
        }

        .checkbox-label:hover {
            background: #f8f9fa;
            border-color: var(--success-color);
        }

        .checkbox-input:checked~.checkbox-label {
            background: #e8f5e9;
            border-color: var(--success-color);
        }

        .checkbox-custom {
            width: 22px;
            height: 22px;
            border: 2px solid #adb5bd;
            border-radius: 6px;
            margin-right: 0.75rem;
            position: relative;
            transition: var(--transition);
            flex-shrink: 0;
        }

        .checkbox-input:checked~.checkbox-label .checkbox-custom {
            background: var(--success-color);
            border-color: var(--success-color);
        }

        .checkbox-input:checked~.checkbox-label .checkbox-custom::after {
            content: '\f00c';
            font-family: 'Font Awesome 5 Free';
            font-weight: 900;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: white;
            font-size: 0.75rem;
        }

        .checkbox-text {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: #495057;
        }

        .checkbox-text i {
            color: var(--success-color);
        }

        /* File Upload Moderno */
        .file-upload-modern {
            position: relative;
        }

        .file-input-modern {
            position: absolute;
            opacity: 0;
            width: 0;
            height: 0;
        }

        .file-label-modern {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            border: 2px dashed #dee2e6;
            border-radius: 8px;
            background: #f8f9fa;
            cursor: pointer;
            transition: var(--transition);
            gap: 0.75rem;
        }

        .file-label-modern:hover {
            border-color: var(--primary-color);
            background: #e3f2fd;
            transform: translateY(-2px);
        }

        .file-label-modern i {
            font-size: 1.5rem;
            color: var(--primary-color);
        }

        .file-text {
            color: #6c757d;
            font-weight: 500;
        }

        /* Switches Modernos */
        .achievement-section {
            padding-bottom: 1rem;
        }

        .achievement-title {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #e9ecef;
        }

        .achievement-primary {
            color: var(--primary-color);
            border-bottom-color: var(--primary-color);
        }

        .achievement-success {
            color: var(--success-color);
            border-bottom-color: var(--success-color);
        }

        .achievement-warning {
            color: var(--warning-color);
            border-bottom-color: var(--warning-color);
        }

        .achievement-info {
            color: var(--info-color);
            border-bottom-color: var(--info-color);
        }

        .achievement-secondary {
            color: var(--secondary-color);
            border-bottom-color: var(--secondary-color);
        }

        .switch-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 0.75rem;
        }

        .switch-modern {
            position: relative;
        }

        .switch-input {
            position: absolute;
            opacity: 0;
        }

        .switch-label {
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            background: #f8f9fa;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            cursor: pointer;
            transition: var(--transition);
            font-size: 0.9rem;
            color: #495057;
            position: relative;
            padding-left: 3rem;
        }

        .switch-label::before {
            content: '';
            position: absolute;
            left: 0.75rem;
            width: 40px;
            height: 22px;
            background: #dee2e6;
            border-radius: 11px;
            transition: var(--transition);
        }

        .switch-label::after {
            content: '';
            position: absolute;
            left: 0.9rem;
            width: 18px;
            height: 18px;
            background: white;
            border-radius: 50%;
            transition: var(--transition);
            top: 50%;
            transform: translateY(-50%);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        .switch-input:checked~.switch-label {
            background: #e8f5e9;
            border-color: var(--success-color);
            color: var(--success-color);
        }

        .switch-input:checked~.switch-label::before {
            background: var(--success-color);
        }

        .switch-input:checked~.switch-label::after {
            left: 2.15rem;
        }

        .switch-label:hover {
            border-color: var(--success-color);
            transform: translateY(-1px);
        }

        /* Alert Moderno */
        .alert-modern {
            border-radius: 8px;
            border: none;
            padding: 1rem 1.25rem;
        }

        .alert-info-modern {
            background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
            color: #1565c0;
            border-left: 4px solid var(--info-color);
        }

        .border-top-modern {
            border-top: 2px solid #e9ecef;
        }

        /* Action Buttons */
        .action-buttons-wrapper {
            background: white;
            padding: 1.5rem;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-md);
            margin-top: 2rem;
        }

        .gap-3 {
            gap: 1rem;
        }

        .btn {
            padding: 0.75rem 2rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 1rem;
            transition: var(--transition);
            border: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }

        .btn-cancel {
            background: white;
            color: var(--secondary-color);
            border: 2px solid #dee2e6;
        }

        .btn-cancel:hover {
            background: #f8f9fa;
            border-color: var(--secondary-color);
            transform: translateY(-2px);
            box-shadow: var(--shadow-sm);
            color: var(--secondary-color);
            text-decoration: none;
        }

        .btn-submit {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            color: white;
            box-shadow: 0 4px 12px rgba(0, 123, 255, 0.3);
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 123, 255, 0.4);
            background: linear-gradient(135deg, var(--primary-dark) 0%, #004085 100%);
            color: white;
        }

        .btn-submit:active {
            transform: translateY(0);
        }

        /* Responsive */
        @media (max-width: 991.98px) {
            .page-header-modern {
                padding: 1.5rem;
            }

            .page-title {
                font-size: 1.5rem;
            }

            .icon-wrapper {
                width: 40px;
                height: 40px;
                font-size: 1.2rem;
            }

            .gap-3 {
                flex-direction: column;
            }

            .gap-3 .btn {
                width: 100%;
            }

            .switch-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 575.98px) {
            .container-fluid {
                padding-left: 1rem;
                padding-right: 1rem;
            }

            .card-body-modern {
                padding: 1rem;
            }

            .card-header-modern {
                padding: 1rem;
            }
        }
    </style>
@endsection
