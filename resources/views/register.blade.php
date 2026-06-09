<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SalesSkip Register</title>
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

                <h2>Create Account</h2>
                <p class="subtitle">Already have an account? <a href="/login">Login here</a></p>

                <form id="registerForm">
                    <div class="input-group">
                        <input type="text" id="name" placeholder="Full Name" required>
                    </div>

                    <div class="input-group">
                        <input type="email" id="email" placeholder="Email Address" required>
                    </div>

                    <div class="input-group">
                        <input type="password" id="password" placeholder="Create Password" required>
                    </div>

                    <div class="input-group">
                        <input type="password" id="confirm_password" placeholder="Confirm Password" required>
                    </div>

                    <button type="submit" class="btn-primary">Register Now</button>
                </form>

            </div>
        </div>
    </div>

    <script>
        document.getElementById('registerForm').addEventListener('submit', function(event) {
            event.preventDefault();

            const name = document.getElementById('name').value;
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;

            // Validasi tambahan: ngecek password cocok atau tidak
            if (password !== confirmPassword) {
                alert('Waduh! Password dan Konfirmasi Password tidak cocok.');
                return;
            }

            if (name && email && password) {
                alert(`Register berhasil dicoba untuk: ${name} (${email})`);
            } else {
                alert('Silakan lengkapi semua data.');
            }
        });
    </script>
</body>

</html>