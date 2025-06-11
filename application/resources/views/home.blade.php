<x-app-layout>
    <!-- Create Post Form - Large Container -->
    <div class="w-full max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 mb-10">
        <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- Zone de saisie -->
            <div class="bg-white dark:bg-indigo-800 p-4 rounded-xl shadow-md border border-gray-200 dark:border-indigo-700">
                <textarea name="content" rows="4"
                    class="w-full bg-white dark:bg-indigo-900 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-300 border border-gray-300 dark:border-indigo-700 rounded-md shadow-sm
                           focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:border-indigo-500 dark:focus:border-indigo-400 resize-none"
                    placeholder="Quoi de neuf ?"></textarea>

                 <!-- Prévisualisation des médias -->
             <div id="media-preview" class="mt-4">
                <div id="image-preview" class="hidden  p-4 rounded-sm shadow-md ">
                    <img src="" alt="Prévisualisation" class="max-h-48 rounded-lg">
                    <button type="button" onclick="removeMedia('image')" class="mt-2 text-red-600 hover:text-red-900">
                        Supprimer l'image
                    </button>
                </div>
                <div id="video-preview" class="hidden bg-white p-4 rounded-sm shadow-md border border-gray-200">
                    <video controls class="max-h-48 rounded-lg">
                        <source src="" type="video/mp4">
                        Votre navigateur ne supporte pas la lecture de vidéos.
                    </video>
                    <button aria-label="Supprimer la vidéo" type="button" onclick="removeMedia('video')" class="mt-2 text-red-600 hover:text-red-900">
                        Supprimer la vidéo
                    </button>
                </div>
            </div>
            </div>




            <!-- Zone de sélection fichiers -->
            <div class=" p-4 rounded-sm  ">
                <div class="flex items-center justify-between">
                    <div class="flex space-x-4">
                        <label class="cursor-pointer">
                            <input type="file" name="image" class="hidden" accept="image/*" onchange="previewImage(this)">
                            <span class="flex items-center space-x-2 text-gray-600 hover:text-gray-900">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                                <span>Photo</span>
                            </span>
                        </label>
                        <label class="cursor-pointer">
                            <input type="file" name="video" class="hidden" accept="video/*" onchange="previewVideo(this)">
                            <span class="flex items-center space-x-2 text-gray-600 hover:text-gray-900">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z">
                                    </path>
                                </svg>
                                <span>Vidéo</span>
                            </span>
                        </label>
                    </div>

                    <button type="submit"
                        class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Publier
                    </button>
                </div>
            </div>


        </form>
    </div>


<!-- Mobile Notifications Button -->
<div class="lg:hidden mb-4 px-4 sm:px-6">
    <button onclick="toggleMobileNotifications()"
            class="w-full bg-white dark:bg-gray-800 shadow-lg rounded-xl p-4 flex items-center justify-between">
        <div class="flex items-center space-x-2">
            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5z"></path>
            </svg>
            <span class="font-medium text-gray-900 dark:text-gray-100">Notifications</span>
            @if(isset($notifications) && $notifications->where('is_read', false)->count() > 0)
                <span class="bg-red-500 text-white text-xs rounded-full px-2 py-1">
                    {{ $notifications->where('is_read', false)->count() }}
                </span>
            @endif
        </div>
        <svg class="w-5 h-5 text-gray-400 transform transition-transform" id="mobile-notifications-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
        </svg>
    </button>

    <!-- Mobile Notifications Panel -->
    <div id="mobile-notifications" class="hidden mt-2 bg-white dark:bg-gray-800 shadow-lg rounded-xl p-4 max-h-96 overflow-y-auto">
        @if(isset($notifications) && $notifications->count() > 0)
            <div class="space-y-2">
                @foreach($notifications->take(5) as $notification)
                    <div class="p-2 rounded bg-gray-50 dark:bg-gray-700">
                        <p class="text-xs text-gray-600 dark:text-gray-300">{{ $notification->message }}</p>
                        <div class="mt-1">
                            @if($notification->type === 'follow')
                                <a href="{{ route('profile.show', $notification->fromUser) }}"
                                   class="text-xs text-indigo-600 hover:text-indigo-800 dark:text-indigo-400">
                                    Voir le profil
                                </a>
                            @elseif(in_array($notification->type, ['like', 'comment', 'new_post']) && isset($notification->data['post_id']))
                                <a href="{{ route('posts.show', $notification->data['post_id']) }}"
                                   class="text-xs text-indigo-600 hover:text-indigo-800 dark:text-indigo-400">
                                    Voir le post
                                </a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="mt-3 text-center">
                <a href="{{ route('notifications.index') }}"
                   class="text-sm text-indigo-600 hover:text-indigo-800 dark:text-indigo-400">
                    Voir toutes les notifications →
                </a>
            </div>
        @else
            <p class="text-sm text-gray-500 text-center">Aucune notification</p>
        @endif
    </div>
</div>

