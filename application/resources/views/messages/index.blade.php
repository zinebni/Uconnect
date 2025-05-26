<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-lg rounded-xl overflow-hidden ring-1 ring-gray-200 dark:ring-gray-700">
                <div class="p-6">
                    <!-- Search Bar -->
                    <div class="mb-6">
                        <div class="relative">
                            <input
                                type="text"
                                id="searchUsers"
                                placeholder="Rechercher une conversation..."
                                class="w-full pl-10 pr-4 py-2 rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100 border border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                            >
                            <div class="absolute left-3 top-2.5 text-gray-500 dark:text-gray-300">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Users List -->
                    <div class="space-y-4">
                        @foreach($users as $user)
                            <a href="{{ route('messages.show', $user['id']) }}"
                               class="flex items-center gap-4 p-4 bg-white dark:bg-gray-900 hover:bg-gray-50 dark:hover:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 transition">
                                <div class="relative">
                                    <img src="{{ $user['profile_image'] ? asset('storage/' . $user['profile_image']) : 'https://ui-avatars.com/api/?name=' . urlencode($user['name']) . '&background=random' }}"
                                         alt="{{ $user['name'] }}"
                                         class="w-12 h-12 rounded-full object-cover">
                                    @if($user['unread_count'] > 0)
                                        <span class="absolute -top-1 -right-1 bg-red-600 text-white text-xs font-semibold rounded-full w-5 h-5 flex items-center justify-center shadow">
                                            {{ $user['unread_count'] }}
                                        </span>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-gray-900 dark:text-gray-100 truncate">
                                        {{ $user['name'] }}
                                    </p>
                                    @if($user['last_message'])
                                        <p class="text-sm text-gray-600 dark:text-gray-400 truncate">
                                            {{ $user['last_message']->content }}
                                        </p>
                                    @endif
                                </div>
                                @if($user['last_message'])
                                    <div class="text-xs text-gray-500 dark:text-gray-400 whitespace-nowrap">
                                        {{ $user['last_message']->created_at->diffForHumans() }}
                                    </div>
                                @endif
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Search functionality
        document.getElementById('searchUsers').addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const users = document.querySelectorAll('.space-y-4 > a');

            users.forEach(user => {
                const userName = user.querySelector('p').textContent.toLowerCase();
                user.style.display = userName.includes(searchTerm) ? '' : 'none';
            });
        });
    </script>
    @endpush
</x-app-layout>
