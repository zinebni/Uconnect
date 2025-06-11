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

    <!-- Custom CSS for responsive media -->
    <style>
        /* Responsive media container */
        .post-media-container {
            position: relative;
            width: 100%;
            max-width: 100%;
            margin: 0 auto;
        }

        /* Responsive image */
        .post-image {
            width: 100%;
            height: auto;
            max-height: 70vh;
            object-fit: contain;
            border-radius: 0.5rem;
            background-color: #f3f4f6;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
            transition: transform 0.2s ease-in-out;
        }

        /* Responsive video */
        .post-video {
            width: 100%;
            height: auto;
            max-height: 70vh;
            border-radius: 0.5rem;
            background-color: #000;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
        }

        /* Hover effect for images */
        .post-image:hover {
            transform: scale(1.02);
        }

        /* Mobile responsive */
        @media (max-width: 640px) {
            .post-image, .post-video {
                max-height: 50vh;
                border-radius: 0.375rem;
            }
        }

        /* Tablet responsive */
        @media (min-width: 641px) and (max-width: 1024px) {
            .post-image, .post-video {
                max-height: 60vh;
            }
        }

        /* Desktop responsive */
        @media (min-width: 1025px) {
            .post-image, .post-video {
                max-height: 70vh;
            }
        }

        /* Dark mode support */
        .dark .post-image {
            background-color: #374151;
        }

        .dark .post-media-container {
            background-color: #1f2937;
        }

        /* Image zoom modal */
        .image-zoom-modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.9);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s ease, visibility 0.3s ease;
        }

        .image-zoom-modal.active {
            opacity: 1;
            visibility: visible;
        }

        .image-zoom-modal img {
            max-width: 95%;
            max-height: 95%;
            object-fit: contain;
            border-radius: 0.5rem;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.5);
        }

        .image-zoom-modal .close-btn {
            position: absolute;
            top: 20px;
            right: 20px;
            background: rgba(255, 255, 255, 0.2);
            border: none;
            color: white;
            font-size: 24px;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background-color 0.2s ease;
        }

        .image-zoom-modal .close-btn:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        /* Clickable image cursor */
        .post-image {
            cursor: pointer;
        }

        /* Video responsive improvements */
        .post-video {
            outline: none;
        }

        .post-video:focus {
            outline: 2px solid #6366f1;
            outline-offset: 2px;
        }
    </style>

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

    <!-- Image Zoom Modal -->
    <div id="imageZoomModal" class="image-zoom-modal">
        <button class="close-btn" onclick="closeImageZoom()">&times;</button>
        <img id="zoomedImage" src="" alt="Zoomed image">
    </div>

    <!-- Image Zoom JavaScript -->
    <script>
        // Function to open image zoom
        function openImageZoom(imageSrc, imageAlt) {
            const modal = document.getElementById('imageZoomModal');
            const zoomedImage = document.getElementById('zoomedImage');

            zoomedImage.src = imageSrc;
            zoomedImage.alt = imageAlt || 'Zoomed image';
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        // Function to close image zoom
        function closeImageZoom() {
            const modal = document.getElementById('imageZoomModal');
            modal.classList.remove('active');
            document.body.style.overflow = 'auto';
        }

        // Close modal when clicking outside the image
        document.getElementById('imageZoomModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeImageZoom();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const modal = document.getElementById('imageZoomModal');
                if (modal.classList.contains('active')) {
                    closeImageZoom();
                }
            }
        });

        // Add click event to all post images when DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            // Add click event to existing images
            addImageZoomEvents();

            // Observer for dynamically added images (AJAX content)
            const observer = new MutationObserver(function(mutations) {
                mutations.forEach(function(mutation) {
                    if (mutation.addedNodes.length) {
                        addImageZoomEvents();
                    }
                });
            });

            observer.observe(document.body, {
                childList: true,
                subtree: true
            });
        });

        function addImageZoomEvents() {
            const postImages = document.querySelectorAll('.post-image');
            postImages.forEach(function(img) {
                if (!img.hasAttribute('data-zoom-enabled')) {
                    img.setAttribute('data-zoom-enabled', 'true');
                    img.addEventListener('click', function() {
                        openImageZoom(this.src, this.alt);
                    });
                }
            });
        }
    </script>

    @stack('scripts')
</body>
</html>
