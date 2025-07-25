<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Tag;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Post::with('tags')
            ->withCount('comments', 'likes', 'reports');

        Controller::postFilter($query, $request);

        $sort = $request->query('sort', 'created');
        Controller::postSortOrder($query, $sort);

        $posts = $query->paginate(5);

        $messEnd = $posts->total() == 1 ? ' post' : ' posts';

        return view(
            'posts.display',
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
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('posts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|min:3|max:255',
            'content' => 'required|string',
            'tags' => 'required|array',
            'tags.*' => 'exists:tags,id',
        ], [
            'title.required' => 'A title is required for the post.',
            'content.required' => 'The post content is required.',
            'tags.required' => 'At least one tag is required.',
            'tags.*.exists' => 'One or more selected tags do not exist.',
        ]);

        try {
            $post = new Post();
            $post->title = $validated['title'];
            $post->content = $validated['content'];
            $post->user_id = Auth::id();
            $post->save();

            $post->tags()->attach($validated['tags']);

            return redirect()
                ->route('posts.create')
                ->with('alert', [
                    'type' => 'success',
                    'message' => 'Post created!',
                    'post_id' => $post->id,
                ]);
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('alert', [
                    'type' => 'error',
                    'message' => 'There was a problem creating the post: ' . $e->getMessage(),
                ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        $post->load('tags');
        return view('posts.post', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        $post->load('tags');
        return view('posts.edit', compact('post'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        $validated = $request->validate([
            'title' => 'required|string|min:3|max:255',
            'content' => 'required|string',
            'tags' => 'required|array',
            'tags.*' => 'exists:tags,id',
        ], [
            'title.required' => 'A title is required for the post.',
            'content.required' => 'The post content is required.',
            'tags.required' => 'At least one tag is required.',
            'tags.*.exists' => 'One or more selected tags do not exist.',
        ]);

        try {
            $post->title = $validated['title'];
            $post->content = $validated['content'];
            $post->save();
            $post->tags()->sync($validated['tags']); // sync tags

            return redirect()
                ->route('posts.show', $post->id)
                ->with('alert', [
                    'type' => 'success',
                    'message' => 'Post updated successfully!',
                ]);
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('alert', [
                    'type' => 'error',
                    'message' => 'There was a problem updating the post: ' . $e->getMessage(),
                ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        // Ensure the authenticated user or admin can only delete their post
        if (Auth::id() !== $post->user_id && !Auth::user()->isAdmin()) {
            return back()
                ->with('alert', [
                    'type' => 'error',
                    'message' => 'You do not have permission for this post.',
                ]);
        }

        $post->delete();

        return redirect()
            ->route('posts.display')
            ->with('alert', [
                'type' => 'warning',
                'message' => 'Post deleted successfully!',
            ]);
    }

    /**
     * Adds a user view to the post_view table
     */
    public function logView(Request $request, Post $post)
    {
        if (!Auth::check() || Auth::user()->isAdmin()) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $user = Auth::user();

        // Check if the user has already viewed the post
        if ($user->viewedPosts()->where('post_id', $post->id)->exists()) {
            // Update the viewed_at timestamp
            $user->viewedPosts()->updateExistingPivot($post->id, [
                'viewed_at' => now(),
            ]);
        } else {
            // First view — attach with viewed_at set
            $user->viewedPosts()->attach($post->id, [
                'viewed_at' => now(),
            ]);
        }

        return response()->json(['message' => 'View logged']);
    }
}
