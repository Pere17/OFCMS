@extends('layouts.app')

@section('page-title', 'My Complaints')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <form method="GET" class="d-flex gap-2">
            <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                <option value="">All Statuses</option>
                @foreach (['pending' => 'Pending', 'in_progress' => 'In Progress', 'resolved' => 'Resolved', 'rejected' => 'Rejected'] as $value => $label)
                    <option value="{{ $value }}" @selected(request('status') === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </form>
        <a href="{{ route('complainant.complaints.create') }}" class="btn btn-primary btn-sm" style="background-color: var(--ofcms-primary); border-color: var(--ofcms-primary);">
            <i class="bi bi-plus-lg"></i> New Complaint
        </a>
    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead>
                    <tr>
                        <th>Reference</th>
                        <th>Subject</th>
                        <th>Category</th>
                        <th>Status</th>
                        <th>Priority</th>
                        <th>Submitted</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($complaints as $complaint)
                        <tr style="cursor: pointer;" onclick="window.location='{{ route('complainant.complaints.show', $complaint) }}'">
                            <td class="fw-semibold">{{ $complaint->reference_number }}</td>
                            <td>{{ $complaint->subject }}</td>
                            <td>{{ $complaint->category->name }}</td>
                            <td>@include('shared.status-badge', ['status' => $complaint->status])</td>
                            <td>@include('shared.priority-badge', ['priority' => $complaint->priority])</td>
                            <td class="text-muted small">{{ $complaint->created_at->format('d M Y, H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">No complaints found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">
        {{ $complaints->links() }}
    </div>
@endsection
