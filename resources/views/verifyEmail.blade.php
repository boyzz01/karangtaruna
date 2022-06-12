<html lang="en">

<body>

    <p>Dear {{ $user->name }}</p>
    <p>Akun Anda Berhasil Dibuat, Berikut Kode Aktivasi Anda</p>
    <p style="font-size: 30px;">{{ $user->email_token }}</p>

    <p>Thanks</p>

</body>

</html>
