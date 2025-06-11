<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CommentController extends Controller
{
    public function store(Request $request, Post $post)
    {
        $request->validate([
            'content' => 'required|string|max:500',
            'parent_id' => 'nullable|exists:comments,id',
        ]);

        // Déterminer la profondeur du commentaire
        $depth = 0;
        $parentComment = null;

        if ($request->parent_id) {
            $parentComment = Comment::find($request->parent_id);
            if ($parentComment) {
                // Limiter à 1 niveau de réponse pour éviter une hiérarchie trop profonde
                $depth = $parentComment->depth >= 1 ? 1 : $parentComment->depth + 1;
            }
        }

        $comment = Comment::create([
            'content' => $request->content,
            'user_id' => Auth::id(),
            'post_id' => $post->id,
            'parent_id' => $request->parent_id,
            'depth' => $depth,
        ]);

        // Charger les relations pour la réponse
        $comment->load('user');

        // Réponse AJAX
        if ($request->wantsJson() || $request->ajax() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            return response()->json([
                'success' => true,
                'comment' => [
                    'id' => $comment->id,
                    'content' => $comment->content,
                    'created_at' => $comment->created_at,
                    'parent_id' => $comment->parent_id,
                    'depth' => $comment->depth,
                    'likes_count' => 0,
                    'replies_count' => 0,
                    'is_liked' => false,
                    'user' => [
                        'id' => $comment->user->id,
                        'name' => $comment->user->name,
                        'profile_image' => $comment->user->profile_image,
                    ]
                ],
                'comments_count' => $post->comments()->count(),
                'message' => $comment->parent_id ? 'Réponse ajoutée avec succès' : 'Commentaire ajouté avec succès'
            ]);
        }

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

    /**
     * Supprimer un commentaire via AJAX
     */
    public function destroyAjax(Comment $comment)
    {
        try {
            // Vérifier que l'utilisateur est l'auteur du commentaire
            if ($comment->user_id !== Auth::id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Action non autorisée'
                ], 403);
            }

            // Compter les réponses avant suppression
            $repliesCount = $comment->replies()->count();

            // Supprimer le commentaire (les réponses seront supprimées en cascade)
            $comment->delete();

            return response()->json([
                'success' => true,
                'message' => $repliesCount > 0
                    ? "Commentaire et {$repliesCount} réponse(s) supprimé(s) avec succès"
                    : 'Commentaire supprimé avec succès',
                'replies_count' => $repliesCount
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur lors de la suppression du commentaire: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression'
            ], 500);
        }
    }
}
