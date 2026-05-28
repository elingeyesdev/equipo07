@extends('layouts.public')

@section('title', 'Mercado Agrícola')
@section('standalone_public', true)

@section('content')
    <style>
        html {
            scroll-behavior: smooth;
            scroll-padding-top: 5rem;
        }

        .agv-landing {
            --agv-agro: #3f7e2a;
            --agv-agro-700: #2f621f;
            --agv-agro-900: #1a3a10;
            --agv-agro-50: #f1f7ec;
            --agv-agro-100: #e2efd9;
            --agv-earth: #b85c28;
            --agv-bg: #fafaf6;
            --agv-surface: #ffffff;
            --agv-ink: #162014;
            --agv-ink-2: #3a4636;
            --agv-muted: #6c7866;
            --agv-line: #e6ebde;
            --agv-line-strong: #d3dcc8;
            --agv-radius: 12px;
            --agv-radius-lg: 20px;
            --agv-shadow: 0 6px 18px rgba(22, 32, 20, .06), 0 1px 2px rgba(22, 32, 20, .04);
            background: var(--agv-bg);
            color: var(--agv-ink);
            font-family: Inter, "Segoe UI", Roboto, Arial, sans-serif;
            font-size: 16px;
            line-height: 1.55;
        }

        .agv-landing *,
        .agv-landing *::before,
        .agv-landing *::after {
            box-sizing: border-box;
        }

        .agv-landing a {
            color: inherit;
            text-decoration: none;
        }

        .agv-landing img {
            display: block;
            max-width: 100%;
        }

        .agv-container {
            width: 100%;
            max-width: 1180px;
            margin: 0 auto;
            padding: 0 24px;
        }

        .agv-eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 12px;
            background: var(--agv-agro-50);
            color: var(--agv-agro-700);
            border: 1px solid var(--agv-agro-100);
            border-radius: 999px;
            font-size: 12px;
            font-weight: 800;
            letter-spacing: .08em;
            text-transform: uppercase;
        }

        .agv-eyebrow::before {
            content: "";
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: var(--agv-agro);
        }

        .agv-title,
        .agv-section-title,
        .agv-landing h3 {
            margin: 0;
            color: var(--agv-ink);
            letter-spacing: 0;
        }

        .agv-title {
            max-width: 640px;
            margin-top: 18px;
            font-family: Georgia, "Times New Roman", serif;
            font-size: clamp(34px, 4.6vw, 56px);
            font-weight: 700;
            line-height: 1.04;
        }

        .agv-title em {
            position: relative;
            color: var(--agv-agro-700);
            font-style: normal;
            white-space: nowrap;
        }

        .agv-title em::after {
            content: "";
            position: absolute;
            left: 0;
            right: 0;
            bottom: 4px;
            z-index: -1;
            height: 8px;
            border-radius: 4px;
            background: var(--agv-agro-100);
        }

        .agv-section-title {
            font-family: Georgia, "Times New Roman", serif;
            font-size: clamp(28px, 3.4vw, 40px);
            font-weight: 700;
            line-height: 1.1;
        }

        .agv-copy {
            margin: 0;
            color: var(--agv-ink-2);
        }

        .agv-muted {
            color: var(--agv-muted);
        }

        .agv-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            min-height: 42px;
            padding: 12px 20px;
            border: 1px solid transparent;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 700;
            white-space: nowrap;
            transition: transform .12s ease, background-color .15s ease, border-color .15s ease, box-shadow .15s ease;
        }

        .agv-btn:hover {
            text-decoration: none;
            transform: translateY(-1px);
        }

        .agv-btn-primary {
            background: var(--agv-agro);
            color: #fff;
            box-shadow: 0 6px 14px rgba(63, 126, 42, .18);
        }

        .agv-btn-primary:hover {
            background: var(--agv-agro-700);
            color: #fff;
        }

        .agv-btn-ghost {
            background: transparent;
            color: var(--agv-ink);
            border-color: var(--agv-line-strong);
        }

        .agv-btn-ghost:hover {
            background: #fff;
            color: var(--agv-ink);
            border-color: var(--agv-ink-2);
        }

        .agv-topbar {
            position: sticky;
            top: 0;
            z-index: 50;
            background: rgba(250, 250, 246, .9);
            border-bottom: 1px solid var(--agv-line);
            backdrop-filter: saturate(140%) blur(8px);
            -webkit-backdrop-filter: saturate(140%) blur(8px);
            transition: background-color .25s ease, box-shadow .25s ease;
        }

        .agv-topbar.is-stuck {
            background: rgba(250, 250, 246, .97);
            box-shadow: 0 14px 30px rgba(22, 32, 20, .1);
        }

        .agv-topbar__inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 24px;
            min-height: 68px;
            transition: min-height .25s ease;
        }

        .agv-topbar.is-stuck .agv-topbar__inner {
            min-height: 58px;
        }

        .agv-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            color: var(--agv-ink);
            font-size: 17px;
            font-weight: 800;
        }

        .agv-brand__mark {
            width: 34px;
            height: 34px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 9px;
            background: linear-gradient(160deg, var(--agv-agro), var(--agv-agro-700));
            color: #fff;
            box-shadow: inset 0 -2px 0 rgba(0, 0, 0, .18), 0 4px 10px rgba(63, 126, 42, .18);
        }

        .agv-brand__name b {
            color: var(--agv-agro-700);
        }

        .agv-brand__name small {
            display: block;
            margin-top: 2px;
            color: var(--agv-muted);
            font-size: 11px;
            font-weight: 700;
            letter-spacing: .04em;
            line-height: 1;
            text-transform: uppercase;
        }

        .agv-nav {
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .agv-nav a {
            padding: 8px 14px;
            border-radius: 8px;
            color: var(--agv-ink-2);
            font-size: 14.5px;
            font-weight: 700;
        }

        .agv-nav a:hover {
            background: rgba(63, 126, 42, .06);
            color: var(--agv-ink);
        }

        .agv-auth {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .agv-hero {
            position: relative;
            overflow: hidden;
            padding: 64px 0 88px;
        }

        .agv-hero::before {
            content: "";
            position: absolute;
            top: -200px;
            right: -180px;
            width: 520px;
            height: 520px;
            background: radial-gradient(closest-side, rgba(63, 126, 42, .1), rgba(63, 126, 42, 0));
            pointer-events: none;
        }

        .agv-hero__grid {
            display: grid;
            grid-template-columns: 1.05fr .95fr;
            gap: 64px;
            align-items: center;
        }

        .agv-hero__sub {
            max-width: 540px;
            margin-top: 20px;
            color: var(--agv-ink-2);
            font-size: 17px;
            line-height: 1.6;
        }

        .agv-hero__actions {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-top: 32px;
        }

        .agv-hero__meta {
            display: flex;
            flex-wrap: wrap;
            gap: 28px;
            margin-top: 36px;
            padding-top: 28px;
            border-top: 1px solid var(--agv-line);
        }

        .agv-hero__meta strong {
            display: block;
            color: var(--agv-ink);
            font-family: Georgia, "Times New Roman", serif;
            font-size: 24px;
            font-weight: 700;
            line-height: 1;
        }

        .agv-hero__meta span {
            display: block;
            margin-top: 6px;
            color: var(--agv-muted);
            font-size: 13px;
        }

        .agv-visual {
            position: relative;
            min-height: 580px;
        }

        .agv-visual__photo {
            position: absolute;
            inset: 0;
            overflow: hidden;
            border-radius: var(--agv-radius-lg);
            background: #dde2d2;
            box-shadow: var(--agv-shadow);
        }

        .agv-visual__photo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .agv-chip {
            position: absolute;
            min-width: 200px;
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 14px;
            border: 1px solid var(--agv-line);
            border-radius: 14px;
            background: rgba(255, 255, 255, .96);
            box-shadow: var(--agv-shadow);
        }

        .agv-chip--top {
            top: 24px;
            left: 24px;
        }

        .agv-chip--right {
            right: 24px;
            bottom: 30%;
        }

        .agv-chip--bottom {
            left: 24px;
            bottom: 24px;
        }

        .agv-chip__icon {
            width: 36px;
            height: 36px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex: 0 0 36px;
            border-radius: 10px;
            background: var(--agv-agro-50);
            color: var(--agv-agro-700);
            font-size: 16px;
            line-height: 1;
            margin-top: 0;
        }

        .agv-chip__icon i {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 1.1em;
            height: 1.1em;
            line-height: 1;
        }

        .agv-chip b,
        .agv-chip > div span {
            display: block;
        }

        .agv-chip b {
            color: var(--agv-ink);
            font-size: 14px;
        }

        .agv-chip > div span {
            margin-top: 1px;
            color: var(--agv-muted);
            font-size: 12px;
        }

        .agv-section {
            padding: 88px 0;
        }

        .agv-section--white {
            background: #fff;
            border-top: 1px solid var(--agv-line);
            border-bottom: 1px solid var(--agv-line);
        }

        .agv-section--soft {
            background: linear-gradient(180deg, #f4f6ee 0%, #fafaf6 100%);
            border-top: 1px solid var(--agv-line);
            border-bottom: 1px solid var(--agv-line);
        }

        .agv-section__head {
            max-width: 680px;
            margin: 0 auto 48px;
            text-align: center;
        }

        .agv-section__head p {
            margin-top: 14px;
            font-size: 16px;
        }

        .agv-section__head--split {
            max-width: none;
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 48px;
            margin-bottom: 40px;
            text-align: left;
        }

        .agv-section__head--split > div {
            max-width: 560px;
        }

        .agv-section__head--split p {
            max-width: 380px;
        }

        .agv-grid {
            display: grid;
            gap: 20px;
        }

        .agv-grid--4 {
            grid-template-columns: repeat(4, minmax(0, 1fr));
        }

        .agv-grid--3 {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }

        .agv-card {
            overflow: hidden;
            display: flex;
            flex-direction: column;
            border: 1px solid var(--agv-line);
            border-radius: var(--agv-radius);
            background: var(--agv-surface);
            transition: transform .15s ease, box-shadow .15s ease, border-color .15s ease;
        }

        .agv-card:hover {
            border-color: var(--agv-line-strong);
            box-shadow: var(--agv-shadow);
            transform: translateY(-2px);
        }

        .agv-category:hover {
            border-color: var(--agv-line);
            box-shadow: none;
            transform: none;
        }

        .agv-card__image {
            position: relative;
            min-height: 170px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 16px;
            background: repeating-linear-gradient(135deg, #d8dfcc 0 14px, #cdd5be 14px 28px);
            color: #5b6552;
            font-size: 12px;
            text-align: center;
        }

        .agv-card__image--green {
            background: repeating-linear-gradient(135deg, #cde1bf 0 14px, #bbd6ac 14px 28px);
        }

        .agv-card__image--earth {
            background: repeating-linear-gradient(135deg, #e6d3bf 0 14px, #d8c2a9 14px 28px);
        }

        .agv-card__visual {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 10px;
            color: var(--agv-agro-700);
            font-weight: 800;
        }

        .agv-card__icon {
            width: 62px;
            height: 62px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 1px solid rgba(63, 126, 42, .16);
            border-radius: 18px;
            background: rgba(255, 255, 255, .72);
            box-shadow: 0 12px 24px rgba(31, 42, 27, .08);
            font-size: 26px;
        }

        .agv-card__body {
            padding: 18px 18px 20px;
        }

        .agv-card__count {
            color: var(--agv-agro-700);
            font-size: 12px;
            font-weight: 800;
            letter-spacing: .04em;
            text-transform: uppercase;
        }

        .agv-card__title {
            margin: 6px 0;
            color: var(--agv-ink);
            font-size: 18px;
            font-weight: 800;
        }

        .agv-card__desc {
            color: var(--agv-muted);
            font-size: 14px;
            line-height: 1.5;
        }

        .agv-card__info {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            margin-top: 14px;
            color: var(--agv-muted);
            font-size: 13px;
            font-weight: 700;
        }

        .agv-benefit {
            border-top: 2px solid var(--agv-agro);
            padding-top: 14px;
        }

        .agv-benefit__num {
            color: var(--agv-agro-700);
            font-size: 14px;
            font-weight: 900;
            letter-spacing: .04em;
        }

        .agv-benefit h3,
        .agv-step h3 {
            margin: 14px 0 8px;
            font-size: 20px;
            font-weight: 800;
        }

        .agv-products-head {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 24px;
            margin-bottom: 32px;
        }

        .agv-products-head > div:first-child {
            max-width: 560px;
        }

        .agv-product {
            background: #fff;
        }

        .agv-product__image {
            min-height: 185px;
        }

        .agv-product__image img {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .agv-product__image::after {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, rgba(22, 32, 20, .06), rgba(22, 32, 20, .18));
            pointer-events: none;
        }

        .agv-product__placeholder {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 64px;
            height: 64px;
            border: 1px solid rgba(63, 126, 42, .16);
            border-radius: 18px;
            background: rgba(255, 255, 255, .72);
            color: var(--agv-agro-700);
            font-size: 26px;
            box-shadow: 0 12px 24px rgba(31, 42, 27, .08);
        }

        .agv-tag {
            position: absolute;
            top: 10px;
            left: 10px;
            z-index: 2;
            padding: 4px 10px;
            border: 1px solid var(--agv-line);
            border-radius: 999px;
            background: rgba(255, 255, 255, .96);
            color: var(--agv-ink-2);
            font-size: 11px;
            font-weight: 800;
        }

        .agv-tag--earth {
            color: var(--agv-earth);
        }

        .agv-product__body {
            padding: 14px 16px 16px;
        }

        .agv-product__loc {
            color: var(--agv-muted);
            font-size: 12px;
        }

        .agv-product__title {
            margin-top: 6px;
            color: var(--agv-ink);
            font-size: 15.5px;
            font-weight: 800;
            line-height: 1.35;
        }

        .agv-product__bottom {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            margin-top: 12px;
            padding-top: 12px;
            border-top: 1px solid var(--agv-line);
        }

        .agv-product__price {
            color: var(--agv-agro-700);
            font-size: 15.5px;
            font-weight: 900;
        }

        .agv-product__seller {
            display: flex;
            align-items: center;
            gap: 6px;
            color: var(--agv-muted);
            font-size: 12px;
        }

        .agv-avatar {
            width: 22px;
            height: 22px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: var(--agv-agro-100);
            color: var(--agv-agro-700);
            font-size: 10px;
            font-weight: 900;
        }

        .agv-step {
            padding: 24px 22px;
            border: 1px solid var(--agv-line);
            border-radius: var(--agv-radius);
            background: #fff;
        }

        .agv-step__num {
            width: 32px;
            height: 32px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            background: var(--agv-agro);
            color: #fff;
            font-family: Georgia, "Times New Roman", serif;
            font-weight: 700;
        }

        .agv-cta {
            padding: 64px 0 96px;
        }

        .agv-cta__box {
            position: relative;
            overflow: hidden;
            display: grid;
            grid-template-columns: 1.4fr 1fr;
            gap: 48px;
            align-items: center;
            padding: 56px;
            border-radius: var(--agv-radius-lg);
            background: linear-gradient(135deg, var(--agv-agro-900) 0%, var(--agv-agro-700) 60%, var(--agv-agro) 100%);
            color: #fff;
        }

        .agv-cta__box h2 {
            color: #fff;
        }

        .agv-cta__box p {
            max-width: 520px;
            margin-top: 14px;
            color: rgba(255, 255, 255, .86);
        }

        .agv-cta__actions {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-top: 28px;
        }

        .agv-cta__box .agv-btn-primary {
            background: #fff;
            color: var(--agv-agro-900);
        }

        .agv-cta__box .agv-btn-ghost {
            color: #fff;
            border-color: rgba(255, 255, 255, .42);
        }

        .agv-cta__list {
            display: flex;
            flex-direction: column;
            gap: 14px;
        }

        .agv-cta__list span {
            display: flex;
            gap: 12px;
            color: rgba(255, 255, 255, .92);
            font-size: 14.5px;
        }

        .agv-footer {
            padding: 56px 0 32px;
            border-top: 1px solid var(--agv-line);
            background: #fff;
        }

        .agv-footer__grid {
            display: grid;
            grid-template-columns: 1.4fr 1fr 1fr 1fr;
            gap: 40px;
            margin-bottom: 40px;
        }

        .agv-footer h4 {
            margin-bottom: 14px;
            color: var(--agv-ink);
            font-size: 12px;
            font-weight: 900;
            letter-spacing: .08em;
            text-transform: uppercase;
        }

        .agv-footer ul {
            display: flex;
            flex-direction: column;
            gap: 8px;
            padding: 0;
            margin: 0;
            list-style: none;
        }

        .agv-footer a,
        .agv-footer p {
            color: var(--agv-muted);
            font-size: 14px;
        }

        .agv-footer a:hover {
            color: var(--agv-agro-700);
        }

        .agv-footer__bottom {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
            padding-top: 20px;
            border-top: 1px solid var(--agv-line);
            color: var(--agv-muted);
            font-size: 13px;
        }

        @media (max-width: 980px) {
            .agv-hero {
                padding: 48px 0 64px;
            }

            .agv-hero__grid,
            .agv-cta__box {
                grid-template-columns: 1fr;
            }

            .agv-visual {
                min-height: 500px;
            }

            .agv-chip--top,
            .agv-chip--right,
            .agv-chip--bottom {
                left: 8px;
                right: auto;
            }

            .agv-chip--right {
                bottom: 30%;
            }

            .agv-grid--4 {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .agv-grid--3 {
                grid-template-columns: 1fr;
            }

            .agv-footer__grid {
                grid-template-columns: 1fr 1fr;
            }
        }

        @media (max-width: 720px) {
            .agv-container {
                padding: 0 18px;
            }

            .agv-nav,
            .agv-auth .agv-btn-ghost {
                display: none;
            }

            .agv-section {
                padding: 64px 0;
            }

            .agv-title {
                font-size: 38px;
            }

            .agv-section__head--split,
            .agv-products-head {
                align-items: flex-start;
                flex-direction: column;
            }

            .agv-cta {
                padding: 48px 0 72px;
            }

            .agv-cta__box {
                gap: 32px;
                padding: 32px 24px;
                border-radius: 16px;
            }

            .agv-footer__grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 460px) {
            .agv-grid--4 {
                grid-template-columns: 1fr;
            }

            .agv-visual {
                min-height: 440px;
            }

            .agv-chip {
                min-width: 170px;
                padding: 10px 12px;
            }
        }
    </style>

    <main class="agv-landing">
        <header class="agv-topbar" data-agv-topbar>
            <div class="agv-container agv-topbar__inner">
                <a href="{{ route('landing') }}" class="agv-brand">
                    <span class="agv-brand__mark" aria-hidden="true">
                        <i class="fas fa-seedling"></i>
                    </span>
                    <span class="agv-brand__name">
                        Agro<b>Vida</b>
                        <small>Mercado agrícola</small>
                    </span>
                </a>

                <nav class="agv-nav" aria-label="Principal">
                    <a href="#mercado">Mercado</a>
                    <a href="#categorias">Categorías</a>
                    <a href="#beneficios">Por qué AgroVida</a>
                    <a href="#como-funciona">Cómo funciona</a>
                </nav>

                <div class="agv-auth">
                    <a href="{{ route('login') }}" class="agv-btn agv-btn-ghost">Login</a>
                </div>
            </div>
        </header>

        <section class="agv-hero" id="inicio">
            <div class="agv-container agv-hero__grid">
                <div>
                    <span class="agv-eyebrow">Plataforma agropecuaria - Bolivia</span>
                    <h1 class="agv-title">
                        Compra y vende <em>productos del campo</em> en un solo lugar.
                    </h1>
                    <p class="agv-hero__sub">
                        AgroVida conecta a productores, vendedores y compradores locales. Publica ganado,
                        maquinaria y productos orgánicos, o encuentra ofertas cerca de tu zona.
                    </p>

                    <div class="agv-hero__actions">
                        <a href="{{ route('register') }}" class="agv-btn agv-btn-primary">
                            Regístrate
                        </a>
                    </div>

                    <div class="agv-hero__meta">
                        <div>
                            <strong>3 categorías</strong>
                            <span>Animales, maquinaria y orgánicos</span>
                        </div>
                        <div>
                            <strong>9 departamentos</strong>
                            <span>Vendedores activos en todo el país</span>
                        </div>
                        <div>
                            <strong>Contacto directo</strong>
                            <span>Habla con el productor sin comisiones</span>
                        </div>
                    </div>
                </div>

                <div class="agv-visual" aria-label="Productos, ganado y maquinaria agrícola">
                    <div class="agv-visual__photo">
                        <img src="{{ asset('img/hero-agrovida.png') }}" alt="Collage de ganado, maquinaria y productos agrícolas">
                    </div>

                    <div class="agv-chip agv-chip--top">
                        <span class="agv-chip__icon"><i class="fas fa-tractor"></i></span>
                        <div>
                            <b>Maquinaria</b>
                            <span>Venta y alquiler</span>
                        </div>
                    </div>

                    <div class="agv-chip agv-chip--right">
                        <span class="agv-chip__icon"><i class="fas fa-horse"></i></span>
                        <div>
                            <b>Ganado certificado</b>
                            <span>Registro y trazabilidad</span>
                        </div>
                    </div>

                    <div class="agv-chip agv-chip--bottom">
                        <span class="agv-chip__icon"><i class="fas fa-leaf"></i></span>
                        <div>
                            <b>Productos orgánicos</b>
                            <span>Producción local</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="agv-section" id="categorias">
            <div class="agv-container">
                <div class="agv-section__head">
                    <span class="agv-eyebrow">Categorías</span>
                    <h2 class="agv-section-title mt-3">Todo el mercado agropecuario, organizado.</h2>
                    <p class="agv-copy">Conoce las áreas principales de AgroVida: animales en pie, maquinaria agrícola y productos orgánicos.</p>
                </div>

                <div class="agv-grid agv-grid--3">
                    @foreach ([
                        ['tone' => 'green', 'icon' => 'fa-horse', 'count' => 'Ganado', 'title' => 'Animales y ganado', 'desc' => 'Bovinos, ovinos, porcinos y aves con datos de edad, procedencia y ubicación.'],
                        ['tone' => '', 'icon' => 'fa-tractor', 'count' => 'Maquinaria', 'title' => 'Maquinaria agrícola', 'desc' => 'Tractores, sembradoras, cosechadoras e implementos en venta o alquiler.'],
                        ['tone' => 'green', 'icon' => 'fa-leaf', 'count' => 'Orgánicos', 'title' => 'Productos orgánicos', 'desc' => 'Hortalizas, frutas, granos y derivados con producción local trazable.'],
                    ] as $category)
                        <article class="agv-card agv-category">
                            <div class="agv-card__image {{ $category['tone'] === 'green' ? 'agv-card__image--green' : '' }} {{ $category['tone'] === 'earth' ? 'agv-card__image--earth' : '' }}">
                                <div class="agv-card__visual">
                                    <span class="agv-card__icon" aria-hidden="true"><i class="fas {{ $category['icon'] }}"></i></span>
                                    <span>{{ $category['title'] }}</span>
                                </div>
                            </div>
                            <div class="agv-card__body">
                                <span class="agv-card__count">{{ $category['count'] }}</span>
                                <div class="agv-card__title">{{ $category['title'] }}</div>
                                <p class="agv-card__desc">{{ $category['desc'] }}</p>
                                <span class="agv-card__info"><i class="fas fa-info-circle"></i> Información de la categoría</span>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>

        <section class="agv-section agv-section--white" id="beneficios">
            <div class="agv-container">
                <div class="agv-section__head agv-section__head--split">
                    <div>
                        <span class="agv-eyebrow">Por qué AgroVida</span>
                        <h2 class="agv-section-title mt-3">Una herramienta pensada para el productor, no para la portada.</h2>
                    </div>
                    <p class="agv-copy">Lo que importa es publicar rápido, encontrar lo que buscas y contactar al productor.</p>
                </div>

                <div class="agv-grid agv-grid--3">
                    @foreach ([
                        ['num' => '01', 'title' => 'Publica en menos de 3 minutos', 'desc' => 'Sube fotos, agrega precio y ubicación. Tu anuncio queda visible y editable cuando lo necesites.'],
                        ['num' => '02', 'title' => 'Contacta al comprador directo', 'desc' => 'Sin comisiones por venta. Mensajes, llamadas o WhatsApp gestionados desde el perfil del anuncio.'],
                        ['num' => '03', 'title' => 'Filtra por zona y categoría', 'desc' => 'Encuentra ofertas por departamento, municipio, categoría y palabra clave.'],
                    ] as $benefit)
                        <article class="agv-benefit">
                            <span class="agv-benefit__num">{{ $benefit['num'] }}</span>
                            <h3>{{ $benefit['title'] }}</h3>
                            <p class="agv-copy">{{ $benefit['desc'] }}</p>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>

        <section class="agv-section" id="mercado">
            <div class="agv-container">
                <div class="agv-products-head">
                    <div>
                        <span class="agv-eyebrow">Mercado</span>
                        <h2 class="agv-section-title mt-3">Publicaciones recientes en tu zona.</h2>
                        <p class="agv-copy mt-2">Inicia sesión para guardar favoritos y contactar al vendedor.</p>
                    </div>
                </div>

                @php
                    $fallbackProducts = collect([
                        ['tone' => 'green', 'tag' => 'Ganado', 'loc' => 'Santa Cruz - Warnes', 'title' => 'Lote de 12 novillos Nelore - 380kg promedio', 'price' => 'Bs. 9.800', 'seller' => 'JM', 'image' => null, 'icon' => 'fa-horse'],
                        ['tone' => '', 'tag' => 'Maquinaria', 'loc' => 'Cochabamba - Quillacollo', 'title' => 'Tractor John Deere 5075E - uso ligero, mantenido', 'price' => 'Bs. 145.000', 'seller' => 'LR', 'image' => null, 'icon' => 'fa-tractor'],
                        ['tone' => 'green', 'tag' => 'Orgánico', 'loc' => 'Oruro - Challapata', 'title' => 'Quinua real orgánica - cosecha reciente, lote 500kg', 'price' => 'Bs. 24/kg', 'seller' => 'AC', 'image' => null, 'icon' => 'fa-leaf'],
                    ]);

                    $products = ($landingProducts ?? collect())->isNotEmpty() ? $landingProducts : $fallbackProducts;
                @endphp

                <div class="agv-grid agv-grid--3">
                    @foreach ($products as $product)
                        <article class="agv-card agv-product">
                            <div class="agv-card__image agv-product__image {{ $product['tone'] === 'green' ? 'agv-card__image--green' : '' }} {{ $product['tone'] === 'earth' ? 'agv-card__image--earth' : '' }}">
                                @if (!empty($product['image']))
                                    <img src="{{ asset('storage/' . $product['image']) }}" alt="{{ $product['title'] }}">
                                @else
                                    <span class="agv-product__placeholder" aria-hidden="true"><i class="fas {{ $product['icon'] ?? 'fa-leaf' }}"></i></span>
                                @endif
                                <span class="agv-tag {{ $product['tone'] === 'earth' ? 'agv-tag--earth' : '' }}">{{ $product['tag'] }}</span>
                            </div>
                            <div class="agv-product__body">
                                <div class="agv-product__loc"><i class="fas fa-map-marker-alt mr-1"></i>{{ $product['loc'] }}</div>
                                <div class="agv-product__title">{{ $product['title'] }}</div>
                                <div class="agv-product__bottom">
                                    <div class="agv-product__price">{{ $product['price'] }}</div>
                                    <div class="agv-product__seller">
                                        <span class="agv-avatar">{{ $product['seller'] }}</span>
                                        Vendedor
                                    </div>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>

        <section class="agv-section agv-section--soft" id="como-funciona">
            <div class="agv-container">
                <div class="agv-section__head">
                    <span class="agv-eyebrow">Cómo funciona</span>
                    <h2 class="agv-section-title mt-3">Tres pasos. Sin vueltas.</h2>
                    <p class="agv-copy">Diseñado para que cualquier productor pueda usarlo, incluso desde el celular.</p>
                </div>

                <div class="agv-grid agv-grid--3">
                    @foreach ([
                        ['num' => '1', 'title' => 'Crea tu cuenta', 'desc' => 'Regístrate para acceder al mercado y mantener tus publicaciones ordenadas.'],
                        ['num' => '2', 'title' => 'Publica o explora', 'desc' => 'Sube fotos, ubicación y precio, o filtra por categoría y zona.'],
                        ['num' => '3', 'title' => 'Conecta y cierra el trato', 'desc' => 'Contacta directo por mensaje, llamada o WhatsApp.'],
                    ] as $step)
                        <article class="agv-step">
                            <span class="agv-step__num">{{ $step['num'] }}</span>
                            <h3>{{ $step['title'] }}</h3>
                            <p class="agv-copy">{{ $step['desc'] }}</p>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>

        <section class="agv-cta">
            <div class="agv-container">
                <div class="agv-cta__box">
                    <div>
                        <h2 class="agv-section-title">Empieza a publicar tu producción hoy.</h2>
                        <p>Únete a productores, vendedores y compradores de toda Bolivia que ya están usando AgroVida para mover el campo.</p>
                        <div class="agv-cta__actions">
                            <a href="{{ route('register') }}" class="agv-btn agv-btn-primary">Crear cuenta gratis</a>
                            <a href="{{ route('login') }}" class="agv-btn agv-btn-ghost">Iniciar sesión</a>
                        </div>
                    </div>
                    <div class="agv-cta__list">
                        <span><i class="fas fa-check"></i> Sin costo por registrarte.</span>
                        <span><i class="fas fa-check"></i> Soporte en español.</span>
                        <span><i class="fas fa-check"></i> Publicaciones moderadas.</span>
                    </div>
                </div>
            </div>
        </section>

        <footer class="agv-footer">
            <div class="agv-container">
                <div class="agv-footer__grid">
                    <div>
                        <a href="{{ route('landing') }}" class="agv-brand">
                            <span class="agv-brand__mark" aria-hidden="true"><i class="fas fa-seedling"></i></span>
                            <span class="agv-brand__name">Agro<b>Vida</b><small>Mercado agrícola</small></span>
                        </a>
                        <p class="mt-3">Plataforma boliviana para conectar productores, vendedores y compradores del sector agropecuario.</p>
                    </div>
                    <div>
                        <h4>Mercado</h4>
                        <ul>
                            <li><a href="{{ route('ads.index') }}">Ganado</a></li>
                            <li><a href="{{ route('ads.index') }}">Maquinaria</a></li>
                            <li><a href="{{ route('ads.index') }}">Productos orgánicos</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4>Plataforma</h4>
                        <ul>
                            <li><a href="#como-funciona">Cómo funciona</a></li>
                            <li><a href="{{ route('ads.create') }}">Publicar producto</a></li>
                            <li><a href="{{ route('register') }}">Crear cuenta</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4>Contacto</h4>
                        <ul>
                            <li><a href="mailto:soporte@agrovida.bo">soporte@agrovida.bo</a></li>
                            <li><a href="{{ route('login') }}">Iniciar sesión</a></li>
                            <li><a href="{{ route('register') }}">Registrarse</a></li>
                        </ul>
                    </div>
                </div>
                <div class="agv-footer__bottom">
                    <span>© {{ date('Y') }} AgroVida - Hecho en Bolivia.</span>
                    <span>Mercado agrícola para productores y compradores locales.</span>
                </div>
            </div>
        </footer>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var topbar = document.querySelector('[data-agv-topbar]');

            if (!topbar) {
                return;
            }

            var updateTopbar = function () {
                topbar.classList.toggle('is-stuck', window.scrollY > 8);
            };

            updateTopbar();
            window.addEventListener('scroll', updateTopbar, { passive: true });
        });
    </script>
@endsection
