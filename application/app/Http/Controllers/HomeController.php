<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        $following_ids = Auth::user()->following()->pluck('users.id');
        $posts = Post::whereIn('user_id', $following_ids)
            ->orWhere('user_id', Auth::id())
            ->with(['user', 'likes', 'comments.user'])
            ->latest()
            ->paginate(10);

        return view('home', compact('posts'));
    }

    public function search(Request $request)
    {
        $query = $request->input('query');
        $users = User::where('name', 'like', "%{$query}%")
            ->orWhere('email', 'like', "%{$query}%")
            ->paginate(10);

        return view('search', compact('users', 'query'));
    }
}
