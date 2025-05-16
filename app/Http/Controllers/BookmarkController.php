<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookmarkController extends Controller
{
    public function index()
    {
        $bookmarks = Auth::user()->bookmarks()->with('user')->latest()->get();
        return inertia('Bookmarks/Index', compact('bookmarks'));
    }

    public function toggle(String $post_id)
    {
        $user = Auth::user();
        $post=Post::where('id',$post_id)->first();
        
        if ($user->bookmarks()->where('post_id', $post->id)->exists()) {
            $user->bookmarks()->detach($post->id);
        } else {
            $user->bookmarks()->attach($post->id);
        }
        return redirect()->back();
        //return response()->json([
        //    'bookmarked' => $user->bookmarks()->where('post_id', $post->id)->exists()
        //]);
    }
}
