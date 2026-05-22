<?php

namespace App\Notifications;

use App\Models\Lead;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EscalationNotification extends Notification
{
    use Queueable;

    protected $lead;
    protected $marketing;

    public function __construct(Lead $lead, User $marketing)
    {
        $this->lead = $lead;
        $this->marketing = $marketing;
    }

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        // If the manager is the marketing person themselves
        if ($notifiable->id === $this->marketing->id) {
            $greeting = 'Peringatan ' . $notifiable->name . ',';
            $message = 'Sistem eskalasi otomatis mendeteksi bahwa Anda memiliki prospek yang sudah terabaikan lebih dari 3 hari.';
        } else {
            $greeting = 'Laporan Eskalasi: ' . $notifiable->name . ',';
            $message = 'Marketing ' . $this->marketing->name . ' belum melakukan follow-up pada prospek di bawah ini selama lebih dari 3 hari.';
        }

        return (new MailMessage)
            ->subject('ESKALASI SISTEM: Lead Terabaikan - ' . $this->lead->name)
            ->greeting($greeting)
            ->line($message)
            ->line('**Rincian Lead:**')
            ->line('Kode: ' . $this->lead->lead_code)
            ->line('Nama Prospek: ' . $this->lead->name)
            ->line('Jatuh Tempo: ' . $this->lead->next_followup_at?->format('d/m/Y H:i'))
            ->action('Evaluasi Lead Ini', route('leads.show', $this->lead->id))
            ->line('Harap segera ambil tindakan operasional untuk menjaga kualitas layanan.')
            ->salutation('DigoCRM System');
    }

    public function toArray($notifiable): array
    {
        return [
            'message' => 'Escalation Alert: Lead ' . $this->lead->name . ' is overdue by 3+ days.',
            'id' => $this->lead->id,
            'module' => 'leads',
        ];
    }
}
