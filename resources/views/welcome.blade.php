<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>{{ config('app.name') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
            background-color: #eaf6fd;
            overflow: hidden;
        }

        .container-fullscreen {
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 1rem;
        }

        .logo {
            max-width: 140px;
            height: auto;
            margin-bottom: 0.5rem;
        }

        .banner-img {
            max-width: 100%;
            max-height: 70vh;
            height: auto;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s ease;
        }

        .banner-img:hover {
            transform: scale(1.01);
        }

        a {
            text-decoration: none;
        }

        @media (max-width: 576px) {
            .banner-img {
                max-height: 60vh;
            }
        }
    </style>
</head>
<body>
    <div class="container-fullscreen">
        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="logo">

        <a href="{{ url('/basvuru') }}">
            <picture>
                {{-- Mobilde dikey görsel --}}
                <source media="(max-width: 576px)" srcset="{{ asset('images/dikeygorsel.jpg') }}">
                {{-- Masaüstünde yatay görsel --}}
                <img
                    src="{{ asset('images/yaza-hareketli.jpg') }}"
                    alt="Başvuru"
                    class="banner-img"
                >
            </picture>
        </a>
    </div>
</body>
</html>
