<x-guest-layout>
    <div class="w-full sm:max-w-xl mt-6 px-12 py-12 bg-white shadow-2xl rounded-[2rem] border border-gray-100 mx-auto">
        <div class="mb-12 text-center">
            <h2 class="text-4xl font-black text-gray-900 tracking-tighter uppercase tracking-widest">Sign In<span class="text-brand">.</span></h2>
            <p class="text-sm text-gray-400 mt-3 font-medium uppercase tracking-widest">Enterprise Access</p>
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}" class="space-y-6">
            @csrf

            <!-- Email Address -->
            <div class="space-y-2">
                <label for="email" class="block text-xs font-black text-gray-400 uppercase tracking-widest ml-1">Email Address</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus class="block w-full px-4 py-4 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-4 focus:ring-brand/10 focus:border-brand focus:bg-white transition-all outline-none text-sm font-bold text-gray-700 shadow-sm">
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Password -->
            <div class="space-y-2">
                <div class="flex justify-between items-center ml-1">
                    <label for="password" class="block text-xs font-black text-gray-400 uppercase tracking-widest">Password</label>
                    @if (Route::has('password.request'))
                        <a class="text-[10px] font-bold text-brand hover:underline uppercase tracking-wider" href="{{ route('password.request') }}">
                            Forgot?
                        </a>
                    @endif
                </div>
                <input id="password" type="password" name="password" required class="block w-full px-4 py-4 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-4 focus:ring-brand/10 focus:border-brand focus:bg-white transition-all outline-none text-sm font-bold text-gray-700 shadow-sm">
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Remember Me -->
            <div class="flex items-center px-1">
                <input id="remember_me" type="checkbox" class="rounded-lg border-gray-300 text-brand shadow-sm focus:ring-brand w-5 h-5 transition-all cursor-pointer" name="remember">
                <label for="remember_me" class="ml-3 text-sm font-bold text-gray-500 cursor-pointer select-none">Remember this device</label>
            </div>

            <div class="pt-4">
                <button type="submit" class="w-full py-4 bg-brand text-white rounded-2xl font-black uppercase tracking-widest hover:bg-opacity-90 transition-all shadow-xl shadow-brand/20 active:scale-[0.98] transform">
                    Sign In
                </button>
            </div>
        </form>
    </div>
</x-guest-layout>
