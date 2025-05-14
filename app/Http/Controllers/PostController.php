<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Inertia\Inertia;


class PostController extends Controller
{
    public function index()
    {
        $posts = Post::where('visibility', 'public')
            ->with(['user', 'tags', 'likes'])
            ->latest()
            ->paginate(5); // 5 posts per page

        return Inertia::render('Posts/Index', [
            'posts' => $posts
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image',
            'visibility' => 'required|in:public,private',
        ]);

        $path = $request->file('image') ? $request->file('image')->store('posts', 'public') : null;

        Post::create([
            'user_id' => auth()->id(),
            'title' => $request->title,
            'content' => $request->content,
            'image' => $path,
            'visibility' => $request->visibility,
        ]);

        return redirect()->route('posts.index');
    }
}
