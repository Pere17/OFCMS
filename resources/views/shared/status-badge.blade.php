@php
    $classes = [
        'pending' => 'bg-warning text-dark',
        'in_progress' => 'bg-info text-white',
        'resolved' => 'bg-success text-white',
        'rejected' => 'bg-danger text-white',
    ];
    $labels = [
        'pending' => 'Pending',
        'in_progress' => 'In Progress',
        'resolved' => 'Resolved',
        'rejected' => 'Rejected',
    ];
@endphp
<span class="badge {{ $classes[$status] ?? 'bg-secondary' }}">{{ $labels[$status] ?? ucfirst($status) }}</span>