<!-- Main Content with Sidebar Layout -->
<div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex gap-8">
        <!-- Notifications Sidebar - Left Side -->
        <div class="hidden lg:block w-80 flex-shrink-0">
            <div class="bg-white dark:bg-gray-800 shadow-lg rounded-xl p-6 sticky top-20 notifications-sidebar" style="height: calc(100vh - 6rem);">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Notifications</h3>

                <!-- Notifications Section -->
                <div class="mt-4">
                    <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Activité récente</h4>
                    @if(isset($notifications) && $notifications->count() > 0)
                        <div class="space-y-2 max-h-64 overflow-y-auto">
                            @foreach($notifications->take(5) as $notification)
                                <div class="p-2 rounded bg-gray-50 dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 transition">
                                    <p class="text-xs text-gray-600 dark:text-gray-300">{{ $notification->message }}</p>
                                    <div class="mt-1">
                                        @if($notification->type === 'follow')
                                            <a href="{{ route('profile.show', $notification->fromUser) }}"
                                               class="text-xs text-indigo-600 hover:text-indigo-800 dark:text-indigo-400">
                                                Voir le profil
                                            </a>
                                        @elseif(in_array($notification->type, ['like', 'comment', 'new_post']) && isset($notification->data['post_id']))
                                            <a href="{{ route('posts.show', $notification->data['post_id']) }}"
                                               class="text-xs text-indigo-600 hover:text-indigo-800 dark:text-indigo-400">
                                                Voir le post
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-3">
                            <a href="{{ route('notifications.index') }}"
                               class="text-xs text-indigo-600 hover:text-indigo-800 dark:text-indigo-400">
                                Voir toutes les notifications →
                            </a>
                        </div>
                    @else
                        <p class="text-sm text-gray-500">Aucune notification</p>
                    @endif
                </div>

                <!-- Suggestions Section -->
                <div class="mt-6">
                    <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Suggestions d'amis</h4>
                    @if(isset($friendSuggestions) && $friendSuggestions->count() > 0)
                        <div class="space-y-2 max-h-48 overflow-y-auto">
                            @foreach($friendSuggestions->take(4) as $suggestion)
                                <div class="flex items-center justify-between p-2 rounded bg-gray-50 dark:bg-gray-700">
                                    <div class="flex items-center space-x-2 min-w-0">
                                        @if($suggestion->profile_image)
                                            <img class="h-6 w-6 rounded-full object-cover flex-shrink-0"
                                                 src="{{ Storage::url($suggestion->profile_image) }}"
                                                 alt="{{ $suggestion->name }}">
                                        @else
                                            <img class="h-6 w-6 rounded-full flex-shrink-0"
                                                 src="https://ui-avatars.com/api/?name={{ urlencode($suggestion->name) }}&size=24"
                                                 alt="{{ $suggestion->name }}">
                                        @endif
                                        <span class="text-sm text-gray-600 dark:text-gray-300 truncate">{{ $suggestion->name }}</span>
                                    </div>
                                    <form action="{{ route('users.follow', $suggestion) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="px-2 py-1 text-xs bg-indigo-600 text-white rounded hover:bg-indigo-700 transition">
                                            Suivre
                                        </button>
                                    </form>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm text-gray-500">Aucune suggestion</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Posts Feed - Center/Right Side -->
        <div class="flex-1 max-w-3xl space-y-8">
    <div class="space-y-6">
        @foreach($posts as $post)
            <div
                class="p-6 rounded-2xl shadow-sm border
                    {{ $post->user_id === auth()->id() ? 'bg-white border-gray-200 dark:bg-gray-800 dark:border-gray-700' : 'bg-gray-50 border-gray-300 dark:bg-gray-900 dark:border-gray-700' }}">

                <!-- Header user -->
                <div class="flex items-center space-x-4 mb-4">
                    <a href="{{ route('profile.show', $post->user) }}" class="flex-shrink-0">
                        @if($post->user->profile_image)
                            <img class="h-10 w-10 rounded-full object-cover"
                                src="{{ Storage::url($post->user->profile_image) }}"
                                alt="{{ $post->user->name }}">
                        @else
                            <img class="h-10 w-10 rounded-full"
                                src="https://ui-avatars.com/api/?name={{ urlencode($post->user->name) }}"
                                alt="{{ $post->user->name }}">
                        @endif
                    </a>
                    <div>
                        <a href="{{ route('profile.show', $post->user) }}"
                            class="font-semibold hover:underline text-gray-900 dark:text-gray-100">{{ $post->user->name }}</a>
                        <p class="text-gray-500 text-sm dark:text-gray-400">{{ $post->created_at->diffForHumans() }}</p>
                    </div>
                </div>

                <!-- Contenu texte -->
                @if($post->content)
                    <p class="mb-4 text-gray-900 dark:text-gray-100">{{ $post->content }}</p>
                @endif

                <!-- Image -->
                @if($post->image_path)
                    <div class="post-media-container mb-4">
                        <img src="{{ Storage::url($post->image_path) }}"
                             alt="Post image"
                             class="post-image">
                    </div>
                @endif

                <!-- Vidéo -->
                @if($post->video_path)
                    <div class="post-media-container mb-4">
                        <video controls class="post-video">
                            <source src="{{ Storage::url($post->video_path) }}" type="video/mp4">
                            Votre navigateur ne supporte pas la lecture de vidéos.
                        </video>
                    </div>
                @endif

                <!-- Barre actions like, commentaire, suppression -->
                <div class="flex items-center justify-between border-t border-gray-200 pt-4 dark:border-gray-700">
                    <div class="flex space-x-4">
                        <!-- Like Button with AJAX -->
                        <button onclick="toggleLike({{ $post->id }})"
                                id="like-btn-{{ $post->id }}"
                                class="flex items-center space-x-2 transition-colors duration-200 {{ $post->isLikedBy(auth()->user()) ? 'text-indigo-600' : 'text-gray-600 hover:text-indigo-600 dark:text-gray-400 dark:hover:text-indigo-400' }}">
                            <svg class="w-5 h-5" id="like-icon-{{ $post->id }}"
                                 fill="{{ $post->isLikedBy(auth()->user()) ? 'currentColor' : 'none' }}"
                                 stroke="currentColor" viewBox="0 0 24 24">
                                @if($post->isLikedBy(auth()->user()))
                                    <path d="M2 10.5a1.5 1.5 0 113 0v6a1.5 1.5 0 01-3 0v-6zM6 10.333v5.43a2 2 0 001.106 1.79l.05.025A4 4 0 008.943 18h5.416a2 2 0 001.962-1.608l1.2-6A2 2 0 0015.56 8H12V4a2 2 0 00-2-2 1 1 0 00-1 1v.667a4 4 0 01-.8 2.4L6.8 7.933a4 4 0 00-.8 2.4z"></path>
                                @else
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"></path>
                                @endif
                            </svg>
                            <span id="like-count-{{ $post->id }}"
                                  onclick="event.stopPropagation(); showLikesModal({{ $post->id }})"
                                  class="cursor-pointer hover:underline text-blue-600 font-semibold">{{ $post->likes->count() }}</span>
                        </button>

                        <button type="button"
                            class="flex items-center space-x-2 text-gray-600 hover:text-indigo-600 dark:text-gray-400 dark:hover:text-indigo-400"
                            onclick="document.getElementById('comments-{{ $post->id }}').classList.toggle('hidden')">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                                </path>
                            </svg>
                            <span id="comment-count-{{ $post->id }}">{{ $post->comments->count() }}</span>
                        </button>

                        <!-- Share Button -->
                        <button type="button"
                            class="flex items-center space-x-2 text-gray-600 hover:text-green-600 dark:text-gray-400 dark:hover:text-green-400 transition-colors duration-200"
                            onclick="sharePost({{ $post->id }})"
                            title="Partager cette publication">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z">
                                </path>
                            </svg>
                            <span class="text-sm">Partager</span>
                        </button>
                    </div>

                    @if($post->user_id === auth()->id())
                        <form action="{{ route('posts.destroy', $post) }}" method="POST" class="flex items-center">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </form>
                    @endif
                </div>

                <!-- Comments Section -->
                <div id="comments-{{ $post->id }}" class="hidden mt-6 bg-gray-50 dark:bg-gray-800 rounded-xl p-4 border border-gray-200 dark:border-gray-700">
                    <!-- Comments Header -->
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="font-semibold text-gray-900 dark:text-gray-100 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                                </path>
                            </svg>
                            Commentaires
                        </h4>
                        <span class="text-sm text-gray-500 dark:text-gray-400" id="comment-count-display-{{ $post->id }}">
                            {{ $post->comments->count() }} {{ $post->comments->count() > 1 ? 'commentaires' : 'commentaire' }}
                        </span>
                    </div>

                    <!-- Add Comment Form -->
                    <form onsubmit="addComment(event, {{ $post->id }})" class="mb-6">
                        <div class="flex space-x-3">
                            <!-- User Avatar -->
                            <div class="flex-shrink-0">
                                @if(auth()->user()->profile_image)
                                    <img class="h-10 w-10 rounded-full object-cover border-2 border-white shadow-sm"
                                        src="{{ Storage::url(auth()->user()->profile_image) }}"
                                        alt="{{ auth()->user()->name }}">
                                @else
                                    <img class="h-10 w-10 rounded-full border-2 border-white shadow-sm"
                                        src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&size=40"
                                        alt="{{ auth()->user()->name }}">
                                @endif
                            </div>

                            <!-- Comment Input -->
                            <div class="flex-1">
                                <div class="relative">
                                    <textarea name="content" id="comment-input-{{ $post->id }}"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-xl resize-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100 dark:focus:border-indigo-400 transition-all duration-200"
                                        placeholder="Écrivez votre commentaire..."
                                        rows="2"
                                        maxlength="500"
                                        required></textarea>

                                    <!-- Character Counter -->
                                    <div class="absolute bottom-2 right-2 text-xs text-gray-400" id="char-counter-{{ $post->id }}">
                                        0/500
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="flex items-center justify-between mt-3">
                                    <div class="flex items-center space-x-2 text-sm text-gray-500 dark:text-gray-400">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <span>Soyez respectueux dans vos commentaires</span>
                                    </div>

                                    <div class="flex items-center space-x-2">
                                        <button type="button" onclick="clearComment({{ $post->id }})"
                                            class="px-3 py-1.5 text-sm text-gray-600 hover:text-gray-800 dark:text-gray-400 dark:hover:text-gray-200 transition-colors">
                                            Annuler
                                        </button>
                                        <button type="submit" id="comment-btn-{{ $post->id }}"
                                            class="px-4 py-1.5 bg-indigo-600 text-white text-sm rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-200">
                                            <span class="flex items-center">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                                </svg>
                                                Publier
                                            </span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

                    <!-- Comments List -->
                    <div id="comments-list-{{ $post->id }}" class="space-y-4">
                        @if($post->comments->count() > 0)
                            @foreach($post->comments as $comment)
                                <div class="comment-item bg-white dark:bg-gray-700 rounded-lg p-4 shadow-sm border border-gray-100 dark:border-gray-600 transition-all duration-200 hover:shadow-md">
                                    <div class="flex space-x-3">
                                        <!-- Comment User Avatar -->
                                        <div class="flex-shrink-0">
                                            <a href="{{ route('profile.show', $comment->user) }}" class="block">
                                                @if($comment->user->profile_image)
                                                    <img class="h-10 w-10 rounded-full object-cover border-2 border-gray-200 dark:border-gray-600 hover:border-indigo-300 transition-colors"
                                                        src="{{ Storage::url($comment->user->profile_image) }}"
                                                        alt="{{ $comment->user->name }}">
                                                @else
                                                    <img class="h-10 w-10 rounded-full border-2 border-gray-200 dark:border-gray-600 hover:border-indigo-300 transition-colors"
                                                        src="https://ui-avatars.com/api/?name={{ urlencode($comment->user->name) }}&size=40"
                                                        alt="{{ $comment->user->name }}">
                                                @endif
                                            </a>
                                        </div>

                                        <!-- Comment Content -->
                                        <div class="flex-1 min-w-0">
                                            <!-- Comment Header -->
                                            <div class="flex items-center justify-between mb-2">
                                                <div class="flex items-center space-x-2">
                                                    <a href="{{ route('profile.show', $comment->user) }}"
                                                        class="font-semibold text-gray-900 dark:text-gray-100 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                                                        {{ $comment->user->name }}
                                                    </a>
                                                    @if($comment->user_id === $post->user_id)
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200">
                                                            Auteur
                                                        </span>
                                                    @endif
                                                </div>

                                                <div class="flex items-center space-x-2">
                                                    <span class="text-gray-500 text-sm dark:text-gray-400" title="{{ $comment->created_at->format('d/m/Y à H:i') }}">
                                                        {{ $comment->created_at->diffForHumans() }}
                                                    </span>

                                                    @if($comment->user_id === auth()->id())
                                                        <div class="relative">
                                                            <button onclick="toggleCommentMenu({{ $comment->id }})"
                                                                class="p-1 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 rounded-full hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                                    <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"></path>
                                                                </svg>
                                                            </button>

                                                            <!-- Dropdown Menu -->
                                                            <div id="comment-menu-{{ $comment->id }}" class="hidden absolute right-0 mt-1 w-32 bg-white dark:bg-gray-800 rounded-md shadow-lg border border-gray-200 dark:border-gray-700 z-10">
                                                                <button type="button"
                                                                    onclick="deleteComment({{ $comment->id }})"
                                                                    class="w-full text-left px-3 py-2 text-sm text-red-600 hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-900 rounded-md transition-colors">
                                                                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                                    </svg>
                                                                    Supprimer
                                                                </button>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>

                                            <!-- Comment Text -->
                                            <div class="text-gray-900 dark:text-gray-100 leading-relaxed">
                                                <p class="whitespace-pre-wrap break-words">{{ $comment->content }}</p>
                                            </div>

                                            <!-- Comment Actions -->
                                            <div class="flex items-center justify-between mt-3">
                                                <div class="flex items-center space-x-4">
                                                    <!-- Like Button -->
                                                    <button type="button"
                                                        onclick="toggleCommentLike({{ $comment->id }})"
                                                        id="comment-like-btn-{{ $comment->id }}"
                                                        class="flex items-center space-x-1 text-sm {{ $comment->isLikedBy(auth()->user()) ? 'text-indigo-600' : 'text-gray-500 hover:text-indigo-600' }} transition-colors duration-200">
                                                        <svg id="comment-like-icon-{{ $comment->id }}" class="w-4 h-4"
                                                             fill="{{ $comment->isLikedBy(auth()->user()) ? 'currentColor' : 'none' }}"
                                                             stroke="currentColor" viewBox="0 0 24 24">
                                                            @if($comment->isLikedBy(auth()->user()))
                                                                <path d="M14 9V5a3 3 0 0 0-3-3l-4 9v11h11.28a2 2 0 0 0 2-1.7l1.38-9a2 2 0 0 0-2-2.3zM7 22H4a2 2 0 0 1-2-2v-7a2 2 0 0 1 2-2h3"></path>
                                                            @else
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"></path>
                                                            @endif
                                                        </svg>
                                                        <span id="comment-like-count-{{ $comment->id }}">{{ $comment->likes()->count() }}</span>
                                                    </button>

                                                    <!-- Reply Button -->
                                                    <button type="button"
                                                        onclick="toggleReplyForm({{ $comment->id }})"
                                                        class="flex items-center space-x-1 text-sm text-gray-500 hover:text-indigo-600 transition-colors duration-200">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path>
                                                        </svg>
                                                        <span>Répondre</span>
                                                    </button>

                                                    @if($comment->replies()->count() > 0)
                                                        <!-- Show Replies Button -->
                                                        <button type="button"
                                                            onclick="toggleReplies({{ $comment->id }})"
                                                            id="replies-toggle-{{ $comment->id }}"
                                                            class="flex items-center space-x-1 text-sm text-indigo-600 hover:text-indigo-800 transition-colors duration-200">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                            </svg>
                                                            <span>{{ $comment->replies()->count() }} {{ $comment->replies()->count() > 1 ? 'réponses' : 'réponse' }}</span>
                                                        </button>
                                                    @endif
                                                </div>
                                            </div>

                                            <!-- Reply Form (Hidden by default) -->
                                            <div id="reply-form-{{ $comment->id }}" class="hidden mt-4">
                                                <form onsubmit="addReply(event, {{ $post->id }}, {{ $comment->id }})" class="flex space-x-3">
                                                    <div class="flex-shrink-0">
                                                        @if(auth()->user()->profile_image)
                                                            <img class="h-8 w-8 rounded-full object-cover border-2 border-white shadow-sm"
                                                                src="{{ Storage::url(auth()->user()->profile_image) }}"
                                                                alt="{{ auth()->user()->name }}">
                                                        @else
                                                            <img class="h-8 w-8 rounded-full border-2 border-white shadow-sm"
                                                                src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&size=32"
                                                                alt="{{ auth()->user()->name }}">
                                                        @endif
                                                    </div>
                                                    <div class="flex-1">
                                                        <textarea name="content" id="reply-input-{{ $comment->id }}"
                                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg resize-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-200 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100"
                                                            placeholder="Répondre à {{ $comment->user->name }}..."
                                                            rows="2"
                                                            maxlength="500"
                                                            required></textarea>
                                                        <div class="flex items-center justify-between mt-2">
                                                            <span class="text-xs text-gray-400">Réponse à {{ $comment->user->name }}</span>
                                                            <div class="flex space-x-2">
                                                                <button type="button" onclick="toggleReplyForm({{ $comment->id }})"
                                                                    class="px-3 py-1 text-sm text-gray-600 hover:text-gray-800 transition-colors">
                                                                    Annuler
                                                                </button>
                                                                <button type="submit" id="reply-btn-{{ $comment->id }}"
                                                                    class="px-3 py-1 bg-indigo-600 text-white text-sm rounded hover:bg-indigo-700 transition-colors">
                                                                    Répondre
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>

                                            <!-- Replies List -->
                                            @if($comment->replies()->count() > 0)
                                                <div id="replies-{{ $comment->id }}" class="mt-3 space-y-3">
                                                    @foreach($comment->replies as $reply)
                                                        <div class="reply-item bg-gray-50 dark:bg-gray-700 rounded-lg p-3 ml-8 border-l-3 border-indigo-200 dark:border-indigo-600">
                                                            <!-- Indication de réponse -->
                                                            <div class="flex items-center space-x-1 mb-2">
                                                                <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path>
                                                                </svg>
                                                                <span class="text-xs text-gray-500 dark:text-gray-400">
                                                                    En réponse à
                                                                    <a href="{{ route('profile.show', $comment->user) }}" class="font-medium text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300">
                                                                        {{ $comment->user->name }}
                                                                    </a>
                                                                </span>
                                                            </div>

                                                            <div class="flex space-x-3">
                                                                <div class="flex-shrink-0">
                                                                    <a href="{{ route('profile.show', $reply->user) }}" class="block">
                                                                        @if($reply->user->profile_image)
                                                                            <img class="h-8 w-8 rounded-full object-cover border-2 border-gray-200 dark:border-gray-500"
                                                                                src="{{ Storage::url($reply->user->profile_image) }}"
                                                                                alt="{{ $reply->user->name }}">
                                                                        @else
                                                                            <img class="h-8 w-8 rounded-full border-2 border-gray-200 dark:border-gray-500"
                                                                                src="https://ui-avatars.com/api/?name={{ urlencode($reply->user->name) }}&size=32"
                                                                                alt="{{ $reply->user->name }}">
                                                                        @endif
                                                                    </a>
                                                                </div>
                                                                <div class="flex-1 min-w-0">
                                                                    <div class="flex items-center justify-between mb-1">
                                                                        <div class="flex items-center space-x-2">
                                                                            <a href="{{ route('profile.show', $reply->user) }}"
                                                                                class="font-medium text-gray-900 dark:text-gray-100 hover:text-indigo-600 dark:hover:text-indigo-400 text-sm">
                                                                                {{ $reply->user->name }}
                                                                            </a>
                                                                            @if($reply->user_id === $post->user_id)
                                                                                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200">
                                                                                    Auteur
                                                                                </span>
                                                                            @endif
                                                                        </div>
                                                                        <div class="flex items-center space-x-2">
                                                                            <span class="text-gray-500 text-xs dark:text-gray-400">
                                                                                {{ $reply->created_at->diffForHumans() }}
                                                                            </span>

                                                                            @if($reply->user_id === auth()->id())
                                                                                <!-- Bouton supprimer pour l'auteur de la réponse -->
                                                                                <button type="button"
                                                                                    onclick="deleteComment({{ $reply->id }})"
                                                                                    class="text-gray-400 hover:text-red-600 transition-colors duration-200"
                                                                                    title="Supprimer la réponse">
                                                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                                                    </svg>
                                                                                </button>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                    <p class="text-gray-900 dark:text-gray-100 text-sm leading-relaxed">{{ $reply->content }}</p>

                                                                    <!-- Reply Actions -->
                                                                    <div class="flex items-center space-x-3 mt-2">
                                                                        <button type="button"
                                                                            onclick="toggleCommentLike({{ $reply->id }})"
                                                                            id="comment-like-btn-{{ $reply->id }}"
                                                                            class="flex items-center space-x-1 text-xs {{ $reply->isLikedBy(auth()->user()) ? 'text-indigo-600' : 'text-gray-500 hover:text-indigo-600' }} transition-colors duration-200">
                                                                            <svg id="comment-like-icon-{{ $reply->id }}" class="w-3 h-3"
                                                                                 fill="{{ $reply->isLikedBy(auth()->user()) ? 'currentColor' : 'none' }}"
                                                                                 stroke="currentColor" viewBox="0 0 24 24">
                                                                                @if($reply->isLikedBy(auth()->user()))
                                                                                    <path d="M14 9V5a3 3 0 0 0-3-3l-4 9v11h11.28a2 2 0 0 0 2-1.7l1.38-9a2 2 0 0 0-2-2.3zM7 22H4a2 2 0 0 1-2-2v-7a2 2 0 0 1 2-2h3"></path>
                                                                                @else
                                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"></path>
                                                                                @endif
                                                                            </svg>
                                                                            <span id="comment-like-count-{{ $reply->id }}">{{ $reply->likes()->count() }}</span>
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="text-center py-8">
                                <svg class="w-12 h-12 mx-auto text-gray-400 dark:text-gray-500 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                                    </path>
                                </svg>
                                <p class="text-gray-500 dark:text-gray-400 text-sm">Aucun commentaire pour le moment</p>
                                <p class="text-gray-400 dark:text-gray-500 text-xs mt-1">Soyez le premier à commenter !</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div> <!-- fin du post container -->
        @endforeach

        <!-- Pagination -->
        <div class="mt-6">
            {{ $posts->links() }}
        </div>
    </div>
        </div>


    </div>
