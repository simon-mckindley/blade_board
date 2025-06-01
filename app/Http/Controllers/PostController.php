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
        $query = Post::with('tags')->orderBy('created_at', 'desc')->withCount("comments");

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                    ->orWhere('content', 'like', '%' . $search . '%');
            });
        }
        // Filter by user if specified
        if ($request->has('user')) {
            $query->where('user_id', $request->user);
        }
        // Filter by tag if specified
        if ($request->has('tag')) {
            $query->whereHas('tags', function ($q) use ($request) {
                $q->where('id', $request->tag);
            });
        }

        $posts = $query->get();
        $tags = Tag::all();

        return view('posts.display', compact('posts', 'tags'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $tags = Tag::all();
        return view('posts.create', compact('tags'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate(
            [
                'title' => 'required|string|max:255',
                'content' => 'required|string',
                'tags' => 'required|array',
                'tags.*' => 'exists:tags,id',
            ],
            [
                'title.required' => 'A title is required for the post.',
                'content.required' => 'The post content is required.',
                'tags.required' => 'At least one tag is required.',
                'tags.*.exists' => 'One or more selected tags do not exist.',
            ]
        );

        $post = new Post();
        $post->title = $validated['title'];
        $post->content = $validated['content'];
        $post->user_id = Auth::id();
        $post->save();

        $post->tags()->attach($validated['tags']); // sync tags

        return redirect()
            ->route('posts.create')
            ->with('success', [
                'message' => 'Post created!',
                'post_id' => $post->id,
            ]);
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
        $tags = Tag::all();
        return view('posts.edit', compact('post', 'tags'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'tags' => 'required|array',
            'tags.*' => 'exists:tags,id',
        ]);
        $post->title = $validated['title'];
        $post->content = $validated['content'];
        $post->save();
        $post->tags()->sync($validated['tags']); // sync tags
        return redirect()
            ->route('posts.show', $post->id)
            ->with('success', 'Post updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        $post->delete();
        return redirect()
            ->route('posts.display')
            ->with('success', 'Post deleted successfully!');
    }
}
