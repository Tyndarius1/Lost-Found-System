<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Lost & Found</title>

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

        .logo-link { text-decoration: none; transition: opacity 0.2s; }
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

        /* CIRCULAR Notification Bell */
        .notification-btn {
            width: 42px !important;
            height: 42px !important;
            display: flex !important;
            align-items: center;
            justify-content: center;
            padding: 0 !important;
            border-radius: 50% !important;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            color: #64748b;
            transition: all 0.2s;
        }
        .notification-btn:hover { background: #fff; color: #6366f1; border-color: #6366f1; }

        .notif-card {
            width: 320px;
            max-height: 400px;
            overflow-y: auto;
            border-radius: 16px !important;
        }

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

        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        .content-area { max-width: 1200px; margin: 40px auto; padding: 0 20px; }
    </style>
</head>
<body>
    

    <nav class="navbar-user">
        <div class="nav-container">
            <a href="{{ route('home') }}" class="logo-link">
                <div class="fw-bold text-primary fs-4" style="letter-spacing: -1px;">
                    <i class="bi bi-intersect me-2"></i>L&F
                </div>
            </a>

            <ul class="nav-links d-none d-md-flex">
                <li><a href="{{ route('home') }}" class="{{ request()->is('home') ? 'active' : '' }}">Dashboard</a></li>
                <li><a href="{{ route('items.index') }}" class="{{ request()->is('items*') && !request()->is('items/user') ? 'active' : '' }}">Browse Items</a></li>
                
                @auth
                <li><a href="{{ route('items.user') }}" class="{{ request()->is('items/user') ? 'active' : '' }}">My Items</a></li>
                <li><a href="{{ route('owner.claims') }}" class="{{ request()->is('my-claims') ? 'active' : '' }}">My Claims</a></li>
                <li><a href="{{ route('fun.index') }}" class="{{ request()->is('fun') ? 'active' : '' }}">Weather</a></li>
                @endauth
            </ul>

            @auth
            <div class="d-flex align-items-center">
                <div class="dropdown me-3">
                    <button class="btn notification-btn position-relative" type="button" data-bs-toggle="dropdown" onclick="markNotificationsAsRead()">
                        <i class="bi bi-bell fs-5"></i>
                        @php $unreadCount = Auth::user()->notifications()->where('is_read', false)->count(); @endphp
                        @if($unreadCount > 0)
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger shadow-sm" style="font-size: 0.6rem; z-index: 2;">
                                {{ $unreadCount }}
                            </span>
                        @endif
                    </button>
                    <div class="dropdown-menu dropdown-menu-end border-0 shadow-lg p-3 mt-2 notif-card">
                        <div class="d-flex justify-content-between align-items-center mb-3 px-2">
                            <h6 class="fw-bold mb-0">Notifications</h6>
                            <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill small">{{ $unreadCount }} New</span>
                        </div>
                        <div class="notif-list">
                            @forelse(Auth::user()->notifications->take(10) as $notif)
                                <div class="p-3 mb-2 rounded-4 border {{ $notif->is_read ? 'bg-white opacity-75' : 'bg-primary bg-opacity-10 border-primary border-opacity-10' }}">
                                    <div class="small fw-bold text-dark mb-1">{{ $notif->title }}</div>
                                    <div class="small text-secondary lh-sm mb-2">
                                        @if(str_contains($notif->message, 'APPROVED'))
                                            {!! str_replace('APPROVED', '<span class="text-success fw-bold">APPROVED</span>', $notif->message) !!}
                                        @elseif(str_contains($notif->message, 'REJECTED'))
                                            {!! str_replace('REJECTED', '<span class="text-danger fw-bold">REJECTED</span>', $notif->message) !!}
                                        @else
                                            {{ $notif->message }}
                                        @endif
                                    </div>
                                    <div class="text-end text-muted opacity-50" style="font-size: 0.7rem;">
                                        <i class="bi bi-clock me-1"></i>{{ $notif->created_at->diffForHumans() }}
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-4">
                                    <i class="bi bi-bell-slash text-muted opacity-25 fs-1"></i>
                                    <p class="small text-muted mt-2">No notifications yet</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="user-dropdown">
                    <div class="user-pill" onclick="toggleUserMenu(event)">
                        @if(Auth::user()->profile_picture)
                            <img src="{{ asset('storage/' . Auth::user()->profile_picture) }}" class="rounded-circle me-2" style="width: 32px; height: 32px; object-fit: cover;">
                        @else
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px; font-weight: 700; font-size: 0.8rem;">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                        @endif
                        <span class="small fw-bold text-dark">{{ Auth::user()->name }}</span>
                        <i class="bi bi-chevron-down ms-2 small text-muted"></i>
                    </div>

                    <div class="dropdown-card" id="userMenu">
                        <a href="{{ route('profile.edit') }}" class="dropdown-item-user">
                            <i class="bi bi-person-circle me-2"></i> My Profile
                        </a>
                        <a href="#" class="dropdown-item-user">
                            <i class="bi bi-gear me-2"></i> Settings
                        </a>
                        <hr class="my-0 opacity-10">
                        <a href="{{ route('logout') }}" class="dropdown-item-user logout" onclick="event.preventDefault(); confirmLogout();">
                            <i class="bi bi-box-arrow-right me-2"></i> Logout
                        </a>
                    </div>
                </div>
            </div>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
            @else
            <div class="nav-links"><a href="{{ route('login') }}">Login</a></div>
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
            if (menu && menu.classList.contains('show')) { menu.classList.remove('show'); }
        }

        function markNotificationsAsRead() {
            fetch("{{ route('notifications.read') }}", {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
            }).then(() => {
                const badge = document.querySelector('.notification-btn .badge');
                if(badge) badge.remove();
            });
        }

        function confirmLogout() {
            Swal.fire({
                title: 'Ready to leave?',
                text: "You will need to login again.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#6366f1',
                cancelButtonColor: '#f1f5f9',
                confirmButtonText: 'Logout Now',
                cancelButtonText: 'Stay',
                reverseButtons: true,
                customClass: {
                    popup: 'rounded-4 shadow border-0',
                    confirmButton: 'btn btn-primary px-4 py-2 rounded-pill fw-bold ms-2',
                    cancelButton: 'btn btn-light px-4 py-2 rounded-pill fw-bold text-dark'
                },
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) { document.getElementById('logout-form').submit(); }
            })
        }
    </script>
</body>
</html>