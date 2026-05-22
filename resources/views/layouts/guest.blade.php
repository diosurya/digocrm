<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'DigoCRM') }}</title>
        <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,900&display=swap" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

        <!-- Scripts -->
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        colors: {
                            brand: 'rgb(12 192 223)',
                            'brand-dark': 'rgb(10 160 186)',
                        },
                        borderRadius: {
                            '3xl': '1.5rem',
                            '2rem': '2rem',
                            'xl': '0.75rem',
                        }
                    }
                }
            }
        </script>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
        
        <style>
            /* Custom Scrollbar Styling */
            ::-webkit-scrollbar {
                width: 6px;
                height: 6px;
            }
            ::-webkit-scrollbar-track {
                background: #f1f1f1;
                border-radius: 10px;
            }
            ::-webkit-scrollbar-thumb {
                background: #d1d5db; /* gray-300 */
                border-radius: 10px;
            }
            ::-webkit-scrollbar-thumb:hover {
                background: #9ca3af; /* gray-400 */
            }
            
            /* Firefox Support */
            * {
                scrollbar-width: thin;
                scrollbar-color: #d1d5db #f1f1f1;
            }
        </style>
    </head>
    <body class="font-sans antialiased bg-gray-50">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
            <div class="mb-6 transition-transform hover:scale-105 duration-300 text-center">
                <a href="/">
                    <img src="{{ asset('DigoCRM.png') }}" class="h-20 w-auto" alt="DigoCRM">
                </a>
            </div>

            <div class="w-full px-6 flex justify-center">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
