<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit User') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('users.update', $user->id) }}">
                    @csrf
                    @method('PATCH')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Name -->
                        <div>
                            <x-input-label for="name" :value="__('Name')" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $user->name)" required />
                        </div>

                        <!-- Email -->
                        <div>
                            <x-input-label for="email" :value="__('Email')" />
                            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $user->email)" required />
                        </div>

                        <!-- WhatsApp -->
                        <div>
                            <x-input-label for="whatsapp" :value="__('WhatsApp')" />
                            <x-text-input id="whatsapp" class="block mt-1 w-full" type="text" name="whatsapp" :value="old('whatsapp', $user->whatsapp)" />
                        </div>

                        <!-- Password -->
                        <div>
                            <x-input-label for="password" :value="__('Password (Leave blank to keep current)')" />
                            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" />
                        </div>

                        <!-- Role -->
                        <div>
                            <x-input-label for="role" :value="__('Role')" />
                            <select id="role" name="role" class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand/20 focus:border-brand focus:bg-white transition-all sm:text-sm">
                                @foreach($roles as $role)
                                    <option value="{{ $role->slug }}" {{ $user->role == $role->slug ? 'selected' : '' }}>{{ $role->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Manager -->
                        <div>
                            <x-input-label for="parent_id" :value="__('Manager (Parent)')" />
                            <select id="parent_id" name="parent_id" class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand/20 focus:border-brand focus:bg-white transition-all sm:text-sm">
                                <option value="">No Manager</option>
                                @foreach($managers as $m)
                                    <option value="{{ $m->id }}" {{ $user->parent_id == $m->id ? 'selected' : '' }}>{{ $m->name }} ({{ strtoupper($m->role) }})</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Companies (Account) -->
                        <div class="md:col-span-2">
                            <x-input-label :value="__('Assign Perusahaan (Bisa Pilih Banyak)')" />
                            <div class="mt-2 grid grid-cols-2 md:grid-cols-4 gap-4 bg-gray-50 p-4 rounded-xl border border-gray-200">
                                @foreach($accounts as $acc)
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" name="account_ids[]" value="{{ $acc->id }}" {{ in_array($acc->id, $userAccountIds) ? 'checked' : '' }} class="rounded border-gray-300 text-brand focus:ring-brand">
                                        <span class="ml-2 text-sm text-gray-600">{{ $acc->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 flex items-center justify-end gap-3">
                        <a href="{{ route('users.index') }}" class="text-gray-600 hover:underline text-sm">Batal</a>
                        <x-primary-button class="bg-brand">
                            {{ __('Update User') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
