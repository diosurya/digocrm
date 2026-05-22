<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Master Product') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="{ deleteUrl: '' }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="flex flex-1 max-w-md">
                    <form action="{{ route('products.index') }}" method="GET" class="w-full relative">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari SKU atau nama produk..." class="w-full pl-10 pr-4 py-2.5 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand/20 focus:border-brand transition-all text-sm">
                        <div class="absolute left-3 top-3 text-gray-400">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </div>
                    </form>
                </div>
                <div class="flex gap-3">
                    @if(auth()->user()->isSuperadmin())
                    <a href="{{ route('products.create') }}" class="inline-flex items-center px-6 py-2.5 bg-brand text-white rounded-xl font-black text-xs uppercase tracking-widest hover:bg-opacity-90 transition-all shadow-lg shadow-brand/20">
                        <i class="fa-solid fa-plus mr-2"></i> Add Product
                    </a>
                    @endif
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100">
                <div class="p-0 overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-black text-gray-400 uppercase tracking-widest">Product Info</th>
                                <th class="px-6 py-4 text-left text-xs font-black text-gray-400 uppercase tracking-widest">SKU</th>
                                <th class="px-6 py-4 text-left text-xs font-black text-gray-400 uppercase tracking-widest">Company</th>
                                <th class="px-6 py-4 text-left text-xs font-black text-gray-400 uppercase tracking-widest">Price</th>
                                <th class="px-6 py-4 text-left text-xs font-black text-gray-400 uppercase tracking-widest">Status</th>
                                <th class="px-6 py-4 text-right text-xs font-black text-gray-400 uppercase tracking-widest">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-50">
                            @forelse ($products as $product)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-bold text-gray-900">{{ $product->name }}</div>
                                    <div class="text-[10px] text-gray-400 font-bold uppercase">{{ $product->category ?: 'General' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <code class="px-2 py-1 bg-brand/5 text-brand rounded text-xs font-black uppercase">{{ $product->sku }}</code>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-600 font-bold uppercase">
                                    {{ $product->account->name ?? '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-black text-gray-900">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                                    <div class="text-[10px] text-gray-400 font-bold uppercase">per {{ $product->unit }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2.5 py-1 inline-flex text-[10px] leading-5 font-black rounded-full uppercase {{ $product->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                        {{ $product->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end gap-3">
                                        <a href="{{ route('products.edit', $product) }}" class="text-brand hover:text-brand-dark transition-colors"><i class="fa-solid fa-pen-to-square"></i></a>
                                        @if(auth()->user()->isSuperadmin())
                                        <button type="button" @click="deleteUrl = '{{ route('products.destroy', $product) }}'; $dispatch('open-modal', 'confirm-deletion')" class="text-red-400 hover:text-red-600 transition-colors">
                                            <i class="fa-solid fa-box-archive"></i>
                                        </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-20 text-center text-gray-400 italic">
                                    <i class="fa-solid fa-box-open text-4xl mb-4 block"></i>
                                    Belum ada data produk.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($products->hasPages())
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                    {{ $products->links() }}
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
                    <i class="fa-solid fa-triangle-exclamation text-2xl"></i>
                </div>

                <h2 class="text-xl font-black text-gray-900 text-center uppercase tracking-tight">
                    {{ __('Archive Product?') }}
                </h2>

                <p class="mt-2 text-sm text-gray-500 text-center font-medium px-4">
                    {{ __('Apakah Anda yakin ingin mengarsipkan produk ini?') }}
                </p>

                <div class="mt-10 flex gap-3">
                    <button type="button" @click="$dispatch('close')" class="flex-1 py-4 bg-gray-100 text-gray-600 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-gray-200 transition-all">
                        {{ __('Cancel') }}
                    </button>

                    <button type="submit" class="flex-1 py-4 bg-red-600 text-white rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-red-700 transition-all shadow-lg shadow-red-200">
                        {{ __('Yes, Archive') }}
                    </button>
                </div>
            </form>
        </x-modal>
    </div>
</x-app-layout>
