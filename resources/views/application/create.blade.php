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
        .btn-main { background:var(--main-color); color:#fff; }
        .btn-main:hover { background:#1a7bad; }
        .kvkk { font-size:.85rem; background:#f2f2f2; padding:1rem; border-radius:.5rem; margin-bottom:1rem; }
        #signature-pad { width:250px !important; height:100px !important; border:1px solid #ccc; display:block; margin:auto; }
        .info-box {
            background: #e8f4fc;
            border: 1px solid #b8d7f5;
            border-radius: 6px;
            padding: 0.75rem;
            margin-top: .75rem;
            color: #0a4b75;
            font-size: .9rem;
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
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $e)
                                <li>{{ $e }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ url('/basvuru') }}">
                    @csrf

                    <div class="row g-2">
                        <div class="col-sm-6">
                            <input type="text" name="first_name" class="form-control" placeholder="Adınız" required>
                        </div>
                        <div class="col-sm-6">
                            <input type="text" name="last_name" class="form-control" placeholder="Soyadınız" required>
                        </div>
                    </div>

                    <div class="mt-2">
                        <input type="email" name="email" class="form-control" placeholder="E-Posta Adresiniz" required>
                    </div>

                    <div class="row mt-2 g-2">
                        <div class="col-sm-6">
                            <input type="text" name="tc_no" class="form-control" placeholder="TC Kimlik No" minlength="11" maxlength="11" pattern="\d{11}" required>
                        </div>
                        <div class="col-sm-6">
                            <input type="date" id="birth_date" name="birth_date" class="form-control" required>
                        </div>
                    </div>

                    <div class="row mt-2 g-2">
                        <div class="col-sm-6">
                            <input type="text" name="phone" class="form-control" placeholder="Telefon No" required>
                        </div>
                        <div class="col-sm-6">
                            <input type="text" name="parent_name" class="form-control" placeholder="Veli Ad Soyad" required>
                        </div>
                    </div>

                    <div class="mt-2">
                        <input type="text" name="parent_phone" class="form-control" placeholder="Veli Telefon No" required>
                    </div>

                    <!-- Eğitim Seçimi -->
                    <div class="mt-3">
                        <label for="education_program_id" class="form-label fw-bold">Eğitim Programı</label>
                        <select id="education_program_id" name="education_program_id" class="form-select" required>
                            <option value="">-- Eğitim Programı Seçin --</option>
                            @foreach ($programs as $program)
                                <option value="{{ $program->id }}" {{ !$program->is_open ? 'disabled' : '' }}>
                                    {{ $program->title }} ({{ $program->age_range }}) - {{ $program->is_open ? 'Açık' : 'Kontenjan Doldu' }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Saat Seçimi (Dinamik) -->
                    <div class="mt-3" id="session_wrapper" style="display:none;">
                        <label for="session_id" class="form-label fw-bold">Saat Aralığı</label>
                        <select id="session_id" name="session_id" class="form-select"></select>
                    </div>

                    <div id="info_message" class="info-box" style="display:none;">
                        Saat ve kontenjan bilgisi <strong>Müdürlüğümüz tarafından belirlenecektir.</strong>
                    </div>

                    <div class="kvkk text-muted mt-3">
                        <strong>KVKK Bilgilendirmesi:</strong><br>
                        <a href="https://api.kirklarelibelediyesi.com/files/dokuman/kirklareli-kvkk.pdf" target="_blank">
                            Kişisel verilerin işlenmesine ilişkin aydınlatma metnine buradan ulaşabilirsiniz.
                        </a>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Kırklareli Belediyesi Veli Taahhütnamesi</label>
                        <div class="border rounded p-3 bg-light" style="font-size:.92rem;">
                            <strong>Sanat Atölyeleri Veli Başvuru Onayı</strong><br><br>
                            Velisi olduğum çocuğumun yaz/kış faaliyetlerine dair talimatları okudum ve sorumluluğun tarafıma ait olduğunu beyan ederim.
                        </div>
                    </div>

                    <div class="mb-3 text-center">
                        <label class="form-label">Veli İmzası (Parmak/fare ile çizin)</label>
                        <input type="hidden" name="signature" id="signature">
                        <canvas id="signature-pad" width="250" height="100"></canvas>
                        <button type="button" class="btn btn-sm btn-secondary mt-2" onclick="clearSignature()">Temizle</button>
                    </div>

                    <div class="d-grid mt-3">
                        <button type="submit" class="btn btn-main">Başvuruyu Gönder</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<footer class="text-center mt-3 text-muted">
    T.C Kırklareli Belediye Başkanlığı<br>Bilgi İşlem Müdürlüğü | Tüm Hakları Saklıdır.
</footer>

<!-- 🧠 Dinamik Saat veya Müdürlük Bilgisi Getirme -->
<script>
document.getElementById('education_program_id').addEventListener('change', function() {
    const eduId = this.value;
    const sessionWrapper = document.getElementById('session_wrapper');
    const sessionSelect = document.getElementById('session_id');
    const infoMessage = document.getElementById('info_message');

    sessionSelect.innerHTML = '';
    sessionWrapper.style.display = 'none';
    infoMessage.style.display = 'none';

    if (!eduId) return;

    // Önce kursun özel olup olmadığını kontrol et
    fetch(`/program/${eduId}`)
        .then(res => res.json())
        .then(program => {
            if (program.is_custom_schedule) {
                infoMessage.style.display = 'block';
                return; // saat listesini çağırmadan çık
            }

            // normal kurs için sessionları getir
            fetch(`/sessions/${eduId}`)
                .then(res => res.json())
                .then(data => {
                    sessionSelect.innerHTML = '<option value="">-- Saat Seçiniz --</option>';
                    if (data.length === 0) {
                        const opt = document.createElement('option');
                        opt.text = 'Bu eğitime ait saat ve tarihler müdürlüğümüzce belirlenecektir.';
                        opt.disabled = true;
                        sessionSelect.appendChild(opt);
                    } else {
                        data.forEach(sess => {
                            const opt = document.createElement('option');
                            opt.value = sess.id;
                            opt.text = `${sess.time_range} — (${sess.registered}/${sess.quota})`;
                            if (sess.is_full) {
                                opt.disabled = true;
                                opt.text += ' ❌ Kontenjan Dolu';
                            }
                            sessionSelect.appendChild(opt);
                        });
                    }
                    sessionWrapper.style.display = 'block';
                });
        })
        .catch(err => console.error('Fetch hatası:', err));
});
</script>

<!-- 🖋️ İmza Alanı Script -->
<script>
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
function updateSignature() { input.value = canvas.toDataURL('image/png'); }
function clearSignature() { ctx.clearRect(0, 0, canvas.width, canvas.height); input.value = ''; }

canvas.addEventListener('mousedown',  startDraw);
canvas.addEventListener('touchstart', startDraw);
canvas.addEventListener('mouseup',    endDraw);
canvas.addEventListener('touchend',   endDraw);
canvas.addEventListener('mouseout',   endDraw);
canvas.addEventListener('mousemove',  draw);
canvas.addEventListener('touchmove',  draw);
document.querySelector('form').addEventListener('submit', updateSignature);
</script>
</body>
</html>
