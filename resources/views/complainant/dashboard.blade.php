@extends('layouts.app')

@section('page-title', 'Dashboard')

@section('content')
    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-lg-3">
            <div class="card stat-card stat-total h-100">
                <div class="card-body">
                    <div class="text-muted small">Total Complaints</div>
                    <div class="fs-3 fw-bold">{{ $stats['total'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card stat-card stat-pending h-100">
                <div class="card-body">
                    <div class="text-muted small">Pending</div>
                    <div class="fs-3 fw-bold">{{ $stats['pending'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card stat-card stat-in-progress h-100">
                <div class="card-body">
                    <div class="text-muted small">In Progress</div>
                    <div class="fs-3 fw-bold">{{ $stats['in_progress'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card stat-card stat-resolved h-100">
                <div class="card-body">
                    <div class="text-muted small">Resolved</div>
                    <div class="fs-3 fw-bold">{{ $stats['resolved'] }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-semibold mb-0">Recent Complaints</h5>
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
                    @forelse ($recentComplaints as $complaint)
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
                            <td colspan="6" class="text-center text-muted py-4">No complaints submitted yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
