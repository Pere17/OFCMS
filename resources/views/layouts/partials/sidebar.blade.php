@php
    $user = auth()->user();
@endphp
<div class="ofcms-sidebar d-flex flex-column flex-shrink-0 p-3">
    <a href="/" class="navbar-brand d-flex align-items-center mb-4 text-decoration-none">
        <i class="bi bi-shield-check fs-4 me-2"></i>
        <span class="fs-5 fw-semibold">OFCMS</span>
    </a>

    <ul class="nav nav-pills flex-column mb-auto">
        @if ($user->isComplainant())
            <li class="nav-item">
                <a href="{{ route('complainant.dashboard') }}" class="nav-link {{ request()->routeIs('complainant.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2 me-2"></i> Dashboard
                </a>
            </li>
            <li>
                <a href="{{ route('complainant.complaints.index') }}" class="nav-link {{ request()->routeIs('complainant.complaints.*') ? 'active' : '' }}">
                    <i class="bi bi-envelope-paper me-2"></i> My Complaints
                </a>
            </li>
            <li>
                <a href="{{ route('complainant.feedback.create') }}" class="nav-link {{ request()->routeIs('complainant.feedback.*') ? 'active' : '' }}">
                    <i class="bi bi-star me-2"></i> Give Feedback
                </a>
            </li>
            <li>
                <a href="{{ route('complainant.notifications') }}" class="nav-link {{ request()->routeIs('complainant.notifications') ? 'active' : '' }}">
                    <i class="bi bi-bell me-2"></i> Notifications
                </a>
            </li>
        @endif

        @if ($user->isAdmin())
            <li class="nav-item">
                <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2 me-2"></i> Dashboard
                </a>
            </li>
            <li>
                <a href="{{ route('admin.complaints.index') }}" class="nav-link {{ request()->routeIs('admin.complaints.*') ? 'active' : '' }}">
                    <i class="bi bi-envelope-paper me-2"></i> Complaints
                </a>
            </li>
            <li>
                <a href="{{ route('admin.feedback.index') }}" class="nav-link {{ request()->routeIs('admin.feedback.*') ? 'active' : '' }}">
                    <i class="bi bi-star me-2"></i> Feedback
                </a>
            </li>
            <li>
                <a href="{{ route('admin.reports') }}" class="nav-link {{ request()->routeIs('admin.reports*') ? 'active' : '' }}">
                    <i class="bi bi-bar-chart me-2"></i> Reports
                </a>
            </li>
            <li>
                <a href="{{ route('admin.notifications') }}" class="nav-link {{ request()->routeIs('admin.notifications') ? 'active' : '' }}">
                    <i class="bi bi-bell me-2"></i> Notifications
                </a>
            </li>
        @endif

        @if ($user->isSuperAdmin())
            <li><hr class="text-white-50"></li>
            <li>
                <a href="{{ route('superadmin.users.index') }}" class="nav-link {{ request()->routeIs('superadmin.users.*') ? 'active' : '' }}">
                    <i class="bi bi-people me-2"></i> Manage Users
                </a>
            </li>
            <li>
                <a href="{{ route('superadmin.categories.index') }}" class="nav-link {{ request()->routeIs('superadmin.categories.*') ? 'active' : '' }}">
                    <i class="bi bi-tags me-2"></i> Categories
                </a>
            </li>
        @endif
    </ul>

    <hr class="text-white-50">
    <a href="{{ route('profile.edit') }}" class="nav-link {{ request()->routeIs('profile.edit') ? 'active' : '' }}">
        <i class="bi bi-person-gear me-2"></i> Profile
    </a>
</div>
