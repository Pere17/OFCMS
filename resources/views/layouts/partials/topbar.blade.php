@php
    $user = auth()->user();
    $unreadNotifications = $user->unreadNotifications()->latest()->take(5)->get();
    $unreadCount = $user->unreadNotifications()->count();
    $notificationsRoute = $user->isComplainant() ? route('complainant.notifications') : route('admin.notifications');
@endphp
<nav class="ofcms-topbar navbar navbar-expand px-4 py-3">
    <div class="container-fluid px-0">
        <span class="fs-5 fw-semibold text-dark">@yield('page-title', 'Dashboard')</span>

        <div class="d-flex align-items-center ms-auto gap-3">
            <div class="dropdown">
                <button class="btn btn-light position-relative" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-bell fs-5"></i>
                    @if ($unreadCount > 0)
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            {{ $unreadCount }}
                        </span>
                    @endif
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow" style="min-width: 320px;">
                    <li><h6 class="dropdown-header">Notifications</h6></li>
                    @forelse ($unreadNotifications as $notification)
                        <li>
                            <span class="dropdown-item small text-wrap">
                                {{ $notification->data['message'] ?? 'New notification' }}
                                <br>
                                <span class="text-muted" style="font-size: 0.75rem;">{{ $notification->created_at->format('d M Y, H:i') }}</span>
                            </span>
                        </li>
                    @empty
                        <li><span class="dropdown-item small text-muted">No new notifications</span></li>
                    @endforelse
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item text-center small" href="{{ $notificationsRoute }}">View All</a></li>
                </ul>
            </div>

            <div class="dropdown">
                <button class="btn btn-light d-flex align-items-center" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-person-circle fs-5 me-2"></i>
                    {{ $user->name }}
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow">
                    <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Profile</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item">Log Out</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>
