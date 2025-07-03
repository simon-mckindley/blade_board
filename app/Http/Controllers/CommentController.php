<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Post $post)
    {
        $validated = $request->validate([
            'comment' => 'required|string|min:3|max:1000',
        ], [
            'comment.required' => 'Please write a comment before submitting.',
        ]);

        try {
            $comment = new Comment();
            $comment->content = $validated['comment'];
            $comment->user_id = Auth::id();
            $comment->post_id = $post->id;
            $comment->save();

            return redirect()->route('posts.show', $post->id)
                ->with('alert', [
                    'type' => 'success',
                    'message' => 'Comment added successfully!'
                ]);
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('alert', [
                    'type' => 'error',
                    'message' => 'There was a problem creating the comment: ' . $e->getMessage(),
                ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment)
    {
        // Ensure the authenticated user or admin can only delete their comment
        if (Auth::id() !== $comment->user_id && !Auth::user()->isAdmin()) {
            return back()
                ->with('alert', [
                    'type' => 'error',
                    'message' => 'You do not have permission for this comment.',
                ]);
        }

        $comment->delete();
        return back()
            ->with('alert', [
                'type' => 'info',
                'message' => 'The comment has been deleted.'
            ]);
    }
}
