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
    <meta name="csrf-token" content="{{ csrf_token() }}">
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

        <div class="search-box">
            <div class="search-input">
                <i class="fa-regular fa-folder-open"
                   onclick="document.getElementById('fileJurnalInput').click()"
                   style="cursor:pointer;"
                   title="Upload jurnal (PDF/TXT)"></i>

                <form id="formUploadJurnal">
                    <input type="file" id="fileJurnalInput" name="file_jurnal" accept=".pdf,.txt"
                        style="display:none;"
                        onchange="prosesUploadKeBackend()">
                </form>

                <input type="text" id="inputKeyword"
                    placeholder="Masukkan topik penelitian, kata kunci, atau judul jurnal..."
                    onkeydown="if(event.key==='Enter') prosesCariJurnal()">
            </div>
            <button class="search-btn" onclick="prosesCariJurnal()">Cari</button>
        </div>

    </section>

    <!-- Features -->
    <section class="features">

        <div class="card">
            <div class="icon blue">
                <i class="fa-solid fa-book"></i>
            </div>
            <h3>Cari Jurnal</h3>
            <p>Temukan jurnal, skripsi dan paper dari berbagai sumber terpercaya.</p>
        </div>

        <div class="card">
            <div class="icon green">
                <i class="fa-solid fa-robot"></i>
            </div>
            <h3>Ringkasan AI</h3>
            <p>Dapatkan ringkasan otomatis dari jurnal yang relevan.</p>
        </div>

        <div class="card">
            <div class="icon orange">
                <i class="fa-solid fa-bookmark"></i>
            </div>
            <h3>Analisis Celah</h3>
            <p>Analisis celah untuk dijadikan ide penelitian baru</p>
        </div>

    </section>

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

