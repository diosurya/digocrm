<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Sales Order Management') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="{ deleteUrl: '' }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="flex flex-1 max-w-md">
                    <form action="{{ route('orders.index') }}" method="GET" class="w-full relative">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search orders..." class="w-full pl-10 pr-4 py-2.5 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand/20 focus:border-brand transition-all text-sm font-medium shadow-sm">
                        <div class="absolute left-3 top-3 text-gray-400">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </div>
                    </form>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('orders.create') }}" class="inline-flex items-center px-6 py-2.5 bg-brand text-white rounded-xl font-black text-xs uppercase tracking-widest hover:bg-opacity-90 transition-all shadow-lg shadow-brand/20">
                        <i class="fa-solid fa-plus mr-2"></i> Create Order
                    </a>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-black text-gray-400 uppercase tracking-widest">Order No</th>
                                <th class="px-6 py-4 text-left text-xs font-black text-gray-400 uppercase tracking-widest">Customer</th>
                                <th class="px-6 py-4 text-left text-xs font-black text-gray-400 uppercase tracking-widest">Date</th>
                                <th class="px-6 py-4 text-left text-xs font-black text-gray-400 uppercase tracking-widest">Total Amount</th>
                                <th class="px-6 py-4 text-left text-xs font-black text-gray-400 uppercase tracking-widest">Status</th>
                                <th class="px-6 py-4 text-right text-xs font-black text-gray-400 uppercase tracking-widest">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-50">
                            @forelse ($orders as $order)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-black text-brand uppercase">{{ $order->order_number }}</td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-bold text-gray-900">{{ $order->customer->name ?? 'N/A' }}</div>
                                    <div class="text-[10px] text-gray-400 font-bold uppercase">{{ $order->account->name ?? '-' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $order->order_date->format('d/m/Y') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-black text-gray-900">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2.5 py-1 inline-flex text-[10px] leading-5 font-black rounded-full uppercase
                                        {{ $order->status == 'completed' ? 'bg-green-100 text-green-700' : '' }}
                                        {{ $order->status == 'cancelled' ? 'bg-red-100 text-red-700' : '' }}
                                        {{ $order->status == 'draft' ? 'bg-gray-100 text-gray-700' : 'bg-blue-100 text-blue-700' }}
                                    ">
                                        {{ $order->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end gap-3">
                                        <a href="{{ route('orders.show', $order) }}" class="text-gray-400 hover:text-gray-600"><i class="fa-solid fa-eye"></i></a>
                                        <button type="button" @click="deleteUrl = '{{ route('orders.destroy', $order) }}'; $dispatch('open-modal', 'confirm-deletion')" class="text-red-400 hover:text-red-600 transition-colors">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-20 text-center text-gray-400 italic">No orders found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($orders->hasPages())
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                    {{ $orders->links() }}
                </div>
                @endif
            </div>
        </div>

        <!-- Confirm Deletion Modal -->
        <x-modal name="confirm-deletion" focusable>
            <form :action="deleteUrl" method="POST" class="p-8">
                @csrf
                @method('DELETE')
                
                <div class="mb-6 flex items-center justify-center w-16 h-16 bg-red-100 text-red-600 rounded-2xl mx-auto">
                    <i class="fa-solid fa-file-invoice-dollar text-2xl"></i>
                </div>

                <h2 class="text-xl font-black text-gray-900 text-center uppercase tracking-tight">
                    {{ __('Delete Order?') }}
                </h2>

                <p class="mt-2 text-sm text-gray-500 text-center font-medium px-4">
                    {{ __('Apakah Anda yakin ingin menghapus sales order ini?') }}
                </p>

                <div class="mt-10 flex gap-3">
                    <button type="button" @click="$dispatch('close')" class="flex-1 py-4 bg-gray-100 text-gray-600 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-gray-200 transition-all">
                        {{ __('Cancel') }}
                    </button>

                    <button type="submit" class="flex-1 py-4 bg-red-600 text-white rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-red-700 transition-all shadow-lg shadow-red-200">
                        {{ __('Yes, Delete') }}
                    </button>
                </div>
            </form>
        </x-modal>
    </div>
</x-app-layout>
