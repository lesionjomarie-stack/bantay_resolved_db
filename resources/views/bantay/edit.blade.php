@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="fw-bold m-0"><i class="bi bi-pencil-square me-2"></i>Update Incident Report</h4>
                <span class="badge bg-primary fs-6">{{ $report->ticket_number }}</span>
            </div>
            <hr class="mb-4">

            <form action="{{ route('bantay.update', $report) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label fw-semibold">Incident Title</label>
                    <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $report->title) }}" required>
                    @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Category</label>
                        <select name="category" class="form-select @error('category') is-invalid @enderror" required>
                            @foreach(['Infrastructure', 'Public Safety', 'Sanitation', 'Noise Complaint', 'Other'] as $cat)
                                <option value="{{ $cat }}" {{ old('category', $report->category) == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                            @endforeach
                        </select>
                        @error('category') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Priority Level</label>
                        <select name="priority" class="form-select @error('priority') is-invalid @enderror" required>
                            @foreach(['Low', 'Medium', 'High', 'Urgent'] as $pr)
                                <option value="{{ $pr }}" {{ old('priority', $report->priority) == $pr ? 'selected' : '' }}>{{ $pr }}</option>
                            @endforeach
                        </select>
                        @error('priority') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Status</label>
                        <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                            @foreach(['Pending', 'Under Review', 'In Progress', 'Resolved', 'Dismissed'] as $st)
                                <option value="{{ $st }}" {{ old('status', $report->status) == $st ? 'selected' : '' }}>{{ $st }}</option>
                            @endforeach
                        </select>
                        @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Location / Address</label>
                    <input type="text" name="location" class="form-control @error('location') is-invalid @enderror" value="{{ old('location', $report->location) }}" required>
                    @error('location') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Detailed Description</label>
                    <textarea name="description" rows="4" class="form-control @error('description') is-invalid @enderror" required>{{ old('description', $report->description) }}</textarea>
                    @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Reporter Name</label>
                        <input type="text" name="reporter_name" class="form-control @error('reporter_name') is-invalid @enderror" value="{{ old('reporter_name', $report->reporter_name) }}" required>
                        @error('reporter_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Contact Info</label>
                        <input type="text" name="reporter_contact" class="form-control @error('reporter_contact') is-invalid @enderror" value="{{ old('reporter_contact', $report->reporter_contact) }}">
                        @error('reporter_contact') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold">Resolution Notes</label>
                    <textarea name="resolution_notes" rows="3" class="form-control @error('resolution_notes') is-invalid @enderror" placeholder="Add actions taken or resolution details here...">{{ old('resolution_notes', $report->resolution_notes) }}</textarea>
                    @error('resolution_notes') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('bantay.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    <button type="submit" class="btn btn-warning"><i class="bi bi-save me-1"></i> Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection