<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Notifications') }} ({{ $notifications->total() }})
            </h2>
            <div class="flex space-x-2">
                @if($notifications->where('is_read', false)->count() > 0)
                    <button onclick="markAllAsRead()" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md text-sm transition">
                        Tout marquer comme lu
                    </button>
                @endif
                @if($notifications->count() > 0)
                    <button onclick="deleteAllNotifications()" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md text-sm transition">
                        Tout supprimer
                    </button>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    @if($notifications->count() > 0)
                        <div class="space-y-4">
                            @foreach($notifications as $notification)
                                <div class="flex items-start space-x-4 p-4 rounded-lg border {{ $notification->is_read ? 'bg-gray-50 dark:bg-gray-700 border-gray-200 dark:border-gray-600' : 'bg-indigo-50 dark:bg-indigo-900/20 border-indigo-200 dark:border-indigo-700' }} hover:bg-gray-100 dark:hover:bg-gray-600 transition"
                                     id="notification-{{ $notification->id }}">

                                    <!-- Avatar -->
                                    <div class="flex-shrink-0">
                                        @if($notification->fromUser->profile_image)
                                            <img class="h-12 w-12 rounded-full object-cover"
                                                 src="{{ Storage::url($notification->fromUser->profile_image) }}"
                                                 alt="{{ $notification->fromUser->name }}">
                                        @else
                                            <img class="h-12 w-12 rounded-full"
                                                 src="https://ui-avatars.com/api/?name={{ urlencode($notification->fromUser->name) }}&size=48"
                                                 alt="{{ $notification->fromUser->name }}">
                                        @endif
                                    </div>

                                    <!-- Content -->
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between">
                                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                {{ $notification->message }}
                                            </p>
                                            @if(!$notification->is_read)
                                                <div class="w-3 h-3 bg-indigo-600 rounded-full"></div>
                                            @endif
                                        </div>

                                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                            {{ $notification->created_at->diffForHumans() }}
                                        </p>

                                        <!-- Type-specific content -->
                                        @if($notification->type === 'like' && isset($notification->data['post_content']))
                                            <p class="text-xs text-gray-600 dark:text-gray-400 mt-2 italic">
                                                "{{ $notification->data['post_content'] }}"
                                            </p>
                                        @elseif($notification->type === 'comment' && isset($notification->data['comment_content']))
                                            <p class="text-xs text-gray-600 dark:text-gray-400 mt-2 italic">
                                                Commentaire: "{{ $notification->data['comment_content'] }}"
                                            </p>
                                        @elseif($notification->type === 'new_post' && isset($notification->data['post_content']))
                                            <p class="text-xs text-gray-600 dark:text-gray-400 mt-2 italic">
                                                "{{ $notification->data['post_content'] }}"
                                            </p>
                                        @endif

                                        <!-- Action buttons -->
                                        <div class="mt-3 flex space-x-2">
                                            @if($notification->type === 'follow')
                                                <a href="{{ route('profile.show', $notification->fromUser) }}"
                                                   class="text-xs bg-indigo-100 dark:bg-indigo-800 text-indigo-700 dark:text-indigo-200 px-2 py-1 rounded hover:bg-indigo-200 dark:hover:bg-indigo-700 transition">
                                                    Voir le profil
                                                </a>
                                            @elseif(in_array($notification->type, ['like', 'comment', 'new_post']) && isset($notification->data['post_id']))
                                                <a href="{{ route('posts.show', $notification->data['post_id']) }}"
                                                   class="text-xs bg-indigo-100 dark:bg-indigo-800 text-indigo-700 dark:text-indigo-200 px-2 py-1 rounded hover:bg-indigo-200 dark:hover:bg-indigo-700 transition">
                                                    Voir le post 
                                                </a>
                                            @else
                                                <span class="text-xs text-gray-500">
                                                    Debug: Type={{ $notification->type }}, Data={{ json_encode($notification->data) }}
                                                </span>
                                            @endif

                                            @if(!$notification->is_read)
                                                <button onclick="markAsRead({{ $notification->id }})"
                                                        class="text-xs bg-green-100 dark:bg-green-800 text-green-700 dark:text-green-200 px-2 py-1 rounded hover:bg-green-200 dark:hover:bg-green-700 transition">
                                                    Marquer comme lu
                                                </button>
                                            @endif

                                            <button onclick="deleteNotification({{ $notification->id }})"
                                                    class="text-xs bg-red-100 dark:bg-red-800 text-red-700 dark:text-red-200 px-2 py-1 rounded hover:bg-red-200 dark:hover:bg-red-700 transition">
                                                Supprimer
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        <div class="mt-6">
                            {{ $notifications->links() }}
                        </div>

                    @else
                        <div class="text-center py-12">
                            <svg class="w-16 h-16 mx-auto text-gray-400 dark:text-gray-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M9 11h.01"></path>
                            </svg>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">Aucune notification</h3>
                            <p class="text-gray-500 dark:text-gray-400">Vous n'avez pas encore de notifications.</p>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>

    <script>
    function markAsRead(notificationId) {
        fetch(`/notifications/${notificationId}/mark-as-read`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Mettre à jour visuellement la notification
                const notificationElement = document.getElementById(`notification-${notificationId}`);
                if (notificationElement) {
                    notificationElement.classList.remove('bg-indigo-50', 'dark:bg-indigo-900/20', 'border-indigo-200', 'dark:border-indigo-700');
                    notificationElement.classList.add('bg-gray-50', 'dark:bg-gray-700', 'border-gray-200', 'dark:border-gray-600');

                    // Supprimer le point d'indication non lu
                    const unreadIndicator = notificationElement.querySelector('.w-3.h-3.bg-indigo-600');
                    if (unreadIndicator) {
                        unreadIndicator.remove();
                    }

                    // Supprimer le bouton "Marquer comme lu"
                    const markAsReadBtn = notificationElement.querySelector('button[onclick*="markAsRead"]');
                    if (markAsReadBtn) {
                        markAsReadBtn.remove();
                    }
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }

    function deleteNotification(notificationId) {
        if (confirm('Êtes-vous sûr de vouloir supprimer cette notification ?')) {
            fetch(`/notifications/${notificationId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Supprimer visuellement la notification avec animation
                    const notificationElement = document.getElementById(`notification-${notificationId}`);
                    if (notificationElement) {
                        notificationElement.style.transition = 'opacity 0.3s ease-out, transform 0.3s ease-out';
                        notificationElement.style.opacity = '0';
                        notificationElement.style.transform = 'translateX(-100%)';

                        setTimeout(() => {
                            notificationElement.remove();

                            // Vérifier s'il reste des notifications
                            const remainingNotifications = document.querySelectorAll('[id^="notification-"]');
                            if (remainingNotifications.length === 0) {
                                location.reload(); // Recharger pour afficher le message "Aucune notification"
                            }
                        }, 300);
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }
    }

    function markAllAsRead() {
        fetch('/notifications/mark-all-as-read', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }

    function deleteAllNotifications() {
        if (confirm('Êtes-vous sûr de vouloir supprimer TOUTES les notifications ? Cette action est irréversible.')) {
            fetch('/notifications', {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }
    }
    </script>
</x-app-layout>
