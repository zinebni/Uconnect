<div class="flex items-center justify-between h-16 px-6 w-full
    bg-gradient-to-r from-blue-50 to-white/70
    dark:bg-gradient-to-r dark:from-gray-900 dark:via-slate-800 dark:to-blue-900/70
    backdrop-blur-md
    border-b border-gray-200 dark:border-gray-800
    shadow-lg sticky top-0 z-50 p-2 rounded-sm">

    <!-- Logo -->
    <div class="flex-shrink-0 m-2">
        <a href="{{ route('home') }}">
            <x-application-logo class="block h-8 w-auto fill-current text-blue-600" />
        </a>
    </div>


    <!-- Profil -->
    <x-dropdown align="left" width="48">
        <x-slot name="trigger">
            <button class="flex items-center space-x-2 text-sm focus:outline-none p-4 m-4">
                <img class="h-8 w-8 rounded-full object-cover"
                    src="{{ Auth::user()->profile_image ? Storage::url(Auth::user()->profile_image) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) }}"
                    alt="{{ Auth::user()->name }}">
                <span  class="text-sm font-semibold text-gray-800 dark:text-gray-100">{{ Auth::user()->name }}</span>
                <span class="ml-2 px-2 py-0.5 rounded-full bg-blue-100 text-green-600 text-xs dark:bg-gray-700 dark:text-blue-300">
                        En ligne
                </span>

            </button>
        </x-slot>

        <x-slot name="content">
            <x-dropdown-link :href="route('profile.edit')">
                {{ __('Profile') }}
            </x-dropdown-link>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <x-dropdown-link :href="route('logout')"
                    onclick="event.preventDefault(); this.closest('form').submit();">
                    {{ __('Log Out') }}
                </x-dropdown-link>
            </form>
        </x-slot>
    </x-dropdown>
     <!-- Navigation / Profile -->
     <div class="flex items-center space-x-6 p-2 m-2">
        <!-- Tes liens ou icônes ici -->
        <x-nav-link :href="route('home')" :active="request()->routeIs('home')">
            {{ __('Accueil') }}
        </x-nav-link>
        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
            {{ __('Dashboard') }}
        </x-nav-link>
        <x-nav-link :href="route('messages.index')" :active="request()->routeIs('messages.*')">
            <div class="relative">
                {{ __('Messages') }}
                @if(auth()->user()->unreadMessages()->count() > 0)
                    <span class="absolute -top-2 -right-3 inline-flex items-center justify-center px-2 py-1 text-xs font-bold text-white bg-red-500 rounded-full">
                        {{ auth()->user()->unreadMessages()->count() }}
                    </span>
                @endif
            </div>
        </x-nav-link>

        <x-nav-link :href="route('notifications.index')" :active="request()->routeIs('notifications.*')">
            <div class="relative">
                {{ __('Notifications') }}
                @if(auth()->user()->unreadNotifications()->count() > 0)
                    <span class="absolute -top-2 -right-3 inline-flex items-center justify-center px-2 py-1 text-xs font-bold text-white bg-red-500 rounded-full">
                        {{ auth()->user()->unreadNotifications()->count() }}
                    </span>
                @endif
            </div>
        </x-nav-link>



    </div>



    <!-- Search Bar -->
    <div class="hidden sm:flex sm:items-center flex-grow max-w-4xl mx-8">
        <form action="{{ route('search') }}" method="GET" class="w-full">
            <div class="relative flex items-center">
                <div class="absolute left-7 top-1/2 -translate-y-1/2">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <input type="text"
                    name="q"
                    placeholder="Rechercher vos amies"
                    class="w-full pl-12 pr-4 py-2 rounded-full
                            bg-white dark:bg-gray-800
                            border border-gray-300 dark:border-gray-600
                            text-gray-900 dark:text-gray-100
                            placeholder-gray-400 dark:placeholder-gray-500
                            focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                            transition-colors duration-200 ease-in-out">

            </div>
        </form>
    </div>

    <!-- Dark Mode Toggle -->
    <div>
        <button @click="darkMode = !darkMode" class="focus:outline-none">
            <template x-if="darkMode">
                <svg class="h-6 w-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
            </template>
            <template x-if="!darkMode">
                <svg class="h-6 w-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                </svg>
            </template>
        </button>
    </div>



</div>
