<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Produk: ' . $product->name) }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl p-8 border border-gray-100">
                <form method="POST" action="{{ route('products.update', $product) }}">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Company (Only for Superadmin) -->
                        @if(auth()->user()->isSuperadmin())
                        <div>
                            <x-input-label for="account_id" :value="__('Perusahaan Pemilik')" />
                            <select id="account_id" name="account_id" class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand/20 focus:border-brand focus:bg-white transition-all sm:text-sm font-bold text-gray-700" required>
                                @foreach($accounts as $acc)
                                    <option value="{{ $acc->id }}" {{ $product->account_id == $acc->id ? 'selected' : '' }}>{{ $acc->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @endif

                        <!-- SKU -->
                        <div>
                            <x-input-label for="sku" :value="__('SKU / Kode Produk')" />
                            <x-text-input id="sku" class="block mt-1 w-full" type="text" name="sku" :value="old('sku', $product->sku)" required />
                        </div>

                        <!-- Name -->
                        <div>
                            <x-input-label for="name" :value="__('Nama Produk')" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $product->name)" required />
                        </div>

                        <!-- Price -->
                        <div>
                            <x-input-label for="price" :value="__('Harga (IDR)')" />
                            <x-text-input id="price" class="block mt-1 w-full" type="number" name="price" :value="old('price', $product->price)" required />
                        </div>

                        <!-- Unit -->
                        <div>
                            <x-input-label for="unit" :value="__('Satuan')" />
                            <x-text-input id="unit" class="block mt-1 w-full" type="text" name="unit" :value="old('unit', $product->unit)" required />
                        </div>

                        <!-- Category -->
                        <div>
                            <x-input-label for="category" :value="__('Kategori')" />
                            <x-text-input id="category" class="block mt-1 w-full" type="text" name="category" :value="old('category', $product->category)" />
                        </div>
                    </div>

                    <div class="mt-8 flex items-center justify-end gap-3">
                        <a href="{{ route('products.index') }}" class="text-gray-600 hover:underline text-sm font-bold">Batal</a>
                        <x-primary-button class="bg-brand px-10 py-3 shadow-lg shadow-brand/20">
                            {{ __('Simpan Perubahan') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
