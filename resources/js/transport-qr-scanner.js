import { Html5Qrcode } from 'html5-qrcode';

document.addEventListener('DOMContentLoaded', () => {
    const openButton = document.getElementById('open-qr-scanner');
    const closeButton = document.getElementById('close-qr-scanner');
    const modal = document.getElementById('qr-scanner-modal');
    const input = document.getElementById('codigo');
    const status = document.getElementById('qr-scanner-status');
    const readerId = 'qr-reader';
    let scanner = null;
    let running = false;

    if (!openButton || !modal || !input || !status) {
        return;
    }

    const normalizeCode = (value) => {
        const normalized = String(value || '')
            .toUpperCase()
            .replace(/[^A-Z0-9]/g, '');

        if (normalized.length !== 12) {
            return null;
        }

        return normalized.match(/.{1,4}/g).join('-');
    };

    const stopScanner = async () => {
        if (scanner && running) {
            try {
                await scanner.stop();
            } catch {
                // The camera may already have been released by the browser.
            }
        }

        running = false;
        scanner = null;
        document.getElementById(readerId).replaceChildren();
    };

    const closeModal = async () => {
        await stopScanner();
        modal.classList.remove('is-open');
        modal.setAttribute('aria-hidden', 'true');
    };

    const startScanner = async () => {
        modal.classList.add('is-open');
        modal.setAttribute('aria-hidden', 'false');
        status.className = 'scanner-status';
        status.textContent = 'Permite el acceso a la camara y apunta al codigo QR.';

        scanner = new Html5Qrcode(readerId);

        try {
            await scanner.start(
                { facingMode: 'environment' },
                {
                    fps: 10,
                    qrbox: { width: 230, height: 230 },
                    aspectRatio: 1,
                },
                async (decodedText) => {
                    const code = normalizeCode(decodedText);

                    if (!code) {
                        status.className = 'scanner-status scanner-status--error';
                        status.textContent = 'El QR no corresponde a un codigo de transporte AgroVida.';
                        return;
                    }

                    input.value = code;
                    input.dispatchEvent(new Event('input', { bubbles: true }));
                    status.className = 'scanner-status scanner-status--success';
                    status.textContent = 'Codigo detectado correctamente.';

                    setTimeout(async () => {
                        await closeModal();
                        input.focus();
                    }, 650);
                },
                () => {}
            );
            running = true;
        } catch {
            status.className = 'scanner-status scanner-status--error';
            status.textContent = window.isSecureContext
                ? 'No se pudo abrir la camara. Revisa los permisos del navegador.'
                : 'La camara requiere una conexion HTTPS, como la URL de ngrok.';
        }
    };

    openButton.addEventListener('click', startScanner);
    closeButton?.addEventListener('click', closeModal);
    modal.addEventListener('click', (event) => {
        if (event.target === modal) {
            closeModal();
        }
    });
    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && modal.classList.contains('is-open')) {
            closeModal();
        }
    });
});
