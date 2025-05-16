<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Like;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function toggle(String $post_id)
    {
        $post = Post::where('id',$post_id)->first();
        $like = $post->likes()->where('user_id', Auth::id())->first();
        if ($like) {
            $like->delete();
        } else {
            $post->likes()->create(['user_id' => Auth::id()]);
        }

        //return response()->json(['likes_count' => $post->likes()->count()]);
        return redirect()->back();
    }
}
