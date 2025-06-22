<!DOCTYPE html>
<html>
<body>
    <h2>Sayın {{ $application->first_name }} {{ $application->last_name }},</h2>

    <p>Başvurmuş olduğunuz <strong>{{ $application->educationProgram->title }}</strong> programına kaydınız onaylanmıştır.</p>

    <p>Lütfen detaylar için kurumumuzla iletişime geçiniz.</p>

    <p>Teşekkür ederiz.</p>
</body>
</html>
