<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Activity / Follow Up') }}
            </h2>
            <a href="{{ route('activities.create') }}" class="inline-flex items-center px-6 py-2.5 bg-brand text-white rounded-xl font-black text-xs uppercase tracking-widest hover:bg-opacity-90 transition-all shadow-lg shadow-brand/20">
                <i class="fa-solid fa-plus mr-2"></i> Log Activity
            </a>
        </div>
    </x-slot>

    <div class="py-12" x-data="{ deleteUrl: '' }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100">
                <div class="p-0 overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-black text-gray-400 uppercase tracking-widest">Date / PIC</th>
                                <th class="px-6 py-4 text-left text-xs font-black text-gray-400 uppercase tracking-widest">Target Entity</th>
                                <th class="px-6 py-4 text-left text-xs font-black text-gray-400 uppercase tracking-widest">Type / Status</th>
                                <th class="px-6 py-4 text-left text-xs font-black text-gray-400 uppercase tracking-widest">Result Summary</th>
                                <th class="px-6 py-4 text-right text-xs font-black text-gray-400 uppercase tracking-widest">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-50">
                            @forelse($activities as $act)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-bold text-gray-900">{{ $act->created_at->format('d/m/Y') }}</div>
                                    <div class="text-[10px] text-gray-400 font-black uppercase">{{ $act->user->name }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-bold text-gray-800">{{ $act->activitable->name ?? 'N/A' }}</div>
                                    <div class="text-[10px] text-brand font-black uppercase">{{ class_basename($act->activitable_type) }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-2">
                                        <span class="px-2 py-0.5 rounded bg-gray-100 text-[10px] font-black text-gray-600 uppercase">{{ $act->activity_type }}</span>
                                        <span class="px-2 py-0.5 rounded text-[10px] font-black uppercase 
                                            {{ $act->status == 'DONE' ? 'bg-green-100 text-green-700' : 'bg-amber-100 text-amber-700' }}">
                                            {{ $act->status }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-xs text-gray-600 line-clamp-2 italic">"{{ $act->result }}"</p>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end gap-3">
                                        <button type="button" @click="deleteUrl = '{{ route('activities.destroy', $act) }}'; $dispatch('open-modal', 'confirm-deletion')" class="text-red-400 hover:text-red-600 transition-colors">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-20 text-center text-gray-400 italic">Belum ada catatan aktivitas.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($activities->hasPages())
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                    {{ $activities->links() }}
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
                    <i class="fa-solid fa-calendar-xmark text-2xl"></i>
                </div>

                <h2 class="text-xl font-black text-gray-900 text-center uppercase tracking-tight">
                    {{ __('Delete Activity?') }}
                </h2>

                <p class="mt-2 text-sm text-gray-500 text-center font-medium px-4">
                    {{ __('Apakah Anda yakin ingin menghapus log aktivitas ini?') }}
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
