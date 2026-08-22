@extends('layouts.app')

@section('page-title', 'Dashboard')

@section('content')
    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-lg-3">
            <div class="card stat-card stat-total h-100">
                <div class="card-body">
                    <div class="text-muted small">Total Complaints</div>
                    <div class="fs-3 fw-bold">{{ $stats['total'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card stat-card stat-pending h-100">
                <div class="card-body">
                    <div class="text-muted small">Pending</div>
                    <div class="fs-3 fw-bold">{{ $stats['pending'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card stat-card stat-in-progress h-100">
                <div class="card-body">
                    <div class="text-muted small">In Progress</div>
                    <div class="fs-3 fw-bold">{{ $stats['in_progress'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card stat-card stat-resolved h-100">
                <div class="card-body">
                    <div class="text-muted small">Resolved</div>
                    <div class="fs-3 fw-bold">{{ $stats['resolved'] }}</div>
                </div>
            </div>
        </div>
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
                    <h6 class="fw-semibold mb-3">Recent Complaints</h6>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead>
                                <tr>
                                    <th>Reference</th>
                                    <th>Complainant</th>
                                    <th>Status</th>
                                    <th>Submitted</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($recentComplaints as $complaint)
                                    <tr style="cursor: pointer;" onclick="window.location='{{ route('admin.complaints.show', $complaint) }}'">
                                        <td class="fw-semibold">{{ $complaint->reference_number }}</td>
                                        <td>{{ $complaint->user->name }}</td>
                                        <td>@include('shared.status-badge', ['status' => $complaint->status])</td>
                                        <td class="text-muted small">{{ $complaint->created_at->format('d M Y, H:i') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-4">No complaints yet.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
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
                labels: @json($chartData['labels']),
                datasets: [{
                    data: @json($chartData['data']),
                    backgroundColor: ['#E8A020', '#1565C0', '#2E7D32', '#C62828'],
                }],
            },
            options: { responsive: true },
        });
    </script>
@endpush
