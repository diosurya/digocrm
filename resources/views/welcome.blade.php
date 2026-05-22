<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'DigoCRM') }}</title>
        <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,900&display=swap" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

        <!-- Styles / Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gray-50 text-gray-900">
        <div class="min-h-screen flex flex-col items-center justify-center p-6 text-center">
            <div class="mb-8 transform transition-all hover:scale-110 duration-500">
                <img src="{{ asset('DigoCRM.png') }}" class="h-24 w-auto mx-auto" alt="DigoCRM">
            </div>
            
            <div class="max-w-md w-full bg-white p-8 rounded-[2.5rem] shadow-2xl border border-gray-100">
                <h1 class="text-2xl font-black mb-2 tracking-tight">Enterprise Solution</h1>
                <p class="text-gray-400 text-sm mb-10 font-medium uppercase tracking-widest">Optimized for Bispro Workflow</p>
                
                <div class="space-y-4">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="block w-full py-4 bg-brand text-white rounded-2xl font-black uppercase tracking-widest hover:bg-opacity-90 transition-all shadow-xl shadow-brand/20">
                                Go to Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="block w-full py-4 bg-brand text-white rounded-2xl font-black uppercase tracking-widest hover:bg-opacity-90 transition-all shadow-xl shadow-brand/20">
                                Sign In
                            </a>

                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="block w-full py-4 bg-gray-50 text-gray-600 rounded-2xl font-black uppercase tracking-widest hover:bg-gray-100 transition-all border border-gray-100">
                                    Create Account
                                </a>
                            @endif
                        @endauth
                    @endif
                </div>
            </div>

            <footer class="mt-12 text-gray-400 text-[10px] font-black uppercase tracking-[0.2em]">
                &copy; 2026 Digosoft. All rights reserved.
            </footer>
        </div>
    </body>
</html>
