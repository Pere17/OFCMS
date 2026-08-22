@extends('layouts.app')

@section('page-title', 'Complaint Details')

@section('content')
    <div class="row g-4">
        <div class="col-lg-8">
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
                        <dt class="col-sm-3 text-muted">Category</dt>
                        <dd class="col-sm-9">{{ $complaint->category->name }}</dd>
                        <dt class="col-sm-3 text-muted">Submitted</dt>
                        <dd class="col-sm-9">{{ $complaint->created_at->format('d M Y, H:i') }}</dd>
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
        </div>

        <div class="col-lg-4">
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
