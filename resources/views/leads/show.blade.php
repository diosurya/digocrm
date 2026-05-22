<x-app-layout>
    @section('title', 'Detail Prospek: ' . $lead->name)

    <div class="space-y-6" x-data="{ activityOpen: false, convertOpen: false, deleteOpen: false }">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <a href="{{ route('leads.index') }}" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="fa-solid fa-arrow-left text-xl"></i>
                </a>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">{{ $lead->name }}</h2>
                    <p class="text-xs font-black text-brand uppercase tracking-widest">{{ $lead->lead_code }}</p>
                </div>
            </div>

            <div class="flex gap-2">
                @if($lead->status !== 'WON' && $lead->status !== 'CONVERTED')
                <button @click="convertOpen = true" class="px-4 py-2 bg-green-600 text-white rounded-xl text-xs font-black uppercase tracking-widest hover:bg-green-700 transition-all shadow-lg shadow-green-200">
                    <i class="fa-solid fa-user-check mr-2"></i> Convert to Customer
                </button>
                @endif
                <button @click="activityOpen = true" class="px-4 py-2 bg-brand text-white rounded-xl text-xs font-black uppercase tracking-widest hover:bg-opacity-90 transition-all shadow-lg shadow-brand/20">
                    <i class="fa-solid fa-plus mr-2"></i> Log Activity
                </button>
                <button @click="deleteOpen = true" class="p-2.5 bg-white border border-gray-200 text-red-400 rounded-xl hover:bg-red-50 hover:text-red-600 transition-all">
                    <i class="fa-solid fa-trash-can"></i>
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left: Stats & Info -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Status Card -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-4">Pipeline Status</h3>
                    <div class="flex items-center justify-between">
                        <span class="px-3 py-1 bg-brand/10 text-brand rounded-full text-xs font-black uppercase">{{ $lead->status }}</span>
                        <span class="text-[10px] text-gray-400 font-bold">Qualification: <span class="text-gray-900">{{ $lead->qualification ?: 'N/A' }}</span></span>
                    </div>
                    <div class="mt-6 space-y-4">
                        <div>
                            <p class="text-[10px] text-gray-400 font-black uppercase">Est. Deal Value</p>
                            <p class="text-xl font-black text-gray-900 tracking-tighter">Rp {{ number_format($lead->estimated_deal_value, 0, ',', '.') }}</p>
                        </div>
                        <div class="pt-4 border-t border-gray-50 flex justify-between">
                            <div>
                                <p class="text-[10px] text-gray-400 font-black uppercase">Next Follow Up</p>
                                <p class="text-xs font-bold text-brand">{{ $lead->next_followup_at?->format('d M Y, H:i') ?: '-' }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-[10px] text-gray-400 font-black uppercase">Source</p>
                                <p class="text-xs font-bold text-gray-700">{{ $lead->source }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact Card -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-4">
                    <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Lead Information</h3>
                    <div class="space-y-3">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-gray-50 flex items-center justify-center text-gray-400"><i class="fa-solid fa-building text-xs"></i></div>
                            <div>
                                <p class="text-[10px] text-gray-400 font-bold leading-none">Company</p>
                                <p class="text-sm font-bold text-gray-700">{{ $lead->company_name ?: '-' }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-gray-50 flex items-center justify-center text-gray-400"><i class="fa-solid fa-envelope text-xs"></i></div>
                            <div>
                                <p class="text-[10px] text-gray-400 font-bold leading-none">Email</p>
                                <p class="text-sm font-bold text-gray-700">{{ $lead->email }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-gray-50 flex items-center justify-center text-gray-400"><i class="fa-solid fa-phone text-xs"></i></div>
                            <div>
                                <p class="text-[10px] text-gray-400 font-bold leading-none">WhatsApp</p>
                                <p class="text-sm font-bold text-brand underline">{{ $lead->phone }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right: Timeline -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 min-h-[400px]">
                    <h3 class="text-lg font-black text-gray-900 mb-8 flex items-center">
                        <i class="fa-solid fa-clock-rotate-left mr-3 text-brand"></i>
                        Activity Timeline
                    </h3>

                    <div class="relative">
                        <div class="absolute left-4 top-0 bottom-0 w-0.5 bg-gray-100"></div>
                        <div class="space-y-10 relative">
                            @forelse($lead->activities()->latest()->get() as $activity)
                                <div class="flex items-start gap-6 relative">
                                    <div class="w-9 h-9 rounded-full border-4 border-white shadow-sm flex items-center justify-center shrink-0 z-10
                                        {{ in_array($activity->activity_type, ['CALL', 'WHATSAPP']) ? 'bg-green-500 text-white' : 'bg-gray-400 text-white' }}
                                        {{ $activity->activity_type == 'escalation' ? 'bg-red-600 text-white' : '' }}
                                    ">
                                        <i class="fa-solid {{ $activity->activity_type == 'CALL' ? 'fa-phone' : ($activity->activity_type == 'WHATSAPP' ? 'fa-brands fa-whatsapp' : 'fa-comment-dots') }} text-xs"></i>
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex justify-between items-center mb-1">
                                            <p class="text-sm font-black text-gray-900 uppercase tracking-tight">{{ $activity->activity_type }} <span class="text-gray-400 font-normal">by</span> {{ $activity->user->name }}</p>
                                            <span class="text-[10px] text-gray-400 font-bold">{{ $activity->created_at->diffForHumans() }}</span>
                                        </div>
                                        <div class="bg-gray-50 rounded-xl p-4 text-sm text-gray-600 border border-gray-100 shadow-sm italic">
                                            "{{ $activity->result }}"
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-20 text-gray-400 italic">No activity recorded yet.</div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Activity Log Modal -->
        <x-modal name="activity-modal" :show="false" focusable>
            <div x-show="activityOpen" class="p-8">
                <h2 class="text-xl font-black text-gray-900 mb-6 uppercase tracking-tight">Log New Activity</h2>
                <form action="{{ route('leads.log-activity', $lead->id) }}" method="POST" class="space-y-6">
                    @csrf
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-1">
                            <label class="text-[10px] font-black text-gray-400 uppercase ml-1">Activity Type</label>
                            <select name="activity_type" class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand/20 focus:border-brand transition-all sm:text-sm">
                                <option value="CALL">Call</option>
                                <option value="WHATSAPP">WhatsApp</option>
                                <option value="EMAIL">Email</option>
                                <option value="VISIT">Site Visit</option>
                                <option value="MEETING">Meeting</option>
                            </select>
                        </div>
                        <div class="space-y-1">
                            <label class="text-[10px] font-black text-gray-400 uppercase ml-1">New Status</label>
                            <select name="new_status" class="block w-full px-4 py-3 bg-brand/5 border border-brand/20 rounded-xl focus:ring-2 focus:ring-brand/20 focus:border-brand transition-all sm:text-sm font-bold text-brand">
                                <option value="CONTACTED">Contacted</option>
                                <option value="QUALIFIED">Qualified</option>
                                <option value="PROPOSAL">Proposal Sent</option>
                                <option value="NEGOTIATION">Negotiation</option>
                                <option value="WON">Won (Closed Won)</option>
                                <option value="LOST">Lost</option>
                            </select>
                        </div>
                    </div>

                    <div class="space-y-1">
                        <label class="text-[10px] font-black text-gray-400 uppercase ml-1">Summary / Result</label>
                        <textarea name="result" rows="3" required class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand/20 focus:border-brand focus:bg-white transition-all sm:text-sm" placeholder="What happened during this activity?"></textarea>
                    </div>

                    <div class="space-y-1">
                        <label class="text-[10px] font-black text-gray-400 uppercase ml-1">Next Follow Up Date</label>
                        <input type="datetime-local" name="next_followup_at" class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand/20 focus:border-brand focus:bg-white transition-all sm:text-sm">
                    </div>

                    <div class="pt-4 flex gap-3">
                        <button type="button" @click="activityOpen = false" class="flex-1 py-3 bg-gray-100 text-gray-600 rounded-xl font-black text-xs uppercase hover:bg-gray-200 transition-all">Batal</button>
                        <button type="submit" class="flex-1 py-3 bg-brand text-white rounded-xl font-black text-xs uppercase shadow-lg shadow-brand/20 hover:bg-opacity-90 transition-all">Simpan Aktivitas</button>
                    </div>
                </form>
            </div>
        </x-modal>

        <!-- Convert Confirmation Modal -->
        <x-modal name="confirm-convert" :show="false" focusable>
            <div x-show="convertOpen" class="p-8 text-center">
                <div class="w-16 h-16 bg-green-100 text-green-600 rounded-[1.5rem] flex items-center justify-center mx-auto mb-6">
                    <i class="fa-solid fa-user-check text-2xl"></i>
                </div>
                <h2 class="text-xl font-black text-gray-900 uppercase tracking-tight">Convert to Customer?</h2>
                <p class="mt-2 text-sm text-gray-500 font-medium px-4">Prospek **{{ $lead->name }}** akan dikonversi menjadi data Pelanggan tetap. Lanjutkan?</p>
                
                <div class="mt-10 flex gap-3">
                    <button type="button" @click="convertOpen = false" class="flex-1 py-4 bg-gray-100 text-gray-600 rounded-2xl font-black text-xs uppercase hover:bg-gray-200 transition-all">Batal</button>
                    <form action="{{ route('leads.convert', $lead->id) }}" method="POST" class="flex-1">
                        @csrf
                        <button type="submit" class="w-full py-4 bg-green-600 text-white rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-green-700 transition-all shadow-lg shadow-green-200">Konversi Sekarang</button>
                    </form>
                </div>
            </div>
        </x-modal>

        <!-- Delete Confirmation Modal -->
        <x-modal name="confirm-deletion" :show="false" focusable>
            <div x-show="deleteOpen" class="p-8 text-center">
                <div class="w-16 h-16 bg-red-100 text-red-600 rounded-[1.5rem] flex items-center justify-center mx-auto mb-6">
                    <i class="fa-solid fa-triangle-exclamation text-2xl"></i>
                </div>
                <h2 class="text-xl font-black text-gray-900 uppercase tracking-tight">Archive Lead?</h2>
                <p class="mt-2 text-sm text-gray-500 font-medium px-4">Apakah Anda yakin ingin mengarsipkan lead **{{ $lead->name }}**? Data akan berpindah ke status Archive.</p>
                
                <div class="mt-10 flex gap-3">
                    <button type="button" @click="deleteOpen = false" class="flex-1 py-4 bg-gray-100 text-gray-600 rounded-2xl font-black text-xs uppercase hover:bg-gray-200 transition-all">Batal</button>
                    <form action="{{ route('leads.destroy', $lead->id) }}" method="POST" class="flex-1">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full py-4 bg-red-600 text-white rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-red-700 transition-all shadow-lg shadow-red-200">Arsipkan Lead</button>
                    </form>
                </div>
            </div>
        </x-modal>

        <!-- Triggering helper -->
        <div x-init="
            $watch('activityOpen', value => value ? $dispatch('open-modal', 'activity-modal') : $dispatch('close-modal', 'activity-modal'));
            $watch('convertOpen', value => value ? $dispatch('open-modal', 'confirm-convert') : $dispatch('close-modal', 'confirm-convert'));
            $watch('deleteOpen', value => value ? $dispatch('open-modal', 'confirm-deletion') : $dispatch('close-modal', 'confirm-deletion'));
        "></div>
    </div>
</x-app-layout>
