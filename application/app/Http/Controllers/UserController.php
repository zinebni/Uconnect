<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function follow(User $user)
    {
        if (Auth::id() === $user->id) {
            return redirect()->back()->with('error', 'Vous ne pouvez pas vous suivre vous-même.');
        }

        Auth::user()->following()->attach($user->id);
        return redirect()->back()->with('success', 'Vous suivez maintenant ' . $user->name);
    }

    public function unfollow(User $user)
    {
        Auth::user()->following()->detach($user->id);
        return redirect()->back()->with('success', 'Vous ne suivez plus ' . $user->name);
    }
} 