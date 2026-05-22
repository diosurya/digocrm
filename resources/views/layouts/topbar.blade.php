<div class="sticky top-0 z-10 flex-shrink-0 flex h-16 bg-white border-b border-gray-200">
    <button type="button" class="px-4 border-r border-gray-200 text-gray-500 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-brand md:hidden" @click="sidebarOpen = true">
        <span class="sr-only">Open sidebar</span>
        <i class="fa-solid fa-bars-staggered text-xl"></i>
    </button>
    <div class="flex-1 px-4 flex justify-between">
        <div class="flex-1 flex items-center">
            <h1 class="text-xl font-bold text-gray-900 hidden sm:block">
                @yield('title', 'DigoCRM')
            </h1>
        </div>
        <div class="ml-4 flex items-center md:ml-6 gap-4">
            
            <!-- Notification Bell -->
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" class="p-2 text-gray-400 hover:text-brand transition-all relative focus:outline-none">
                    <i class="fa-solid fa-bell text-xl"></i>
                    @if(auth()->user()->unreadNotifications->count() > 0)
                        <span class="absolute top-1 right-1 block h-4 w-4 rounded-full bg-red-600 text-white text-[9px] font-black flex items-center justify-center border-2 border-white animate-pulse">
                            {{ auth()->user()->unreadNotifications->count() }}
                        </span>
                    @endif
                </button>

                <div x-show="open" 
                     @click.away="open = false"
                     x-cloak
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="transform opacity-0 scale-95"
                     x-transition:enter-end="transform opacity-100 scale-100"
                     class="origin-top-right absolute right-0 mt-3 w-80 rounded-2xl shadow-2xl bg-white ring-1 ring-black ring-opacity-5 focus:outline-none overflow-hidden z-50">
                    <div class="px-5 py-4 bg-gray-50 border-b border-gray-100 flex justify-between items-center">
                        <span class="text-[11px] font-black text-gray-500 uppercase tracking-widest">Aktivitas Terbaru</span>
                        <a href="{{ route('notifications.index') }}" class="text-[10px] font-black text-brand uppercase hover:underline">Lihat Semua</a>
                    </div>
                    <div class="max-h-96 overflow-y-auto">
                        @forelse(auth()->user()->unreadNotifications->take(5) as $notification)
                            @php
                                $notifData = $notification->data;
                                $isDigest = str_contains($notification->type, 'DailySalesDigest');
                            @endphp
                            <div class="px-5 py-4 hover:bg-brand/5 border-b border-gray-50 transition-colors flex items-start gap-4">
                                <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 
                                    {{ str_contains($notification->type, 'CustomerAssigned') ? 'bg-brand/10 text-brand' : '' }}
                                    {{ str_contains($notification->type, 'FollowUpReminder') ? 'bg-indigo-50 text-indigo-600' : '' }}
                                    {{ str_contains($notification->type, 'Escalation') ? 'bg-red-50 text-red-600' : '' }}
                                    {{ $isDigest ? 'bg-emerald-50 text-emerald-600' : '' }}
                                ">
                                    <i class="fa-solid 
                                        {{ str_contains($notification->type, 'CustomerAssigned') ? 'fa-user-tag' : '' }}
                                        {{ str_contains($notification->type, 'FollowUpReminder') ? 'fa-clock' : '' }}
                                        {{ str_contains($notification->type, 'Escalation') ? 'fa-triangle-exclamation' : '' }}
                                        {{ $isDigest ? 'fa-chart-pie' : '' }}
                                        text-sm"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    @if($isDigest && isset($notifData['digest']))
                                        <p class="text-[11px] text-gray-400 font-black uppercase tracking-widest mb-1">Daily Digest</p>
                                        <div class="space-y-1">
                                            @foreach(array_slice($notifData['digest']['hot'] ?? [], 0, 2) as $lead)
                                                <a href="{{ route('leads.show', $lead['id']) }}" class="block text-[12px] font-bold text-gray-800 hover:text-brand truncate">
                                                    <i class="fa-solid fa-bolt-lightning text-orange-500 mr-1 text-[8px]"></i> {{ $lead['name'] }}
                                                </a>
                                            @endforeach
                                            @if(isset($notifData['total_count']) && $notifData['total_count'] > 2)
                                                <p class="text-[9px] font-black text-brand uppercase mt-1">+{{ $notifData['total_count'] - 2 }} Prioritas Lainnya</p>
                                            @endif
                                        </div>
                                    @else
                                        <p class="text-[13px] text-gray-800 font-bold leading-tight">
                                            {{ $notifData['message'] ?? 'Notifikasi baru diterima.' }}
                                        </p>
                                    @endif
                                    <p class="text-[10px] text-gray-400 mt-2 font-bold">{{ $notification->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        @empty
                            <div class="px-4 py-12 text-center">
                                <div class="w-12 h-12 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <i class="fa-solid fa-bell-slash text-gray-200 text-xl"></i>
                                </div>
                                <p class="text-xs text-gray-400 font-medium italic">Tidak ada notifikasi baru</p>
                            </div>
                        @endforelse
                    </div>
                    @if(auth()->user()->unreadNotifications->count() > 0)
                    <form action="{{ route('notifications.read-all') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full py-4 text-center text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] hover:text-brand transition-colors bg-white border-t border-gray-100">
                            Tandai Sudah Dibaca
                        </button>
                    </form>
                    @endif
                </div>
            </div>

            <!-- Profile dropdown -->
            <div class="ml-1 relative" x-data="{ open: false }">
                <div>
                    <button type="button" class="max-w-xs bg-white flex items-center text-sm rounded-full focus:outline-none" @click="open = !open">
                        <div class="h-9 w-9 rounded-xl bg-gray-900 text-white flex items-center justify-center font-black shadow-lg border-2 border-gray-800">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                        <div class="ml-3 text-left hidden lg:block mr-2">
                            <p class="text-[11px] font-black text-gray-900 leading-none">{{ Auth::user()->name }}</p>
                            <p class="text-[9px] font-bold text-brand uppercase tracking-tighter mt-1">{{ str_replace('_', ' ', Auth::user()->role) }}</p>
                        </div>
                        <i class="fa-solid fa-chevron-down text-gray-300 text-[9px]"></i>
                    </button>
                </div>

                <div x-show="open" 
                     @click.away="open = false"
                     x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="transform opacity-0 scale-95"
                     x-transition:enter-end="transform opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-75"
                     x-transition:leave-start="transform opacity-100 scale-100"
                     x-transition:leave-end="transform opacity-0 scale-95"
                     class="origin-top-right absolute right-0 mt-3 w-56 rounded-2xl shadow-2xl py-2 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none overflow-hidden" x-cloak>
                    <div class="px-4 py-3 border-b border-gray-50 mb-1">
                        <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Email Address</p>
                        <p class="text-[11px] font-bold text-gray-900 mt-0.5 truncate">{{ auth()->user()->email }}</p>
                    </div>
                    <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-4 py-2.5 text-xs font-bold text-gray-700 hover:bg-gray-50 transition-colors">
                        <i class="fa-solid fa-circle-user text-gray-400 text-sm"></i> Pengaturan Profil
                    </a>
                    
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="flex items-center gap-3 w-full text-left px-4 py-2.5 text-xs font-bold text-red-600 hover:bg-red-50 transition-colors">
                            <i class="fa-solid fa-power-off text-red-400 text-sm"></i> Keluar Sistem
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
