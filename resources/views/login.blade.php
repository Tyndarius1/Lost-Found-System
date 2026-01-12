<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lost & Found | Auth</title>
    <link href="https://fonts.bunny.net/css?family=Plus+Jakarta+Sans:400,600,700" rel="stylesheet">
    <style>
        :root {
            --primary: #4f46e5;
            --gradient: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            --danger: #ef4444;
            --transition: all 0.6s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #f3f4f6;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            padding: 10px;
        }

        .container {
            background-color: #fff;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            position: relative;
            overflow: hidden;
            width: 768px;
            max-width: 100%;
            min-height: 520px; /* Extra height for validation messages */
        }

        .form-container {
            position: absolute;
            top: 0;
            height: 100%;
            transition: var(--transition);
        }

        .sign-in-container { left: 0; width: 50%; z-index: 2; }
        .container.right-panel-active .sign-in-container { transform: translateX(100%); }

        .sign-up-container { left: 0; width: 50%; opacity: 0; z-index: 1; }
        .container.right-panel-active .sign-up-container {
            transform: translateX(100%);
            opacity: 1;
            z-index: 5;
            animation: show 0.6s;
        }

        @keyframes show {
            0%, 49.99% { opacity: 0; z-index: 1; }
            50%, 100% { opacity: 1; z-index: 5; }
        }

        form {
            background-color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            padding: 0 35px;
            height: 100%;
            text-align: center;
        }

        h1 { font-weight: 700; margin-bottom: 5px; font-size: 1.5rem; color: #111827; }
        p { font-size: 13px; color: #6b7280; margin-bottom: 15px; }

        input {
            background-color: #f9fafb;
            border: 1px solid #e5e7eb;
            padding: 10px 15px;
            margin: 5px 0;
            width: 100%;
            border-radius: 12px;
            font-size: 13px;
            outline: none;
            transition: 0.2s;
        }

        /* Validation Error Styles */
        input.is-invalid { border-color: var(--danger); background-color: #fef2f2; }
        .error-msg { color: var(--danger); font-size: 10px; align-self: flex-start; margin-left: 5px; margin-bottom: 2px; font-weight: 600; }

        input:focus { border-color: var(--primary); background: #fff; box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1); }

        button {
            border-radius: 12px;
            border: none;
            background: var(--primary);
            color: #ffffff;
            font-size: 11px;
            font-weight: 700;
            padding: 12px 40px;
            letter-spacing: 1px;
            text-transform: uppercase;
            transition: transform 80ms ease-in, opacity 0.2s;
            cursor: pointer;
            margin-top: 10px;
        }

        button.ghost { background-color: transparent; border: 2px solid #ffffff; }

        /* Overlay */
        .overlay-container {
            position: absolute;
            top: 0; left: 50%; width: 50%; height: 100%;
            overflow: hidden; transition: var(--transition); z-index: 100;
        }
        .container.right-panel-active .overlay-container { transform: translateX(-100%); }
        .overlay {
            background: var(--gradient); color: #ffffff; position: relative;
            left: -100%; height: 100%; width: 200%; transform: translateX(0); transition: var(--transition);
        }
        .container.right-panel-active .overlay { transform: translateX(50%); }

        .overlay-panel {
            position: absolute; display: flex; align-items: center; justify-content: center;
            flex-direction: column; padding: 0 40px; text-align: center; top: 0; height: 100%; width: 50%; transition: var(--transition);
        }
        .overlay-left { transform: translateX(-20%); }
        .container.right-panel-active .overlay-left { transform: translateX(0); }
        .overlay-right { right: 0; transform: translateX(0); }
        .container.right-panel-active .overlay-right { transform: translateX(20%); }

        /* Mobile Adjustments */
        @media (max-width: 600px) {
            .overlay-container { display: none; }
            .form-container { width: 100% !important; }
            .sign-in-container, .sign-up-container { transform: none !important; left: 0; width: 100%; }
            .container.right-panel-active .sign-in-container { display: none; }
            .mobile-toggle { display: block !important; margin-top: 15px; color: var(--primary); font-size: 12px; text-decoration: underline; cursor: pointer; }
        }
        .mobile-toggle { display: none; }
    </style>
</head>
<body>

<div class="container" id="container">
    <div class="form-container sign-up-container">
        <form action="{{ route('register') }}" method="POST">
            @csrf
            <h1>Join Us</h1>
            <p>Create your free account</p>

            @error('name') <span class="error-msg">{{ $message }}</span> @enderror
            <input type="text" name="name" placeholder="Name" value="{{ old('name') }}" class="@error('name') is-invalid @enderror" required />

            @error('email') <span class="error-msg">{{ $message }}</span> @enderror
            <input type="email" name="email" placeholder="Email" value="{{ old('email') }}" class="@error('email') is-invalid @enderror" required />

            @error('password') <span class="error-msg">{{ $message }}</span> @enderror
            <input type="password" name="password" placeholder="Password" class="@error('password') is-invalid @enderror" required />
            <input type="password" name="password_confirmation" placeholder="Confirm Password" required />

            <button type="submit">Sign Up</button>
            <span class="mobile-toggle" onclick="container.classList.remove('right-panel-active')">Already have an account? Sign In</span>
        </form>
    </div>

    <div class="form-container sign-in-container">
        <form action="{{ route('login') }}" method="POST">
            @csrf
            <h1>Sign in</h1>
            <p>Enter your details</p>

            @if (session('status'))
                <span class="error-msg" style="align-self:center; margin-bottom:10px;">{{ session('status') }}</span>
            @endif

            @error('email') <span class="error-msg">{{ $message }}</span> @enderror
            <input type="email" name="email" placeholder="Email" value="{{ old('email') }}" class="@error('email') is-invalid @enderror" required />

            @error('password') <span class="error-msg">{{ $message }}</span> @enderror
            <input type="password" name="password" placeholder="Password" class="@error('password') is-invalid @enderror" required />

            <a href="#" style="font-size: 11px; color: #94a3b8; text-decoration: none; margin-top: 5px;">Forgot password?</a>
            <button type="submit">Sign In</button>
            <span class="mobile-toggle" onclick="container.classList.add('right-panel-active')">New here? Sign Up</span>
        </form>
    </div>

    <div class="overlay-container">
        <div class="overlay">
            <div class="overlay-panel overlay-left">
                <h1>Welcome Back!</h1>
                <p>Log in to continue your progress</p>
                <button class="ghost" id="signIn">Sign In</button>
            </div>
            <div class="overlay-panel overlay-right">
                <h1>New Here?</h1>
                <p>Sign up to start your journey with us</p>
                <button class="ghost" id="signUp">Sign Up</button>
            </div>
        </div>
    </div>
</div>

<script>
    const container = document.getElementById('container');
    const signUpBtn = document.getElementById('signUp');
    const signInBtn = document.getElementById('signIn');

    signUpBtn.addEventListener('click', () => container.classList.add("right-panel-active"));
    signInBtn.addEventListener('click', () => container.classList.remove("right-panel-active"));

    // FIXED: If there are validation errors on Sign Up, keep the panel on the right side
    @if($errors->has('name') || $errors->has('password') || (isset($errors) && $errors->has('email') && old('name')))
        container.classList.add("right-panel-active");
    @endif
</script>

</body>
</html>