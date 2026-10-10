@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card p-4">
            <h4 class="fw-bold mb-3"><i class="bi bi-plus-circle me-2"></i>Submit New Incident Report</h4>
            <hr class="mb-4">

            <form action="{{ route('bantay.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label fw-semibold">Incident Title</label>
                    <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" placeholder="e.g. Broken Streetlight on Main St." required>
                    @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Category</label>
                        <select name="category" class="form-select @error('category') is-invalid @enderror" required>
                            <option value="">Select Category</option>
                            <option value="Infrastructure" {{ old('category') == 'Infrastructure' ? 'selected' : '' }}>Infrastructure</option>
                            <option value="Public Safety" {{ old('category') == 'Public Safety' ? 'selected' : '' }}>Public Safety</option>
                            <option value="Sanitation" {{ old('category') == 'Sanitation' ? 'selected' : '' }}>Sanitation</option>
                            <option value="Noise Complaint" {{ old('category') == 'Noise Complaint' ? 'selected' : '' }}>Noise Complaint</option>
                            <option value="Other" {{ old('category') == 'Other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('category') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Priority Level</label>
                        <select name="priority" class="form-select @error('priority') is-invalid @enderror" required>
                            <option value="Low" {{ old('priority') == 'Low' ? 'selected' : '' }}>Low</option>
                            <option value="Medium" {{ old('priority', 'Medium') == 'Medium' ? 'selected' : '' }}>Medium</option>
                            <option value="High" {{ old('priority') == 'High' ? 'selected' : '' }}>High</option>
                            <option value="Urgent" {{ old('priority') == 'Urgent' ? 'selected' : '' }}>Urgent</option>
                        </select>
                        @error('priority') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Location / Address</label>
                    <input type="text" name="location" class="form-control @error('location') is-invalid @enderror" value="{{ old('location') }}" placeholder="e.g. Purok 3, Near Barangay Hall" required>
                    @error('location') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Detailed Description</label>
                    <textarea name="description" rows="4" class="form-control @error('description') is-invalid @enderror" placeholder="Describe what happened..." required>{{ old('description') }}</textarea>
                    @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Reporter Name</label>
                        <input type="text" name="reporter_name" class="form-control @error('reporter_name') is-invalid @enderror" value="{{ old('reporter_name') }}" placeholder="Your Full Name" required>
                        @error('reporter_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Contact Info (Optional)</label>
                        <input type="text" name="reporter_contact" class="form-control @error('reporter_contact') is-invalid @enderror" value="{{ old('reporter_contact') }}" placeholder="Phone or Email">
                        @error('reporter_contact') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('bantay.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-send me-1"></i> Submit Report</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection