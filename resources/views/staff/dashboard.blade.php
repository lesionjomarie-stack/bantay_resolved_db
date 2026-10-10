@extends('layouts.staff')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
        <div>
            <p class="text-uppercase text-primary fw-bold small mb-1">Operations center</p>
            <h1 class="h2 fw-bold mb-1">Staff Dashboard</h1>
            <p class="text-muted mb-0">Review citizen reports and publish official updates.</p>
        </div>
        <div class="mt-3 mt-md-0">
            <span class="badge rounded-pill bg-primary px-3 py-2">
                <i class="bi bi-person-circle me-1"></i>{{ Auth::user()->name }}
            </span>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success" role="alert">{{ session('success') }}</div>
    @endif

    <div class="row g-3 mb-4">
        <div class="col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted text-uppercase small fw-bold">Total Reports</span>
                        <i class="bi bi-file-text-fill text-primary fs-3"></i>
                    </div>
                    <h2 class="display-6 fw-bold text-primary mt-2" data-metric="total">{{ $totalReports }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted text-uppercase small fw-bold">Pending</span>
                        <i class="bi bi-exclamation-triangle-fill text-warning fs-3"></i>
                    </div>
                    <h2 class="display-6 fw-bold text-warning mt-2" data-metric="pending">{{ $pendingReports }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted text-uppercase small fw-bold">In Progress</span>
                        <i class="bi bi-gear-wide-connected text-info fs-3"></i>
                    </div>
                    <h2 class="display-6 fw-bold text-info mt-2" data-metric="in-progress">{{ $inProgressReports }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted text-uppercase small fw-bold">Resolved</span>
                        <i class="bi bi-check-circle-fill text-success fs-3"></i>
                    </div>
                    <h2 class="display-6 fw-bold text-success mt-2" data-metric="resolved">{{ $resolvedReports }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted text-uppercase small fw-bold">Rejected</span>
                        <i class="bi bi-x-circle-fill text-danger fs-3"></i>
                    </div>
                    <h2 class="display-6 fw-bold text-danger mt-2" data-metric="rejected">{{ $rejectedReports }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <a href="{{ route('staff.reports') }}" class="card border-0 shadow-sm h-100 text-decoration-none text-reset">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted text-uppercase small fw-bold">Review Queue</span>
                        <i class="bi bi-arrow-right-circle text-secondary fs-3"></i>
                    </div>
                    <h2 class="display-6 fw-bold text-secondary mt-2">Open Queue</h2>
                    <small class="text-muted">View and update all reports</small>
                </div>
            </a>
        </div>
    </div>

    <form method="GET" action="{{ route('staff.dashboard') }}" class="card border-0 shadow-sm mb-4 p-3" aria-label="Filter community reports">
        <div class="row g-3 align-items-end">
            <div class="col-lg-3">
                <label class="form-label small fw-bold text-muted" for="search">Search</label>
                <input id="search" name="search" value="{{ request('search') }}" class="form-control" placeholder="Citizen, issue, location, or description">
            </div>
            <div class="col-lg-2">
                <label class="form-label small fw-bold text-muted" for="category">Category</label>
                <select id="category" name="category" class="form-select">
                    <option value="">All categories</option>
                    <option value="Infrastructure" {{ request('category') === 'Infrastructure' ? 'selected' : '' }}>Infrastructure</option>
                    <option value="Sanitation" {{ request('category') === 'Sanitation' ? 'selected' : '' }}>Sanitation</option>
                    <option value="Public Safety" {{ request('category') === 'Public Safety' ? 'selected' : '' }}>Public Safety</option>
                </select>
            </div>
            <div class="col-lg-2">
                <label class="form-label small fw-bold text-muted" for="status">Status</label>
                <select id="status" name="status" class="form-select">
                    <option value="">All statuses</option>
                    <option value="Pending" {{ request('status') === 'Pending' ? 'selected' : '' }}>Pending</option>
                    <option value="Verified" {{ request('status') === 'Verified' ? 'selected' : '' }}>Verified</option>
                    <option value="In Progress" {{ request('status') === 'In Progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="Resolved" {{ request('status') === 'Resolved' ? 'selected' : '' }}>Resolved</option>
                    <option value="Rejected" {{ request('status') === 'Rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
            </div>
            <div class="col-lg-2">
                <label class="form-label small fw-bold text-muted" for="date">Report date</label>
                <input id="date" name="date" type="date" value="{{ request('date') }}" class="form-control">
            </div>
            <div class="col-lg-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-grow-1"><i class="bi bi-search me-1"></i>Filter reports</button>
                <a href="{{ route('staff.dashboard') }}" class="btn btn-outline-secondary" aria-label="Reset filters"><i class="bi bi-arrow-counterclockwise"></i></a>
            </div>
        </div>
    </form>

    <div class="row g-4">
        <div class="col-xl-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 px-4 pt-4 d-flex justify-content-between align-items-center">
                    <h3 class="h5 fw-bold mb-0">Community Reports</h3>
                    <a href="{{ route('staff.reports') }}" class="btn btn-sm btn-outline-primary">View all</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr><th>Citizen</th><th>Issue</th><th>Location</th><th>Status</th><th class="text-end">View / Action</th></tr>
                        </thead>
                        <tbody>
                            @forelse($reports as $report)
                                @php($statusClass = match ($report->status) {
                                    'Resolved' => 'bg-success',
                                    'Rejected' => 'bg-danger',
                                    'In Progress' => 'bg-info',
                                    'Verified' => 'bg-primary',
                                    default => 'bg-warning text-dark',
                                })
                                <tr>
                                    <td class="fw-semibold">{{ $report->user?->name ?? 'Anonymous Citizen' }}</td>
                                    <td><span class="badge bg-primary">{{ $report->category }}</span></td>
                                    <td class="text-muted small">{{ $report->location }}</td>
                                    <td>
                                        <span class="badge {{ $statusClass }}">
                                            {{ $report->status }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#report-detail-{{ $report->id }}">
                                            <i class="bi bi-eye me-1"></i>View / Action
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="text-center text-muted py-4">No reports match the selected filters.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 px-4 pt-4">
                    <h3 class="h5 fw-bold mb-0">Recent Announcements</h3>
                </div>
                <div class="list-group list-group-flush">
                    @forelse($recentAnnouncements as $announcement)
                        <div class="list-group-item">
                            <div class="d-flex justify-content-between gap-2">
                                <strong class="small">{{ $announcement->title }}</strong>
                                <span class="badge {{ $announcement->priority === 'Urgent Alert' ? 'bg-danger' : ($announcement->priority === 'High Priority' ? 'bg-warning text-dark' : 'bg-info') }}">{{ $announcement->priority }}</span>
                            </div>
                            <small class="text-muted">{{ $announcement->created_at->format('M d, h:i A') }}</small>
                        </div>
                    @empty
                        <div class="list-group-item text-center text-muted small">No announcements yet.</div>
                    @endforelse
                </div>
                <div class="card-body border-top">
                    <a href="{{ route('staff.announcements') }}" class="btn btn-primary w-100">Publish Announcement</a>
                </div>
            </div>
        </div>
    </div>

    @foreach($reports as $report)
        <div class="modal fade" id="report-detail-{{ $report->id }}" tabindex="-1" aria-labelledby="report-detail-title-{{ $report->id }}" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <div>
                            <span class="badge bg-primary">Report #{{ $report->id }}</span>
                            <h2 class="modal-title fs-5 mt-2" id="report-detail-title-{{ $report->id }}">{{ $report->user?->name ?? 'Anonymous Citizen' }}</h2>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-4">
                            <div class="col-lg-7">
                                <div class="card border-0 bg-light mb-3">
                                    <div class="card-body">
                                        <h3 class="h6 fw-bold">Citizen description</h3>
                                        <p class="mb-0">{{ $report->description }}</p>
                                    </div>
                                </div>
                                <div class="card border-0 bg-light mb-3">
                                    <div class="card-body">
                                        <h3 class="h6 fw-bold">Exact location</h3>
                                        <p class="mb-0"><i class="bi bi-geo-alt me-2"></i>{{ $report->location }}</p>
                                    </div>
                                </div>
                                <div class="card border-0 bg-light">
                                    <div class="card-body">
                                        <h3 class="h6 fw-bold">Uploaded evidence</h3>
                                        @if($report->hasPhotos())
                                            <div class="row g-3">
                                                @foreach($report->photoCollection() as $photo)
                                                    <div class="col-md-6">
                                                        <img src="{{ asset($photo) }}" class="img-fluid rounded border" alt="Evidence for report #{{ $report->id }}">
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <p class="text-muted mb-0">No photo evidence was uploaded.</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-5">
                                <form action="{{ route('staff.reports.update', $report) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="mb-3">
                                        <label class="form-label small fw-bold" for="detail-status-{{ $report->id }}">Update status</label>
                                        <select id="detail-status-{{ $report->id }}" name="status" class="form-select">
                                            <option value="Pending" {{ $report->status === 'Pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="Verified" {{ $report->status === 'Verified' ? 'selected' : '' }}>Verified</option>
                                            <option value="In Progress" {{ $report->status === 'In Progress' ? 'selected' : '' }}>In Progress</option>
                                            <option value="Resolved" {{ $report->status === 'Resolved' ? 'selected' : '' }}>Resolved</option>
                                            <option value="Rejected" {{ $report->status === 'Rejected' ? 'selected' : '' }}>Rejected</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label small fw-bold" for="detail-responder-{{ $report->id }}">Responder / department</label>
                                        <input id="detail-responder-{{ $report->id }}" name="responder_name" value="{{ $report->responder_name }}" maxlength="255" class="form-control" placeholder="e.g. Barangay Safety Team">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label small fw-bold" for="detail-remarks-{{ $report->id }}">Staff remarks</label>
                                        <textarea id="detail-remarks-{{ $report->id }}" name="admin_remarks" rows="5" maxlength="1000" class="form-control">{{ $report->admin_remarks }}</textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary w-100"><i class="bi bi-save me-1"></i>Save report action</button>
                                </form>
                                @if($report->statusHistory->isNotEmpty())
                                    <div class="mt-4 border-top pt-3">
                                        <h3 class="h6 fw-bold">Status history</h3>
                                        <div class="list-group list-group-flush">
                                            @foreach($report->statusHistory as $history)
                                                <div class="list-group-item px-0">
                                                    <div class="d-flex justify-content-between gap-2">
                                                        <strong class="small">{{ $history->from_status }} → {{ $history->to_status }}</strong>
                                                        <small class="text-muted">{{ $history->created_at->format('M d, Y h:i A') }}</small>
                                                    </div>
                                                    @if($history->remarks)
                                                        <small class="text-muted d-block mt-1">{{ $history->remarks }}</small>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
@endsection
