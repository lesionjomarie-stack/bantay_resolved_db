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

class AdminController extends Controller
{
    // Admin Dashboard Index
    public function index(Request $request)
    {
        $query = Report::with('user');

        // Search Filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%")
                    ->orWhere('category', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($u) use ($search) {
                        $u->where('name', 'like', "%{$search}%");
                    });
            });
        }

        // Status Filter
        if ($request->filled('status') && $request->status !== 'All Statuses') {
            $query->where('status', $request->status);
        }

        // Category Filter
        if ($request->filled('category') && $request->category !== 'All Categories') {
            $query->where('category', $request->category);
        }

        $reports = $query->latest()->get();

        $totalReports = Report::count();
        $pendingReports = Report::whereIn('status', ['Pending', 'In Progress'])->count();
        $resolvedReports = Report::where('status', 'Resolved')->count();

        $mapLocations = Report::whereNotNull('location')
            ->where('location', '!=', '')
            ->latest()
            ->take(6)
            ->get()
            ->map(function ($report) {
                return [
                    'id' => $report->id,
                    'location' => trim($report->location),
                    'category' => $report->category ?? 'Community Issue',
                    'status' => $report->status ?? 'Pending',
                ];
            })
            ->filter(fn ($item) => ! empty($item['location']))
            ->values();

        return view('admin.dashboard', compact('reports', 'totalReports', 'pendingReports', 'resolvedReports', 'mapLocations'));
    }

    // Pending Reports View
    public function pendingReports()
    {
        $reports = Report::with('user')->whereIn('status', ['Pending', 'In Progress'])->latest()->get();
        $pendingReports = $reports->count();

        return view('admin.pending-reports', compact('reports', 'pendingReports'));
    }

    // Update Report Status & Admin Remarks
    public function updateReport(Request $request, Report $report): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:Pending,In Progress,Resolved'],
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
                'action' => 'Updated Report #'.$report->id,
                'description' => "Changed status from '{$previousStatus}' to '{$report->status}' for report at {$report->location}.",
                'ip_address' => $request->ip(),
            ]);
        });

        return redirect()->back()->with('success', 'Report status and remarks updated successfully.');
    }

    // Delete Report
    public function deleteReport(Request $request, $id)
    {
        $report = Report::findOrFail($id);
        $location = $report->location;

        // Delete photo file if it exists
        if ($report->photo && file_exists(public_path($report->photo))) {
            @unlink(public_path($report->photo));
        }

        $report->delete();

        // Log admin deletion activity
        try {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => 'Deleted Report #'.$id,
                'description' => 'Admin deleted report located at '.$location,
                'ip_address' => $request->ip(),
            ]);
        } catch (\Exception $e) {
            // Ignore optional logging errors
        }

        return redirect()->back()->with('success', 'Report deleted successfully.');
    }

    // Manage Announcements View
    public function announcements()
    {
        $announcements = Announcement::with('user')->latest()->get();

        return view('admin.announcements', compact('announcements'));
    }

    // Store Announcement & Broadcast Notification to Citizens
    public function storeAnnouncement(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'priority' => 'required|string',
            'content' => 'required|string',
        ]);

        // 1. Create Announcement
        $announcement = Announcement::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'priority' => $request->priority,
            'content' => $request->content,
        ]);

        // 2. Broadcast notification to all registered citizens
        $citizens = User::where('role', 'citizen')->get();
        if ($citizens->isNotEmpty()) {
            Notification::send($citizens, new NewAnnouncementNotification($announcement));
        }

        // 3. Log activity
        try {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => 'Published Announcement',
                'description' => 'Published new announcement: "'.$request->title.'"',
                'ip_address' => $request->ip(),
            ]);
        } catch (\Exception $e) {
            // Ignore optional logging errors
        }

        return redirect()->back()->with('success', 'Announcement published and broadcasted to citizens successfully.');
    }

    // Manage Users View
    public function manageUsers()
    {
        $users = User::latest()->paginate(10);

        return view('admin.users', compact('users'));
    }

    // Update User
    public function updateUser(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$id,
            'role' => 'required|string',
        ]);

        $user = User::findOrFail($id);
        $user->update($request->only(['name', 'email', 'role']));

        // Log user update activity
        try {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => 'Updated User Profile',
                'description' => 'Updated details for user: '.$user->name.' (ID: '.$user->id.')',
                'ip_address' => $request->ip(),
            ]);
        } catch (\Exception $e) {
            // Ignore optional logging errors
        }

        return redirect()->back()->with('success', 'User updated successfully.');
    }

    // Delete User & Log Activity
    public function deleteUser(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $deletedUserName = $user->name;

        try {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => 'Deleted user account: '.$deletedUserName,
                'description' => 'User account '.$deletedUserName.' (ID: '.$user->id.') was permanently deleted.',
                'ip_address' => $request->ip(),
            ]);
        } catch (\Exception $e) {
            // Ignore optional logging errors
        }

        $user->delete();

        return redirect()->back()->with('success', 'User deleted successfully.');
    }

    // Activity Logs View
    public function activityLogs()
    {
        $logs = ActivityLog::with('user')->latest()->paginate(10);

        return view('admin.logs', compact('logs'));
    }

    // Delete Activity Log
    public function deleteActivityLog(Request $request, $id)
    {
        $log = ActivityLog::findOrFail($id);
        $log->delete();

        return redirect()->back()->with('success', 'Activity log deleted successfully.');
    }
}
