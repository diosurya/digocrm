<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Role: ') . $role->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('roles.update', $role->id) }}">
                    @csrf
                    @method('PATCH')

                    <div class="space-y-6">
                        <!-- Name -->
                        <div>
                            <x-input-label for="name" :value="__('Nama Role')" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $role->name)" required autofocus />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            @if(in_array($role->slug, ['superadmin', 'manager_marketing', 'marketing']))
                                <p class="mt-1 text-[10px] text-red-500 italic">* Role sistem: Hanya nama tampilan yang bisa diubah, Slug terkunci.</p>
                            @endif
                        </div>

                        <!-- Description -->
                        <div>
                            <x-input-label for="description" :value="__('Deskripsi')" />
                            <textarea id="description" name="description" rows="4" class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand/20 focus:border-brand focus:bg-white transition-all sm:text-sm">{{ old('description', $role->description) }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>
                    </div>

                    <div class="mt-8 flex items-center justify-end gap-3">
                        <a href="{{ route('roles.index') }}" class="text-gray-600 hover:underline text-sm">Batal</a>
                        <x-primary-button class="bg-brand">
                            {{ __('Update Role') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
