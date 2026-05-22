<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;

class MailConfigServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Only override if settings exist in DB to avoid boot errors
        try {
            if (Setting::get('mail_override', '0') === '1') {
                // FORCE driver to SMTP to ignore .env log setting
                Config::set('mail.default', 'smtp');

                Config::set('mail.mailers.smtp.host', Setting::get('mail_host', config('mail.mailers.smtp.host')));
                Config::set('mail.mailers.smtp.port', (int) Setting::get('mail_port', config('mail.mailers.smtp.port')));
                Config::set('mail.mailers.smtp.encryption', Setting::get('mail_encryption', config('mail.mailers.smtp.encryption')));
                Config::set('mail.mailers.smtp.username', Setting::get('mail_username', config('mail.mailers.smtp.username')));
                Config::set('mail.mailers.smtp.password', Setting::get('mail_password', config('mail.mailers.smtp.password')));
                
                // Extra layer for localhost: disable SSL certificate verification
                Config::set('mail.mailers.smtp.stream', [
                    'ssl' => [
                        'allow_self_signed' => true,
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                    ],
                ]);

                Config::set('mail.from.address', Setting::get('mail_from_address', config('mail.from.address')));
                Config::set('mail.from.name', Setting::get('mail_from_name', config('mail.from.name')));
            }
        } catch (\Exception $e) {
            // Log or ignore if table doesn't exist yet
        }
    }
}
