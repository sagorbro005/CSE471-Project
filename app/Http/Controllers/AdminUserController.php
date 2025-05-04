<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminUserController extends Controller
{
    // List all users (with search and pagination)
    public function index(Request $request)
    {
        $query = User::query();
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%")
                  ->orWhere('phone', 'like', "%$search%") ;
            });
        }
        $users = $query->orderBy('id', 'desc')->paginate(10);
        // For API response
        if ($request->wantsJson()) {
            return response()->json($users);
        }
        // For Inertia (if you want to use Inertia page)
        return Inertia::render('admin/AdminUsersIndex', [ 'users' => $users ]);
    }

    // Update a user
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'gender' => 'nullable|string|in:male,female,other',
            'date_of_birth' => 'nullable|date',
            'address' => 'nullable|string|max:255',
        ]);
        $user->update($validated);
        return response()->json(['success' => true]);
    }
}
