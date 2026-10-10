<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Reports - BantayResolve</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body { background-color: #f4f6f9; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }

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

        .main-wrapper {
            padding: 30px;
            flex: 1;
            box-sizing: border-box;
        }

        .top-navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
        }

        .card-report {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.01);
        }
        .remarks-box {
            border: 2px solid #3b82f6;
            border-radius: 8px;
            padding: 10px 15px;
            background-color: #ffffff;
            margin-top: 10px;
            margin-bottom: 15px;
        }
        .badge-status {
            font-size: 0.75rem;
            padding: 4px 10px;
            border-radius: 20px;
            font-weight: 600;
        }
        .badge-pending { background-color: #fef3c7; color: #d97706; }
        .badge-progress { background-color: #e0f2fe; color: #0284c7; }
        .badge-resolved { background-color: #10b981; color: #ffffff; }
        .filter-pill {
            font-size: 0.8rem;
            padding: 6px 14px;
            border: 1px solid #cbd5e1;
            background: #ffffff;
            color: #475569;
            border-radius: 4px;
            text-decoration: none;
            font-weight: 500;
        }
        .filter-pill.active {
            background: #334155;
            color: #ffffff;
            border-color: #334155;
        }

        .active-photo {
            border: 2px solid #0d6efd;
        }
    </style>
</head>
<body>

    <div class="d-flex" style="min-height: 100vh;">
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

        <main class="main-wrapper flex-grow-1">
        <!-- Top Navbar Header -->
        <div class="top-navbar">
            <h4 class="fw-bold text-dark m-0">My Submitted Reports</h4>
            <div class="text-secondary fw-medium" style="font-size: 0.9rem;">
                <i class="bi bi-person-circle me-1"></i> Citizen: <span class="text-dark fw-bold">{{ Auth::user()->name }}</span>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Filter Tracking Header -->
        <div class="card bg-white border border-light-subtle rounded-3 p-3 mb-4 shadow-sm">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div class="fw-bold text-dark d-flex align-items-center gap-2">
                    <i class="bi bi-clock-history text-primary"></i> Report Tracking & Status History
                </div>
                <div class="d-flex gap-1">
                    <a href="{{ url('/citizen/reports') }}" class="filter-pill {{ request('status') == '' ? 'active' : '' }}">All</a>
                    <a href="{{ url('/citizen/reports?status=Received') }}" class="filter-pill {{ request('status') == 'Received' ? 'active' : '' }}">Received</a>
                    <a href="{{ url('/citizen/reports?status=Review') }}" class="filter-pill {{ request('status') == 'Review' ? 'active' : '' }}">Review</a>
                    <a href="{{ url('/citizen/reports?status=Progress') }}" class="filter-pill {{ request('status') == 'Progress' ? 'active' : '' }}">Progress</a>
                    <a href="{{ url('/citizen/reports?status=Resolved') }}" class="filter-pill {{ request('status') == 'Resolved' ? 'active' : '' }}">Resolved</a>
                </div>
            </div>
        </div>

        <!-- Reports List -->
        @if(isset($reports) && $reports->count() > 0)
            @foreach($reports as $report)
                <div class="card-report">
                    <!-- Description & Facebook -->
                    <div class="mb-2 text-dark" style="font-size: 0.95rem;">
                        <strong>Description:</strong> {{ $report->description }} 
                        @if(!empty($report->facebook_link))
                            <span class="ms-2"><strong>Facebook Account:</strong> {{ $report->facebook_link }}</span>
                        @endif
                    </div>

                    <!-- Official Admin Remarks Box -->
                    <div class="remarks-box">
                        <div class="text-primary fw-bold" style="font-size: 0.85rem;">
                            <i class="bi bi-chat-left-text-fill me-1"></i> Official Admin Remarks:
                        </div>
                        <div class="text-dark mt-1" style="font-size: 0.9rem;">
                            {{ $report->admin_remarks ?? 'No remarks from admin yet.' }}
                        </div>
                    </div>

                    <!-- Report Details Grid -->
                    <div class="row align-items-center">
                        <!-- Left Info: Category, Status, Location, Contact -->
                        <div class="col-md-4 mb-3 mb-md-0">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <h5 class="fw-bold text-dark m-0">{{ $report->category }}</h5>
                                @php
                                    $statusClass = 'badge-pending';
                                    if($report->status == 'In Progress' || $report->status == 'Progress') $statusClass = 'badge-progress';
                                    if($report->status == 'Resolved') $statusClass = 'badge-resolved';
                                @endphp
                                <span class="badge badge-status {{ $statusClass }}">{{ $report->status }}</span>
                            </div>
                            <div class="text-secondary small mb-1">
                                <i class="bi bi-geo-alt-fill text-danger me-1"></i> 
                                <span class="text-secondary">{{ $report->location }}</span> 
                            </div>
                            @if(!empty($report->contact_number))
                                <div class="text-secondary small">
                                    <i class="bi bi-telephone-fill text-success me-1"></i> {{ $report->contact_number }}
                                </div>
                            @endif
                        </div>

                        <!-- Center: Photo Thumbnail & View All Photos Button -->
                        <div class="col-md-5 text-center mb-3 mb-md-0">
                            @php
                                $reportPhotos = $report->photoCollection();
                                $reportPrimaryPhoto = $report->firstPhoto();
                            @endphp

                            @if(!empty($reportPhotos))
                                <a href="#" data-bs-toggle="modal" data-bs-target="#citizenMyReportModal{{ $report->id }}" class="d-inline-block p-1 border bg-light rounded mb-2 text-decoration-none" style="max-width: 120px;">
                                    <img src="{{ asset($reportPrimaryPhoto) }}" alt="Report Photo" class="img-fluid rounded" style="max-height: 70px; object-fit: cover; cursor: pointer;">
                                </a>
                                <div>
                                    <button type="button" class="btn btn-dark btn-sm px-3 py-1" data-bs-toggle="modal" data-bs-target="#citizenMyReportModal{{ $report->id }}" style="font-size: 0.75rem;">
                                        <i class="bi bi-image me-1"></i> View All Photos ({{ count($reportPhotos) }})
                                    </button>
                                </div>

                                <div class="modal fade" id="citizenMyReportModal{{ $report->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-xl">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title fw-bold">Your Uploaded Evidence</h5>
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
                                                        <img src="{{ asset($reportPhotos[0]) }}" class="gallery-main-image img-fluid rounded shadow-sm" style="max-height: 60vh; object-fit: contain;" alt="Report photo evidence">
                                                    </div>

                                                    @if(count($reportPhotos) > 1)
                                                        <div class="d-flex flex-wrap justify-content-center gap-2 mt-2">
                                                            @foreach($reportPhotos as $index => $photo)
                                                                <button type="button" class="gallery-thumb-btn border-0 bg-transparent p-0 {{ $index === 0 ? 'active' : '' }}" data-index="{{ $index }}">
                                                                    <img src="{{ asset($photo) }}" class="img-fluid rounded {{ $index === 0 ? 'active-photo' : '' }}" style="width: 72px; height: 72px; object-fit: cover; border-radius: 10px;">
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
                            @else
                                <span class="text-muted small fst-italic">No photo attached</span>
                            @endif
                        </div>

                        <!-- Right Info: Submission Date & Delete Button -->
                        <div class="col-md-3 text-md-end text-start">
                            <div class="text-muted small mb-2">
                                <i class="bi bi-file-earmark-text me-1"></i> Submitted on: {{ $report->created_at->format('M d, Y') }}
                            </div>
                            <form action="{{ url('/citizen/reports/' . $report->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this report?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm px-3 py-1" style="font-size: 0.8rem;">
                                    <i class="bi bi-trash me-1"></i> Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach

            <div class="mt-4">
                {{ $reports->links() }}
            </div>
        @else
            <div class="card-report text-center py-5">
                <i class="bi bi-file-earmark-x text-muted fs-1 mb-3 d-block"></i>
                <h6 class="fw-bold text-dark">No reports found</h6>
                <p class="text-secondary small mb-3">You haven't submitted any community reports yet.</p>
                <a href="{{ url('/citizen/reports/create') }}" class="btn btn-primary btn-sm fw-bold">File Your First Report</a>
            </div>
        @endif
    </main>

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