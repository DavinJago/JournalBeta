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
            <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                @csrf
                <button class="logout-btn" type="submit">
                    <i class="fa-solid fa-right-from-bracket"></i>
                    Logout
                </button>
            </form>
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

        <div class="search-input-wrapper">
    <span class="search-icon">
        <i class="fa-solid fa-magnifying-glass-plus"></i>
    </span>
    <input type="text" id="inputKeyword" placeholder="Masukkan topik penelitian, kata kunci, atau judul jurnal..."
        onkeydown="if(event.key==='Enter') prosesCariJurnal()">
</div>

<style>
    /* Layout split */
    .app-layout {
        display: flex;
        transition: all 0.35s cubic-bezier(.4,0,.2,1);
        min-height: 100vh;
        position: relative;
    }

    .main-content {
        flex: 1;
        min-width: 0;
        transition: all 0.35s cubic-bezier(.4,0,.2,1);
    }

    .hasil-panel {
        width: 0;
        min-width: 0;
        overflow: hidden;
        background: #fff;
        border-left: 0px solid #ede9fe;
        transition: all 0.35s cubic-bezier(.4,0,.2,1);
        display: flex;
        flex-direction: column;
        position: fixed;
        right: 0;
        top: 0;
        height: 100vh;
        z-index: 100;
        box-shadow: none;
    }

    .hasil-panel.open {
        width: 50vw;
        min-width: 320px;
        border-left: 1px solid #ede9fe;
        box-shadow: -4px 0 24px rgba(124,58,237,0.08);
    }

    /* Geser body saat panel terbuka */
    body.panel-open {
        padding-right: 50vw;
        transition: padding-right 0.35s cubic-bezier(.4,0,.2,1);
    }

    /* Panel inner content */
    .panel-header {
        padding: 18px 20px 14px;
        border-bottom: 1px solid #f0f0f0;
        background: #fafafa;
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        flex-shrink: 0;
    }

    .panel-keyword-badge {
        font-size: 11px;
        font-weight: 600;
        color: #7c3aed;
        background: #f3f0ff;
        padding: 3px 10px;
        border-radius: 99px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        margin-bottom: 5px;
    }

    .panel-title {
        margin: 0;
        font-size: 14px;
        font-weight: 700;
        color: #1e1e2e;
    }

    .panel-close-btn {
        background: #f3f0ff;
        border: none;
        font-size: 16px;
        cursor: pointer;
        color: #7c3aed;
        padding: 5px 9px;
        border-radius: 8px;
        line-height: 1;
        flex-shrink: 0;
        margin-left: 8px;
        transition: background .2s;
    }

    .panel-close-btn:hover {
        background: #ede9fe;
    }

    .panel-body {
        flex: 1;
        overflow-y: auto;
        padding: 16px;
    }

    .jurnal-card {
        margin-bottom: 12px;
        padding: 14px;
        background: #f8f7ff;
        border: 1px solid #ede9fe;
        border-radius: 10px;
        transition: box-shadow .2s;
    }

    .jurnal-card:hover {
        box-shadow: 0 2px 12px rgba(124,58,237,0.12);
    }

    .jurnal-num {
        background: #7c3aed;
        color: #fff;
        border-radius: 99px;
        padding: 1px 7px;
        font-size: 10px;
        font-weight: 700;
    }

    .jurnal-card-label {
        font-size: 11px;
        font-weight: 600;
        color: #7c3aed;
        display: flex;
        align-items: center;
        gap: 6px;
        margin-bottom: 7px;
    }

    .jurnal-card-title {
        font-size: 13px;
        font-weight: 700;
        color: #1e1e2e;
        line-height: 1.4;
        margin: 0 0 6px;
    }

    .jurnal-card-abstract {
        font-size: 12px;
        color: #6b7280;
        margin: 0 0 10px;
        line-height: 1.5;
    }

    .jurnal-card-btn {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-size: 12px;
        font-weight: 600;
        color: #fff;
        background: #7c3aed;
        padding: 5px 12px;
        border-radius: 6px;
        text-decoration: none;
        transition: background .2s;
    }

    .jurnal-card-btn:hover {
        background: #6d28d9;
    }

    .panel-empty {
        text-align: center;
        padding: 50px 20px;
        color: #999;
    }

    .panel-empty .icon {
        font-size: 40px;
        margin-bottom: 12px;
    }

    @media (max-width: 768px) {
        .hasil-panel.open {
            width: 100vw;
        }
        body.panel-open {
            padding-right: 0;
        }
    }
</style>

<!-- Side Panel -->
<div class="hasil-panel" id="hasil-panel">
    <div class="panel-header">
        <div>
            <div class="panel-keyword-badge">
                <span>🔍</span>
                <span id="panel-keyword-label"></span>
            </div>
            <h3 class="panel-title" id="panel-jumlah"></h3>
        </div>
        <button class="panel-close-btn" onclick="tutupPanel()" title="Tutup">✕</button>
    </div>
    <div class="panel-body" id="panel-list"></div>
</div>

<script>
function bukaPanel() {
    document.getElementById('hasil-panel').classList.add('open');
    document.body.classList.add('panel-open');
}

function tutupPanel() {
    document.getElementById('hasil-panel').classList.remove('open');
    document.body.classList.remove('panel-open');
}

async function prosesCariJurnal() {
    const keyword = document.getElementById('inputKeyword').value.trim();
    if (!keyword) return;

    document.getElementById('panel-keyword-label').textContent = keyword;
    document.getElementById('panel-jumlah').textContent = 'Sedang mencari...';
    document.getElementById('panel-list').innerHTML = `
        <div class="panel-empty">
            <div class="icon">⏳</div>
            <p style="margin:0; font-size:13px;">Mengambil rekomendasi jurnal...</p>
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
                    <div class="jurnal-card">
                        <div class="jurnal-card-label">
                            <span class="jurnal-num">${i + 1}</span>
                            Rekomendasi
                        </div>
                        <p class="jurnal-card-title">${jurnal.title}</p>
                        <p class="jurnal-card-abstract">
                            ${jurnal.abstract ? jurnal.abstract.substring(0, 120) + '...' : 'Tidak ada abstrak.'}
                        </p>
                        <a href="${jurnal.url}" target="_blank" class="jurnal-card-btn">
                            🔗 Buka Jurnal
                        </a>
                    </div>
                `;
            });

            document.getElementById('panel-list').innerHTML = html;
        } else {
            document.getElementById('panel-jumlah').textContent = 'Jurnal tidak ditemukan';
            document.getElementById('panel-list').innerHTML = `
                <div class="panel-empty">
                    <div class="icon">🔎</div>
                    <p style="margin:0 0 4px; font-weight:600; color:#555;">Tidak ada hasil</p>
                    <p style="margin:0; font-size:12px;">Coba kata kunci yang lebih spesifik.</p>
                </div>
            `;
        }
    } catch (error) {
        document.getElementById('panel-jumlah').textContent = 'Terjadi kesalahan';
        document.getElementById('panel-list').innerHTML = `
            <div class="panel-empty" style="color:#e53e3e;">
                <div class="icon">⚠️</div>
                <p style="margin:0; font-size:13px;">Gagal terhubung ke server.</p>
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