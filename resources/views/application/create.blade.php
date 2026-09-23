<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Kış Okulu Başvuru Formu</title>
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
            --teal-soft: #e4f1f1;
            --snow: #f4f8fa;
            --panel: #ffffff;
            --line: rgba(18, 32, 46, 0.12);
            --danger: #b42318;
            --ok: #0f6b4c;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: "Sora", sans-serif;
            color: var(--ink);
            background:
                radial-gradient(ellipse 70% 45% at 0% 0%, rgba(14, 107, 110, 0.12), transparent 55%),
                radial-gradient(ellipse 55% 40% at 100% 10%, rgba(90, 120, 150, 0.12), transparent 50%),
                linear-gradient(180deg, #dfeaf0 0%, var(--snow) 35%, #e8eef2 100%);
        }

        a { color: var(--teal); }

        .page {
            width: min(720px, calc(100% - 2rem));
            margin: 0 auto;
            padding: clamp(1.25rem, 3vw, 2.5rem) 0 2.5rem;
        }

        .topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            margin-bottom: 1.5rem;
            opacity: 0;
            animation: rise 0.55s ease forwards;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
            color: inherit;
        }

        .brand img {
            width: 52px;
            height: auto;
        }

        .brand strong {
            display: block;
            font-family: "Fraunces", serif;
            font-size: 1.05rem;
            font-weight: 700;
            line-height: 1.15;
        }

        .brand span {
            display: block;
            font-size: 0.72rem;
            color: var(--muted);
            margin-top: 0.15rem;
        }

        .back-link {
            font-size: 0.85rem;
            font-weight: 500;
            text-decoration: none;
            color: var(--muted);
            white-space: nowrap;
        }

        .back-link:hover { color: var(--teal); }

        .panel {
            background: var(--panel);
            border: 1px solid var(--line);
            border-radius: 6px;
            padding: clamp(1.25rem, 3vw, 2rem);
            opacity: 0;
            animation: rise 0.65s ease 0.08s forwards;
        }

        .panel-head {
            margin-bottom: 1.75rem;
            padding-bottom: 1.25rem;
            border-bottom: 1px solid var(--line);
        }

        .panel-head h1 {
            margin: 0 0 0.45rem;
            font-family: "Fraunces", serif;
            font-size: clamp(1.7rem, 4vw, 2.15rem);
            font-weight: 700;
            letter-spacing: -0.02em;
            line-height: 1.15;
        }

        .panel-head p {
            margin: 0;
            color: var(--muted);
            font-size: 0.95rem;
            line-height: 1.5;
        }

        .section {
            margin-bottom: 1.5rem;
        }

        .section-title {
            margin: 0 0 0.85rem;
            font-size: 0.72rem;
            font-weight: 600;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: var(--teal);
        }

        .grid {
            display: grid;
            gap: 0.85rem;
        }

        @media (min-width: 576px) {
            .grid.two { grid-template-columns: 1fr 1fr; }
        }

        .field label {
            display: block;
            margin-bottom: 0.35rem;
            font-size: 0.82rem;
            font-weight: 500;
            color: var(--ink);
        }

        .field input,
        .field select {
            width: 100%;
            padding: 0.72rem 0.85rem;
            border: 1px solid var(--line);
            border-radius: 4px;
            background: #fbfcfd;
            color: var(--ink);
            font: inherit;
            font-size: 0.95rem;
            transition: border-color 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
        }

        .field input:focus,
        .field select:focus {
            outline: none;
            border-color: var(--teal);
            background: #fff;
            box-shadow: 0 0 0 3px rgba(14, 107, 110, 0.12);
        }

        .field-hint {
            margin-top: 0.4rem;
            font-size: 0.8rem;
            line-height: 1.4;
            color: var(--muted);
        }

        .field-hint.is-ok { color: var(--ok); }
        .field-hint.is-warn { color: #9a5b00; }
        .field-hint.is-error { color: var(--danger); }

        .info-box {
            margin-top: 0.85rem;
            padding: 0.9rem 1rem;
            background: var(--teal-soft);
            border-left: 3px solid var(--teal);
            color: var(--teal-deep);
            font-size: 0.9rem;
            line-height: 1.5;
        }

        .note {
            margin-top: 1rem;
            padding: 0.9rem 1rem;
            background: #f3f5f7;
            border-radius: 4px;
            font-size: 0.85rem;
            color: var(--muted);
            line-height: 1.5;
        }

        .pledge {
            padding: 1rem;
            border: 1px solid var(--line);
            border-radius: 4px;
            background: #fafbfc;
            font-size: 0.9rem;
            line-height: 1.55;
        }

        .pledge strong {
            display: block;
            margin-bottom: 0.5rem;
            font-family: "Fraunces", serif;
            font-size: 1.05rem;
        }

        .signature-wrap {
            text-align: center;
        }

        #signature-pad {
            width: min(100%, 320px) !important;
            height: 120px !important;
            display: block;
            margin: 0.5rem auto 0;
            border: 1px dashed var(--line);
            border-radius: 4px;
            background: #fff;
            touch-action: none;
            cursor: crosshair;
        }

        .btn-clear {
            margin-top: 0.65rem;
            padding: 0.4rem 0.85rem;
            border: 1px solid var(--line);
            border-radius: 4px;
            background: #fff;
            color: var(--muted);
            font: inherit;
            font-size: 0.82rem;
            font-weight: 500;
            cursor: pointer;
        }

        .btn-clear:hover {
            color: var(--ink);
            border-color: var(--muted);
        }

        .btn-submit {
            width: 100%;
            margin-top: 0.5rem;
            padding: 0.95rem 1.25rem;
            border: none;
            border-radius: 4px;
            background: var(--teal);
            color: #fff;
            font: inherit;
            font-weight: 600;
            font-size: 0.98rem;
            cursor: pointer;
            transition: background 0.25s ease, transform 0.25s ease;
        }

        .btn-submit:hover {
            background: var(--teal-deep);
            transform: translateY(-1px);
        }

        .alert {
            margin-bottom: 1.25rem;
            padding: 0.85rem 1rem;
            border-radius: 4px;
            font-size: 0.9rem;
            line-height: 1.45;
        }

        .alert-success {
            background: #e8f6ef;
            color: var(--ok);
            border-left: 3px solid var(--ok);
        }

        .alert-danger {
            background: #fceeee;
            color: var(--danger);
            border-left: 3px solid var(--danger);
        }

        .alert ul {
            margin: 0;
            padding-left: 1.1rem;
        }

        .site-footer {
            margin-top: 1.75rem;
            text-align: center;
            font-size: 0.78rem;
            color: var(--muted);
            line-height: 1.5;
            opacity: 0;
            animation: rise 0.6s ease 0.18s forwards;
        }

        @keyframes rise {
            from {
                opacity: 0;
                transform: translateY(14px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>
<div class="page">
    <div class="topbar">
        <a class="brand" href="{{ url('/') }}">
            <img src="{{ asset('images/logo.png') }}" alt="Kırklareli Belediyesi">
            <div>
                <strong>Kış Dönemi Sanat Atölyeleri</strong>
                <span>Kırklareli Belediyesi</span>
            </div>
        </a>
        <a class="back-link" href="{{ url('/') }}">← Geri</a>
    </div>

    <div class="panel">
        <div class="panel-head">
            <h1>Başvuru Formu</h1>
            <p>Yaş grubunuza uygun dersi seçin. Aynı TC ile en fazla 2 kursa başvuru yapılabilir.</p>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ url('/basvuru') }}">
            @csrf

            <div class="section">
                <h2 class="section-title">Kişisel Bilgiler</h2>
                <div class="grid two">
                    <div class="field">
                        <label for="first_name">Ad</label>
                        <input id="first_name" type="text" name="first_name" value="{{ old('first_name') }}" required>
                    </div>
                    <div class="field">
                        <label for="last_name">Soyad</label>
                        <input id="last_name" type="text" name="last_name" value="{{ old('last_name') }}" required>
                    </div>
                    <div class="field" style="grid-column: 1 / -1;">
                        <label for="email">E-posta</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required>
                    </div>
                    <div class="field">
                        <label for="tc_no">TC Kimlik No</label>
                        <input id="tc_no" type="text" name="tc_no" value="{{ old('tc_no') }}" minlength="11" maxlength="11" pattern="\d{11}" inputmode="numeric" required>
                        <div id="tc_hint" class="field-hint" style="display:none;"></div>
                    </div>
                    <div class="field">
                        <label for="birth_date">Doğum Tarihi</label>
                        <input id="birth_date" type="date" name="birth_date" value="{{ old('birth_date') }}" required>
                        <div id="age_hint" class="field-hint" style="display:none;"></div>
                    </div>
                    <div class="field">
                        <label for="phone">Telefon</label>
                        <input id="phone" type="text" name="phone" value="{{ old('phone') }}" required>
                    </div>
                    <div class="field">
                        <label for="parent_name">Veli Ad Soyad</label>
                        <input id="parent_name" type="text" name="parent_name" value="{{ old('parent_name') }}" required>
                    </div>
                    <div class="field" style="grid-column: 1 / -1;">
                        <label for="parent_phone">Veli Telefon</label>
                        <input id="parent_phone" type="text" name="parent_phone" value="{{ old('parent_phone') }}" required>
                    </div>
                </div>
            </div>

            <div class="section">
                <h2 class="section-title">Eğitim Seçimi</h2>
                <div class="field">
                    <label for="education_program_id">Eğitim Programı</label>
                    <select id="education_program_id" name="education_program_id" required>
                        <option value="">Program seçin</option>
                        @foreach ($programs as $program)
                            <option
                                value="{{ $program->id }}"
                                data-age-range="{{ $program->age_range }}"
                                data-age-label="{{ $program->ageRequirementLabel() }}"
                                {{ old('education_program_id') == $program->id ? 'selected' : '' }}
                            >
                                {{ $program->title }}
                                @if($program->instructor) — {{ $program->instructor }} @endif
                                ({{ $program->age_range }} yaş
                                @if($program->location), {{ $program->location }} @endif
                                ) — {{ $program->applications_count }}/{{ $program->capacity }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="field" id="session_wrapper" style="display:none; margin-top: 0.85rem;">
                    <label for="session_id">Saat Aralığı</label>
                    <select id="session_id" name="session_id"></select>
                </div>

                <div id="schedule_info" class="info-box" style="display:none;"></div>
                <div id="info_message" class="info-box" style="display:none;"></div>
            </div>

            <div class="section">
                <h2 class="section-title">Onay</h2>

                <div class="note">
                    <strong>KVKK:</strong>
                    <a href="https://api.kirklarelibelediyesi.com/files/dokuman/kirklareli-kvkk.pdf" target="_blank" rel="noopener">
                        Aydınlatma metnini buradan okuyabilirsiniz.
                    </a>
                </div>

                <div style="margin-top: 1rem;">
                    <label class="section-title" style="margin-bottom: 0.65rem; display:block;">Veli Taahhütnamesi</label>
                    <div class="pledge">
                        <strong>Kış Okulu Veli Başvuru Onayı</strong>
                        Velisi olduğum çocuğumun kış okulu faaliyetlerine dair talimatları okudum ve sorumluluğun tarafıma ait olduğunu beyan ederim.
                    </div>
                </div>

                <div class="signature-wrap" style="margin-top: 1.25rem;">
                    <label for="signature-pad" style="font-size: 0.82rem; font-weight: 500;">Veli İmzası</label>
                    <input type="hidden" name="signature" id="signature">
                    <canvas id="signature-pad" width="320" height="120"></canvas>
                    <button type="button" class="btn-clear" onclick="clearSignature()">İmzayı Temizle</button>
                </div>
            </div>

            <button type="submit" class="btn-submit">Başvuruyu Gönder</button>
        </form>
    </div>

    <footer class="site-footer">
        T.C. Kırklareli Belediye Başkanlığı<br>
        Bilgi İşlem Müdürlüğü
    </footer>
</div>

<script>
function calcAge(dateStr) {
    if (!dateStr) return null;
    const birth = new Date(dateStr + 'T00:00:00');
    const today = new Date();
    let age = today.getFullYear() - birth.getFullYear();
    const m = today.getMonth() - birth.getMonth();
    if (m < 0 || (m === 0 && today.getDate() < birth.getDate())) age--;
    return age;
}

function acceptsAge(age, range) {
    if (age === null || !range) return true;
    range = String(range).trim();
    let m = range.match(/^(\d+)\s*\+$/);
    if (m) return age >= parseInt(m[1], 10);
    m = range.match(/^(\d+)\s*[-–]\s*(\d+)$/);
    if (m) return age >= parseInt(m[1], 10) && age <= parseInt(m[2], 10);
    m = range.match(/^(\d+)$/);
    if (m) return age === parseInt(m[1], 10);
    return true;
}

function updateAgeHint() {
    const hint = document.getElementById('age_hint');
    const birth = document.getElementById('birth_date').value;
    const select = document.getElementById('education_program_id');
    const option = select.options[select.selectedIndex];
    const age = calcAge(birth);

    hint.style.display = 'none';
    hint.className = 'field-hint';

    if (age === null) return;

    if (!select.value) {
        hint.textContent = `Başvuranın yaşı: ${age}`;
        hint.style.display = 'block';
        return;
    }

    const range = option.dataset.ageRange;
    const label = option.dataset.ageLabel || (range + ' yaş');

    if (acceptsAge(age, range)) {
        hint.textContent = `Yaş uygun (${age}) — Program: ${label}`;
        hint.classList.add('is-ok');
    } else {
        hint.textContent = `Yaş uygun değil (${age}). Bu program ${label} içindir.`;
        hint.classList.add('is-error');
    }
    hint.style.display = 'block';
}

document.getElementById('tc_no').addEventListener('blur', function () {
    const tc = this.value.trim();
    const hint = document.getElementById('tc_hint');
    hint.style.display = 'none';
    hint.className = 'field-hint';

    if (!/^\d{11}$/.test(tc)) return;

    fetch(`/tc-check/${tc}`)
        .then(r => r.json())
        .then(data => {
            hint.textContent = data.message;
            if (data.remaining === 0) hint.classList.add('is-error');
            else if (data.count > 0) hint.classList.add('is-warn');
            else hint.classList.add('is-ok');
            hint.style.display = 'block';
        })
        .catch(() => {});
});

document.getElementById('birth_date').addEventListener('change', updateAgeHint);
document.getElementById('education_program_id').addEventListener('change', updateAgeHint);

document.getElementById('education_program_id').addEventListener('change', function() {
    const eduId = this.value;
    const sessionWrapper = document.getElementById('session_wrapper');
    const sessionSelect = document.getElementById('session_id');
    const infoMessage = document.getElementById('info_message');
    const scheduleInfo = document.getElementById('schedule_info');

    sessionSelect.innerHTML = '';
    sessionWrapper.style.display = 'none';
    infoMessage.style.display = 'none';
    scheduleInfo.style.display = 'none';

    if (!eduId) return;

    fetch(`/program/${eduId}`)
        .then(res => res.json())
        .then(program => {
            if (program.is_full || program.is_open === false) {
                infoMessage.textContent = 'Bu kurs için kontenjan dolmuştur. Lütfen başka bir kurs seçiniz.';
                infoMessage.style.display = 'block';
                return;
            }

            if (program.is_custom_schedule) {
                infoMessage.innerHTML = 'Saat ve kontenjan bilgisi <strong>Müdürlüğümüz tarafından belirlenecektir.</strong>';
                infoMessage.style.display = 'block';
                return;
            }

            fetch(`/sessions/${eduId}`)
                .then(res => res.json())
                .then(data => {
                    const combined = data.length === 1 && data[0].is_combined;

                    if (combined) {
                        const sess = data[0];
                        scheduleInfo.innerHTML = `<strong>Eğitim Günleri:</strong> ${sess.time_range}<br>
                            <strong>Kontenjan:</strong> ${sess.registered}/${sess.quota}
                            ${sess.is_full ? ' — <strong>Kontenjan Dolu</strong>' : ''}
                            <br><small>Bu kursa kayıt olan katılımcılar belirtilen tüm günlere kayıt olmuş sayılır.</small>`;
                        scheduleInfo.style.display = 'block';
                        return;
                    }

                    sessionSelect.innerHTML = '<option value="">Saat seçin</option>';
                    if (data.length === 0) {
                        const opt = document.createElement('option');
                        opt.text = 'Bu eğitime ait saat ve tarihler müdürlüğümüzce belirlenecektir.';
                        opt.disabled = true;
                        sessionSelect.appendChild(opt);
                    } else {
                        data.forEach(sess => {
                            const opt = document.createElement('option');
                            opt.value = sess.id;
                            opt.text = `${sess.time_range} — ${sess.registered}/${sess.quota}`;
                            if (sess.is_full) {
                                opt.disabled = true;
                                opt.text += ' — Kontenjan Dolu';
                            }
                            sessionSelect.appendChild(opt);
                        });

                        const selectable = [...sessionSelect.options].filter(o => o.value && !o.disabled);
                        if (selectable.length === 1) {
                            sessionSelect.value = selectable[0].value;
                        }
                    }
                    sessionWrapper.style.display = 'block';
                });
        })
        .catch(err => console.error('Fetch hatası:', err));
});

if (document.getElementById('tc_no').value.length === 11) {
    document.getElementById('tc_no').dispatchEvent(new Event('blur'));
}
updateAgeHint();
</script>

<script>
const canvas = document.getElementById('signature-pad');
const input  = document.getElementById('signature');
const ctx    = canvas.getContext('2d');
let drawing  = false;

function getPos(e) {
    const rect = canvas.getBoundingClientRect();
    const scaleX = canvas.width / rect.width;
    const scaleY = canvas.height / rect.height;
    const clientX = e.touches ? e.touches[0].clientX : e.clientX;
    const clientY = e.touches ? e.touches[0].clientY : e.clientY;
    return {
        x: (clientX - rect.left) * scaleX,
        y: (clientY - rect.top) * scaleY,
    };
}

function startDraw(e) {
    e.preventDefault();
    drawing = true;
    const { x, y } = getPos(e);
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
    const { x, y } = getPos(e);
    ctx.lineWidth = 2;
    ctx.lineCap = 'round';
    ctx.strokeStyle = '#12202e';
    ctx.lineTo(x, y);
    ctx.stroke();
    ctx.beginPath();
    ctx.moveTo(x, y);
    updateSignature();
}
function updateSignature() { input.value = canvas.toDataURL('image/png'); }
function clearSignature() { ctx.clearRect(0, 0, canvas.width, canvas.height); input.value = ''; }

canvas.addEventListener('mousedown',  startDraw);
canvas.addEventListener('touchstart', startDraw, { passive: false });
canvas.addEventListener('mouseup',    endDraw);
canvas.addEventListener('touchend',   endDraw);
canvas.addEventListener('mouseout',   endDraw);
canvas.addEventListener('mousemove',  draw);
canvas.addEventListener('touchmove',  draw, { passive: false });
document.querySelector('form').addEventListener('submit', function (e) {
    updateSignature();
    if (!input.value || input.value.length < 100) {
        e.preventDefault();
        alert('Lütfen veli imzasını çizin.');
    }
});
</script>
</body>
</html>
