@extends('layouts.staff')

@section('content')
<div class="container-fluid py-4">
    <div class="mb-4">
        <h1 class="h2 fw-bold">Announcements</h1>
        <p class="text-muted mb-0">Publish official notices to all registered citizens.</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success" role="alert">{{ session('success') }}</div>
    @endif

    <div class="row g-4">
        <div class="col-lg-5">
            <form action="{{ route('staff.announcements.store') }}" method="POST" class="card border-0 shadow-sm p-4">
                @csrf
                <h3 class="h5 fw-bold mb-3">Create announcement</h3>
                <div class="mb-3">
                    <label class="form-label small fw-bold" for="title">Title</label>
                    <input id="title" name="title" class="form-control" maxlength="255" required placeholder="e.g. Scheduled power outage">
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold" for="priority">Priority</label>
                    <select id="priority" name="priority" class="form-select" required>
                        <option value="General Notice">General Notice</option>
                        <option value="High Priority">High Priority</option>
                        <option value="Urgent Alert">Urgent Alert</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold" for="content">Message</label>
                    <textarea id="content" name="content" class="form-control" rows="6" maxlength="2000" required placeholder="Write the official notice for citizens"></textarea>
                </div>
                <button type="submit" class="btn btn-primary w-100"><i class="bi bi-megaphone-fill me-1"></i> Publish announcement</button>
            </form>
        </div>

        <div class="col-lg-7">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 px-4 pt-4 d-flex justify-content-between align-items-center">
                    <h3 class="h5 fw-bold mb-0">Published notices</h3>
                    <span class="badge bg-secondary">{{ $announcements->total() }} total</span>
                </div>
                <div class="list-group list-group-flush">
                    @forelse($announcements as $announcement)
                        <div class="list-group-item">
                            <div class="d-flex justify-content-between gap-3">
                                <div>
                                    <h4 class="h6 fw-bold mb-1">{{ $announcement->title }}</h4>
                                    <p class="text-muted mb-2">{{ $announcement->content }}</p>
                                    <small class="text-muted">Posted by {{ $announcement->user?->name ?? 'Staff' }} on {{ $announcement->created_at->format('M d, Y h:i A') }}</small>
                                </div>
                                <span class="badge {{ $announcement->priority === 'Urgent Alert' ? 'bg-danger' : ($announcement->priority === 'High Priority' ? 'bg-warning text-dark' : 'bg-info') }} align-self-start">{{ $announcement->priority }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="list-group-item text-center text-muted py-4">No announcements have been published yet.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
