<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ config('app.name', 'Laravel') }} | Auth</title>

<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=Nunito:400,600,700" rel="stylesheet">

<style>
    body {
        margin: 0;
        font-family: 'Nunito', sans-serif;
        background: #f5f5f7;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        overflow: hidden;
    }

    .auth-wrapper {
        width: 800px;
        max-width: 90%;
        height: 500px;
        position: relative;
        perspective: 1500px;
    }

    .auth-container {
        width: 100%;
        height: 100%;
        display: flex;
        transition: transform 0.8s cubic-bezier(0.77,0,0.175,1);
    }

    .auth-card {
        width: 100%;
        flex-shrink: 0;
        background: #fff;
        border-radius: 16px;
        padding: 3rem;
        box-shadow: 0 20px 40px rgba(0,0,0,0.08);
        display: flex;
        flex-direction: column;
        justify-content: center;
        transition: transform 0.8s ease, opacity 0.8s ease;
    }

    h2 {
        text-align: center;
        font-size: 1.75rem;
        font-weight: 700;
        margin-bottom: 2rem;
        color: #1d1d1f;
    }

    .form-control {
        width: 100%;
        padding: 0.75rem 1rem;
        margin-bottom: 1.25rem;
        border-radius: 12px;
        border: 1px solid #d1d1d6;
        font-size: 1rem;
    }

    .btn-primary {
        padding: 0.75rem;
        border-radius: 12px;
        font-weight: 600;
        font-size: 1rem;
        border: none;
        background-color: #0071e3;
        color: #fff;
        cursor: pointer;
        transition: background 0.2s ease;
    }

    .btn-primary:hover { background-color: #005bb5; }

    .toggle-link {
        display: block;
        margin-top: 1rem;
        text-align: center;
        color: #0071e3;
        cursor: pointer;
        font-weight: 600;
        transition: color 0.2s ease;
    }
    .toggle-link:hover { text-decoration: underline; }

    /* Slide and scale effect */
    .register-active .auth-container {
        transform: translateX(-100%);
    }

    .auth-container > .auth-card {
        transform: scale(1);
    }
    .register-active .auth-container > .register-card {
        transform: scale(1);
    }

    .register-card {
        position: absolute;
        top: 0;
        left: 100%;
        width: 100%;
    }

    @media (max-width: 768px) {
        .auth-wrapper {
            width: 100%;
            height: auto;
        }
        .auth-container {
            flex-direction: column;
            transform: translateX(0) !important;
        }
        .auth-card, .register-card {
            width: 100%;
            position: relative;
            transform: scale(1) !important;
            margin-bottom: 2rem;
        }
    }
</style>
</head>
<body>

<div class="auth-wrapper" id="authWrapper">
    <div class="auth-container">

        <!-- LOGIN CARD -->
        <div class="auth-card login-card">
            <h2>Login</h2>
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <input type="email" name="email" placeholder="Email" class="form-control" required autofocus>
                <input type="password" name="password" placeholder="Password" class="form-control" required>
                <button type="submit" class="btn-primary">Login</button>
            </form>
            <span class="toggle-link" onclick="toggleAuth()">Create Account</span>
        </div>

        <!-- REGISTER CARD -->
        <div class="auth-card register-card">
            <h2>Register</h2>
            <form method="POST" action="{{ route('register') }}">
                @csrf
                <input type="text" name="name" placeholder="Full Name" class="form-control" required>
                <input type="email" name="email" placeholder="Email" class="form-control" required>
                <input type="password" name="password" placeholder="Password" class="form-control" required>
                <input type="password" name="password_confirmation" placeholder="Confirm Password" class="form-control" required>
                <button type="submit" class="btn-primary">Register</button>
            </form>
            <span class="toggle-link" onclick="toggleAuth()">Already have an account?</span>
        </div>

    </div>
</div>

<script>
    const authWrapper = document.getElementById('authWrapper');
    function toggleAuth() {
        authWrapper.classList.toggle('register-active');
    }
</script>

</body>
</html>
