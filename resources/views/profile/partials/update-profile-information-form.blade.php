<section>
    <header>
        <h2 class="text-lg font-bold text-gray-900 flex items-center gap-2">
            <i class="fa-solid fa-user-gear text-brand"></i>
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-500">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-8 space-y-6">
        @csrf
        @method('patch')

        <div class="space-y-2">
            <x-input-label for="name" :value="__('Name')" class="ml-1" />
            <input id="name" name="name" type="text" class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand/20 focus:border-brand focus:bg-white transition-all sm:text-sm" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div class="space-y-2">
            <x-input-label for="email" :value="__('Email Address')" class="ml-1" />
            <input id="email" name="email" type="email" class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand/20 focus:border-brand focus:bg-white transition-all sm:text-sm" value="{{ old('email', $user->email) }}" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="bg-amber-50 p-4 rounded-xl border border-amber-100 mt-4">
                    <p class="text-sm text-amber-800 flex items-center gap-2">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                        {{ __('Your email address is unverified.') }}
                    </p>

                    <button form="send-verification" class="mt-2 text-xs font-black text-brand uppercase tracking-widest hover:underline focus:outline-none">
                        {{ __('Click here to re-send verification') }}
                    </button>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-bold text-xs text-green-600 uppercase">
                            {{ __('A new verification link has been sent.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4 pt-2">
            <button type="submit" class="inline-flex items-center px-8 py-3 bg-brand border border-transparent rounded-xl font-bold text-xs text-white uppercase tracking-widest hover:bg-opacity-90 transition-all shadow-lg shadow-brand/20">
                {{ __('Save Changes') }}
            </button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm font-bold text-green-600 flex items-center gap-2"
                >
                    <i class="fa-solid fa-circle-check"></i>
                    {{ __('Saved successfully.') }}
                </p>
            @endif
        </div>
    </form>
</section>