<style>
    /* ── Layout Split ──────────────────────────────── */
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

    body.panel-open {
        padding-right: 50vw;
        transition: padding-right 0.35s cubic-bezier(.4,0,.2,1);
    }

    /* ── Panel Header ──────────────────────────────── */
    .panel-header {
        padding: 18px 20px 14px;
        border-bottom: 1px solid #f0f0f0;
        background: #fafafa;
        display: flex;
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
        background: none;
        border: none;
        cursor: pointer;
        font-size: 16px;
        color: #888;
        padding: 4px 8px;
        border-radius: 6px;
        line-height: 1;
        flex-shrink: 0;
        margin-left: 8px;
        transition: background .2s;
    }

    .panel-close-btn:hover { background: #ede9fe; }

    /* ── Panel Body ────────────────────────────────── */
    .panel-body {
        flex: 1;
        overflow-y: auto;
        padding: 16px;
    }

    /* ── Jurnal Cards ──────────────────────────────── */
    .jurnal-card {
        margin-bottom: 12px;
        padding: 14px;
        background: #f8f7ff;
        border: 1px solid #ede9fe;
        border-left: 4px solid #7c3aed;
        border-radius: 10px;
        transition: box-shadow .2s;
    }

    .jurnal-card:hover { box-shadow: 0 2px 12px rgba(124,58,237,0.12); }
    
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
        font-size: 18px;
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

    .jurnal-card-btn:hover { background: #6d28d9; }

    /* ── Empty / Loading States ────────────────────── */
    .panel-empty {
        text-align: center;
        padding: 50px 20px;
        color: #999;
    }

    .panel-empty .icon { font-size: 40px; margin-bottom: 12px; }

    /* Spinner animasi */
    .spinner {
        display: inline-block;
        width: 36px;
        height: 36px;
        border: 4px solid #ede9fe;
        border-top-color: #7c3aed;
        border-radius: 50%;
        animation: spin 0.8s linear infinite;
        margin-bottom: 14px;
    }

    @keyframes spin { to { transform: rotate(360deg); } }

    /* Loading steps */
    .loading-steps {
        list-style: none;
        padding: 0;
        margin: 16px 0 0;
        text-align: left;
        display: inline-block;
    }

    .loading-steps li {
        font-size: 12px;
        color: #9ca3af;
        padding: 3px 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .loading-steps li.active {
        color: #7c3aed;
        font-weight: 600;
    }

    .loading-steps li.done { color: #10b981; }

    /* ── Responsive ────────────────────────────────── */
    @media (max-width: 768px) {
        .hasil-panel.open { width: 100vw; }
        body.panel-open { padding-right: 0; }
    }
</style>

<script>
    // ── Panel helpers ───────────────────────────────────────────────────────
    function bukaPanel() {
        document.getElementById('hasil-panel').classList.add('open');
        document.body.classList.add('panel-open');
    }

    function tutupPanel() {
        document.getElementById('hasil-panel').classList.remove('open');
        document.body.classList.remove('panel-open');
    }

    // ── Render loading dengan step indicator ───────────────────────────────
    function renderLoading(namaFile, stepAktif = 0) {
        const steps = [
            'Mengunggah file ke server...',
            'Gemini memproses dokumen...',
            'AI membedah isi jurnal...',
        ];

        const stepsHtml = steps.map((s, i) => {
            let cls = '';
            if (i < stepAktif) cls = 'done';
            else if (i === stepAktif) cls = 'active';
            const icon = i < stepAktif ? '✅' : (i === stepAktif ? '⏳' : '○');
            return `<li class="${cls}">${icon} ${s}</li>`;
        }).join('');

        document.getElementById('panel-list').innerHTML = `
            <div class="panel-empty">
                <div class="spinner"></div>
                <p style="margin:0 0 4px; font-weight:600; color:#1e1e2e;">${namaFile}</p>
                <p style="margin:0; font-size:12px; color:#888;">Proses ini memakan 15–45 detik.</p>
                <ul class="loading-steps">${stepsHtml}</ul>
            </div>
        `;
    }

    // ── Upload & Analisis ───────────────────────────────────────────────────
    async function prosesUploadKeBackend() {
        const fileInput = document.getElementById('fileJurnalInput');
        const file      = fileInput.files[0];
        if (!file) return;

        // Cek ukuran file di sisi klien (max 10MB)
        if (file.size > 10 * 1024 * 1024) {
            alert('File terlalu besar. Maksimum 10MB.');
            fileInput.value = '';
            return;
        }

        // Tampilkan panel loading — step 0: upload
        document.getElementById('panel-keyword-label').textContent = file.name;
        document.getElementById('panel-jumlah').textContent = 'Mengunggah & Menganalisis...';
        renderLoading(file.name, 0);
        bukaPanel();

        const dataForm = new FormData();
        dataForm.append('file_jurnal', file);

        // Simulasi step 1 setelah 2 detik (Gemini memproses)
        const stepTimer = setTimeout(() => renderLoading(file.name, 1), 2000);
        // Simulasi step 2 setelah 8 detik (AI membedah)
        const stepTimer2 = setTimeout(() => renderLoading(file.name, 2), 8000);

        try {
            const response = await fetch('/api/upload-jurnal', {
                method: 'POST',
                body: dataForm,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                }
            });

            clearTimeout(stepTimer);
            clearTimeout(stepTimer2);

            const hasil = await response.json();

            // ── Sukses ────────────────────────────────────────────────────
            if (hasil.status === 'Sukses Simpan dan Analisis') {
                document.getElementById('panel-jumlah').textContent = 'Analisis Berhasil ✅';
                const ai = hasil.hasil_rangkuman_gemini;

                document.getElementById('panel-list').innerHTML = `
                    <div class="jurnal-card" style="border-left-color:#4a5568; background:#f7fafc;">
                        <div class="jurnal-card-label" style="color:#4a5568;">📄 Identitas Jurnal</div>
                        <p class="jurnal-card-title">${escapeHtml(ai.judul_dan_penulis || file.name)}</p>
                    </div>

                    <div class="jurnal-card">
                        <div class="jurnal-card-label">📍 Bab 1: Latar Belakang</div>
                        <p class="jurnal-card-abstract" style="-webkit-line-clamp:unset;">${escapeHtml(ai.latar_belakang_bab1)}</p>
                    </div>

                    <div class="jurnal-card">
                        <div class="jurnal-card-label">📚 Landasan Teori</div>
                        <p class="jurnal-card-abstract" style="-webkit-line-clamp:unset;">${escapeHtml(ai.landasan_teori)}</p>
                    </div>

                    <div class="jurnal-card">
                        <div class="jurnal-card-label">⚙️ Metodologi Penelitian</div>
                        <p class="jurnal-card-abstract" style="-webkit-line-clamp:unset;">${escapeHtml(ai.metodologi_penelitian)}</p>
                    </div>

                    <div class="jurnal-card">
                        <div class="jurnal-card-label">📊 Hasil dan Pembahasan</div>
                        <p class="jurnal-card-abstract" style="-webkit-line-clamp:unset;">${escapeHtml(ai.hasil_dan_pembahasan)}</p>
                    </div>

                    <div class="jurnal-card">
                        <div class="jurnal-card-label">🏁 Kesimpulan dan Saran</div>
                        <p class="jurnal-card-abstract" style="-webkit-line-clamp:unset;">${escapeHtml(ai.kesimpulan_dan_saran)}</p>
                    </div>

                    <div class="jurnal-card" style="border-left-color:#319795; background:#e6fffa;">
                        <div class="jurnal-card-label" style="color:#319795;">💡 Rekomendasi Ide Penelitian Baru</div>
                        <p class="jurnal-card-abstract" style="-webkit-line-clamp:unset; font-weight:500; color:#2d3748;">
                            ${escapeHtml(ai.ide_penelitian_baru || 'Gemini tidak merumuskan ide baru untuk jurnal ini.')}
                        </p>
                    </div>
                `;

            // ── Error dari backend ─────────────────────────────────────────
            } else {
                const pesanError = hasil.error || 'AI gagal menganalisis jurnal.';
                document.getElementById('panel-jumlah').textContent = 'Analisis Gagal';
                document.getElementById('panel-list').innerHTML = `
                    <div class="panel-empty" style="color:#e53e3e;">
                        <div class="icon">❌</div>
                        <p style="margin:0 0 6px; font-weight:600;">Gagal menganalisis</p>
                        <p style="margin:0; font-size:12px; color:#718096;">${escapeHtml(pesanError)}</p>
                        <p style="margin:8px 0 0; font-size:12px;">Pastikan file PDF bisa dibaca teks (bukan hasil scan).</p>
                    </div>
                `;
            }

        } catch (error) {
            clearTimeout(stepTimer);
            clearTimeout(stepTimer2);
            console.error('Upload error:', error);
            document.getElementById('panel-jumlah').textContent = 'Terjadi Kesalahan';
            document.getElementById('panel-list').innerHTML = `
                <div class="panel-empty" style="color:#e53e3e;">
                    <div class="icon">⚠️</div>
                    <p style="margin:0 0 6px; font-weight:600;">Koneksi Gagal</p>
                    <p style="margin:0; font-size:12px; color:#718096;">Gagal menghubungi server. Periksa koneksi internet dan coba lagi.</p>
                </div>
            `;
        }

        fileInput.value = '';
    }

    // ── Cari Jurnal via Keyword ─────────────────────────────────────────────
    async function prosesCariJurnal() {
        const keyword = document.getElementById('inputKeyword').value.trim();
        if (!keyword) return;

        document.getElementById('panel-keyword-label').textContent = keyword;
        document.getElementById('panel-jumlah').textContent = 'Mencari jurnal...';
        document.getElementById('panel-list').innerHTML = `
            <div class="panel-empty">
                <div class="spinner"></div>
                <p style="margin:0; font-size:13px; color:#555;">Mengambil rekomendasi jurnal...</p>
            </div>
        `;
        bukaPanel();

        try {
            const response = await fetch(`/api/cari-jurnal?keyword=${encodeURIComponent(keyword)}`);
            const hasil    = await response.json();

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
                            <p class="jurnal-card-title">${escapeHtml(jurnal.title)}</p>
                            <p class="jurnal-card-abstract">
                                ${jurnal.abstract ? escapeHtml(jurnal.abstract.substring(0, 120)) + '...' : 'Tidak ada abstrak.'}
                            </p>
                            <a href="${escapeHtml(jurnal.url)}" target="_blank" rel="noopener" class="jurnal-card-btn">
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
            document.getElementById('panel-jumlah').textContent = 'Terjadi Kesalahan';
            document.getElementById('panel-list').innerHTML = `
                <div class="panel-empty" style="color:#e53e3e;">
                    <div class="icon">⚠️</div>
                    <p style="margin:0; font-size:13px;">Gagal terhubung ke server.</p>
                </div>
            `;
        }
    }

    // ── Utility: escape HTML untuk cegah XSS ───────────────────────────────
    function escapeHtml(str) {
        if (!str) return '';
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');
    }
</script>

</body>
</html>