@php
    $datoSanitario = $datoSanitario ?? null;
    $today = now()->toDateString();
    $tomorrow = now()->addDay()->toDateString();
    $fileText = function (?string $path, string $empty, string $filled = 'Deje vacío para mantener el archivo actual o seleccione uno nuevo...') {
        return $path ? $filled : $empty;
    };
@endphp

<div class="p-3 mb-4 ganado-info-panel" id="div_sanidad">
    <div class="custom-control custom-switch">
        <input type="checkbox" class="custom-control-input" id="has_sanity" name="has_sanity" value="1"
            {{ old('has_sanity', $datoSanitario ? true : false) ? 'checked' : '' }}>
        <label class="custom-control-label font-weight-bold" for="has_sanity" id="label_sanity">
            ¿Cuenta con certificados o datos sanitarios del animal?
        </label>
    </div>
    <small class="text-muted d-block mt-2">
        Activa esta sección si tienes vacunas, certificados, marca, carnet del dueño, genealogía o reconocimientos.
    </small>
</div>

<div id="sanidad_detail_zone" style="display: none;">
    <div class="p-3 mb-4 ganado-info-panel">
        <h6 class="font-weight-bold mb-3 text-success">
            <i class="fas fa-shield-alt mr-1"></i> Vacunación y tratamiento
        </h6>

        <div class="form-group">
            <label>Otras Vacunas</label>
            <input type="text" name="vacuna" class="form-control"
                value="{{ old('vacuna', $datoSanitario->vacuna ?? '') }}"
                placeholder="Ej: Triple, Brucelosis, etc.">
        </div>

        <div class="sanitary-option-grid mb-3">
            <label class="sanitary-check-card" for="vacunado_fiebre_aftosa">
                <input type="checkbox" name="vacunado_fiebre_aftosa" id="vacunado_fiebre_aftosa"
                    value="1" {{ old('vacunado_fiebre_aftosa', $datoSanitario->vacunado_fiebre_aftosa ?? false) ? 'checked' : '' }}>
                <span><i class="fas fa-shield-alt"></i></span>
                <strong>Libre de Fiebre Aftosa</strong>
            </label>
            <label class="sanitary-check-card" for="vacunado_antirabica">
                <input type="checkbox" name="vacunado_antirabica" id="vacunado_antirabica"
                    value="1" {{ old('vacunado_antirabica', $datoSanitario->vacunado_antirabica ?? false) ? 'checked' : '' }}>
                <span><i class="fas fa-shield-alt"></i></span>
                <strong>Vacuna Antirrábica</strong>
            </label>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label>Tratamiento</label>
                <input type="text" name="tratamiento" class="form-control"
                    value="{{ old('tratamiento', $datoSanitario->tratamiento ?? '') }}"
                    placeholder="Tipo de tratamiento aplicado">
            </div>
            <div class="col-md-6 mb-3">
                <label>Medicamento</label>
                <input type="text" name="medicamento" class="form-control"
                    value="{{ old('medicamento', $datoSanitario->medicamento ?? '') }}"
                    placeholder="Nombre del medicamento">
            </div>
            <div class="col-md-6 mb-3">
                <label>Fecha de Aplicación</label>
                <input type="date" name="fecha_aplicacion" class="form-control"
                    value="{{ old('fecha_aplicacion', $datoSanitario->fecha_aplicacion ?? '') }}"
                    max="{{ $today }}">
            </div>
            <div class="col-md-6 mb-3">
                <label>Próxima Fecha</label>
                <input type="date" name="proxima_fecha" class="form-control"
                    value="{{ old('proxima_fecha', $datoSanitario->proxima_fecha ?? '') }}"
                    min="{{ $tomorrow }}">
            </div>
            <div class="col-md-12 mb-3">
                <label>Veterinario</label>
                <input type="text" name="veterinario" class="form-control"
                    value="{{ old('veterinario', $datoSanitario->veterinario ?? '') }}"
                    placeholder="Nombre del veterinario responsable">
            </div>
            <div class="col-md-12">
                <label>Observaciones</label>
                <textarea name="observaciones" class="form-control" rows="3"
                    placeholder="Notas adicionales sobre el tratamiento o estado del animal">{{ old('observaciones', $datoSanitario->observaciones ?? '') }}</textarea>
            </div>
        </div>
    </div>

    <div class="p-3 mb-4 ganado-info-panel">
        <h6 class="font-weight-bold mb-3 text-success">
            <i class="fas fa-id-card mr-1"></i> Identificación y dueño
        </h6>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label>Marca del Ganado</label>
                <input type="text" name="marca_ganado" class="form-control"
                    value="{{ old('marca_ganado', $datoSanitario->marca_ganado ?? '') }}"
                    placeholder="Ej: marca registrada del animal">
            </div>
            <div class="col-md-6 mb-3">
                <label>Señal o #</label>
                <input type="text" name="senal_numero" class="form-control"
                    value="{{ old('senal_numero', $datoSanitario->senal_numero ?? '') }}"
                    placeholder="Ej: #12345, señal A-001">
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
                    <strong>{{ $fileText($datoSanitario->marca_ganado_foto ?? null, 'Seleccione una imagen de la marca...') }}</strong>
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
                placeholder="Ej: Juan Pérez">
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
                    <strong>{{ $fileText($datoSanitario->carnet_dueno_foto ?? null, 'Seleccione una imagen del carnet...') }}</strong>
                    <small>Tamaño máximo: 5MB. Formatos: JPG, PNG, GIF.</small>
                </span>
            </label>
            <input type="file" name="carnet_dueno_foto" class="maquinaria-upload-input sanitary-file-input"
                id="carnet_dueno_foto" accept="image/*">
        </div>
    </div>

    <div class="p-3 mb-4 ganado-info-panel">
        <h6 class="font-weight-bold mb-3 text-success">
            <i class="fas fa-certificate mr-1"></i> Certificados y genealogía
        </h6>

        @include('datos_sanitarios._current_file_preview', [
            'path' => $datoSanitario->documento_pdf ?? null,
            'title' => 'Documento sanitario actual',
            'imageTitle' => 'Documento sanitario',
            'icon' => 'fas fa-file-pdf',
            'allowDownloadOnly' => true,
        ])

        <div class="form-group">
            <label>Documento sanitario PDF</label>
            <label for="pdf-input" class="maquinaria-upload-zone sanitary-upload-zone">
                <span class="maquinaria-upload-zone__icon"><i class="fas fa-file-pdf"></i></span>
                <span class="maquinaria-upload-zone__content">
                    <strong>{{ $fileText($datoSanitario->documento_pdf ?? null, 'Seleccione un certificado PDF...') }}</strong>
                    <small>Opcional. Tamaño máximo: 10MB.</small>
                </span>
            </label>
            <input type="file" name="documento_pdf" id="pdf-input" class="maquinaria-upload-input" accept=".pdf">
            <span id="pdf-file-name" class="text-success small mt-1 d-block"></span>
        </div>

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
                    <strong>{{ $fileText($datoSanitario->certificado_imagen ?? null, 'Seleccione una imagen del certificado...') }}</strong>
                    <small>Tamaño máximo: 5MB. Formatos: JPG, PNG, GIF.</small>
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
                    <strong>{{ $fileText($datoSanitario->certificado_campeon_imagen ?? null, 'Seleccione una imagen del certificado...') }}</strong>
                    <small>Tamaño máximo: 5MB. Formatos: JPG, PNG, GIF.</small>
                </span>
            </label>
            <input type="file" name="certificado_campeon_imagen" class="maquinaria-upload-input sanitary-file-input"
                id="certificado_campeon_imagen" accept="image/*">
        </div>

        @include('datos_sanitarios._current_file_preview', [
            'path' => $datoSanitario->arbol_genealogico ?? null,
            'title' => 'Árbol genealógico actual',
            'imageTitle' => 'Árbol genealógico',
            'icon' => 'fas fa-sitemap',
            'allowDownloadOnly' => true,
        ])

        <div class="form-group mb-0">
            <label>Árbol Genealógico (PDF o Imagen)</label>
            <label for="arbol_genealogico" class="maquinaria-upload-zone sanitary-upload-zone">
                <span class="maquinaria-upload-zone__icon"><i class="fas fa-cloud-upload-alt"></i></span>
                <span class="maquinaria-upload-zone__content">
                    <strong>{{ $fileText($datoSanitario->arbol_genealogico ?? null, 'Seleccione un archivo PDF o imagen...') }}</strong>
                    <small>Formatos: PDF, JPG, PNG, GIF. Tamaño máximo: 10MB.</small>
                </span>
            </label>
            <input type="file" name="arbol_genealogico" class="maquinaria-upload-input sanitary-file-input"
                id="arbol_genealogico" accept=".pdf,.jpg,.jpeg,.png,.gif">
        </div>
    </div>

    <div class="p-3 mb-4 ganado-info-panel">
        <h6 class="font-weight-bold mb-3 text-success">
            <i class="fas fa-trophy mr-1"></i> Logros y reconocimientos
        </h6>

        <div class="sanitary-achievement-group">
            <h6><i class="fas fa-star"></i> Belleza y estructura</h6>
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
            <h6><i class="fas fa-tint"></i> Producción de leche</h6>
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
            <h6><i class="fas fa-chart-line"></i> Producción de carne</h6>
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
</div>
