<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-4">
            <a href="{{ route('home') }}" class="text-indigo-600 hover:text-indigo-800 dark:text-indigo-400">
                ← Retour à l'accueil
            </a>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Post de {{ $post->user->name }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">

                <!-- Post Content -->
                <div class="p-6">
                    <!-- User Info -->
                    <div class="flex items-center space-x-3 mb-4">
                        @if($post->user->profile_image)
                            <img class="h-12 w-12 rounded-full object-cover"
                                 src="{{ Storage::url($post->user->profile_image) }}"
                                 alt="{{ $post->user->name }}">
                        @else
                            <img class="h-12 w-12 rounded-full"
                                 src="https://ui-avatars.com/api/?name={{ urlencode($post->user->name) }}&size=48"
                                 alt="{{ $post->user->name }}">
                        @endif

                        <div>
                            <h3 class="font-semibold text-gray-900 dark:text-gray-100">
                                <a href="{{ route('profile.show', $post->user) }}" class="hover:text-indigo-600 dark:hover:text-indigo-400">
                                    {{ $post->user->name }}
                                </a>
                            </h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                {{ $post->created_at->diffForHumans() }}
                            </p>
                        </div>
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
                    <div class="flex items-center space-x-6 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <!-- Like Button et Count séparés -->
                        <div class="flex items-center space-x-2">
                            <!-- Bouton Like seulement -->
                            <form action="{{ $post->likes()->where('user_id', Auth::id())->exists() ? route('posts.unlike', $post) : route('posts.like', $post) }}"
                                  method="POST" class="inline">
                                @csrf
                                @if($post->likes()->where('user_id', Auth::id())->exists())
                                    @method('DELETE')
                                @endif
                                <button type="submit" class="flex items-center {{ $post->likes()->where('user_id', Auth::id())->exists() ? 'text-indigo-600' : 'text-gray-600 hover:text-indigo-600 dark:text-gray-400 dark:hover:text-indigo-400' }} transition-colors duration-200">
                                    <svg class="w-5 h-5" fill="{{ $post->likes()->where('user_id', Auth::id())->exists() ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                                        @if($post->likes()->where('user_id', Auth::id())->exists())
                                            <path d="M2 10.5a1.5 1.5 0 113 0v6a1.5 1.5 0 01-3 0v-6zM6 10.333v5.43a2 2 0 001.106 1.79l.05.025A4 4 0 008.943 18h5.416a2 2 0 001.962-1.608l1.2-6A2 2 0 0015.56 8H12V4a2 2 0 00-2-2 1 1 0 00-1 1v.667a4 4 0 01-.8 2.4L6.8 7.933a4 4 0 00-.8 2.4z"></path>
                                        @else
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"></path>
                                        @endif
                                    </svg>
                                </button>
                            </form>

                            <!-- Nombre de likes séparé -->
                            <span onclick="showLikesModal({{ $post->id }})"
                                  class="cursor-pointer hover:underline text-blue-600 font-semibold">{{ $post->likes()->count() }}</span>
                        </div>

                        <!-- Comment Count -->
                        <div class="flex items-center space-x-2 text-gray-500 dark:text-gray-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                            </svg>
                            <span>{{ $post->comments()->count() }}</span>
                        </div>
                    </div>
                </div>

                <!-- Comments Section -->
                <div class="border-t border-gray-200 dark:border-gray-700">
                    <!-- Add Comment Form -->
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                        <form action="{{ route('posts.comments.store', $post) }}" method="POST">
                            @csrf
                            <div class="flex space-x-3">
                                @if(Auth::user()->profile_image)
                                    <img class="h-8 w-8 rounded-full object-cover"
                                         src="{{ Storage::url(Auth::user()->profile_image) }}"
                                         alt="{{ Auth::user()->name }}">
                                @else
                                    <img class="h-8 w-8 rounded-full"
                                         src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&size=32"
                                         alt="{{ Auth::user()->name }}">
                                @endif

                                <div class="flex-1">
                                    <textarea name="content"
                                              placeholder="Écrivez un commentaire..."
                                              class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 resize-none"
                                              rows="2" required></textarea>
                                    <div class="mt-2">
                                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md text-sm transition">
                                            Commenter
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Comments List -->
                    <div class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($post->comments as $comment)
                            <div class="p-6">
                                <div class="flex space-x-3">
                                    @if($comment->user->profile_image)
                                        <img class="h-8 w-8 rounded-full object-cover"
                                             src="{{ Storage::url($comment->user->profile_image) }}"
                                             alt="{{ $comment->user->name }}">
                                    @else
                                        <img class="h-8 w-8 rounded-full"
                                             src="https://ui-avatars.com/api/?name={{ urlencode($comment->user->name) }}&size=32"
                                             alt="{{ $comment->user->name }}">
                                    @endif

                                    <div class="flex-1">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center space-x-2">
                                                <h4 class="font-medium text-gray-900 dark:text-gray-100">
                                                    <a href="{{ route('profile.show', $comment->user) }}" class="hover:text-indigo-600 dark:hover:text-indigo-400">
                                                        {{ $comment->user->name }}
                                                    </a>
                                                </h4>
                                                <span class="text-sm text-gray-500 dark:text-gray-400">
                                                    {{ $comment->created_at->diffForHumans() }}
                                                </span>
                                            </div>

                                            @if(auth()->check() && $comment->user_id === auth()->id())
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
                                        <p class="mt-1 text-gray-700 dark:text-gray-300">{{ $comment->content }}</p>

                                        @auth
                                        <!-- Actions du commentaire -->
                                        <div class="flex items-center space-x-4 mt-3">
                                            <!-- Bouton Like -->
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
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.60L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 712-2h2.5"></path>
                                                    @endif
                                                </svg>
                                                <span id="comment-like-count-{{ $comment->id }}">{{ $comment->likes()->count() }}</span>
                                            </button>

                                            <!-- Bouton Répondre -->
                                            <button type="button"
                                                onclick="toggleReplyForm({{ $comment->id }})"
                                                class="flex items-center space-x-1 text-sm text-gray-500 hover:text-indigo-600 transition-colors duration-200">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path>
                                                </svg>
                                                <span>Répondre</span>
                                            </button>
                                        </div>
                                        @endauth

                                        <!-- Formulaire de réponse -->
                                        @auth
                                        <div id="reply-form-{{ $comment->id }}" class="hidden mt-4 ml-8">
                                            <div class="flex space-x-3">
                                                @if(Auth::user()->profile_image)
                                                    <img class="h-6 w-6 rounded-full object-cover border-2 border-gray-200 dark:border-gray-500"
                                                         src="{{ Storage::url(Auth::user()->profile_image) }}"
                                                         alt="{{ Auth::user()->name }}">
                                                @else
                                                    <img class="h-6 w-6 rounded-full border-2 border-gray-200 dark:border-gray-500"
                                                         src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&size=24"
                                                         alt="{{ Auth::user()->name }}">
                                                @endif
                                                <div class="flex-1">
                                                    <textarea id="reply-input-{{ $comment->id }}"
                                                              placeholder="Écrivez votre réponse..."
                                                              class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 resize-none text-sm"
                                                              rows="2"></textarea>
                                                    <div class="flex justify-between items-center mt-2">
                                                        <button type="button"
                                                                onclick="toggleReplyForm({{ $comment->id }})"
                                                                class="text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                                                            Annuler
                                                        </button>
                                                        <button type="button"
                                                                onclick="addReply({{ $comment->id }}, {{ $post->id }})"
                                                                class="bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-1 rounded-md text-sm transition">
                                                            Répondre
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @endauth

                                        <!-- Replies List - Directement sous le commentaire -->
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
                                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.60L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 712-2h2.5"></path>
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
                        @empty
                            <div class="p-6 text-center text-gray-500 dark:text-gray-400">
                                <p>Aucun commentaire pour le moment.</p>
                                <p class="text-sm">Soyez le premier à commenter !</p>
                            </div>
                        @endforelse
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
    // Fonctions pour gérer le modal des likes
    async function showLikesModal(postId) {
        const modal = document.getElementById('likes-modal');
        const loading = document.getElementById('likes-loading');
        const likesList = document.getElementById('likes-list');
        const likesEmpty = document.getElementById('likes-empty');

        // Afficher le modal
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';

        // Réinitialiser l'état
        loading.classList.remove('hidden');
        likesList.classList.add('hidden');
        likesEmpty.classList.add('hidden');
        likesList.innerHTML = '';

        try {
            const response = await axios.get(`/posts/${postId}/likes`, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (response.data.success) {
                const likes = response.data.likes;

                loading.classList.add('hidden');

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
        document.body.style.overflow = 'auto';
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

    // Fermer le modal en cliquant à l'extérieur
    document.getElementById('likes-modal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeLikesModal();
        }
    });

    // Fonction pour toggle le formulaire de réponse
    function toggleReplyForm(commentId) {
        const replyForm = document.getElementById(`reply-form-${commentId}`);
        const replyInput = document.getElementById(`reply-input-${commentId}`);

        if (replyForm.classList.contains('hidden')) {
            // Masquer tous les autres formulaires de réponse
            document.querySelectorAll('[id^="reply-form-"]').forEach(form => {
                if (form.id !== `reply-form-${commentId}`) {
                    form.classList.add('hidden');
                }
            });

            // Afficher ce formulaire
            replyForm.classList.remove('hidden');
            replyInput.focus();
        } else {
            // Masquer ce formulaire
            replyForm.classList.add('hidden');
            replyInput.value = '';
        }
    }

    // Fonction pour ajouter une réponse
    async function addReply(parentCommentId, postId) {
        const replyInput = document.getElementById(`reply-input-${parentCommentId}`);
        const content = replyInput.value.trim();
        const replyBtn = document.querySelector(`#reply-form-${parentCommentId} button[onclick*="addReply"]`);

        if (!content) {
            alert('Veuillez saisir votre réponse');
            return;
        }

        // Désactiver le formulaire pendant l'envoi
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
                    alert('Erreur: La réponse n\'a pas été créée correctement');
                    return;
                }

                // Récupérer les informations du commentaire parent
                const parentCommentElement = document.querySelector(`#comment-like-btn-${parentCommentId}`).closest('.p-6');
                if (!parentCommentElement) {
                    console.error('Erreur: Impossible de trouver l\'élément du commentaire parent', parentCommentId);
                    alert('Erreur: Commentaire parent introuvable');
                    return;
                }

                const parentCommentUserLink = parentCommentElement.querySelector('a[href*="/profile/"]');
                if (!parentCommentUserLink) {
                    console.error('Erreur: Impossible de trouver le lien du profil utilisateur parent');
                    alert('Erreur: Utilisateur parent introuvable');
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
                }

                // Ajouter la nouvelle réponse
                repliesContainer.appendChild(newReply);

                // S'assurer que les réponses sont toujours visibles (ne jamais masquer)
                repliesContainer.classList.remove('hidden');

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

                alert(data.message);
            }
        } catch (error) {
            console.error('Erreur lors de l\'ajout de la réponse:', error);
            alert('Erreur lors de l\'ajout de la réponse');
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
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.60L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 712-2h2.5"></path>
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
                // Trouver l'élément du commentaire (dans posts.show, c'est le div parent ou reply-item)
                const commentElement = document.querySelector(`#comment-like-btn-${commentId}`).closest('.p-6, .reply-item');

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

                // Afficher une notification de succès
                alert(data.message);
            }
        } catch (error) {
            console.error('Erreur lors de la suppression du commentaire:', error);
            alert('Erreur lors de la suppression');
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

    // Fonction pour toggle le like d'un commentaire
    async function toggleCommentLike(commentId) {
        const likeBtn = document.getElementById(`comment-like-btn-${commentId}`);
        const likeIcon = document.getElementById(`comment-like-icon-${commentId}`);
        const likeCount = document.getElementById(`comment-like-count-${commentId}`);

        // Désactiver temporairement le bouton
        likeBtn.disabled = true;

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
                // Mettre à jour l'interface
                likeCount.textContent = data.likes_count;

                if (data.liked) {
                    // L'utilisateur a liké
                    likeBtn.classList.remove('text-gray-500', 'hover:text-indigo-600');
                    likeBtn.classList.add('text-indigo-600');
                    likeIcon.setAttribute('fill', 'currentColor');
                    likeIcon.innerHTML = '<path d="M14 9V5a3 3 0 0 0-3-3l-4 9v11h11.28a2 2 0 0 0 2-1.7l1.38-9a2 2 0 0 0-2-2.3zM7 22H4a2 2 0 0 1-2-2v-7a2 2 0 0 1 2-2h3"></path>';
                } else {
                    // L'utilisateur a unliké
                    likeBtn.classList.remove('text-indigo-600');
                    likeBtn.classList.add('text-gray-500', 'hover:text-indigo-600');
                    likeIcon.setAttribute('fill', 'none');
                    likeIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.60L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"></path>';
                }

                // Animation du bouton
                likeBtn.style.transform = 'scale(1.1)';
                setTimeout(() => {
                    likeBtn.style.transform = 'scale(1)';
                }, 150);
            }
        } catch (error) {
            console.error('Erreur lors du toggle du like:', error);
            alert('Erreur lors de l\'action');
        } finally {
            // Réactiver le bouton
            likeBtn.disabled = false;
        }
    }

    // Fermer les menus quand on clique ailleurs
    document.addEventListener('click', function(event) {
        if (!event.target.closest('[onclick*="toggleCommentMenu"]') && !event.target.closest('[id^="comment-menu-"]')) {
            const allMenus = document.querySelectorAll('[id^="comment-menu-"]');
            allMenus.forEach(menu => menu.classList.add('hidden'));
        }
    });

    // Fermer le modal avec la touche Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const modal = document.getElementById('likes-modal');
            if (!modal.classList.contains('hidden')) {
                closeLikesModal();
            }
        }
    });
    </script>
</x-app-layout>
