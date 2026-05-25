@php
    $isEdit = $mode === 'edit' && $datoSanitario;
    $today = now()->toDateString();
    $tomorrow = now()->addDay()->toDateString();
    $wizardSteps = [
        [
            'icon' => 'fas fa-paw',
            'title' => 'Animal y vacunas',
            'description' => 'Selecciona el animal y registra vacunas aplicadas.',
        ],
        [
            'icon' => 'fas fa-pills',
            'title' => 'Tratamiento',
            'description' => 'Documenta tratamiento, fechas, veterinario y observaciones.',
        ],
        [
            'icon' => 'fas fa-id-card',
            'title' => 'Identificación',
            'description' => 'Registra marca, señal e información del dueño.',
        ],
        [
            'icon' => 'fas fa-certificate',
            'title' => 'Certificados',
            'description' => 'Adjunta certificados sanitarios, premios y genealogía.',
        ],
        [
            'icon' => 'fas fa-trophy',
            'title' => 'Logros',
            'description' => 'Marca reconocimientos de belleza, producción y reproducción.',
        ],
    ];

    $fileText = function (?string $path, string $empty, string $filled = 'Deje vacío para mantener el archivo actual o seleccione uno nuevo...') {
        return $path ? $filled : $empty;
    };
@endphp

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show sanitary-wizard-alert" role="alert">
        <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show sanitary-wizard-alert" role="alert">
        <i class="fas fa-exclamation-circle mr-2"></i> {{ session('error') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-danger sanitary-wizard-alert">
        <strong>Revisa estos datos antes de guardar:</strong>
        <ul class="mb-0 mt-2">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ $formAction }}" method="POST" enctype="multipart/form-data" id="formDatosSanitarios">
    @csrf
    @if ($formMethod !== 'POST')
        @method($formMethod)
    @endif

    <div class="maquinaria-wizard sanitary-wizard" data-sanitary-wizard>
        <div class="maquinaria-wizard__shell">
            <div class="maquinaria-wizard__hero">
                <div>
                    <span class="maquinaria-wizard__eyebrow">Datos sanitarios del animal</span>
                    <h3 class="maquinaria-wizard__title mb-1">
                        <i class="{{ $isEdit ? 'fas fa-edit' : 'fas fa-clipboard-check' }} mr-2"></i>
                        {{ $isEdit ? 'Editar registro sanitario' : 'Nuevo registro sanitario' }}
                    </h3>
                    <p class="maquinaria-wizard__subtitle mb-0">
                        Completa la información progresivamente. Ningún campo del formulario original fue eliminado.
                    </p>
                </div>
                <span class="badge badge-success maquinaria-wizard__badge" data-wizard-current-label>
                    Paso 1 de {{ count($wizardSteps) }}
                </span>
            </div>

            <div class="sanitary-wizard-note">
                <i class="fas fa-info-circle"></i>
                <span>
                    Puede {{ $isEdit ? 'actualizar' : 'agregar' }} datos sanitarios a cualquier animal registrado.
                    Los animales sin publicar aparecerán marcados con
                    <span class="badge badge-warning">[Sin publicar]</span>.
                </span>
            </div>

            <div class="maquinaria-wizard__progress" role="tablist" aria-label="Pasos del registro sanitario">
                @foreach ($wizardSteps as $index => $step)
                    <button type="button"
                        class="maquinaria-wizard__step-indicator {{ $index === 0 ? 'is-active' : '' }}"
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
                <section class="card card-outline card-success shadow-sm mb-4 maquinaria-wizard-step is-active"
                    data-wizard-step="0">
                    <div class="card-header d-flex justify-content-between align-items-start">
                        <div>
                            <h3 class="card-title mb-0">
                                <i class="fas fa-paw mr-2"></i> Animal y vacunaciones
                            </h3>
                            <small class="text-muted">Relaciona el registro con un animal y marca vacunas aplicadas.</small>
                        </div>
                        <span class="badge badge-success">Paso 1 de {{ count($wizardSteps) }}</span>
                    </div>

                    <div class="card-body">
                        <div class="form-group">
                            <label>Animal</label>
                            <select name="ganado_id" class="form-control">
                                <option value="">Seleccione un animal (opcional)...</option>
                                @foreach ($ganados as $g)
                                    <option value="{{ $g->id }}"
                                        {{ old('ganado_id', $datoSanitario->ganado_id ?? '') == $g->id ? 'selected' : '' }}>
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
                            <small class="form-text text-muted">Puede seleccionar cualquier animal registrado, incluso si no tiene fecha de publicación.</small>
                        </div>

                        <div class="form-group">
                            <label>Otras Vacunas</label>
                            <input type="text" name="vacuna" class="form-control"
                                value="{{ old('vacuna', $datoSanitario->vacuna ?? '') }}"
                                placeholder="Ej: Triple, Brucelosis, etc. (opcional)">
                        </div>

                        <div class="sanitary-option-grid">
                            <label class="sanitary-check-card" for="vacunado_fiebre_aftosa">
                                <input type="checkbox" name="vacunado_fiebre_aftosa" id="vacunado_fiebre_aftosa"
                                    value="1"
                                    {{ old('vacunado_fiebre_aftosa', $datoSanitario->vacunado_fiebre_aftosa ?? false) ? 'checked' : '' }}>
                                <span><i class="fas fa-shield-alt"></i></span>
                                <strong>Vacunado de Libre de Fiebre Aftosa</strong>
                            </label>
                            <label class="sanitary-check-card" for="vacunado_antirabica">
                                <input type="checkbox" name="vacunado_antirabica" id="vacunado_antirabica"
                                    value="1"
                                    {{ old('vacunado_antirabica', $datoSanitario->vacunado_antirabica ?? false) ? 'checked' : '' }}>
                                <span><i class="fas fa-shield-alt"></i></span>
                                <strong>Vacunado de Antirrábica</strong>
                            </label>
                        </div>
                    </div>
                </section>

                <section class="card card-outline card-success shadow-sm mb-4 maquinaria-wizard-step" data-wizard-step="1">
                    <div class="card-header d-flex justify-content-between align-items-start">
                        <div>
                            <h3 class="card-title mb-0">
                                <i class="fas fa-pills mr-2"></i> Tratamientos y medicamentos
                            </h3>
                            <small class="text-muted">Registra tratamiento, medicamento, fechas, veterinario y observaciones.</small>
                        </div>
                        <span class="badge badge-success">Paso 2 de {{ count($wizardSteps) }}</span>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Tratamiento</label>
                                    <input type="text" name="tratamiento" class="form-control"
                                        value="{{ old('tratamiento', $datoSanitario->tratamiento ?? '') }}"
                                        placeholder="Tipo de tratamiento aplicado">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Medicamento</label>
                                    <input type="text" name="medicamento" class="form-control"
                                        value="{{ old('medicamento', $datoSanitario->medicamento ?? '') }}"
                                        placeholder="Nombre del medicamento">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Fecha de Aplicación</label>
                                    <input type="date" name="fecha_aplicacion" class="form-control"
                                        value="{{ old('fecha_aplicacion', $datoSanitario->fecha_aplicacion ?? '') }}"
                                        max="{{ $today }}">
                                    <small class="form-text text-muted">Debe ser hoy o una fecha pasada.</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Próxima Fecha</label>
                                    <input type="date" name="proxima_fecha" class="form-control"
                                        value="{{ old('proxima_fecha', $datoSanitario->proxima_fecha ?? '') }}"
                                        min="{{ $tomorrow }}">
                                    <small class="form-text text-muted">Debe ser una fecha futura.</small>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Veterinario</label>
                            <input type="text" name="veterinario" class="form-control"
                                value="{{ old('veterinario', $datoSanitario->veterinario ?? '') }}"
                                placeholder="Nombre del veterinario responsable">
                        </div>

                        <div class="form-group mb-0">
                            <label>Observaciones</label>
                            <textarea name="observaciones" class="form-control" rows="4"
                                placeholder="Notas adicionales sobre el tratamiento o estado del animal">{{ old('observaciones', $datoSanitario->observaciones ?? '') }}</textarea>
                        </div>
                    </div>
                </section>

                <section class="card card-outline card-success shadow-sm mb-4 maquinaria-wizard-step" data-wizard-step="2">
                    <div class="card-header d-flex justify-content-between align-items-start">
                        <div>
                            <h3 class="card-title mb-0">
                                <i class="fas fa-id-card mr-2"></i> Identificación y dueño
                            </h3>
                            <small class="text-muted">Marca del animal, señal, foto de marca y carnet del dueño.</small>
                        </div>
                        <span class="badge badge-success">Paso 3 de {{ count($wizardSteps) }}</span>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Marca del Ganado</label>
                                    <input type="text" name="marca_ganado" class="form-control"
                                        value="{{ old('marca_ganado', $datoSanitario->marca_ganado ?? '') }}"
                                        placeholder="Ej: Marca registrada del animal">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Señal o #</label>
                                    <input type="text" name="senal_numero" class="form-control"
                                        value="{{ old('senal_numero', $datoSanitario->senal_numero ?? '') }}"
                                        placeholder="Ej: #12345, Señal A-001">
                                </div>
                            </div>
                        </div>

                        @include('datos_sanitarios._current_file_preview', [
                            'path' => $datoSanitario->marca_ganado_foto ?? null,
                            'title' => 'Foto de marca actual',
                            'imageTitle' => 'Foto de marca',
                            'icon' => 'fas fa-image',
                        ])

                        <div class="form-group">
                            <label>Foto de la Marca</label>
                            <label for="marca_ganado_foto" class="maquinaria-upload-zone sanitary-upload-zone">
                                <span class="maquinaria-upload-zone__icon"><i class="fas fa-cloud-upload-alt"></i></span>
                                <span class="maquinaria-upload-zone__content">
                                    <strong>{{ $fileText($datoSanitario->marca_ganado_foto ?? null, 'Seleccione una imagen de la marca...', 'Deje vacío para mantener la imagen actual o seleccione una nueva...') }}</strong>
                                    <small>Tamaño máximo: 5MB. Formatos: JPG, PNG, GIF.</small>
                                </span>
                            </label>
                            <input type="file" name="marca_ganado_foto" class="maquinaria-upload-input sanitary-file-input"
                                id="marca_ganado_foto" accept="image/*">
                        </div>

                        <div class="form-group">
                            <label>Nombre del Dueño</label>
                            <input type="text" name="nombre_dueno" class="form-control"
                                value="{{ old('nombre_dueno', $datoSanitario->nombre_dueno ?? '') }}"
                                placeholder="Ej: Juan Pérez, María González">
                        </div>

                        @include('datos_sanitarios._current_file_preview', [
                            'path' => $datoSanitario->carnet_dueno_foto ?? null,
                            'title' => 'Carnet del dueño actual',
                            'imageTitle' => 'Carnet del dueño',
                            'icon' => 'fas fa-id-card',
                        ])

                        <div class="form-group mb-0">
                            <label>Foto del Carnet del Dueño</label>
                            <label for="carnet_dueno_foto" class="maquinaria-upload-zone sanitary-upload-zone">
                                <span class="maquinaria-upload-zone__icon"><i class="fas fa-cloud-upload-alt"></i></span>
                                <span class="maquinaria-upload-zone__content">
                                    <strong>{{ $fileText($datoSanitario->carnet_dueno_foto ?? null, 'Seleccione una imagen...', 'Deje vacío para mantener la imagen actual o seleccione una nueva...') }}</strong>
                                    <small>Tamaño máximo: 5MB. Formatos: JPG, PNG, GIF.</small>
                                </span>
                            </label>
                            <input type="file" name="carnet_dueno_foto" class="maquinaria-upload-input sanitary-file-input"
                                id="carnet_dueno_foto" accept="image/*">
                        </div>
                    </div>
                </section>

                <section class="card card-outline card-success shadow-sm mb-4 maquinaria-wizard-step" data-wizard-step="3">
                    <div class="card-header d-flex justify-content-between align-items-start">
                        <div>
                            <h3 class="card-title mb-0">
                                <i class="fas fa-certificate mr-2"></i> Certificados y genealogía
                            </h3>
                            <small class="text-muted">Adjunta certificado SENASAG, certificado de campeón y árbol genealógico.</small>
                        </div>
                        <span class="badge badge-success">Paso 4 de {{ count($wizardSteps) }}</span>
                    </div>

                    <div class="card-body">
                        @include('datos_sanitarios._current_file_preview', [
                            'path' => $datoSanitario->certificado_imagen ?? null,
                            'title' => 'Certificado SENASAG actual',
                            'imageTitle' => 'Certificado sanitario',
                            'icon' => 'fas fa-certificate',
                        ])

                        <div class="form-group">
                            <label>Imagen del Certificado SENASAG</label>
                            <label for="certificado_imagen" class="maquinaria-upload-zone sanitary-upload-zone">
                                <span class="maquinaria-upload-zone__icon"><i class="fas fa-cloud-upload-alt"></i></span>
                                <span class="maquinaria-upload-zone__content">
                                    <strong>{{ $fileText($datoSanitario->certificado_imagen ?? null, 'Seleccione una imagen...', 'Deje vacío para mantener la imagen actual o seleccione una nueva...') }}</strong>
                                    <small>Tamaño máximo: 5MB. Formatos permitidos: JPG, PNG, GIF.</small>
                                </span>
                            </label>
                            <input type="file" name="certificado_imagen" class="maquinaria-upload-input sanitary-file-input"
                                id="certificado_imagen" accept="image/*">
                        </div>

                        @include('datos_sanitarios._current_file_preview', [
                            'path' => $datoSanitario->certificado_campeon_imagen ?? null,
                            'title' => 'Certificado de campeón actual',
                            'imageTitle' => 'Certificado de campeón',
                            'icon' => 'fas fa-trophy',
                        ])

                        <div class="form-group">
                            <label>Imagen del Certificado de Campeón</label>
                            <label for="certificado_campeon_imagen" class="maquinaria-upload-zone sanitary-upload-zone">
                                <span class="maquinaria-upload-zone__icon"><i class="fas fa-cloud-upload-alt"></i></span>
                                <span class="maquinaria-upload-zone__content">
                                    <strong>{{ $fileText($datoSanitario->certificado_campeon_imagen ?? null, 'Seleccione una imagen del certificado...', 'Deje vacío para mantener la imagen actual o seleccione una nueva...') }}</strong>
                                    <small>Tamaño máximo: 5MB. Formatos permitidos: JPG, PNG, GIF.</small>
                                </span>
                            </label>
                            <input type="file" name="certificado_campeon_imagen"
                                class="maquinaria-upload-input sanitary-file-input" id="certificado_campeon_imagen"
                                accept="image/*">
                        </div>

                        @include('datos_sanitarios._current_file_preview', [
                            'path' => $datoSanitario->arbol_genealogico ?? null,
                            'title' => 'Árbol genealógico actual',
                            'imageTitle' => 'Árbol genealógico',
                            'icon' => 'fas fa-sitemap',
                            'allowDownloadOnly' => true,
                        ])

                        <div class="form-group mb-0">
                            <label>Subir Árbol Genealógico (PDF o Imagen)</label>
                            <label for="arbol_genealogico" class="maquinaria-upload-zone sanitary-upload-zone">
                                <span class="maquinaria-upload-zone__icon"><i class="fas fa-cloud-upload-alt"></i></span>
                                <span class="maquinaria-upload-zone__content">
                                    <strong>{{ $fileText($datoSanitario->arbol_genealogico ?? null, 'Seleccione un archivo PDF o imagen...', 'Deje vacío para mantener el archivo actual o seleccione uno nuevo...') }}</strong>
                                    <small>Formatos permitidos: PDF, JPG, PNG, GIF. Tamaño máximo: 10MB.</small>
                                </span>
                            </label>
                            <input type="file" name="arbol_genealogico" class="maquinaria-upload-input sanitary-file-input"
                                id="arbol_genealogico" accept=".pdf,.jpg,.jpeg,.png,.gif">
                        </div>
                    </div>
                </section>

                <section class="card card-outline card-success shadow-sm mb-4 maquinaria-wizard-step" data-wizard-step="4">
                    <div class="card-header d-flex justify-content-between align-items-start">
                        <div>
                            <h3 class="card-title mb-0">
                                <i class="fas fa-trophy mr-2"></i> Logros y reconocimientos
                            </h3>
                            <small class="text-muted">Marca todos los logros que correspondan al animal.</small>
                        </div>
                        <span class="badge badge-success">Paso 5 de {{ count($wizardSteps) }}</span>
                    </div>

                    <div class="card-body">
                        <div class="sanitary-achievement-group">
                            <h6><i class="fas fa-star"></i> Belleza y Estructura</h6>
                            <div class="sanitary-option-grid">
                                @foreach ([
                                    'logro_campeon_raza' => 'Campeón de Raza',
                                    'logro_gran_campeon_macho' => 'Gran Campeón Macho',
                                    'logro_gran_campeon_hembra' => 'Gran Campeón Hembra',
                                    'logro_mejor_ubre' => 'Mejor Ubre',
                                ] as $field => $label)
                                    <label class="sanitary-check-card" for="{{ $field }}">
                                        <input type="checkbox" name="{{ $field }}" id="{{ $field }}"
                                            value="1" {{ old($field, $datoSanitario->{$field} ?? false) ? 'checked' : '' }}>
                                        <span><i class="fas fa-award"></i></span>
                                        <strong>{{ $label }}</strong>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <div class="sanitary-achievement-group">
                            <h6><i class="fas fa-tint"></i> Producción de Leche</h6>
                            <div class="sanitary-option-grid">
                                @foreach ([
                                    'logro_campeona_litros_dia' => 'Campeona en Litros/Día',
                                    'logro_mejor_lactancia' => 'Mejor Lactancia',
                                    'logro_mejor_calidad_leche' => 'Mejor Calidad de Leche',
                                ] as $field => $label)
                                    <label class="sanitary-check-card" for="{{ $field }}">
                                        <input type="checkbox" name="{{ $field }}" id="{{ $field }}"
                                            value="1" {{ old($field, $datoSanitario->{$field} ?? false) ? 'checked' : '' }}>
                                        <span><i class="fas fa-award"></i></span>
                                        <strong>{{ $label }}</strong>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <div class="sanitary-achievement-group">
                            <h6><i class="fas fa-chart-line"></i> Producción de Carne</h6>
                            <div class="sanitary-option-grid">
                                @foreach ([
                                    'logro_mejor_novillo' => 'Mejor Novillo',
                                    'logro_gran_campeon_carne' => 'Gran Campeón de Carne',
                                    'logro_mejor_semental' => 'Mejor Semental',
                                ] as $field => $label)
                                    <label class="sanitary-check-card" for="{{ $field }}">
                                        <input type="checkbox" name="{{ $field }}" id="{{ $field }}"
                                            value="1" {{ old($field, $datoSanitario->{$field} ?? false) ? 'checked' : '' }}>
                                        <span><i class="fas fa-award"></i></span>
                                        <strong>{{ $label }}</strong>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <div class="sanitary-achievement-group mb-0">
                            <h6><i class="fas fa-heart"></i> Reproducción</h6>
                            <div class="sanitary-option-grid">
                                @foreach ([
                                    'logro_mejor_madre' => 'Mejor Madre',
                                    'logro_mejor_padre' => 'Mejor Padre',
                                    'logro_mejor_fertilidad' => 'Mejor Fertilidad',
                                ] as $field => $label)
                                    <label class="sanitary-check-card" for="{{ $field }}">
                                        <input type="checkbox" name="{{ $field }}" id="{{ $field }}"
                                            value="1" {{ old($field, $datoSanitario->{$field} ?? false) ? 'checked' : '' }}>
                                        <span><i class="fas fa-award"></i></span>
                                        <strong>{{ $label }}</strong>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </section>
            </div>

            <div class="maquinaria-wizard__actions">
                <a href="{{ route('admin.datos-sanitarios.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-times mr-1"></i> Cancelar
                </a>
                <div class="maquinaria-wizard__action-group">
                    <button type="button" class="btn btn-outline-agro" data-wizard-prev disabled>
                        <i class="fas fa-chevron-left mr-1"></i> Anterior
                    </button>
                    <button type="button" class="btn btn-success" data-wizard-next>
                        Siguiente <i class="fas fa-chevron-right ml-1"></i>
                    </button>
                    <button type="submit" class="btn btn-success d-none" data-wizard-submit>
                        <i class="fas fa-save mr-1"></i> {{ $isEdit ? 'Actualizar Registro' : 'Guardar Registro' }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const wizard = document.querySelector('[data-sanitary-wizard]');
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
        let currentStep = 0;

        form.setAttribute('novalidate', 'novalidate');

        const fieldStepMap = {
            ganado_id: 0,
            vacuna: 0,
            vacunado_fiebre_aftosa: 0,
            vacunado_antirabica: 0,
            tratamiento: 1,
            medicamento: 1,
            fecha_aplicacion: 1,
            proxima_fecha: 1,
            veterinario: 1,
            observaciones: 1,
            marca_ganado: 2,
            senal_numero: 2,
            marca_ganado_foto: 2,
            nombre_dueno: 2,
            carnet_dueno_foto: 2,
            certificado_imagen: 3,
            certificado_campeon_imagen: 3,
            arbol_genealogico: 3,
            logro_campeon_raza: 4,
            logro_gran_campeon_macho: 4,
            logro_gran_campeon_hembra: 4,
            logro_mejor_ubre: 4,
            logro_campeona_litros_dia: 4,
            logro_mejor_lactancia: 4,
            logro_mejor_calidad_leche: 4,
            logro_mejor_novillo: 4,
            logro_gran_campeon_carne: 4,
            logro_mejor_semental: 4,
            logro_mejor_madre: 4,
            logro_mejor_padre: 4,
            logro_mejor_fertilidad: 4,
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
            return fieldStepMap[normalizeFieldName(key)] ?? 0;
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
            const uploadZone = control.previousElementSibling?.classList?.contains('sanitary-upload-zone') ?
                control.previousElementSibling : null;
            if (uploadZone) uploadZone.classList.add('is-invalid');
            feedbackElement(control).textContent = message;
        }

        function clearFieldError(control) {
            control.classList.remove('is-invalid');
            const uploadZone = control.previousElementSibling?.classList?.contains('sanitary-upload-zone') ?
                control.previousElementSibling : null;
            if (uploadZone) uploadZone.classList.remove('is-invalid');
            const holder = control.closest('.input-group') || control;
            const feedback = holder.nextElementSibling;
            if (feedback && feedback.classList.contains('wizard-field-error')) feedback.textContent = '';
        }

        function validateStep(index, shouldFocus = true) {
            const controls = Array.from(steps[index].querySelectorAll('input, select, textarea'))
                .filter(control => !control.disabled && control.type !== 'hidden' && !control.classList.contains('maquinaria-upload-input'));
            const invalidControls = [];

            controls.forEach(control => {
                clearFieldError(control);
                if (!control.checkValidity()) {
                    invalidControls.push(control);
                    setFieldError(control, control.validationMessage || 'Revisa este campo antes de continuar.');
                }
            });

            steps[index].classList.toggle('has-errors', invalidControls.length > 0);
            indicators[index].classList.toggle('has-errors', invalidControls.length > 0);

            if (invalidControls.length > 0) {
                errorSummary.textContent = 'Revisa los campos marcados antes de continuar.';
                errorSummary.classList.remove('d-none');
                if (shouldFocus) {
                    invalidControls[0].focus({ preventScroll: true });
                    invalidControls[0].scrollIntoView({ behavior: 'smooth', block: 'center' });
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

            indicators.forEach((indicator, indicatorIndex) => {
                indicator.classList.toggle('is-active', indicatorIndex === currentStep);
                indicator.classList.toggle('is-complete', indicatorIndex < currentStep);
                indicator.setAttribute('aria-current', indicatorIndex === currentStep ? 'step' : 'false');
                const status = indicator.querySelector('[data-wizard-step-status]');
                if (!status) return;
                status.textContent = indicatorIndex < currentStep ? 'Completado' :
                    indicatorIndex === currentStep ? 'En progreso' : 'Pendiente';
            });

            prevButton.disabled = currentStep === 0;
            nextButton.classList.toggle('d-none', currentStep === steps.length - 1);
            submitButton.classList.toggle('d-none', currentStep !== steps.length - 1);
            errorSummary.classList.add('d-none');
            if (progressBar) progressBar.style.width = `${((currentStep + 1) / steps.length) * 100}%`;
            if (currentLabel) currentLabel.textContent = `Paso ${currentStep + 1} de ${steps.length}`;
            wizard.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }

        indicators.forEach(indicator => {
            indicator.addEventListener('click', function() {
                const targetStep = Number(this.getAttribute('data-wizard-go-to'));
                if (targetStep <= currentStep || validateUntil(targetStep)) showStep(targetStep);
            });
        });

        prevButton.addEventListener('click', () => showStep(currentStep - 1));
        nextButton.addEventListener('click', () => {
            if (validateStep(currentStep)) showStep(currentStep + 1);
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

        const firstServerError = Object.keys(serverErrors)[0];
        if (firstServerError) {
            showStep(getStepForField(firstServerError));
            errorSummary.textContent = 'Hay datos por corregir antes de guardar el registro sanitario.';
            errorSummary.classList.remove('d-none');
        } else {
            showStep(0);
        }

        wizard.querySelectorAll('.sanitary-file-input').forEach(input => {
            input.addEventListener('change', function(event) {
                const fileName = event.target.files[0]?.name;
                const label = input.previousElementSibling;
                if (!label || !fileName) return;
                label.classList.add('has-files');
                const text = label.querySelector('.maquinaria-upload-zone__content strong');
                if (text) text.textContent = fileName;
            });
        });
    });
</script>
