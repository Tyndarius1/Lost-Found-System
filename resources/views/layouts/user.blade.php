<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <link href="https://fonts.bunny.net/css?family=Plus+Jakarta+Sans:400,500,600,700" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <style>
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            background-color: #f4f7fa; 
            color: #1a1a1a; 
            margin: 0;
        }

        /* Modern Frosted Glass Header */
        .navbar-user {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            position: sticky;
            top: 0;
            z-index: 1000;
            height: 70px;
            display: flex;
            align-items: center;
        }

        .nav-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .logo-link {
            text-decoration: none;
            transition: opacity 0.2s;
        }
        .logo-link:hover { opacity: 0.8; }

        .nav-links { display: flex; gap: 30px; list-style: none; margin: 0; padding: 0; }
        
        .nav-links a {
            text-decoration: none;
            color: #64748b;
            font-weight: 600;
            font-size: 0.95rem;
            transition: color 0.2s;
        }

        .nav-links a:hover, .nav-links a.active { color: #6366f1; }

        /* User Menu */
        .user-dropdown { position: relative; }
        
        .user-pill {
            display: flex;
            align-items: center;
            background: #fff;
            padding: 5px 15px 5px 5px;
            border-radius: 50px;
            border: 1px solid #e2e8f0;
            cursor: pointer;
            transition: all 0.2s;
        }
        .user-pill:hover { border-color: #6366f1; box-shadow: 0 4px 12px rgba(99, 102, 241, 0.1); }

        .dropdown-card {
            position: absolute;
            top: 120%;
            right: 0;
            width: 220px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            border: 1px solid #eef2f6;
            display: none;
            overflow: hidden;
        }
        .dropdown-card.show { display: block; animation: fadeIn 0.2s ease; }

        .dropdown-item-user {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: #4b5563;
            text-decoration: none;
            font-size: 0.9rem;
        }
        .dropdown-item-user:hover { background: #f8fafc; color: #6366f1; }
        .dropdown-item-user.logout { color: #ef4444; }
        .dropdown-item-user.logout:hover { background: #fef2f2; }

        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

        .content-area { max-width: 1200px; margin: 40px auto; padding: 0 20px; }
    </style>
</head>
<body>

    <nav class="navbar-user">
        <div class="nav-container">
            <a href="{{ route('home') }}" class="logo-link">
                <div class="fw-bold text-primary fs-4" style="letter-spacing: -1px;">
                    <i class="bi bi-intersect me-2"></i>UserPanel
                </div>
            </a>

            <ul class="nav-links d-none d-md-flex">
                <li><a href="{{ route('home') }}" class="{{ request()->is('home') ? 'active' : '' }}">Dashboard</a></li>
                <li><a href="{{ route('items.index') }}" class="{{ request()->is('items*') && !request()->is('items/user') ? 'active' : '' }}">Browse Items</a></li>
                
                @auth
                <li><a href="{{ route('items.user') }}" class="{{ request()->is('items/user') ? 'active' : '' }}">My Items</a></li>
                @endauth

                <li><a href="{{ route('owner.claims') }}" class="{{ request()->is('my-claims') ? 'active' : '' }}">My Claims</a></li>
            </ul>

            @auth
            <div class="user-dropdown">
                <div class="user-pill" onclick="toggleUserMenu(event)">
                    @if(Auth::user()->profile_picture)
                        <img src="{{ asset('storage/' . Auth::user()->profile_picture) }}" 
                             class="rounded-circle me-2" 
                             style="width: 32px; height: 32px; object-fit: cover;">
                    @else
                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px; font-weight: 700; font-size: 0.8rem;">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                    @endif
                    <span class="small fw-bold text-dark">{{ Auth::user()->name }}</span>
                    <i class="bi bi-chevron-down ms-2 small text-muted"></i>
                </div>

                <div class="dropdown-card" id="userMenu">
                    <div class="px-3 py-2 border-bottom bg-light d-md-none">
                         <small class="text-muted d-block">Signed in as</small>
                         <span class="fw-bold small">{{ Auth::user()->name }}</span>
                    </div>
                    <a href="{{ route('profile.edit') }}" class="dropdown-item-user">
                        <i class="bi bi-person-circle me-2"></i> My Profile
                    </a>
                    <a href="#" class="dropdown-item-user">
                        <i class="bi bi-gear me-2"></i> Settings
                    </a>
                    <hr class="my-0 opacity-10">
                    <a href="{{ route('logout') }}" class="dropdown-item-user logout" 
                       onclick="event.preventDefault(); confirmLogout();">
                        <i class="bi bi-box-arrow-right me-2"></i> Logout
                    </a>
                </div>
            </div>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
            @else
            <div class="nav-links">
                <a href="{{ route('login') }}">Login</a>
            </div>
            @endauth
        </div>
    </nav>

    <main class="content-area">
        @yield('content')
    </main>

    <script>
        function toggleUserMenu(event) {
            event.stopPropagation();
            document.getElementById('userMenu').classList.toggle('show');
        }

        window.onclick = function() {
            const menu = document.getElementById('userMenu');
            if (menu && menu.classList.contains('show')) {
                menu.classList.remove('show');
            }
        }

        // Professional Logout Alert
        function confirmLogout() {
            Swal.fire({
                title: 'Ready to leave?',
                text: "You will need to login again to access your items.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#6366f1',
                cancelButtonColor: '#f1f5f9',
                confirmButtonText: 'Logout Now',
                cancelButtonText: 'Stay logged in',
                reverseButtons: true,
                customClass: {
                    popup: 'rounded-4 shadow border-0',
                    confirmButton: 'btn btn-primary px-4 py-2 rounded-pill fw-bold ms-2',
                    cancelButton: 'btn btn-light px-4 py-2 rounded-pill fw-bold text-dark'
                },
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('logout-form').submit();
                }
            })
        }
    </script>
</body>
</html>