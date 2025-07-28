<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Post;
use App\Models\Comment;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Enums\UserStatus;
use App\Enums\UserRole;


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
            if (Auth::user()->status !== \App\Enums\UserStatus::Active) {
                Auth::logout();
                return back()->withErrors([
                    'inactive' => 'Your account is not active.',
                    'contact' => 'admin@bladeboard.com',
                ]);
            }

            $request->session()->regenerate();
            return redirect('/')
                ->with('alert', [
                    'type' => 'info',
                    'message' => 'Login successful!',
                ]);
        }

        return back()->withErrors([
            'invalid' => 'The login info doesn\'t match our records.',
        ]);
    }


    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')
            ->with('alert', [
                'type' => 'info',
                'message' => 'You have been logged out successfully.',
            ]);
    }


    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Creates a new user 
     */
    public function register(Request $request)
    {
        $role = UserRole::User; // Default

        if (Auth::check() && Auth::user()->isSuper()) {
            $incomingRole = $request->input('role');

            if (in_array($incomingRole, UserRole::values())) {
                $role = UserRole::from($incomingRole);
            }
        }

        // Merge final role into request
        $request->merge(['role' => $role->value]);

        // Validate input
        $validated = $request->validate(
            [
                'name' => 'required|string|min:3|max:25',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|confirmed|min:6',
                'role' => 'in:user,admin,super', // Validate only allowed roles
            ],
            [
                'name.required' => 'The name is required.',
                'email.email' => 'The email must be a valid email address.',
                'email.required' => 'The email is required.',
                'email.unique' => 'This email is already registered.',
                'password.required' => 'The password is required.',
                'password.confirmed' => 'The password confirmation does not match.',
                'role.in' => 'The selected role is invalid.',
            ]
        );

        try {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => $role, // This will be 'user' if not set
            ]);

            if (Auth::check() && Auth::user()->isAdmin()) {
                return redirect()->route('admin.users.index')
                    ->with('alert', [
                        'type' => 'success',
                        'message' => 'Registration successful!',
                    ]);
            }

            Auth::login($user);

            return redirect()->route('home')->with('alert', [
                'type' => 'success',
                'message' => 'Registration successful!',
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('alert', [
                'type' => 'error',
                'message' => 'There was a problem creating the account: ' . $e->getMessage(),
            ]);
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if (!Auth::user() || !Auth::user()->isAdmin()) {
            return redirect()->route('home')->with('alert', [
                'type' => 'error',
                'message' => 'You do not have permission to view this page.',
            ]);
        }

        // Check if 'search' input exists
        if ($request->has('search')) {
            $validated = $request->validate([
                'search' => 'required|string|min:3|max:25',
            ]);

            $searchTerm = strtolower($validated['search']);

            $query = User::withCount(['posts', 'comments'])->orderBy('created_at', 'desc');

            $query->where(function ($q) use ($searchTerm) {
                $q->whereRaw('LOWER(name) LIKE ?', ["%{$searchTerm}%"])
                    ->orWhereRaw('LOWER(email) LIKE ?', ["%{$searchTerm}%"]);
            });

            $users = $query->get();

            $messEnd = $users->count() == 1 ? '' : 's';

            return view('admin.users', [
                'users' => $users,
                'searchTerm' => $searchTerm,
                'alert' => [
                    'type' => 'info',
                    'message' => 'Search for "' . $searchTerm . '" returned ' . $users->count() . ' result' . $messEnd . '.',
                ],
            ]);
        }

        // No search yet — just render the page with no users
        return view('admin.users');
    }


    /**
     * Display the specified resource.
     */
    public function show()
    {
        // Return the user profile view with the user data
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login')
                ->with('alert', [
                    'type' => 'error',
                    'message' => 'You must be logged in to view this page.',
                ]);
        }

        // Count posts and comments for the authenticated user
        $postCount = $user->posts()->count();
        $commentCount = $user->comments()->count();
        $likeCount = $user->likedPosts()->count();
        $viewCount = $user->viewedPosts()->count();

        return view('user.profile', compact('user', 'postCount', 'commentCount', 'likeCount', 'viewCount'));
    }

    /**
     * Display the posts created by the user.
     */
    public function userPosts(Request $request)
    {
        $user = Auth::user();

        $query = $user->posts()
            ->with('tags')
            ->withCount('comments', 'likes');

        Controller::postFilter($query, $request);

        $sort = $request->query('sort', 'created');
        Controller::postSortOrder($query, $sort);

        $posts = $query->paginate(5);

        $messEnd = $posts->total() == 1 ? ' post' : ' posts';

        return view(
            'user.posts',
            compact('posts'),
            [
                'alert' => [
                    'type' => 'info',
                    'message' => 'Showing ' . $posts->total() . $messEnd . '.',
                ],
            ]
        );
    }

    /**
     * Display the posts that the user has commented on.
     */
    public function commentedPosts(Request $request)
    {
        $user = Auth::user();

        // Get unique post IDs from the user's comments, then load the posts
        $postIds = $user->comments()->pluck('post_id')->unique();

        $query = Post::with(['user', 'tags'])
            ->whereIn('id', $postIds)
            ->withCount('comments', 'likes');

        Controller::postFilter($query, $request);

        $sort = $request->query('sort', 'created');
        Controller::postSortOrder($query, $sort);

        $posts = $query->paginate(5);

        $messEnd = $posts->total() == 1 ? ' post' : ' posts';

        return view(
            'user.commented',
            compact('posts'),
            [
                'alert' => [
                    'type' => 'info',
                    'message' => 'Showing ' . $posts->total() . $messEnd . '.',
                ],
            ]
        );
    }

    /**
     * Display the posts that the user has liked.
     */
    public function likedPosts(Request $request)
    {
        $user = Auth::user();
        $query = $user->likedPosts()
            ->with('tags')
            ->withCount('comments', 'likes');

        Controller::postFilter($query, $request);

        $sort = $request->query('sort', 'created');
        Controller::postSortOrder($query, $sort);

        $posts = $query->paginate(5);

        $messEnd = $posts->total() == 1 ? ' post' : ' posts';

        return view(
            'user.liked',
            compact('posts'),
            [
                'alert' => [
                    'type' => 'info',
                    'message' => 'Showing ' . $posts->total() . $messEnd . '.',
                ],
            ]
        );
    }

    /**
     * Display the posts that the user has viewed ordered by most recent view.
     */
    public function viewedPosts()
    {
        $user = Auth::user();

        $posts = Post::select('posts.*', 'post_user_views.viewed_at as last_viewed_at')
            ->join('post_user_views', 'posts.id', '=', 'post_user_views.post_id')
            ->where('post_user_views.user_id', $user->id)
            ->orderBy('post_user_views.viewed_at', 'desc')
            ->with('tags')
            ->withCount('comments', 'likes')
            ->paginate(5);

        $messEnd = $posts->total() == 1 ? ' post' : ' posts';

        return view(
            'user.viewed',
            compact('posts'),
            [
                'alert' => [
                    'type' => 'info',
                    'message' => 'Showing ' . $posts->total() . $messEnd . '.',
                ],
            ]
        );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        // Ensure the authenticated user or admim can only edit their own profile
        if (Auth::user()->id !== $user->id && !Auth::user()->isAdmin()) {
            return redirect()->route('user.show')
                ->with('alert', [
                    'type' => 'error',
                    'message' => 'You do not have permission to edit this profile.',
                ]);
        }

        return view('user.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        // Ensure the authenticated user or admin can only update their own profile
        if (Auth::id() !== $user->id && !Auth::user()->isAdmin()) {
            return redirect()->route('user.show')
                ->with('alert', [
                    'type' => 'error',
                    'message' => 'You do not have permission to update this profile.',
                ]);
        }

        // Base validation
        $rules = [
            'name' => 'required|string|min:3|max:25',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|confirmed|min:6',
        ];

        // Add status validation if user is admin
        if (Auth::user()->isAdmin()) {
            $rules['status'] = 'required|in:' . implode(',', array_column(UserStatus::cases(), 'value'));
        }

        // Validate input
        $validated = $request->validate(
            $rules,
            [
                'email.email' => 'The email must be a valid email address.',
                'email.required' => 'The email is required.',
                'email.unique' => 'This email is already registered.',
                'password.confirmed' => 'The password confirmation does not match.',
                'status.in' => 'Invalid status selected.',
            ]
        );

        try {
            // Update fields
            $user->name = $validated['name'];
            $user->email = $validated['email'];

            if ($request->filled('password')) {
                $user->password = Hash::make($validated['password']);
            }

            // Only update status if admin submitted it
            if (Auth::user()->isAdmin() && isset($validated['status'])) {
                $user->status = UserStatus::from($validated['status']);
            }

            $user->save();

            return redirect()
                ->back()
                ->with('alert', [
                    'type' => 'success',
                    'message' => 'Profile updated successfully!',
                ]);
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('alert', [
                    'type' => 'error',
                    'message' => 'There was a problem updating the profile: ' . $e->getMessage(),
                ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        // Ensure the authenticated user or admin can only update their profile
        if (Auth::id() !== $user->id && !Auth::user()->isAdmin()) {
            return back()
                ->with('alert', [
                    'type' => 'error',
                    'message' => 'You do not have permission for this profile.',
                ]);
        }

        $route = Auth::user()->isAdmin() ? 'admin.users.index' : 'home';

        $user->delete();

        return redirect()
            ->route($route)
            ->with('alert', [
                'type' => 'warning',
                'message' => 'User account deleted successfully!',
            ]);
    }
}