</div>

<!-- Modal pour afficher les likes -->
<div id="likes-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-md w-full max-h-[80vh] overflow-hidden">
        <!-- Header du modal -->
        <div class="flex items-center justify-between p-6 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                Personnes qui ont aimé
            </h3>
            <button onclick="closeLikesModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <!-- Contenu du modal -->
        <div class="p-6">
            <!-- Loading state -->
            <div id="likes-loading" class="flex items-center justify-center py-8">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600"></div>
                <span class="ml-3 text-gray-600 dark:text-gray-400">Chargement...</span>
            </div>

            <!-- Liste des likes -->
            <div id="likes-list" class="hidden space-y-3 max-h-96 overflow-y-auto">
                <!-- Les likes seront ajoutés ici dynamiquement -->
            </div>

            <!-- État vide -->
            <div id="likes-empty" class="hidden text-center py-8">
                <svg class="w-16 h-16 mx-auto text-gray-400 dark:text-gray-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"></path>
                </svg>
                <p class="text-gray-500 dark:text-gray-400">Aucun like pour le moment</p>
            </div>
        </div>
    </div>
</div>

    <script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const imagePreview = document.getElementById('image-preview');
            const videoPreview = document.getElementById('video-preview');
            const mediaPreview = document.getElementById('media-preview');

            imagePreview.querySelector('img').src = e.target.result;
            imagePreview.classList.remove('hidden');
            videoPreview.classList.add('hidden');
            mediaPreview.classList.remove('hidden');

            // Reset video input to prevent submitting old file
            const videoInput = document.querySelector('input[name="video"]');
            if (videoInput) {
                videoInput.value = "";
            }
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function previewVideo(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const videoPreview = document.getElementById('video-preview');
            const imagePreview = document.getElementById('image-preview');
            const mediaPreview = document.getElementById('media-preview');

            videoPreview.querySelector('source').src = e.target.result;
            videoPreview.querySelector('video').load();
            videoPreview.classList.remove('hidden');
            imagePreview.classList.add('hidden');
            mediaPreview.classList.remove('hidden');

            // Reset image input to prevent submitting old file
            const imageInput = document.querySelector('input[name="image"]');
            if (imageInput) {
                imageInput.value = "";
            }
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function removeMedia(type) {
    if (type === 'image') {
        const imageInput = document.querySelector('input[name="image"]');
        if (imageInput) {
            imageInput.value = "";
        }
        document.getElementById('image-preview').classList.add('hidden');
    } else if (type === 'video') {
        const videoInput = document.querySelector('input[name="video"]');
        if (videoInput) {
            videoInput.value = "";
        }
        document.getElementById('video-preview').classList.add('hidden');
    }

    // If both previews hidden, hide the container
    const imageHidden = document.getElementById('image-preview').classList.contains('hidden');
    const videoHidden = document.getElementById('video-preview').classList.contains('hidden');
    if (imageHidden && videoHidden) {
        document.getElementById('media-preview').classList.add('hidden');
    }
}

function toggleMobileNotifications() {
    const panel = document.getElementById('mobile-notifications');
    const arrow = document.getElementById('mobile-notifications-arrow');

    if (panel.classList.contains('hidden')) {
        panel.classList.remove('hidden');
        arrow.style.transform = 'rotate(180deg)';
    } else {
        panel.classList.add('hidden');
        arrow.style.transform = 'rotate(0deg)';
    }
}

// Fonction pour gérer les likes avec AJAX
async function toggleLike(postId) {
    const likeBtn = document.getElementById(`like-btn-${postId}`);
    const likeIcon = document.getElementById(`like-icon-${postId}`);
    const likeCount = document.getElementById(`like-count-${postId}`);

    // Désactiver le bouton temporairement
    likeBtn.disabled = true;
    likeBtn.style.opacity = '0.6';

    try {
        // Déterminer si c'est un like ou unlike basé sur la couleur actuelle
        const isCurrentlyLiked = likeBtn.classList.contains('text-indigo-600');
        const url = `/posts/${postId}/like`;
        const method = isCurrentlyLiked ? 'DELETE' : 'POST';

        const response = await axios({
            method: method,
            url: url,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            }
        });

        if (response.data.success) {
            // Mettre à jour le compteur
            likeCount.textContent = response.data.likes_count;

            // Mettre à jour l'apparence du bouton (garder seulement l'icône de pouce)
            if (response.data.liked) {
                // Post aimé - pouce plein bleu
                likeBtn.className = 'flex items-center space-x-2 transition-colors duration-200 text-indigo-600';
                likeIcon.setAttribute('fill', 'currentColor');
                likeIcon.innerHTML = '<path d="M2 10.5a1.5 1.5 0 113 0v6a1.5 1.5 0 01-3 0v-6zM6 10.333v5.43a2 2 0 001.106 1.79l.05.025A4 4 0 008.943 18h5.416a2 2 0 001.962-1.608l1.2-6A2 2 0 0015.56 8H12V4a2 2 0 00-2-2 1 1 0 00-1 1v.667a4 4 0 01-.8 2.4L6.8 7.933a4 4 0 00-.8 2.4z"></path>';
            } else {
                // Post pas aimé - pouce outline gris
                likeBtn.className = 'flex items-center space-x-2 transition-colors duration-200 text-gray-600 hover:text-indigo-600 dark:text-gray-400 dark:hover:text-indigo-400';
                likeIcon.setAttribute('fill', 'none');
                likeIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"></path>';
            }

            // Animation de feedback
            likeBtn.style.transform = 'scale(1.1)';
            setTimeout(() => {
                likeBtn.style.transform = 'scale(1)';
            }, 150);
        }
    } catch (error) {
        console.error('Erreur lors du like:', error);
        // Afficher un message d'erreur
        showNotification('Erreur lors du like', 'error');
    } finally {
        // Réactiver le bouton
        likeBtn.disabled = false;
        likeBtn.style.opacity = '1';
    }
}

