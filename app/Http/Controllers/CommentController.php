<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Events\CommentPosted;
use App\Events\CommentDeleted;

class CommentController extends Controller
{
    public function store(Request $request, String $post_id)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
            'parent_id' => 'nullable'
        ]);

        //dd($request->all());
        //dd($post_id);
        try {
            $comment = Comment::create([
                'user_id' => Auth::id(),
                'post_id' => $post_id,
                'content' => $request->content,
                'parent_id' => $request->parent_id
            ]);
            broadcast(new CommentPosted($comment))->toOthers();
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }

        return redirect()->back()->with('success', 'Comment Posted');


        //return response()->json($comment, 201);

    }

    /*
    public function destroy(Comment $comment)
    {
        if (Auth::id() === $comment->user_id || Auth::id() === $comment->post->user_id) {
            $comment->delete();
            return response()->json(['message' => 'Comment deleted']);
        }

        return response()->json(['message' => 'Unauthorized'], 403);
    }
        */
    public function destroy(String $post_id, String $comment_id)
    {
        $post = Post::where('id',$post_id)->first();
        $comment = Comment::where('id',$comment_id)->first();
        if ($comment->user_id == auth()->id() || $post->user_id == auth()->id()) {
            $comment->delete();

            // Broadcast deletion
            broadcast(new CommentDeleted($comment->id))->toOthers();

            return response()->json(['message' => 'Comment deleted successfully']);
        }

        return response()->json(['message' => 'Unauthorized'], 403);
    }
}
