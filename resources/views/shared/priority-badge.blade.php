@php
    $classes = [
        'low' => 'bg-secondary',
        'medium' => 'bg-primary',
        'high' => 'bg-danger',
    ];
@endphp
<span class="badge {{ $classes[$priority] ?? 'bg-secondary' }}">{{ ucfirst($priority) }}</span>
