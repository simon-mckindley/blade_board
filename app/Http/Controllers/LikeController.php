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
            $post->likes()->where('user_id', $user->id)->delete();
            return response()->json(['liked' => false, 'message' => 'Like removed']);
        }

        $post->likes()->create(['user_id' => $user->id]);
        return response()->json(['liked' => true, 'message' => 'Post liked!']);
    }
}
