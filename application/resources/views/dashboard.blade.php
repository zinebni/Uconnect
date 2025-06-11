<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Profile Header Section -->
            <div class="relative overflow-hidden rounded-2xl shadow-xl">
                <!-- Animated Background -->
                <div class="absolute inset-0 bg-gradient-to-r from-blue-500 via-purple-500 to-pink-500 opacity-90">
                    <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjAiIGhlaWdodD0iNjAiIHZpZXdCb3g9IjAgMCA2MCA2MCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48ZyBmaWxsPSJub25lIiBmaWxsLXJ1bGU9ImV2ZW5vZGQiPjxnIGZpbGw9IiNmZmZmZmYiIGZpbGwtb3BhY2l0eT0iMC40Ij48cGF0aCBkPSJNMzYgMzRjMC0yLjIxLTEuNzktNC00LTRzLTQgMS43OS00IDQgMS43OSA0IDQgNCA0LTEuNzkgNC00eiIvPjwvZz48L2c+PC9zdmc+')] opacity-20"></div>
                    <div class="absolute inset-0 bg-gradient-to-b from-transparent to-black opacity-30"></div>
                </div>

                <!-- Profile Content -->
                <div class="relative">
                    <!-- Cover Image -->
                    <div class="h-40 bg-gradient-to-r from-indigo-500 to-purple-600 opacity-50"></div>

                    <!-- Profile Info -->
                    <div class="px-6 pb-6">
                        <div class="flex flex-col sm:flex-row items-center sm:items-end -mt-12 sm:-mt-12">
                            <!-- Profile Image -->
                            <div class="relative group">
                                @if(Auth::user()->profile_image)
                                    <img src="{{ Storage::url(Auth::user()->profile_image) }}"
                                         alt="{{ Auth::user()->name }}"
                                         class="h-24 w-24 rounded-full border-4 border-white object-cover shadow-xl transition-transform duration-300 group-hover:scale-105">
                                @else
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&size=96"
                                         alt="{{ Auth::user()->name }}"
                                         class="h-24 w-24 rounded-full border-4 border-white shadow-xl transition-transform duration-300 group-hover:scale-105">
                                @endif
                                <a href="{{ route('profile.edit') }}"
                                   class="absolute bottom-1 right-1 bg-white rounded-full p-2 shadow-lg hover:bg-gray-100 transition-all duration-300 hover:scale-110">
                                    <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </a>
                            </div>

                            <!-- User Info & Stats -->
                            <div class="sm:ml-6 mt-4 sm:mt-0 text-center sm:text-left flex-1">
                                <h1 class="text-2xl font-bold text-white drop-shadow-lg">{{ Auth::user()->name }}</h1>
                                <p class="text-gray-200 text-sm font-medium mb-2">{{ '@' . Str::slug(Auth::user()->name) }}</p>

                                @if(Auth::user()->academic_status)
                                    <div class="mb-4 inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-white/20 text-white backdrop-blur-sm">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path>
                                        </svg>
                                        {{ Auth::user()->academic_status }}
                                    </div>
                                @else
                                    <div class="mb-4"></div>
                                @endif

                                <!-- Stats Row -->
                                <div class="flex justify-center sm:justify-start space-x-6 text-white">
                                    <div class="text-center">
                                        <div class="text-xl font-bold">{{ $stats['posts_count'] }}</div>
                                        <div class="text-xs text-gray-200">Publications</div>
                                    </div>
                                    <button onclick="showFollowersModal()" class="text-center hover:bg-white/10 px-3 py-1 rounded-lg transition-colors">
                                        <div class="text-xl font-bold">{{ $stats['followers_count'] }}</div>
                                        <div class="text-xs text-gray-200">Abonnés</div>
                                    </button>
                                    <button onclick="showFollowingModal()" class="text-center hover:bg-white/10 px-3 py-1 rounded-lg transition-colors">
                                        <div class="text-xl font-bold">{{ $stats['following_count'] }}</div>
                                        <div class="text-xs text-gray-200">Abonnements</div>
                                    </button>
                                    <div class="text-center">
                                        <div class="text-xl font-bold">{{ $stats['likes_received'] }}</div>
                                        <div class="text-xs text-gray-200">Likes reçus</div>
                                    </div>
                                </div>

                                <!-- User Details -->
                                <div class="mt-4 flex flex-wrap justify-center sm:justify-start gap-2 text-xs text-gray-200">
                                    <span class="flex items-center bg-white/20 px-3 py-1 rounded-full">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                        </svg>
                                        {{ Auth::user()->email }}
                                    </span>
                                    <span class="flex items-center bg-white/20 px-3 py-1 rounded-full">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        Membre depuis {{ Auth::user()->created_at->format('F Y') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Posts Section -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-2xl">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-2xl font-bold text-gray-800 dark:text-gray-100">
                            Vos publications
                        </h3>
                        <div class="text-sm text-gray-500 dark:text-gray-400">
                            {{ $posts->total() }} publication{{ $posts->total() > 1 ? 's' : '' }}
                        </div>
                    </div>

                    @if($posts->count() > 0)
                        <div class="space-y-6">
                            @foreach($posts as $post)
                                <div class="bg-gray-50 dark:bg-gray-700 rounded-2xl p-6 border border-gray-200 dark:border-gray-600 hover:shadow-lg transition-shadow duration-300">
                                    <!-- Post Header -->
                                    <div class="flex items-center justify-between mb-4">
                                        <div class="flex items-center space-x-3">
                                            @if(Auth::user()->profile_image)
                                                <img class="h-10 w-10 rounded-full object-cover"
                                                     src="{{ Storage::url(Auth::user()->profile_image) }}"
                                                     alt="{{ Auth::user()->name }}">
                                            @else
                                                <img class="h-10 w-10 rounded-full"
                                                     src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&size=40"
                                                     alt="{{ Auth::user()->name }}">
                                            @endif

                                            <div>
                                                <h4 class="font-semibold text-gray-900 dark:text-gray-100">{{ Auth::user()->name }}</h4>
                                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $post->created_at->diffForHumans() }}</p>
                                            </div>
                                        </div>

                                        <!-- Delete Button -->
                                        <button onclick="deletePost({{ $post->id }})" class="text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </div>

                                    <!-- Post Content -->
                                    @if($post->content)
                                        <div class="mb-4">
                                            <p class="text-gray-900 dark:text-gray-100 whitespace-pre-wrap">{{ $post->content }}</p>
                                        </div>
                                    @endif

                                    <!-- Post Media -->
                                    @if($post->image_path)
                                        <div class="post-media-container mb-4">
                                            <img src="{{ Storage::url($post->image_path) }}"
                                                 alt="Post image"
                                                 class="post-image">
                                        </div>
                                    @endif

                                    @if($post->video_path)
                                        <div class="post-media-container mb-4">
                                            <video controls class="post-video">
                                                <source src="{{ Storage::url($post->video_path) }}" type="video/mp4">
                                                Votre navigateur ne supporte pas la lecture de vidéos.
                                            </video>
                                        </div>
                                    @endif

                                    <!-- Post Actions -->
                                    <div class="flex items-center space-x-6 pt-4 border-t border-gray-200 dark:border-gray-600">
                                        <!-- Like Button with AJAX -->
                                        <button onclick="toggleLike({{ $post->id }})"
                                                id="like-btn-{{ $post->id }}"
                                                class="flex items-center space-x-2 transition-colors duration-200 {{ $post->isLikedBy(auth()->user()) ? 'text-indigo-600' : 'text-gray-500 hover:text-indigo-600 dark:text-gray-400 dark:hover:text-indigo-400' }}">
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

                                        <!-- Comment Button -->
                                        <button onclick="toggleComments({{ $post->id }})" class="flex items-center space-x-2 text-gray-500 hover:text-blue-600 dark:text-gray-400 dark:hover:text-blue-400 transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                            </svg>
                                            <span id="comment-count-{{ $post->id }}">{{ $post->comments->count() }}</span>
                                        </button>

                                        <!-- Share Button -->
                                        <button onclick="sharePost({{ $post->id }})" class="flex items-center space-x-2 text-gray-500 hover:text-green-600 dark:text-gray-400 dark:hover:text-green-400 transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"></path>
                                            </svg>
                                            <span>Partager</span>
                                        </button>
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
                                                    <div class="flex items-center justify-end mt-3 space-x-2">
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
                                        </form>

                                        <!-- Comments List -->
                                        <div id="comments-list-{{ $post->id }}" class="space-y-4">
                                            @if($post->comments->count() > 0)
                                                @foreach($post->comments->where('parent_id', null) as $comment)
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
                                                                <div class="text-gray-900 dark:text-gray-100 leading-relaxed mb-3">
                                                                    <p class="whitespace-pre-wrap break-words">{{ $comment->content }}</p>
                                                                </div>

                                                                <!-- Comment Actions -->
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
                                </div>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        <div class="mt-8">
                            {{ $posts->links() }}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="w-16 h-16 mx-auto text-gray-400 dark:text-gray-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">Aucune publication</h3>
                            <p class="text-gray-500 dark:text-gray-400 mb-4">Vous n'avez pas encore publié de contenu.</p>
                            <a href="{{ route('home') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                Créer votre première publication
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modal pour afficher les followers -->
    <div id="followers-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-md w-full max-h-[80vh] overflow-hidden">
            <div class="flex items-center justify-between p-6 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Abonnés</h3>
                <button onclick="closeFollowersModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="p-6">
                <div id="followers-loading" class="flex items-center justify-center py-8">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600"></div>
                    <span class="ml-3 text-gray-600 dark:text-gray-400">Chargement...</span>
                </div>
                <div id="followers-list" class="hidden space-y-3 max-h-96 overflow-y-auto"></div>
                <div id="followers-empty" class="hidden text-center py-8">
                    <p class="text-gray-500 dark:text-gray-400">Aucun abonné pour le moment</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal pour afficher les abonnements -->
    <div id="following-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-md w-full max-h-[80vh] overflow-hidden">
            <div class="flex items-center justify-between p-6 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Abonnements</h3>
                <button onclick="closeFollowingModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="p-6">
                <div id="following-loading" class="flex items-center justify-center py-8">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600"></div>
                    <span class="ml-3 text-gray-600 dark:text-gray-400">Chargement...</span>
                </div>
                <div id="following-list" class="hidden space-y-3 max-h-96 overflow-y-auto"></div>
                <div id="following-empty" class="hidden text-center py-8">
                    <p class="text-gray-500 dark:text-gray-400">Vous ne suivez personne pour le moment</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal pour afficher les likes -->
    <div id="likes-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-md w-full max-h-[80vh] overflow-hidden">
            <div class="flex items-center justify-between p-6 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Personnes qui ont aimé</h3>
                <button onclick="closeLikesModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="p-6">
                <div id="likes-loading" class="flex items-center justify-center py-8">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600"></div>
                    <span class="ml-3 text-gray-600 dark:text-gray-400">Chargement...</span>
                </div>
                <div id="likes-list" class="hidden space-y-3 max-h-96 overflow-y-auto"></div>
                <div id="likes-empty" class="hidden text-center py-8">
                    <p class="text-gray-500 dark:text-gray-400">Aucun like pour le moment</p>
                </div>
            </div>
        </div>
    </div>

    <script>
    // Fonctions pour les likes (AJAX)
    async function toggleLike(postId) {
        const likeBtn = document.getElementById(`like-btn-${postId}`);
        const likeIcon = document.getElementById(`like-icon-${postId}`);
        const likeCount = document.getElementById(`like-count-${postId}`);

        likeBtn.disabled = true;
        likeBtn.style.opacity = '0.6';

        try {
            const isCurrentlyLiked = likeBtn.classList.contains('text-indigo-600');
            const url = `/posts/${postId}/like`;
            const method = isCurrentlyLiked ? 'DELETE' : 'POST';

            const response = await fetch(url, {
                method: method,
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
                likeCount.textContent = data.likes_count;

                if (data.liked) {
                    // Post aimé - pouce plein bleu
                    likeBtn.className = 'flex items-center space-x-2 transition-colors duration-200 text-indigo-600';
                    likeIcon.setAttribute('fill', 'currentColor');
                    likeIcon.innerHTML = '<path d="M2 10.5a1.5 1.5 0 113 0v6a1.5 1.5 0 01-3 0v-6zM6 10.333v5.43a2 2 0 001.106 1.79l.05.025A4 4 0 008.943 18h5.416a2 2 0 001.962-1.608l1.2-6A2 2 0 0015.56 8H12V4a2 2 0 00-2-2 1 1 0 00-1 1v.667a4 4 0 01-.8 2.4L6.8 7.933a4 4 0 00-.8 2.4z"></path>';
                } else {
                    // Post pas aimé - pouce outline gris
                    likeBtn.className = 'flex items-center space-x-2 transition-colors duration-200 text-gray-500 hover:text-indigo-600 dark:text-gray-400 dark:hover:text-indigo-400';
                    likeIcon.setAttribute('fill', 'none');
                    likeIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"></path>';
                }

                likeBtn.style.transform = 'scale(1.1)';
                setTimeout(() => {
                    likeBtn.style.transform = 'scale(1)';
                }, 150);
            }
        } catch (error) {
            console.error('Erreur lors du like:', error);
            showNotification('Erreur lors du like', 'error');
        } finally {
            likeBtn.disabled = false;
            likeBtn.style.opacity = '1';
        }
    }

    // Fonction pour toggle les commentaires
    function toggleComments(postId) {
        const commentsSection = document.getElementById(`comments-${postId}`);
        commentsSection.classList.toggle('hidden');
    }

    // Fonction pour ajouter un commentaire (AJAX)
    async function addComment(event, postId) {
        event.preventDefault();

        const commentInput = document.getElementById(`comment-input-${postId}`);
        const commentBtn = document.getElementById(`comment-btn-${postId}`);
        const commentsList = document.getElementById(`comments-list-${postId}`);
        const commentCount = document.getElementById(`comment-count-${postId}`);

        const content = commentInput.value.trim();
        if (!content) return;

        commentBtn.disabled = true;
        commentBtn.textContent = 'Envoi...';

        try {
            const formData = new FormData();
            formData.append('content', content);

            const response = await fetch(`/posts/${postId}/comments`, {
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
                commentCount.textContent = data.comments_count;

                const newComment = createCommentElement(data.comment);
                commentsList.appendChild(newComment);

                commentInput.value = '';

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
            commentBtn.disabled = false;
            commentBtn.textContent = 'Commenter';
        }
    }

    // Fonction pour créer un élément commentaire
    function createCommentElement(comment) {
        const div = document.createElement('div');
        div.className = 'flex space-x-3';

        const profileImage = comment.user.profile_image
            ? `/storage/${comment.user.profile_image}`
            : `https://ui-avatars.com/api/?name=${encodeURIComponent(comment.user.name)}&size=32`;

        div.innerHTML = `
            <img class="h-8 w-8 rounded-full object-cover"
                 src="${profileImage}"
                 alt="${comment.user.name}">
            <div class="flex-1">
                <div class="bg-gray-100 dark:bg-gray-600 rounded-lg p-3">
                    <div class="flex items-center justify-between">
                        <a href="/profile/${comment.user.id}"
                           class="font-semibold hover:underline text-gray-900 dark:text-gray-100">${comment.user.name}</a>
                        <span class="text-gray-500 text-sm dark:text-gray-400">À l'instant</span>
                    </div>
                    <p class="mt-1 text-gray-700 dark:text-gray-300">${comment.content}</p>
                </div>
            </div>
        `;

        return div;
    }

    // Fonction pour supprimer un post
    async function deletePost(postId) {
        if (!confirm('Êtes-vous sûr de vouloir supprimer cette publication ?')) {
            return;
        }

        try {
            const response = await fetch(`/posts/${postId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                }
            });

            if (response.ok) {
                const postElement = document.querySelector(`[data-post-id="${postId}"]`) ||
                                  document.querySelector(`button[onclick="deletePost(${postId})"]`).closest('.bg-gray-50');

                if (postElement) {
                    postElement.style.transition = 'opacity 0.3s ease-out, transform 0.3s ease-out';
                    postElement.style.opacity = '0';
                    postElement.style.transform = 'translateX(-100%)';

                    setTimeout(() => {
                        postElement.remove();
                        showNotification('Publication supprimée', 'success');
                    }, 300);
                } else {
                    location.reload();
                }
            } else {
                showNotification('Erreur lors de la suppression', 'error');
            }
        } catch (error) {
            console.error('Erreur lors de la suppression:', error);
            showNotification('Erreur lors de la suppression', 'error');
        }
    }

    // Fonction pour partager un post
    function sharePost(postId) {
        const url = `${window.location.origin}/posts/${postId}`;

        if (navigator.share) {
            navigator.share({
                title: 'Regardez cette publication',
                url: url
            });
        } else {
            navigator.clipboard.writeText(url).then(() => {
                showNotification('Lien copié dans le presse-papiers !', 'success');
            });
        }
    }

    // Fonctions pour les modals followers/following
    async function showFollowersModal() {
        const modal = document.getElementById('followers-modal');
        const loading = document.getElementById('followers-loading');
        const followersList = document.getElementById('followers-list');
        const followersEmpty = document.getElementById('followers-empty');

        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';

        loading.classList.remove('hidden');
        followersList.classList.add('hidden');
        followersEmpty.classList.add('hidden');
        followersList.innerHTML = '';

        try {
            const response = await fetch('/dashboard/followers', {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                }
            });

            if (!response.ok) {
                throw new Error(`HTTP ${response.status}`);
            }

            const data = await response.json();
            loading.classList.add('hidden');

            if (data.success) {
                const followers = data.followers;

                if (followers.length === 0) {
                    followersEmpty.classList.remove('hidden');
                } else {
                    followersList.classList.remove('hidden');

                    followers.forEach(follower => {
                        const followerElement = createFollowerElement(follower);
                        followersList.appendChild(followerElement);
                    });
                }
            } else {
                throw new Error(data.error || 'Response not successful');
            }
        } catch (error) {
            console.error('Erreur lors du chargement des followers:', error);
            loading.classList.add('hidden');
            followersList.innerHTML = '<div class="text-center py-8"><p class="text-red-500">Erreur lors du chargement</p></div>';
            followersList.classList.remove('hidden');
        }
    }

    async function showFollowingModal() {
        const modal = document.getElementById('following-modal');
        const loading = document.getElementById('following-loading');
        const followingList = document.getElementById('following-list');
        const followingEmpty = document.getElementById('following-empty');

        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';

        loading.classList.remove('hidden');
        followingList.classList.add('hidden');
        followingEmpty.classList.add('hidden');
        followingList.innerHTML = '';

        try {
            const response = await fetch('/dashboard/following', {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                }
            });

            if (!response.ok) {
                throw new Error(`HTTP ${response.status}`);
            }

            const data = await response.json();
            loading.classList.add('hidden');

            if (data.success) {
                const following = data.following;

                if (following.length === 0) {
                    followingEmpty.classList.remove('hidden');
                } else {
                    followingList.classList.remove('hidden');

                    following.forEach(user => {
                        const userElement = createFollowingElement(user);
                        followingList.appendChild(userElement);
                    });
                }
            } else {
                throw new Error(data.error || 'Response not successful');
            }
        } catch (error) {
            console.error('Erreur lors du chargement des abonnements:', error);
            loading.classList.add('hidden');
            followingList.innerHTML = '<div class="text-center py-8"><p class="text-red-500">Erreur lors du chargement</p></div>';
            followingList.classList.remove('hidden');
        }
    }

    function createFollowerElement(follower) {
        const div = document.createElement('div');
        div.className = 'flex items-center justify-between p-3 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors';

        const profileImage = follower.profile_image_url
            ? follower.profile_image_url
            : `https://ui-avatars.com/api/?name=${encodeURIComponent(follower.name)}&size=40`;

        div.innerHTML = `
            <div class="flex items-center space-x-3">
                <a href="/profile/${follower.id}" class="flex-shrink-0">
                    <img class="h-10 w-10 rounded-full object-cover border-2 border-gray-200 dark:border-gray-600"
                         src="${profileImage}"
                         alt="${follower.name}">
                </a>
                <div>
                    <a href="/profile/${follower.id}" class="font-medium text-gray-900 dark:text-gray-100 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                        ${follower.name}
                    </a>
                </div>
            </div>
            <div class="flex items-center space-x-2">
                ${follower.is_following ?
                    `<button class="follow-btn px-3 py-1 text-sm bg-gray-200 dark:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-full hover:bg-gray-300 dark:hover:bg-gray-500 transition-colors">
                        Suivi(e)
                    </button>` :
                    `<button class="follow-btn px-3 py-1 text-sm bg-indigo-600 text-white rounded-full hover:bg-indigo-700 transition-colors">
                        Suivre
                    </button>`
                }
            </div>
        `;

        // Ajouter l'event listener après avoir créé l'élément
        const button = div.querySelector('.follow-btn');
        if (button) {
            button.onclick = () => {
                if (follower.is_following) {
                    unfollowUser(follower.id, button);
                } else {
                    followUser(follower.id, button);
                }
            };
        }

        return div;
    }

    function createFollowingElement(user) {
        const div = document.createElement('div');
        div.className = 'flex items-center justify-between p-3 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors';

        const profileImage = user.profile_image_url
            ? user.profile_image_url
            : `https://ui-avatars.com/api/?name=${encodeURIComponent(user.name)}&size=40`;

        div.innerHTML = `
            <div class="flex items-center space-x-3">
                <a href="/profile/${user.id}" class="flex-shrink-0">
                    <img class="h-10 w-10 rounded-full object-cover border-2 border-gray-200 dark:border-gray-600"
                         src="${profileImage}"
                         alt="${user.name}">
                </a>
                <div>
                    <a href="/profile/${user.id}" class="font-medium text-gray-900 dark:text-gray-100 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                        ${user.name}
                    </a>
                </div>
            </div>
            <div class="flex items-center space-x-2">
                <button class="unfollow-btn px-3 py-1 text-sm bg-gray-200 dark:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-full hover:bg-gray-300 dark:hover:bg-gray-500 transition-colors">
                    Ne plus suivre
                </button>
            </div>
        `;

        // Ajouter l'event listener après avoir créé l'élément
        const button = div.querySelector('.unfollow-btn');
        if (button) {
            button.onclick = () => unfollowUser(user.id, button);
        }

        return div;
    }

    function closeFollowersModal() {
        const modal = document.getElementById('followers-modal');
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    function closeFollowingModal() {
        const modal = document.getElementById('following-modal');
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    // Fonctions pour les likes modal (réutilisées de la page home)
    async function showLikesModal(postId) {
        const modal = document.getElementById('likes-modal');
        const loading = document.getElementById('likes-loading');
        const likesList = document.getElementById('likes-list');
        const likesEmpty = document.getElementById('likes-empty');

        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';

        loading.classList.remove('hidden');
        likesList.classList.add('hidden');
        likesEmpty.classList.add('hidden');
        likesList.innerHTML = '';

        try {
            const response = await fetch(`/posts/${postId}/likes`, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();

            loading.classList.add('hidden');

            if (data.success) {
                const likes = data.likes;

                if (likes.length === 0) {
                    likesEmpty.classList.remove('hidden');
                } else {
                    likesList.classList.remove('hidden');

                    likes.forEach(like => {
                        const likeElement = createLikeElement(like);
                        likesList.appendChild(likeElement);
                    });
                }
            }
        } catch (error) {
            console.error('Erreur lors du chargement des likes:', error);
            loading.classList.add('hidden');
            likesList.innerHTML = '<div class="text-center py-8"><p class="text-red-500">Erreur lors du chargement</p></div>';
            likesList.classList.remove('hidden');
        }
    }

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

    function closeLikesModal() {
        const modal = document.getElementById('likes-modal');
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    // Fonctions pour suivre/ne plus suivre
    async function followUser(userId, buttonElement) {
        // Empêcher les clics multiples
        if (buttonElement && buttonElement.disabled) {
            return;
        }

        try {
            // Désactiver le bouton pendant la requête
            if (buttonElement) {
                buttonElement.disabled = true;
                buttonElement.textContent = 'En cours...';
            }

            const response = await fetch(`/users/${userId}/follow`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            const data = await response.json();

            if (data.success) {
                showNotification(data.message, 'success');
                // Mettre à jour le bouton
                if (buttonElement) {
                    buttonElement.textContent = 'Suivi(e)';
                    buttonElement.className = 'px-3 py-1 text-sm bg-gray-200 dark:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-full hover:bg-gray-300 dark:hover:bg-gray-500 transition-colors';
                    buttonElement.onclick = () => unfollowUser(userId, buttonElement);
                }
            } else {
                showNotification(data.message, 'error');
            }
        } catch (error) {
            console.error('Erreur lors du suivi:', error);
            showNotification('Erreur lors du suivi', 'error');
        } finally {
            // Réactiver le bouton
            if (buttonElement) {
                buttonElement.disabled = false;
            }
        }
    }

    async function unfollowUser(userId, buttonElement) {
        // Empêcher les clics multiples
        if (buttonElement && buttonElement.disabled) {
            return;
        }

        try {
            // Désactiver le bouton pendant la requête
            if (buttonElement) {
                buttonElement.disabled = true;
                buttonElement.textContent = 'En cours...';
            }

            const response = await fetch(`/users/${userId}/follow`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            const data = await response.json();

            if (data.success) {
                showNotification(data.message, 'success');
                // Mettre à jour le bouton
                if (buttonElement) {
                    buttonElement.textContent = 'Suivre';
                    buttonElement.className = 'px-3 py-1 text-sm bg-indigo-600 text-white rounded-full hover:bg-indigo-700 transition-colors';
                    buttonElement.onclick = () => followUser(userId, buttonElement);
                }
            } else {
                showNotification(data.message, 'error');
            }
        } catch (error) {
            console.error('Erreur lors du désabonnement:', error);
            showNotification('Erreur lors du désabonnement', 'error');
        } finally {
            // Réactiver le bouton
            if (buttonElement) {
                buttonElement.disabled = false;
            }
        }
    }

    // Fonction pour afficher des notifications toast
    function showNotification(message, type = 'success') {
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 px-6 py-3 rounded-lg text-white z-50 transition-all duration-300 ${
            type === 'success' ? 'bg-green-500' : 'bg-red-500'
        }`;
        notification.textContent = message;

        document.body.appendChild(notification);

        setTimeout(() => {
            notification.style.transform = 'translateX(0)';
            notification.style.opacity = '1';
        }, 10);

        setTimeout(() => {
            notification.style.transform = 'translateX(100%)';
            notification.style.opacity = '0';
            setTimeout(() => {
                document.body.removeChild(notification);
            }, 300);
        }, 3000);
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

    // Fonction pour vider le commentaire
    function clearComment(postId) {
        const commentInput = document.getElementById(`comment-input-${postId}`);
        const charCounter = document.getElementById(`char-counter-${postId}`);

        commentInput.value = '';
        if (charCounter) {
            charCounter.textContent = '0/500';
            charCounter.className = 'absolute bottom-2 right-2 text-xs text-gray-400';
        }
        commentInput.focus();
    }

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
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.60L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"></path>
                            </svg>
                            <span id="comment-like-count-${reply.id}">0</span>
                        </button>
                    </div>
                </div>
            </div>
        `;

        return div;
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

        // Fermer les menus quand on clique ailleurs
        document.addEventListener('click', function(event) {
            if (!event.target.closest('[onclick*="toggleCommentMenu"]') && !event.target.closest('[id^="comment-menu-"]')) {
                const allMenus = document.querySelectorAll('[id^="comment-menu-"]');
                allMenus.forEach(menu => menu.classList.add('hidden'));
            }
        });
    });
    </script>

</x-app-layout>