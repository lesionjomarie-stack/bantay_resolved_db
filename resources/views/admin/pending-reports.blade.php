<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pending Reports - BantayResolve</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { background-color: #f4f6f9; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }

        /* Sidebar Styling */
        .sidebar { 
            width: 250px; 
            min-height: 100vh; 
            background: #ffffff; 
            border-right: 1px solid #e0e0e0; 
            position: fixed; 
            top: 0;
            left: 0;
            z-index: 1000;
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

        .main-content { margin-left: 250px; padding: 30px; }

        /* Card Layout */
        .report-card {
            background: #ffffff;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 2px 6px rgba(0,0,0,0.03);
            margin-bottom: 20px;
            padding: 24px;
        }

        .report-thumb {
            width: 100%;
            height: 140px;
            object-fit: cover;
            border-radius: 8px;
            cursor: pointer;
            transition: opacity 0.2s;
        }

        .report-thumb:hover {
            opacity: 0.85;
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
                <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-grid-fill"></i>
                        <span>Dashboard</span>
                    </div>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.pending-reports') }}" class="nav-link {{ request()->routeIs(['admin.pending-reports', 'admin.reports.pending']) ? 'active' : '' }}">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                        <span>Pending Reports</span>
                    </div>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.announcements') }}" class="nav-link {{ request()->routeIs('admin.announcements') ? 'active' : '' }}">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-megaphone"></i>
                        <span>Announcements</span>
                    </div>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.users') }}" class="nav-link {{ request()->routeIs('admin.users') ? 'active' : '' }}">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-people"></i>
                        <span>Manage Users</span>
                    </div>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.logs') }}" class="nav-link {{ request()->routeIs('admin.logs') ? 'active' : '' }}">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-clock-history"></i>
                        <span>Activity Logs</span>
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
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold m-0 text-secondary">Pending & Active Reports Queue</h4>
            <div class="text-muted fw-semibold">
                Administrator: {{ Auth::user()->name ?? 'Admin' }}
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Active Queue Counter Banner -->
        <div class="bg-light p-3 rounded-3 border d-flex justify-content-between align-items-center mb-4">
            <div class="d-flex align-items-center gap-2 text-warning fw-bold">
                <i class="bi bi-exclamation-triangle-fill"></i>
                <span>Active Queue (Pending / In Review / In Progress)</span>
            </div>
            <span class="badge bg-warning text-dark px-3 py-2 rounded-pill fw-bold">
                {{ $pendingReports ?? count($reports) }} Unresolved Issue(s) Found
            </span>
        </div>

        <!-- Filter Bar -->
        <div class="card border-0 shadow-sm p-3 mb-4">
            <form method="GET" action="{{ route('admin.reports.pending') }}" class="row g-3">
                <div class="col-md-6">
                    <label class="form-label text-muted small fw-bold text-uppercase">Search Keywords</label>
                    <input type="text" name="search" class="form-control" placeholder="Search description, location, citizen..." value="{{ request('search') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label text-muted small fw-bold text-uppercase">Filter By Status</label>
                    <select name="status" class="form-select">
                        <option value="All Unresolved Statuses">All Unresolved Statuses</option>
                        <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                        <option value="In Progress" {{ request('status') == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-warning w-100 fw-bold">
                        <i class="bi bi-search me-1"></i> Filter
                    </button>
                </div>
            </form>
        </div>

        <!-- Reports List -->
        @forelse($reports as $report)
            <div class="report-card">
                <div class="row g-4">
                    <!-- Column 1: Citizen Details -->
                    <div class="col-md-3 border-end">
                        <div class="fw-bold text-dark mb-1">
                            <i class="bi bi-person-fill text-secondary me-1"></i> {{ $report->user->name ?? 'Anonymous Citizen' }}
                        </div>
                        <div class="mb-2">
                            <span class="badge bg-primary px-2 py-1">{{ $report->category }}</span>
                        </div>
                        <div class="text-primary small mb-1">
                            <i class="bi bi-geo-alt-fill me-1"></i> {{ $report->location }}
                        </div>
                        <div class="text-muted small mb-1">
                            <i class="bi bi-calendar3 me-1"></i> Submitted on: {{ $report->created_at ? $report->created_at->format('M d, Y') : 'N/A' }}
                        </div>
                        @if($report->contact_number)
                            <div class="text-muted small">
                                <i class="bi bi-telephone-fill me-1"></i> {{ $report->contact_number }}
                            </div>
                        @endif
                    </div>

                    <!-- Column 2: Description & Image Preview -->
                    <div class="col-md-4 border-end">
                        <div class="fw-bold text-dark small mb-1">Description:</div>
                        <p class="text-muted small mb-3">{!! nl2br(e($report->description)) !!}</p>

                        @php
                            $reportPhotos = $report->photoCollection();
                            $reportPrimaryPhoto = $report->firstPhoto();
                        @endphp

                        @if(!empty($reportPhotos))
                            <div class="mb-2">
                                <img src="{{ asset($reportPrimaryPhoto) }}" class="report-thumb" alt="Report Attachment" data-bs-toggle="modal" data-bs-target="#photoModal{{ $report->id }}">
                            </div>
                            <button type="button" class="btn btn-dark btn-sm rounded-pill w-100 fw-semibold" data-bs-toggle="modal" data-bs-target="#photoModal{{ $report->id }}">
                                <i class="bi bi-eye-fill me-1"></i> View All Photos ({{ count($reportPhotos) }})
                            </button>
                        @else
                            <div class="text-muted small italic">No photos attached.</div>
                        @endif
                    </div>

                    <!-- Column 3: Update Status Form -->
                    <div class="col-md-5">
                        <form action="{{ route('admin.reports.update', $report->id) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label text-muted small fw-bold">Update Status</label>
                                <select name="status" class="form-select">
                                    <option value="Pending" {{ $report->status == 'Pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="In Progress" {{ $report->status == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                                    <option value="Resolved" {{ $report->status == 'Resolved' ? 'selected' : '' }}>Resolved</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label text-muted small fw-bold">Admin Remarks / Feedback</label>
                                <textarea name="admin_remarks" class="form-control" rows="3" placeholder="Enter remarks or updates for the citizen...">{{ $report->admin_remarks }}</textarea>
                            </div>

                            <div class="d-flex justify-content-between align-items-center">
                                <button type="submit" class="btn btn-primary fw-bold px-4">
                                    <i class="bi bi-save me-1"></i> Save Update
                                </button>
                        </form>

                        <!-- Delete Report Form -->
                        <form action="{{ route('admin.reports.delete', $report->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this report?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger btn-sm fw-semibold">
                                <i class="bi bi-trash me-1"></i> Delete
                            </button>
                        </form>
                            </div>
                    </div>
                </div>
            </div>

            <!-- Modal for Photo Lightbox -->
            @if(!empty($report->photoCollection()))
                <div class="modal fade" id="photoModal{{ $report->id }}" tabindex="-1" aria-labelledby="photoModalLabel{{ $report->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-xl">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title fw-bold" id="photoModalLabel{{ $report->id }}">
                                    <i class="bi bi-image me-2 text-primary"></i> Attached Photos - Report #{{ $report->id }}
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>

                            <div class="modal-body bg-light p-4">
                                @php $reportPhotos = $report->photoCollection(); @endphp
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
                                        <img src="{{ asset($reportPhotos[0]) }}" class="gallery-main-image img-fluid rounded shadow-sm" style="max-height: 60vh; object-fit: contain;" alt="Report Attachment Full">
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
                                <a href="{{ asset($report->firstPhoto()) }}" target="_blank" class="btn btn-outline-primary btn-sm fw-semibold">
                                    <i class="bi bi-box-arrow-up-right me-1"></i> Open First Image
                                </a>
                                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

        @empty
            <div class="card border-0 shadow-sm p-5 text-center text-muted bg-white rounded-3">
                <i class="bi bi-check-circle fs-1 mb-2 text-success opacity-50"></i>
                <h6 class="fw-bold">No Pending Reports Found</h6>
                <p class="m-0 small">All reported issues have been resolved or no matching records exist.</p>
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