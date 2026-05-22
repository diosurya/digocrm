<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class WhatsAppService
{
    /**
     * Send a WhatsApp message using configured settings.
     */
    public function sendMessage($to, $message)
    {
        $enabled = Setting::get('notification_whatsapp_enabled', '0');
        
        if ($enabled !== '1') {
            return false;
        }

        $gateway = Setting::get('whatsapp_api_gateway');
        $token = Setting::get('whatsapp_api_token');
        $sender = Setting::get('whatsapp_sender_number');

        if (!$gateway || !$to) {
            Log::warning("WhatsApp Notification failed: Missing Gateway URL or Recipient.");
            return false;
        }

        // Standard Enterprise Integration Pattern
        // This is where you would call Fonnte, Wablas, or Twilio
        Log::info("WhatsApp Sending Logic Triggered", [
            'to' => $to,
            'gateway' => $gateway,
            'message' => $message,
            'sender' => $sender
        ]);

        /* 
        Example Implementation for a typical REST API Gateway:
        $response = Http::withHeaders(['Authorization' => $token])
            ->post($gateway, [
                'target' => $to,
                'message' => $message,
                'sender' => $sender
            ]);
        return $response->successful();
        */

        return true;
    }
}
