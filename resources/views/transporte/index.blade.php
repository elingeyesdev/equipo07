<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title>Transporte | AgroVida</title>
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/plugins/fontawesome-free/css/all.min.css') }}">
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0;
            min-height: 100vh;
            display: grid;
            place-items: center;
            padding: 24px;
            color: #183018;
            background: #eef5eb;
            font-family: Arial, sans-serif;
        }
        .transport-access {
            width: min(100%, 440px);
            padding: 30px;
            border: 1px solid #d7e4d1;
            border-radius: 8px;
            background: #fff;
            box-shadow: 0 18px 45px rgba(31, 74, 25, .12);
        }
        .transport-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 26px;
        }
        .transport-brand img { width: 48px; height: 48px; object-fit: contain; }
        h1 { margin: 0 0 8px; font-size: 1.65rem; }
        p { margin: 0 0 24px; color: #667363; line-height: 1.5; }
        label { display: block; margin-bottom: 8px; font-weight: 700; }
        input {
            width: 100%;
            min-height: 52px;
            padding: 0 14px;
            border: 1px solid #bdcbbb;
            border-radius: 6px;
            font-size: 1.05rem;
            letter-spacing: 1px;
            text-transform: uppercase;
        }
        input:focus { outline: 3px solid rgba(55, 125, 43, .16); border-color: #377d2b; }
        button, .back-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            min-height: 50px;
            border-radius: 6px;
            font-weight: 700;
            text-decoration: none;
        }
        button {
            width: 100%;
            margin-top: 14px;
            border: 0;
            color: #fff;
            background: #2f7d24;
            cursor: pointer;
        }
        .scan-button {
            color: #2f7d24;
            border: 1px solid #7daf75;
            background: #fff;
        }
        .access-divider {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 18px 0 4px;
            color: #788574;
            font-size: .82rem;
            font-weight: 700;
        }
        .access-divider::before, .access-divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #d7e4d1;
        }
        .scanner-modal {
            position: fixed;
            inset: 0;
            z-index: 2000;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 18px;
            background: rgba(16, 34, 17, .72);
        }
        .scanner-modal.is-open { display: flex; }
        .scanner-dialog {
            width: min(100%, 480px);
            max-height: calc(100vh - 36px);
            overflow-y: auto;
            border-radius: 8px;
            background: #fff;
            box-shadow: 0 20px 60px rgba(0,0,0,.3);
        }
        .scanner-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            padding: 16px 18px;
            border-bottom: 1px solid #d7e4d1;
        }
        .scanner-header strong { font-size: 1.05rem; }
        .scanner-close {
            width: 40px;
            min-height: 40px;
            margin: 0;
            border: 0;
            color: #51604f;
            background: transparent;
        }
        .scanner-body { padding: 18px; }
        #qr-reader {
            width: 100%;
            min-height: 280px;
            overflow: hidden;
            border: 1px solid #d7e4d1;
            border-radius: 6px;
            background: #101810;
        }
        #qr-reader video { object-fit: cover; }
        .scanner-status {
            margin-top: 12px;
            padding: 10px 12px;
            border-radius: 6px;
            color: #425441;
            background: #eef5eb;
        }
        .scanner-status--success { color: #155724; background: #d4edda; }
        .scanner-status--error { color: #842029; background: #f8d7da; }
        .back-link { width: 100%; margin-top: 10px; color: #486047; }
        .error {
            margin-top: 8px;
            color: #b42318;
            font-size: .9rem;
        }
        .alert {
            margin-bottom: 18px;
            padding: 12px;
            border-radius: 6px;
            color: #842029;
            background: #f8d7da;
        }
    </style>
</head>
<body>
    <main class="transport-access">
        <div class="transport-brand">
            <img src="{{ asset('img/logo-agrovida.png') }}?v={{ filemtime(public_path('img/logo-agrovida.png')) }}" alt="AgroVida">
            <strong>AgroVida Transporte</strong>
        </div>

        <h1>Acceso al envio</h1>
        <p>Ingresa el codigo que recibiste del vendedor.</p>

        @if (session('error'))
            <div class="alert">{{ session('error') }}</div>
        @endif

        <form method="POST" action="{{ route('transporte.acceder') }}">
            @csrf
            <label for="codigo">Codigo de transporte</label>
            <input id="codigo" name="codigo" value="{{ old('codigo') }}"
                placeholder="XXXX-XXXX-XXXX" autocomplete="one-time-code" required autofocus>
            @error('codigo')
                <div class="error">{{ $message }}</div>
            @enderror
            <div class="access-divider">o</div>
            <button type="button" class="scan-button" id="open-qr-scanner">
                <i class="fas fa-qrcode"></i>
                Escanear codigo QR
            </button>
            <button type="submit">
                <i class="fas fa-arrow-right"></i>
                Continuar
            </button>
        </form>

        <a class="back-link" href="{{ route('landing') }}">
            <i class="fas fa-arrow-left"></i>
            Volver al inicio
        </a>
    </main>

    <div class="scanner-modal" id="qr-scanner-modal" aria-hidden="true">
        <section class="scanner-dialog" role="dialog" aria-modal="true" aria-labelledby="scanner-title">
            <header class="scanner-header">
                <strong id="scanner-title"><i class="fas fa-camera mr-1"></i>Escanear QR de transporte</strong>
                <button type="button" class="scanner-close" id="close-qr-scanner" title="Cerrar">
                    <i class="fas fa-times"></i>
                </button>
            </header>
            <div class="scanner-body">
                <div id="qr-reader"></div>
                <div class="scanner-status" id="qr-scanner-status">
                    Permite el acceso a la camara y apunta al codigo QR.
                </div>
            </div>
        </section>
    </div>

    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
    <script src="{{ asset('js/transport-qr-scanner.js') }}?v={{ filemtime(public_path('js/transport-qr-scanner.js')) }}"></script>
    <script>
        window.addEventListener('pageshow', function(event) {
            if (event.persisted) {
                window.location.reload();
            }
        });
    </script>
</body>
</html>
