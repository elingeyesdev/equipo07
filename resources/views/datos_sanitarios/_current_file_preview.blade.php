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
                <small>{{ !$allowDownloadOnly ? 'Click para abrir la vista previa' : 'Archivo guardado actualmente' }}</small>
            </div>
        </div>

        @if ($isImage && !$allowDownloadOnly)
            <img src="{{ asset('storage/' . $path) }}" alt="{{ $imageTitle ?? 'Archivo sanitario' }}"
                class="sanitary-current-file__image" data-file-viewer
                data-file-url="{{ asset('storage/' . $path) }}" data-file-title="{{ $imageTitle ?? 'Archivo sanitario' }}"
                title="Click para abrir vista previa">
        @else
            <a href="{{ asset('storage/' . $path) }}" class="btn btn-sm btn-outline-agro"
                @unless ($allowDownloadOnly)
                    data-file-viewer data-file-url="{{ asset('storage/' . $path) }}"
                    data-file-title="{{ $imageTitle ?? $title ?? 'Archivo sanitario' }}"
                @else
                    target="_blank"
                @endunless>
                <i class="fas fa-eye mr-1"></i> Ver archivo
            </a>
        @endif
    </div>
@endif
