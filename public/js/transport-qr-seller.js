document.addEventListener('DOMContentLoaded', function () {
    var canvas = document.getElementById('transport-qr-canvas');
    var codeInput = document.getElementById('transport-code');
    var downloadButton = document.getElementById('transport-qr-download');

    if (!canvas || !codeInput || !window.QRCode) {
        return;
    }

    window.QRCode.toCanvas(canvas, codeInput.value, {
        width: 220,
        margin: 2,
        errorCorrectionLevel: 'M',
        color: {
            dark: '#173f18',
            light: '#ffffff',
        },
    }, function (error) {
        if (error) {
            var message = document.createElement('p');
            message.className = 'text-danger mb-0';
            message.textContent = 'No se pudo generar el QR.';
            canvas.replaceWith(message);
            return;
        }

        if (downloadButton) {
            downloadButton.addEventListener('click', function () {
                var link = document.createElement('a');
                link.download = 'agrovida-transporte-' + codeInput.value + '.png';
                link.href = canvas.toDataURL('image/png');
                link.click();
            });
        }
    });
});