// Fonction pour ajouter un commentaire avec AJAX
async function addComment(event, postId) {
    event.preventDefault();

    const commentInput = document.getElementById(`comment-input-${postId}`);
    const commentBtn = document.getElementById(`comment-btn-${postId}`);
    const commentsList = document.getElementById(`comments-list-${postId}`);
    const commentCount = document.getElementById(`comment-count-${postId}`);

    const content = commentInput.value.trim();
    if (!content) return;

    // Désactiver le formulaire
    commentBtn.disabled = true;
    commentBtn.textContent = 'Envoi...';

    try {
        const response = await axios.post(`/posts/${postId}/comments`, {
            content: content
        }, {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        if (response.data.success) {
            // Mettre à jour le compteur dans le bouton
            commentCount.textContent = response.data.comments_count;

            // Mettre à jour le compteur d'affichage dans l'en-tête des commentaires
            const commentCountDisplay = document.getElementById(`comment-count-display-${postId}`);
            if (commentCountDisplay) {
                const count = response.data.comments_count;
                commentCountDisplay.textContent = `${count} ${count > 1 ? 'commentaires' : 'commentaire'}`;
            }

            // Ajouter le nouveau commentaire à la liste
            const newComment = createCommentElement(response.data.comment);

            // Si c'est le premier commentaire, remplacer le message "aucun commentaire"
            const emptyState = commentsList.querySelector('.text-center.py-8');
            if (emptyState) {
                emptyState.remove();
            }

            commentsList.appendChild(newComment);

            // Vider le champ de saisie et réinitialiser le compteur
            commentInput.value = '';
            const charCounter = document.getElementById(`char-counter-${postId}`);
            if (charCounter) {
                charCounter.textContent = '0/500';
                charCounter.className = 'absolute bottom-2 right-2 text-xs text-gray-400';
            }

            // Réinitialiser la hauteur du textarea
            commentInput.style.height = 'auto';

            // Animation d'apparition
            newComment.style.opacity = '0';
            newComment.style.transform = 'translateY(20px)';
            setTimeout(() => {
                newComment.style.transition = 'all 0.3s ease-out';
                newComment.style.opacity = '1';
                newComment.style.transform = 'translateY(0)';
            }, 10);

            showNotification('Commentaire ajouté !', 'success');
        }
    } catch (error) {
        console.error('Erreur lors de l\'ajout du commentaire:', error);
        showNotification('Erreur lors de l\'ajout du commentaire', 'error');
    } finally {
        // Réactiver le formulaire
        commentBtn.disabled = false;
        commentBtn.textContent = 'Commenter';
    }
}

// Fonction pour créer un élément commentaire avec le nouveau design
function createCommentElement(comment) {
    const div = document.createElement('div');
    div.className = 'comment-item bg-white dark:bg-gray-700 rounded-lg p-4 shadow-sm border border-gray-100 dark:border-gray-600 transition-all duration-200 hover:shadow-md';

    const profileImage = comment.user.profile_image
        ? `/storage/${comment.user.profile_image}`
        : `https://ui-avatars.com/api/?name=${encodeURIComponent(comment.user.name)}&size=40`;

    div.innerHTML = `
        <div class="flex space-x-3">
            <!-- Comment User Avatar -->
            <div class="flex-shrink-0">
                <a href="/profile/${comment.user.id}" class="block">
                    <img class="h-10 w-10 rounded-full object-cover border-2 border-gray-200 dark:border-gray-600 hover:border-indigo-300 transition-colors"
                         src="${profileImage}"
                         alt="${comment.user.name}">
                </a>
            </div>

            <!-- Comment Content -->
            <div class="flex-1 min-w-0">
                <!-- Comment Header -->
                <div class="flex items-center justify-between mb-2">
                    <div class="flex items-center space-x-2">
                        <a href="/profile/${comment.user.id}"
                           class="font-semibold text-gray-900 dark:text-gray-100 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                            ${comment.user.name}
                        </a>
                    </div>

                    <div class="flex items-center space-x-2">
                        <span class="text-gray-500 text-sm dark:text-gray-400">
                            À l'instant
                        </span>

                        <div class="relative">
                            <button onclick="toggleCommentMenu(${comment.id})"
                                class="p-1 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 rounded-full hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"></path>
                                </svg>
                            </button>

                            <!-- Dropdown Menu -->
                            <div id="comment-menu-${comment.id}" class="hidden absolute right-0 mt-1 w-32 bg-white dark:bg-gray-800 rounded-md shadow-lg border border-gray-200 dark:border-gray-700 z-10">
                                <button type="button"
                                    onclick="deleteComment(${comment.id})"
                                    class="w-full text-left px-3 py-2 text-sm text-red-600 hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-900 rounded-md transition-colors">
                                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                    Supprimer
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Comment Text -->
                <div class="text-gray-900 dark:text-gray-100 leading-relaxed">
                    <p class="whitespace-pre-wrap break-words">${comment.content}</p>
                </div>
            </div>
        </div>
    `;

    return div;
}

// Fonction pour vider le commentaire
function clearComment(postId) {
    const commentInput = document.getElementById(`comment-input-${postId}`);
    const charCounter = document.getElementById(`char-counter-${postId}`);

    commentInput.value = '';
    charCounter.textContent = '0/500';
    commentInput.focus();
}

// Fonction pour gérer le menu dropdown des commentaires
function toggleCommentMenu(commentId) {
    const menu = document.getElementById(`comment-menu-${commentId}`);
    const allMenus = document.querySelectorAll('[id^="comment-menu-"]');

    // Fermer tous les autres menus
    allMenus.forEach(m => {
        if (m.id !== `comment-menu-${commentId}`) {
            m.classList.add('hidden');
        }
    });

    // Toggle le menu actuel
    menu.classList.toggle('hidden');
}

// Fermer les menus dropdown en cliquant ailleurs
document.addEventListener('click', function(e) {
    if (!e.target.closest('[onclick^="toggleCommentMenu"]') && !e.target.closest('[id^="comment-menu-"]')) {
        const allMenus = document.querySelectorAll('[id^="comment-menu-"]');
        allMenus.forEach(menu => menu.classList.add('hidden'));
    }
});

// Fonction pour mettre à jour le compteur de caractères
function updateCharCounter(postId) {
    const commentInput = document.getElementById(`comment-input-${postId}`);
    const charCounter = document.getElementById(`char-counter-${postId}`);

    if (commentInput && charCounter) {
        const currentLength = commentInput.value.length;
        const maxLength = 500;

        charCounter.textContent = `${currentLength}/${maxLength}`;

        // Changer la couleur selon la proximité de la limite
        if (currentLength > maxLength * 0.9) {
            charCounter.className = 'absolute bottom-2 right-2 text-xs text-red-500';
        } else if (currentLength > maxLength * 0.7) {
            charCounter.className = 'absolute bottom-2 right-2 text-xs text-yellow-500';
        } else {
            charCounter.className = 'absolute bottom-2 right-2 text-xs text-gray-400';
        }
    }
}

// Ajouter les event listeners pour les compteurs de caractères
document.addEventListener('DOMContentLoaded', function() {
    // Ajouter les event listeners pour tous les textareas de commentaires
    const commentInputs = document.querySelectorAll('[id^="comment-input-"]');
    commentInputs.forEach(input => {
        const postId = input.id.replace('comment-input-', '');

        input.addEventListener('input', function() {
            updateCharCounter(postId);
        });

        // Auto-resize du textarea
        input.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = Math.min(this.scrollHeight, 120) + 'px';
        });
    });
});

