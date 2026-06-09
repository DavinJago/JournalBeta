<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SalesSkip Login</title>
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
</head>

<body>

    <div class="container">
        <div class="left-panel">
            <div class="left-content">
                <div class="logo-icon">✳</div>
                <h1>Hello<br>Journalist!👋</h1>
                <p>Solusi Mencari dan Meringkas Jurnal andalanmu</p>
            </div>
            <div class="copyright">© 2026 RPL. All rights reserved.</div>
        </div>

        <div class="right-panel">
            <div class="form-container">
                <div class="logo-text">SalesSkip</div>

                <h2>Welcome Back!</h2>
                <p class="subtitle">Don't have an account? <a href="#">Create a new account now</a>, it's FREE! Takes less than a minute.</p>

                <form id="loginForm">
                    <div class="input-group">
                        <input type="email" id="email" value="hisalim.ux@gmail.com" placeholder="Email Address" required>
                    </div>
                    <div class="input-group">
                        <input type="password" id="password" placeholder="Password" required>
                    </div>

                    <button type="submit" class="btn-primary">Login Now</button>

                </form>

                <p class="forgot-password">Forget password <a href="#">Click here</a></p>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('loginForm').addEventListener('submit', function(event) {
            // Mencegah halaman reload saat form di-submit
            event.preventDefault();

            // Mengambil nilai input
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;

            // Validasi sederhana & Feedback
            if (email && password) {
                alert(`Login berhasil dicoba dengan email: ${email}`);
                // Di sini Anda bisa menambahkan logika fetch/AJAX ke server
            } else {
                alert('Silakan masukkan email dan password.');
            }
        });
    </script>
</body>

</html>