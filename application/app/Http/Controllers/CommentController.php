<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request, Post $post)
    {
        $request->validate([
            'content' => 'required|string|max:500'
        ]);

        $post->comments()->create([
            'user_id' => Auth::id(),
            'content' => $request->content
        ]);

        return redirect()->back()->with('success', 'Commentaire ajouté avec succès !');
    }

    public function destroy(Post $post, $commentId)
    {
        $comment = $post->comments()->where('id', $commentId)->firstOrFail();

        if ($comment->user_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Action non autorisée');
        }

        $comment->delete();

        return redirect()->back()->with('success', 'Commentaire supprimé avec succès !');
    }
}
