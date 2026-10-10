@extends('layouts.staff')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
        <div>
            <h1 class="h2 fw-bold">Community Reports</h1>
            <p class="text-muted mb-0">Review evidence, update status, and provide citizen feedback.</p>
        </div>
        <a href="{{ route('staff.dashboard') }}" class="btn btn-outline-secondary mt-3 mt-md-0"><i class="bi bi-arrow-left me-1"></i> Back to dashboard</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success" role="alert">{{ session('success') }}</div>
    @endif

    <form method="GET" action="{{ route('staff.reports') }}" class="card border-0 shadow-sm mb-4 p-3">
        <div class="row g-3 align-items-end">
            <div class="col-md-6">
                <label class="form-label small fw-bold text-muted" for="search">Search</label>
                <input id="search" name="search" value="{{ request('search') }}" class="form-control" placeholder="Citizen, issue, location, or description">
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-bold text-muted" for="category">Category</label>
                <select id="category" name="category" class="form-select">
                    <option value="">All categories</option>
                    <option value="Infrastructure" {{ request('category') === 'Infrastructure' ? 'selected' : '' }}>Infrastructure</option>
                    <option value="Sanitation" {{ request('category') === 'Sanitation' ? 'selected' : '' }}>Sanitation</option>
                    <option value="Public Safety" {{ request('category') === 'Public Safety' ? 'selected' : '' }}>Public Safety</option>
                </select>
            </div>
            <div class="col-md-3">
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
            <div class="col-md-3">
                <label class="form-label small fw-bold text-muted" for="date">Report date</label>
                <input id="date" name="date" type="date" value="{{ request('date') }}" class="form-control">
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-grow-1"><i class="bi bi-search me-1"></i> Filter</button>
                <a href="{{ route('staff.reports') }}" class="btn btn-outline-secondary" aria-label="Reset filters"><i class="bi bi-arrow-counterclockwise"></i></a>
            </div>
        </div>
    </form>

    @forelse($reports as $report)
        <article class="card border-0 shadow-sm mb-3">
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-lg-4">
                        <div class="d-flex align-items-center mb-2">
                            <i class="bi bi-person-circle-fill text-primary fs-3 me-2"></i>
                            <div><strong>{{ $report->user?->name ?? 'Anonymous Citizen' }}</strong><br><small class="text-muted">Report #{{ $report->id }}</small></div>
                        </div>
                        <span class="badge bg-primary mb-2">{{ $report->category }}</span>
                        <div class="text-muted small mb-1"><i class="bi bi-geo-alt me-1"></i>{{ $report->location }}</div>
                        <div class="text-muted small mb-1"><i class="bi bi-calendar3 me-1"></i>{{ $report->created_at->format('M d, Y h:i A') }}</div>
                        @if($report->contact_number)
                            <div class="text-muted small"><i class="bi bi-telephone me-1"></i>{{ $report->contact_number }}</div>
                        @endif
                    </div>
                    <div class="col-lg-4">
                        <h3 class="h6 fw-bold">Issue description</h3>
                        <p class="text-muted mb-0">{{ $report->description }}</p>
                        @if($report->photo)
                            <img src="{{ asset($report->firstPhoto()) }}" class="img-thumbnail rounded mt-3" style="max-height: 130px;" alt="Report evidence">
                        @else
                            <p class="text-muted small fst-italic mt-3">No evidence photo attached.</p>
                        @endif
                    </div>
                    <div class="col-lg-4">
                        <form action="{{ route('staff.reports.update', $report) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <label class="form-label small fw-bold" for="status-{{ $report->id }}">Update status</label>
                            <select id="status-{{ $report->id }}" name="status" class="form-select mb-3">
                                <option value="Pending" {{ $report->status === 'Pending' ? 'selected' : '' }}>Pending</option>
                                <option value="Verified" {{ $report->status === 'Verified' ? 'selected' : '' }}>Verified</option>
                                <option value="In Progress" {{ $report->status === 'In Progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="Resolved" {{ $report->status === 'Resolved' ? 'selected' : '' }}>Resolved</option>
                                <option value="Rejected" {{ $report->status === 'Rejected' ? 'selected' : '' }}>Rejected</option>
                            </select>
                            <label class="form-label small fw-bold" for="responder-{{ $report->id }}">Responder / department</label>
                            <input id="responder-{{ $report->id }}" name="responder_name" value="{{ $report->responder_name }}" maxlength="255" class="form-control mb-3" placeholder="e.g. Barangay Safety Team">
                            <label class="form-label small fw-bold" for="remarks-{{ $report->id }}">Staff remarks</label>
                            <textarea id="remarks-{{ $report->id }}" name="admin_remarks" rows="4" maxlength="1000" class="form-control mb-3">{{ $report->admin_remarks }}</textarea>
                            <button type="submit" class="btn btn-primary w-100"><i class="bi bi-save me-1"></i> Save update</button>
                        </form>

                        @if($report->statusHistory->isNotEmpty())
                            <div class="mt-4 border-top pt-3">
                                <h4 class="h6 fw-bold mb-2">Status history</h4>
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
                                            <small class="text-muted">By {{ $history->user?->name ?? 'Administrator' }}</small>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </article>
    @empty
        <div class="card border-0 shadow-sm p-5 text-center text-muted"><i class="bi bi-inbox fs-1 d-block mb-2"></i>No reports match the selected filters.</div>
    @endforelse

    <div class="d-flex justify-content-center mt-3">
        {{ $reports->links() }}
    </div>
</div>
@endsection
