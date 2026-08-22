<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: sans-serif; font-size: 11px; color: #1C2B3A; }
        h1 { font-size: 18px; margin-bottom: 4px; }
        .meta { color: #6B7C8D; margin-bottom: 16px; }
        .stats { width: 100%; margin-bottom: 20px; border-collapse: collapse; }
        .stats td { border: 1px solid #ddd; padding: 8px; text-align: center; }
        table.complaints { width: 100%; border-collapse: collapse; }
        table.complaints th, table.complaints td { border: 1px solid #ddd; padding: 6px; text-align: left; }
        table.complaints th { background-color: #1E3A5F; color: #fff; }
    </style>
</head>
<body>
    <h1>OFCMS Complaint Report</h1>
    <div class="meta">Generated: {{ $generated }}</div>

    <table class="stats">
        <tr>
            <td><strong>{{ $stats['total'] }}</strong><br>Total</td>
            <td><strong>{{ $stats['pending'] }}</strong><br>Pending</td>
            <td><strong>{{ $stats['in_progress'] }}</strong><br>In Progress</td>
            <td><strong>{{ $stats['resolved'] }}</strong><br>Resolved</td>
            <td><strong>{{ $stats['rejected'] }}</strong><br>Rejected</td>
        </tr>
    </table>

    <table class="complaints">
        <thead>
            <tr>
                <th>Reference</th>
                <th>Complainant</th>
                <th>Category</th>
                <th>Subject</th>
                <th>Status</th>
                <th>Priority</th>
                <th>Submitted</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($complaints as $complaint)
                <tr>
                    <td>{{ $complaint->reference_number }}</td>
                    <td>{{ $complaint->user->name }}</td>
                    <td>{{ $complaint->category->name }}</td>
                    <td>{{ $complaint->subject }}</td>
                    <td>{{ ucfirst(str_replace('_', ' ', $complaint->status)) }}</td>
                    <td>{{ ucfirst($complaint->priority) }}</td>
                    <td>{{ $complaint->created_at->format('d M Y, H:i') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
