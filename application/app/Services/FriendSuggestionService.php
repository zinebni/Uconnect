<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;

class FriendSuggestionService
{
    /**
     * Obtenir des suggestions d'amis pour l'utilisateur connecté
     */
    public function getSuggestions(int $limit = 5): Collection
    {
        $user = Auth::user();
        $suggestions = collect();

        // 1. Suggestions basées sur les amis communs
        $mutualFriendsSuggestions = $this->getMutualFriendsSuggestions($user, $limit);
        $suggestions = $suggestions->merge($mutualFriendsSuggestions);

        // 2. Suggestions basées sur l'activité récente (nouveaux utilisateurs actifs)
        if ($suggestions->count() < $limit) {
            $activeSuggestions = $this->getActiveUsersSuggestions($user, $limit - $suggestions->count());
            $suggestions = $suggestions->merge($activeSuggestions);
        }

        // 3. Suggestions aléatoires si pas assez
        if ($suggestions->count() < $limit) {
            $randomSuggestions = $this->getRandomSuggestions($user, $limit - $suggestions->count());
            $suggestions = $suggestions->merge($randomSuggestions);
        }

        return $suggestions->unique('id')->take($limit);
    }

    /**
     * Suggestions basées sur les amis communs
     */
    private function getMutualFriendsSuggestions(User $user, int $limit): Collection
    {
        try {
            // Obtenir les IDs des utilisateurs que l'utilisateur suit déjà
            $followingIds = $user->following()->pluck('following_id')->toArray();
            $followingIds[] = $user->id; // Exclure l'utilisateur lui-même

            // Si l'utilisateur ne suit personne, retourner une collection vide
            if (count($followingIds) <= 1) {
                return collect();
            }

            // Trouver les utilisateurs qui sont suivis par les amis de l'utilisateur
            return User::whereHas('followers', function ($query) use ($user) {
                $query->whereIn('follower_id', $user->following()->pluck('following_id'));
            })
            ->whereNotIn('id', $followingIds)
            ->limit($limit)
            ->get();
        } catch (\Exception $e) {
            return collect();
        }
    }

    /**
     * Suggestions basées sur l'activité récente
     */
    private function getActiveUsersSuggestions(User $user, int $limit): Collection
    {
        try {
            $followingIds = $user->following()->pluck('following_id')->toArray();
            $followingIds[] = $user->id;

            return User::whereNotIn('id', $followingIds)
                ->whereHas('posts', function ($query) {
                    $query->where('created_at', '>=', now()->subDays(7));
                })
                ->withCount(['posts' => function ($query) {
                    $query->where('created_at', '>=', now()->subDays(7));
                }])
                ->orderBy('posts_count', 'desc')
                ->limit($limit)
                ->get();
        } catch (\Exception $e) {
            return collect();
        }
    }

    /**
     * Suggestions aléatoires
     */
    private function getRandomSuggestions(User $user, int $limit): Collection
    {
        try {
            $followingIds = $user->following()->pluck('following_id')->toArray();
            $followingIds[] = $user->id;

            return User::whereNotIn('id', $followingIds)
                ->inRandomOrder()
                ->limit($limit)
                ->get();
        } catch (\Exception $e) {
            return collect();
        }
    }

    /**
     * Obtenir les nouveaux followers
     */
    public function getRecentFollowers(int $days = 7): Collection
    {
        try {
            $user = Auth::user();

            return $user->followers()
                ->wherePivot('created_at', '>=', now()->subDays($days))
                ->orderByPivot('created_at', 'desc')
                ->get();
        } catch (\Exception $e) {
            return collect();
        }
    }

    /**
     * Obtenir l'activité récente des amis (nouveaux posts)
     */
    public function getFriendsRecentActivity(int $limit = 10): Collection
    {
        try {
            $user = Auth::user();
            $followingIds = $user->following()->pluck('following_id');

            return User::whereIn('id', $followingIds)
                ->whereHas('posts', function ($query) {
                    $query->where('created_at', '>=', now()->subHours(24));
                })
                ->withCount(['posts' => function ($query) {
                    $query->where('created_at', '>=', now()->subHours(24));
                }])
                ->having('posts_count', '>', 0)
                ->orderBy('posts_count', 'desc')
                ->limit($limit)
                ->get();
        } catch (\Exception $e) {
            return collect();
        }
    }
}
