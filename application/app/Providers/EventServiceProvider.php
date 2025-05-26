<?php

namespace App\Providers;

use App\Events\PostCommented;
use App\Events\PostCreated;
use App\Events\PostLiked;
use App\Events\UserFollowed;
use App\Listeners\CreateCommentNotification;
use App\Listeners\CreateFollowNotification;
use App\Listeners\CreateLikeNotification;
use App\Listeners\CreatePostNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        UserFollowed::class => [
            CreateFollowNotification::class,
        ],
        PostLiked::class => [
            CreateLikeNotification::class,
        ],
        PostCommented::class => [
            CreateCommentNotification::class,
        ],
        PostCreated::class => [
            CreatePostNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
