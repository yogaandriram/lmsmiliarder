<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Atur Ulang Kata Sandi</title>
</head>
<body style="font-family: Arial, sans-serif; color:#222;">
    <h2 style="margin-bottom:8px;">Permintaan Atur Ulang Kata Sandi</h2>
    <p>Halo {{ $name }},</p>
    <p>Kami menerima permintaan untuk mengatur ulang kata sandi akun Anda.</p>
    <p>Silakan klik tombol di bawah ini untuk mengatur ulang kata sandi:</p>
    <p>
        <a href="{{ $resetUrl }}" style="display:inline-block;padding:10px 16px;background:#FFB800;color:#000;text-decoration:none;border-radius:6px;">Atur Ulang Kata Sandi</a>
    </p>
    <p>Link ini berlaku selama 60 menit.</p>
    <p>Jika Anda tidak meminta perubahan ini, abaikan email ini.</p>
    <hr style="margin-top:16px;">
    <p style="font-size:12px; color:#666;">&copy; {{ date('Y') }} EduLux LMS</p>
</body>
</html>