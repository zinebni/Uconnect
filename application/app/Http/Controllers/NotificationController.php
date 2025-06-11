<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Afficher toutes les notifications de l'utilisateur
     */
    public function index()
    {
        /** @var User $user */
        $user = Auth::user();
        $notifications = $user->notifications()
            ->with('fromUser')
            ->latest()
            ->paginate(20);

        return view('notifications.index', compact('notifications'));
    }

    /**
     * Marquer une notification comme lue
     */
    public function markAsRead(Notification $notification)
    {
        // Vérifier que la notification appartient à l'utilisateur connecté
        if ($notification->user_id !== Auth::id()) {
            abort(403);
        }

        $notification->markAsRead();

        return response()->json(['success' => true]);
    }

    /**
     * Supprimer une notification
     */
    public function destroy(Notification $notification)
    {
        // Vérifier que la notification appartient à l'utilisateur connecté
        if ($notification->user_id !== Auth::id()) {
            abort(403);
        }

        $notification->delete();

        return response()->json(['success' => true]);
    }

    /**
     * Marquer toutes les notifications comme lues
     */
    public function markAllAsRead()
    {
        /** @var User $user */
        $user = Auth::user();
        $user->unreadNotifications()->update(['is_read' => true]);

        return response()->json(['success' => true]);
    }

    /**
     * Supprimer toutes les notifications
     */
    public function destroyAll()
    {
        /** @var User $user */
        $user = Auth::user();
        $user->notifications()->delete();

        return response()->json(['success' => true]);
    }

    /**
     * Récupérer les notifications non lues (pour AJAX)
     */
    public function getUnread()
    {
        /** @var User $user */
        $user = Auth::user();
        $notifications = $user->unreadNotifications()
            ->with('fromUser')
            ->latest()
            ->limit(10)
            ->get();

        return response()->json([
            'notifications' => $notifications,
            'count' => $notifications->count()
        ]);
    }

    /**
     * Créer une nouvelle notification
     */
    public static function create($userId, $fromUserId, $type, $message, $data = null)
    {
        return Notification::create([
            'user_id' => $userId,
            'from_user_id' => $fromUserId,
            'type' => $type,
            'message' => $message,
            'data' => $data
        ]);
    }
}
