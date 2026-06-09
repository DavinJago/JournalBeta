<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JurnaLens</title>

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar">

        <div class="logo">
            <div class="logo-icon">
                <i class="fa-solid fa-magnifying-glass"></i>
            </div>
            <span>JurnaLens</span>
        </div>

        <ul class="menu">
            <li><a href="#">Beranda</a></li>
            <li><a href="#">Jurnal</a></li>
            <li><a href="#">Fitur</a></li>
            <li><a href="#">Pricing</a></li>
            <li><a href="#">Bantuan</a></li>
        </ul>

        <div class="nav-right">
            <i class="fa-regular fa-bell"></i>

            <button class="logout-btn">
                <i class="fa-solid fa-right-from-bracket"></i>
                Logout
            </button>
        </div>

    </nav>

    <!-- Hero -->
    <section class="hero">

        <div class="badge">
            AI Powered Research
        </div>

        <h1>
            Temukan Referensi Akademik
            <br>
            Lebih Cepat dengan
            <span>AI</span>
        </h1>

        <p>
            Cari jutaan jurnal, skripsi, dan paper dari database terpercaya.
            <br>
            Dapatkan ringkasan, analisis, dan rekomendasi relevan dari AI.
        </p>

        <!-- Search -->
        <div class="search-box">

            <div class="search-input">

                <a href="/tambah-jurnal" class="add-journal-btn">
                <i class="fa-solid fa-plus"></i>
                </a>

                <input
                     type="text"placeholder="Cari jurnal berdasarkan judul, penulis, atau kata kunci..."
    >

</div>

            <button class="search-btn">
                Cari
            </button>

        </div>

    </section>

    <!-- Features -->

    <section class="features">

        <div class="card">

            <div class="icon blue">
                <i class="fa-solid fa-book"></i>
            </div>

            <h3>Cari Jurnal</h3>

            <p>
                Temukan jurnal, skripsi dan paper dari berbagai sumber terpercaya.
            </p>

        </div>

        <div class="card">

            <div class="icon green">
                <i class="fa-solid fa-robot"></i>
            </div>

            <h3>Ringkasan AI</h3>

            <p>
                Dapatkan ringkasan otomatis dari jurnal yang relevan.
            </p>

        </div>

        <div class="card">

            <div class="icon purple">
                <i class="fa-solid fa-chart-column"></i>
            </div>

            <h3>Analisis Sitasi</h3>

            <p>
                Lihat tren sitasi dan dampak penelitian secara visual.
            </p>

        </div>

        <div class="card">

            <div class="icon orange">
                <i class="fa-solid fa-bookmark"></i>
            </div>

            <h3>Kelola Referensi</h3>

            <p>
                Simpan dan atur referensi favorit Anda dengan mudah.
            </p>

        </div>

    </section>

</body>
</html>