<?php

namespace App\Http\Controllers;

use App\Models\Bookmark;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;


class BookmarkController extends Controller
{
    // Display Bookmarked Posts with Pagination
    public function index()
    {
        $bookmarks = Bookmark::where('user_id', Auth::id())
            ->with('post.tags', 'post.user')
            ->latest()
            ->paginate(5); // 5 bookmarks per page

        return Inertia::render('Bookmarks', [
            'bookmarks' => $bookmarks
        ]);
    }

    // Toggle a Bookmark on Sigle Post Page
    public function toggle(String $post_id)
    {
        $user = Auth::user();
        $post = Post::where('id', $post_id)->first();

        if ($user->bookmarks()->where('post_id', $post->id)->exists()) {
            $user->bookmarks()->detach($post->id);
        } else {
            $user->bookmarks()->attach($post->id);
        }
        return redirect()->back();
    }

    // Remove a Bookmark
    public function destroy(Bookmark $bookmark)
    {
        if ($bookmark->user_id === Auth::id()) {
            $bookmark->delete();
            return redirect()->back()->with('success', 'Bookmark removed successfully.');
        }

        return redirect()->back()->with('error', 'Unauthorized');
    }
}
