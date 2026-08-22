<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Complaint;
use App\Models\Feedback;
use App\Notifications\ComplaintResolved;
use App\Notifications\ComplaintStatusUpdated;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total' => Complaint::count(),
            'pending' => Complaint::where('status', 'pending')->count(),
            'in_progress' => Complaint::where('status', 'in_progress')->count(),
            'resolved' => Complaint::where('status', 'resolved')->count(),
        ];

        $recentComplaints = Complaint::with(['category', 'user'])->latest()->take(10)->get();

        $chartData = [
            'labels' => ['Pending', 'In Progress', 'Resolved', 'Rejected'],
            'data' => [
                Complaint::where('status', 'pending')->count(),
                Complaint::where('status', 'in_progress')->count(),
                Complaint::where('status', 'resolved')->count(),
                Complaint::where('status', 'rejected')->count(),
            ],
        ];

        $unreadCount = auth()->user()->unreadNotifications()->count();

        return view('admin.dashboard', compact('stats', 'recentComplaints', 'chartData', 'unreadCount'));
    }

    public function index(Request $request)
    {
        $complaints = Complaint::with(['category', 'user'])
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
            ->when($request->filled('category_id'), fn ($q) => $q->where('category_id', $request->category_id))
            ->when($request->filled('priority'), fn ($q) => $q->where('priority', $request->priority))
            ->when($request->filled('date_from'), fn ($q) => $q->whereDate('created_at', '>=', $request->date_from))
            ->when($request->filled('date_to'), fn ($q) => $q->whereDate('created_at', '<=', $request->date_to))
            ->when($request->filled('search'), function ($q) use ($request) {
                $q->where(function ($sub) use ($request) {
                    $sub->where('reference_number', 'like', "%{$request->search}%")
                        ->orWhere('subject', 'like', "%{$request->search}%");
                });
            })
            ->latest()
            ->paginate(15)
            ->appends($request->query());

        $categories = Category::orderBy('name')->get();

        return view('admin.complaints.index', compact('complaints', 'categories'));
    }

    public function show(Complaint $complaint)
    {
        $complaint->load(['category', 'user', 'responses.admin', 'assignedTo']);
        $admins = \App\Models\User::whereIn('role', ['admin', 'superadmin'])->orderBy('name')->get();

        return view('admin.complaints.show', compact('complaint', 'admins'));
    }

    public function updateStatus(Request $request, Complaint $complaint)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,in_progress,resolved,rejected',
            'priority' => 'required|in:low,medium,high',
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        if ($validated['status'] === 'resolved' && $complaint->status !== 'resolved') {
            $validated['resolved_at'] = now();
        }

        $complaint->update($validated);

        $complaint->user->notify(new ComplaintStatusUpdated($complaint));

        return back()->with('success', 'Complaint updated successfully.');
    }

    public function respond(Request $request, Complaint $complaint)
    {
        $validated = $request->validate([
            'message' => 'required|string|min:5',
            'resolve' => 'nullable|boolean',
        ]);

        $complaint->responses()->create([
            'admin_id' => auth()->id(),
            'message' => $validated['message'],
        ]);

        if ($request->boolean('resolve')) {
            $complaint->update([
                'status' => 'resolved',
                'resolved_at' => now(),
            ]);

            $complaint->user->notify(new ComplaintResolved($complaint));
        } else {
            $complaint->user->notify(new ComplaintStatusUpdated($complaint));
        }

        return back()->with('success', 'Response submitted successfully.');
    }

    public function feedback(Request $request)
    {
        $feedback = Feedback::with('user')
            ->when($request->filled('rating'), fn ($q) => $q->where('rating', $request->rating))
            ->latest()
            ->paginate(15)
            ->appends($request->query());

        return view('admin.feedback.index', compact('feedback'));
    }

    public function reports()
    {
        $byCategory = Category::withCount('complaints')->orderByDesc('complaints_count')->get();

        $byStatus = [
            'pending' => Complaint::where('status', 'pending')->count(),
            'in_progress' => Complaint::where('status', 'in_progress')->count(),
            'resolved' => Complaint::where('status', 'resolved')->count(),
            'rejected' => Complaint::where('status', 'rejected')->count(),
        ];

        $months = collect(range(5, 0))->map(function ($i) {
            $date = now()->subMonths($i);

            return [
                'label' => $date->format('M Y'),
                'count' => Complaint::whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->count(),
            ];
        });

        $avgResolutionHours = Complaint::whereNotNull('resolved_at')
            ->get()
            ->avg(fn ($c) => $c->created_at->diffInHours($c->resolved_at));

        return view('admin.reports.index', compact('byCategory', 'byStatus', 'months', 'avgResolutionHours'));
    }

    public function exportPdf()
    {
        $data = [
            'complaints' => Complaint::with(['category', 'user'])->latest()->get(),
            'stats' => [
                'total' => Complaint::count(),
                'pending' => Complaint::where('status', 'pending')->count(),
                'in_progress' => Complaint::where('status', 'in_progress')->count(),
                'resolved' => Complaint::where('status', 'resolved')->count(),
                'rejected' => Complaint::where('status', 'rejected')->count(),
            ],
            'generated' => now()->format('d M Y, H:i'),
        ];

        $pdf = Pdf::loadView('admin.reports.pdf', $data)->setPaper('a4', 'landscape');

        return $pdf->download('OFCMS_Report_'.date('Ymd').'.pdf');
    }

    public function notifications()
    {
        $notifications = auth()->user()->notifications()->paginate(15);

        return view('admin.notifications', compact('notifications'));
    }
}
