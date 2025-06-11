<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'from_user_id',
        'type',
        'message',
        'data',
        'is_read'
    ];

    protected $casts = [
        'data' => 'array',
        'is_read' => 'boolean',
    ];

    // Types de notifications
    public const TYPE_FOLLOW = 'follow';
    public const TYPE_LIKE = 'like';
    public const TYPE_COMMENT = 'comment';
    public const TYPE_NEW_POST = 'new_post';

    /**
     * Utilisateur qui reçoit la notification
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Utilisateur qui a déclenché la notification
     */
    public function fromUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }

    /**
     * Créer une notification de manière sécurisée (évite les doublons)
     */
    public static function createSafely($userId, $fromUserId, $type, $message, $data = null)
    {
        // Vérifier s'il existe déjà une notification similaire récente (dans les 5 dernières minutes)
        $existingNotification = self::where('user_id', $userId)
            ->where('from_user_id', $fromUserId)
            ->where('type', $type)
            ->where('created_at', '>=', now()->subMinutes(5))
            ->first();

        // Si une notification récente existe déjà, ne pas en créer une nouvelle
        if ($existingNotification) {
            return $existingNotification;
        }

        // Créer la nouvelle notification
        return self::create([
            'user_id' => $userId,
            'from_user_id' => $fromUserId,
            'type' => $type,
            'message' => $message,
            'data' => $data
        ]);
    }

    /**
     * Marquer la notification comme lue
     */
    public function markAsRead(): void
    {
        $this->update(['is_read' => true]);
    }

    /**
     * Scope pour les notifications non lues
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    /**
     * Scope pour les notifications récentes
     */
    public function scopeRecent($query, $days = 7)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }
}
