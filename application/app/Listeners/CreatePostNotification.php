<?php

namespace App\Listeners;

use App\Events\PostCreated;
use App\Models\Notification;

class CreatePostNotification
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
    public function handle(PostCreated $event): void
    {
        // Récupérer tous les followers de l'utilisateur qui a créé le post
        $followers = $event->user->followers;

        // Créer une notification pour chaque follower
        foreach ($followers as $follower) {
            Notification::create([
                'user_id' => $follower->id,
                'from_user_id' => $event->user->id,
                'type' => Notification::TYPE_NEW_POST,
                'message' => $event->user->name . ' a publié quelque chose de nouveau',
                'data' => [
                    'post_id' => (string)$event->post->id,
                    'post_content' => $event->post->content ? substr($event->post->content, 0, 100) . '...' : 'Nouvelle publication',
                    'author_name' => $event->user->name,
                    'author_profile_image' => $event->user->profile_image,
                    'has_image' => !empty($event->post->image_path),
                    'has_video' => !empty($event->post->video_path)
                ]
            ]);
        }
    }
}
