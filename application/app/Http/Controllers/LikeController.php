<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    /**
     * Like a post (AJAX avec Axios)
     *
     * @param Post $post
     * @return JsonResponse
     */
    public function store(Post $post): JsonResponse
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['error' => 'Vous devez être connecté.'], 401);
        }

        $existingLike = $post->likes()->where('user_id', $user->id)->exists();

        if (!$existingLike) {
            $post->likes()->create([
                'user_id' => $user->id
            ]);
        }

        $likesCount = $post->likes()->count();

        return response()->json([
            'message' => 'Post aimé avec succès !',
            'likes' => $likesCount
        ]);
    }

    /**
     * Unlike a post (AJAX avec Axios)
     *
     * @param Post $post
     * @return JsonResponse
     */
    public function destroy(Post $post): JsonResponse
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['error' => 'Vous devez être connecté.'], 401);
        }

        $post->likes()->where('user_id', $user->id)->delete();

        $likesCount = $post->likes()->count();

        return response()->json([
            'message' => 'Like retiré avec succès !',
            'likes' => $likesCount
        ]);
    }
}
