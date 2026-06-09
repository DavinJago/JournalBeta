<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>

<body>
    <div class="formulir">
        <div class="flex-1">
            <h1>Halo Jurnalers! 👋</h1>
            <p>Solusi untukmu Meringkas Jurnal!</p>
        </div>
        <div class="flex-2">
            <div>
                <div>
                    <p>Welcome Back!</p>
                    <p>Belum Punya Akun? Register Dulu</p>
                </div>
                <div>
                    <form>
                        <p><input type="email" placeholder="Jurnal@gmail.com"></p>
                        <p><input type="password" placeholder="Jurnal"></p>
                        <button type="submit">Login Now</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>