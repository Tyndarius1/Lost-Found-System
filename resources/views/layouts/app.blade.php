<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <link href="https://fonts.bunny.net/css?family=Plus+Jakarta+Sans:400,500,600,700" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <style>
        :root { 
            --sidebar-width: 260px; 
            --primary-color: #6366f1; 
            --bg-body: #fafafa;
        }

        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            background-color: var(--bg-body); 
            color: #1a1a1a; 
            margin: 0;
        }
        
        /* Layout Structure */
        .wrapper { display: flex; min-height: 100vh; }
        
        .sidebar {
            width: var(--sidebar-width);
            background: #fff;
            border-right: 1px solid #e5e7eb;
            position: fixed;
            height: 100vh;
            padding: 1.5rem;
            display: flex;
            flex-direction: column; /* Allows mt-auto to work */
            z-index: 1000;
        }

        .main-content {
            margin-left: var(--sidebar-width);
            flex: 1;
            padding: 2rem 3rem;
        }

        /* Navigation Links */
        .nav-link {
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            color: #4b5563;
            text-decoration: none;
            border-radius: 10px;
            margin-bottom: 0.25rem;
            font-weight: 500;
            transition: all 0.2s;
        }
        .nav-link:hover { background: #f3f4f6; color: var(--primary-color); }
        .nav-link.active { background: #eef2ff; color: var(--primary-color); }
        .nav-link i { margin-right: 12px; font-size: 1.1rem; }

        /* User Profile Section at Bottom */
        .user-profile-container {
            margin-top: auto; /* Pushes this section to the bottom */
            padding-top: 1.5rem;
            border-top: 1px solid #e5e7eb;
            position: relative;
        }

        .user-trigger {
            display: flex;
            align-items: center;
            cursor: pointer;
            padding: 10px;
            border-radius: 12px;
            transition: background 0.2s;
            width: 100%;
            border: none;
            background: transparent;
            text-align: left;
        }
        .user-trigger:hover { background: #f3f4f6; }

        /* Popup Logout Menu */
        .logout-popup {
            position: absolute;
            bottom: calc(100% + 12px); /* Floats above the profile */
            left: 0;
            right: 0;
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 14px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.08);
            display: none; /* Hidden by default */
            overflow: hidden;
            z-index: 1100;
        }
        .logout-popup.show { 
            display: block; 
            animation: popUp 0.2s ease-out;
        }

        .logout-item {
            display: flex;
            align-items: center;
            padding: 12px 16px;
            color: #dc2626;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9rem;
            transition: background 0.2s;
        }
        .logout-item:hover { background: #fff1f2; }

        @keyframes popUp {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); transition: 0.3s; }
            .main-content { margin-left: 0; padding: 1.5rem; }
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <nav class="sidebar">
    <div class="mb-5 px-2">
        <h4 class="fw-bold text-primary mb-0">
            <i class="bi bi-rocket-takeoff-fill me-2"></i>YourLogo
        </h4>
    </div>

    @auth
        <a href="/home" class="nav-link {{ request()->is('home') ? 'active' : '' }}">
            <i class="bi bi-grid-1x2-fill"></i> Overview
        </a>
        <a href="#" class="nav-link">
            <i class="bi bi-folder2-open"></i> Projects
        </a>
        <a href="#" class="nav-link">
            <i class="bi bi-chat-dots"></i> Messages
        </a>
        <a href="#" class="nav-link">
            <i class="bi bi-gear"></i> Settings
        </a>

        <div class="user-profile-container">
            <div class="logout-popup" id="logoutPopup">
                <div class="px-3 py-2 border-bottom bg-light">
                    <span class="text-muted fw-bold" style="font-size: 0.7rem; text-uppercase; letter-spacing: 0.5px;">Action</span>
                </div>
                <a href="{{ route('logout') }}" 
                   class="logout-item"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="bi bi-box-arrow-right me-2"></i> Logout
                </a>
            </div>

            <button class="user-trigger" onclick="togglePopup(event)">
                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 38px; height: 38px; font-weight: 700; flex-shrink: 0;">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
                <div class="small overflow-hidden flex-grow-1">
                    <div class="fw-bold text-dark text-truncate">{{ Auth::user()->name }}</div>
                    <div class="text-muted" style="font-size: 0.75rem;">{{ ucfirst(Auth::user()->role) }}</div>
                </div>
                <i class="bi bi-chevron-expand text-muted ms-2"></i>
            </button>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </div>
    @else
        <a href="{{ route('login') }}" class="nav-link {{ request()->is('login') ? 'active' : '' }}">
            <i class="bi bi-box-arrow-in-right"></i> Login
        </a>
        <a href="{{ route('register') }}" class="nav-link {{ request()->is('register') ? 'active' : '' }}">
            <i class="bi bi-person-plus"></i> Register
        </a>
    @endauth
</nav>

        <main class="main-content">
            @yield('content')
        </main>
    </div>

    <script>
        function togglePopup(event) {
            event.stopPropagation();
            const popup = document.getElementById('logoutPopup');
            popup.classList.toggle('show');
        }

        // Close popup if clicking anywhere else
        window.onclick = function(event) {
            const popup = document.getElementById('logoutPopup');
            if (!event.target.closest('.user-profile-container')) {
                if (popup.classList.contains('show')) {
                    popup.classList.remove('show');
                }
            }
        }
    </script>
</body>
</html>