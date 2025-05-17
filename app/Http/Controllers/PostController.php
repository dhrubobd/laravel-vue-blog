<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
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

    public function userPosts()
    {
        $posts = auth()->user()->posts()->latest()->paginate(5);

        return Inertia::render('Posts/UserPosts', [
            'posts' => $posts
        ]);
    }

    public function show(String $postID)
    {
        try {
            $post = Post::where('id', $postID)
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
        } catch (\Throwable $th) {
            return redirect()->back()->with('error',$th->getMessage());
        }
    }

    public function create()
    {
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

        if ($request->hasFile('image')) {
            $image = $request->file('image');

            $fileName = time() . '.' . $image->getClientOriginalExtension();
            $filePath = 'uploads/' . $fileName;

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

    public function edit(String $post_id)
    {
        $post = Post::where('id', $post_id)->first();
        // Ensure only the post owner can edit
        if ($post->user_id !== auth()->id()) {
            return redirect()->back()->with('error', 'Unauthorized');
        }

        $tags = Tag::all(); // For tag selection

        return Inertia::render('Posts/Edit', [
            'post' => $post->load('tags'),
            'tags' => $tags
        ]);
    }

    public function update(Request $request, String $post_id)
    {
        $post = Post::where('id', $post_id)->first();
        // Ensure only the post owner can update
        if ($post->user_id !== auth()->id()) {
            return redirect()->back()->with('error', 'Unauthorized');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'visibility' => 'required|in:public,private',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
            'image' => 'nullable|image|max:2048',
        ]);

        // Update post data
        $post->update([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'visibility' => $validated['visibility'],
        ]);

        // Update image if provided
        if ($request->hasFile('image')) {
            if ($post->image) {
                File::delete(public_path('/' . $post->image));
                $image = $request->file('image');

                $fileName = time() . '.' . $image->getClientOriginalExtension();
                $filePath = 'uploads/' . $fileName;

                $image->move(public_path('uploads'), $fileName);
                $post->image = $filePath;
            }

            $post->save();
        }

        // Sync tags
        $post->tags()->sync($validated['tags'] ?? []);

        return redirect()->route('user.posts')->with('success', 'Post updated successfully');
    }

    public function getTags()
    {
        $tags = Tag::select('id', 'name')->get();
        return response()->json(['tags' => $tags]);
    }

    // Destroy function for deleting a post
    public function destroy(String $post_id)
    {
        $post = Post::where('id', $post_id)->first();
        if ($post->user_id !== auth()->id()) {
            return redirect()->back()->with('error', 'Unauthorized');
        }

        // Delete the image if it exists
        if ($post->image) {
            File::delete(public_path('/' . $post->image));
        }

        $post->delete();
        return redirect()->back()->with('success', 'Post deleted successfully');
    }
}
