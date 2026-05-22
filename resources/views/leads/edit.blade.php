<x-app-layout>
    @section('title', 'Edit Lead: ' . $lead->name)

    <div class="max-w-4xl mx-auto space-y-6">
        <div class="flex items-center gap-4">
            <a href="{{ route('leads.index') }}" class="text-gray-400 hover:text-gray-600 transition-colors">
                <i class="fa-solid fa-arrow-left text-xl"></i>
            </a>
            <h2 class="text-2xl font-bold text-gray-900">Edit Lead</h2>
        </div>

        <form method="POST" action="{{ route('leads.update', $lead) }}">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Info Lead -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 space-y-6 md:col-span-2">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center border-b border-gray-100 pb-3">
                        <i class="fa-solid fa-user-pen text-brand w-6"></i> Informasi Prospek
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="block text-sm font-bold text-gray-700 ml-1">Nama Lead / PIC</label>
                            <input type="text" name="name" value="{{ old('name', $lead->name) }}" required class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand/20 focus:border-brand focus:bg-white transition-all sm:text-sm">
                        </div>

                        <div class="space-y-2">
                            <label class="block text-sm font-bold text-gray-700 ml-1">Nama Perusahaan</label>
                            <input type="text" name="company_name" value="{{ old('company_name', $lead->company_name) }}" class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand/20 focus:border-brand focus:bg-white transition-all sm:text-sm">
                        </div>

                        <div class="space-y-2">
                            <label class="block text-sm font-bold text-gray-700 ml-1">Email</label>
                            <input type="email" name="email" value="{{ old('email', $lead->email) }}" required class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand/20 focus:border-brand focus:bg-white transition-all sm:text-sm">
                        </div>

                        <div class="space-y-2">
                            <label class="block text-sm font-bold text-gray-700 ml-1">WhatsApp / Phone</label>
                            <input type="text" name="phone" value="{{ old('phone', $lead->phone) }}" required class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand/20 focus:border-brand focus:bg-white transition-all sm:text-sm">
                        </div>

                        <div class="space-y-2">
                            <label class="block text-sm font-bold text-gray-700 ml-1">Source Lead</label>
                            <select name="source" class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand/20 focus:border-brand focus:bg-white transition-all sm:text-sm">
                                <option value="Website" {{ $lead->source == 'Website' ? 'selected' : '' }}>Website</option>
                                <option value="WhatsApp" {{ $lead->source == 'WhatsApp' ? 'selected' : '' }}>WhatsApp</option>
                                <option value="Referral" {{ $lead->source == 'Referral' ? 'selected' : '' }}>Referral</option>
                                <option value="Event" {{ $lead->source == 'Event' ? 'selected' : '' }}>Event</option>
                                <option value="Ads" {{ $lead->source == 'Ads' ? 'selected' : '' }}>Ads</option>
                            </select>
                        </div>

                        <div class="space-y-2">
                            <label class="block text-sm font-bold text-gray-700 ml-1">Source Reference (Supplier/Partner)</label>
                            <input type="text" name="source_reference" value="{{ old('source_reference', $lead->source_reference) }}" class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand/20 focus:border-brand focus:bg-white transition-all sm:text-sm">
                        </div>
                    </div>
                </div>

                <!-- Qualification & Budget -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 space-y-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center border-b border-gray-100 pb-3">
                        <i class="fa-solid fa-file-invoice-dollar text-brand w-6"></i> Kualifikasi & Budget (Opsional)
                    </h3>
                    
                    <div class="space-y-4">
                        <div class="space-y-2">
                            <label class="block text-sm font-bold text-gray-700 ml-1">Qualification</label>
                            <select name="qualification" class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand/20 focus:border-brand focus:bg-white transition-all sm:text-sm">
                                <option value="">-- Pilih Kualifikasi --</option>
                                <option value="Cold" {{ $lead->qualification == 'Cold' ? 'selected' : '' }}>Cold (Baru Masuk)</option>
                                <option value="Warm" {{ $lead->qualification == 'Warm' ? 'selected' : '' }}>Warm (Ada Respon)</option>
                                <option value="Hot" {{ $lead->qualification == 'Hot' ? 'selected' : '' }}>Hot (Sangat Tertarik)</option>
                            </select>
                        </div>

                        <div class="space-y-2">
                            <label class="block text-sm font-bold text-gray-700 ml-1">Estimasi Budget (IDR)</label>
                            <input type="number" name="estimated_budget" value="{{ old('estimated_budget', $lead->estimated_budget ?: 0) }}" class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand/20 focus:border-brand focus:bg-white transition-all sm:text-sm">
                        </div>

                        <div class="space-y-2">
                            <label class="block text-sm font-bold text-gray-700 ml-1">Estimasi Deal Value (IDR)</label>
                            <input type="number" name="estimated_deal_value" value="{{ old('estimated_deal_value', $lead->estimated_deal_value ?: 0) }}" class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand/20 focus:border-brand focus:bg-white transition-all sm:text-sm">
                        </div>
                    </div>
                </div>

                <!-- Pipeline & Status -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 space-y-6 md:col-span-1">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center border-b border-gray-100 pb-3">
                        <i class="fa-solid fa-arrows-spin text-brand w-6"></i> Pipeline Status
                    </h3>
                    <div class="space-y-4">
                        <div class="space-y-2">
                            <label class="block text-sm font-bold text-gray-700 ml-1">Status Sekarang</label>
                            <select name="status" class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand/20 focus:border-brand focus:bg-white transition-all sm:text-sm">
                                <option value="NEW" {{ $lead->status == 'NEW' ? 'selected' : '' }}>New Lead</option>
                                <option value="CONTACTED" {{ $lead->status == 'CONTACTED' ? 'selected' : '' }}>Contacted</option>
                                <option value="FOLLOW_UP" {{ $lead->status == 'FOLLOW_UP' ? 'selected' : '' }}>Follow Up</option>
                                <option value="QUALIFIED" {{ $lead->status == 'QUALIFIED' ? 'selected' : '' }}>Qualified</option>
                                <option value="PROPOSAL" {{ $lead->status == 'PROPOSAL' ? 'selected' : '' }}>Proposal Sent</option>
                                <option value="NEGOTIATION" {{ $lead->status == 'NEGOTIATION' ? 'selected' : '' }}>Negotiation</option>
                                <option value="WON" {{ $lead->status == 'WON' ? 'selected' : '' }}>Won (Closed Won)</option>
                                <option value="LOST" {{ $lead->status == 'LOST' ? 'selected' : '' }}>Lost</option>
                            </select>
                        </div>
                        
                        <div class="space-y-2">
                            <label class="block text-sm font-bold text-gray-700 ml-1">Next Follow Up</label>
                            <input type="datetime-local" name="next_followup_at" value="{{ $lead->next_followup_at ? $lead->next_followup_at->format('Y-m-d\TH:i') : '' }}" class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand/20 focus:border-brand focus:bg-white transition-all sm:text-sm">
                        </div>
                    </div>
                </div>
                
                <!-- Needs -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 space-y-6 md:col-span-2">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center border-b border-gray-100 pb-3">
                        <i class="fa-solid fa-clipboard-list text-brand w-6"></i> Kebutuhan Pelanggan
                    </h3>
                    <textarea name="customer_needs" rows="4" class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand/20 focus:border-brand focus:bg-white transition-all sm:text-sm" placeholder="Rincian kebutuhan customer...">{{ old('customer_needs', $lead->customer_needs) }}</textarea>
                </div>
            </div>

            <div class="pt-6 flex justify-end gap-3">
                <a href="{{ route('leads.index') }}" class="px-6 py-3 bg-white border border-gray-300 rounded-xl font-bold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50 transition-all">Batal</a>
                <button type="submit" class="px-10 py-3 bg-brand text-white rounded-xl font-bold text-xs uppercase tracking-widest hover:bg-opacity-90 transition-all shadow-xl shadow-brand/20">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
