<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Onaylı Başvurular</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #999; padding: 6px; text-align: left; }
        th { background: #f0f0f0; }
    </style>
</head>
<body>
    <h2>Onaylı Başvurular</h2>
    <table>
        <thead>
            <tr>
                <th>Ad Soyad</th>
                <th>TC</th>
                <th>Telefon</th>
                <th>Veli</th>
                <th>Program</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($applications as $app)
                <tr>
                    <td>{{ $app->first_name }} {{ $app->last_name }}</td>
                    <td>{{ $app->tc_no }}</td>
                    <td>{{ $app->phone }}</td>
                    <td>{{ $app->parent_name }} ({{ $app->parent_phone }})</td>
                    <td>{{ $app->educationProgram->title ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
