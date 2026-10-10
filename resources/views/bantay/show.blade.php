@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <span class="badge bg-secondary mb-1">{{ $report->ticket_number }}</span>
                    <h3 class="fw-bold text-dark m-0">{{ $report->title }}</h3>
                </div>
                <div>
                    <a href="{{ route('bantay.edit', $report) }}" class="btn btn-warning btn-sm"><i class="bi bi-pencil me-1"></i> Edit</a>
                    <a href="{{ route('bantay.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i> Back</a>
                </div>
            </div>

            <hr>

            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <small class="text-muted d-block fw-semibold">Category</small>
                    <span>{{ $report->category }}</span>
                </div>
                <div class="col-md-3">
                    <small class="text-muted d-block fw-semibold">Priority</small>
                    <span class="badge bg-danger">{{ $report->priority }}</span>
                </div>
                <div class="col-md-3">
                    <small class="text-muted d-block fw-semibold">Status</small>
                    <span class="badge bg-primary">{{ $report->status }}</span>
                </div>
                <div class="col-md-3">
                    <small class="text-muted d-block fw-semibold">Date Reported</small>
                    <span>{{ $report->created_at->format('M d, Y h:i A') }}</span>
                </div>
            </div>

            <div class="mb-4">
                <small class="text-muted d-block fw-semibold">Location</small>
                <p class="fs-6"><i class="bi bi-geo-alt text-danger me-1"></i> {{ $report->location }}</p>
            </div>

            <div class="mb-4">
                <small class="text-muted d-block fw-semibold">Description</small>
                <div class="p-3 bg-light rounded border">{{ $report->description }}</div>
            </div>

            <div class="row mb-4">
                <div class="col-md-6">
                    <small class="text-muted d-block fw-semibold">Reporter Name</small>
                    <span>{{ $report->reporter_name }}</span>
                </div>
                <div class="col-md-6">
                    <small class="text-muted d-block fw-semibold">Reporter Contact</small>
                    <span>{{ $report->reporter_contact ?? 'N/A' }}</span>
                </div>
            </div>

            @if($report->resolution_notes)
                <div class="p-3 bg-success-subtle border border-success rounded">
                    <h6 class="fw-bold text-success mb-1"><i class="bi bi-check-circle me-1"></i> Resolution Details</h6>
                    <p class="mb-1">{{ $report->resolution_notes }}</p>
                    @if($report->resolved_at)
                        <small class="text-muted">Resolved on: {{ $report->resolved_at->format('M d, Y h:i A') }}</small>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>
@endsection