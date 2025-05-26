<?php

namespace App\Listeners;

use App\Events\PostCommented;
use App\Models\Notification;

class CreateCommentNotification
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
    public function handle(PostCommented $event): void
    {
        // Ne pas créer de notification si l'utilisateur commente son propre post
        if ($event->user->id === $event->post->user_id) {
            return;
        }

        // Créer une notification pour le propriétaire du post
        Notification::create([
            'user_id' => $event->post->user_id,
            'from_user_id' => $event->user->id,
            'type' => Notification::TYPE_COMMENT,
            'message' => $event->user->name . ' a commenté votre publication',
            'data' => [
                'post_id' => (string)$event->post->id,
                'comment_id' => (string)$event->comment->id,
                'comment_content' => substr($event->comment->content, 0, 50) . '...',
                'post_content' => $event->post->content ? substr($event->post->content, 0, 50) . '...' : 'Publication',
                'commenter_name' => $event->user->name,
                'commenter_profile_image' => $event->user->profile_image
            ]
        ]);
    }
}
