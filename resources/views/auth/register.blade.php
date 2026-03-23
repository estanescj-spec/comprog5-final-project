<x-guest-layout>
    <h1 class="text-xl font-semibold text-gray-900 mb-1">Create your FLEUR DE PEAU account</h1>
    <p class="text-sm text-gray-500 mb-4">Join our skincare community and track your rituals.</p>

    <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
        @csrf

        <!-- Profile Photo -->
        <div>
            <x-input-label for="profile_photo" :value="__('Profile Photo (optional)')" />
            <div class="mt-2 flex items-center gap-4">
                <img id="reg-photo-preview" src="" alt="Preview" class="h-14 w-14 rounded-full object-cover hidden" />
                <div id="reg-photo-placeholder" class="h-14 w-14 rounded-full bg-gray-100 flex items-center justify-center text-gray-400 text-xs">No photo</div>
                <input id="profile_photo" name="profile_photo" type="file" accept="image/*"
                    class="text-sm text-gray-600"
                    onchange="previewRegistrationPhoto(event)" />
            </div>
            <x-input-error :messages="$errors->get('profile_photo')" class="mt-2" />
        </div>

        <!-- Name -->
        <div class="mt-4">
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Phone -->
        <div class="mt-4">
            <x-input-label for="phone" :value="__('Phone Number')" />
            <x-text-input id="phone" class="block mt-1 w-full" type="tel" name="phone" :value="old('phone')" required autocomplete="tel" />
            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
        </div>

        <!-- Address -->
        <div class="mt-4">
            <x-input-label for="address" :value="__('Address')" />
            <textarea id="address" name="address"
                class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                rows="2" autocomplete="street-address" required>{{ old('address') }}</textarea>
            <x-input-error :messages="$errors->get('address')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <div class="relative flex items-center">
                <x-text-input id="password" class="block mt-1 w-full pr-10" type="password" name="password" required autocomplete="new-password" />
                <button type="button" onclick="togglePassword('password', this)" tabindex="-1" class="absolute right-3 inset-y-0 flex items-center px-1 text-gray-500 focus:outline-none h-full">
                    <svg id="eye-icon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268-2.943 9.542-7-1.274 4.057-5.064 7-9.542 7-4.478 0-8.268-2.943-9.542-7z" /></svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <div class="relative flex items-center">
                <x-text-input id="password_confirmation" class="block mt-1 w-full pr-10" type="password" name="password_confirmation" required autocomplete="new-password" />
                <button type="button" onclick="togglePassword('password_confirmation', this)" tabindex="-1" class="absolute right-3 inset-y-0 flex items-center px-1 text-gray-500 focus:outline-none h-full">
                    <svg id="eye-icon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268-2.943 9.542-7-1.274 4.057-5.064 7-9.542 7-4.478 0-8.268-2.943-9.542-7z" /></svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <script>
        function togglePassword(id, btn) {
            const input = document.getElementById(id);
            if (!input) return;
            if (input.type === 'password') {
                input.type = 'text';
                btn.querySelector('svg').classList.add('text-blue-600');
            } else {
                input.type = 'password';
                btn.querySelector('svg').classList.remove('text-blue-600');
            }
        }
        </script>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>

    <script>
    function previewRegistrationPhoto(event) {
        const file = event.target.files[0];
        if (!file) return;

        const preview = document.getElementById('reg-photo-preview');
        const placeholder = document.getElementById('reg-photo-placeholder');
        if (!preview) return;

        preview.src = URL.createObjectURL(file);
        preview.classList.remove('hidden');
        if (placeholder) placeholder.classList.add('hidden');
    }
    </script>
</x-guest-layout>