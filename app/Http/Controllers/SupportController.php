<?php

namespace App\Http\Controllers;

use App\Models\Support;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SupportController extends Controller
{
    // Show the support page
    public function index(Request $request)
    {
        // Fetch all support issues (latest first) with search functionality
        $query = \App\Models\Support::query();
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%'.$request->search.'%')
                  ->orWhere('email', 'like', '%'.$request->search.'%')
                  ->orWhere('phone', 'like', '%'.$request->search.'%')
                  ->orWhere('subject', 'like', '%'.$request->search.'%')
                  ->orWhere('message', 'like', '%'.$request->search.'%');
            });
        }
        $issues = $query->orderBy('created_at', 'desc')->paginate(12)->withQueryString();
        // Render the Vue AdminSupport page for admin, otherwise render user support
        if (request()->routeIs('admin.support')) {
            return Inertia::render('admin/AdminSupport', [
                'issues' => $issues,
                'filters' => [
                    'search' => $request->search,
                ],
            ]);
        }
        // Default: user support page
        return Inertia::render('Support');
    }

    // Store a new support issue
    public function store(Request $request)
    {
        // Validate all fields are required
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:30',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        // Save the support issue to the database
        Support::create($validated);

        // Return to the Support page with a flash message
        return Inertia::render('Support', [
            'flash' => [
                'success' => 'Your message has been sent successfully! We are working on your issues and will get back to you soon.'
            ]
        ]);
    }
    
    // Update the status of a support issue
    public function updateStatus(Request $request, $id)
    {
        // Validate the status
        $validated = $request->validate([
            'status' => 'required|string|in:Solved,Not Solved',
        ]);
        
        // Find the support issue
        $issue = Support::findOrFail($id);
        
        // Update the status
        $issue->update([
            'status' => $validated['status']
        ]);
        
        // Get all support issues again to refresh the data
        $query = \App\Models\Support::query();
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%'.$request->search.'%')
                  ->orWhere('email', 'like', '%'.$request->search.'%')
                  ->orWhere('phone', 'like', '%'.$request->search.'%')
                  ->orWhere('subject', 'like', '%'.$request->search.'%')
                  ->orWhere('message', 'like', '%'.$request->search.'%');
            });
        }
        $issues = $query->orderBy('created_at', 'desc')->paginate(12)->withQueryString();
        
        // Return to the admin support page with a flash message
        return Inertia::render('admin/AdminSupport', [
            'issues' => $issues,
            'filters' => [
                'search' => $request->search,
            ],
            'flash' => [
                'success' => 'Support issue status updated successfully'
            ]
        ]);
    }
}