// Fonction pour afficher des notifications toast
function showNotification(message, type = 'success') {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 px-6 py-3 rounded-lg text-white z-50 transition-all duration-300 ${
        type === 'success' ? 'bg-green-500' : 'bg-red-500'
    }`;
    notification.textContent = message;

    document.body.appendChild(notification);

    // Animation d'apparition
    setTimeout(() => {
        notification.style.transform = 'translateX(0)';
        notification.style.opacity = '1';
    }, 10);

    // Suppression automatique
    setTimeout(() => {
        notification.style.transform = 'translateX(100%)';
        notification.style.opacity = '0';
        setTimeout(() => {
            document.body.removeChild(notification);
        }, 300);
    }, 3000);
}


// Fonctions pour gérer le modal des likes
async function showLikesModal(postId) {
    console.log('Opening likes modal for post:', postId);

    const modal = document.getElementById('likes-modal');
    const loading = document.getElementById('likes-loading');
    const likesList = document.getElementById('likes-list');
    const likesEmpty = document.getElementById('likes-empty');

    if (!modal) {
        console.error('Modal not found!');
        return;
    }

    // Afficher le modal
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';

    // Réinitialiser l'état
    loading.classList.remove('hidden');
    likesList.classList.add('hidden');
    likesEmpty.classList.add('hidden');
    likesList.innerHTML = '';

    try {
        const response = await fetch(`/posts/${postId}/likes`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            }
        });

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const data = await response.json();
        console.log('Received likes data:', data);

        loading.classList.add('hidden');

        if (data.success) {
            const likes = data.likes;

            if (likes.length === 0) {
                likesEmpty.classList.remove('hidden');
            } else {
                likesList.classList.remove('hidden');

                // Créer les éléments pour chaque like
                likes.forEach(like => {
                    const likeElement = createLikeElement(like);
                    likesList.appendChild(likeElement);
                });
            }
        } else {
            // Afficher un message d'erreur
            loading.classList.add('hidden');
            likesList.innerHTML = '<div class="text-center py-8"><p class="text-red-500">Erreur lors du chargement</p></div>';
            likesList.classList.remove('hidden');
        }
    } catch (error) {
        console.error('Erreur lors du chargement des likes:', error);
        loading.classList.add('hidden');

        // Afficher un message d'erreur
        likesList.innerHTML = `
            <div class="text-center py-8">
                <p class="text-red-500">Erreur lors du chargement des likes</p>
                <button onclick="showLikesModal(${postId})" class="mt-2 text-indigo-600 hover:text-indigo-800">
                    Réessayer
                </button>
            </div>
        `;
        likesList.classList.remove('hidden');
    }
}

function closeLikesModal() {
    const modal = document.getElementById('likes-modal');
    modal.classList.add('hidden');
    document.body.style.overflow = 'auto'; // Rétablir le scroll de la page
}

// Créer un élément pour afficher un like
function createLikeElement(like) {
    const div = document.createElement('div');
    div.className = 'flex items-center justify-between p-3 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors';

    const profileImage = like.profile_image_url
        ? like.profile_image_url
        : `https://ui-avatars.com/api/?name=${encodeURIComponent(like.name)}&size=40`;

    div.innerHTML = `
        <div class="flex items-center space-x-3">
            <a href="/profile/${like.id}" class="flex-shrink-0">
                <img class="h-10 w-10 rounded-full object-cover border-2 border-gray-200 dark:border-gray-600"
                     src="${profileImage}"
                     alt="${like.name}">
            </a>
            <div>
                <a href="/profile/${like.id}" class="font-medium text-gray-900 dark:text-gray-100 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                    ${like.name}
                </a>
                <p class="text-sm text-gray-500 dark:text-gray-400">${like.liked_at}</p>
            </div>
        </div>
        <div class="flex items-center space-x-2">
            <svg class="w-5 h-5 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                <path d="M2 10.5a1.5 1.5 0 113 0v6a1.5 1.5 0 01-3 0v-6zM6 10.333v5.43a2 2 0 001.106 1.79l.05.025A4 4 0 008.943 18h5.416a2 2 0 001.962-1.608l1.2-6A2 2 0 0015.56 8H12V4a2 2 0 00-2-2 1 1 0 00-1 1v.667a4 4 0 01-.8 2.4L6.8 7.933a4 4 0 00-.8 2.4z"></path>
            </svg>
        </div>
    `;

    return div;
}

