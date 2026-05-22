<?php

namespace App\Notifications;

use App\Models\Lead;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class FollowUpReminderNotification extends Notification
{
    use Queueable;

    protected $lead;

    public function __construct(Lead $lead)
    {
        $this->lead = $lead;
    }

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('PENGINGAT: Jadwal Follow-Up Lead - ' . $this->lead->name)
            ->greeting('Halo ' . $notifiable->name . ',')
            ->line('Ini adalah pengingat otomatis bahwa Anda memiliki jadwal follow-up yang jatuh tempo hari ini.')
            ->line('**Rincian Prospek:**')
            ->line('Nama: ' . $this->lead->name)
            ->line('Perusahaan: ' . ($this->lead->company_name ?: '-'))
            ->line('Telepon: ' . ($this->lead->phone ?? '-'))
            ->action('Buka Detail Prospek', route('leads.show', $this->lead->id))
            ->line('Harap segera lakukan tindak lanjut dan perbarui status di CRM.')
            ->salutation('DigoCRM Automation System');
    }

    public function toArray($notifiable): array
    {
        return [
            'message' => 'Reminder Follow-Up: ' . $this->lead->name . ' (' . ($this->lead->company_name ?: 'Personal') . ')',
            'id' => $this->lead->id,
            'module' => 'leads',
        ];
    }
}
