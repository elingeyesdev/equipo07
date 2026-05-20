<div class="modal fade sanitary-image-modal" id="sanitaryImageViewerModal" tabindex="-1" role="dialog"
    aria-labelledby="sanitaryImageViewerTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content sanitary-image-modal__content">
            <div class="modal-header sanitary-image-modal__header">
                <div>
                    <span class="sanitary-image-modal__eyebrow">Vista previa</span>
                    <h5 class="modal-title" id="sanitaryImageViewerTitle">Imagen sanitaria</h5>
                </div>
                <button type="button" class="close sanitary-image-modal__close" data-dismiss="modal"
                    aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body sanitary-image-modal__body">
                <div class="sanitary-image-modal__loading" id="sanitaryImageLoading">
                    <i class="fas fa-spinner fa-spin"></i>
                    <span>Cargando imagen...</span>
                </div>

                <div class="sanitary-image-modal__error d-none" id="sanitaryImageError">
                    <i class="fas fa-image"></i>
                    <strong>No se pudo cargar la imagen</strong>
                    <span>Verifica que el archivo exista o intenta nuevamente.</span>
                </div>

                <img src="" alt="" class="sanitary-image-modal__image d-none" id="sanitaryImageViewerImage">
            </div>

            <div class="modal-footer sanitary-image-modal__footer">
                <a href="#" class="btn btn-success sanitary-image-modal__download" id="sanitaryImageDownload" download>
                    <i class="fas fa-download mr-1"></i> Descargar
                </a>
                <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">
                    <i class="fas fa-times mr-1"></i> Cerrar
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    .sanitary-image-modal .modal-dialog {
        max-width: min(1120px, calc(100vw - 24px));
    }

    .sanitary-image-modal__content {
        border: 0;
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 24px 80px rgba(9, 29, 12, 0.38);
    }

    .sanitary-image-modal__header {
        align-items: center;
        background: linear-gradient(135deg, var(--agro, #3f7e2a), var(--agro-700, #2f621f));
        border-bottom: 0;
        color: #fff;
        padding: 1rem 1.25rem;
    }

    .sanitary-image-modal__eyebrow {
        display: block;
        font-size: 0.72rem;
        font-weight: 700;
        letter-spacing: 0.08em;
        opacity: 0.78;
        text-transform: uppercase;
    }

    .sanitary-image-modal__header .modal-title {
        font-weight: 700;
        line-height: 1.2;
        margin-top: 0.1rem;
    }

    .sanitary-image-modal__close {
        color: #fff;
        opacity: 0.9;
        text-shadow: none;
    }

    .sanitary-image-modal__close:hover {
        color: #fff;
        opacity: 1;
    }

    .sanitary-image-modal__body {
        align-items: center;
        background:
            linear-gradient(45deg, rgba(63, 126, 42, 0.04) 25%, transparent 25%),
            linear-gradient(-45deg, rgba(63, 126, 42, 0.04) 25%, transparent 25%),
            #f7faf6;
        display: flex;
        justify-content: center;
        min-height: 320px;
        padding: 1.25rem;
    }

    .sanitary-image-modal__image {
        background: #fff;
        border-radius: 14px;
        box-shadow: 0 14px 40px rgba(24, 39, 24, 0.18);
        max-height: min(72vh, 760px);
        max-width: 100%;
        object-fit: contain;
    }

    .sanitary-image-modal__loading,
    .sanitary-image-modal__error {
        align-items: center;
        color: #5b6a58;
        display: flex;
        flex-direction: column;
        gap: 0.45rem;
        justify-content: center;
        min-height: 220px;
        text-align: center;
    }

    .sanitary-image-modal__loading i,
    .sanitary-image-modal__error i {
        color: var(--agro, #3f7e2a);
        font-size: 2rem;
    }

    .sanitary-image-modal__error strong {
        color: #243021;
        font-size: 1.05rem;
    }

    .sanitary-image-modal__footer {
        background: #fff;
        border-top: 1px solid rgba(63, 126, 42, 0.12);
        gap: 0.5rem;
    }

    .sanitary-image-modal__download {
        border-radius: 999px;
        font-weight: 700;
        padding-left: 1rem;
        padding-right: 1rem;
    }

    @media (max-width: 575.98px) {
        .sanitary-image-modal__body {
            min-height: 260px;
            padding: 0.75rem;
        }

        .sanitary-image-modal__image {
            max-height: 64vh;
        }

        .sanitary-image-modal__footer {
            align-items: stretch;
            flex-direction: column;
        }

        .sanitary-image-modal__footer .btn {
            width: 100%;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('sanitaryImageViewerModal');
        const title = document.getElementById('sanitaryImageViewerTitle');
        const image = document.getElementById('sanitaryImageViewerImage');
        const loading = document.getElementById('sanitaryImageLoading');
        const error = document.getElementById('sanitaryImageError');
        const download = document.getElementById('sanitaryImageDownload');

        if (!modal || !title || !image || !loading || !error || !download) {
            return;
        }

        function setState(state) {
            loading.classList.toggle('d-none', state !== 'loading');
            image.classList.toggle('d-none', state !== 'loaded');
            error.classList.toggle('d-none', state !== 'error');
            download.classList.toggle('disabled', state !== 'loaded');
            download.setAttribute('aria-disabled', state !== 'loaded' ? 'true' : 'false');
        }

        document.querySelectorAll('[data-sanitary-image-viewer]').forEach(function(trigger) {
            trigger.addEventListener('click', function(event) {
                event.preventDefault();

                const imageUrl = this.dataset.imageUrl || this.getAttribute('href');
                const imageTitle = this.dataset.imageTitle || 'Imagen sanitaria';
                const downloadName = this.dataset.downloadName || 'imagen-sanitaria';

                title.textContent = imageTitle;
                image.alt = imageTitle;
                image.removeAttribute('src');
                download.href = imageUrl || '#';
                download.setAttribute('download', downloadName);
                setState('loading');

                if (!imageUrl) {
                    setState('error');
                    $('#sanitaryImageViewerModal').modal('show');
                    return;
                }

                image.onload = function() {
                    setState('loaded');
                };

                image.onerror = function() {
                    image.removeAttribute('src');
                    setState('error');
                };

                image.src = imageUrl;
                $('#sanitaryImageViewerModal').modal('show');
            });
        });

        $('#sanitaryImageViewerModal').on('hidden.bs.modal', function() {
            image.removeAttribute('src');
            image.onload = null;
            image.onerror = null;
            setState('loading');
        });
    });
</script>
