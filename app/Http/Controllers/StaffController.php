<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Announcement;
use App\Models\Report;
use App\Models\ReportStatusHistory;
use App\Models\User;
use App\Notifications\NewAnnouncementNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\View\View;

class StaffController extends Controller
{
    public function index(Request $request): View
    {
        $reports = $this->reportQuery($request)->with('user')->latest()->limit(8)->get();

        $totalReports = Report::count();
        $pendingReports = Report::where('status', 'Pending')->count();
        $inProgressReports = Report::where('status', 'In Progress')->count();
        $resolvedReports = Report::where('status', 'Resolved')->count();
        $rejectedReports = Report::where('status', 'Rejected')->count();
        $recentAnnouncements = Announcement::latest()->limit(3)->get();

        return view('staff.dashboard', compact(
            'reports',
            'totalReports',
            'pendingReports',
            'inProgressReports',
            'resolvedReports',
            'rejectedReports',
            'recentAnnouncements'
        ));
    }

    public function reports(Request $request): View
    {
        $reports = $this->reportQuery($request)
            ->with(['user', 'statusHistory.user'])
            ->latest()
            ->paginate(10);

        return view('staff.reports', compact('reports'));
    }

    public function updateReport(Request $request, Report $report): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:Pending,Verified,In Progress,Resolved,Rejected'],
            'responder_name' => ['nullable', 'string', 'max:255'],
            'admin_remarks' => ['nullable', 'string', 'max:1000'],
        ]);

        $previousStatus = $report->status;

        DB::transaction(function () use ($report, $validated, $request, $previousStatus) {
            $report->fill($validated)->save();

            if ($previousStatus !== $report->status) {
                ReportStatusHistory::create([
                    'report_id' => $report->id,
                    'user_id' => Auth::id(),
                    'from_status' => $previousStatus,
                    'to_status' => $report->status,
                    'remarks' => $validated['admin_remarks'],
                    'ip_address' => $request->ip(),
                ]);
            }

            ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => 'Updated report status',
                'description' => "Changed report #{$report->id} from '{$previousStatus}' to '{$report->status}'.",
                'ip_address' => $request->ip(),
            ]);
        });

        return redirect()->route('staff.reports')->with(
            'success',
            "Report #{$report->id} was updated successfully."
        );
    }

    private function reportQuery(Request $request)
    {
        $query = Report::query();

        if ($request->filled('search')) {
            $query->where(function ($query) use ($request) {
                $search = $request->string('search');
                $query->where('description', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%")
                    ->orWhere('category', 'like', "%{$search}%")
                    ->orWhereHas('user', fn ($userQuery) => $userQuery->where('name', 'like', "%{$search}%"));
            });
        }

        if ($request->filled('category')) {
            $query->where('category', $request->string('category'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->string('status'));
        }

        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->string('date'));
        }

        return $query;
    }

    public function announcements(): View
    {
        $announcements = Announcement::with('user')->latest()->paginate(10);

        return view('staff.announcements', compact('announcements'));
    }

    public function storeAnnouncement(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'priority' => ['required', 'in:General Notice,High Priority,Urgent Alert'],
            'content' => ['required', 'string', 'max:2000'],
        ]);

        $announcement = Announcement::create([
            'user_id' => Auth::id(),
            ...$validated,
        ]);

        $citizens = User::where('role', 'citizen')->get();
        if ($citizens->isNotEmpty()) {
            Notification::send($citizens, new NewAnnouncementNotification($announcement));
        }

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'Published announcement',
            'description' => "Published announcement '{$announcement->title}'.",
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('staff.announcements')->with(
            'success',
            'Announcement published to citizens.'
        );
    }
}
