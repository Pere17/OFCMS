@extends('layouts.app')

@section('page-title', 'Reports')

@section('content')
    <div class="d-flex justify-content-end mb-3">
        <a href="{{ route('admin.reports.export') }}" class="btn btn-primary btn-sm" style="background-color: var(--ofcms-primary); border-color: var(--ofcms-primary);">
            <i class="bi bi-file-earmark-pdf"></i> Export PDF
        </a>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-lg-5">
            <div class="card h-100">
                <div class="card-body">
                    <h6 class="fw-semibold mb-3">Complaints by Status</h6>
                    <canvas id="statusChart" height="220"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-7">
            <div class="card h-100">
                <div class="card-body">
                    <h6 class="fw-semibold mb-3">Complaints per Month (Last 6 Months)</h6>
                    <canvas id="monthChart" height="220"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">
                    <h6 class="fw-semibold mb-3">Complaints by Category</h6>
                    <table class="table table-sm mb-0">
                        <thead>
                            <tr><th>Category</th><th class="text-end">Complaints</th></tr>
                        </thead>
                        <tbody>
                            @foreach ($byCategory as $category)
                                <tr>
                                    <td>{{ $category->name }}</td>
                                    <td class="text-end">{{ $category->complaints_count }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">
                    <h6 class="fw-semibold mb-3">Summary</h6>
                    <dl class="row small mb-0">
                        <dt class="col-6">Pending</dt><dd class="col-6">{{ $byStatus['pending'] }}</dd>
                        <dt class="col-6">In Progress</dt><dd class="col-6">{{ $byStatus['in_progress'] }}</dd>
                        <dt class="col-6">Resolved</dt><dd class="col-6">{{ $byStatus['resolved'] }}</dd>
                        <dt class="col-6">Rejected</dt><dd class="col-6">{{ $byStatus['rejected'] }}</dd>
                        <dt class="col-6">Avg. Resolution Time</dt>
                        <dd class="col-6">{{ $avgResolutionHours ? round($avgResolutionHours, 1) . ' hrs' : 'N/A' }}</dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        new Chart(document.getElementById('statusChart'), {
            type: 'doughnut',
            data: {
                labels: ['Pending', 'In Progress', 'Resolved', 'Rejected'],
                datasets: [{
                    data: @json(array_values($byStatus)),
                    backgroundColor: ['#E8A020', '#1565C0', '#2E7D32', '#C62828'],
                }],
            },
        });

        new Chart(document.getElementById('monthChart'), {
            type: 'bar',
            data: {
                labels: @json($months->pluck('label')),
                datasets: [{
                    label: 'Complaints Submitted',
                    data: @json($months->pluck('count')),
                    backgroundColor: '#2E5090',
                }],
            },
            options: {
                scales: { y: { beginAtZero: true, ticks: { precision: 0 } } },
            },
        });
    </script>
@endpush
