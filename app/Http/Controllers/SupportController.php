<?php

namespace App\Http\Controllers;

use App\Models\Support;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SupportController extends Controller
{
    // Show the support page
    public function index()
    {
        // Fetch all support issues (latest first)
        $issues = \App\Models\Support::orderBy('created_at', 'desc')->get();
        // Render the Vue AdminSupport page for admin, otherwise render user support
        if (request()->routeIs('admin.support')) {
            return Inertia::render('admin/AdminSupport', [
                'issues' => $issues
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

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Your message has been sent successfully! We are working on your issues and will get back to you soon.');
    }
}
