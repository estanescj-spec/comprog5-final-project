<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Profile Information') }}
        </h2>
        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form id="profile-update-form" method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <!-- Profile Photo -->
        <div>
            <x-input-label for="profile_photo" :value="__('Profile Photo')" />
            <div class="mt-2 flex items-center gap-4">
                @if ($user->profile_photo_path)
                    <img id="photo-preview" src="{{ asset('storage/' . $user->profile_photo_path) }}"
                         alt="Profile photo"
                         class="h-16 w-16 rounded-full object-cover">
                @else
                    <img id="photo-preview" src="" class="h-16 w-16 rounded-full object-cover hidden">
                    <div id="photo-placeholder" class="h-16 w-16 rounded-full bg-gray-200 flex items-center justify-center text-xs text-gray-500">
                        {{ __('No photo') }}
                    </div>
                @endif
                <input id="profile_photo" name="profile_photo" type="file" accept="image/*"
                       class="text-sm text-gray-600" onchange="previewPhoto(event)" />
            </div>
            <p class="mt-1 text-xs text-gray-500">Photo is previewed first. Click Save to apply changes.</p>
            <x-input-error class="mt-2" :messages="$errors->get('profile_photo')" />
        </div>

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                          :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <!-- Email -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full"
                          :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        {{ __('Your email address is unverified.') }}
                        <button form="send-verification"
                                class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <!-- Phone -->
        <div>
            <x-input-label for="phone" :value="__('Phone Number')" />
            <x-text-input id="phone" name="phone" type="tel" class="mt-1 block w-full"
                          :value="old('phone', data_get($user, 'phone'))" autocomplete="tel" />
            <x-input-error class="mt-2" :messages="$errors->get('phone')" />
        </div>

        <!-- Address -->
        <div>
            <x-input-label for="address" :value="__('Address')" />
            <textarea id="address" name="address" rows="2" autocomplete="street-address"
                      class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('address', data_get($user, 'address')) }}</textarea>
            <x-input-error class="mt-2" :messages="$errors->get('address')" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button style="border-radius: 0.375rem !important;">{{ __('Save') }}</x-primary-button>
            <button type="reset" class="inline-flex items-center px-4 py-2 bg-white border border-slate-300 text-sm font-medium text-slate-700 rounded-md hover:bg-slate-50" style="border-radius: 0.375rem !important;">
                {{ __('Cancel') }}
            </button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }"
                   x-show="show"
                   x-transition
                   x-init="setTimeout(() => show = false, 2000)"
                   class="text-sm text-gray-600">
                    {{ __('Saved.') }}
                </p>
            @endif
        </div>
    </form>
</section>

<script>
function previewPhoto(event) {
    const file = event.target.files[0];
    if (!file) return;

    const preview = document.getElementById('photo-preview');
    const placeholder = document.getElementById('photo-placeholder');

    if (preview) {
        preview.src = URL.createObjectURL(file);
        preview.classList.remove('hidden');
    }
    if (placeholder) placeholder.classList.add('hidden');
}

document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('profile-update-form');
    const preview = document.getElementById('photo-preview');
    const placeholder = document.getElementById('photo-placeholder');
    const input = document.getElementById('profile_photo');
    const initialPhotoSrc = preview ? preview.getAttribute('src') : '';

    if (!form) return;

    form.addEventListener('reset', function () {
        setTimeout(function () {
            if (input) input.value = '';

            if (preview) {
                if (initialPhotoSrc) {
                    preview.src = initialPhotoSrc;
                    preview.classList.remove('hidden');
                } else {
                    preview.src = '';
                    preview.classList.add('hidden');
                }
            }

            if (placeholder) {
                if (initialPhotoSrc) {
                    placeholder.classList.add('hidden');
                } else {
                    placeholder.classList.remove('hidden');
                }
            }
        }, 0);
    });
});
</script>