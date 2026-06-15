import QRCode from 'qrcode';

document.addEventListener('DOMContentLoaded', async () => {
    const canvas = document.getElementById('transport-qr-canvas');
    const codeInput = document.getElementById('transport-code');
    const downloadButton = document.getElementById('transport-qr-download');
    const status = document.getElementById('transport-qr-status');

    if (!canvas || !codeInput) {
        return;
    }

    try {
        if (!codeInput.value.trim()) {
            throw new Error('El codigo de transporte esta vacio.');
        }

        await QRCode.toCanvas(canvas, codeInput.value, {
            width: 220,
            margin: 2,
            errorCorrectionLevel: 'M',
            color: {
                dark: '#2c4033',
                light: '#ffffff',
            },
        });

        canvas.classList.add('is-ready');
        if (status) {
            status.textContent = 'QR listo para escanear';
            status.classList.add('is-ready');
        }

        downloadButton?.addEventListener('click', () => {
            const link = document.createElement('a');
            link.download = `agrovida-transporte-${codeInput.value}.png`;
            link.href = canvas.toDataURL('image/png');
            link.click();
        });
    } catch (error) {
        canvas.classList.add('is-error');
        if (status) {
            status.textContent = 'No se pudo generar el QR. Regenera el codigo e intenta nuevamente.';
            status.classList.add('is-error');
        }
        downloadButton?.setAttribute('disabled', 'disabled');
        console.error('Error al generar el QR de transporte:', error);
    }
});
