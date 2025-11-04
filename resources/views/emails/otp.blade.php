<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>OTP EduLux</title>
</head>
<body style="font-family: Arial, sans-serif; color:#222;">
    <h2 style="margin-bottom:8px;">Verifikasi Email EduLux LMS</h2>
    <p>Halo {{ $name }},</p>
    <p>Kode OTP Anda:</p>
    <div style="font-size:24px; font-weight:bold; letter-spacing:4px;">{{ $code }}</div>
    <p style="margin-top:8px;">Kode berlaku sampai {{ $expiresAt->format('H:i:s') }} (1 menit).</p>
    <p>Jika Anda tidak meminta kode ini, abaikan email ini.</p>
    <hr style="margin-top:16px;">
    <p style="font-size:12px; color:#666;">&copy; {{ date('Y') }} EduLux LMS</p>
</body>
</html>