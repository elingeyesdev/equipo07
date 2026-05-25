@php
    $path = $path ?? null;
    $allowDownloadOnly = $allowDownloadOnly ?? false;
    $isImage = $path && preg_match('/\.(jpg|jpeg|png|gif|webp)$/i', $path);
@endphp

@if ($path)
    <div class="sanitary-current-file">
        <div class="sanitary-current-file__header">
            <span><i class="{{ $icon ?? 'fas fa-file' }}"></i></span>
            <div>
                <strong>{{ $title ?? 'Archivo actual' }}</strong>
                <small>{{ $isImage && !$allowDownloadOnly ? 'Click en la imagen para abrir la vista previa' : 'Archivo guardado actualmente' }}</small>
            </div>
        </div>

        @if ($isImage && !$allowDownloadOnly)
            <img src="{{ asset('storage/' . $path) }}" alt="{{ $imageTitle ?? 'Archivo sanitario' }}"
                class="sanitary-current-file__image" data-sanitary-image-viewer
                data-image-url="{{ asset('storage/' . $path) }}" data-image-title="{{ $imageTitle ?? 'Archivo sanitario' }}"
                data-download-name="{{ basename($path) }}" title="Click para abrir vista previa">
        @else
            <a href="{{ asset('storage/' . $path) }}" target="_blank" class="btn btn-sm btn-outline-agro">
                <i class="fas fa-download mr-1"></i> Ver/Descargar archivo
            </a>
        @endif
    </div>
@endif
