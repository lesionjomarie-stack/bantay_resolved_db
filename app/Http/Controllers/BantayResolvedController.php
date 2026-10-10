<?php

namespace App\Http\Controllers;

use App\Models\BantayResolved;
use Illuminate\Http\Request;

class BantayResolvedController extends Controller
{
    public function index(Request $request)
    {
        $query = BantayResolved::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('ticket_number', 'like', "%{$search}%")
                    ->orWhere('title', 'like', "%{$search}%")
                    ->orWhere('reporter_name', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        $reports = $query->latest()->paginate(10);

        return view('bantay.index', compact('reports'));
    }

    public function create()
    {
        return view('bantay.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string|max:255',
            'priority' => 'required|in:Low,Medium,High,Urgent',
            'reporter_name' => 'required|string|max:255',
            'reporter_contact' => 'nullable|string|max:255',
        ]);

        $validated['ticket_number'] = 'BR-'.strtoupper(substr(uniqid(), -6));
        $validated['status'] = 'Pending';

        BantayResolved::create($validated);

        return redirect()->route('bantay.index')->with('success', 'Incident report created successfully!');
    }

    public function show(BantayResolved $bantay)
    {
        return view('bantay.show', ['report' => $bantay]);
    }

    public function edit(BantayResolved $bantay)
    {
        return view('bantay.edit', ['report' => $bantay]);
    }

    public function update(Request $request, BantayResolved $bantay)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string|max:255',
            'priority' => 'required|in:Low,Medium,High,Urgent',
            'status' => 'required|in:Pending,Under Review,In Progress,Resolved,Dismissed',
            'reporter_name' => 'required|string|max:255',
            'reporter_contact' => 'nullable|string|max:255',
            'resolution_notes' => 'nullable|string',
        ]);

        if ($validated['status'] === 'Resolved' && ! $bantay->resolved_at) {
            $validated['resolved_at'] = now();
        }

        $bantay->update($validated);

        return redirect()->route('bantay.index')->with('success', 'Incident report updated successfully!');
    }

    public function destroy(BantayResolved $bantay)
    {
        $bantay->delete();

        return redirect()->route('bantay.index')->with('success', 'Incident report deleted successfully!');
    }
}
