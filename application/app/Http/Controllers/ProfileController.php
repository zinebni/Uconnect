<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $imageUpdated = false;
        $newImageUrl = null;

        if ($request->hasFile('profile_image')) {
            // Supprimer l'ancienne image si elle existe
            if ($request->user()->profile_image) {
                Storage::disk('public')->delete($request->user()->profile_image);
            }

            // Stocker la nouvelle image
            $path = $request->file('profile_image')->store('profile-images', 'public');
            $request->user()->profile_image = $path;
            $imageUpdated = true;
            $newImageUrl = Storage::url($path);
        }

        $request->user()->save();

        $message = 'profile-updated';
        if ($imageUpdated) {
            $message = 'profile-updated-with-image';
        }

        return Redirect::route('profile.edit')
            ->with('status', $message)
            ->with('new_image_url', $newImageUrl);
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    /**
     * Display the user's public profile.
     */
    public function show(User $user): View
    {
        $posts = $user->posts()
            ->with([
                'user',
                'likes',
                'comments' => function($query) {
                    $query->whereNull('parent_id') // Seulement les commentaires principaux
                          ->with([
                              'user',
                              'likes',
                              'replies.user',
                              'replies.likes'
                          ])
                          ->latest();
                }
            ])
            ->latest()
            ->paginate(10);

        $isFollowing = Auth::check() ? Auth::user()->isFollowing($user) : false;
        $followersCount = $user->followers()->count();
        $followingCount = $user->following()->count();

        // Calculer les likes reçus
        $likesReceived = $user->posts()
            ->withCount('likes')
            ->get()
            ->sum('likes_count');

        return view('profile.show', compact('user', 'posts', 'isFollowing', 'followersCount', 'followingCount', 'likesReceived'));
    }

    /**
     * Récupérer les followers d'un utilisateur spécifique
     */
    public function getFollowers(Request $request, User $user)
    {
        try {
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
                        'is_following' => Auth::check() ? Auth::user()->isFollowing($follower) : false
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
     * Récupérer les utilisateurs suivis par un utilisateur spécifique
     */
    public function getFollowing(Request $request, User $user)
    {
        try {
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
                        'is_following' => Auth::check() ? Auth::user()->isFollowing($followedUser) : false
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
