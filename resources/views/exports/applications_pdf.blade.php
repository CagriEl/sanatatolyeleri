<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <title>Onaylı Başvurular</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 1rem; }
        th, td { border: 1px solid #444; padding: 4px; text-align: left; }
        th { background-color: #eee; }
        h1 { font-size: 18px; }
    </style>
</head>
<body>
    <h1>Onaylı Başvurular</h1>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Ad Soyad</th>
                <th>E-Posta</th>
                <th>TC No</th>
                <th>Program</th>
                <th>Yaş Aralığı</th>
            </tr>
        </thead>
        <tbody>
            @foreach($applications as $i => $app)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $app->first_name }} {{ $app->last_name }}</td>
                    <td>{{ $app->email }}</td>
                    <td>{{ $app->tc_no }}</td>
                    <td>{{ $app->educationProgram->title }}</td>
                    <td>{{ $app->educationProgram->age_range }} Yaş</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
