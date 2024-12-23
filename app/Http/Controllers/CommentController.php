<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request, Post $post)
    {
        $request->validate([
            'content' => 'required|max:1000',
        ]);

        $comment = new Comment();
        $comment->content = $request->content;
        $comment->user_id = Auth::id();
        $comment->post_id = $post->id;
        $comment->save();

        return redirect()->route('posts.show', $post->id)->with('success', 'Comment added successfully.');
    }

    public function destroy(Comment $comment)
    {
        // Hanya komentar milik user yang bisa dihapus
        if ($comment->user_id !== Auth::id()) {
            return redirect()->back()->with('error', 'You can only delete your own comments.');
        }

        $comment->delete();

        return redirect()->back()->with('success', 'Comment deleted successfully.');
    }
}
