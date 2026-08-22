@extends('layouts.app')

@section('page-title', 'Complaint Details')

@section('content')
    <div class="row g-4">
        <div class="col-lg-7">
            <div class="card">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h5 class="fw-semibold mb-1">{{ $complaint->subject }}</h5>
                            <div class="text-muted small">Reference: {{ $complaint->reference_number }}</div>
                        </div>
                        <div class="d-flex gap-2">
                            @include('shared.status-badge', ['status' => $complaint->status])
                            @include('shared.priority-badge', ['priority' => $complaint->priority])
                        </div>
                    </div>

                    <dl class="row small mb-4">
                        <dt class="col-sm-3 text-muted">Complainant</dt>
                        <dd class="col-sm-9">{{ $complaint->user->name }} ({{ $complaint->user->email }})</dd>
                        <dt class="col-sm-3 text-muted">Category</dt>
                        <dd class="col-sm-9">{{ $complaint->category->name }}</dd>
                        <dt class="col-sm-3 text-muted">Submitted</dt>
                        <dd class="col-sm-9">{{ $complaint->created_at->format('d M Y, H:i') }}</dd>
                        <dt class="col-sm-3 text-muted">Assigned To</dt>
                        <dd class="col-sm-9">{{ $complaint->assignedTo->name ?? 'Unassigned' }}</dd>
                        @if ($complaint->resolved_at)
                            <dt class="col-sm-3 text-muted">Resolved</dt>
                            <dd class="col-sm-9">{{ $complaint->resolved_at->format('d M Y, H:i') }}</dd>
                        @endif
                    </dl>

                    <h6 class="fw-semibold">Description</h6>
                    <p>{{ $complaint->description }}</p>

                    @if ($complaint->attachment)
                        <a href="{{ asset('storage/' . $complaint->attachment) }}" target="_blank" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-paperclip"></i> View Attachment
                        </a>
                    @endif
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-body p-4">
                    <h6 class="fw-semibold mb-3">Update Status</h6>
                    <form method="POST" action="{{ route('admin.complaints.status', $complaint) }}" class="row g-2">
                        @csrf
                        @method('PATCH')
                        <div class="col-md-4">
                            <label class="form-label small text-muted">Status</label>
                            <select name="status" class="form-select form-select-sm">
                                @foreach (['pending' => 'Pending', 'in_progress' => 'In Progress', 'resolved' => 'Resolved', 'rejected' => 'Rejected'] as $value => $label)
                                    <option value="{{ $value }}" @selected($complaint->status === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small text-muted">Priority</label>
                            <select name="priority" class="form-select form-select-sm">
                                @foreach (['low' => 'Low', 'medium' => 'Medium', 'high' => 'High'] as $value => $label)
                                    <option value="{{ $value }}" @selected($complaint->priority === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small text-muted">Assign To</label>
                            <select name="assigned_to" class="form-select form-select-sm">
                                <option value="">Unassigned</option>
                                @foreach ($admins as $admin)
                                    <option value="{{ $admin->id }}" @selected($complaint->assigned_to === $admin->id)>{{ $admin->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary btn-sm mt-2" style="background-color: var(--ofcms-primary); border-color: var(--ofcms-primary);">
                                Update
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-body p-4">
                    <h6 class="fw-semibold mb-3">Add Response</h6>
                    <form method="POST" action="{{ route('admin.complaints.respond', $complaint) }}">
                        @csrf
                        <textarea name="message" rows="4" class="form-control @error('message') is-invalid @enderror" required minlength="5" placeholder="Write your response..."></textarea>
                        @error('message')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                        <div class="form-check mt-2">
                            <input type="checkbox" name="resolve" id="resolve" value="1" class="form-check-input">
                            <label class="form-check-label" for="resolve">Mark complaint as resolved</label>
                        </div>

                        <button type="submit" class="btn btn-primary btn-sm mt-3" style="background-color: var(--ofcms-primary); border-color: var(--ofcms-primary);">
                            Submit Response
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card">
                <div class="card-body p-4">
                    <h6 class="fw-semibold mb-3">Response Timeline</h6>
                    <ul class="timeline">
                        @forelse ($complaint->responses as $response)
                            <li class="timeline-item">
                                <div class="fw-semibold small">{{ $response->admin->name }}</div>
                                <div class="text-muted" style="font-size: 0.75rem;">{{ $response->created_at->format('d M Y, H:i') }}</div>
                                <p class="small mb-0 mt-1">{{ $response->message }}</p>
                            </li>
                        @empty
                            <li class="text-muted small">No responses yet.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
