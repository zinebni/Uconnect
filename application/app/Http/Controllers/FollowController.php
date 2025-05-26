<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Events\UserFollowed;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FollowController extends Controller
{
    public function follow(User $user)
    {
        if (Auth::id() === $user->id) {
            return redirect()->back()->with('error', 'Vous ne pouvez pas vous suivre vous-même.');
        }

        // Vérifier si l'utilisateur ne suit pas déjà cette personne
        if (!Auth::user()->following()->where('following_id', $user->id)->exists()) {
            Auth::user()->following()->attach($user->id);

            // Déclencher l'événement UserFollowed pour créer une notification
            UserFollowed::dispatch(Auth::user(), $user);
        }

        return redirect()->back()->with('success', 'Vous suivez maintenant ' . $user->name);
    }

    public function unfollow(User $user)
    {
        Auth::user()->following()->detach($user->id);
        return redirect()->back()->with('success', 'Vous ne suivez plus ' . $user->name);
    }
}
