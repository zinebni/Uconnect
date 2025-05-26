<?php

namespace App\Listeners;

use App\Events\UserFollowed;
use App\Models\Notification;

class CreateFollowNotification
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
    public function handle(UserFollowed $event): void
    {
        // Créer une notification pour l'utilisateur qui a été suivi
        Notification::create([
            'user_id' => $event->followed->id,
            'from_user_id' => $event->follower->id,
            'type' => Notification::TYPE_FOLLOW,
            'message' => $event->follower->name . ' a commencé à vous suivre',
            'data' => [
                'follower_id' => $event->follower->id,
                'follower_name' => $event->follower->name,
                'follower_profile_image' => $event->follower->profile_image
            ]
        ]);
    }
}
