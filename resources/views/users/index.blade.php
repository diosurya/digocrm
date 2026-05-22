<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('User Management') }}
            </h2>
            <div class="flex flex-wrap gap-3">
                <button type="button" @click="$dispatch('open-modal', 'import-user-modal')" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg font-bold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 transition-all">
                    <i class="fa-solid fa-file-import mr-2"></i> Import Excel
                </button>
                <a href="{{ route('users.create') }}" class="inline-flex items-center px-4 py-2 bg-brand border border-transparent rounded-lg font-bold text-xs text-white uppercase tracking-widest hover:bg-opacity-90 transition-all shadow-lg shadow-brand/20">
                    <i class="fa-solid fa-user-plus mr-2"></i> Add User
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12" x-data="{ deleteUrl: '' }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-xl shadow-sm" role="alert">
                    <p class="font-bold">Berhasil!</p>
                    <p class="text-sm">{{ session('success') }}</p>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100">
                <div class="p-0 overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-black text-gray-400 uppercase tracking-widest">Name / Email</th>
                                <th class="px-6 py-4 text-left text-xs font-black text-gray-400 uppercase tracking-widest">Role</th>
                                <th class="px-6 py-4 text-left text-xs font-black text-gray-400 uppercase tracking-widest">Company Units</th>
                                <th class="px-6 py-4 text-left text-xs font-black text-gray-400 uppercase tracking-widest">Manager</th>
                                <th class="px-6 py-4 text-right text-xs font-black text-gray-400 uppercase tracking-widest">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-50">
                            @foreach ($users as $u)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="h-10 w-10 rounded-xl bg-brand/10 flex items-center justify-center text-brand font-black shrink-0">
                                            {{ strtoupper(substr($u->name, 0, 1)) }}
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-bold text-gray-900">{{ $u->name }}</div>
                                            <div class="text-xs text-gray-500">{{ $u->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 inline-flex text-[10px] leading-5 font-black rounded-full bg-brand/5 text-brand uppercase">
                                        {{ str_replace('_', ' ', $u->role) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-600 font-medium">
                                    @forelse($u->accounts as $acc)
                                        <span class="bg-gray-100 px-2 py-0.5 rounded mr-1">{{ $acc->name }}</span>
                                    @empty
                                        <span class="text-gray-300 italic">No Unit Assigned</span>
                                    @endforelse
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    @if($u->manager)
                                        <span class="font-bold text-gray-700">{{ $u->manager->name }}</span>
                                    @else
                                        <span class="text-gray-300 italic">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end gap-3">
                                        <a href="{{ route('users.edit', $u->id) }}" class="text-brand hover:text-brand-dark transition-colors"><i class="fa-solid fa-pen-to-square"></i></a>
                                        @if(auth()->id() !== $u->id)
                                        <button type="button" @click="deleteUrl = '{{ route('users.destroy', $u->id) }}'; $dispatch('open-modal', 'confirm-deletion')" class="text-red-400 hover:text-red-600">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if($users->hasPages())
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                    {{ $users->links() }}
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
                    <i class="fa-solid fa-user-slash text-2xl"></i>
                </div>

                <h2 class="text-xl font-black text-gray-900 text-center uppercase tracking-tight">
                    {{ __('Remove User?') }}
                </h2>

                <p class="mt-2 text-sm text-gray-500 text-center font-medium px-4">
                    {{ __('Apakah Anda yakin ingin menghapus user ini? Akses mereka ke sistem akan segera dicabut.') }}
                </p>

                <div class="mt-10 flex gap-3">
                    <button type="button" @click="$dispatch('close')" class="flex-1 py-4 bg-gray-100 text-gray-600 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-gray-200 transition-all">
                        {{ __('Cancel') }}
                    </button>

                    <button type="submit" class="flex-1 py-4 bg-red-600 text-white rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-red-700 transition-all shadow-lg shadow-red-200">
                        {{ __('Yes, Remove') }}
                    </button>
                </div>
            </form>
        </x-modal>
    </div>

    <!-- Import Modal -->
    <x-modal name="import-user-modal">
        <form action="{{ route('users.import') }}" method="POST" enctype="multipart/form-data" class="p-8">
            @csrf
            <div class="mb-6 flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-brand/10 flex items-center justify-center text-brand">
                    <i class="fa-solid fa-users-gear text-2xl"></i>
                </div>
                <div>
                    <h3 class="text-lg font-black text-gray-900 tracking-tight uppercase">Import User Master</h3>
                    <p class="text-sm text-gray-400 font-medium">Unggah database marketing dari Excel.</p>
                </div>
            </div>

            <div class="bg-brand/5 p-6 rounded-2xl border border-brand/10 mb-6">
                <p class="text-xs font-bold text-brand uppercase tracking-widest mb-2">Petunjuk Penting:</p>
                <ul class="text-xs text-gray-600 space-y-2 list-disc list-inside">
                    <li>Gunakan template resmi untuk menghindari error.</li>
                    <li>Pastikan email user belum pernah terdaftar di sistem.</li>
                    <li>Satu user bisa memiliki banyak perusahaan (pisahkan dengan koma).</li>
                </ul>
                <div class="mt-4">
                    <a href="{{ route('users.template') }}" class="text-sm font-black text-brand hover:underline flex items-center gap-2">
                        <i class="fa-solid fa-download"></i> Download Template Marketing
                    </a>
                </div>
            </div>

            <div class="space-y-2">
                <label class="block text-sm font-bold text-gray-700 ml-1">Pilih File Excel (.xlsx)</label>
                <input type="file" name="file" required class="block w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-black file:bg-brand/10 file:text-brand hover:file:bg-brand/20 transition-all border border-gray-200 rounded-2xl p-2 bg-gray-50">
            </div>

            <div class="mt-10 flex gap-3">
                <button type="button" @click="$dispatch('close')" class="flex-1 px-4 py-3 bg-gray-100 border border-gray-200 rounded-xl font-bold text-xs text-gray-600 uppercase tracking-widest hover:bg-gray-200 transition-all">
                    Batal
                </button>
                <button type="submit" class="flex-1 px-4 py-3 bg-brand border border-transparent rounded-xl font-bold text-xs text-white uppercase tracking-widest hover:bg-opacity-90 transition-all shadow-lg shadow-brand/20">
                    Mulai Import
                </button>
            </div>
        </form>
    </x-modal>
</x-app-layout>
