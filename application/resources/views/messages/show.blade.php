<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('messages.index') }}" class="text-gray-600 dark:text-gray-300 hover:text-indigo-600 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                </a>
                <div class="flex items-center gap-3">
                    <img src="{{ $user->profile_image ? asset('storage/' . $user->profile_image) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=random' }}"
                         alt="{{ $user->name }}"
                         class="w-10 h-10 rounded-full object-cover ring-2 ring-indigo-400 dark:ring-indigo-600">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                            {{ $user->name }}
                        </h2>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            @if($user->followers()->where('follower_id', auth()->id())->exists())
                                Vous suivez cet utilisateur
                            @elseif($user->following()->where('following_id', auth()->id())->exists())
                                Cet utilisateur vous suit
                            @else
                                Relation mutuelle
                            @endif
                        </p>
                    </div>
                </div>
            </div>
            <a href="{{ route('profile.show', $user) }}"
               class="text-indigo-600 dark:text-indigo-400 hover:underline text-sm font-medium">
                Voir le profil
            </a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-md sm:rounded-xl overflow-hidden">
                <div class="p-6">
                    <!-- Messages Area -->
                    <div id="messagesArea" class="space-y-4 mb-6 max-h-[60vh] overflow-y-auto pr-2">
                        @forelse($messages as $message)
                            <div class="flex {{ $message->sender_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                                <div class="flex items-end gap-2 max-w-[75%]">
                                    @if($message->sender_id !== auth()->id())
                                        <img src="{{ $message->sender->profile_image ? asset('storage/' . $message->sender->profile_image) : 'https://ui-avatars.com/api/?name=' . urlencode($message->sender->name) . '&background=random' }}"
                                             alt="{{ $message->sender->name }}"
                                             class="w-8 h-8 rounded-full object-cover">
                                    @endif
                                    <div class="flex flex-col {{ $message->sender_id === auth()->id() ? 'items-end' : 'items-start' }}">
                                        <div class="{{ $message->sender_id === auth()->id() ? 'bg-indigo-600 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-100' }} px-4 py-2 rounded-xl shadow-sm">
                                            <p class="text-sm break-words">{{ $message->content }}</p>
                                        </div>
                                        <span class="text-xs text-gray-500 mt-1">
                                            {{ $message->created_at->format('H:i') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-10 text-gray-500 dark:text-gray-400">
                                Aucun message. Commencez la conversation !
                            </div>
                        @endforelse
                    </div>

                    <!-- Message Input -->
                    <form action="{{ route('messages.store', $user) }}" method="POST" class="mt-4">
                        @csrf
                        <div class="flex items-center gap-2">
                            <input type="text"
                                   name="content"
                                   placeholder="Écrivez votre message..."
                                   class="flex-1 rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-100 px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                   required>
                            <button type="submit"
                                    class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                </svg>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        const messagesArea = document.getElementById('messagesArea');
        messagesArea.scrollTop = messagesArea.scrollHeight;

        // Auto-refresh messages every 5s
        setInterval(() => {
            fetch(window.location.href)
                .then(response => response.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const newMessages = doc.querySelector('#messagesArea');
                    if (newMessages) {
                        messagesArea.innerHTML = newMessages.innerHTML;
                        messagesArea.scrollTop = messagesArea.scrollHeight;
                    }
                });
        }, 5000);
    </script>
    @endpush
</x-app-layout>
