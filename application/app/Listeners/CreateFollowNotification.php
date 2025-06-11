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
        // Utiliser la méthode sécurisée pour créer la notification
        Notification::createSafely(
            $event->followed->id,
            $event->follower->id,
            Notification::TYPE_FOLLOW,
            $event->follower->name . ' a commencé à vous suivre',
            [
                'follower_id' => $event->follower->id,
                'follower_name' => $event->follower->name,
                'follower_profile_image' => $event->follower->profile_image
            ]
        );
    }
}
