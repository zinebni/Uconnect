<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserFollowed
{
    use Dispatchable, SerializesModels;

    public User $follower;
    public User $followed;

    /**
     * Create a new event instance.
     */
    public function __construct(User $follower, User $followed)
    {
        $this->follower = $follower;
        $this->followed = $followed;
    }
}
