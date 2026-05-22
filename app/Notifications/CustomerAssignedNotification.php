<?php

namespace App\Notifications;

use App\Models\Setting;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CustomerAssignedNotification extends Notification
{
    use Queueable;

    protected $target;
    protected $typeLabel;

    /**
     * Accepts both Customer or Lead objects.
     */
    public function __construct($target)
    {
        $this->target = $target;
        $this->typeLabel = (class_basename($target) === 'Lead') ? 'Prospek (Lead)' : 'Pelanggan (Customer)';
    }

    public function via($notifiable): array
    {
        $channels = ['database']; // Always store in DB for Notification Center
        
        // Check Superadmin settings
        if (Setting::get('notification_email_enabled', '0') === '1' && $notifiable->email) {
            $channels[] = 'mail';
        }
        
        if (Setting::get('notification_whatsapp_enabled', '0') === '1' && $notifiable->whatsapp) {
            // Trigger WhatsApp Service
            try {
                $waService = new \App\Services\WhatsAppService();
                $message = "Halo " . $notifiable->name . ", ada " . $this->typeLabel . " baru yang di-assign ke Anda: " . $this->target->name;
                $waService->sendMessage($notifiable->whatsapp, $message);
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('WA Notification Error: ' . $e->getMessage());
            }
        }

        return $channels;
    }

    public function toMail($notifiable): MailMessage
    {
        $module = (class_basename($this->target) === 'Lead') ? 'leads' : 'customers';
        
        return (new MailMessage)
            ->subject('Penugasan Baru: ' . $this->target->name)
            ->greeting('Halo ' . $notifiable->name . ',')
            ->line('Anda baru saja menerima penugasan ' . $this->typeLabel . ' baru dari unit ' . ($this->target->account->name ?? 'N/A') . '.')
            ->line('**Rincian Data:**')
            ->line('Nama: ' . $this->target->name)
            ->line('Email: ' . $this->target->email)
            ->line('Phone: ' . ($this->target->phone ?? $this->target->whatsapp ?? '-'))
            ->action('Buka ' . $this->typeLabel . ' di DigoCRM', route($module . '.show', $this->target->id))
            ->line('Mohon segera melakukan tindak lanjut pada data ini.')
            ->line('Terima kasih,')
            ->salutation('DigoCRM System');
    }

    public function toArray($notifiable): array
    {
        return [
            'message' => 'Penugasan ' . $this->typeLabel . ' baru: ' . $this->target->name,
            'id' => $this->target->id,
            'module' => (class_basename($this->target) === 'Lead') ? 'leads' : 'customers',
        ];
    }
}
