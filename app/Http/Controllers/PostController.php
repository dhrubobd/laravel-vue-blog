<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Tag;
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

    public function show(String $postID)
    {
        $post = Post::where('id',$postID)
        ->where('visibility', 'public')
        ->first();
        $post->load(['user', 'tags', 'comments.user', 'likes']);
        $isBookmarked = auth()->check() ? auth()->user()->bookmarks()->where('post_id', $post->id)->exists() : false;
        $isLiked = auth()->check() ? auth()->user()->likes()->where('post_id', $post->id)->exists() : false;

        return Inertia::render('Posts/Show', [
            'post' => $post,
            'isBookmarked' => $isBookmarked,
            'isLiked' => $isLiked
        ]);
    }

    public function create(){
        return Inertia::render('Posts/Create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'visibility' => 'required|in:public,private',
            'tags' => 'nullable|array'
        ]);

        if($request->hasFile('image')){
            $image = $request->file('image');

            $fileName = time().'.'.$image->getClientOriginalExtension();
            $filePath = 'uploads/'.$fileName;

            $image->move(public_path('uploads'), $fileName);
        }

        //$path = $request->file('image') ? $request->file('image')->store('posts', 'public') : null;

        $post = Post::create([
            'user_id' => auth()->id(),
            'title' => $request->title,
            'content' => $request->content,
            'image' => $filePath,
            'visibility' => $request->visibility,
        ]);

        if ($request->tags) {
            $post->tags()->sync($request->tags);
        }

        return redirect()->route('posts.index');
    }

    public function getTags()
    {
        $tags = Tag::select('id', 'name')->get();
        return response()->json(['tags' => $tags]);
    }
}
