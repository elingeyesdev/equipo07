document.addEventListener('DOMContentLoaded', function () {
    var openButton = document.getElementById('open-qr-scanner');
    var closeButton = document.getElementById('close-qr-scanner');
    var modal = document.getElementById('qr-scanner-modal');
    var input = document.getElementById('codigo');
    var status = document.getElementById('qr-scanner-status');
    var readerId = 'qr-reader';
    var scanner = null;
    var running = false;

    if (!openButton || !modal || !input || !status || !window.Html5Qrcode) {
        return;
    }

    function normalizeCode(value) {
        var normalized = String(value || '')
            .toUpperCase()
            .replace(/[^A-Z0-9]/g, '');

        if (normalized.length !== 12) {
            return null;
        }

        return normalized.match(/.{1,4}/g).join('-');
    }

    async function stopScanner() {
        if (scanner && running) {
            try {
                await scanner.stop();
            } catch (error) {
                // La camara puede haber sido liberada por el navegador.
            }
        }

        running = false;
        scanner = null;
        document.getElementById(readerId).replaceChildren();
    }

    async function closeModal() {
        await stopScanner();
        modal.classList.remove('is-open');
        modal.setAttribute('aria-hidden', 'true');
    }

    async function startScanner() {
        modal.classList.add('is-open');
        modal.setAttribute('aria-hidden', 'false');
        status.className = 'scanner-status';
        status.textContent = 'Permite el acceso a la camara y apunta al codigo QR.';

        scanner = new window.Html5Qrcode(readerId);

        try {
            await scanner.start(
                { facingMode: 'environment' },
                {
                    fps: 10,
                    qrbox: { width: 230, height: 230 },
                    aspectRatio: 1,
                },
                async function (decodedText) {
                    var code = normalizeCode(decodedText);

                    if (!code) {
                        status.className = 'scanner-status scanner-status--error';
                        status.textContent = 'El QR no corresponde a un codigo de transporte AgroVida.';
                        return;
                    }

                    input.value = code;
                    input.dispatchEvent(new Event('input', { bubbles: true }));
                    status.className = 'scanner-status scanner-status--success';
                    status.textContent = 'Codigo detectado correctamente.';

                    setTimeout(async function () {
                        await closeModal();
                        input.focus();
                    }, 650);
                },
                function () {}
            );
            running = true;
        } catch (error) {
            status.className = 'scanner-status scanner-status--error';
            status.textContent = window.isSecureContext
                ? 'No se pudo abrir la camara. Revisa los permisos del navegador.'
                : 'La camara requiere una conexion HTTPS, como la URL de ngrok.';
        }
    }

    openButton.addEventListener('click', startScanner);
    if (closeButton) {
        closeButton.addEventListener('click', closeModal);
    }

    modal.addEventListener('click', function (event) {
        if (event.target === modal) {
            closeModal();
        }
    });

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape' && modal.classList.contains('is-open')) {
            closeModal();
        }
    });
});
