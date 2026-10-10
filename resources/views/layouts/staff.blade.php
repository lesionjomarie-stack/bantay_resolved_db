<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'BantayResolve') }} - Staff Dashboard</title>
    <link rel="dns-prefetch" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background: #f3f6fb; font-family: 'Nunito', 'Segoe UI', sans-serif; }
        .sidebar { width: 270px; min-height: 100vh; position: sticky; top: 0; height: 100vh; }
        .sidebar-link { display: flex; align-items: center; gap: .75rem; color: #475569; padding: .8rem 1rem; border-radius: .75rem; }
        .sidebar-link:hover, .sidebar-link.active { background: #2563eb; color: white; text-decoration: none; }
        .sidebar-link i { font-size: 1.2rem; }
        .mobile-sidebar { position: fixed; inset: 0 auto 0 0; z-index: 1040; width: 270px; transform: translateX(-100%); transition: transform .25s ease; }
        .mobile-sidebar.show { transform: translateX(0); }
        .sidebar-backdrop { position: fixed; inset: 0; z-index: 1039; display: none; background: rgba(15,23,42,.55); }
        .sidebar-backdrop.show { display: block; }
        .main-content { min-width: 0; }
        @media (min-width: 992px) {
            .mobile-sidebar { position: static; transform: none; height: 100vh; display: flex; flex-direction: column; }
            .main-content { flex: 1; }
            .sidebar-backdrop { display: none !important; }
        }
    </style>
</head>
<body class="d-flex flex-column flex-md-row">
    <aside class="mobile-sidebar bg-white border-end d-flex flex-column p-3" aria-label="Staff navigation">
        <div class="d-flex align-items-center px-3 mb-4 pt-2">
            <i class="bi bi-shield-check-fill text-primary fs-3 me-2"></i>
            <div><strong class="text-primary">BantayResolve</strong><small class="d-block text-muted">Staff operations</small></div>
        </div>
        <nav class="d-flex flex-column gap-1">
            <a href="{{ route('staff.dashboard') }}" class="sidebar-link {{ request()->routeIs('staff.dashboard') ? 'active' : '' }}"><i class="bi bi-grid-1x2-fill"></i>Dashboard</a>
            <a href="{{ route('staff.reports') }}" class="sidebar-link {{ request()->routeIs('staff.reports') ? 'active' : '' }}"><i class="bi bi-file-text-fill"></i>Community Reports</a>
            <a href="{{ route('staff.announcements') }}" class="sidebar-link {{ request()->routeIs('staff.announcements') ? 'active' : '' }}"><i class="bi bi-megaphone-fill"></i>Announcements</a>
        </nav>
        <div class="mt-auto border-top pt-3">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="sidebar-link border-0 bg-transparent w-100 text-start"><i class="bi bi-box-arrow-right"></i>Logout</button>
            </form>
        </div>
    </aside>

    <div class="sidebar-backdrop" id="sidebarBackdrop"></div>
    <div class="main-content flex-grow-1">
        <header class="bg-white border-bottom px-3 px-md-4 py-3 d-flex justify-content-between align-items-center sticky-top" style="top:0;z-index:1020">
            <div class="d-flex align-items-center gap-2">
                <button class="btn btn-outline-secondary d-md-none" id="sidebarToggle" type="button" aria-label="Open navigation"><i class="bi bi-list"></i></button>
                <strong class="small">Staff workspace</strong>
            </div>
            <div class="d-flex align-items-center gap-2">
                <span class="d-none d-md-inline text-muted small">Signed in as <strong>{{ Auth::user()->name }}</strong></span>
                <span class="badge bg-primary rounded-pill"><i class="bi bi-shield-lock-fill me-1"></i>Staff</span>
            </div>
        </header>
        <main>@yield('content')</main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const toggle = document.getElementById('sidebarToggle');
        const sidebar = document.querySelector('.mobile-sidebar');
        const backdrop = document.getElementById('sidebarBackdrop');
        if (toggle && backdrop) {
            toggle.addEventListener('click', () => {
                sidebar?.classList.add('show');
                backdrop.classList.add('show');
            });
            backdrop.addEventListener('click', () => {
                sidebar?.classList.remove('show');
                backdrop.classList.remove('show');
            });
        }
    </script>
</body>
</html>
