<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Announcement;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CitizenController extends Controller
{
    // Citizen Dashboard
    public function dashboard()
    {
        $userId = Auth::id();
        $totalReports = Report::where('user_id', $userId)->count();
        $activeReports = Report::where('user_id', $userId)->whereIn('status', ['Pending', 'In Progress'])->count();
        $resolvedReports = Report::where('user_id', $userId)->where('status', 'Resolved')->count();

        $recentReports = Report::where('user_id', $userId)->latest()->take(5)->get();

        return view('citizen.dashboard', compact('totalReports', 'activeReports', 'resolvedReports', 'recentReports'));
    }

    // Show File Report Form
    public function createReport()
    {
        return view('citizen.create_report');
    }

    // Save New Report
    public function storeReport(Request $request)
    {
        $request->validate([
            'category' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'contact_number' => 'nullable|string|max:20',
            'facebook_link' => 'nullable|string|max:255',
            'description' => 'required|string',
            'photos.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:4096',
        ]);

        $photoPaths = [];

        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $file) {
                $filename = time().'_'.uniqid().'_'.$file->getClientOriginalName();
                $file->move(public_path('storage/reports'), $filename);
                $photoPaths[] = 'storage/reports/'.$filename;
            }
        } elseif ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('reports', 'public');
            $photoPaths[] = $path;
        }

        $photoValue = null;
        if (! empty($photoPaths)) {
            $photoValue = count($photoPaths) === 1 ? $photoPaths[0] : json_encode($photoPaths);
        }

        // Create the report
        $report = Report::create([
            'user_id' => Auth::id(),
            'category' => $request->category,
            'location' => $request->location,
            'contact_number' => $request->contact_number,
            'facebook_link' => $request->facebook_link,
            'description' => $request->description,
            'photo' => $photoValue,
            'status' => 'Pending',
        ]);

        // Safely attempt logging without breaking submission
        try {
            if (Auth::user()) {
                ActivityLog::create([
                    'user_id' => Auth::id(),
                    'action' => 'Filed Report #'.$report->id,
                    'description' => Auth::user()->name.' filed a report under category: '.$request->category.' at '.$request->location,
                    'ip_address' => $request->ip(),
                ]);
            }
        } catch (\Exception $e) {
            // Bypasses any database column errors for activity logs automatically
        }

        // Redirect directly using URL to completely avoid route-name errors
        return redirect()->to('/citizen/reports')->with('success', 'Incident report submitted successfully.');
    }

    // View All My Reports with Status Filtering
    public function myReports(Request $request)
    {
        $query = Report::where('user_id', Auth::id());

        if ($request->has('status') && $request->status != '') {
            $status = $request->status;

            // Map custom filter labels from the view to match database status values
            if ($status == 'Received' || $status == 'Review') {
                $query->where('status', 'Pending');
            } elseif ($status == 'Progress') {
                $query->where('status', 'In Progress');
            } else {
                $query->where('status', $status);
            }
        }

        $reports = $query->latest()->paginate(10)->appends($request->all());

        return view('citizen.my_reports', compact('reports'));
    }

    // Delete Report
    public function destroy(Request $request, $id)
    {
        $report = Report::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        // Delete attached photo file if it exists
        if ($report->photo && file_exists(public_path($report->photo))) {
            @unlink(public_path($report->photo));
        }

        $report->delete();

        // Log deletion activity
        try {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => 'Deleted Report #'.$id,
                'description' => Auth::user()->name.' deleted their report #'.$id,
                'ip_address' => $request->ip(),
            ]);
        } catch (\Exception $e) {
            // Ignore optional logging errors
        }

        return redirect()->to('/citizen/reports')->with('success', 'Report deleted successfully.');
    }

    // View Public Announcements & Clear Unread Notifications
    public function announcements()
    {
        $user = Auth::user();

        // Automatically mark all unread announcement notifications as read upon opening
        if ($user) {
            $user->unreadNotifications
                ->where('type', 'App\Notifications\NewAnnouncementNotification')
                ->markAsRead();
        }

        $announcements = Announcement::with('user')->latest()->get();

        return view('citizen.announcements', compact('announcements'));
    }
}
