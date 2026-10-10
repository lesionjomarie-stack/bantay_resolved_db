<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'BantayResolve') }}</title>
    
    <!-- Fonts & Icons -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body { background-color: #f8f9fa; }

        /* Unified Sidebar Container */
        .sidebar-container {
            width: 260px;
            flex-shrink: 0;
            background-color: #ffffff !important;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            position: sticky;
            top: 0;
        }

        /* Standard Inactive Link Design */
        .sidebar-link { 
            color: #333333 !important; 
            font-weight: 500; 
            border-radius: 12px; 
            margin-bottom: 4px; 
            padding: 14px 18px;
            transition: all 0.2s ease-in-out;
            display: flex;
            align-items: center;
            background-color: transparent !important;
            text-decoration: none !important;
            box-shadow: none !important;
            outline: none !important;
            width: 100%;
            min-height: 52px;
            font-size: 1.08rem;
        }

        .sidebar-link i {
            color: #4a5568;
            font-size: 1.35rem;
            transition: color 0.2s ease-in-out;
        }

        /* Hover State for Inactive Items */
        .sidebar-link:hover:not(.active) { 
            background-color: rgba(0, 0, 0, 0.04) !important; 
            color: #000000 !important; 
        }

        .sidebar-link:hover:not(.active) i {
            color: #000000;
        }

        /* Solid Blue Active Button State for ALL Pages */
        .sidebar-link.active,
        .sidebar-link.active:hover,
        .sidebar-link.active:focus { 
            background-color: #2563eb !important; /* Solid vibrant blue */
            color: #ffffff !important;            /* White text */
            font-weight: 600; 
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25) !important;
        }

        .sidebar-link.active i {
            color: #ffffff !important;
        }
    </style>
</head>
<body>
    <div class="d-flex" style="min-height: 100vh;">
        
        <!-- SINGLE MASTER SIDEBAR -->
        @auth
            <div class="sidebar-container border-end shadow-sm">
                <div class="p-4 border-bottom">
                    <h5 class="fw-bold text-primary mb-0"><i class="bi bi-shield-check me-2"></i>BantayResolve</h5>
                </div>
                <div class="p-3 d-flex flex-column flex-grow-1">
                    <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="bi bi-grid-1x2 me-2"></i> Dashboard
                    </a>
                    <a href="{{ route('admin.pending-reports') }}" class="sidebar-link {{ request()->routeIs('admin.pending-reports') ? 'active' : '' }}">
                        <i class="bi bi-exclamation-triangle me-2"></i> Pending Reports
                    </a>
                    <a href="{{ route('admin.announcements') }}" class="sidebar-link {{ request()->routeIs('admin.announcements') ? 'active' : '' }}">
                        <i class="bi bi-megaphone me-2"></i> Announcements
                    </a>
                    <a href="{{ route('admin.users') }}" class="sidebar-link {{ request()->routeIs('admin.users') ? 'active' : '' }}">
                        <i class="bi bi-people me-2"></i> Manage Users
                    </a>
                    <a href="{{ route('admin.logs') }}" class="sidebar-link {{ request()->routeIs('admin.logs') ? 'active' : '' }}">
                        <i class="bi bi-clock-history me-2"></i> Activity Logs
                    </a>
                    
                    <div class="mt-auto pt-3 border-top">
                        <a class="sidebar-link text-danger" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="bi bi-box-arrow-right me-2"></i> Logout
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </div>
                </div>
            </div>
        @endauth

        <!-- MAIN CONTENT AREA -->
        <div class="flex-grow-1">
            <div class="bg-white border-bottom shadow-sm px-4 py-3 d-flex justify-content-between align-items-center">
                <h4 class="mb-0 fw-bold">Admin Dashboard</h4>
                <div class="text-muted small fw-semibold">
                    <i class="bi bi-person-circle me-1"></i> Administrator: {{ Auth::user()->name ?? 'Administrator' }}
                </div>
            </div>

            <main class="p-4">
                @yield('content')
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>