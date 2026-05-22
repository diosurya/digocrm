<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Unit Perusahaan Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-[2rem] p-8 border border-gray-100">
                <form method="POST" action="{{ route('accounts.store') }}" class="space-y-6">
                    @csrf

                    <div class="space-y-2">
                        <x-input-label for="name" :value="__('Nama Perusahaan')" class="ml-1" />
                        <x-text-input id="name" name="name" type="text" class="block w-full" :value="old('name')" required placeholder="Contoh: PT. Langgeng Jaya" />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <div class="space-y-2">
                        <x-input-label for="industry" :value="__('Bidang Industri')" class="ml-1" />
                        <x-text-input id="industry" name="industry" type="text" class="block w-full" :value="old('industry')" placeholder="Misal: Retail, Manufacturing" />
                        <x-input-error :messages="$errors->get('industry')" class="mt-2" />
                    </div>

                    <div class="space-y-2">
                        <x-input-label for="website" :value="__('Website Resmi')" class="ml-1" />
                        <x-text-input id="website" name="website" type="text" class="block w-full" :value="old('website')" placeholder="https://www.example.com" />
                        <x-input-error :messages="$errors->get('website')" class="mt-2" />
                    </div>

                    <div class="space-y-2">
                        <x-input-label for="tax_id" :value="__('Tax ID / NPWP Unit')" class="ml-1" />
                        <x-text-input id="tax_id" name="tax_id" type="text" class="block w-full" :value="old('tax_id')" />
                        <x-input-error :messages="$errors->get('tax_id')" class="mt-2" />
                    </div>

                    <div class="pt-6 border-t border-gray-100 flex items-center justify-end gap-3">
                        <a href="{{ route('accounts.index') }}" class="text-gray-600 hover:underline text-sm font-bold uppercase tracking-widest">Batal</a>
                        <button type="submit" class="px-10 py-4 bg-brand text-white rounded-2xl font-black text-xs uppercase tracking-widest shadow-lg shadow-brand/20 hover:bg-brand-dark transition-all">
                            Simpan Perusahaan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
