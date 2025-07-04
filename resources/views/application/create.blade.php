<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Atölye Başvuru Formu</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" type="image/x-icon" href="https://kirklareli.bel.tr/dist/media/favicon/favicon.ico">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root { --main-color: #25A2DB; }
        body { background-color: #f8f9fa; }
        .form-card { background:#fff; border-radius:.75rem; box-shadow:0 0 12px rgba(0,0,0,0.08); padding:1.5rem; }
        .logo-wrapper { text-align:center; padding:1rem 0; }
        .logo-wrapper img { max-width:120px; }
        .line-curve { height:25px; background:linear-gradient(to right,transparent 20%,var(--main-color) 30%,var(--main-color) 70%,transparent 80%); }
        .btn-main { background:var(--main-color); color:#fff; }
        .btn-main:hover { background:#1a7bad; }
        .kvkk { font-size:.85rem; background:#f2f2f2; padding:1rem; border-radius:.5rem; margin-bottom:1rem; }
        canvas { width:100%; height:120px; border:1px solid #ccc; display:block; margin:auto; }
        footer { font-size:.85rem; color:#555; text-align:center; margin-top:1rem; }
        #signature-pad {
    width: 250px !important;
    height: 100px !important;
    border: 1px solid #ccc;
    display: block;
    margin: auto;
}
    </style>
</head>
<body>
    <div class="container-fluid py-4">
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-lg-6">
                <div class="form-card">
                    <div class="logo-wrapper">
                        <img src="{{ asset('images/logo.png') }}" alt="Logo">
                        <h2>Kırklareli Belediye Başkanlığı<br>Sanat Atölyeleri Başvuru Formu</h2>
                    </div>

                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger"><ul class="mb-0">
                            @foreach($errors->all() as $e)
                                <li>{{ $e }}</li>
                            @endforeach
                        </ul></div>
                    @endif

                    <form method="POST" action="{{ url('/basvuru') }}">
                        @csrf

                        <div class="row g-2">
                            <div class="col-sm-6">
                                <input type="text" name="first_name" class="form-control"
                                       placeholder="Adınız" value="{{ old('first_name') }}" required>
                            </div>
                            <div class="col-sm-6">
                                <input type="text" name="last_name" class="form-control"
                                       placeholder="Soyadınız" value="{{ old('last_name') }}" required>
                            </div>
                        </div>

                        <div class="mt-2">
                            <input type="email" name="email" class="form-control"
                                   placeholder="E-Posta Adresiniz" value="{{ old('email') }}" required>
                        </div>

                        <div class="row mt-2 g-2">
                            <div class="col-sm-6">
                                <input type="text" name="tc_no" class="form-control"
                                       placeholder="TC Kimlik No" value="{{ old('tc_no') }}"
                                       minlength="11" maxlength="11" pattern="\d{11}" required>
                            </div>
                            <div class="col-sm-6">
                                <label for="birth_date" class="form-label d-sm-none">
                                    Doğum Tarihi (GG/AA/YYYY)
                                </label>
                                <input type="date" id="birth_date" name="birth_date"
                                       class="form-control" value="{{ old('birth_date') }}" required>
                            </div>
                        </div>

                        <div class="row mt-2 g-2">
                            <div class="col-sm-6">
                                <input type="text" name="phone" class="form-control"
                                       placeholder="Telefon No" value="{{ old('phone') }}" required>
                            </div>
                            <div class="col-sm-6">
                                <input type="text" name="parent_name" class="form-control"
                                       placeholder="Veli Ad Soyad" value="{{ old('parent_name') }}" required>
                            </div>
                        </div>

                        <div class="mt-2">
                            <input type="text" name="parent_phone" class="form-control"
                                   placeholder="Veli Telefon No" value="{{ old('parent_phone') }}" required>
                        </div>

                        <div class="mt-3">
                            <select name="education_program_id" class="form-select" required>
                                <option value="">-- Eğitim Programı Seçin --</option>
                                @foreach($programs as $program)
                                    @php
                                        $full      = $program->applications_count >= $program->capacity;
                                        $ageLabel  = "{$program->age_range} Yaş";
                                        $quota     = $full ? 'Kontenjan Doldu' : "{$program->capacity} Kişi";
                                        $label     = "{$program->title} — {$ageLabel} — {$quota}";
                                    @endphp
                                    <option value="{{ $program->id }}"
                                            {{ $full ? 'disabled class=text-muted' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
<br>
                        <div class="kvkk">
                            <strong>KVKK Bilgilendirmesi:</strong><br>
                            <a href="https://api.kirklarelibelediyesi.com/files/dokuman/kirklareli-kvkk.pdf"
                               target="_blank" style="color:inherit; text-decoration:none;">
                                Kişisel verilerin işlenmesine ilişkin aydınlatma metnine buradan ulaşabilirsiniz.
                            </a>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Kırklareli Belediyesi Veli Taahhütnamesi</label>
                            <div class="border rounded p-3 bg-light" style="font-size:.92rem;">
                                <strong>Yaz Okulları Veli Başvuru Onayı</strong><br><br>
                                Velisi olduğum çocuğumun yaz/kış faaliyetlerine dair
                                talimatları okudum ve sorumluluğun tarafıma ait olduğunu beyan ederim.
                            </div>
                        </div>

                        <div class="mb-3 text-center">
    <label class="form-label">Veli İmzası (Parmak/fare ile çizin)</label>
    <input type="hidden" name="signature" id="signature">
    <!-- width=250, height=100 olarak ayarlandı -->
    <canvas id="signature-pad" width="250" height="100"></canvas>
    <button type="button" class="btn btn-sm btn-secondary mt-2" onclick="clearSignature()">Temizle</button>
</div>

                        <div class="d-grid mt-3">
                            <button type="submit" class="btn btn-main">
                                Başvuruyu Gönder
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Signature + Datepicker Script -->
    <script>
        // --- Signature Pad ---
        const canvas = document.getElementById('signature-pad');
        const input  = document.getElementById('signature');
        const ctx    = canvas.getContext('2d');
        let drawing  = false;

        function startDraw(e) {
            e.preventDefault();
            drawing = true;
            const rect = canvas.getBoundingClientRect();
            const x = (e.touches ? e.touches[0].clientX : e.clientX) - rect.left;
            const y = (e.touches ? e.touches[0].clientY : e.clientY) - rect.top;
            ctx.beginPath();
            ctx.moveTo(x, y);
        }
        function endDraw(e) {
            e.preventDefault();
            drawing = false;
            updateSignature();
        }
        function draw(e) {
            if (!drawing) return;
            e.preventDefault();
            const rect = canvas.getBoundingClientRect();
            const x = (e.touches ? e.touches[0].clientX : e.clientX) - rect.left;
            const y = (e.touches ? e.touches[0].clientY : e.clientY) - rect.top;
            ctx.lineWidth   = 2;
            ctx.lineCap     = 'round';
            ctx.strokeStyle = '#000';
            ctx.lineTo(x, y);
            ctx.stroke();
            ctx.beginPath();
            ctx.moveTo(x, y);
            updateSignature();
        }
        function updateSignature() {
            input.value = canvas.toDataURL('image/png');
        }
        function clearSignature() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            input.value = '';
        }
        canvas.addEventListener('mousedown',  startDraw);
        canvas.addEventListener('touchstart', startDraw);
        canvas.addEventListener('mouseup',    endDraw);
        canvas.addEventListener('touchend',   endDraw);
        canvas.addEventListener('mouseout',   endDraw);
        canvas.addEventListener('mousemove',  draw);
        canvas.addEventListener('touchmove',  draw);
        document.querySelector('form').addEventListener('submit', updateSignature);

        // --- Datepicker Focus / showPicker ---
        document.addEventListener('DOMContentLoaded', function() {
            const dateInput = document.getElementById('birth_date');
            if (!dateInput) return;
            // focus ile aç
            dateInput.addEventListener('focus', () => {
                if (typeof dateInput.showPicker === 'function') {
                    dateInput.showPicker();
                }
            });
        });
    </script>

    <footer>
        T.C Kırklareli Belediye Başkanlığı<br>Bilgi İşlem Müdürlüğü | Tüm Hakları Saklıdır.
    </footer>
</body>
</html>
