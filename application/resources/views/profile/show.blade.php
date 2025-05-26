<x-app-layout>
    <div class="min-h-screen bg-gradient-to-br from-indigo-50 via-white to-purple-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
        <div class="max-w-6xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
            <!-- Header du profil -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-2xl mb-8">
                <div class="relative">
                    <!-- Bannière gradient -->
                    <div class="h-32 bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500"></div>

                    <!-- Contenu du profil -->
                    <div class="relative px-6 pb-6">
                        <!-- Photo de profil -->
                        <div class="flex items-end justify-between -mt-16">
                            <div class="flex items-end space-x-6">
                                @if($user->profile_image)
                                    <img class="h-32 w-32 rounded-full border-4 border-white dark:border-gray-800 shadow-lg object-cover"
                                         src="{{ Storage::url($user->profile_image) }}"
                                         alt="{{ $user->name }}">
                                @else
                                    <img class="h-32 w-32 rounded-full border-4 border-white dark:border-gray-800 shadow-lg"
                                         src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&size=128"
                                         alt="{{ $user->name }}">
                                @endif

                                <div class="pb-2">
                                    <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">{{ $user->name }}</h1>
                                    <p class="text-gray-500 dark:text-gray-400 text-lg">{{ '@' . Str::slug($user->name) }}</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                        Membre depuis {{ $user->created_at->format('M Y') }}
                                    </p>
                                </div>
                            </div>

                            <!-- Bouton Follow/Unfollow -->
                            @if(Auth::id() !== $user->id)
                                <div class="pb-2">
                                    @if($isFollowing)
                                        <button id="main-follow-btn" onclick="toggleMainFollow({{ $user->id }}, this)"
                                                class="px-6 py-3 bg-gray-200 dark:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-xl hover:bg-gray-300 dark:hover:bg-gray-500 transition-all duration-200 font-semibold shadow-lg">
                                            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            Suivi(e)
                                        </button>
                                    @else
                                        <button id="main-follow-btn" onclick="toggleMainFollow({{ $user->id }}, this)"
                                                class="px-6 py-3 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition-all duration-200 font-semibold shadow-lg">
                                            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                            </svg>
                                            Suivre
                                        </button>
                                    @endif
                                </div>
                            @endif
                        </div>

                        <!-- Statistiques -->
                        <div class="flex justify-center space-x-12 mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                            <div class="text-center cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg p-3 transition-colors">
                                <div class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $posts->total() }}</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400 font-medium">Publication{{ $posts->total() > 1 ? 's' : '' }}</div>
                            </div>

                            <div class="text-center cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg p-3 transition-colors"
                                 onclick="showUserFollowersModal({{ $user->id }})">
                                <div class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $followersCount }}</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400 font-medium">Abonné{{ $followersCount > 1 ? 's' : '' }}</div>
                            </div>

                            <div class="text-center cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg p-3 transition-colors"
                                 onclick="showUserFollowingModal({{ $user->id }})">
                                <div class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $followingCount }}</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400 font-medium">Abonnement{{ $followingCount > 1 ? 's' : '' }}</div>
                            </div>

                            <div class="text-center cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg p-3 transition-colors">
                                <div class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $likesReceived }}</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400 font-medium">Like{{ $likesReceived > 1 ? 's' : '' }} reçu{{ $likesReceived > 1 ? 's' : '' }}</div>
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
                            Publications de {{ $user->name }}
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
                                            @if($post->user->profile_image)
                                                <img class="h-10 w-10 rounded-full object-cover"
                                                     src="{{ Storage::url($post->user->profile_image) }}"
                                                     alt="{{ $post->user->name }}">
                                            @else
                                                <img class="h-10 w-10 rounded-full"
                                                     src="https://ui-avatars.com/api/?name={{ urlencode($post->user->name) }}&size=40"
                                                     alt="{{ $post->user->name }}">
                                            @endif

                                            <div>
                                                <h4 class="font-semibold text-gray-900 dark:text-gray-100">{{ $post->user->name }}</h4>
                                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $post->created_at->diffForHumans() }}</p>
                                            </div>
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
                                        <div class="mb-4">
                                            <img src="{{ Storage::url($post->image_path) }}"
                                                 alt="Post image"
                                                 class="w-full rounded-lg max-h-96 object-cover">
                                        </div>
                                    @endif

                                    @if($post->video_path)
                                        <div class="mb-4">
                                            <video controls class="w-full rounded-lg max-h-96">
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
                                                          d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                                @endif
                                            </svg>
                                            <span id="like-count-{{ $post->id }}"
                                                  onclick="showLikesModal({{ $post->id }})"
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
                                    <div id="comments-{{ $post->id }}" class="hidden mt-4 space-y-4">
                                        <form onsubmit="addComment(event, {{ $post->id }})" class="flex space-x-2">
                                            <input type="text" name="content" id="comment-input-{{ $post->id }}"
                                                class="flex-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-600 dark:border-gray-500 dark:text-gray-100"
                                                placeholder="Ajouter un commentaire..." required>
                                            <button type="submit" id="comment-btn-{{ $post->id }}"
                                                class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50">
                                                Commenter
                                            </button>
                                        </form>

                                        <div id="comments-list-{{ $post->id }}">
                                        @foreach($post->comments as $comment)
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
                                                    <div class="bg-gray-100 dark:bg-gray-600 rounded-lg p-3">
                                                        <div class="flex items-center justify-between">
                                                            <a href="{{ route('profile.show', $comment->user) }}"
                                                               class="font-semibold hover:underline text-gray-900 dark:text-gray-100">{{ $comment->user->name }}</a>
                                                            <span class="text-gray-500 text-sm dark:text-gray-400">{{ $comment->created_at->diffForHumans() }}</span>
                                                        </div>
                                                        <p class="mt-1 text-gray-700 dark:text-gray-300">{{ $comment->content }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
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
                            <p class="text-gray-500 dark:text-gray-400">{{ $user->name }} n'a pas encore publié de contenu.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modal pour afficher les followers de l'utilisateur -->
    <div id="user-followers-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-md w-full max-h-[80vh] overflow-hidden">
            <div class="flex items-center justify-between p-6 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Abonnés de {{ $user->name }}</h3>
                <button onclick="closeUserFollowersModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="p-6">
                <div id="user-followers-loading" class="flex items-center justify-center py-8">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600"></div>
                    <span class="ml-3 text-gray-600 dark:text-gray-400">Chargement...</span>
                </div>
                <div id="user-followers-list" class="hidden space-y-3 max-h-96 overflow-y-auto"></div>
                <div id="user-followers-empty" class="hidden text-center py-8">
                    <p class="text-gray-500 dark:text-gray-400">Aucun abonné pour le moment</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal pour afficher les abonnements de l'utilisateur -->
    <div id="user-following-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-md w-full max-h-[80vh] overflow-hidden">
            <div class="flex items-center justify-between p-6 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Abonnements de {{ $user->name }}</h3>
                <button onclick="closeUserFollowingModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="p-6">
                <div id="user-following-loading" class="flex items-center justify-center py-8">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600"></div>
                    <span class="ml-3 text-gray-600 dark:text-gray-400">Chargement...</span>
                </div>
                <div id="user-following-list" class="hidden space-y-3 max-h-96 overflow-y-auto"></div>
                <div id="user-following-empty" class="hidden text-center py-8">
                    <p class="text-gray-500 dark:text-gray-400">Aucun abonnement pour le moment</p>
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
    // Réutiliser les fonctions du dashboard pour les likes et commentaires
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
                    likeBtn.className = 'flex items-center space-x-2 transition-colors duration-200 text-indigo-600';
                    likeIcon.setAttribute('fill', 'currentColor');
                    likeIcon.innerHTML = '<path d="M2 10.5a1.5 1.5 0 113 0v6a1.5 1.5 0 01-3 0v-6zM6 10.333v5.43a2 2 0 001.106 1.79l.05.025A4 4 0 008.943 18h5.416a2 2 0 001.962-1.608l1.2-6A2 2 0 0015.56 8H12V4a2 2 0 00-2-2 1 1 0 00-1 1v.667a4 4 0 01-.8 2.4L6.8 7.933a4 4 0 00-.8 2.4z"></path>';
                } else {
                    likeBtn.className = 'flex items-center space-x-2 transition-colors duration-200 text-gray-500 hover:text-indigo-600 dark:text-gray-400 dark:hover:text-indigo-400';
                    likeIcon.setAttribute('fill', 'none');
                    likeIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>';
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
            const response = await fetch(`/posts/${postId}/comments`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ content: content })
            });

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

    // Fonction spéciale pour le bouton principal Follow/Unfollow
    async function toggleMainFollow(userId, buttonElement) {
        const isCurrentlyFollowing = buttonElement.classList.contains('bg-gray-200');

        try {
            const method = isCurrentlyFollowing ? 'DELETE' : 'POST';
            const response = await fetch(`/users/${userId}/follow`, {
                method: method,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                }
            });

            if (response.ok) {
                if (isCurrentlyFollowing) {
                    // Changer vers "Suivre"
                    showNotification('Utilisateur non suivi', 'success');
                    buttonElement.innerHTML = `
                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Suivre
                    `;
                    buttonElement.className = 'px-6 py-3 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition-all duration-200 font-semibold shadow-lg';
                } else {
                    // Changer vers "Suivi(e)"
                    showNotification('Utilisateur suivi !', 'success');
                    buttonElement.innerHTML = `
                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Suivi(e)
                    `;
                    buttonElement.className = 'px-6 py-3 bg-gray-200 dark:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-xl hover:bg-gray-300 dark:hover:bg-gray-500 transition-all duration-200 font-semibold shadow-lg';
                }
            }
        } catch (error) {
            console.error('Erreur lors du toggle follow:', error);
            showNotification('Erreur lors de l\'action', 'error');
        }
    }

    // Fonctions pour les modals des followers/following de l'utilisateur
    async function showUserFollowersModal(userId) {
        const modal = document.getElementById('user-followers-modal');
        const loading = document.getElementById('user-followers-loading');
        const followersList = document.getElementById('user-followers-list');
        const followersEmpty = document.getElementById('user-followers-empty');

        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';

        loading.classList.remove('hidden');
        followersList.classList.add('hidden');
        followersEmpty.classList.add('hidden');
        followersList.innerHTML = '';

        try {
            const response = await fetch(`/users/${userId}/followers`, {
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
                        const followerElement = createUserFollowerElement(follower);
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

    async function showUserFollowingModal(userId) {
        const modal = document.getElementById('user-following-modal');
        const loading = document.getElementById('user-following-loading');
        const followingList = document.getElementById('user-following-list');
        const followingEmpty = document.getElementById('user-following-empty');

        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';

        loading.classList.remove('hidden');
        followingList.classList.add('hidden');
        followingEmpty.classList.add('hidden');
        followingList.innerHTML = '';

        try {
            const response = await fetch(`/users/${userId}/following`, {
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
                        const userElement = createUserFollowingElement(user);
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

    function createUserFollowerElement(follower) {
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
                    <p class="text-sm text-gray-500 dark:text-gray-400">Abonné(e)</p>
                </div>
            </div>
        `;

        return div;
    }

    function createUserFollowingElement(user) {
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
                    <p class="text-sm text-gray-500 dark:text-gray-400">Suivi(e)</p>
                </div>
            </div>
        `;

        return div;
    }

    function closeUserFollowersModal() {
        const modal = document.getElementById('user-followers-modal');
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    function closeUserFollowingModal() {
        const modal = document.getElementById('user-following-modal');
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
            const response = await fetch(`/posts/${postId}/likes`);
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
                <svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
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
    </script>

</x-app-layout>