// Fermer le modal en cliquant à l'extérieur
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('likes-modal');
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === this) {
                closeLikesModal();
            }
        });
    }
});

// Fermer les modals avec la touche Escape
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        // Fermer le modal des likes
        const likesModal = document.getElementById('likes-modal');
        if (likesModal && !likesModal.classList.contains('hidden')) {
            closeLikesModal();
            return;
        }

        // Fermer le modal de partage
        const shareModal = document.getElementById('share-modal');
        if (shareModal) {
            closeShareModal();
            return;
        }
    }
});

// Fonction pour partager un post
function sharePost(postId) {
    const url = `${window.location.origin}/posts/${postId}`;
    const postElement = document.querySelector(`[data-post-id="${postId}"]`) ||
                       document.querySelector(`button[onclick="sharePost(${postId})"]`).closest('.bg-white');

    // Récupérer le contenu du post pour le partage
    let postContent = '';
    if (postElement) {
        const contentElement = postElement.querySelector('p');
        if (contentElement) {
            postContent = contentElement.textContent.trim();
            // Limiter à 100 caractères pour le partage
            if (postContent.length > 100) {
                postContent = postContent.substring(0, 100) + '...';
            }
        }
    }

    const shareData = {
        title: 'Regardez cette publication',
        text: postContent || 'Découvrez cette publication intéressante !',
        url: url
    };

    // Vérifier si l'API Web Share est supportée (mobile principalement)
    if (navigator.share) {
        navigator.share(shareData)
            .then(() => {
                showNotification('Publication partagée !', 'success');
            })
            .catch((error) => {
                console.log('Erreur lors du partage:', error);
                // Fallback vers la copie du lien
                fallbackShare(url);
            });
    } else {
        // Fallback pour les navigateurs desktop
        fallbackShare(url, shareData);
    }
}

// Fonction de fallback pour le partage
function fallbackShare(url, shareData = null) {
    // Essayer de copier dans le presse-papiers
    if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(url)
            .then(() => {
                showNotification('Lien copié dans le presse-papiers !', 'success');
            })
            .catch(() => {
                // Si la copie échoue, afficher un modal avec le lien
                showShareModal(url, shareData);
            });
    } else {
        // Si clipboard API n'est pas supportée, afficher un modal
        showShareModal(url, shareData);
    }
}

// Fonction pour afficher un modal de partage
function showShareModal(url, shareData = null) {
    // Créer le modal de partage
    const modal = document.createElement('div');
    modal.className = 'fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4';
    modal.innerHTML = `
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-md w-full p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                    Partager cette publication
                </h3>
                <button onclick="closeShareModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Lien de la publication :
                    </label>
                    <div class="flex">
                        <input type="text" value="${url}" readonly
                               class="flex-1 px-3 py-2 border border-gray-300 rounded-l-md bg-gray-50 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100 text-sm"
                               id="share-url-input">
                        <button onclick="copyShareUrl()"
                                class="px-4 py-2 bg-indigo-600 text-white rounded-r-md hover:bg-indigo-700 text-sm">
                            Copier
                        </button>
                    </div>
                </div>

                <div class="flex space-x-3">
                    <button onclick="shareToSocial('facebook', '${url}')"
                            class="flex-1 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 text-sm">
                        Facebook
                    </button>
                    <button onclick="shareToSocial('twitter', '${url}', '${shareData?.text || ''}')"
                            class="flex-1 bg-blue-400 text-white px-4 py-2 rounded-lg hover:bg-blue-500 text-sm">
                        Twitter
                    </button>
                    <button onclick="shareToSocial('whatsapp', '${url}', '${shareData?.text || ''}')"
                            class="flex-1 bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 text-sm">
                        WhatsApp
                    </button>
                </div>
            </div>
        </div>
    `;

    modal.id = 'share-modal';
    document.body.appendChild(modal);
    document.body.style.overflow = 'hidden';

    // Fermer le modal en cliquant à l'extérieur
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            closeShareModal();
        }
    });
}

// Fonction pour fermer le modal de partage
function closeShareModal() {
    const modal = document.getElementById('share-modal');
    if (modal) {
        document.body.removeChild(modal);
        document.body.style.overflow = 'auto';
    }
}

