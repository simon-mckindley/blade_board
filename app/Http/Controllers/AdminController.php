<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AdminController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function index()
    {
        // This method should return the admin dashboard view
        return view('admin.dashboard');
    }

    public function showRegistrationForm()
    {
        return view('admin.register');
    }

    public function showUserManagement(User $user)
    {
        // This method should return the user management view
        // Ensure the authenticated user or admim can only edit their own profile
        if (Auth::user()->id !== $user->id && !Auth::user()->isAdmin()) {
            return redirect()->route('/')
                ->with('alert', [
                    'type' => 'error',
                    'message' => 'You do not have permission to edit this profile.',
                ]);
        }

        $postCount = $user->posts()->count();
        $commentCount = $user->comments()->count();

        return view('admin.useradmin', compact('user', 'postCount', 'commentCount'));
    }

}
