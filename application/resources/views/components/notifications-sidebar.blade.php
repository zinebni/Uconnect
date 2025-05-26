@props(['notifications' => [], 'friendSuggestions' => [], 'friendsActivity' => []])

<div class="w-80 bg-white dark:bg-gray-800 shadow-lg rounded-xl p-6 h-fit sticky top-6">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Notifications</h3>
        @if(count($notifications) > 0)
            <button onclick="markAllAsRead()" class="text-sm text-indigo-600 hover:text-indigo-800 dark:text-indigo-400">
                Tout marquer comme lu
            </button>
        @endif
    </div>

    <!-- Notifications Section -->
    <div class="mb-8">
        <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3 flex items-center">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M9 11h.01"></path>
            </svg>
            Activité récente
        </h4>

        <div class="space-y-3 max-h-64 overflow-y-auto">
            @forelse($notifications as $notification)
                <div class="flex items-start space-x-3 p-3 rounded-lg {{ $notification->is_read ? 'bg-gray-50 dark:bg-gray-700' : 'bg-indigo-50 dark:bg-indigo-900/20' }} hover:bg-gray-100 dark:hover:bg-gray-600 transition cursor-pointer"
                     onclick="markAsRead({{ $notification->id }})">
                    <!-- Avatar -->
                    <div class="flex-shrink-0">
                        @if($notification->fromUser->profile_image)
                            <img class="h-8 w-8 rounded-full object-cover"
                                 src="{{ Storage::url($notification->fromUser->profile_image) }}"
                                 alt="{{ $notification->fromUser->name }}">
                        @else
                            <img class="h-8 w-8 rounded-full"
                                 src="https://ui-avatars.com/api/?name={{ urlencode($notification->fromUser->name) }}"
                                 alt="{{ $notification->fromUser->name }}">
                        @endif
                    </div>

                    <!-- Content -->
                    <div class="flex-1 min-w-0">
                        <p class="text-sm text-gray-900 dark:text-gray-100">
                            {{ $notification->message }}
                        </p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                            {{ $notification->created_at->diffForHumans() }}
                        </p>
                    </div>

                    <!-- Unread indicator -->
                    @if(!$notification->is_read)
                        <div class="w-2 h-2 bg-indigo-600 rounded-full"></div>
                    @endif
                </div>
            @empty
                <div class="text-center py-6 text-gray-500 dark:text-gray-400">
                    <svg class="w-12 h-12 mx-auto mb-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5z"></path>
                    </svg>
                    <p class="text-sm">Aucune notification</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Friend Suggestions Section -->
    <div class="mb-8">
        <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3 flex items-center">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
            </svg>
            Suggestions d'amis
        </h4>

        <div class="space-y-3">
            @forelse($friendSuggestions as $suggestion)
                <div class="flex items-center justify-between p-3 rounded-lg bg-gray-50 dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 transition">
                    <div class="flex items-center space-x-3">
                        <!-- Avatar -->
                        @if($suggestion->profile_image)
                            <img class="h-8 w-8 rounded-full object-cover"
                                 src="{{ Storage::url($suggestion->profile_image) }}"
                                 alt="{{ $suggestion->name }}">
                        @else
                            <img class="h-8 w-8 rounded-full"
                                 src="https://ui-avatars.com/api/?name={{ urlencode($suggestion->name) }}"
                                 alt="{{ $suggestion->name }}">
                        @endif

                        <!-- Name -->
                        <div>
                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                {{ $suggestion->name }}
                            </p>
                            @if(isset($suggestion->followers_count))
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $suggestion->followers_count }} amis communs
                                </p>
                            @endif
                        </div>
                    </div>

                    <!-- Follow button -->
                    <form action="{{ route('users.follow', $suggestion) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="px-3 py-1 text-xs bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition">
                            Suivre
                        </button>
                    </form>
                </div>
            @empty
                <div class="text-center py-4 text-gray-500 dark:text-gray-400">
                    <p class="text-sm">Aucune suggestion</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Friends Activity Section -->
    <div>
        <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3 flex items-center">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
            </svg>
            Activité des amis
        </h4>

        <div class="space-y-3">
            @forelse($friendsActivity as $activity)
                <div class="flex items-center space-x-3 p-3 rounded-lg bg-gray-50 dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 transition">
                    <!-- Avatar -->
                    @if($activity->profile_image)
                        <img class="h-8 w-8 rounded-full object-cover"
                             src="{{ Storage::url($activity->profile_image) }}"
                             alt="{{ $activity->name }}">
                    @else
                        <img class="h-8 w-8 rounded-full"
                             src="https://ui-avatars.com/api/?name={{ urlencode($activity->name) }}"
                             alt="{{ $activity->name }}">
                    @endif

                    <!-- Content -->
                    <div class="flex-1 min-w-0">
                        <p class="text-sm text-gray-900 dark:text-gray-100">
                            <span class="font-medium">{{ $activity->name }}</span>
                            a publié {{ $activity->posts_count }} nouveau{{ $activity->posts_count > 1 ? 'x' : '' }} post{{ $activity->posts_count > 1 ? 's' : '' }}
                        </p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            Dernières 24h
                        </p>
                    </div>
                </div>
            @empty
                <div class="text-center py-4 text-gray-500 dark:text-gray-400">
                    <p class="text-sm">Aucune activité récente</p>
                </div>
            @endforelse
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
            // Recharger la page pour mettre à jour l'affichage
            location.reload();
        }
    });
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
            // Recharger la page pour mettre à jour l'affichage
            location.reload();
        }
    });
}
</script>
