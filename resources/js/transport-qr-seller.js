import QRCode from 'qrcode';

document.addEventListener('DOMContentLoaded', async () => {
    const canvas = document.getElementById('transport-qr-canvas');
    const codeInput = document.getElementById('transport-code');
    const downloadButton = document.getElementById('transport-qr-download');

    if (!canvas || !codeInput) {
        return;
    }

    try {
        await QRCode.toCanvas(canvas, codeInput.value, {
            width: 220,
            margin: 2,
            errorCorrectionLevel: 'M',
            color: {
                dark: '#173f18',
                light: '#ffffff',
            },
        });

        downloadButton?.addEventListener('click', () => {
            const link = document.createElement('a');
            link.download = `agrovida-transporte-${codeInput.value}.png`;
            link.href = canvas.toDataURL('image/png');
            link.click();
        });
    } catch {
        canvas.replaceWith(Object.assign(document.createElement('p'), {
            className: 'text-danger mb-0',
            textContent: 'No se pudo generar el QR.',
        }));
    }
});
