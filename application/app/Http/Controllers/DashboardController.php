<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Afficher le dashboard de l'utilisateur
     */
    public function index(): View
    {
        $user = Auth::user();

        // Récupérer les posts de l'utilisateur avec toutes les relations
        $posts = $user->posts()
            ->with(['user', 'likes.user', 'comments.user'])
            ->latest()
            ->paginate(10);

        // Statistiques de l'utilisateur
        $stats = [
            'posts_count' => $user->posts()->count(),
            'followers_count' => $user->followers()->count(),
            'following_count' => $user->following()->count(),
            'likes_received' => $user->posts()->withCount('likes')->get()->sum('likes_count'),
        ];

        // Récupérer les followers et following pour les modals
        $followers = $user->followers()->latest()->get();
        $following = $user->following()->latest()->get();

        return view('dashboard', compact('posts', 'stats', 'followers', 'following'));
    }

    /**
     * Récupérer les followers d'un utilisateur
     */
    public function getFollowers(Request $request)
    {
        try {
            $user = Auth::user();

            if (!$user) {
                return response()->json(['error' => 'User not authenticated'], 401);
            }

            $followers = $user->followers()
                ->select('users.id', 'users.name', 'users.profile_image')
                ->latest('follows.created_at')
                ->get()
                ->map(function ($follower) {
                    return [
                        'id' => $follower->id,
                        'name' => $follower->name,
                        'profile_image' => $follower->profile_image,
                        'profile_image_url' => $follower->profile_image
                            ? \Storage::url($follower->profile_image)
                            : null,
                        'is_following' => Auth::user()->isFollowing($follower)
                    ];
                });

            return response()->json([
                'success' => true,
                'followers' => $followers,
                'total' => $followers->count()
            ]);

        } catch (\Exception $e) {
            \Log::error('Error in getFollowers: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Server error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Récupérer les utilisateurs suivis
     */
    public function getFollowing(Request $request)
    {
        try {
            $user = Auth::user();

            if (!$user) {
                return response()->json(['error' => 'User not authenticated'], 401);
            }

            $following = $user->following()
                ->select('users.id', 'users.name', 'users.profile_image')
                ->latest('follows.created_at')
                ->get()
                ->map(function ($followedUser) {
                    return [
                        'id' => $followedUser->id,
                        'name' => $followedUser->name,
                        'profile_image' => $followedUser->profile_image,
                        'profile_image_url' => $followedUser->profile_image
                            ? \Storage::url($followedUser->profile_image)
                            : null,
                        'is_following' => true // Par définition, on suit ces utilisateurs
                    ];
                });

            return response()->json([
                'success' => true,
                'following' => $following,
                'total' => $following->count()
            ]);

        } catch (\Exception $e) {
            \Log::error('Error in getFollowing: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Server error: ' . $e->getMessage()
            ], 500);
        }
    }
}
