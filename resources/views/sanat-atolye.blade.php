<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Kış Dönemi Sanat Atölyeleri</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/x-icon" href="https://kirklareli.bel.tr/dist/media/favicon/favicon.ico">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,700&family=Sora:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --ink: #12202e;
            --muted: #5a6b7c;
            --teal: #0e6b6e;
            --teal-deep: #0a4f52;
            --snow: #f4f8fa;
        }

        * { box-sizing: border-box; }

        html, body {
            height: 100%;
            margin: 0;
        }

        body {
            font-family: "Sora", sans-serif;
            color: var(--ink);
            background:
                radial-gradient(ellipse 80% 60% at 15% 20%, rgba(14, 107, 110, 0.14), transparent 55%),
                radial-gradient(ellipse 70% 50% at 90% 80%, rgba(90, 120, 150, 0.16), transparent 50%),
                linear-gradient(160deg, #dfeaf0 0%, var(--snow) 45%, #e4eef2 100%);
            overflow-x: hidden;
        }

        .hero {
            min-height: 100%;
            display: grid;
            grid-template-rows: auto 1fr;
            padding: clamp(1.25rem, 3vw, 2.5rem);
        }

        .brand-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            opacity: 0;
            animation: rise 0.7s ease forwards;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 0.85rem;
            text-decoration: none;
            color: inherit;
        }

        .brand img {
            width: clamp(56px, 8vw, 72px);
            height: auto;
        }

        .brand-text {
            font-family: "Fraunces", serif;
            font-size: clamp(1.05rem, 2.2vw, 1.35rem);
            font-weight: 700;
            line-height: 1.2;
            letter-spacing: -0.02em;
        }

        .brand-text span {
            display: block;
            font-family: "Sora", sans-serif;
            font-size: 0.72rem;
            font-weight: 500;
            color: var(--muted);
            letter-spacing: 0.04em;
            text-transform: uppercase;
            margin-top: 0.2rem;
        }

        .back-link {
            font-size: 0.85rem;
            font-weight: 500;
            color: var(--muted);
            text-decoration: none;
            white-space: nowrap;
        }

        .back-link:hover { color: var(--teal); }

        .stage {
            display: grid;
            align-items: center;
            gap: clamp(1.5rem, 4vw, 3rem);
            max-width: 1100px;
            width: 100%;
            margin: 0 auto;
            padding: clamp(1rem, 3vh, 2rem) 0;
        }

        @media (min-width: 900px) {
            .stage { grid-template-columns: 1fr 1.15fr; }
        }

        .copy {
            opacity: 0;
            animation: rise 0.8s ease 0.12s forwards;
        }

        .copy h1 {
            font-family: "Fraunces", serif;
            font-size: clamp(2.1rem, 5.5vw, 3.6rem);
            font-weight: 700;
            line-height: 1.02;
            letter-spacing: -0.03em;
            margin: 0 0 1rem;
        }

        .copy p {
            margin: 0 0 1.75rem;
            max-width: 28rem;
            font-size: clamp(1rem, 2vw, 1.1rem);
            line-height: 1.55;
            color: var(--muted);
        }

        .cta {
            display: inline-flex;
            align-items: center;
            gap: 0.65rem;
            padding: 0.95rem 1.5rem;
            background: var(--teal);
            color: #fff;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.95rem;
            border-radius: 4px;
            transition: background 0.25s ease, transform 0.25s ease;
        }

        .cta:hover {
            background: var(--teal-deep);
            transform: translateY(-2px);
            color: #fff;
        }

        .cta svg {
            width: 18px;
            height: 18px;
            transition: transform 0.25s ease;
        }

        .cta:hover svg { transform: translateX(3px); }

        .visual {
            position: relative;
            opacity: 0;
            animation: rise 0.9s ease 0.22s forwards;
        }

        .visual::before {
            content: "";
            position: absolute;
            inset: 8% -4% -4% 8%;
            background: linear-gradient(135deg, rgba(14, 107, 110, 0.2), rgba(18, 32, 46, 0.08));
            border-radius: 2px;
            z-index: 0;
        }

        .visual picture,
        .visual img {
            position: relative;
            z-index: 1;
            display: block;
            width: 100%;
            height: auto;
            max-height: min(68vh, 560px);
            object-fit: cover;
            border-radius: 2px;
        }

        .visual-fallback {
            position: relative;
            z-index: 1;
            aspect-ratio: 4 / 3;
            max-height: min(68vh, 560px);
            display: grid;
            place-items: end start;
            padding: 1.5rem;
            background:
                linear-gradient(160deg, rgba(14, 107, 110, 0.85), rgba(18, 32, 46, 0.9)),
                repeating-linear-gradient(-18deg, transparent, transparent 12px, rgba(255,255,255,0.03) 12px, rgba(255,255,255,0.03) 24px);
            color: #fff;
            border-radius: 2px;
        }

        .visual-fallback strong {
            font-family: "Fraunces", serif;
            font-size: clamp(1.5rem, 3vw, 2.1rem);
            line-height: 1.15;
        }

        @keyframes rise {
            from { opacity: 0; transform: translateY(18px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @media (max-width: 575px) {
            .visual::before { display: none; }
            .visual img { max-height: 48vh; }
        }
    </style>
</head>
<body>
    <main class="hero">
        <header class="brand-bar">
            <a class="brand" href="{{ url('/') }}">
                <img src="{{ asset('images/logo.png') }}" alt="Kırklareli Belediyesi">
                <div class="brand-text">
                    Kırklareli Belediyesi
                    <span>Kültür Sanat Evi &amp; AKM</span>
                </div>
            </a>
            <a class="back-link" href="{{ config('services.portal.url', '/') }}">← Portal</a>
        </header>

        <section class="stage">
            <div class="copy">
                <h1>Kış Dönemi<br>Sanat Atölyeleri</h1>
                <p>Bale, drama, halk oyunları ve daha fazlası için yaş grubunuza uygun programa hemen başvurun.</p>
                <a class="cta" href="{{ url('/basvuru') }}">
                    Başvuruya Başla
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true">
                        <path d="M5 12h14M13 6l6 6-6 6"/>
                    </svg>
                </a>
            </div>

            <div class="visual">
                <picture>
                    <source media="(max-width: 576px)" srcset="{{ asset('images/dikeygorsel.jpg') }}">
                    <img
                        src="{{ asset('images/kis-okulu.webp') }}"
                        alt="Kış Dönemi Sanat Atölyeleri"
                        onerror="this.closest('.visual').innerHTML='<div class=\'visual-fallback\'><strong>Kış Dönemi<br>Sanat Atölyeleri</strong></div>'"
                    >
                </picture>
            </div>
        </section>
    </main>
</body>
</html>
