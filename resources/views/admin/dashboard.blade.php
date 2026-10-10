<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - BantayResolve</title>
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

        /* Circle Badge Counter */
        .badge-count {
            background-color: #ffc107;
            color: #000000;
            font-weight: 700;
            font-size: 0.8rem;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            line-height: 1;
        }

        .main-content {
            padding: 30px;
            flex: 1;
            box-sizing: border-box;
        }
        .stat-card { background: #fff; border-radius: 8px; padding: 20px; border-left: 5px solid #0d6efd; box-shadow: 0 2px 4px rgba(0,0,0,0.04); }
        .stat-card.pending { border-left-color: #ffc107; background: #fffdf5; }
        .stat-card.resolved { border-left-color: #198754; }
        .priority-banner { background-color: #fff9e6; border: 1px solid #ffeeba; border-left: 5px solid #ffc107; border-radius: 8px; padding: 20px; }
        .report-card { background: #fff; border-radius: 8px; padding: 20px; border: 1px solid #e2e8f0; margin-bottom: 20px; }
        .badge-category { background-color: #0d6efd; color: #fff; padding: 4px 10px; border-radius: 4px; font-size: 0.8rem; }
    </style>
</head>
<body>

<div class="d-flex">
    <div class="sidebar d-flex flex-column p-3">
        <div class="d-flex align-items-center mb-4 px-2 pt-2">
            <i class="bi bi-shield-check text-primary fs-3 me-2"></i>
            <h5 class="fw-bold m-0 text-primary">BantayResolve</h5>
        </div>

        <ul class="nav nav-pills flex-column">
            <li class="nav-item">
                <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-grid-fill"></i>
                        <span>Dashboard</span>
                    </div>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.pending-reports') }}" class="nav-link {{ request()->routeIs('admin.pending-reports') ? 'active' : '' }}">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-exclamation-triangle"></i>
                        <span>Pending Reports</span>
                    </div>
                    @if($pendingReports > 0)
                        <span class="badge-count">{{ $pendingReports }}</span>
                    @endif
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

            <hr class="text-secondary opacity-25 my-2" />

            <li class="nav-item">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-link nav-link text-dark w-100 text-start border-0">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-box-arrow-right"></i>
                            <span>Logout</span>
                        </div>
                    </button>
                </form>
            </li>
        </ul>
    </div>

    <div class="main-content flex-grow-1">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold m-0">Admin Dashboard</h3>
            <div class="text-muted fw-semibold">
                <i class="bi bi-person-circle me-1"></i> Administrator: {{ Auth::user()->name ?? 'Administrator' }}
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if($pendingReports > 0)
            <div class="priority-banner d-flex justify-content-between align-items-center mb-4">
                <div class="d-flex align-items-center">
                    <i class="bi bi-exclamation-triangle-fill text-warning fs-2 me-3"></i>
                    <div>
                        <h5 class="fw-bold mb-1 text-dark">Priority Attention Required: Unresolved Issues!</h5>
                        <p class="mb-0 text-muted">There are currently {{ $pendingReports }} pending or active community report(s) that require your review and action.</p>
                    </div>
                </div>
                <a href="{{ route('admin.pending-reports') }}" class="btn btn-warning fw-semibold px-4">
                    <i class="bi bi-arrow-right-circle me-1"></i> Review Pending Now
                </a>
            </div>
        @endif

        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="stat-card">
                    <small class="text-muted fw-bold text-uppercase">Total Reports</small>
                    <h2 class="fw-bold m-0 text-primary">{{ $totalReports }}</h2>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card pending">
                    <small class="text-warning fw-bold text-uppercase">Pending / Active Priority</small>
                    <h2 class="fw-bold m-0 text-warning">{{ $pendingReports }}</h2>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card resolved">
                    <small class="text-success fw-bold text-uppercase">Resolved Issues</small>
                    <h2 class="fw-bold m-0 text-success">{{ $resolvedReports }}</h2>
                </div>
            </div>
        </div>

        @if(!empty($mapLocations))
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold mb-0 text-dark"><i class="bi bi-map text-primary me-2"></i> Community Incident Map</h5>
                        <span class="badge bg-light text-dark border">Live location tracker</span>
                    </div>

                    <div class="row g-3 align-items-stretch">
                        <div class="col-lg-8">
                            <div class="rounded overflow-hidden border" style="height: 360px; background: #e2e8f0;">
                                <iframe
                                    src="https://www.google.com/maps?q={{ urlencode($mapLocations[0]['location']) }}&output=embed"
                                    width="100%"
                                    height="100%"
                                    style="border:0;"
                                    allowfullscreen=""
                                    loading="lazy"
                                    referrerpolicy="no-referrer-when-downgrade"
                                    title="Community Incident Map">
                                </iframe>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="bg-light rounded p-3 h-100 border">
                                <h6 class="fw-bold mb-3 text-dark">Reported Areas</h6>
                                <div class="d-flex flex-column gap-2">
                                    @foreach($mapLocations as $location)
                                        <div class="border rounded p-2 bg-white">
                                            <div class="d-flex justify-content-between align-items-center gap-2">
                                                <span class="fw-semibold small text-dark">{{ $location['category'] }}</span>
                                                <span class="badge {{ $location['status'] === 'Resolved' ? 'bg-success' : 'bg-warning text-dark' }} small">{{ $location['status'] }}</span>
                                            </div>
                                            <div class="text-muted small mt-1">{{ $location['location'] }}</div>
                                            <form action="{{ route('admin.reports.delete', $location['id']) }}" method="POST" class="mt-2" onsubmit="return confirm('Delete this reported area entry?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger btn-sm w-100">
                                                    <i class="bi bi-trash"></i> Delete
                                                </button>
                                            </form>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="card border-0 shadow-sm p-3 mb-4">
            <h5 class="fw-bold mb-3">Citizen Reports Management & Tracking</h5>
            <form action="{{ route('admin.dashboard') }}" method="GET" class="row g-2 align-items-end">
                <div class="col-md-4">
                    <label class="form-label small text-muted mb-1">Search Keywords</label>
                    <input type="text" name="search" class="form-control" placeholder="Search description, location, citizen..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label small text-muted mb-1">Filter by Status</label>
                    <select name="status" class="form-select">
                        <option value="All Statuses">All Statuses</option>
                        <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                        <option value="In Progress" {{ request('status') == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="Resolved" {{ request('status') == 'Resolved' ? 'selected' : '' }}>Resolved</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small text-muted mb-1">Filter by Category</label>
                    <select name="category" class="form-select">
                        <option value="All Categories">All Categories</option>
                        <option value="Self Help" {{ request('category') == 'Self Help' ? 'selected' : '' }}>Self Help</option>
                        <option value="Infrastructure" {{ request('category') == 'Infrastructure' ? 'selected' : '' }}>Infrastructure</option>
                        <option value="Public Safety" {{ request('category') == 'Public Safety' ? 'selected' : '' }}>Public Safety</option>
                        <option value="Health" {{ request('category') == 'Health' ? 'selected' : '' }}>Health</option>
                        <option value="Utilities" {{ request('category') == 'Utilities' ? 'selected' : '' }}>Utilities</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex gap-1">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search me-1"></i> Filter
                    </button>
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary" title="Reset Filters">
                        <i class="bi bi-arrow-counterclockwise"></i>
                    </a>
                </div>
            </form>
        </div>

        @forelse($reports as $report)
            <div class="report-card">
                <div class="row g-3">
                    <div class="col-md-3 border-end">
                        <div class="fw-bold mb-1 text-dark">
                            <i class="bi bi-person-fill text-primary me-1"></i> {{ optional($report->user)->name ?? 'Citizen User' }}
                        </div>
                        <div class="mb-2">
                            <span class="badge-category">{{ $report->category ?? 'General' }}</span>
                        </div>
                        <div class="mb-2">
                            <span class="badge bg-info text-white">
                                <i class="bi bi-hand-thumbs-up me-1"></i> Backed / Me Too: {{ $report->backed_count ?? 0 }}
                            </span>
                        </div>
                        <div class="small text-muted mb-1">
                            <i class="bi bi-geo-alt-fill text-danger me-1"></i> {{ $report->location }}
                        </div>
                        <div class="small text-muted mb-1">
                            <i class="bi bi-calendar3 me-1"></i> {{ $report->created_at ? $report->created_at->format('M d, Y') : 'N/A' }}
                        </div>
                        <div class="small text-muted">
                            <i class="bi bi-telephone-fill me-1"></i> {{ $report->contact_number ?? $report->phone ?? 'N/A' }}
                        </div>
                    </div>

                    <div class="col-md-5 border-end">
                        <p class="mb-2"><strong>Description:</strong> {{ $report->description }}</p>
                        @php
                            $reportPhotos = $report->photoCollection();
                            $reportPrimaryPhoto = $report->firstPhoto();
                        @endphp

                        @if(!empty($reportPhotos))
                            <div class="position-relative d-inline-block mt-2">
                                <a href="#" data-bs-toggle="modal" data-bs-target="#reportPhotoModal{{ $report->id }}" class="d-inline-block">
                                    <img src="{{ asset($reportPrimaryPhoto) }}" class="rounded img-thumbnail" style="max-height: 100px; cursor: pointer;" alt="Report Attachment">
                                </a>
                                <span class="position-absolute bottom-0 start-0 bg-dark text-white px-2 py-1 rounded small opacity-75">
                                    <i class="bi bi-images me-1"></i> {{ count($reportPhotos) }} Photo(s)
                                </span>
                            </div>

                            <div class="modal fade" id="reportPhotoModal{{ $report->id }}" tabindex="-1" aria-labelledby="reportPhotoModalLabel{{ $report->id }}" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-xl">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title fw-bold" id="reportPhotoModalLabel{{ $report->id }}">Citizen Photo Evidence</h5>
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
                                                    <img src="{{ asset($reportPhotos[0]) }}" class="gallery-main-image img-fluid rounded shadow-sm" style="max-height: 60vh; object-fit: contain;" alt="Attached citizen report photo">
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
                                            @if(!empty($reportPrimaryPhoto))
                                                <a href="{{ asset($reportPrimaryPhoto) }}" target="_blank" rel="noopener" class="btn btn-primary btn-sm">
                                                    <i class="bi bi-box-arrow-up-right me-1"></i> Open First Image
                                                </a>
                                            @endif
                                            <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="text-muted small fst-italic mt-2">No photo attached</div>
                        @endif
                    </div>

                    <div class="col-md-4">
                        <form action="{{ route('admin.reports.update', $report->id) }}" method="POST" class="mb-2">
                            @csrf
                            @method('PUT')
                            <div class="mb-2">
                                <label class="form-label small text-muted mb-1 fw-bold">Update Status</label>
                                <select name="status" class="form-select form-select-sm">
                                    <option value="Pending" {{ $report->status == 'Pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="In Progress" {{ $report->status == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                                    <option value="Resolved" {{ $report->status == 'Resolved' ? 'selected' : '' }}>Resolved</option>
                                </select>
                            </div>
                            <div class="mb-2">
                                <label class="form-label small text-muted mb-1 fw-bold">Admin Remarks / Feedback</label>
                                <textarea name="admin_remarks" class="form-control form-control-sm" rows="2" placeholder="Enter remarks...">{{ $report->admin_remarks }}</textarea>
                            </div>
                            <div class="d-flex justify-content-between align-items-center pt-1">
                                <button type="submit" class="btn btn-primary btn-sm px-3">
                                    <i class="bi bi-save me-1"></i> Save Update
                                </button>
                        </form>

                                <form action="{{ route('admin.reports.delete', $report->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this report?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm">
                                        <i class="bi bi-trash"></i> Delete
                                    </button>
                                </form>
                            </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="card border-0 shadow-sm p-5 text-center text-muted">
                <i class="bi bi-inbox fs-1 mb-2"></i>
                <p class="m-0">No submitted reports found matching criteria.</p>
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