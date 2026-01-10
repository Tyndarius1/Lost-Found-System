<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - @yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            min-height: 100vh;
            display: flex;
            margin: 0;
            background-color: #f8f9fa;
        }
        .sidebar {
            width: 220px;
            background-color: #343a40;
            color: white;
            min-height: 100vh;
            padding-top: 1rem;
        }
        .sidebar a {
            display: block;
            color: white;
            padding: 0.75rem 1rem;
            text-decoration: none;
            margin-bottom: 0.25rem;
            border-radius: 5px;
        }
        .sidebar a:hover, .sidebar a.active {
            background-color: #495057;
        }
        .main-content {
            flex: 1; /* occupy remaining space */
            padding: 2rem;
            overflow-x: auto;
        }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <h4 class="text-center mb-4">Admin Panel</h4>
        <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">Dashboard</a>
        <a href="{{ route('admin.items') }}" class="{{ request()->routeIs('admin.items*') ? 'active' : '' }}">Items</a>
        <a href="{{ route('admin.claims') }}" class="{{ request()->routeIs('admin.claims*') ? 'active' : '' }}">Claims</a>
        <a href="{{ route('admin.users') }}" class="{{ request()->routeIs('admin.users*') ? 'active' : '' }}">Users</a>
        <form method="POST" action="{{ route('logout') }}" class="mt-3 px-3">
            @csrf
            <button type="submit" class="btn btn-danger w-100">Logout</button>
        </form>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <h2>@yield('title')</h2>
        <hr>
        @yield('content')
    </div>

</body>
</html>
