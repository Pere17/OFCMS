<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Complaint;
use App\Models\Feedback;
use App\Models\User;
use App\Notifications\ComplaintSubmitted;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ComplainantController extends Controller
{
    public function dashboard()
    {
        $userId = auth()->id();

        $stats = [
            'total' => Complaint::where('user_id', $userId)->count(),
            'pending' => Complaint::where('user_id', $userId)->where('status', 'pending')->count(),
            'in_progress' => Complaint::where('user_id', $userId)->where('status', 'in_progress')->count(),
            'resolved' => Complaint::where('user_id', $userId)->where('status', 'resolved')->count(),
        ];

        $recentComplaints = Complaint::with('category')
            ->where('user_id', $userId)
            ->latest()
            ->take(5)
            ->get();

        return view('complainant.dashboard', compact('stats', 'recentComplaints'));
    }

    public function index(Request $request)
    {
        $complaints = Complaint::with('category')
            ->where('user_id', auth()->id())
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
            ->latest()
            ->paginate(15)
            ->appends($request->query());

        return view('complainant.complaints.index', compact('complaints'));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->orderBy('name')->get();

        return view('complainant.complaints.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'subject' => 'required|string|max:255',
            'description' => 'required|string|min:20',
            'attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png,docx|max:5120',
            'priority' => 'required|in:low,medium,high',
        ]);

        if ($request->hasFile('attachment')) {
            $validated['attachment'] = $request->file('attachment')->store('attachments', 'public');
        }

        $validated['user_id'] = auth()->id();

        $complaint = Complaint::create($validated);

        $admins = User::whereIn('role', ['admin', 'superadmin'])->get();
        foreach ($admins as $admin) {
            $admin->notify(new ComplaintSubmitted($complaint));
        }

        return redirect()->route('complainant.complaints.show', $complaint)
            ->with('success', "Complaint submitted successfully. Reference: {$complaint->reference_number}");
    }

    public function show(Complaint $complaint)
    {
        abort_unless(auth()->id() === $complaint->user_id, 403);

        $complaint->load(['category', 'responses.admin']);

        return view('complainant.complaints.show', compact('complaint'));
    }

    public function feedbackCreate()
    {
        return view('complainant.feedback.create');
    }

    public function feedbackStore(Request $request)
    {
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string|min:10',
            'rating' => 'required|integer|min:1|max:5',
            'is_anonymous' => 'nullable|boolean',
        ]);

        $validated['user_id'] = auth()->id();
        $validated['is_anonymous'] = $request->boolean('is_anonymous');

        Feedback::create($validated);

        return redirect()->route('complainant.dashboard')->with('success', 'Thank you for your feedback!');
    }

    public function notifications()
    {
        $notifications = auth()->user()->notifications()->paginate(15);

        return view('complainant.notifications', compact('notifications'));
    }

    public function markNotificationsRead()
    {
        auth()->user()->unreadNotifications->markAsRead();

        return back()->with('success', 'Notifications marked as read.');
    }
}
