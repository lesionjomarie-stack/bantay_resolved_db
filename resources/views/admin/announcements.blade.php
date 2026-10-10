@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h3 class="fw-bold mb-1">Post Official Announcement</h3>
    <p class="text-muted small mb-4">Broadcast important updates directly to the citizen portal.</p>

    @if(session('success'))
        <div class="alert alert-success border-0 mb-4" style="background:#ecfdf5; color:#065f46;">
            {{ session('success') }}
        </div>
    @endif

    <!-- Announcement Form -->
    <form action="{{ route('admin.store_announcement') }}" method="POST" class="card p-4 border-0 shadow-sm mb-4 rounded-3 bg-white">
        @csrf
        <div class="row g-3">
            <div class="col-md-8">
                <label class="form-label fw-semibold text-secondary small">ANNOUNCEMENT TITLE</label>
                <input type="text" name="title" class="form-control" placeholder="e.g. Scheduled Power Outage / Road Repairs" required>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold text-secondary small">PRIORITY LEVEL</label>
                <select name="priority" class="form-select" required>
                    <option value="General Notice">General Notice</option>
                    <option value="High Priority">High Priority</option>
                    <option value="Urgent Alert">Urgent Alert</option>
                </select>
            </div>
            <div class="col-12">
                <label class="form-label fw-semibold text-secondary small">ANNOUNCEMENT CONTENT</label>
                <textarea name="content" class="form-control" rows="4" placeholder="Write detailed notice here..." required></textarea>
            </div>
            <div class="col-12 text-end">
                <button type="submit" class="btn btn-primary px-4 fw-semibold">Publish Announcement</button>
            </div>
        </div>
    </form>

    <!-- Published List -->
    <h5 class="fw-bold mb-3">Published Announcements</h5>
    <div class="card border-0 shadow-sm p-4 bg-white">
        @forelse($announcements as $announcement)
            <div class="border rounded-3 p-4 mb-3 bg-light-subtle">
                <div class="d-flex justify-content-between align-items-start gap-3 mb-2">
                    <div>
                        <h6 class="fw-bold mb-1 text-dark">{{ $announcement->title }}</h6>
                        <div class="small text-muted">Posted by {{ optional($announcement->user)->name ?? 'Administrator' }} • {{ $announcement->created_at ? $announcement->created_at->format('M d, Y h:i A') : '' }}</div>
                    </div>
                    <span class="badge rounded-pill {{ $announcement->priority === 'Urgent Alert' ? 'bg-danger' : ($announcement->priority === 'High Priority' ? 'bg-warning text-dark' : 'bg-info text-dark') }} px-3 py-2">
                        {{ $announcement->priority }}
                    </span>
                </div>
                <p class="mb-0 text-secondary">{{ $announcement->content }}</p>
            </div>
        @empty
            <div class="text-center text-muted py-4">
                No official announcements published yet.
            </div>
        @endforelse
    </div>
</div>
@endsection