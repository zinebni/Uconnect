<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
      x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }"
      x-init="$watch('darkMode', value => localStorage.setItem('darkMode', value))"
      :class="{ 'dark': darkMode }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

</head>
<body class="font-sans antialiased bg-gradient-to-b from-gray-100 to-white dark:from-gray-950 dark:to-gray-900 text-gray-900 dark:text-gray-100 transition-colors duration-300 ease-in-out">

    <div class="min-h-screen flex flex-col">
        <!-- Navigation -->
        @include('layouts.navigation')

        <!-- Page Header -->
        @if (isset($header))
            <header class="bg-white/80 dark:bg-gray-800/80 shadow-sm backdrop-blur-md border-b border-gray-200 dark:border-gray-700">
                <div class="max-w-screen-xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endif

        <!-- Page Content -->
        <main class="flex-1 w-full px-4 sm:px-6 lg:px-8 py-8">
            {{ $slot }}
        </main>
    </div>

    @stack('scripts')
</body>
</html>
