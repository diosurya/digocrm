<x-app-layout>
    @section('title', 'Tambah Lead Baru')

    <div class="max-w-4xl mx-auto space-y-6">
        <div class="flex items-center gap-4">
            <a href="{{ route('leads.index') }}" class="text-gray-400 hover:text-gray-600 transition-colors">
                <i class="fa-solid fa-arrow-left text-xl"></i>
            </a>
            <h2 class="text-2xl font-bold text-gray-900">Input Lead Baru</h2>
        </div>

        @if ($errors->any())
            <div class="bg-red-50 border-l-4 border-red-400 p-4 rounded-md shadow-sm">
                <ul class="text-sm text-red-700 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('leads.store') }}">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Perusahaan (Account) -->
                <div class="md:col-span-2 space-y-2 bg-brand/5 p-6 rounded-2xl border border-brand/10">
                    <label for="account_id" class="block text-sm font-black text-brand uppercase tracking-wider ml-1">Pilih Perusahaan Unit</label>
                    <select name="account_id" id="account_id" required class="block w-full px-4 py-3 bg-white border border-brand/20 rounded-xl focus:ring-2 focus:ring-brand/20 focus:border-brand transition-all sm:text-sm font-bold text-gray-700">
                        @foreach($accounts as $acc)
                            <option value="{{ $acc->id }}">{{ $acc->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Info Lead -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 space-y-6 md:col-span-2">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center border-b border-gray-100 pb-3">
                        <i class="fa-solid fa-user-plus text-brand w-6"></i> Informasi Prospek
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="block text-sm font-bold text-gray-700 ml-1">Nama Lead / PIC</label>
                            <input type="text" name="name" value="{{ old('name') }}" required class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand/20 focus:border-brand focus:bg-white transition-all sm:text-sm">
                        </div>

                        <div class="space-y-2">
                            <label class="block text-sm font-bold text-gray-700 ml-1">Nama Perusahaan</label>
                            <input type="text" name="company_name" value="{{ old('company_name') }}" class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand/20 focus:border-brand focus:bg-white transition-all sm:text-sm">
                        </div>

                        <div class="space-y-2">
                            <label class="block text-sm font-bold text-gray-700 ml-1">Email</label>
                            <input type="email" name="email" value="{{ old('email') }}" required class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand/20 focus:border-brand focus:bg-white transition-all sm:text-sm">
                        </div>

                        <div class="space-y-2">
                            <label class="block text-sm font-bold text-gray-700 ml-1">WhatsApp / Phone</label>
                            <input type="text" name="phone" value="{{ old('phone') }}" required class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand/20 focus:border-brand focus:bg-white transition-all sm:text-sm">
                        </div>

                        <div class="space-y-2">
                            <label class="block text-sm font-bold text-gray-700 ml-1">Kota</label>
                            <input type="text" name="city" value="{{ old('city') }}" class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand/20 focus:border-brand focus:bg-white transition-all sm:text-sm">
                        </div>

                        <div class="space-y-2">
                            <label class="block text-sm font-bold text-gray-700 ml-1">Source Lead</label>
                            <select name="source" class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand/20 focus:border-brand focus:bg-white transition-all sm:text-sm">
                                <option value="Website">Website</option>
                                <option value="WhatsApp">WhatsApp</option>
                                <option value="Referral">Referral</option>
                                <option value="Event">Event</option>
                                <option value="Ads">Ads</option>
                            </select>
                        </div>

                        <div class="space-y-2">
                            <label class="block text-sm font-bold text-gray-700 ml-1">Source Reference (Supplier/Partner)</label>
                            <input type="text" name="source_reference" value="{{ old('source_reference') }}" class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand/20 focus:border-brand focus:bg-white transition-all sm:text-sm" placeholder="Contoh: PT. Supplier A">
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
                                <option value="Cold">Cold (Baru Masuk)</option>
                                <option value="Warm">Warm (Ada Respon)</option>
                                <option value="Hot">Hot (Sangat Tertarik)</option>
                            </select>
                        </div>

                        <div class="space-y-2">
                            <label class="block text-sm font-bold text-gray-700 ml-1">Estimasi Budget (IDR)</label>
                            <input type="number" name="estimated_budget" value="{{ old('estimated_budget', 0) }}" class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand/20 focus:border-brand focus:bg-white transition-all sm:text-sm">
                        </div>

                        <div class="space-y-2">
                            <label class="block text-sm font-bold text-gray-700 ml-1">Estimasi Deal Value (IDR)</label>
                            <input type="number" name="estimated_deal_value" value="{{ old('estimated_deal_value', 0) }}" class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand/20 focus:border-brand focus:bg-white transition-all sm:text-sm">
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
                                <option value="NEW">New Lead</option>
                                <option value="CONTACTED">Contacted</option>
                                <option value="FOLLOW_UP">Follow Up</option>
                                <option value="QUALIFIED">Qualified</option>
                                <option value="PROPOSAL">Proposal Sent</option>
                                <option value="NEGOTIATION">Negotiation</option>
                            </select>
                        </div>

                        <div class="space-y-2">
                            <label class="block text-sm font-bold text-gray-700 ml-1">Assign ke Marketing</label>
                            <select name="user_id" class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand/20 focus:border-brand focus:bg-white transition-all sm:text-sm">
                                <option value="">-- Pilih Marketing --</option>
                                @foreach(\App\Models\User::whereIn('role', ['marketing', 'manager_marketing'])->get() as $m)
                                    <option value="{{ $m->id }}" {{ auth()->id() == $m->id ? 'selected' : '' }}>{{ $m->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="space-y-2">
                            <label class="block text-sm font-bold text-gray-700 ml-1">Next Follow Up</label>
                            <input type="datetime-local" name="next_followup_at" class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand/20 focus:border-brand focus:bg-white transition-all sm:text-sm">
                        </div>
                    </div>
                </div>
                
                <!-- Needs -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 space-y-6 md:col-span-2">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center border-b border-gray-100 pb-3">
                        <i class="fa-solid fa-clipboard-list text-brand w-6"></i> Kebutuhan Pelanggan
                    </h3>
                    <textarea name="customer_needs" rows="4" class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand/20 focus:border-brand focus:bg-white transition-all sm:text-sm" placeholder="Rincian kebutuhan customer..."></textarea>
                </div>
            </div>

            <div class="pt-6 flex justify-end gap-3">
                <a href="{{ route('leads.index') }}" class="px-6 py-3 bg-white border border-gray-300 rounded-xl font-bold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50 transition-all">Batal</a>
                <button type="submit" class="px-10 py-3 bg-brand text-white rounded-xl font-bold text-xs uppercase tracking-widest hover:bg-opacity-90 transition-all shadow-xl shadow-brand/20">
                    Simpan Lead & Mulai Pipeline
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
