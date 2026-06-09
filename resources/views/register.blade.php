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
                <p class="subtitle">Already have an account? <a href="{{ route('login') }}">Login here</a></p>

                @if ($errors->any())
                    <div class="subtitle" style="color: #dc2626;">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form id="registerForm" method="POST" action="{{ route('register.process') }}">
                    @csrf
                    <div class="input-group">
                        <input type="text" id="name" name="name" value="{{ old('name') }}" placeholder="Full Name" required>
                    </div>

                    <div class="input-group">
                        <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="Email Address" required>
                    </div>

                    <div class="input-group">
                        <input type="password" id="password" name="password" placeholder="Create Password" required>
                    </div>

                    <div class="input-group">
                        <input type="password" id="confirm_password" name="password_confirmation" placeholder="Confirm Password" required>
                    </div>

                    <button type="submit" class="btn-primary">Register Now</button>
                </form>

            </div>
        </div>
    </div>
</body>

</html>