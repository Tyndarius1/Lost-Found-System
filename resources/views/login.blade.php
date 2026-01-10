<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Laravel') }} | Auth</title>
    <link href="https://fonts.bunny.net/css?family=Plus+Jakarta+Sans:400,600,700" rel="stylesheet">
    <style>
        :root {
            --primary: #4f46e5;
            --secondary: #6366f1;
            --text-dark: #111827;
            --text-muted: #6b7280;
            --transition: all 0.5s cubic-bezier(0.645, 0.045, 0.355, 1);
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #f3f4f6;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        /* SHRUNK CONTAINER: From 850px to 700px width, 550px to 420px height */
        .container {
            background-color: #fff;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            position: relative;
            overflow: hidden;
            width: 700px; 
            max-width: 95%;
            min-height: 420px;
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
            animation: show 0.5s;
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
            padding: 0 30px; /* Reduced padding */
            height: 100%;
            text-align: center;
        }

        /* SCALED DOWN TEXT */
        h1 { font-weight: 700; margin-bottom: 8px; font-size: 1.4rem; color: var(--text-dark); }
        p { font-size: 13px; color: var(--text-muted); margin-bottom: 15px; line-height: 1.4; }
        .overlay p { color: rgba(255,255,255,0.9); } /* Better visibility on gradient */

        /* SMALLER INPUTS */
        input {
            background-color: #f9fafb;
            border: 1px solid #e5e7eb;
            padding: 10px 12px;
            margin: 5px 0;
            width: 100%;
            border-radius: 10px;
            font-size: 13px;
            outline: none;
            transition: 0.2s;
        }

        input:focus { border-color: var(--primary); background: #fff; box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1); }

        /* COMPACT BUTTON */
        button {
            border-radius: 10px;
            border: 1px solid var(--primary);
            background-color: var(--primary);
            color: #ffffff;
            font-size: 11px;
            font-weight: 700;
            padding: 10px 30px;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            transition: transform 80ms ease-in, background 0.2s;
            cursor: pointer;
            margin-top: 10px;
        }

        button:active { transform: scale(0.96); }
        button.ghost { background-color: transparent; border-color: #ffffff; margin-top: 5px; }

        /* OVERLAY SECTION - High Contrast */
        .overlay-container {
            position: absolute;
            top: 0;
            left: 50%;
            width: 50%;
            height: 100%;
            overflow: hidden;
            transition: var(--transition);
            z-index: 100;
        }

        .container.right-panel-active .overlay-container { transform: translateX(-100%); }

        .overlay {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            color: #ffffff;
            position: relative;
            left: -100%;
            height: 100%;
            width: 200%;
            transform: translateX(0);
            transition: var(--transition);
        }

        .container.right-panel-active .overlay { transform: translateX(50%); }

        .overlay-panel {
            position: absolute;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            padding: 0 25px;
            text-align: center;
            top: 0;
            height: 100%;
            width: 50%;
            transition: var(--transition);
        }

        .overlay-left { transform: translateX(-20%); }
        .container.right-panel-active .overlay-left { transform: translateX(0); }

        .overlay-right { right: 0; transform: translateX(0); }
        .container.right-panel-active .overlay-right { transform: translateX(20%); }

        .overlay h1 { color: #fff; }

        @media (max-width: 768px) {
            .container { width: 90%; min-height: 500px; }
            .overlay-container { display: none; }
            .sign-in-container, .sign-up-container { width: 100%; }
            .container.right-panel-active .sign-in-container { display: none; }
            .container.right-panel-active .sign-up-container { width: 100%; transform: none; opacity: 1; position: relative; }
        }
    </style>
</head>
<body>

<div class="container" id="container">
    <div class="form-container sign-up-container">
        <form action="{{ route('register') }}" method="POST">
            @csrf
            <h1>Join Us</h1>
            <p>Create your free account</p>
            <input type="text" name="name" placeholder="Name" required />
            <input type="email" name="email" placeholder="Email" required />
            <input type="password" name="password" placeholder="Password" required />
            <input type="password" name="password_confirmation" placeholder="Confirm" required />
            <button type="submit">Sign Up</button>
        </form>
    </div>

    <div class="form-container sign-in-container">
        <form action="{{ route('login') }}" method="POST">
            @csrf
            <h1>Sign in</h1>
            <p>Enter your details</p>
            <input type="email" name="email" placeholder="Email" required />
            <input type="password" name="password" placeholder="Password" required />
            <a href="#" style="font-size:11px; color:#94a3b8; margin-top:8px; text-decoration:none;">Forgot password?</a>
            <button type="submit">Sign In</button>
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
    document.getElementById('signUp').onclick = () => container.classList.add("right-panel-active");
    document.getElementById('signIn').onclick = () => container.classList.remove("right-panel-active");
</script>

</body>
</html>