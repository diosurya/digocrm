<x-app-layout>
    @section('title', 'Detail Pelanggan: ' . $customer->name)

    <div class="space-y-6" x-data="{ deleteOpen: false }">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <a href="{{ route('customers.index') }}" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="fa-solid fa-arrow-left text-xl"></i>
                </a>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">{{ $customer->name }}</h2>
                    <p class="text-xs font-black text-brand uppercase tracking-widest">{{ $customer->customer_code }}</p>
                </div>
            </div>

            <div class="flex gap-2">
                <a href="{{ route('customers.edit', $customer) }}" class="px-4 py-2 bg-white border border-gray-200 text-gray-600 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-gray-50 transition-all shadow-sm">
                    <i class="fa-solid fa-pen-to-square mr-2"></i> Edit Profile
                </a>
                <button @click="deleteOpen = true" class="px-4 py-2 bg-red-50 text-red-600 border border-red-100 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-red-600 hover:text-white transition-all">
                    <i class="fa-solid fa-box-archive mr-2"></i> Archive
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left: Profile Info -->
            <div class="lg:col-span-1 space-y-6">
                <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 p-8">
                    <div class="flex flex-col items-center text-center mb-8">
                        <div class="w-20 h-20 bg-brand/10 text-brand rounded-3xl flex items-center justify-center text-3xl font-black mb-4">
                            {{ strtoupper(substr($customer->name, 0, 1)) }}
                        </div>
                        <h3 class="text-lg font-black text-gray-900">{{ $customer->name }}</h3>
                        <span class="px-3 py-1 bg-green-50 text-green-700 rounded-full text-[10px] font-black uppercase mt-2">{{ $customer->status }}</span>
                    </div>

                    <div class="space-y-4 border-t border-gray-50 pt-6">
                        <div class="flex justify-between items-center text-xs">
                            <span class="text-gray-400 font-bold uppercase tracking-widest">Company</span>
                            <span class="text-gray-800 font-black">{{ $customer->company_name ?: '-' }}</span>
                        </div>
                        <div class="flex justify-between items-center text-xs">
                            <span class="text-gray-400 font-bold uppercase tracking-widest">Email</span>
                            <span class="text-gray-800 font-black">{{ $customer->email }}</span>
                        </div>
                        <div class="flex justify-between items-center text-xs">
                            <span class="text-gray-400 font-bold uppercase tracking-widest">Phone</span>
                            <span class="text-brand font-black underline">{{ $customer->whatsapp ?: '-' }}</span>
                        </div>
                        <div class="flex justify-between items-center text-xs">
                            <span class="text-gray-400 font-bold uppercase tracking-widest">Sales PIC</span>
                            <span class="text-gray-800 font-black uppercase">{{ $customer->user->name ?? 'Unassigned' }}</span>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 p-8">
                    <h4 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-6 border-b border-gray-50 pb-2">ERP Data</h4>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center text-xs">
                            <span class="text-gray-400 font-bold">NPWP</span>
                            <span class="text-gray-800 font-black">{{ $customer->npwp ?: '-' }}</span>
                        </div>
                        <div class="flex justify-between items-center text-xs">
                            <span class="text-gray-400 font-bold">TOP</span>
                            <span class="text-gray-800 font-black uppercase">{{ str_replace('_', ' ', $customer->payment_term) ?: 'COD' }}</span>
                        </div>
                        <div class="flex justify-between items-center text-xs">
                            <span class="text-gray-400 font-bold">Currency</span>
                            <span class="text-brand font-black">{{ $customer->currency ?: 'IDR' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right: Activity & Orders -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Orders Table -->
                <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 p-8">
                    <div class="flex items-center justify-between mb-8">
                        <h3 class="text-lg font-black text-gray-900 flex items-center gap-3">
                            <i class="fa-solid fa-file-invoice-dollar text-brand"></i> Sales Orders
                        </h3>
                        <a href="{{ route('orders.create', ['customer_id' => $customer->id]) }}" class="text-xs font-black text-brand uppercase tracking-widest hover:underline">+ Create New</a>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead>
                                <tr class="text-[10px] font-black text-gray-400 uppercase tracking-widest border-b border-gray-50">
                                    <th class="pb-4 text-left">Order No</th>
                                    <th class="pb-4 text-left">Date</th>
                                    <th class="pb-4 text-right">Total</th>
                                    <th class="pb-4 text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @forelse($customer->orders as $order)
                                <tr>
                                    <td class="py-4 text-sm font-black text-brand">{{ $order->order_number }}</td>
                                    <td class="py-4 text-xs text-gray-500">{{ $order->order_date->format('d M Y') }}</td>
                                    <td class="py-4 text-sm font-black text-gray-900 text-right">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                    <td class="py-4 text-center">
                                        <span class="px-2 py-0.5 bg-blue-50 text-blue-600 rounded text-[10px] font-black uppercase">{{ $order->status }}</span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="py-10 text-center text-gray-400 italic text-sm">No transaction history found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Delete Confirmation Modal -->
        <x-modal name="confirm-deletion" :show="false" focusable>
            <div class="p-8 text-center" x-show="deleteOpen" @click.away="deleteOpen = false">
                <div class="w-16 h-16 bg-red-100 text-red-600 rounded-3xl flex items-center justify-center mx-auto mb-6">
                    <i class="fa-solid fa-triangle-exclamation text-2xl"></i>
                </div>
                <h2 class="text-xl font-black text-gray-900 uppercase tracking-tight">Archive Customer?</h2>
                <p class="mt-2 text-sm text-gray-500 font-medium">Apakah Anda yakin ingin mengarsipkan pelanggan **{{ $customer->name }}**? Data tidak akan dihapus permanen tetapi tidak akan muncul di daftar aktif.</p>
                
                <div class="mt-10 flex gap-3">
                    <button type="button" @click="deleteOpen = false" class="flex-1 py-4 bg-gray-100 text-gray-600 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-gray-200 transition-all">Batal</button>
                    <form action="{{ route('customers.destroy', $customer) }}" method="POST" class="flex-1">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full py-4 bg-red-600 text-white rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-red-700 transition-all shadow-lg shadow-red-200">Arsipkan Data</button>
                    </form>
                </div>
            </div>
        </x-modal>
        
        <!-- Script to trigger modal -->
        <div x-init="$watch('deleteOpen', value => value ? $dispatch('open-modal', 'confirm-deletion') : $dispatch('close-modal', 'confirm-deletion'))"></div>
    </div>
</x-app-layout>
