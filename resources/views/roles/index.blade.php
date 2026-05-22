<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Role Management') }}
            </h2>
            <a href="{{ route('roles.create') }}" class="inline-flex items-center px-4 py-2 bg-brand border border-transparent rounded-lg font-bold text-xs text-white uppercase tracking-widest hover:bg-opacity-90 transition-all shadow-lg shadow-brand/20">
                <i class="fa-solid fa-plus mr-2"></i> Add Role
            </a>
        </div>
    </x-slot>

    <div class="py-12" x-data="{ deleteUrl: '' }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-xl shadow-sm" role="alert">
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100">
                <div class="p-0 overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-black text-gray-400 uppercase tracking-widest">Role Name</th>
                                <th class="px-6 py-4 text-left text-xs font-black text-gray-400 uppercase tracking-widest">Slug</th>
                                <th class="px-6 py-4 text-left text-xs font-black text-gray-400 uppercase tracking-widest">Description</th>
                                <th class="px-6 py-4 text-right text-xs font-black text-gray-400 uppercase tracking-widest">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-50">
                            @foreach ($roles as $role)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-bold text-gray-900">{{ $role->name }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <code class="px-2 py-1 bg-brand/5 text-brand rounded text-xs font-black uppercase">{{ $role->slug }}</code>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-xs text-gray-500 line-clamp-1 italic">{{ $role->description ?: 'No description provided.' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end gap-3">
                                        <a href="{{ route('roles.edit', $role->id) }}" class="text-brand hover:text-brand-dark transition-colors"><i class="fa-solid fa-pen-to-square"></i></a>
                                        @if(!in_array($role->slug, ['superadmin', 'manager_marketing', 'marketing']))
                                        <button type="button" @click="deleteUrl = '{{ route('roles.destroy', $role->id) }}'; $dispatch('open-modal', 'confirm-deletion')" class="text-red-400 hover:text-red-600">
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
            </div>
        </div>

        <!-- Confirm Deletion Modal -->
        <x-modal name="confirm-deletion" focusable>
            <form :action="deleteUrl" method="POST" class="p-8">
                @csrf
                @method('DELETE')
                
                <div class="mb-6 flex items-center justify-center w-16 h-16 bg-red-100 text-red-600 rounded-2xl mx-auto">
                    <i class="fa-solid fa-shield-halved text-2xl"></i>
                </div>

                <h2 class="text-xl font-black text-gray-900 text-center uppercase tracking-tight">
                    {{ __('Remove Role?') }}
                </h2>

                <p class="mt-2 text-sm text-gray-500 text-center font-medium px-4">
                    {{ __('Apakah Anda yakin ingin menghapus role ini? User dengan role ini mungkin akan kehilangan akses.') }}
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
</x-app-layout>
