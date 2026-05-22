<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Master Perusahaan (Units)') }}
            </h2>
            <a href="{{ route('accounts.create') }}" class="inline-flex items-center px-4 py-2 bg-brand border border-transparent rounded-lg font-bold text-xs text-white uppercase tracking-widest hover:bg-opacity-90 transition-all shadow-lg shadow-brand/20">
                <i class="fa-solid fa-plus mr-2"></i> Tambah Perusahaan
            </a>
        </div>
    </x-slot>

    <div class="py-12" x-data="{ deleteUrl: '' }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-xl shadow-sm">
                    <p class="font-bold">Berhasil!</p>
                    <p class="text-sm">{{ session('success') }}</p>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-xl shadow-sm">
                    <p class="font-bold">Gagal!</p>
                    <p class="text-sm">{{ session('error') }}</p>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-[2rem] border border-gray-100">
                <div class="p-0 overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-black text-gray-400 uppercase tracking-widest">Nama Perusahaan</th>
                                <th class="px-6 py-4 text-left text-xs font-black text-gray-400 uppercase tracking-widest">Industri</th>
                                <th class="px-6 py-4 text-center text-xs font-black text-gray-400 uppercase tracking-widest">Marketing</th>
                                <th class="px-6 py-4 text-center text-xs font-black text-gray-400 uppercase tracking-widest">Customer</th>
                                <th class="px-6 py-4 text-right text-xs font-black text-gray-400 uppercase tracking-widest">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-50">
                            @foreach ($accounts as $account)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="h-10 w-10 rounded-xl bg-brand/10 flex items-center justify-center text-brand font-black shrink-0">
                                            {{ strtoupper(substr($account->name, 0, 1)) }}
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-bold text-gray-900">{{ $account->name }}</div>
                                            <div class="text-xs text-gray-400 font-medium">{{ $account->website ?: 'No website' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-xs font-bold text-gray-600 uppercase">{{ $account->industry ?: 'General' }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="px-2.5 py-0.5 rounded-full bg-gray-100 text-gray-600 text-xs font-black">{{ $account->users_count }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="px-2.5 py-0.5 rounded-full bg-brand/10 text-brand text-xs font-black">{{ $account->contacts_count }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end gap-3">
                                        <a href="{{ route('accounts.edit', $account) }}" class="text-brand hover:text-brand-dark"><i class="fa-solid fa-pen-to-square"></i></a>
                                        <button type="button" @click="deleteUrl = '{{ route('accounts.destroy', $account) }}'; $dispatch('open-modal', 'confirm-deletion')" class="text-red-400 hover:text-red-600">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Delete Modal -->
        <x-modal name="confirm-deletion" focusable>
            <form :action="deleteUrl" method="POST" class="p-8">
                @csrf
                @method('DELETE')
                
                <div class="mb-6 flex items-center justify-center w-16 h-16 bg-red-100 text-red-600 rounded-2xl mx-auto">
                    <i class="fa-solid fa-building-circle-exclamation text-2xl"></i>
                </div>

                <h2 class="text-xl font-black text-gray-900 text-center uppercase tracking-tight">Hapus Unit Perusahaan?</h2>
                <p class="mt-2 text-sm text-gray-500 text-center font-medium px-4">
                    Menghapus perusahaan ini akan memutuskan hubungan seluruh marketing dari unit ini. Pastikan tidak ada customer aktif di dalamnya.
                </p>

                <div class="mt-10 flex gap-3">
                    <button type="button" @click="$dispatch('close')" class="flex-1 py-4 bg-gray-100 text-gray-600 rounded-2xl font-black text-xs uppercase hover:bg-gray-200 transition-all">Batal</button>
                    <button type="submit" class="flex-1 py-4 bg-red-600 text-white rounded-2xl font-black text-xs uppercase hover:bg-red-700 transition-all shadow-lg">Ya, Hapus Unit</button>
                </div>
            </form>
        </x-modal>
    </div>
</x-app-layout>
