@extends('layouts.app')

@section('page-title', 'Notifications')

@section('content')
    <div class="d-flex justify-content-end mb-3">
        <form method="POST" action="{{ route('complainant.notifications.read') }}">
            @csrf
            <button type="submit" class="btn btn-outline-secondary btn-sm">Mark All as Read</button>
        </form>
    </div>

    <div class="card">
        <ul class="list-group list-group-flush">
            @forelse ($notifications as $notification)
                <li class="list-group-item {{ $notification->read_at ? '' : 'bg-light' }}">
                    <div class="d-flex justify-content-between">
                        <span>{{ $notification->data['message'] ?? 'Notification' }}</span>
                        <span class="text-muted small">{{ $notification->created_at->format('d M Y, H:i') }}</span>
                    </div>
                </li>
            @empty
                <li class="list-group-item text-center text-muted py-4">No notifications yet.</li>
            @endforelse
        </ul>
    </div>

    <div class="mt-3">
        {{ $notifications->links() }}
    </div>
@endsection
