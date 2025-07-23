<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Atölye Başvuru Formu</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            --main-color: #25A2DB;
        }

        body {
            background-color: #f8f9fa;
        }

        .logo-wrapper {
            background-color: white;
            padding: 1rem;
            text-align: center;
        }

        .logo-wrapper img {
            width: 120px;
            height: auto;
        }

        .line-curve {
            height: 25px;
            background: linear-gradient(to right, transparent 20%, var(--main-color) 30%, var(--main-color) 70%, transparent 80%);
        }

        .btn-main {
            background-color: var(--main-color);
            color: white;
        }

        .btn-main:hover {
            background-color: #1a7bad;
        }

        .kvkk {
            font-size: 0.85rem;
            background: #f2f2f2;
            padding: 1rem;
            border-radius: 0.5rem;
            margin-bottom: 1rem;
        }

        canvas {
            width: 100%;
            max-width: 300px;
            height: 120px;
            border: 1px solid #ccc;
            display: block;
            margin: auto;
        }

        .form-card {
            border-radius: 0.75rem;
            overflow: hidden;
            background-color: white;
            box-shadow: 0 0 12px rgba(0, 0, 0, 0.08);
            padding: 1.5rem;
        }

        .img-fluid-custom {
            max-height: 100%;
            max-width: 100%;
            object-fit: contain;
        }

        @media (max-width: 767px) {
            .side-image {
                display: none;
            }
        }
    </style>
</head>
<body>

<div class="container-fluid py-4">
    <div class="row g-4 justify-content-center align-items-start">

        <!-- Sol Görsel -->
        {{-- <div class="col-lg-3 d-none d-lg-block text-center side-image">
            <img src="{{ asset('images/sol.jpg') }}" class="img-fluid-custom" alt="Sanat Atölyeleri">
        </div> --}}

        <!-- Form -->
        <div class="col-12 col-md-10 col-lg-6">
            <div class="form-card">
                <div class="logo-wrapper">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo">
                    <h2>Kırklareli Belediye Başkanlığı Sanat Atölyeleri Başvuru Formu</h2>
                </div>

                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="/basvuru">
                    @csrf

                    <div class="row g-2">
                        <div class="col-sm-6">
                            <input type="text" name="first_name" class="form-control" placeholder="Adınız" value="{{ old('first_name') }}" required>
                        </div>
                        <div class="col-sm-6">
                            <input type="text" name="last_name" class="form-control" placeholder="Soyadınız" value="{{ old('last_name') }}" required>
                        </div>
                    </div>

                    <div class="row mt-2 g-2">
                        <div class="col-sm-6">
                            <input type="text" name="tc_no" class="form-control" placeholder="TC Kimlik No" value="{{ old('tc_no') }}" minlength="11" maxlength="11" pattern="\d{11}" required>
                        </div>
                        <div class="col-sm-6">
                            <input type="date" name="birth_date" class="form-control" value="{{ old('birth_date') }}" required>
                        </div>
                    </div>

                    <div class="row mt-2 g-2">
                        <div class="col-sm-6">
                            <input type="text" name="phone" class="form-control" placeholder="Telefon No" value="{{ old('phone') }}" required>
                        </div>
                        <div class="col-sm-6">
                            <input type="text" name="parent_name" class="form-control" placeholder="Veli Ad Soyad" value="{{ old('parent_name') }}" required>
                        </div>
                    </div>

                    <div class="mt-2">
                        <input type="text" name="parent_phone" class="form-control" placeholder="Veli Telefon No" value="{{ old('parent_phone') }}" required>
                    </div>

                    <div class="mt-3">
                            <select name="education_program_id" class="form-select" required>
                            <option value="">-- Eğitim Programı Seçin --</option>
                        @foreach ($programs as $program)
                            <option value="{{ $program->id }}">
                            {{ $program->title }} ({{ $program->age_range }})
                            </option>
                        @endforeach
</select>
                    </div>
<style>
    .kvkk-link {
    color: inherit;
    text-decoration: none;
}

.kvkk-link:hover {
    text-decoration: underline;
}

</style>
<div class="kvkk text-muted">
    <strong>KVKK Bilgilendirmesi:</strong><br>
    <a href="https://api.kirklarelibelediyesi.com/files/dokuman/kirklareli-kvkk.pdf" target="_blank" class="kvkk-link">
        Kişisel verilerin işlenmesine ilişkin aydınlatma metnine buradan ulaşabilirsiniz.
    </a>
</div>



                    <!-- Taahhüt Metni -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">Kırklareli Belediyesi Veli Taahhütnamesi</label>
                        <div class="border rounded p-3 bg-light" style="font-size: 0.92rem;">
                            <strong>Yaz Okulları Veli Başvuru Onayı</strong><br><br>
                            Velisi (Vasisi) olduğum için;<br>
                            Velayetim altında bulunduğunu ve reşit olana kadar adına her türlü işlem yapma haklarının tarafıma ait olduğunu,<br>
                            Katılacağı Kırklareli Belediyesi yaz/kış okullarına ait yönerge ve talimatlarının bütün hükümleri hakkında bilgi sahibi olduğumu,<br>
                            Yönerge ve talimatların taraflara yüklediği vecibeleri eksiksiz yerine getireceğini beyan ve taahhüt ettiğimi biliyorum.<br>
                            Velisi (Vasisi) bulunduğum yukarıda açık kimlik bilgileri yazılı çocuğumun Kırklareli Belediyesi Yaz/Kış spor faaliyetlerine katılımı için kendi imkanlarıyla seyahat edeceğini ve her türlü sorumluluğun tarafıma ait olduğunu kabul ederim.
                        </div>
                    </div>

                    <!-- İmza -->
                    <div class="mb-3 text-center">
                        <label class="form-label">Veli İmzası (Parmak/fare ile çizin)</label>
                        <input type="hidden" name="signature" id="signature">
                        <canvas id="signature-pad"></canvas>
                        <button type="button" class="btn btn-sm btn-secondary mt-2" onclick="clearSignature()">Temizle</button>
                    </div>

                    <div class="d-grid mt-3">
                        <button type="submit" class="btn btn-main">Başvuruyu Gönder</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Sağ Görsel -->
        {{-- <div class="col-lg-3 d-none d-lg-block text-center side-image">
            <img src="{{ asset('images/sağ.jpg') }}" class="img-fluid-custom" alt="Sportif Branşlar">
        </div> --}}
    </div>
</div>

<!-- İmza JS -->
<script>
    const canvas = document.getElementById('signature-pad');
    const input = document.getElementById('signature');
    const ctx = canvas.getContext('2d');
    let drawing = false;

    canvas.addEventListener('mousedown', () => drawing = true);
    canvas.addEventListener('mouseup', () => {
        drawing = false;
        input.value = canvas.toDataURL('image/png');
        ctx.beginPath();
    });
    canvas.addEventListener('mouseleave', () => drawing = false);
    canvas.addEventListener('mousemove', draw);

    function draw(e) {
        if (!drawing) return;
        ctx.lineWidth = 2;
        ctx.lineCap = 'round';
        ctx.strokeStyle = '#000';
        ctx.lineTo(e.offsetX, e.offsetY);
        ctx.stroke();
        ctx.beginPath();
        ctx.moveTo(e.offsetX, e.offsetY);
    }

    function clearSignature() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        input.value = '';
    }
</script>
<footer class="text-center py-2" style="font-size: 0.85rem; color: #555;">
    T.C Kırklareli Belediye Başkanlığı <br> Bilgi İşlem Müdürlüğü | Tüm Hakları Saklıdır.
</footer>
</body>
</html>
