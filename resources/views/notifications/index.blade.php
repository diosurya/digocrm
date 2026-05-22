<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight flex items-center gap-3">
                <i class="fa-solid fa-bell text-brand"></i>
                {{ __('Notification Center') }}
            </h2>
            <form action="{{ route('notifications.read-all') }}" method="POST">
                @csrf
                <button type="submit" class="text-xs font-black text-brand uppercase tracking-[0.2em] hover:underline">
                    <i class="fa-solid fa-check-double mr-1"></i> Mark all as read
                </button>
            </form>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-4">
            @forelse ($notifications as $notification)
                @php
                    $isDigest = str_contains($notification->type, 'DailySalesDigest');
                    $data = $notification->data;
                @endphp

                <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border {{ $notification->read_at ? 'border-gray-100 opacity-60' : 'border-brand/20 shadow-brand/5 bg-brand/5' }} transition-all flex items-start gap-6">
                    <!-- Icon Section -->
                    <div class="w-16 h-16 rounded-2xl flex items-center justify-center shrink-0 shadow-sm
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
                            text-3xl"></i>
                    </div>
                    
                    <div class="flex-1 min-w-0">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="text-xs font-black text-gray-400 uppercase tracking-[0.3em]">
                                {{ str_replace(['App\\Notifications\\', 'Notification'], '', $notification->type) }}
                            </h3>
                            <span class="text-[10px] font-bold text-gray-400 flex items-center gap-1">
                                <i class="fa-regular fa-clock"></i>
                                {{ $notification->created_at->diffForHumans() }}
                            </span>
                        </div>

                        <!-- CASE: Daily Sales Digest (Rich List) -->
                        @if($isDigest && isset($data['digest']))
                            <div class="space-y-8 mt-6">
                                <!-- Hot List -->
                                @if(count($data['digest']['hot'] ?? []) > 0)
                                    <div>
                                        <p class="text-[11px] font-black text-brand uppercase tracking-widest mb-3 flex items-center gap-2">
                                            <i class="fa-solid fa-bolt-lightning text-orange-500"></i> Prioritas Hari Ini (Hot List)
                                        </p>
                                        <div class="space-y-2">
                                            @foreach($data['digest']['hot'] as $lead)
                                                <a href="{{ route('leads.show', $lead['id']) }}" class="flex items-center justify-between p-4 bg-white border border-gray-100 rounded-2xl hover:border-brand hover:shadow-md transition-all group">
                                                    <div class="flex items-center gap-3">
                                                        <div class="w-8 h-8 rounded-lg bg-gray-50 flex items-center justify-center text-gray-400 group-hover:text-brand transition-colors">
                                                            <i class="fa-solid fa-user-tie text-xs"></i>
                                                        </div>
                                                        <span class="text-sm font-bold text-gray-700">{{ $lead['name'] }}</span>
                                                    </div>
                                                    <div class="flex items-center gap-2 text-[10px] font-black text-brand opacity-0 group-hover:opacity-100 transition-all">
                                                        DETAIL <i class="fa-solid fa-arrow-right-to-bracket"></i>
                                                    </div>
                                                </a>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                <!-- Stale List -->
                                @if(count($data['digest']['stale'] ?? []) > 0)
                                    <div>
                                        <p class="text-[11px] font-black text-amber-600 uppercase tracking-widest mb-3 flex items-center gap-2">
                                            <i class="fa-solid fa-hourglass-end"></i> Perlu Perhatian (Stagnant)
                                        </p>
                                        <div class="space-y-2">
                                            @foreach($data['digest']['stale'] as $lead)
                                                <a href="{{ route('leads.show', $lead['id']) }}" class="flex items-center justify-between p-4 bg-white border border-gray-100 rounded-2xl hover:border-brand hover:shadow-md transition-all group">
                                                    <div class="flex items-center gap-3">
                                                        <div class="w-8 h-8 rounded-lg bg-gray-50 flex items-center justify-center text-gray-400 group-hover:text-brand transition-colors">
                                                            <i class="fa-solid fa-comment-slash text-xs"></i>
                                                        </div>
                                                        <span class="text-sm font-bold text-gray-700">{{ $lead['name'] }}</span>
                                                    </div>
                                                    <div class="flex items-center gap-2 text-[10px] font-black text-brand opacity-0 group-hover:opacity-100 transition-all">
                                                        DETAIL <i class="fa-solid fa-arrow-right-to-bracket"></i>
                                                    </div>
                                                </a>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                <!-- New Leads -->
                                @if(count($data['digest']['new'] ?? []) > 0)
                                    <div>
                                        <p class="text-[11px] font-black text-emerald-600 uppercase tracking-widest mb-3 flex items-center gap-2">
                                            <i class="fa-solid fa-sun text-yellow-500"></i> Prospek Baru (Untouched)
                                        </p>
                                        <div class="space-y-2">
                                            @foreach($data['digest']['new'] as $lead)
                                                <a href="{{ route('leads.show', $lead['id']) }}" class="flex items-center justify-between p-4 bg-white border border-gray-100 rounded-2xl hover:border-brand hover:shadow-md transition-all group">
                                                    <div class="flex items-center gap-3">
                                                        <div class="w-8 h-8 rounded-lg bg-gray-50 flex items-center justify-center text-gray-400 group-hover:text-brand transition-colors">
                                                            <i class="fa-solid fa-user-plus text-xs"></i>
                                                        </div>
                                                        <span class="text-sm font-bold text-gray-700">{{ $lead['name'] }}</span>
                                                    </div>
                                                    <div class="flex items-center gap-2 text-[10px] font-black text-brand opacity-0 group-hover:opacity-100 transition-all">
                                                        DETAIL <i class="fa-solid fa-arrow-right-to-bracket"></i>
                                                    </div>
                                                </a>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @else
                            <!-- Standard Notification -->
                            <p class="text-lg text-gray-800 leading-snug font-bold mb-6 mt-4">{{ $data['message'] ?? 'Notifikasi baru diterima.' }}</p>
                            
                            <div class="flex items-center gap-3">
                                @if(isset($data['module']) && isset($data['id']))
                                <a href="{{ route($data['module'] . '.show', $data['id']) }}" class="px-5 py-2.5 bg-gray-900 text-white text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-gray-800 transition-all flex items-center gap-2 shadow-lg shadow-gray-200">
                                    <i class="fa-solid fa-arrow-up-right-from-square"></i> Lihat Detail Data
                                </a>
                                @endif
                            </div>
                        @endif

                        <div class="mt-10 flex items-center gap-3 border-t border-gray-100 pt-6">
                            @if(!$notification->read_at)
                            <form action="{{ route('notifications.read', $notification->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="px-5 py-2 bg-white border border-brand/20 text-brand text-[10px] font-black rounded-xl hover:bg-brand hover:text-white transition-all flex items-center gap-2">
                                    <i class="fa-solid fa-check"></i> TANDAI SUDAH DIBACA
                                </button>
                            </form>
                            @else
                                <span class="text-[10px] font-black text-gray-300 uppercase tracking-widest flex items-center gap-2">
                                    <i class="fa-solid fa-circle-check"></i> Sudah Dibaca
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white p-24 rounded-[3rem] border border-gray-100 text-center shadow-sm">
                    <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6 text-gray-200">
                        <i class="fa-solid fa-bell-slash text-5xl"></i>
                    </div>
                    <h3 class="text-xl font-black text-gray-900 uppercase tracking-widest mb-2">Semua Sudah Dibaca</h3>
                    <p class="text-sm text-gray-400 font-medium">Belum ada notifikasi baru untuk Anda saat ini.</p>
                </div>
            @endforelse

            @if($notifications->hasPages())
            <div class="mt-8">
                {{ $notifications->links() }}
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
