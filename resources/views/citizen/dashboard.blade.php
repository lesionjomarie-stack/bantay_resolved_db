<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Citizen Portal Dashboard - BantayResolve</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { background-color: #f4f6f9; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }

        /* Sidebar Container */
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

        .sidebar .nav {
            display: flex;
            flex-direction: column;
            flex: 1;
            margin-bottom: 0;
        }

        /* Nav Item Styles */
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

        .sidebar .nav-link i {
            font-size: 1.35rem;
            color: #475569;
        }

        .sidebar .nav-link:hover { 
            background-color: #f1f5f9; 
            color: #0d6efd; 
        }

        .sidebar .nav-link:hover i {
            color: #0d6efd;
        }

        /* Active Blue Highlight */
        .sidebar .nav-link.active { 
            background-color: #1d61f2 !important; 
            color: #ffffff !important; 
            font-weight: 600; 
            box-shadow: 0 4px 12px rgba(29, 97, 242, 0.3); 
            border: none;
        }

        .sidebar .nav-link.active i { 
            color: #ffffff !important; 
        }

        .sidebar .nav-item:last-child {
            margin-top: auto;
        }

        .sidebar .btn-link.nav-link {
            border: none;
            background: transparent;
            text-align: left;
            padding-left: 16px;
            padding-right: 16px;
            width: 100%;
            display: flex;
            align-items: center;
        }

        .main-content {
            padding: 30px;
            flex: 1;
            box-sizing: border-box;
        }
        
        /* Welcome Banner */
        .welcome-banner { 
            background: linear-gradient(135deg, #0d6efd, #1d61f2); 
            color: white; 
            border-radius: 12px; 
            padding: 25px 30px; 
            box-shadow: 0 4px 15px rgba(13, 110, 253, 0.2); 
        }

        /* Stat Cards */
        .stat-card { 
            background: #fff; 
            border-radius: 12px; 
            padding: 22px; 
            box-shadow: 0 2px 6px rgba(0,0,0,0.04); 
            border: 1px solid #e2e8f0; 
        }

        /* Submission Card Row */
        .submission-card { 
            background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%); 
            border-radius: 14px; 
            padding: 20px; 
            border: 1px solid #e2e8f0; 
            margin-bottom: 15px; 
            box-shadow: 0 10px 25px rgba(15, 23, 42, 0.04); 
            transition: transform 0.2s ease, box-shadow 0.2s ease; 
        }
        .submission-card:hover { 
            transform: translateY(-2px); 
            box-shadow: 0 16px 30px rgba(13, 110, 253, 0.09); 
        }
    </style>
</head>
<body>

<div class="d-flex">
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

    <div class="main-content flex-grow-1">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold m-0 text-dark">Citizen Portal Dashboard</h3>
            <div class="text-muted fw-semibold">
                <i class="bi bi-person-circle me-1"></i> Citizen: {{ Auth::user()->name ?? 'Citizen' }}
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="welcome-banner d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold mb-1">Welcome back, {{ Auth::user()->name ?? 'Citizen' }}!</h3>
                <p class="mb-0 opacity-75">Track your community concerns securely and monitor official government responses.</p>
            </div>
            <a href="{{ route('citizen.reports.create') }}" class="btn btn-light text-primary fw-bold px-4 py-2 shadow-sm">
                <i class="bi bi-plus-lg me-1"></i> File New Report
            </a>
        </div>

        <div class="row g-3 mb-5">
            <div class="col-md-4">
                <div class="stat-card">
                    <small class="text-muted fw-bold text-uppercase tracking-wider" style="font-size: 0.75rem;">Total Reports Filed</small>
                    <h2 class="fw-bold m-0 text-primary mt-1">{{ $totalReports ?? 0 }}</h2>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card">
                    <small class="text-muted fw-bold text-uppercase tracking-wider" style="font-size: 0.75rem;">Active / Pending</small>
                    <h2 class="fw-bold m-0 text-warning mt-1">{{ $activeReports ?? $activePendingReports ?? 0 }}</h2>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card">
                    <small class="text-muted fw-bold text-uppercase tracking-wider" style="font-size: 0.75rem;">Resolved Issues</small>
                    <h2 class="fw-bold m-0 text-success mt-1">{{ $resolvedReports ?? 0 }}</h2>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="fw-bold m-0 text-dark">
                <i class="bi bi-clock-history text-primary me-1"></i> Recent Submissions
            </h5>
            <a href="{{ route('citizen.reports.index') }}" class="text-primary text-decoration-none fw-semibold small">
                View All Reports
            </a>
        </div>

        @forelse($recentReports ?? $reports ?? [] as $report)
            <div class="submission-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <h6 class="fw-bold mb-0 text-dark">{{ $report->category ?? 'General Report' }}</h6>
                            @if(strtolower($report->status) == 'resolved')
                                <span class="badge bg-success px-2 py-1" style="font-size: 0.75rem;">Resolved</span>
                            @elseif(in_array(strtolower($report->status), ['in progress', 'progress']))
                                <span class="badge bg-primary px-2 py-1" style="font-size: 0.75rem;">In Progress</span>
                            @else
                                <span class="badge bg-warning text-dark px-2 py-1" style="font-size: 0.75rem;">Pending</span>
                            @endif
                        </div>

                        <div class="text-muted small mb-1">
                            <i class="bi bi-geo-alt-fill text-danger me-1"></i> {{ $report->location ?? 'No location specified' }}
                        </div>

                        @php
                            $reportPhotos = $report->photoCollection();
                            $reportPrimaryPhoto = $report->firstPhoto();
                        @endphp

                        @if(!empty($reportPhotos))
                            <div class="mt-2">
                                <a href="#" data-bs-toggle="modal" data-bs-target="#citizenPhotoModal{{ $report->id }}" class="d-inline-block text-decoration-none">
                                    <img src="{{ asset($reportPrimaryPhoto) }}" alt="Attached report evidence" class="img-fluid rounded border" style="max-width: 120px; max-height: 80px; object-fit: cover; cursor: pointer;">
                                </a>
                            </div>

                            <div class="modal fade" id="citizenPhotoModal{{ $report->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-xl">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title fw-bold">Uploaded Photo Evidence</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body p-3">
                                            <div class="photo-gallery-modal" data-photos='@json(array_map(fn ($photo) => asset($photo), $reportPhotos))' data-id="{{ $report->id }}">
                                                <div class="d-flex justify-content-between align-items-center mb-3">
                                                    <button type="button" class="btn btn-light border prev-photo" aria-label="Previous photo">
                                                        <i class="bi bi-chevron-left"></i>
                                                    </button>
                                                    <div class="text-muted small fw-semibold photo-counter">1 / {{ count($reportPhotos) }}</div>
                                                    <button type="button" class="btn btn-light border next-photo" aria-label="Next photo">
                                                        <i class="bi bi-chevron-right"></i>
                                                    </button>
                                                </div>

                                                <div class="d-flex justify-content-center align-items-center mb-3" style="min-height: 340px; background: #ffffff; border-radius: 12px; border: 1px solid #e2e8f0;">
                                                    <img src="{{ asset($reportPhotos[0]) }}" class="gallery-main-image img-fluid rounded shadow-sm" style="max-height: 60vh; object-fit: contain;" alt="Citizen uploaded evidence">
                                                </div>

                                                @if(count($reportPhotos) > 1)
                                                    <div class="d-flex flex-wrap justify-content-center gap-2 mt-2">
                                                        @foreach($reportPhotos as $index => $photo)
                                                            <button type="button" class="gallery-thumb-btn border-0 bg-transparent p-0 {{ $index === 0 ? 'active' : '' }}" data-index="{{ $index }}">
                                                                <img src="{{ asset($photo) }}" class="img-fluid rounded" style="width: 72px; height: 72px; object-fit: cover; border: {{ $index === 0 ? '2px solid #0d6efd' : '2px solid transparent' }}; border-radius: 10px;">
                                                            </button>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="text-muted small mt-2">
                            <i class="bi bi-calendar3 me-1"></i> Submitted on: {{ $report->created_at ? $report->created_at->format('M d, Y') : 'N/A' }}
                        </div>
                    </div>

                    <div>
                        <a href="{{ route('citizen.reports.index') }}" class="btn btn-outline-secondary btn-sm px-3 fw-semibold">
                            Details
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="card border-0 shadow-sm p-5 text-center text-muted bg-white rounded-3">
                <i class="bi bi-inbox fs-1 mb-2 text-secondary opacity-50"></i>
                <p class="m-0">No reports submitted yet.</p>
            </div>
        @endforelse
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.photo-gallery-modal').forEach(function (gallery) {
            const photos = JSON.parse(gallery.dataset.photos || '[]');
            const mainImage = gallery.querySelector('.gallery-main-image');
            const counter = gallery.querySelector('.photo-counter');
            const prevBtn = gallery.querySelector('.prev-photo');
            const nextBtn = gallery.querySelector('.next-photo');
            const thumbs = gallery.querySelectorAll('.gallery-thumb-btn');

            let currentIndex = 0;

            function renderGallery() {
                if (!photos.length || !mainImage) return;

                mainImage.src = photos[currentIndex];

                if (counter) {
                    counter.textContent = (currentIndex + 1) + ' / ' + photos.length;
                }

                thumbs.forEach(function (thumb, index) {
                    const active = index === currentIndex;
                    thumb.classList.toggle('active', active);
                    const img = thumb.querySelector('img');
                    if (img) {
                        img.style.border = active ? '2px solid #0d6efd' : '2px solid transparent';
                    }
                });
            }

            prevBtn && prevBtn.addEventListener('click', function () {
                currentIndex = (currentIndex - 1 + photos.length) % photos.length;
                renderGallery();
            });

            nextBtn && nextBtn.addEventListener('click', function () {
                currentIndex = (currentIndex + 1) % photos.length;
                renderGallery();
            });

            thumbs.forEach(function (thumb) {
                thumb.addEventListener('click', function () {
                    currentIndex = Number(thumb.dataset.index || 0);
                    renderGallery();
                });
            });

            renderGallery();
        });
    });
</script>
</body>
</html>