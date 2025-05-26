<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('home');
});

Route::get('/home', [PostController::class, 'index'])->middleware(['auth', 'verified'])->name('home');

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard/followers', [DashboardController::class, 'getFollowers'])->name('dashboard.followers');
    Route::get('/dashboard/following', [DashboardController::class, 'getFollowing'])->name('dashboard.following');

    // Routes pour les followers/following d'un utilisateur spécifique
    Route::get('/users/{user}/followers', [ProfileController::class, 'getFollowers'])->name('users.followers');
    Route::get('/users/{user}/following', [ProfileController::class, 'getFollowing'])->name('users.following');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/profile/{user}', [ProfileController::class, 'show'])->name('profile.show');

    Route::get('/search', [SearchController::class, 'index'])->name('search');

    Route::post('/users/{user}/follow', [FollowController::class, 'follow'])->name('users.follow');
    Route::delete('/users/{user}/follow', [FollowController::class, 'unfollow'])->name('users.unfollow');

    Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.show');
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
    Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');
    Route::post('/posts/{post}/like', [PostController::class, 'like'])->name('posts.like');
    Route::delete('/posts/{post}/like', [PostController::class, 'unlike'])->name('posts.unlike');
    Route::get('/posts/{post}/likes', [PostController::class, 'getLikes'])->name('posts.likes');

    Route::post('/posts/{post}/comments', [PostController::class, 'storeComment'])->name('posts.comments.store');
    Route::delete('/posts/{post}/comments/{comment}', [PostController::class, 'destroyComment'])->name('posts.comments.destroy');

    // Routes pour la messagerie
    Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/{user}', [MessageController::class, 'show'])->name('messages.show');
    Route::post('/messages/{user}', [MessageController::class, 'store'])->name('messages.store');

    // Routes pour les notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{notification}/mark-as-read', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
    Route::delete('/notifications/{notification}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
    Route::post('/notifications/mark-all-as-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllAsRead');
    Route::delete('/notifications', [NotificationController::class, 'destroyAll'])->name('notifications.destroyAll');
    Route::get('/notifications/unread', [NotificationController::class, 'getUnread'])->name('notifications.unread');
});

require __DIR__.'/auth.php';
