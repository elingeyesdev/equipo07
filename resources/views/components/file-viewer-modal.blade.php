<div class="modal fade global-file-viewer" id="globalFileViewerModal" tabindex="-1" role="dialog"
    aria-labelledby="globalFileViewerTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <small class="d-block text-uppercase">Vista previa</small>
                    <h5 class="modal-title" id="globalFileViewerTitle">Archivo</h5>
                </div>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="global-file-viewer__loading" id="globalFileViewerLoading">
                    <i class="fas fa-spinner fa-spin"></i>
                    <span>Cargando archivo...</span>
                </div>
                <img class="global-file-viewer__image d-none" id="globalFileViewerImage" src="" alt="">
                <iframe class="global-file-viewer__pdf d-none" id="globalFileViewerPdf" src=""
                    title="Vista previa de PDF"></iframe>
                <div class="global-file-viewer__fallback d-none" id="globalFileViewerFallback">
                    <i class="fas fa-file-alt"></i>
                    <strong>Vista previa no disponible</strong>
                    <span>Este formato puede abrirse o descargarse desde el boton inferior.</span>
                </div>
            </div>
            <div class="modal-footer">
                <a href="#" class="btn btn-success" id="globalFileViewerOpen" target="_blank" rel="noopener">
                    <i class="fas fa-external-link-alt mr-1"></i> Abrir archivo
                </a>
                <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<style>
    .global-file-viewer .modal-content {
        border: 0;
        border-radius: 18px;
        box-shadow: 0 24px 80px rgba(9, 29, 12, 0.38);
        overflow: hidden;
    }

    .global-file-viewer .modal-header {
        align-items: center;
        background: linear-gradient(135deg, var(--agro, #3f7e2a), var(--agro-700, #2f621f));
        border: 0;
        color: #fff;
    }

    .global-file-viewer .modal-header small {
        font-size: 0.7rem;
        font-weight: 700;
        letter-spacing: 0.08em;
        opacity: 0.78;
    }

    .global-file-viewer .modal-body {
        align-items: center;
        background: #f7faf6;
        display: flex;
        justify-content: center;
        min-height: 360px;
        padding: 1rem;
    }

    .global-file-viewer__image {
        border-radius: 12px;
        max-height: 72vh;
        max-width: 100%;
        object-fit: contain;
    }

    .global-file-viewer__pdf {
        background: #fff;
        border: 0;
        height: 72vh;
        width: 100%;
    }

    .global-file-viewer__loading,
    .global-file-viewer__fallback {
        align-items: center;
        color: #5b6a58;
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
        justify-content: center;
        text-align: center;
    }

    .global-file-viewer__loading i,
    .global-file-viewer__fallback i {
        color: var(--agro, #3f7e2a);
        font-size: 2rem;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp', 'svg'];
        var modal = document.getElementById('globalFileViewerModal');
        var title = document.getElementById('globalFileViewerTitle');
        var image = document.getElementById('globalFileViewerImage');
        var pdf = document.getElementById('globalFileViewerPdf');
        var loading = document.getElementById('globalFileViewerLoading');
        var fallback = document.getElementById('globalFileViewerFallback');
        var openLink = document.getElementById('globalFileViewerOpen');

        if (!modal || !window.jQuery) {
            return;
        }

        function showOnly(element) {
            [image, pdf, loading, fallback].forEach(function(item) {
                item.classList.toggle('d-none', item !== element);
            });
        }

        function fileType(trigger, url) {
            if (trigger.dataset.fileType) {
                return trigger.dataset.fileType;
            }

            var cleanUrl = (url || '').split('?')[0].split('#')[0];
            var extension = cleanUrl.includes('.') ? cleanUrl.split('.').pop().toLowerCase() : '';

            if (imageExtensions.includes(extension)) {
                return 'image';
            }

            return extension === 'pdf' ? 'pdf' : 'file';
        }

        document.addEventListener('click', function(event) {
            var trigger = event.target.closest('[data-file-viewer]');

            if (!trigger) {
                return;
            }

            event.preventDefault();

            var url = trigger.dataset.fileUrl || trigger.getAttribute('href');
            var name = trigger.dataset.fileTitle || trigger.dataset.fileName || trigger.dataset.imageTitle || 'Archivo';
            var type = fileType(trigger, url);

            title.textContent = name;
            openLink.href = url || '#';
            image.removeAttribute('src');
            pdf.removeAttribute('src');
            showOnly(loading);

            if (!url) {
                showOnly(fallback);
            } else if (type === 'image') {
                image.alt = name;
                image.onload = function() {
                    showOnly(image);
                };
                image.onerror = function() {
                    showOnly(fallback);
                };
                image.src = url;
            } else if (type === 'pdf') {
                pdf.title = name;
                pdf.src = url;
                showOnly(pdf);
            } else {
                showOnly(fallback);
            }

            window.jQuery(modal).modal('show');
        });

        window.jQuery(modal).on('hidden.bs.modal', function() {
            image.removeAttribute('src');
            pdf.removeAttribute('src');
            image.onload = null;
            image.onerror = null;
            openLink.href = '#';
            showOnly(loading);
        });
    });
</script>
