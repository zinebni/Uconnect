<?php

namespace App\Events;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PostCreated
{
    use Dispatchable, SerializesModels;

    public User $user;
    public Post $post;

    /**
     * Create a new event instance.
     */
    public function __construct(User $user, Post $post)
    {
        $this->user = $user;
        $this->post = $post;
    }
}
