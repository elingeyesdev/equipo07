document.addEventListener('DOMContentLoaded', function () {
    var canvas = document.getElementById('transport-qr-canvas');
    var codeInput = document.getElementById('transport-code');
    var downloadButton = document.getElementById('transport-qr-download');
    var status = document.getElementById('transport-qr-status');

    if (!canvas || !codeInput) {
        return;
    }

    if (!window.QRious || !codeInput.value.trim()) {
        canvas.classList.add('is-error');
        if (status) {
            status.textContent = 'No se pudo generar el QR. Regenera el codigo e intenta nuevamente.';
            status.classList.add('is-error');
        }
        if (downloadButton) {
            downloadButton.setAttribute('disabled', 'disabled');
        }
        return;
    }

    try {
        new window.QRious({
            element: canvas,
            value: codeInput.value,
            size: 220,
            padding: 16,
            level: 'M',
            foreground: '#2c4033',
            background: '#ffffff',
        });
        canvas.classList.add('is-ready');
        if (status) {
            status.textContent = 'QR listo para escanear';
            status.classList.add('is-ready');
        }

        if (downloadButton) {
            downloadButton.addEventListener('click', function () {
                var link = document.createElement('a');
                link.download = 'agrovida-transporte-' + codeInput.value + '.png';
                link.href = canvas.toDataURL('image/png');
                link.click();
            });
        }
    } catch (error) {
        canvas.classList.add('is-error');
        if (status) {
            status.textContent = 'No se pudo generar el QR. Regenera el codigo e intenta nuevamente.';
            status.classList.add('is-error');
        }
        if (downloadButton) {
            downloadButton.setAttribute('disabled', 'disabled');
        }
        console.error('Error al generar el QR de transporte:', error);
    }
});
