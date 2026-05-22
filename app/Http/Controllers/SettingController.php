<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->groupBy('group');
        return view('settings.index', compact('settings'));
    }

    public function logs()
    {
        $logs = \App\Models\NotificationLog::latest()->paginate(20);
        return view('settings.logs', compact('logs'));
    }

    public function update(Request $request)
    {
        $data = $request->except('_token');

        foreach ($data as $key => $value) {
            $group = 'general';
            if (str_contains($key, 'mail_')) $group = 'email';
            if (str_contains($key, 'notification_email')) $group = 'email';
            if (str_contains($key, 'whatsapp_')) $group = 'whatsapp';
            if (str_contains($key, 'notification_whatsapp')) $group = 'whatsapp';

            Setting::set($key, $value, $group);
        }

        return redirect()->back()->with('success', 'Settings updated successfully.');
    }

    public function testEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        try {
            // Force dynamic config right before sending
            config([
                'mail.mailers.smtp_test' => [
                    'transport' => 'smtp',
                    'host' => Setting::get('mail_host'),
                    'port' => (int) Setting::get('mail_port'),
                    'encryption' => Setting::get('mail_encryption'),
                    'username' => Setting::get('mail_username'),
                    'password' => Setting::get('mail_password'),
                    'timeout' => 10,
                    'stream' => [
                        'ssl' => [
                            'allow_self_signed' => true,
                            'verify_peer' => false,
                            'verify_peer_name' => false,
                        ],
                    ],
                ],
                'mail.from.address' => Setting::get('mail_from_address'),
                'mail.from.name' => Setting::get('mail_from_name', 'DigoCRM Test'),
            ]);

            // Use the newly created temporary mailer
            \Illuminate\Support\Facades\Mail::mailer('smtp_test')->raw('DigoCRM SMTP Test Success! If you see this, your settings are 100% correct.', function ($message) use ($request) {
                $message->to($request->email)
                        ->subject('DigoCRM SMTP Connection Test');
            });

            return redirect()->back()->with('success', 'REAL-TIME TEST SUCCESS: Email has been sent using the current settings.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('SMTP Test Failure: ' . $e->getMessage());
            return redirect()->back()->with('error', 'SMTP FAILED: ' . $e->getMessage());
        }
    }
}
