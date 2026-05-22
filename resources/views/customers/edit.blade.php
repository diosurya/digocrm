<x-app-layout>
    @section('title', 'Edit Customer Master: ' . $customer->name)

    <div class="max-w-6xl mx-auto space-y-6">
        <div class="flex items-center gap-4">
            <a href="{{ route('customers.index') }}" class="text-gray-400 hover:text-gray-600 transition-colors">
                <i class="fa-solid fa-arrow-left text-xl"></i>
            </a>
            <h2 class="text-2xl font-bold text-gray-900">Edit Customer Master</h2>
        </div>

        <form method="POST" action="{{ route('customers.update', $customer) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Left Column: Core Info & Contacts -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- SECTION 1: Informasi Dasar -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
                        <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center border-b border-gray-100 pb-3">
                            <i class="fa-solid fa-building text-brand w-6"></i> Informasi Dasar
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="md:col-span-2 space-y-2">
                                <label for="account_id" class="block text-sm font-black text-brand uppercase tracking-wider ml-1">Kepemilikan Data</label>
                                <select name="account_id" id="account_id" required class="block w-full px-4 py-3 bg-brand/5 border border-brand/20 rounded-xl focus:ring-2 focus:ring-brand/20 focus:border-brand transition-all sm:text-sm font-bold text-gray-900">
                                    @foreach($accounts as $acc)
                                        <option value="{{ $acc->id }}" {{ (old('account_id', $customer->account_id) == $acc->id) ? 'selected' : '' }}>{{ $acc->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="space-y-2">
                                <label for="type" class="block text-sm font-bold text-gray-700 ml-1">Tipe Entitas</label>
                                <select name="type" id="type" class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand/20 focus:border-brand transition-all sm:text-sm">
                                    <option value="corporate" {{ $customer->type == 'corporate' ? 'selected' : '' }}>B2B - Corporate</option>
                                    <option value="individual" {{ $customer->type == 'individual' ? 'selected' : '' }}>B2C - Individual</option>
                                </select>
                            </div>

                            <div class="space-y-2">
                                <label for="status" class="block text-sm font-bold text-gray-700 ml-1">Status Customer</label>
                                <select name="status" id="status" class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand/20 focus:border-brand transition-all sm:text-sm">
                                    <option value="ACTIVE" {{ $customer->status == 'ACTIVE' ? 'selected' : '' }}>Active</option>
                                    <option value="INACTIVE" {{ $customer->status == 'INACTIVE' ? 'selected' : '' }}>Inactive</option>
                                    <option value="BLACKLIST" {{ $customer->status == 'BLACKLIST' ? 'selected' : '' }}>Blacklist</option>
                                    <option value="ARCHIVED" {{ $customer->status == 'ARCHIVED' ? 'selected' : '' }}>Archived</option>
                                </select>
                            </div>

                            <div class="space-y-2">
                                <label for="company_name" class="block text-sm font-bold text-gray-700 ml-1">Nama Perusahaan</label>
                                <input type="text" name="company_name" id="company_name" value="{{ old('company_name', $customer->company_name) }}" class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand/20 focus:border-brand focus:bg-white transition-all sm:text-sm" placeholder="Contoh: PT. Langgeng Jaya">
                            </div>

                            <div class="space-y-2">
                                <label for="name" class="block text-sm font-bold text-gray-700 ml-1">Nama Panggil / Pelanggan</label>
                                <input type="text" name="name" id="name" value="{{ old('name', $customer->name) }}" required class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand/20 focus:border-brand focus:bg-white transition-all sm:text-sm" placeholder="Nama entitas untuk ditampilkan">
                            </div>
                        </div>
                    </div>

                    <!-- SECTION 2: Kontak & Alamat -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
                        <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center border-b border-gray-100 pb-3">
                            <i class="fa-solid fa-address-book text-brand w-6"></i> Kontak PIC & Alamat
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label for="contact_person" class="block text-sm font-bold text-gray-700 ml-1">Contact Person (PIC)</label>
                                <input type="text" name="contact_person" id="contact_person" value="{{ old('contact_person', $customer->contact_person) }}" class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand/20 focus:border-brand focus:bg-white transition-all sm:text-sm" placeholder="Nama perwakilan">
                            </div>

                            <div class="space-y-2">
                                <label for="job_title" class="block text-sm font-bold text-gray-700 ml-1">Jabatan PIC</label>
                                <input type="text" name="job_title" id="job_title" value="{{ old('job_title', $customer->job_title) }}" class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand/20 focus:border-brand focus:bg-white transition-all sm:text-sm" placeholder="Contoh: Purchasing Manager">
                            </div>

                            <div class="space-y-2">
                                <label for="email" class="block text-sm font-bold text-gray-700 ml-1">Email Resmi</label>
                                <input type="email" name="email" id="email" value="{{ old('email', $customer->email) }}" required class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand/20 focus:border-brand focus:bg-white transition-all sm:text-sm" placeholder="email@contoh.com">
                            </div>

                            <div class="space-y-2">
                                <label for="whatsapp" class="block text-sm font-bold text-gray-700 ml-1">Nomor WhatsApp Utama</label>
                                <input type="text" name="whatsapp" id="whatsapp" value="{{ old('whatsapp', $customer->whatsapp) }}" class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand/20 focus:border-brand focus:bg-white transition-all sm:text-sm" placeholder="Contoh: 08123456789">
                            </div>

                            <div class="space-y-2">
                                <label for="alt_phone" class="block text-sm font-bold text-gray-700 ml-1">Telepon Alternatif / Kantor</label>
                                <input type="text" name="alt_phone" id="alt_phone" value="{{ old('alt_phone', $customer->alt_phone) }}" class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand/20 focus:border-brand focus:bg-white transition-all sm:text-sm" placeholder="Telepon kantor">
                            </div>

                            <div class="md:col-span-2 space-y-2">
                                <label for="location" class="block text-sm font-bold text-gray-700 ml-1">Alamat Lengkap</label>
                                <textarea name="location" id="location" rows="3" class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand/20 focus:border-brand focus:bg-white transition-all sm:text-sm" placeholder="Jalan, Gedung, Nomor...">{{ old('location', $customer->location) }}</textarea>
                            </div>

                            <div class="space-y-2">
                                <label for="province" class="block text-sm font-bold text-gray-700 ml-1">Provinsi / State</label>
                                <input type="text" name="province" id="province" value="{{ old('province', $customer->province) }}" class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand/20 focus:border-brand focus:bg-white transition-all sm:text-sm">
                            </div>

                            <div class="space-y-2">
                                <label for="postal_code" class="block text-sm font-bold text-gray-700 ml-1">Kode Pos</label>
                                <input type="text" name="postal_code" id="postal_code" value="{{ old('postal_code', $customer->postal_code) }}" class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand/20 focus:border-brand focus:bg-white transition-all sm:text-sm">
                            </div>
                            
                            <div class="space-y-2">
                                <label for="country" class="block text-sm font-bold text-gray-700 ml-1">Negara</label>
                                <input type="text" name="country" id="country" value="{{ old('country', $customer->country ?: 'Indonesia') }}" class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand/20 focus:border-brand focus:bg-white transition-all sm:text-sm">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column: ERP, Financial & Assignment -->
                <div class="space-y-6">
                    <!-- SECTION 3: ERP & Keuangan -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
                        <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center border-b border-gray-100 pb-3">
                            <i class="fa-solid fa-server text-brand w-6"></i> Integrasi ERP & Finance
                        </h3>
                        <div class="space-y-4">
                            <div class="space-y-2">
                                <label for="npwp" class="block text-sm font-bold text-gray-700 ml-1">NPWP / Tax ID (Opsional)</label>
                                <input type="text" name="npwp" id="npwp" value="{{ old('npwp', $customer->npwp) }}" class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand/20 focus:border-brand focus:bg-white transition-all sm:text-sm">
                            </div>

                            <div class="space-y-2">
                                <label for="tax_type" class="block text-sm font-bold text-gray-700 ml-1">Tipe Pajak (Opsional)</label>
                                <select name="tax_type" id="tax_type" class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand/20 focus:border-brand transition-all sm:text-sm">
                                    <option value="">-- Pilih Tipe Pajak --</option>
                                    <option value="non_tax" {{ $customer->tax_type == 'non_tax' ? 'selected' : '' }}>Non-Tax / V0</option>
                                    <option value="ppn_11" {{ $customer->tax_type == 'ppn_11' ? 'selected' : '' }}>PPN 11% / V1</option>
                                    <option value="ppn_12" {{ $customer->tax_type == 'ppn_12' ? 'selected' : '' }}>PPN 12% / V2</option>
                                    <option value="export" {{ $customer->tax_type == 'export' ? 'selected' : '' }}>Export / V3</option>
                                </select>
                            </div>

                            <div class="space-y-2">
                                <label for="payment_term" class="block text-sm font-bold text-gray-700 ml-1">Term of Payment (Opsional)</label>
                                <select name="payment_term" id="payment_term" class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand/20 focus:border-brand transition-all sm:text-sm">
                                    <option value="">-- Pilih TOP --</option>
                                    <option value="cash_in_advance" {{ $customer->payment_term == 'cash_in_advance' ? 'selected' : '' }}>CIA (Cash In Advance)</option>
                                    <option value="cash_on_delivery" {{ $customer->payment_term == 'cash_on_delivery' ? 'selected' : '' }}>COD</option>
                                    <option value="net_14" {{ $customer->payment_term == 'net_14' ? 'selected' : '' }}>Net 14 Days</option>
                                    <option value="net_30" {{ $customer->payment_term == 'net_30' ? 'selected' : '' }}>Net 30 Days</option>
                                    <option value="net_60" {{ $customer->payment_term == 'net_60' ? 'selected' : '' }}>Net 60 Days</option>
                                </select>
                            </div>

                            <div class="space-y-2">
                                <label for="currency" class="block text-sm font-bold text-gray-700 ml-1">Mata Uang (Opsional)</label>
                                <select name="currency" id="currency" class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand/20 focus:border-brand transition-all sm:text-sm">
                                    <option value="IDR" {{ $customer->currency == 'IDR' ? 'selected' : '' }}>IDR - Rupiah</option>
                                    <option value="USD" {{ $customer->currency == 'USD' ? 'selected' : '' }}>USD - US Dollar</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- SECTION 4: Sales Assignment -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
                        <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center border-b border-gray-100 pb-3">
                            <i class="fa-solid fa-user-tie text-brand w-6"></i> Ownership
                        </h3>
                        <div class="space-y-4">
                            @if(auth()->user()->isSuperadmin() || auth()->user()->isManager())
                            <div class="space-y-2">
                                <label for="user_id" class="block text-sm font-bold text-gray-700 ml-1">Account Owner (Sales PIC)</label>
                                <select name="user_id" id="user_id" class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand/20 focus:border-brand transition-all sm:text-sm">
                                    <option value="">-- Unassigned --</option>
                                    @php
                                        $marketingList = auth()->user()->isSuperadmin() 
                                            ? \App\Models\User::whereIn('role', ['marketing', 'manager_marketing'])->get()
                                            : auth()->user()->subordinates;
                                    @endphp
                                    @foreach($marketingList as $m)
                                        <option value="{{ $m->id }}" {{ old('user_id', $customer->user_id) == $m->id ? 'selected' : '' }}>{{ $m->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @endif

                            <div class="space-y-2">
                                <label for="source" class="block text-sm font-bold text-gray-700 ml-1">Source / Asal Customer</label>
                                <input type="text" name="source" id="source" value="{{ old('source', $customer->source) }}" class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand/20 focus:border-brand focus:bg-white transition-all sm:text-sm">
                            </div>

                            <div class="space-y-2">
                                <label for="source_reference" class="block text-sm font-bold text-gray-700 ml-1">Source Reference (Supplier)</label>
                                <input type="text" name="source_reference" id="source_reference" value="{{ old('source_reference', $customer->source_reference) }}" class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand/20 focus:border-brand focus:bg-white transition-all sm:text-sm">
                            </div>

                            <div class="space-y-2">
                                <label for="priority" class="block text-sm font-bold text-gray-700 ml-1">Prioritas</label>
                                <select name="priority" id="priority" class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand/20 focus:border-brand transition-all sm:text-sm">
                                    <option value="low" {{ $customer->priority == 'low' ? 'selected' : '' }}>Low</option>
                                    <option value="medium" {{ $customer->priority == 'medium' ? 'selected' : '' }}>Medium</option>
                                    <option value="high" {{ $customer->priority == 'high' ? 'selected' : '' }}>High</option>
                                </select>
                            </div>

                            <div class="space-y-2">
                                <label for="important_chat" class="block text-sm font-bold text-gray-700 ml-1">Catatan Internal</label>
                                <textarea name="important_chat" id="important_chat" rows="3" class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand/20 focus:border-brand focus:bg-white transition-all sm:text-sm">{{ old('important_chat', $customer->important_chat) }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="pt-6 border-t border-gray-100 flex flex-col sm:flex-row justify-end gap-3">
                <a href="{{ route('customers.index') }}" class="inline-flex items-center justify-center px-6 py-3 bg-white border border-gray-300 rounded-xl font-bold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 transition-all">
                    Batal
                </a>
                <button type="submit" class="inline-flex items-center justify-center px-10 py-3 bg-gray-900 text-white border border-transparent rounded-xl font-bold text-xs uppercase tracking-widest hover:bg-gray-800 transition-all shadow-xl shadow-gray-200">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
