<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Community Announcements - BantayResolve</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { background-color: #f4f6f9; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }

        /* Sidebar Styling */
        .sidebar { 
            width: 250px; 
            height: 100vh; 
            background: #ffffff; 
            border-right: 1px solid #e0e0e0; 
            position: sticky; 
            top: 0;
            left: 0;
            z-index: 1000;
            display: flex;
            flex-direction: column;
        }

        .sidebar .nav-link { 
            color: #334155; 
            font-weight: 500; 
            padding: 14px 18px; 
            border-radius: 12px; 
            margin: 4px 0; 
            display: flex;
            align-items: center;
            justify-content: space-between;
            text-decoration: none;
            white-space: nowrap;
            transition: all 0.2s ease-in-out; 
            width: 100%;
            min-height: 52px;
            font-size: 1.08rem;
        }

        .sidebar .nav-link i { font-size: 1.35rem; color: #475569; }
        .sidebar .nav-link:hover { background-color: #f1f5f9; color: #0d6efd; }
        .sidebar .nav-link:hover i { color: #0d6efd; }

        .sidebar .nav-link.active { 
            background-color: #1d61f2 !important; 
            color: #ffffff !important; 
            font-weight: 600; 
            box-shadow: 0 4px 12px rgba(29, 97, 242, 0.3); 
        }

        .sidebar .nav-link.active i { color: #ffffff !important; }

        .main-content { padding: 30px 30px 30px 20px; flex: 1; box-sizing: border-box; }

        /* Announcement Card Styling */
        .announcement-card {
            background: #ffffff;
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 20px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 2px 8px rgba(0,0,0,0.03);
            transition: transform 0.2s ease;
        }

        .announcement-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.06);
        }

        .priority-badge {
            font-size: 0.75rem;
            font-weight: 600;
            padding: 4px 10px;
            border-radius: 20px;
            text-transform: uppercase;
        }

        .priority-high, .priority-urgent {
            background-color: #fee2e2;
            color: #dc2626;
        }

        .priority-medium {
            background-color: #fef3c7;
            color: #d97706;
        }

        .priority-low, .priority-normal {
            background-color: #e0f2fe;
            color: #0284c7;
        }
    </style>
</head>
<body>

<div class="d-flex">
    <!-- Sidebar -->
    <div class="sidebar d-flex flex-column p-3">
        <div class="d-flex align-items-center mb-4 px-2 pt-2">
            <i class="bi bi-shield-check text-primary fs-3 me-2"></i>
            <h5 class="fw-bold m-0 text-primary">BantayResolve</h5>
        </div>

        <ul class="nav nav-pills flex-column mb-auto">
            <li class="nav-item">
                <a href="{{ route('citizen.dashboard') }}" class="nav-link {{ request()->routeIs('citizen.dashboard') ? 'active' : '' }}">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-grid-fill"></i>
                        <span>Dashboard</span>
                    </div>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('citizen.reports.create') }}" class="nav-link {{ request()->routeIs('citizen.reports.create') ? 'active' : '' }}">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-plus-circle"></i>
                        <span>File a Report</span>
                    </div>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('citizen.announcements') }}" class="nav-link {{ request()->routeIs('citizen.announcements') ? 'active' : '' }}">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-megaphone"></i>
                        <span>Announcements</span>
                    </div>

                    @php
                        $unreadCount = Auth::user() ? Auth::user()->unreadNotifications->where('type', 'App\Notifications\NewAnnouncementNotification')->count() : 0;
                    @endphp

                    @if($unreadCount > 0)
                        <span class="badge rounded-pill bg-danger">
                            {{ $unreadCount }}
                        </span>
                    @endif
                </a>
            </li>
            <li class="nav-item" style="margin-top: 6px;">
                <a href="{{ route('citizen.reports.index') }}" class="nav-link {{ request()->routeIs('citizen.reports.index') ? 'active' : '' }}">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-folder2-open"></i>
                        <span>My Reports</span>
                    </div>
                </a>
            </li>
        </ul>

        <div class="mt-auto pt-3 border-top">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-link nav-link text-danger w-100 text-start border-0 text-decoration-none">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-box-arrow-right text-danger"></i>
                        <span>Logout</span>
                    </div>
                </button>
            </form>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content flex-grow-1">
        <div class="d-flex justify-content-between align-items-center mb-4" style="margin-left: 0;">
            <h4 class="fw-bold m-0 text-dark">Community Announcements</h4>
            <div class="text-muted fw-semibold">
                <i class="bi bi-person-circle me-1"></i> Citizen: {{ Auth::user()->name ?? 'Citizen' }}
            </div>
        </div>

        <!-- Dynamic Announcements Loop -->
        @forelse($announcements as $announcement)
            <div class="announcement-card">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <h5 class="fw-bold text-dark m-0">{{ $announcement->title }}</h5>
                    <div class="d-flex align-items-center gap-2">
                        @if($announcement->priority)
                            <span class="priority-badge priority-{{ strtolower($announcement->priority) }}">
                                {{ $announcement->priority }}
                            </span>
                        @endif
                        <span class="text-muted small">
                            {{ $announcement->created_at ? $announcement->created_at->format('F j, Y') : '' }}
                        </span>
                    </div>
                </div>
                <p class="text-muted mb-0 mt-3" style="line-height: 1.6;">
                    {!! nl2br(e($announcement->content)) !!}
                </p>
                @if($announcement->user)
                    <div class="mt-3 pt-2 border-top text-muted small">
                        <i class="bi bi-person-fill me-1"></i> Posted by: {{ $announcement->user->name }}
                    </div>
                @endif
            </div>
        @empty
            <div class="card border-0 shadow-sm p-5 text-center text-muted bg-white rounded-3">
                <i class="bi bi-megaphone fs-1 mb-2 text-secondary opacity-50"></i>
                <h6 class="fw-bold">No Announcements Yet</h6>
                <p class="m-0 small">Check back later for public updates and community advisories.</p>
            </div>
        @endforelse
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>