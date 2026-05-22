<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Lead Management') }}
            </h2>
            <div class="flex gap-3">
                <button type="button" @click="$dispatch('open-modal', 'import-lead-modal')" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg font-bold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 transition-all">
                    <i class="fa-solid fa-file-import mr-2"></i> Import
                </button>
                <a href="{{ route('leads.create') }}" class="inline-flex items-center px-4 py-2 bg-brand border border-transparent rounded-lg font-bold text-xs text-white uppercase tracking-widest hover:bg-opacity-90 transition-all shadow-lg shadow-brand/20">
                    <i class="fa-solid fa-plus mr-2"></i> Add Lead
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12" x-data="{ deleteUrl: '' }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Search & Filter -->
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <form action="{{ route('leads.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
                    <div class="flex-1 relative">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, perusahaan, atau email..." class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand/20 focus:border-brand transition-all text-sm font-medium">
                        <div class="absolute left-3 top-3 text-gray-400">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </div>
                    </div>
                    <button type="submit" class="px-8 py-2.5 bg-gray-900 text-white rounded-xl font-black text-xs uppercase tracking-widest hover:bg-gray-800 transition-all">Search</button>
                </form>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-black text-gray-400 uppercase tracking-widest">Lead Code</th>
                                <th class="px-6 py-4 text-left text-xs font-black text-gray-400 uppercase tracking-widest">Nama / Perusahaan</th>
                                <th class="px-6 py-4 text-left text-xs font-black text-gray-400 uppercase tracking-widest">WA</th>
                                <th class="px-6 py-4 text-left text-xs font-black text-gray-400 uppercase tracking-widest">Source</th>
                                <th class="px-6 py-4 text-left text-xs font-black text-gray-400 uppercase tracking-widest">Marketing</th>
                                <th class="px-6 py-4 text-left text-xs font-black text-gray-400 uppercase tracking-widest">Status</th>
                                <th class="px-6 py-4 text-left text-xs font-black text-gray-400 uppercase tracking-widest">Next FU</th>
                                <th class="px-6 py-4 text-right text-xs font-black text-gray-400 uppercase tracking-widest">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-50">
                            @forelse($leads as $lead)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-xs font-black text-brand uppercase">{{ $lead->lead_code }}</td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-bold text-gray-900">{{ $lead->name }}</div>
                                        <div class="text-[10px] text-gray-400 font-bold uppercase">{{ $lead->company_name ?: '-' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-brand font-bold underline">
                                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $lead->phone) }}" target="_blank">{{ $lead->phone }}</a>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-500 font-medium">{{ $lead->source }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-700 font-black uppercase">{{ $lead->user->name ?? 'Unassigned' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2.5 py-1 inline-flex text-[10px] leading-5 font-black rounded-full uppercase
                                            {{ $lead->status == 'WON' ? 'bg-green-100 text-green-700' : '' }}
                                            {{ $lead->status == 'LOST' ? 'bg-red-100 text-red-700' : '' }}
                                            {{ in_array($lead->status, ['NEW', 'CONTACTED', 'FOLLOW_UP']) ? 'bg-blue-100 text-blue-700' : '' }}
                                            {{ in_array($lead->status, ['QUALIFIED', 'PROPOSAL', 'NEGOTIATION']) ? 'bg-amber-100 text-amber-700' : '' }}
                                        ">
                                            {{ $lead->status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-xs font-black {{ $lead->next_followup_at && $lead->next_followup_at->isPast() ? 'text-red-600' : 'text-brand' }}">
                                        {{ $lead->next_followup_at?->format('d/m H:i') ?: '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex justify-end gap-3">
                                            <a href="{{ route('leads.show', $lead) }}" class="text-gray-400 hover:text-gray-600"><i class="fa-solid fa-eye"></i></a>
                                            <a href="{{ route('leads.edit', $lead) }}" class="text-brand hover:text-brand-dark"><i class="fa-solid fa-pen-to-square"></i></a>
                                            <button type="button" @click="deleteUrl = '{{ route('leads.destroy', $lead) }}'; $dispatch('open-modal', 'confirm-deletion')" class="text-red-400 hover:text-red-600">
                                                <i class="fa-solid fa-box-archive"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-20 text-center text-gray-400 italic">Belum ada data prospek.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($leads->hasPages())
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                    {{ $leads->links() }}
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
                    {{ __('Archive Lead?') }}
                </h2>

                <p class="mt-2 text-sm text-gray-500 text-center font-medium px-4">
                    {{ __('Apakah Anda yakin ingin mengarsipkan lead ini? Data tidak akan dihapus permanen.') }}
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

    <!-- Import Modal -->
    <x-modal name="import-lead-modal">
        <form action="{{ route('leads.import') }}" method="POST" enctype="multipart/form-data" class="p-8">
            @csrf
            <h2 class="text-xl font-black text-gray-900 mb-6 uppercase tracking-tight">Import Lead Master</h2>
            
            <div class="bg-brand/5 p-6 rounded-2xl border border-brand/10 mb-6">
                <p class="text-xs font-bold text-brand uppercase tracking-widest mb-4">Instruksi:</p>
                <a href="{{ route('leads.template') }}" class="inline-flex items-center text-sm font-black text-brand hover:underline">
                    <i class="fa-solid fa-download mr-2"></i> Download Template Excel
                </a>
            </div>

            <div class="space-y-2">
                <label class="block text-sm font-bold text-gray-700 ml-1">Pilih File (.xlsx)</label>
                <input type="file" name="file" required class="block w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-black file:bg-brand/10 file:text-brand hover:file:bg-brand/20 transition-all border border-gray-200 rounded-2xl p-2 bg-gray-50">
            </div>

            <div class="mt-10 flex gap-3">
                <button type="button" @click="$dispatch('close')" class="flex-1 px-4 py-3 bg-gray-100 text-gray-600 rounded-xl font-black text-xs uppercase">Batal</button>
                <button type="submit" class="flex-1 px-4 py-3 bg-brand text-white rounded-xl font-black text-xs uppercase shadow-lg shadow-brand/20">Proses Import</button>
            </div>
        </form>
    </x-modal>
</x-app-layout>
