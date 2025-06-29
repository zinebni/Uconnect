<?php

namespace App\Listeners;

use App\Events\PostLiked;
use App\Models\Notification;

class CreateLikeNotification
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(PostLiked $event): void
    {
        // Ne pas créer de notification si l'utilisateur like son propre post
        if ($event->user->id === $event->post->user_id) {
            return;
        }

        // Créer une notification pour le propriétaire du post de manière sécurisée
        Notification::createSafely(
            $event->post->user_id,
            $event->user->id,
            Notification::TYPE_LIKE,
            $event->user->name . ' a aimé votre publication',
            [
                'post_id' => (string)$event->post->id, // Convertir en string pour être sûr
                'post_content' => $event->post->content ? substr($event->post->content, 0, 50) . '...' : 'Publication',
                'liker_name' => $event->user->name,
                'liker_profile_image' => $event->user->profile_image
            ]
        );
    }
}