// Fonction pour copier l'URL depuis le modal
function copyShareUrl() {
    const input = document.getElementById('share-url-input');
    input.select();
    input.setSelectionRange(0, 99999); // Pour mobile

    try {
        document.execCommand('copy');
        showNotification('Lien copié !', 'success');
        closeShareModal();
    } catch (err) {
        console.error('Erreur lors de la copie:', err);
        showNotification('Erreur lors de la copie', 'error');
    }
}

// Fonction pour partager sur les réseaux sociaux
function shareToSocial(platform, url, text = '') {
    let shareUrl = '';

    switch (platform) {
        case 'facebook':
            shareUrl = `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(url)}`;
            break;
        case 'twitter':
            shareUrl = `https://twitter.com/intent/tweet?url=${encodeURIComponent(url)}&text=${encodeURIComponent(text)}`;
            break;
        case 'whatsapp':
            shareUrl = `https://wa.me/?text=${encodeURIComponent(text + ' ' + url)}`;
            break;
    }

    if (shareUrl) {
        window.open(shareUrl, '_blank', 'width=600,height=400');
        showNotification('Ouverture du partage...', 'success');
        closeShareModal();
    }
}

// Fonction pour liker/unliker un commentaire
async function toggleCommentLike(commentId) {
    const likeBtn = document.getElementById(`comment-like-btn-${commentId}`);
    const likeIcon = document.getElementById(`comment-like-icon-${commentId}`);
    const likeCount = document.getElementById(`comment-like-count-${commentId}`);

    // Désactiver le bouton temporairement
    likeBtn.disabled = true;
    likeBtn.style.opacity = '0.6';

    try {
        const response = await fetch(`/comments/${commentId}/like`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const data = await response.json();

        if (data.success) {
            // Mettre à jour le compteur
            likeCount.textContent = data.likes_count;

            // Mettre à jour l'apparence du bouton
            if (data.liked) {
                // Commentaire liké - pouce plein bleu
                likeBtn.className = likeBtn.className.replace('text-gray-500 hover:text-indigo-600', 'text-indigo-600');
                likeIcon.setAttribute('fill', 'currentColor');
                likeIcon.innerHTML = '<path d="M14 9V5a3 3 0 0 0-3-3l-4 9v11h11.28a2 2 0 0 0 2-1.7l1.38-9a2 2 0 0 0-2-2.3zM7 22H4a2 2 0 0 1-2-2v-7a2 2 0 0 1 2-2h3"></path>';
            } else {
                // Commentaire pas liké - pouce outline gris
                likeBtn.className = likeBtn.className.replace('text-indigo-600', 'text-gray-500 hover:text-indigo-600');
                likeIcon.setAttribute('fill', 'none');
                likeIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"></path>';
            }

            // Animation de feedback
            likeBtn.style.transform = 'scale(1.1)';
            setTimeout(() => {
                likeBtn.style.transform = 'scale(1)';
            }, 150);

            showNotification(data.message, 'success');
        }
    } catch (error) {
        console.error('Erreur lors du like de commentaire:', error);
        showNotification('Erreur lors du like', 'error');
    } finally {
        // Réactiver le bouton
        likeBtn.disabled = false;
        likeBtn.style.opacity = '1';
    }
}

// Fonction pour afficher/masquer le formulaire de réponse
function toggleReplyForm(commentId) {
    const replyForm = document.getElementById(`reply-form-${commentId}`);
    const replyInput = document.getElementById(`reply-input-${commentId}`);

    if (replyForm.classList.contains('hidden')) {
        // Masquer tous les autres formulaires de réponse
        const allReplyForms = document.querySelectorAll('[id^="reply-form-"]');
        allReplyForms.forEach(form => form.classList.add('hidden'));

        // Afficher ce formulaire
        replyForm.classList.remove('hidden');
        replyInput.focus();

        // Animation d'apparition
        replyForm.style.opacity = '0';
        replyForm.style.transform = 'translateY(-10px)';
        setTimeout(() => {
            replyForm.style.transition = 'all 0.3s ease-out';
            replyForm.style.opacity = '1';
            replyForm.style.transform = 'translateY(0)';
        }, 10);
    } else {
        // Masquer le formulaire
        replyForm.style.transition = 'all 0.3s ease-out';
        replyForm.style.opacity = '0';
        replyForm.style.transform = 'translateY(-10px)';
        setTimeout(() => {
            replyForm.classList.add('hidden');
            replyForm.style.transition = '';
            replyForm.style.opacity = '';
            replyForm.style.transform = '';
            replyInput.value = '';
        }, 300);
    }
}

// Fonction pour afficher/masquer les réponses
function toggleReplies(commentId) {
    const repliesContainer = document.getElementById(`replies-${commentId}`);
    const toggleButton = document.getElementById(`replies-toggle-${commentId}`);
    const arrow = toggleButton.querySelector('svg');

    if (repliesContainer.classList.contains('hidden')) {
        // Afficher les réponses
        repliesContainer.classList.remove('hidden');
        arrow.style.transform = 'rotate(180deg)';

        // Animation d'apparition
        repliesContainer.style.opacity = '0';
        repliesContainer.style.transform = 'translateY(-10px)';
        setTimeout(() => {
            repliesContainer.style.transition = 'all 0.3s ease-out';
            repliesContainer.style.opacity = '1';
            repliesContainer.style.transform = 'translateY(0)';
        }, 10);
    } else {
        // Masquer les réponses
        repliesContainer.style.transition = 'all 0.3s ease-out';
        repliesContainer.style.opacity = '0';
        repliesContainer.style.transform = 'translateY(-10px)';
        arrow.style.transform = 'rotate(0deg)';
        setTimeout(() => {
            repliesContainer.classList.add('hidden');
            repliesContainer.style.transition = '';
            repliesContainer.style.opacity = '';
            repliesContainer.style.transform = '';
        }, 300);
    }
}

// Fonction pour ajouter une réponse
async function addReply(event, postId, parentCommentId) {
    event.preventDefault();

    const replyInput = document.getElementById(`reply-input-${parentCommentId}`);
    const replyBtn = document.getElementById(`reply-btn-${parentCommentId}`);

    const content = replyInput.value.trim();
    if (!content) return;

    // Désactiver le formulaire
    replyBtn.disabled = true;
    replyBtn.textContent = 'Envoi...';

    try {
        const formData = new FormData();
        formData.append('content', content);
        formData.append('parent_id', parentCommentId);

        const response = await fetch(`/posts/${postId}/comments-with-replies`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        });

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const data = await response.json();

        if (data.success) {
            // Vérifier que c'est bien une réponse avec parent_id
            if (!data.comment.parent_id || data.comment.parent_id != parentCommentId) {
                console.error('Erreur: La réponse créée n\'a pas le bon parent_id', data.comment);
                showNotification('Erreur: La réponse n\'a pas été créée correctement', 'error');
                return;
            }

            // Récupérer les informations du commentaire parent
            const parentCommentElement = document.querySelector(`#comment-like-btn-${parentCommentId}`).closest('.comment-item');
            if (!parentCommentElement) {
                console.error('Erreur: Impossible de trouver l\'élément du commentaire parent', parentCommentId);
                showNotification('Erreur: Commentaire parent introuvable', 'error');
                return;
            }

            const parentCommentUserLink = parentCommentElement.querySelector('a[href*="/profile/"]');
            if (!parentCommentUserLink) {
                console.error('Erreur: Impossible de trouver le lien du profil utilisateur parent');
                showNotification('Erreur: Utilisateur parent introuvable', 'error');
                return;
            }

            const parentCommentUserName = parentCommentUserLink.textContent.trim();
            const parentCommentUserId = parentCommentUserLink.href.split('/profile/')[1];

            const parentCommentUser = {
                id: parentCommentUserId,
                name: parentCommentUserName
            };

            console.log('Création de la réponse:', {
                reply: data.comment,
                parentUser: parentCommentUser,
                parentCommentId: parentCommentId
            });

            // Créer l'élément de réponse
            const newReply = createReplyElement(data.comment, parentCommentUser);

            // Chercher ou créer le conteneur de réponses
            let repliesContainer = document.getElementById(`replies-${parentCommentId}`);
            const parentComment = parentCommentElement;

            if (!repliesContainer) {
                // Créer le conteneur s'il n'existe pas
                repliesContainer = document.createElement('div');
                repliesContainer.id = `replies-${parentCommentId}`;
                repliesContainer.className = 'mt-3 space-y-3';

                // Insérer après le formulaire de réponse
                const replyForm = document.getElementById(`reply-form-${parentCommentId}`);
                replyForm.parentNode.insertBefore(repliesContainer, replyForm.nextSibling);

                // Créer et ajouter le bouton "voir réponses" s'il n'existe pas
                const actionsDiv = parentComment.querySelector('.flex.items-center.space-x-4');
                if (!document.getElementById(`replies-toggle-${parentCommentId}`)) {
                    const toggleBtn = document.createElement('button');
                    toggleBtn.type = 'button';
                    toggleBtn.id = `replies-toggle-${parentCommentId}`;
                    toggleBtn.onclick = () => toggleReplies(parentCommentId);
                    toggleBtn.className = 'flex items-center space-x-1 text-sm text-indigo-600 hover:text-indigo-800 transition-colors duration-200';
                    toggleBtn.innerHTML = `
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                        <span>1 réponse</span>
                    `;
                    actionsDiv.appendChild(toggleBtn);
                }
            }

            // Ajouter la nouvelle réponse
            repliesContainer.appendChild(newReply);

            // S'assurer que les réponses sont toujours visibles (ne jamais masquer)
            repliesContainer.classList.remove('hidden');

            // Mettre à jour le bouton toggle s'il existe
            const toggleBtn = document.getElementById(`replies-toggle-${parentCommentId}`);
            if (toggleBtn) {
                const replyCount = repliesContainer.children.length;
                toggleBtn.querySelector('span').textContent = `${replyCount} ${replyCount > 1 ? 'réponses' : 'réponse'}`;
            }

            // Vider le champ et masquer le formulaire
            replyInput.value = '';
            toggleReplyForm(parentCommentId);

            // Animation d'apparition
            newReply.style.opacity = '0';
            newReply.style.transform = 'translateY(20px)';
            setTimeout(() => {
                newReply.style.transition = 'all 0.3s ease-out';
                newReply.style.opacity = '1';
                newReply.style.transform = 'translateY(0)';
            }, 10);

            showNotification(data.message, 'success');
        }
    } catch (error) {
        console.error('Erreur lors de l\'ajout de la réponse:', error);
        showNotification('Erreur lors de l\'ajout de la réponse', 'error');
    } finally {
        // Réactiver le formulaire
        replyBtn.disabled = false;
        replyBtn.textContent = 'Répondre';
    }
}

