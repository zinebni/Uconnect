<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function index()
    {
        $users = User::where('id', '!=', Auth::id())
            ->where(function ($query) {
                $query->whereHas('followers', function ($q) {
                    $q->where('follower_id', Auth::id());
                })->orWhereHas('following', function ($q) {
                    $q->where('following_id', Auth::id());
                });
            })
            ->with(['sentMessages', 'receivedMessages'])
            ->get()
            ->map(function ($user) {
                // Récupérer le dernier message entre l'utilisateur connecté et cet utilisateur
                $lastMessage = Message::where(function ($query) use ($user) {
                    $query->where('sender_id', Auth::id())
                        ->where('receiver_id', $user->id);
                })->orWhere(function ($query) use ($user) {
                    $query->where('sender_id', $user->id)
                        ->where('receiver_id', Auth::id());
                })
                ->latest()
                ->first();
                
                // Compter les messages non lus de cet utilisateur spécifique
                $unreadCount = Message::where('sender_id', $user->id)
                    ->where('receiver_id', Auth::id())
                    ->where('is_read', false)
                    ->count();

                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'profile_image' => $user->profile_image,
                    'last_message' => $lastMessage,
                    'unread_count' => $unreadCount,
                ];
            });

        return view('messages.index', compact('users'));
    }

    public function show(User $user)
    {
        // Vérifier si l'utilisateur est autorisé à envoyer des messages
        if (!$user->followers()->where('follower_id', Auth::id())->exists() && 
            !$user->following()->where('following_id', Auth::id())->exists()) {
            return redirect()->route('messages.index')
                ->with('error', 'Vous ne pouvez pas envoyer de messages à cet utilisateur.');
        }

        // Récupérer uniquement les messages entre l'utilisateur connecté et l'utilisateur sélectionné
        $messages = Message::where(function ($query) use ($user) {
            $query->where('sender_id', Auth::id())
                ->where('receiver_id', $user->id);
        })->orWhere(function ($query) use ($user) {
            $query->where('sender_id', $user->id)
                ->where('receiver_id', Auth::id());
        })
        ->orderBy('created_at', 'asc')
        ->get();

        // Marquer uniquement les messages non lus de cet utilisateur comme lus
        Message::where('sender_id', $user->id)
            ->where('receiver_id', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return view('messages.show', compact('messages', 'user'));
    }

    public function store(Request $request, User $user)
    {
        // Vérifier si l'utilisateur est autorisé à envoyer des messages
        if (!$user->followers()->where('follower_id', Auth::id())->exists() && 
            !$user->following()->where('following_id', Auth::id())->exists()) {
            return redirect()->route('messages.index')
                ->with('error', 'Vous ne pouvez pas envoyer de messages à cet utilisateur.');
        }

        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $user->id,
            'content' => $request->content,
        ]);

        return back();
    }
} 