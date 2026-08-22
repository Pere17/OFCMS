@extends('layouts.app')

@section('page-title', 'Manage Users')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <form method="GET" class="d-flex gap-2">
            <input type="text" name="search" class="form-control form-control-sm" placeholder="Search name or email" value="{{ request('search') }}">
            <select name="role" class="form-select form-select-sm" onchange="this.form.submit()">
                <option value="">All Roles</option>
                @foreach (['complainant' => 'Complainant', 'admin' => 'Admin', 'superadmin' => 'Super Admin'] as $value => $label)
                    <option value="{{ $value }}" @selected(request('role') === $value)>{{ $label }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-outline-secondary btn-sm">Search</button>
        </form>
        <a href="{{ route('superadmin.users.create') }}" class="btn btn-primary btn-sm" style="background-color: var(--ofcms-primary); border-color: var(--ofcms-primary);">
            <i class="bi bi-plus-lg"></i> New User
        </a>
    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->phone ?: '—' }}</td>
                            <td><span class="badge bg-secondary">{{ ucfirst($user->role) }}</span></td>
                            <td>
                                <span class="badge {{ $user->is_active ? 'bg-success' : 'bg-danger' }}">
                                    {{ $user->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="d-flex gap-2">
                                <a href="{{ route('superadmin.users.edit', $user) }}" class="btn btn-outline-secondary btn-sm">Edit</a>
                                @if ($user->id !== auth()->id())
                                    <form method="POST" action="{{ route('superadmin.users.toggle', $user) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-outline-warning btn-sm">
                                            {{ $user->is_active ? 'Deactivate' : 'Activate' }}
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('superadmin.users.destroy', $user) }}" onsubmit="return confirm('Delete this user?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm">Delete</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">No users found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">
        {{ $users->links() }}
    </div>
@endsection
