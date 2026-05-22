<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('System Settings') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-xl shadow-sm" role="alert">
                    <p class="font-bold">Success!</p>
                    <p class="text-sm">{{ session('success') }}</p>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-xl shadow-sm" role="alert">
                    <p class="font-bold">Error!</p>
                    <p class="text-sm">{{ session('error') }}</p>
                </div>
            @endif

            <form method="POST" action="{{ route('settings.update') }}">
                @csrf
                
                <!-- Notification Settings -->
                <div class="p-8 bg-white shadow-sm border border-gray-100 rounded-3xl">
                    <div class="max-w-xl">
                        <header>
                            <h2 class="text-lg font-black text-gray-900 uppercase tracking-tight flex items-center gap-2">
                                <i class="fa-solid fa-bell text-brand"></i>
                                {{ __('Notification Channels') }}
                            </h2>
                            <p class="mt-1 text-sm text-gray-500 font-medium">
                                {{ __('Enable or disable notification channels for lead assignments.') }}
                            </p>
                        </header>

                        <div class="mt-8 space-y-4">
                            <div class="flex items-center">
                                <input id="notification_email_enabled" name="notification_email_enabled" type="checkbox" value="1" {{ \App\Models\Setting::get('notification_email_enabled') == '1' ? 'checked' : '' }} class="h-5 w-5 text-brand border-gray-300 rounded-lg focus:ring-brand transition-all cursor-pointer">
                                <label for="notification_email_enabled" class="ml-3 block text-sm font-bold text-gray-700 cursor-pointer">
                                    {{ __('Enable Email Notifications') }}
                                </label>
                            </div>

                            <div class="flex items-center">
                                <input id="notification_whatsapp_enabled" name="notification_whatsapp_enabled" type="checkbox" value="1" {{ \App\Models\Setting::get('notification_whatsapp_enabled') == '1' ? 'checked' : '' }} class="h-5 w-5 text-brand border-gray-300 rounded-lg focus:ring-brand transition-all cursor-pointer">
                                <label for="notification_whatsapp_enabled" class="ml-3 block text-sm font-bold text-gray-700 cursor-pointer">
                                    {{ __('Enable WhatsApp Notifications') }}
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Email Setup (SMTP) -->
                <div class="mt-6 p-8 bg-white shadow-sm border border-gray-100 rounded-3xl">
                    <div class="max-w-xl">
                        <header class="flex justify-between items-start">
                            <div>
                                <h2 class="text-lg font-black text-gray-900 uppercase tracking-tight flex items-center gap-2">
                                    <i class="fa-solid fa-envelope-open-text text-brand"></i>
                                    {{ __('Email Setup (SMTP)') }}
                                </h2>
                                <p class="mt-1 text-sm text-gray-500 font-medium">
                                    {{ __('Configure your SMTP server to override default environment settings.') }}
                                </p>
                            </div>
                            <button type="button" @click="$dispatch('open-modal', 'test-email-modal')" class="px-4 py-2 bg-gray-50 border border-gray-200 text-gray-600 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-brand hover:text-white hover:border-brand transition-all">
                                <i class="fa-solid fa-paper-plane mr-1"></i> Test Email
                            </button>
                        </header>

                        <div class="mt-10 space-y-6">
                            <div class="flex items-center p-4 bg-red-50 border border-red-100 rounded-2xl">
                                <input id="mail_override" name="mail_override" type="checkbox" value="1" {{ \App\Models\Setting::get('mail_override') == '1' ? 'checked' : '' }} class="h-5 w-5 text-brand border-gray-300 rounded-lg focus:ring-brand">
                                <label for="mail_override" class="ml-3 block text-xs font-black text-red-600 uppercase tracking-widest cursor-pointer">
                                    {{ __('ACTIVATE SMTP OVERRIDE') }}
                                </label>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <x-input-label for="mail_host" :value="__('SMTP Host')" class="ml-1" />
                                    <x-text-input id="mail_host" name="mail_host" type="text" :value="\App\Models\Setting::get('mail_host')" placeholder="smtp.gmail.com" />
                                </div>

                                <div class="space-y-2">
                                    <x-input-label for="mail_port" :value="__('SMTP Port')" class="ml-1" />
                                    <x-text-input id="mail_port" name="mail_port" type="text" :value="\App\Models\Setting::get('mail_port')" placeholder="587" />
                                </div>

                                <div class="space-y-2">
                                    <x-input-label for="mail_encryption" :value="__('Encryption')" class="ml-1" />
                                    <select name="mail_encryption" class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand/20 focus:border-brand transition-all sm:text-sm">
                                        <option value="tls" {{ \App\Models\Setting::get('mail_encryption') == 'tls' ? 'selected' : '' }}>TLS</option>
                                        <option value="ssl" {{ \App\Models\Setting::get('mail_encryption') == 'ssl' ? 'selected' : '' }}>SSL</option>
                                        <option value="none" {{ \App\Models\Setting::get('mail_encryption') == 'none' ? 'selected' : '' }}>None</option>
                                    </select>
                                </div>

                                <div class="space-y-2">
                                    <x-input-label for="mail_username" :value="__('Username')" class="ml-1" />
                                    <x-text-input id="mail_username" name="mail_username" type="text" :value="\App\Models\Setting::get('mail_username')" />
                                </div>

                                <div class="space-y-2 md:col-span-2">
                                    <x-input-label for="mail_password" :value="__('Password')" class="ml-1" />
                                    <x-text-input id="mail_password" name="mail_password" type="password" :value="\App\Models\Setting::get('mail_password')" />
                                </div>

                                <div class="space-y-2">
                                    <x-input-label for="mail_from_address" :value="__('From Email')" class="ml-1" />
                                    <x-text-input id="mail_from_address" name="mail_from_address" type="email" :value="\App\Models\Setting::get('mail_from_address')" />
                                </div>

                                <div class="space-y-2">
                                    <x-input-label for="mail_from_name" :value="__('From Name')" class="ml-1" />
                                    <x-text-input id="mail_from_name" name="mail_from_name" type="text" :value="\App\Models\Setting::get('mail_from_name')" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- WhatsApp Setup -->
                <div class="mt-6 p-8 bg-white shadow-sm border border-gray-100 rounded-3xl">
                    <div class="max-w-xl">
                        <header>
                            <h2 class="text-lg font-black text-gray-900 uppercase tracking-tight flex items-center gap-2">
                                <i class="fa-brands fa-whatsapp text-green-500"></i>
                                {{ __('WhatsApp Setup') }}
                            </h2>
                            <p class="mt-1 text-sm text-gray-500 font-medium">
                                {{ __('Configure your WhatsApp API Gateway (e.g., Fonnte, Wablas).') }}
                            </p>
                        </header>

                        <div class="mt-10 space-y-6">
                            <div class="space-y-2">
                                <x-input-label for="whatsapp_api_gateway" :value="__('API Gateway URL')" class="ml-1" />
                                <x-text-input id="whatsapp_api_gateway" name="whatsapp_api_gateway" type="text" :value="\App\Models\Setting::get('whatsapp_api_gateway')" placeholder="https://api.fonnte.com/send" />
                            </div>

                            <div class="space-y-2">
                                <x-input-label for="whatsapp_api_token" :value="__('API Token / Secret')" class="ml-1" />
                                <x-text-input id="whatsapp_api_token" name="whatsapp_api_token" type="password" :value="\App\Models\Setting::get('whatsapp_api_token')" />
                            </div>

                            <div class="space-y-2">
                                <x-input-label for="whatsapp_sender_number" :value="__('Sender Number')" class="ml-1" />
                                <x-text-input id="whatsapp_sender_number" name="whatsapp_sender_number" type="text" :value="\App\Models\Setting::get('whatsapp_sender_number')" placeholder="e.g. 62812345678" />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-10 flex items-center gap-4">
                    <button type="submit" class="px-12 py-4 bg-brand text-white rounded-2xl font-black uppercase tracking-widest hover:bg-opacity-90 transition-all shadow-xl shadow-brand/20 active:scale-95 transform">
                        {{ __('Save All Settings') }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Test Email Modal -->
    <x-modal name="test-email-modal" focusable>
        <form action="{{ route('settings.test-email') }}" method="POST" class="p-8">
            @csrf
            <h2 class="text-xl font-black text-gray-900 mb-2 uppercase tracking-tight">Test SMTP Configuration</h2>
            <p class="text-sm text-gray-500 mb-8 font-medium">Masukkan email tujuan untuk mencoba pengiriman email dari sistem.</p>

            <div class="space-y-2">
                <label for="test_email" class="block text-xs font-black text-gray-400 uppercase tracking-widest ml-1">Recipient Email Address</label>
                <input id="test_email" name="email" type="email" required class="block w-full px-4 py-4 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-4 focus:ring-brand/10 focus:border-brand focus:bg-white transition-all outline-none text-sm font-bold text-gray-700 shadow-sm" placeholder="user@example.com">
            </div>

            <div class="mt-10 flex gap-3">
                <button type="button" @click="$dispatch('close')" class="flex-1 py-4 bg-gray-100 text-gray-600 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-gray-200 transition-all">Cancel</button>
                <button type="submit" class="flex-1 py-4 bg-brand text-white rounded-2xl font-black text-xs uppercase tracking-widest shadow-lg shadow-brand/20 hover:bg-opacity-90 transition-all">Send Test Email</button>
            </div>
        </form>
    </x-modal>
</x-app-layout>
