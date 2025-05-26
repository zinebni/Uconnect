<x-app-layout>
    <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-900 overflow-hidden shadow-sm sm:rounded-lg ">
            <div class="p-6 ">
                <h1 class="text-2xl font-bold mb-6">Résultats de recherche pour "{{ $query }}"</h1>

                @if($users->isEmpty())
                    <p class="text-gray-500">Aucun utilisateur trouvé.</p>
                @else
                    <div class="space-y-4">
                        @foreach($users as $user)
                            <div class="flex items-center justify-between p-4 border rounded-lg">
                                <div class="flex items-center space-x-4">
                                    <img class="h-10 w-10 rounded-full" src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}" alt="{{ $user->name }}">
                                    <div>
                                        <a href="{{ route('profile.show', $user) }}" class="font-semibold hover:underline">{{ $user->name }}</a>
                                        <p class="text-gray-500 text-sm">{{ $user->email }}</p>
                                    </div>
                                </div>
                                @if(Auth::id() !== $user->id)
                                    <form action="{{ route('users.follow', $user) }}" method="POST">
                                        @csrf
                                        @if($user->followers->contains(Auth::id()))
                                            @method('DELETE')
                                            <button type="submit" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300">
                                                Ne plus suivre
                                            </button>
                                        @else
                                            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                                                Suivre
                                            </button>
                                        @endif
                                    </form>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-4">
                        {{ $users->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout> 