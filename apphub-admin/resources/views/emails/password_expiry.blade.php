<!DOCTYPE html>
<html>
<head>
    <title>Pemberitahuan Kata Sandi Akan Kedaluwarsa</title>
</head>
<body>
    <p>Dear {{ $user->name }},</p>

    <p>Kami informasikan bahwa kata sandi Anda akan kedaluwarsa dalam <strong>{{ $daysRemaining }} hari</strong>.</p>

    <p>Demi keamanan akun, mohon ubah kata sandi Anda sebelum kedaluwarsa.</p>

    <p>
        <a href="{{ url('/profile/security') }}" style="background-color: #3490dc; padding: 10px 15px; color: white; text-decoration: none; border-radius: 5px;">Change Password Now</a>
    </p>

    <p>Terima kasih,</p>
    <p>Tim Keamanan AppHub</p>
</body>
</html>
