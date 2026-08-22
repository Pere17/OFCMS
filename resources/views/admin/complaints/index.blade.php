@extends('layouts.app')

@section('page-title', 'Complaints')

@section('content')
    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" class="row g-2 align-items-end">
                <div class="col-md-3">
                    <label class="form-label small text-muted">Search</label>
                    <input type="text" name="search" class="form-control form-control-sm" placeholder="Reference or subject" value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label small text-muted">Status</label>
                    <select name="status" class="form-select form-select-sm">
                        <option value="">All</option>
                        @foreach (['pending' => 'Pending', 'in_progress' => 'In Progress', 'resolved' => 'Resolved', 'rejected' => 'Rejected'] as $value => $label)
                            <option value="{{ $value }}" @selected(request('status') === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small text-muted">Category</label>
                    <select name="category_id" class="form-select form-select-sm">
                        <option value="">All</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" @selected(request('category_id') == $category->id)>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small text-muted">Priority</label>
                    <select name="priority" class="form-select form-select-sm">
                        <option value="">All</option>
                        @foreach (['low' => 'Low', 'medium' => 'Medium', 'high' => 'High'] as $value => $label)
                            <option value="{{ $value }}" @selected(request('priority') === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small text-muted">From</label>
                    <input type="date" name="date_from" class="form-control form-control-sm" value="{{ request('date_from') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label small text-muted">To</label>
                    <input type="date" name="date_to" class="form-control form-control-sm" value="{{ request('date_to') }}">
                </div>
                <div class="col-md-1">
                    <button type="submit" class="btn btn-primary btn-sm w-100" style="background-color: var(--ofcms-primary); border-color: var(--ofcms-primary);">Filter</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead>
                    <tr>
                        <th>Reference</th>
                        <th>Complainant</th>
                        <th>Category</th>
                        <th>Status</th>
                        <th>Priority</th>
                        <th>Submitted</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($complaints as $complaint)
                        <tr style="cursor: pointer;" onclick="window.location='{{ route('admin.complaints.show', $complaint) }}'">
                            <td class="fw-semibold">{{ $complaint->reference_number }}</td>
                            <td>{{ $complaint->user->name }}</td>
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
