<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\CommentLike;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentLikeController extends Controller
{
    /**
     * Liker ou unliker un commentaire
     */
    public function toggle(Request $request, Comment $comment)
    {
        try {
            $user = Auth::user();

            // Vérifier si l'utilisateur a déjà liké ce commentaire
            $existingLike = CommentLike::where('user_id', $user->id)
                                     ->where('comment_id', $comment->id)
                                     ->first();

            if ($existingLike) {
                // Unlike - supprimer le like
                $existingLike->delete();
                $liked = false;
                $message = 'Like retiré du commentaire';
            } else {
                // Like - créer un nouveau like
                CommentLike::create([
                    'user_id' => $user->id,
                    'comment_id' => $comment->id,
                ]);
                $liked = true;
                $message = 'Commentaire liké';

                // TODO: Créer une notification pour l'auteur du commentaire
                // (si ce n'est pas l'utilisateur lui-même)
            }

            // Compter le nombre total de likes
            $likesCount = $comment->likes()->count();

            return response()->json([
                'success' => true,
                'liked' => $liked,
                'likes_count' => $likesCount,
                'message' => $message
            ]);

        } catch (\Exception $e) {
            \Log::error('Erreur lors du like de commentaire: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du like'
            ], 500);
        }
    }

    /**
     * Obtenir la liste des utilisateurs qui ont liké un commentaire
     */
    public function getLikes(Request $request, Comment $comment)
    {
        try {
            $likes = $comment->likes()
                ->with('user:id,name,profile_image')
                ->latest()
                ->get()
                ->map(function ($like) {
                    return [
                        'id' => $like->user->id,
                        'name' => $like->user->name,
                        'profile_image' => $like->user->profile_image,
                        'profile_image_url' => $like->user->profile_image
                            ? \Storage::url($like->user->profile_image)
                            : null,
                        'liked_at' => $like->created_at->diffForHumans(),
                    ];
                });

            return response()->json([
                'success' => true,
                'likes' => $likes,
                'total' => $likes->count()
            ]);

        } catch (\Exception $e) {
            \Log::error('Erreur lors de la récupération des likes: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'error' => 'Erreur serveur: ' . $e->getMessage()
            ], 500);
        }
    }
}
