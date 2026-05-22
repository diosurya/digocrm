<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Internal Tasks') }}
            </h2>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('tasks.export') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg font-bold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 transition-all">
                    <i class="fa-solid fa-file-export mr-2"></i> Export
                </a>
                <a href="{{ route('tasks.create') }}" class="inline-flex items-center px-4 py-2 bg-brand border border-transparent rounded-lg font-bold text-xs text-white uppercase tracking-widest hover:bg-opacity-90 transition-all shadow-lg shadow-brand/20">
                    <i class="fa-solid fa-plus mr-2"></i> New Task
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12" x-data="{ deleteUrl: '' }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Filters -->
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <form action="{{ route('tasks.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="md:col-span-2 relative">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul tugas..." class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand/20 focus:border-brand transition-all text-sm">
                        <div class="absolute left-3 top-3 text-gray-400">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </div>
                    </div>
                    <select name="status" class="block w-full px-3 py-2.5 border border-gray-200 rounded-xl bg-gray-50 focus:ring-2 focus:ring-brand/20 focus:border-brand sm:text-sm transition-all text-gray-700 font-bold uppercase">
                        <option value="">All Status</option>
                        <option value="TODO" {{ request('status') == 'TODO' ? 'selected' : '' }}>To Do</option>
                        <option value="IN_PROGRESS" {{ request('status') == 'IN_PROGRESS' ? 'selected' : '' }}>In Progress</option>
                        <option value="DONE" {{ request('status') == 'DONE' ? 'selected' : '' }}>Done</option>
                        <option value="OVERDUE" {{ request('status') == 'OVERDUE' ? 'selected' : '' }}>Overdue</option>
                    </select>
                    <button type="submit" class="w-full bg-gray-900 text-white rounded-xl font-black text-xs uppercase tracking-widest hover:bg-gray-800 transition-all">Filter</button>
                </form>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100">
                <div class="p-0 overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-black text-gray-400 uppercase tracking-widest">Task Details</th>
                                <th class="px-6 py-4 text-left text-xs font-black text-gray-400 uppercase tracking-widest">Deadline</th>
                                <th class="px-6 py-4 text-left text-xs font-black text-gray-400 uppercase tracking-widest">Priority</th>
                                <th class="px-6 py-4 text-left text-xs font-black text-gray-400 uppercase tracking-widest">Status</th>
                                <th class="px-6 py-4 text-right text-xs font-black text-gray-400 uppercase tracking-widest">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-50">
                            @forelse($tasks as $task)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="text-sm font-bold text-gray-900">{{ $task->title }}</div>
                                    <div class="text-xs text-gray-500 line-clamp-1 italic">{{ $task->description ?: 'No description' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-black {{ $task->due_date && $task->due_date->isPast() && $task->status != 'DONE' ? 'text-red-600' : 'text-gray-700' }}">
                                        {{ $task->due_date ? $task->due_date->format('d M Y') : 'No Date' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2.5 py-1 inline-flex text-[10px] leading-5 font-black rounded-full uppercase
                                        {{ $task->priority == 'HIGH' ? 'bg-red-100 text-red-700' : ($task->priority == 'MEDIUM' ? 'bg-amber-100 text-amber-700' : 'bg-green-100 text-green-700') }}">
                                        {{ $task->priority }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2.5 py-1 inline-flex text-[10px] leading-5 font-black rounded-full uppercase
                                        {{ $task->status == 'DONE' ? 'bg-green-100 text-green-700' : ($task->status == 'OVERDUE' ? 'bg-red-100 text-red-700' : 'bg-blue-100 text-blue-700') }}">
                                        {{ $task->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end gap-3">
                                        <form action="{{ route('tasks.toggle', $task) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="text-green-500 hover:text-green-700" title="Toggle Status">
                                                <i class="fa-solid {{ $task->status == 'DONE' ? 'fa-rotate-left' : 'fa-check-double' }}"></i>
                                            </button>
                                        </form>
                                        <a href="{{ route('tasks.edit', $task) }}" class="text-brand hover:text-brand-dark"><i class="fa-solid fa-pen-to-square"></i></a>
                                        <button type="button" @click="deleteUrl = '{{ route('tasks.destroy', $task) }}'; $dispatch('open-modal', 'confirm-deletion')" class="text-red-400 hover:text-red-600">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-20 text-center text-gray-400 italic">Belum ada daftar tugas.</td>
                            </tr>
                            @endforelse
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
                    <i class="fa-solid fa-trash-can text-2xl"></i>
                </div>

                <h2 class="text-xl font-black text-gray-900 text-center uppercase tracking-tight">
                    {{ __('Delete Task?') }}
                </h2>

                <p class="mt-2 text-sm text-gray-500 text-center font-medium px-4">
                    {{ __('Apakah Anda yakin ingin menghapus tugas ini?') }}
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
