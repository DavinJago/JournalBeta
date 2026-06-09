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

        <div class="search-box">
    <input type="text" id="inputKeyword" placeholder="Ketik topik jurnal di sini..."
        onkeydown="if(event.key==='Enter') prosesCariJurnal()">
    <button onclick="prosesCariJurnal()">Cari Jurnal</button>
</div>

<!-- Side Panel Overlay -->
<div id="panel-overlay" onclick="tutupPanel()" style="
    display: none;
    position: fixed; inset: 0;
    background: rgba(0,0,0,0.3);
    z-index: 999;
    backdrop-filter: blur(2px);
"></div>

<!-- Side Panel -->
<div id="hasil-panel" style="
    position: fixed;
    top: 0; right: 0;
    width: 420px;
    max-width: 95vw;
    height: 100vh;
    background: #fff;
    box-shadow: -4px 0 32px rgba(0,0,0,0.15);
    z-index: 1000;
    transform: translateX(100%);
    transition: transform 0.35s cubic-bezier(.4,0,.2,1);
    display: flex;
    flex-direction: column;
    overflow: hidden;
">
    <!-- Panel Header -->
    <div style="
        padding: 20px 24px 16px;
        border-bottom: 1px solid #f0f0f0;
        display: flex; align-items: center; justify-content: space-between;
        background: #fafafa;
    ">
        <div>
            <span id="panel-keyword-label" style="
                font-size: 12px; font-weight: 600;
                color: #7c3aed; background: #f3f0ff;
                padding: 3px 10px; border-radius: 99px;
                display: inline-block; margin-bottom: 6px;
            "></span>
            <h3 id="panel-jumlah" style="margin:0; font-size: 15px; color: #1e1e2e; font-weight: 700;"></h3>
        </div>
        <button onclick="tutupPanel()" style="
            background: none; border: none;
            font-size: 20px; cursor: pointer;
            color: #888; line-height: 1;
            padding: 4px 8px; border-radius: 6px;
        " title="Tutup">✕</button>
    </div>

    <!-- Panel Body (scrollable) -->
    <div id="panel-list" style="
        flex: 1;
        overflow-y: auto;
        padding: 16px 20px;
    "></div>
</div>

<script>
function bukaPanel() {
    document.getElementById('hasil-panel').style.transform = 'translateX(0)';
    document.getElementById('panel-overlay').style.display = 'block';
    document.body.style.overflow = 'hidden';
}

function tutupPanel() {
    document.getElementById('hasil-panel').style.transform = 'translateX(100%)';
    document.getElementById('panel-overlay').style.display = 'none';
    document.body.style.overflow = '';
}

async function prosesCariJurnal() {
    const keyword = document.getElementById('inputKeyword').value.trim();
    if (!keyword) return;

    // Tampilkan panel dengan loading state
    document.getElementById('panel-keyword-label').textContent = '🔍 ' + keyword;
    document.getElementById('panel-jumlah').textContent = 'Sedang mencari...';
    document.getElementById('panel-list').innerHTML = `
        <div style="text-align:center; padding: 40px 20px; color: #999;">
            <div style="font-size: 32px; margin-bottom: 12px;">⏳</div>
            <p style="margin: 0; font-size: 14px;">Mengambil rekomendasi jurnal...</p>
        </div>
    `;
    bukaPanel();

    try {
        const response = await fetch(`/api/cari-jurnal?keyword=${encodeURIComponent(keyword)}`);
        const hasil = await response.json();

        if (hasil.status === 'Sukses' && hasil.data.length > 0) {
            document.getElementById('panel-jumlah').textContent = hasil.data.length + ' Jurnal Ditemukan';

            let html = '';
            hasil.data.forEach((jurnal, i) => {
                html += `
                    <div style="
                        margin-bottom: 14px;
                        padding: 14px 16px;
                        background: #f8f7ff;
                        border: 1px solid #ede9fe;
                        border-radius: 10px;
                        transition: box-shadow .2s;
                    " onmouseover="this.style.boxShadow='0 2px 12px rgba(124,58,237,.12)'"
                       onmouseout="this.style.boxShadow='none'">
                        <div style="
                            font-size: 11px; font-weight: 600;
                            color: #7c3aed; margin-bottom: 6px;
                            display: flex; align-items: center; gap: 6px;
                        ">
                            <span style="background:#7c3aed; color:#fff; border-radius:99px; padding:1px 7px; font-size:10px;">${i+1}</span>
                            Rekomendasi
                        </div>
                        <strong style="display:block; color:#1e1e2e; font-size:13px; line-height:1.4; margin-bottom:6px;">
                            ${jurnal.title}
                        </strong>
                        <p style="font-size:12px; color:#6b7280; margin:0 0 10px; line-height:1.5;">
                            ${jurnal.abstract ? jurnal.abstract.substring(0, 120) + '...' : 'Tidak ada abstrak.'}
                        </p>
                        <a href="${jurnal.url}" target="_blank" style="
                            display: inline-flex; align-items: center; gap: 5px;
                            font-size: 12px; font-weight: 600;
                            color: #fff; background: #7c3aed;
                            padding: 5px 12px; border-radius: 6px;
                            text-decoration: none;
                        ">
                            🔗 Buka Jurnal
                        </a>
                    </div>
                `;
            });

            document.getElementById('panel-list').innerHTML = html;
        } else {
            document.getElementById('panel-jumlah').textContent = 'Jurnal tidak ditemukan';
            document.getElementById('panel-list').innerHTML = `
                <div style="text-align:center; padding: 40px 20px; color: #999;">
                    <div style="font-size: 40px; margin-bottom: 12px;">🔎</div>
                    <p style="margin: 0 0 6px; font-weight: 600; color: #555;">Tidak ada hasil</p>
                    <p style="margin: 0; font-size: 13px;">Coba kata kunci yang lebih spesifik.</p>
                </div>
            `;
        }
    } catch (error) {
        document.getElementById('panel-jumlah').textContent = 'Terjadi kesalahan';
        document.getElementById('panel-list').innerHTML = `
            <div style="text-align:center; padding: 40px 20px; color: #e53e3e;">
                <div style="font-size: 36px; margin-bottom: 12px;">⚠️</div>
                <p style="margin: 0; font-size: 13px;">Gagal terhubung ke server. Coba lagi.</p>
            </div>
        `;
    }
}
</script>

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