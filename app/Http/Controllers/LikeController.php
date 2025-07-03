<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function toggle(Post $post)
    {
        $user = Auth::user();

        if ($post->likes()->where('user_id', $user->id)->exists()) {
            // Unlike
            $post->likes()->where('user_id', $user->id)->delete();
            return back()->with('alert', ['type' => 'info', 'message' => 'Like removed']);
        }

        // Like
        $post->likes()->create(['user_id' => $user->id]);
        return back()->with('alert', ['type' => 'success', 'message' => 'Post liked!']);
    }

}
