@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card border-0 shadow-sm p-4 rounded-3 bg-white">
        <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
            <h5 class="fw-bold mb-0 text-dark"><i class="bi bi-clock-history text-primary me-2"></i> System Audit Trail</h5>
            <span class="badge bg-light text-dark border px-3 py-2 fw-semibold rounded-pill">{{ $logs->total() ?? 0 }} Total Recorded Event(s)</span>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light text-uppercase fs-7 text-secondary">
                    <tr>
                        <th class="py-3"># ID</th>
                        <th class="py-3">Administrator</th>
                        <th class="py-3">Action Performed</th>
                        <th class="py-3 text-end">Timestamp</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                        <tr>
                            <td class="fw-bold text-secondary">#{{ $log->id }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-person-circle fs-5 text-secondary me-2"></i>
                                    <span class="fw-semibold text-dark">{{ optional($log->user)->name ?? 'System' }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="fw-semibold text-dark">{{ $log->action }}</div>
                                <div class="text-muted small mt-1">{{ $log->description }}</div>
                            </td>
                            <td class="text-end text-muted small"><i class="bi bi-calendar3 me-1"></i> {{ optional($log->created_at)->format('M d, Y - h:i A') }}</td>
                            <td class="text-end">
                                <form action="{{ route('admin.logs.delete', $log->id) }}" method="POST" onsubmit="return confirm('Delete this log entry?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm">
                                        <i class="bi bi-trash"></i> Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center p-5 text-muted">
                                <i class="bi bi-shield-check fs-1 mb-2"></i>
                                <p class="mb-0">No activity logs recorded yet.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4 d-flex justify-content-center">
            {{ $logs->links() }}
        </div>
    </div>
</div>
@endsection