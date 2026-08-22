@extends('layouts.app')

@section('page-title', 'Categories')

@section('content')
    <div class="d-flex justify-content-end mb-3">
        <a href="{{ route('superadmin.categories.create') }}" class="btn btn-primary btn-sm" style="background-color: var(--ofcms-primary); border-color: var(--ofcms-primary);">
            <i class="bi bi-plus-lg"></i> New Category
        </a>
    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Complaints</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($categories as $category)
                        <tr>
                            <td class="fw-semibold">{{ $category->name }}</td>
                            <td class="text-truncate" style="max-width: 300px;">{{ $category->description }}</td>
                            <td>{{ $category->complaints_count }}</td>
                            <td>
                                <span class="badge {{ $category->is_active ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $category->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="d-flex gap-2">
                                <a href="{{ route('superadmin.categories.edit', $category) }}" class="btn btn-outline-secondary btn-sm">Edit</a>
                                <form method="POST" action="{{ route('superadmin.categories.destroy', $category) }}" onsubmit="return confirm('Delete this category?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">No categories found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">
        {{ $categories->links() }}
    </div>
@endsection
