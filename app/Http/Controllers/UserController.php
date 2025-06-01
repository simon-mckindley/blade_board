<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Post;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }


    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }


    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    // Handle the registration form submission
    public function register(Request $request)
    {
        // Validate input
        $validated = $request->validate(
            [
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|confirmed|min:6',
            ],
            [
                'name.required' => 'The name field is required.',
                'email.email' => 'The email must be a valid email address.',
                'email.required' => 'The email field is required.',
                'email.unique' => 'This email is already registered.',
                'password.confirmed' => 'The password confirmation does not match.',
            ]
        );

        // Create the user
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        // Log the user in
        Auth::login($user);

        // Redirect to home
        return redirect()->route('home')->with('success', 'Registration successful!');
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show()
    {
        // Return the user profile view with the user data
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login')->withErrors(['You must be logged in to view this page.']);
        }

        // Count posts and comments for the authenticated user
        $postCount = $user->posts()->count();
        $commentCount = $user->comments()->count();

        return view('user.profile', compact('user', 'postCount', 'commentCount'));
    }

    /**
     * Display the posts created by the user.
     */
    public function userPosts()
    {
        $user = Auth::user();
        $posts = $user->posts()->orderBy('created_at', 'desc')->with('tags')->withCount("comments")->get();

        return view('user.posts', compact('posts', 'user'));
    }

    /**
     * Display the posts that the user has commented on.
     */
    public function commentedPosts()
    {
        $user = Auth::user();

        // Get unique post IDs from the user's comments, then load the posts
        $postIds = $user->comments()->pluck('post_id')->unique();

        $posts = Post::with(['user', 'tags'])
            ->whereIn('id', $postIds)
            ->orderBy('created_at', 'desc')
            ->withCount("comments")
            ->get();

        return view('user.commented', compact('posts'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        // Ensure the authenticated user can only edit their own profile
        if (Auth::user()->id !== $user->id) {
            return redirect()->route('user.show')->withErrors(['You do not have permission to edit this profile.']);
        }

        return view('user.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        // Ensure the authenticated user can only update their own profile
        if (Auth::user()->id !== $user->id) {
            return redirect()->route('user.show')->withErrors(['You do not have permission to update this profile.']);
        }

        // Validate input
        $validated = $request->validate(
            [
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,' . $user->id,
                'password' => 'nullable|confirmed|min:6',
            ],
            [
                'email.email' => 'The email must be a valid email address.',
                'email.required' => 'The email field is required.',
                'email.unique' => 'This email is already registered.',
                'password.confirmed' => 'The password confirmation does not match.',
            ]
        );

        // Update the user
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        if ($request->filled('password')) {
            $user->password = Hash::make($validated['password']);
        }
        $user->save();

        return redirect()->route('user.show')->with('success', 'Profile updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }
}
