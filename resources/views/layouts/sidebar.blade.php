<!-- Sidebar for Mobile -->
<div x-show="sidebarOpen" class="fixed inset-0 z-40 flex md:hidden" x-ref="dialog" aria-modal="true" x-cloak>
    <div x-show="sidebarOpen" x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-600 bg-opacity-75" aria-hidden="true"></div>

    <div x-show="sidebarOpen" x-transition:enter="transition ease-in-out duration-300 transform" x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transition ease-in-out duration-300 transform" x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full" class="relative flex-1 flex flex-col max-w-xs w-full bg-white">
        <div x-show="sidebarOpen" x-transition:enter="ease-in-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in-out duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="absolute top-0 right-0 -mr-12 pt-2">
            <button type="button" class="ml-1 flex items-center justify-center h-10 w-10 rounded-full focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white" @click="sidebarOpen = false">
                <span class="sr-only">Close sidebar</span>
                <i class="fa-solid fa-xmark text-white text-xl"></i>
            </button>
        </div>

        <div class="flex-1 h-0 pt-5 pb-4 overflow-y-auto">
            <div class="flex-shrink-0 flex items-center px-4">
                <img src="{{ asset('DigoCRM.png') }}" class="h-10 w-auto" alt="DigoCRM">
            </div>
            <nav class="mt-5 px-2 space-y-1">
                @include('layouts.sidebar-links')
            </nav>
        </div>
    </div>
</div>

<!-- Static sidebar for desktop -->
<div class="hidden md:flex md:flex-shrink-0 transition-all duration-300" :class="sidebarCollapsed ? 'w-20' : 'w-64'">
    <div class="flex flex-col h-full border-r border-gray-200 bg-white w-full">
        <div class="flex-1 flex flex-col pt-5 pb-4 overflow-y-auto overflow-x-hidden">
            <div class="flex items-center flex-shrink-0 px-6 h-12 justify-between">
                <div x-show="!sidebarCollapsed" class="transition-all duration-300">
                    <img src="{{ asset('DigoCRM.png') }}" class="h-10 w-auto" alt="DigoCRM">
                </div>
                <div x-show="sidebarCollapsed" class="mx-auto hidden">
                    <!-- Logo Hidden when collapsed -->
                </div>
                <button @click="toggleSidebar()" class="p-1.5 rounded-lg bg-gray-50 text-gray-400 hover:text-brand transition-colors mx-auto md:mx-0">
                    <i class="fa-solid" :class="sidebarCollapsed ? 'fa-indent' : 'fa-outdent'"></i>
                </button>
            </div>
            
            <nav class="mt-8 flex-1 px-3 space-y-1">
                @include('layouts.sidebar-links')
            </nav>
        </div>
        
        <div class="flex-shrink-0 flex border-t border-gray-200 p-4">
            <a href="{{ route('profile.edit') }}" class="flex-shrink-0 w-full group block">
                <div class="flex items-center">
                    <div class="inline-block h-9 w-9 rounded-full bg-brand text-white flex items-center justify-center font-bold shrink-0">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <div class="ml-3 transition-opacity duration-300" :class="sidebarCollapsed ? 'opacity-0 w-0' : 'opacity-100'">
                        <p class="text-sm font-medium text-gray-700 group-hover:text-gray-900 truncate">{{ Auth::user()->name }}</p>
                        <p class="text-xs font-medium text-gray-500 group-hover:text-gray-700">View profile</p>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>
