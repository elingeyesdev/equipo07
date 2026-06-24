<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sesion expirada | AgroVida</title>
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/plugins/fontawesome-free/css/all.min.css') }}">
    <style>
        :root {
            --leaf: #1f7a3f;
            --leaf-dark: #15552d;
            --field: #d9a441;
            --soil: #7c4a22;
            --sky: #eef8f0;
            --ink: #1e3528;
            --muted: #647569;
            --white: #ffffff;
        }

        * {
            box-sizing: border-box;
        }

        body {
            min-height: 100vh;
            margin: 0;
            display: grid;
            place-items: center;
            overflow-x: hidden;
            color: var(--ink);
            font-family: "Inter", "Segoe UI", Arial, sans-serif;
            background:
                radial-gradient(circle at 18% 18%, rgba(255, 220, 113, .38), transparent 26rem),
                linear-gradient(180deg, #f7fff8 0%, var(--sky) 52%, #eef1dc 100%);
        }

        .session-page {
            width: min(1080px, calc(100% - 32px));
            min-height: min(680px, calc(100vh - 32px));
            display: grid;
            grid-template-columns: minmax(0, 1.02fr) minmax(300px, .98fr);
            align-items: center;
            gap: 36px;
            padding: 38px;
            position: relative;
        }

        .session-copy {
            max-width: 560px;
            z-index: 2;
        }

        .brand {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 28px;
            color: var(--leaf-dark);
            font-weight: 800;
            letter-spacing: .02em;
        }

        .brand img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
        }

        .eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 12px;
            margin: 0 0 18px;
            border-radius: 999px;
            color: var(--leaf-dark);
            background: rgba(31, 122, 63, .1);
            font-size: .84rem;
            font-weight: 800;
        }

        h1 {
            margin: 0;
            max-width: 11ch;
            font-size: clamp(2.6rem, 8vw, 5.8rem);
            line-height: .96;
            letter-spacing: 0;
        }

        .message {
            max-width: 490px;
            margin: 22px 0 0;
            color: var(--muted);
            font-size: clamp(1rem, 2vw, 1.14rem);
            line-height: 1.7;
        }

        .actions {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-top: 30px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 9px;
            min-height: 46px;
            padding: 12px 18px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 800;
            transition: transform .2s ease, box-shadow .2s ease, background .2s ease;
        }

        .btn:hover {
            transform: translateY(-2px);
        }

        .btn-primary {
            color: var(--white);
            background: linear-gradient(135deg, var(--leaf), var(--leaf-dark));
            box-shadow: 0 14px 28px rgba(21, 85, 45, .22);
        }

        .btn-light {
            color: var(--leaf-dark);
            background: rgba(255, 255, 255, .82);
            border: 1px solid rgba(21, 85, 45, .16);
        }

        .farm-scene {
            position: relative;
            min-height: 430px;
            border-radius: 8px;
            overflow: hidden;
            background: linear-gradient(180deg, #dff5ff 0%, #f9f7d2 54%, #d9a441 55%, #9b642f 100%);
            box-shadow: 0 24px 58px rgba(33, 76, 45, .18);
            isolation: isolate;
        }

        .sun {
            position: absolute;
            top: 44px;
            right: 54px;
            width: 82px;
            height: 82px;
            border-radius: 50%;
            background: #ffd45a;
            box-shadow: 0 0 0 18px rgba(255, 212, 90, .22), 0 0 50px rgba(255, 185, 56, .5);
            animation: sunPulse 3.8s ease-in-out infinite;
        }

        .hill {
            position: absolute;
            left: -8%;
            right: -8%;
            bottom: 145px;
            height: 170px;
            border-radius: 50% 50% 0 0;
            background: linear-gradient(135deg, #6ab36d, #2f8750);
        }

        .hill.hill-back {
            bottom: 178px;
            left: 18%;
            background: linear-gradient(135deg, #9dcf77, #4f9f5d);
            opacity: .85;
        }

        .field-lines {
            position: absolute;
            inset: auto -18% 0;
            height: 190px;
            background:
                repeating-linear-gradient(104deg, rgba(255, 255, 255, .25) 0 8px, transparent 8px 42px),
                linear-gradient(180deg, var(--field), var(--soil));
            transform: skewY(-3deg);
            transform-origin: left top;
        }

        .tractor {
            position: absolute;
            left: 44px;
            bottom: 114px;
            width: 156px;
            height: 82px;
            animation: tractorRide 6s ease-in-out infinite;
            z-index: 3;
        }

        .tractor-body {
            position: absolute;
            left: 38px;
            top: 28px;
            width: 90px;
            height: 34px;
            border-radius: 8px 16px 6px 6px;
            background: #207d42;
        }

        .tractor-cabin {
            position: absolute;
            left: 70px;
            top: 2px;
            width: 45px;
            height: 40px;
            border-radius: 8px 8px 3px 3px;
            background: #2d9a55;
        }

        .tractor-cabin::after {
            content: "";
            position: absolute;
            inset: 8px 8px 10px;
            border-radius: 4px;
            background: #c8efff;
        }

        .tractor-front {
            position: absolute;
            left: 16px;
            top: 40px;
            width: 34px;
            height: 22px;
            border-radius: 12px 4px 4px 10px;
            background: #15552d;
        }

        .wheel {
            position: absolute;
            bottom: 0;
            border-radius: 50%;
            background: #26332b;
            border: 7px solid #111a14;
            animation: wheelSpin 1.1s linear infinite;
        }

        .wheel::after {
            content: "";
            position: absolute;
            inset: 9px;
            border-radius: 50%;
            background: #d7e0d4;
        }

        .wheel-large {
            left: 76px;
            width: 54px;
            height: 54px;
        }

        .wheel-small {
            left: 24px;
            bottom: 3px;
            width: 38px;
            height: 38px;
        }

        .sprouts {
            position: absolute;
            left: 0;
            right: 0;
            bottom: 45px;
            height: 70px;
            z-index: 4;
        }

        .sprout {
            position: absolute;
            bottom: 0;
            width: 32px;
            height: 54px;
            transform-origin: center bottom;
            animation: sproutSway 2.6s ease-in-out infinite;
        }

        .sprout::before,
        .sprout::after {
            content: "";
            position: absolute;
            bottom: 12px;
            width: 18px;
            height: 30px;
            border-radius: 18px 18px 2px 18px;
            background: #2f8c4e;
        }

        .sprout::before {
            left: 2px;
            transform: rotate(-35deg);
        }

        .sprout::after {
            right: 2px;
            transform: rotate(35deg) scaleX(-1);
        }

        .stem {
            position: absolute;
            left: 15px;
            bottom: 0;
            width: 4px;
            height: 48px;
            border-radius: 4px;
            background: #206b39;
        }

        .sprout:nth-child(1) { left: 10%; animation-delay: -.1s; }
        .sprout:nth-child(2) { left: 26%; animation-delay: -.7s; transform: scale(.86); }
        .sprout:nth-child(3) { left: 45%; animation-delay: -1.2s; transform: scale(1.08); }
        .sprout:nth-child(4) { left: 63%; animation-delay: -.4s; transform: scale(.92); }
        .sprout:nth-child(5) { left: 82%; animation-delay: -1.6s; }

        .session-code {
            position: absolute;
            left: 24px;
            top: 22px;
            z-index: 5;
            color: rgba(21, 85, 45, .82);
            font-size: .92rem;
            font-weight: 900;
        }

        @keyframes sunPulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.07); }
        }

        @keyframes tractorRide {
            0%, 100% { transform: translateX(0) translateY(0); }
            50% { transform: translateX(64px) translateY(-2px); }
        }

        @keyframes wheelSpin {
            to { transform: rotate(360deg); }
        }

        @keyframes sproutSway {
            0%, 100% { transform: rotate(-2deg); }
            50% { transform: rotate(3deg); }
        }

        @media (max-width: 820px) {
            body {
                place-items: start center;
            }

            .session-page {
                grid-template-columns: 1fr;
                gap: 26px;
                padding: 24px 0 32px;
            }

            .session-copy {
                padding: 0 4px;
            }

            .farm-scene {
                min-height: 330px;
            }

            .tractor {
                transform: scale(.84);
                transform-origin: left bottom;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            *,
            *::before,
            *::after {
                animation-duration: .01ms !important;
                animation-iteration-count: 1 !important;
                scroll-behavior: auto !important;
            }
        }
    </style>
</head>

<body>
    <main class="session-page">
        <section class="session-copy" aria-labelledby="session-title">
            <div class="brand">
                <img src="{{ asset('img/brand/logo-agrovida.jpeg') }}" alt="AgroVida">
                <span>AgroVida Bolivia</span>
            </div>

            <p class="eyebrow"><i class="fas fa-shield-alt"></i> Codigo 419</p>
            <h1 id="session-title">Sesion expirada</h1>
            <p class="message">
                Por seguridad, la pagina quedo abierta demasiado tiempo y el formulario ya no puede enviarse.
                Recarga la pantalla para continuar con una sesion actualizada.
            </p>

            <div class="actions">
                <a class="btn btn-primary" href="{{ url()->previous() ?: route('home') }}">
                    <i class="fas fa-redo-alt"></i>
                    Recargar pagina
                </a>
                <a class="btn btn-light" href="{{ route('home') }}">
                    <i class="fas fa-home"></i>
                    Ir al inicio
                </a>
            </div>
        </section>

        <section class="farm-scene" aria-hidden="true">
            <div class="session-code">419</div>
            <div class="sun"></div>
            <div class="hill hill-back"></div>
            <div class="hill"></div>
            <div class="field-lines"></div>
            <div class="tractor">
                <div class="tractor-cabin"></div>
                <div class="tractor-front"></div>
                <div class="tractor-body"></div>
                <div class="wheel wheel-small"></div>
                <div class="wheel wheel-large"></div>
            </div>
            <div class="sprouts">
                <div class="sprout"><span class="stem"></span></div>
                <div class="sprout"><span class="stem"></span></div>
                <div class="sprout"><span class="stem"></span></div>
                <div class="sprout"><span class="stem"></span></div>
                <div class="sprout"><span class="stem"></span></div>
            </div>
        </section>
    </main>
</body>

</html>
