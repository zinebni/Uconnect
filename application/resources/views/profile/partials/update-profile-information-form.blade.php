<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-white">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name" :value="__('Name')" class="dark:text-gray-300" />
            <x-text-input
                id="name"
                name="name"
                type="text"
                class="mt-1 block w-full bg-white dark:bg-gray-700 dark:text-white dark:border-gray-600"
                :value="old('name', $user->name)"
                required
                autofocus
                autocomplete="name"
            />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" class="dark:text-gray-300" />
            <x-text-input
                id="email"
                name="email"
                type="email"
                class="mt-1 block w-full bg-white dark:bg-gray-700 dark:text-white dark:border-gray-600"
                :value="old('email', $user->email)"
                required
                autocomplete="username"
            />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800 dark:text-gray-300">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600 dark:text-green-400">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div>
            <x-input-label for="academic_status" :value="__('Statut scolaire')" class="dark:text-gray-300" />
            <x-text-input
                id="academic_status"
                name="academic_status"
                type="text"
                class="mt-1 block w-full bg-white dark:bg-gray-700 dark:text-white dark:border-gray-600"
                :value="old('academic_status', $user->academic_status)"
                placeholder="Ex: 3e année Business School, Master en Informatique, Licence en Droit..."
                autocomplete="academic_status"
            />
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Cette information sera visible sur votre profil public
            </p>
            <x-input-error class="mt-2" :messages="$errors->get('academic_status')" />
        </div>

        <div>
            <x-input-label for="profile_image" :value="__('Photo de profil')" class="dark:text-gray-300" />

            <!-- Aperçu de l'image actuelle et nouvelle -->
            <div class="mt-2 flex flex-col space-y-4">
                <!-- Image actuelle -->
                <div class="flex items-center space-x-4">
                    <div class="flex-shrink-0">
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Image actuelle :</span>
                        <div class="mt-1">
                            @if($user->profile_image)
                                <img id="current-image" src="{{ Storage::url($user->profile_image) }}" alt="Photo de profil actuelle"
                                     class="h-20 w-20 rounded-full object-cover border-2 border-gray-200 dark:border-gray-600">
                            @else
                                <img id="current-image" src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}" alt="Photo de profil actuelle"
                                     class="h-20 w-20 rounded-full border-2 border-gray-200 dark:border-gray-600">
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Aperçu de la nouvelle image -->
                <div id="new-image-preview" class="hidden">
                    <div class="flex items-center space-x-4">
                        <div class="flex-shrink-0">
                            <span class="text-sm font-medium text-green-700 dark:text-green-400">Nouvelle image :</span>
                            <div class="mt-1">
                                <img id="preview-image" src="" alt="Aperçu nouvelle photo"
                                     class="h-20 w-20 rounded-full object-cover border-2 border-green-500 shadow-lg">
                            </div>
                        </div>
                        <div class="flex flex-col space-y-2">
                            <button type="button" onclick="removeImagePreview()"
                                    class="inline-flex items-center px-3 py-1 border border-red-300 text-sm leading-4 font-medium rounded-md text-red-700 bg-red-50 hover:bg-red-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 dark:bg-red-900 dark:text-red-300 dark:border-red-600 dark:hover:bg-red-800">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                Supprimer
                            </button>
                            <span class="text-xs text-gray-500 dark:text-gray-400">Cette image remplacera l'actuelle</span>
                        </div>
                    </div>
                </div>

                <!-- Input file avec drag & drop -->
                <div class="relative border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-4 transition-colors duration-200 hover:border-indigo-400 dark:hover:border-indigo-500">
                    <input
                        type="file"
                        name="profile_image"
                        id="profile_image"
                        class="block w-full text-sm text-gray-500
                               file:mr-4 file:py-2 file:px-4
                               file:rounded-full file:border-0
                               file:text-sm file:font-semibold
                               file:bg-indigo-50 file:text-indigo-700
                               hover:file:bg-indigo-100
                               cursor-pointer
                               dark:text-gray-400 dark:file:bg-gray-600 dark:file:text-indigo-300 dark:hover:file:bg-gray-700"
                        accept="image/*"
                        onchange="previewProfileImage(this)"
                    >
                    <div class="text-center mt-2">
                        <svg class="mx-auto h-8 w-8 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                            <span class="font-medium text-indigo-600 dark:text-indigo-400">Cliquez pour choisir</span> ou glissez-déposez une image
                        </p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            PNG, JPG, GIF jusqu'à 2MB
                        </p>
                    </div>
                </div>
            </div>
            <x-input-error class="mt-2" :messages="$errors->get('profile_image')" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 3000)"
                    class="text-sm text-green-600 dark:text-green-400 font-medium"
                >{{ __('Profil sauvegardé avec succès !') }}</p>
            @endif

            @if (session('status') === 'profile-updated-with-image')
                <div
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 5000)"
                    class="flex items-center gap-3 bg-green-50 dark:bg-green-900 border border-green-200 dark:border-green-700 rounded-lg p-3"
                >
                    @if (session('new_image_url'))
                        <img src="{{ session('new_image_url') }}" alt="Nouvelle photo" class="h-8 w-8 rounded-full object-cover border border-green-300">
                    @endif
                    <p class="text-sm text-green-700 dark:text-green-300 font-medium">
                        {{ __('Profil et photo mis à jour avec succès !') }}
                    </p>
                    <svg class="h-5 w-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
            @endif
        </div>
    </form>

    <!-- JavaScript pour l'aperçu d'image -->
    <script>
        function previewProfileImage(input) {
            const previewContainer = document.getElementById('new-image-preview');
            const previewImage = document.getElementById('preview-image');

            if (input.files && input.files[0]) {
                const file = input.files[0];

                // Vérifier la taille du fichier (2MB max)
                if (file.size > 2 * 1024 * 1024) {
                    alert('Le fichier est trop volumineux. Taille maximale : 2MB');
                    input.value = '';
                    previewContainer.classList.add('hidden');
                    return;
                }

                // Vérifier le type de fichier
                if (!file.type.match('image.*')) {
                    alert('Veuillez sélectionner un fichier image valide.');
                    input.value = '';
                    previewContainer.classList.add('hidden');
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImage.src = e.target.result;
                    previewContainer.classList.remove('hidden');

                    // Afficher les informations du fichier
                    const fileInfo = document.createElement('div');
                    fileInfo.className = 'text-xs text-gray-500 dark:text-gray-400 mt-1';
                    fileInfo.innerHTML = `
                        <span class="font-medium">Fichier :</span> ${file.name}<br>
                        <span class="font-medium">Taille :</span> ${(file.size / 1024 / 1024).toFixed(2)} MB<br>
                        <span class="font-medium">Type :</span> ${file.type}
                    `;

                    // Supprimer l'ancien info s'il existe
                    const existingInfo = previewContainer.querySelector('.file-info');
                    if (existingInfo) {
                        existingInfo.remove();
                    }

                    fileInfo.classList.add('file-info');
                    previewContainer.appendChild(fileInfo);

                    // Animation d'apparition
                    previewContainer.style.opacity = '0';
                    previewContainer.style.transform = 'translateY(10px)';
                    setTimeout(() => {
                        previewContainer.style.transition = 'all 0.3s ease-out';
                        previewContainer.style.opacity = '1';
                        previewContainer.style.transform = 'translateY(0)';
                    }, 10);
                };
                reader.readAsDataURL(file);
            } else {
                previewContainer.classList.add('hidden');
            }
        }

        function removeImagePreview() {
            const input = document.getElementById('profile_image');
            const previewContainer = document.getElementById('new-image-preview');

            // Animation de disparition
            previewContainer.style.transition = 'all 0.3s ease-out';
            previewContainer.style.opacity = '0';
            previewContainer.style.transform = 'translateY(-10px)';

            setTimeout(() => {
                previewContainer.classList.add('hidden');
                input.value = '';
                previewContainer.style.transition = '';
                previewContainer.style.opacity = '';
                previewContainer.style.transform = '';
            }, 300);
        }

        // Drag and drop functionality
        document.addEventListener('DOMContentLoaded', function() {
            const fileInput = document.getElementById('profile_image');
            const dropZone = fileInput.parentElement;

            // Prevent default drag behaviors
            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                dropZone.addEventListener(eventName, preventDefaults, false);
                document.body.addEventListener(eventName, preventDefaults, false);
            });

            // Highlight drop zone when item is dragged over it
            ['dragenter', 'dragover'].forEach(eventName => {
                dropZone.addEventListener(eventName, highlight, false);
            });

            ['dragleave', 'drop'].forEach(eventName => {
                dropZone.addEventListener(eventName, unhighlight, false);
            });

            // Handle dropped files
            dropZone.addEventListener('drop', handleDrop, false);

            function preventDefaults(e) {
                e.preventDefault();
                e.stopPropagation();
            }

            function highlight(e) {
                dropZone.classList.add('border-indigo-500', 'bg-indigo-50', 'dark:bg-indigo-900');
            }

            function unhighlight(e) {
                dropZone.classList.remove('border-indigo-500', 'bg-indigo-50', 'dark:bg-indigo-900');
            }

            function handleDrop(e) {
                const dt = e.dataTransfer;
                const files = dt.files;

                if (files.length > 0) {
                    fileInput.files = files;
                    previewProfileImage(fileInput);
                }
            }
        });
    </script>
</section>
