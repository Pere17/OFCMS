@extends('layouts.app')

@section('page-title', 'Feedback')

@section('content')
    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" class="d-flex gap-2 align-items-end">
                <div>
                    <label class="form-label small text-muted">Rating</label>
                    <select name="rating" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="">All Ratings</option>
                        @for ($i = 5; $i >= 1; $i--)
                            <option value="{{ $i }}" @selected(request('rating') == $i)>{{ $i }} Star{{ $i > 1 ? 's' : '' }}</option>
                        @endfor
                    </select>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead>
                    <tr>
                        <th>Submitted By</th>
                        <th>Subject</th>
                        <th>Message</th>
                        <th>Rating</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($feedback as $item)
                        <tr>
                            <td>{{ $item->is_anonymous ? 'Anonymous' : $item->user->name }}</td>
                            <td>{{ $item->subject }}</td>
                            <td class="text-truncate" style="max-width: 300px;">{{ $item->message }}</td>
                            <td>
                                @for ($i = 1; $i <= 5; $i++)
                                    <i class="bi bi-star{{ $i <= $item->rating ? '-fill' : '' }}" style="color: var(--ofcms-accent);"></i>
                                @endfor
                            </td>
                            <td class="text-muted small">{{ $item->created_at->format('d M Y, H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">No feedback submitted yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">
        {{ $feedback->links() }}
    </div>
@endsection
