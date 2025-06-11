<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Events\UserFollowed;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FollowController extends Controller
{
    public function follow(Request $request, User $user)
    {
        if (Auth::id() === $user->id) {
            if ($request->wantsJson() || $request->ajax() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
                return response()->json([
                    'success' => false,
                    'message' => 'Vous ne pouvez pas vous suivre vous-même.'
                ], 400);
            }
            return redirect()->back()->with('error', 'Vous ne pouvez pas vous suivre vous-même.');
        }

        // Vérifier si l'utilisateur ne suit pas déjà cette personne
        $alreadyFollowing = Auth::user()->following()->where('following_id', $user->id)->exists();

        if (!$alreadyFollowing) {
            Auth::user()->following()->attach($user->id);

            // Déclencher l'événement UserFollowed pour créer une notification
            UserFollowed::dispatch(Auth::user(), $user);

            $message = 'Vous suivez maintenant ' . $user->name;
        } else {
            $message = 'Vous suivez déjà ' . $user->name;
        }

        // Retourner JSON pour les requêtes AJAX
        if ($request->wantsJson() || $request->ajax() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            return response()->json([
                'success' => true,
                'following' => !$alreadyFollowing,
                'message' => $message
            ]);
        }

        return redirect()->back()->with('success', $message);
    }

    public function unfollow(Request $request, User $user)
    {
        $wasFollowing = Auth::user()->following()->where('following_id', $user->id)->exists();

        if ($wasFollowing) {
            Auth::user()->following()->detach($user->id);
            $message = 'Vous ne suivez plus ' . $user->name;
        } else {
            $message = 'Vous ne suiviez pas ' . $user->name;
        }

        // Retourner JSON pour les requêtes AJAX
        if ($request->wantsJson() || $request->ajax() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            return response()->json([
                'success' => true,
                'following' => false,
                'message' => $message
            ]);
        }

        return redirect()->back()->with('success', $message);
    }
}
