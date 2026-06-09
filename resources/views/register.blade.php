<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - JurnaLens</title>

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
</head>
<body>

    <nav class="navbar">
        <div class="logo">
            <div class="logo-icon">
                <i class="fa-solid fa-magnifying-glass"></i>
            </div>
            <span>JurnaLens</span>
        </div>

        <ul class="menu">
            <li><a href="/">Beranda</a></li>
            <li><a href="#">Bantuan</a></li>
        </ul>

        <div class="nav-right">
            <a href="/login" style="text-decoration: none;">
                <button class="logout-btn" style="background-color: transparent; border: 1px solid #ddd; color: #333;">
                    <i class="fa-solid fa-right-to-bracket"></i>
                    Login
                </button>
            </a>
        </div>
    </nav>

    <section class="hero" style="height: calc(100vh - 70px); display: flex; flex-direction: column; justify-content: center; align-items: center; padding-top: 40px; box-sizing: border-box;">

        <h1 style="margin: 0 0 5px 0; font-size: 2.5rem; line-height: 1.2;">
            Mulai Penelitianmu<br>
            <span>Daftar Akun Baru</span>
        </h1>

        <p style="margin: 0 0 40px 0; font-size: 1.125rem;">Sudah punya akun? <a href="/login" style="color: #3b82f6; text-decoration: none; font-weight: 400;">Login di sini</a></p>

        <div class="search-box" style="display: flex; flex-direction: column; gap: 12px; width: 100%; max-width: 500px; padding: 50px; background: white; border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); box-sizing: border-box; margin: 0;">
            
            <div class="search-input" style="display: flex; align-items: center; border: 1px solid #e2e8f0; border-radius: 8px; padding: 8px 12px;">
                <i class="fa-solid fa-user" style="color: #94a3b8; margin-right: 10px;"></i>
                <input type="text" placeholder="Nama Lengkap Anda" style="border: none; outline: none; width: 100%; font-family: 'Inter', sans-serif; font-size: 14px;">
            </div>

            <div class="search-input" style="display: flex; align-items: center; border: 1px solid #e2e8f0; border-radius: 8px; padding: 8px 12px;">
                <i class="fa-solid fa-envelope" style="color: #94a3b8; margin-right: 10px;"></i>
                <input type="email" placeholder="Alamat Email" style="border: none; outline: none; width: 100%; font-family: 'Inter', sans-serif; font-size: 14px;">
            </div>

            <div class="search-input" style="display: flex; align-items: center; border: 1px solid #e2e8f0; border-radius: 8px; padding: 8px 12px;">
                <i class="fa-solid fa-lock" style="color: #94a3b8; margin-right: 10px;"></i>
                <input type="password" placeholder="Buat Password" style="border: none; outline: none; width: 100%; font-family: 'Inter', sans-serif; font-size: 14px;">
            </div>

            <div class="search-input" style="display: flex; align-items: center; border: 1px solid #e2e8f0; border-radius: 8px; padding: 8px 12px;">
                <i class="fa-solid fa-shield-halved" style="color: #94a3b8; margin-right: 10px;"></i>
                <input type="password" placeholder="Ulangi Password" style="border: none; outline: none; width: 100%; font-family: 'Inter', sans-serif; font-size: 14px;">
            </div>

            <button class="search-btn" style="width: 100%; padding: 10px; border-radius: 8px; font-weight: 600; font-size: 15px; cursor: pointer; margin-top: 5px;">
                Daftar Sekarang
            </button>

        </div>

    </section>

</body>
</html>