// Fonction pour créer un élément de réponse
function createReplyElement(reply, parentCommentUser) {
    const div = document.createElement('div');
    div.className = 'reply-item bg-gray-50 dark:bg-gray-700 rounded-lg p-3 ml-8 border-l-3 border-indigo-200 dark:border-indigo-600';

    const profileImage = reply.user.profile_image
        ? `/storage/${reply.user.profile_image}`
        : `https://ui-avatars.com/api/?name=${encodeURIComponent(reply.user.name)}&size=32`;

    div.innerHTML = `
        <!-- Indication de réponse -->
        <div class="flex items-center space-x-1 mb-2">
            <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path>
            </svg>
            <span class="text-xs text-gray-500 dark:text-gray-400">
                En réponse à
                <a href="/profile/${parentCommentUser.id}" class="font-medium text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300">
                    ${parentCommentUser.name}
                </a>
            </span>
        </div>

        <div class="flex space-x-3">
            <div class="flex-shrink-0">
                <a href="/profile/${reply.user.id}" class="block">
                    <img class="h-8 w-8 rounded-full object-cover border-2 border-gray-200 dark:border-gray-500"
                         src="${profileImage}"
                         alt="${reply.user.name}">
                </a>
            </div>
            <div class="flex-1 min-w-0">
                <div class="flex items-center justify-between mb-1">
                    <div class="flex items-center space-x-2">
                        <a href="/profile/${reply.user.id}"
                           class="font-medium text-gray-900 dark:text-gray-100 hover:text-indigo-600 dark:hover:text-indigo-400 text-sm">
                            ${reply.user.name}
                        </a>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="text-gray-500 text-xs dark:text-gray-400">
                            À l'instant
                        </span>

                        <button type="button"
                            onclick="deleteComment(${reply.id})"
                            class="text-gray-400 hover:text-red-600 transition-colors duration-200"
                            title="Supprimer la réponse">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </button>
                    </div>
                </div>
                <p class="text-gray-900 dark:text-gray-100 text-sm leading-relaxed">${reply.content}</p>

                <div class="flex items-center space-x-3 mt-2">
                    <button type="button"
                        onclick="toggleCommentLike(${reply.id})"
                        id="comment-like-btn-${reply.id}"
                        class="flex items-center space-x-1 text-xs text-gray-500 hover:text-indigo-600 transition-colors duration-200">
                        <svg id="comment-like-icon-${reply.id}" class="w-3 h-3"
                             fill="none"
                             stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"></path>
                        </svg>
                        <span id="comment-like-count-${reply.id}">0</span>
                    </button>
                </div>
            </div>
        </div>
    `;

    return div;
}

// Fonction pour supprimer un commentaire
async function deleteComment(commentId) {
    // Demander confirmation
    if (!confirm('Êtes-vous sûr de vouloir supprimer ce commentaire ? Cette action est irréversible.')) {
        return;
    }

    try {
        const response = await fetch(`/comments/${commentId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const data = await response.json();

        if (data.success) {
            // Trouver l'élément du commentaire
            const commentElement = document.querySelector(`#comment-like-btn-${commentId}`).closest('.comment-item, .reply-item');

            if (commentElement) {
                // Animation de disparition
                commentElement.style.transition = 'all 0.3s ease-out';
                commentElement.style.opacity = '0';
                commentElement.style.transform = 'translateX(-20px)';

                setTimeout(() => {
                    commentElement.remove();
                }, 300);
            }

            // Fermer le menu dropdown s'il est ouvert
            const commentMenu = document.getElementById(`comment-menu-${commentId}`);
            if (commentMenu) {
                commentMenu.classList.add('hidden');
            }

            showNotification(data.message, 'success');
        }
    } catch (error) {
        console.error('Erreur lors de la suppression du commentaire:', error);
        showNotification('Erreur lors de la suppression', 'error');
    }
}

// Fonction pour toggle le menu des commentaires
function toggleCommentMenu(commentId) {
    const menu = document.getElementById(`comment-menu-${commentId}`);
    const allMenus = document.querySelectorAll('[id^="comment-menu-"]');

    // Fermer tous les autres menus
    allMenus.forEach(otherMenu => {
        if (otherMenu.id !== `comment-menu-${commentId}`) {
            otherMenu.classList.add('hidden');
        }
    });

    // Toggle ce menu
    menu.classList.toggle('hidden');
}

// Fermer les menus quand on clique ailleurs
document.addEventListener('click', function(event) {
    if (!event.target.closest('[onclick*="toggleCommentMenu"]') && !event.target.closest('[id^="comment-menu-"]')) {
        const allMenus = document.querySelectorAll('[id^="comment-menu-"]');
        allMenus.forEach(menu => menu.classList.add('hidden'));
    }
});

    </script>

    <style>
    .notifications-sidebar {
        overflow-y: auto;
        scrollbar-width: thin;
        scrollbar-color: rgba(156, 163, 175, 0.5) transparent;
    }

    .notifications-sidebar::-webkit-scrollbar {
        width: 6px;
    }

    .notifications-sidebar::-webkit-scrollbar-track {
        background: transparent;
    }

    .notifications-sidebar::-webkit-scrollbar-thumb {
        background-color: rgba(156, 163, 175, 0.5);
        border-radius: 3px;
    }

    .notifications-sidebar::-webkit-scrollbar-thumb:hover {
        background-color: rgba(156, 163, 175, 0.7);
    }

    /* Dark mode scrollbar */
    .dark .notifications-sidebar::-webkit-scrollbar-thumb {
        background-color: rgba(75, 85, 99, 0.5);
    }

    .dark .notifications-sidebar::-webkit-scrollbar-thumb:hover {
        background-color: rgba(75, 85, 99, 0.7);
    }
    </style>
</x-app-layout>