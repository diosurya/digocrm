<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Account;
use App\Models\Setting;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Default Roles
        $roles = [
            ['name' => 'Super Admin', 'slug' => 'superadmin', 'description' => 'Akses penuh ke seluruh sistem.'],
            ['name' => 'Manager Marketing', 'slug' => 'manager_marketing', 'description' => 'Melihat data tim dan eskalasi.'],
            ['name' => 'Marketing', 'slug' => 'marketing', 'description' => 'Mengelola lead dan aktivitas pribadi.'],
        ];
        foreach ($roles as $role) {
            Role::updateOrCreate(['slug' => $role['slug']], $role);
        }

        // 2. Super Admin
        $superadmin = User::updateOrCreate(['email' => 'admin@digosoft.id'], [
            'name' => 'Super Admin',
            'password' => Hash::make('password'),
            'role' => 'superadmin',
        ]);

        // 3. System Settings
        $settings = [
            ['key' => 'notification_email_enabled', 'value' => '1', 'group' => 'general'],
            ['key' => 'notification_whatsapp_enabled', 'value' => '0', 'group' => 'general'],
            ['key' => 'mail_override', 'value' => '0', 'group' => 'email'],
            ['key' => 'mail_host', 'value' => 'smtp.hostinger.com', 'group' => 'email'],
            ['key' => 'mail_port', 'value' => '465', 'group' => 'email'],
            ['key' => 'mail_encryption', 'value' => 'ssl', 'group' => 'email'],
            ['key' => 'mail_username', 'value' => 'support@digosoft.id', 'group' => 'email'],
            ['key' => 'mail_password', 'value' => '0Ly74a!kO=h%', 'group' => 'email'],
            ['key' => 'mail_from_address', 'value' => 'support@digosoft.id', 'group' => 'email'],
            ['key' => 'mail_from_name', 'value' => 'DigoSoft', 'group' => 'email'],
            ['key' => 'whatsapp_api_gateway', 'value' => 'https://api.whatsapp.com/send', 'group' => 'whatsapp'],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(['key' => $setting['key']], $setting);
        }
    }
}
