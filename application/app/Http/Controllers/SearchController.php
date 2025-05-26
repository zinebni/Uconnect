<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'q' => 'required|string|min:1|max:50'
        ]);

        $query = trim($request->input('q'));
        $searchTerms = explode(' ', strtolower($query));
        
        $users = User::where(function($q) use ($searchTerms) {
            foreach ($searchTerms as $term) {
                $q->orWhere(function($query) use ($term) {
                    $query->where(DB::raw('LOWER(name)'), 'like', '%' . $term . '%')
                          ->orWhere(DB::raw('LOWER(email)'), 'like', '%' . $term . '%');
                });
            }
        })
        ->orderBy('name')
        ->paginate(10)
        ->withQueryString();

        return view('search', compact('users', 'query'));
    }
} 