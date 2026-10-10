<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>File a Report - BantayResolve</title>
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

        .card-custom { 
            background: #ffffff; 
            border: 1px solid #e2e8f0; 
            border-radius: 12px; 
            padding: 28px; 
            box-shadow: 0 1px 3px rgba(0,0,0,0.02); 
        }

        .badge-secure {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            color: #475569;
            font-size: 0.8rem;
            padding: 6px 12px;
            border-radius: 6px;
            font-weight: 500;
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
        <div class="top-navbar">
            <h4 class="fw-bold text-dark m-0">File a New Report</h4>
            <span class="text-secondary small fw-medium">Citizen: {{ Auth::user()->name ?? 'User' }}</span>
        </div>

        <div style="max-width: 950px;">
            <form action="{{ url('/citizen/reports') }}" method="POST" enctype="multipart/form-data" class="card-custom">
                @csrf

                <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-pencil-square text-primary fs-5"></i>
                        <h5 class="fw-bold text-dark m-0">Community Issue Reporting Desk</h5>
                    </div>
                    <span class="badge-secure"><i class="bi bi-shield-check me-1"></i> Secure Submission</span>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-secondary" style="font-size: 0.85rem;">Issue Category <span class="text-danger">*</span></label>
                        <input type="text" name="category" class="form-control" placeholder="e.g., Streetlights & Electricity, Road Hazard..." required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-secondary" style="font-size: 0.85rem;">Specific Barangay / Location Details <span class="text-danger">*</span></label>
                        <input type="text" name="location" class="form-control" placeholder="e.g., gutalid kitaotao bukidnon" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-secondary" style="font-size: 0.85rem;">Contact Number (Optional)</label>
                        <input type="text" name="contact_number" class="form-control" placeholder="e.g., 09171234567">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-secondary" style="font-size: 0.85rem;">Facebook Account Name / Link (Optional)</label>
                        <input type="text" name="facebook_link" class="form-control" placeholder="e.g., facebook.com/username">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold text-secondary" style="font-size: 0.85rem;">Issue Description <span class="text-danger">*</span></label>
                    <textarea name="description" class="form-control" rows="5" placeholder="Describe the community concern clearly..." required></textarea>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold text-secondary" style="font-size: 0.85rem;">Attach Photo Evidence (Multiple allowed)</label>
                    <input type="file" name="photos[]" class="form-control" multiple>
                </div>

                <div class="d-flex justify-content-end gap-2 pt-2">
                    <a href="{{ url('/citizen/dashboard') }}" class="btn btn-light fw-bold text-secondary px-4 border">Cancel</a>
                    <button type="submit" class="btn btn-primary fw-bold px-4"><i class="bi bi-send me-1"></i> Submit Report</button>
                </div>
            </form>
        </div>
        </main>
    </div>

</body>
</html>