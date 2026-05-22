<x-app-layout>
    @section('title', 'Tambah Tugas Baru')

    <div class="max-w-4xl mx-auto space-y-6">
        <div class="flex items-center gap-4">
            <a href="{{ route('tasks.index') }}" class="text-gray-400 hover:text-gray-600 transition-colors">
                <i class="fa-solid fa-arrow-left text-xl"></i>
            </a>
            <h2 class="text-2xl font-bold text-gray-900">Tambah Tugas Baru</h2>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <form action="{{ route('tasks.store') }}" method="POST" class="p-8 space-y-8">
                @csrf
                <div class="space-y-8">
                    <div class="space-y-2">
                        <label class="block text-sm font-bold text-gray-700 ml-1" for="title">Judul Tugas</label>
                        <input type="text" name="title" id="title" class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand/20 focus:border-brand focus:bg-white transition-all sm:text-sm" placeholder="e.g. Hubungi Pak Budi" required>
                        @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    
                    <div class="space-y-2">
                        <label class="block text-sm font-bold text-gray-700 ml-1" for="description">Deskripsi</label>
                        <textarea name="description" id="description" rows="3" class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand/20 focus:border-brand focus:bg-white transition-all sm:text-sm" placeholder="Detail tugas..."></textarea>
                        @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <div class="space-y-2">
                            <label class="block text-sm font-bold text-gray-700 ml-1" for="due_date">Deadline</label>
                            <input type="date" name="due_date" id="due_date" class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand/20 focus:border-brand focus:bg-white transition-all sm:text-sm">
                            @error('due_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        
                        <div class="space-y-2">
                            <label class="block text-sm font-bold text-gray-700 ml-1" for="priority">Prioritas</label>
                            <select name="priority" id="priority" class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand/20 focus:border-brand focus:bg-white transition-all sm:text-sm text-gray-700">
                                <option value="LOW">Low</option>
                                <option value="MEDIUM" selected>Medium</option>
                                <option value="HIGH">High</option>
                            </select>
                            @error('priority') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="space-y-2">
                            <label class="block text-sm font-bold text-gray-700 ml-1" for="status">Status Awal</label>
                            <select name="status" id="status" class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand/20 focus:border-brand focus:bg-white transition-all sm:text-sm text-gray-700">
                                <option value="TODO">To Do</option>
                                <option value="IN_PROGRESS">In Progress</option>
                                <option value="DONE">Done</option>
                            </select>
                            @error('status') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <div class="pt-6 border-t border-gray-100 flex flex-col sm:flex-row justify-end gap-3">
                    <a href="{{ route('tasks.index') }}" class="inline-flex items-center justify-center px-6 py-3 bg-white border border-gray-300 rounded-xl font-bold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50 transition-all">
                        Batal
                    </a>
                    <button type="submit" class="inline-flex items-center justify-center px-10 py-3 bg-brand border border-transparent rounded-xl font-bold text-xs text-white uppercase tracking-widest hover:bg-opacity-90 transition-all shadow-sm">
                        Simpan Tugas
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
