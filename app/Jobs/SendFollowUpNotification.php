<?php

namespace App\Jobs;

use App\Models\Lead;
use App\Models\Setting;
use App\Models\NotificationLog;
use App\Services\WhatsAppService;
use App\Notifications\CustomerAssignedNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendFollowUpNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $lead;

    public function __construct(Lead $lead)
    {
        $this->lead = $lead;
    }

    public function handle()
    {
        $marketing = $this->lead->user;
        if (!$marketing) return;

        $message = "REMINDER: Anda memiliki jadwal follow-up hari ini untuk Lead: " . $this->lead->name . " (" . $this->lead->phone . ")";

        // Email Notification
        if (Setting::get('notification_email_enabled') === '1' && $marketing->email) {
            try {
                // Using standard notification for simplicity or direct mail
                $marketing->notify(new \App\Notifications\FollowUpReminderNotification($this->lead));
                
                NotificationLog::create([
                    'lead_id' => $this->lead->id,
                    'user_id' => $marketing->id,
                    'channel' => 'email',
                    'recipient' => $marketing->email,
                    'message' => $message,
                    'status' => 'sent',
                ]);
            } catch (\Exception $e) {
                NotificationLog::create([
                    'lead_id' => $this->lead->id,
                    'user_id' => $marketing->id,
                    'channel' => 'email',
                    'recipient' => $marketing->email,
                    'message' => $message,
                    'status' => 'failed',
                    'error_message' => $e->getMessage(),
                ]);
            }
        }

        // WhatsApp Notification
        if (Setting::get('notification_whatsapp_enabled') === '1' && $marketing->whatsapp) {
            $waService = new WhatsAppService();
            $sent = $waService->sendMessage($marketing->whatsapp, $message);
            
            NotificationLog::create([
                'lead_id' => $this->lead->id,
                'user_id' => $marketing->id,
                'channel' => 'whatsapp',
                'recipient' => $marketing->whatsapp,
                'message' => $message,
                'status' => $sent ? 'sent' : 'failed',
                'error_message' => $sent ? null : 'API Gateway rejected request',
            ]);
        }
    }
}
