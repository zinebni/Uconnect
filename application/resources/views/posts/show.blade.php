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
                    <div class="flex items-center space-x-6 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <!-- Like Button -->
                        <form action="{{ $post->likes()->where('user_id', Auth::id())->exists() ? route('posts.unlike', $post) : route('posts.like', $post) }}"
                              method="POST" class="inline">
                            @csrf
                            @if($post->likes()->where('user_id', Auth::id())->exists())
                                @method('DELETE')
                            @endif
                            <button type="submit" class="flex items-center space-x-2 {{ $post->likes()->where('user_id', Auth::id())->exists() ? 'text-indigo-600' : 'text-gray-600 hover:text-indigo-600 dark:text-gray-400 dark:hover:text-indigo-400' }} transition-colors duration-200">
                                <svg class="w-5 h-5" fill="{{ $post->likes()->where('user_id', Auth::id())->exists() ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                                    @if($post->likes()->where('user_id', Auth::id())->exists())
                                        <path d="M2 10.5a1.5 1.5 0 113 0v6a1.5 1.5 0 01-3 0v-6zM6 10.333v5.43a2 2 0 001.106 1.79l.05.025A4 4 0 008.943 18h5.416a2 2 0 001.962-1.608l1.2-6A2 2 0 0015.56 8H12V4a2 2 0 00-2-2 1 1 0 00-1 1v.667a4 4 0 01-.8 2.4L6.8 7.933a4 4 0 00-.8 2.4z"></path>
                                    @else
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"></path>
                                    @endif
                                </svg>
                                <span onclick="showLikesModal({{ $post->id }})"
                                      class="cursor-pointer hover:underline text-blue-600 font-semibold">{{ $post->likes()->count() }}</span>
                            </button>
                        </form>

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
                                        <p class="mt-1 text-gray-700 dark:text-gray-300">{{ $comment->content }}</p>
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
            const response = await axios.get(`/posts/${postId}/likes`);

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
