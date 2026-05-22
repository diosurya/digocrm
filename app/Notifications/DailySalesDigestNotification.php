<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Collection;

class DailySalesDigestNotification extends Notification
{
    use Queueable;

    protected $hotList;      // Due today or overdue
    protected $staleLeads;   // No activity for 3+ days
    protected $newLeads;     // Not yet touched (24h)

    public function __construct(Collection $hotList, Collection $staleLeads, Collection $newLeads)
    {
        $this->hotList = $hotList;
        $this->staleLeads = $staleLeads;
        $this->newLeads = $newLeads;
    }

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        $mail = (new MailMessage)
            ->subject('Daily Sales Digest: Your Lead Priorities Today')
            ->greeting('Halo ' . $notifiable->name . ',')
            ->line('Berikut adalah rangkuman prioritas prospek Anda untuk hari ini agar performa penjualan tetap terjaga.');

        if ($this->hotList->count() > 0) {
            $mail->line('**PRIORITAS HARI INI (HOT LIST)**');
            foreach ($this->hotList as $lead) {
                $mail->line("- **{$lead->name}** ({$lead->company_name})");
            }
        }

        if ($this->staleLeads->count() > 0) {
            $mail->line('**PERLU PERHATIAN (STAGNANT)**');
            foreach ($this->staleLeads as $lead) {
                $mail->line("- **{$lead->name}**");
            }
        }

        if ($this->newLeads->count() > 0) {
            $mail->line('**PROSPEK BARU (UNTOUCHED)**');
            foreach ($this->newLeads as $lead) {
                $mail->line("- **{$lead->name}**");
            }
        }

        return $mail->action('Buka Pipeline Saya', route('leads.index'))
            ->line('Mari tutup lebih banyak penjualan hari ini!')
            ->salutation('DigoCRM Automation System');
    }

    public function toArray($notifiable): array
    {
        return [
            'title' => 'Daily Sales Digest',
            'message' => 'Rangkuman aktivitas harian untuk Anda hari ini.',
            'module' => 'leads',
            'digest' => [
                'hot' => $this->hotList->map(fn($l) => ['name' => $l->name, 'id' => $l->id])->values()->toArray(),
                'stale' => $this->staleLeads->map(fn($l) => ['name' => $l->name, 'id' => $l->id])->values()->toArray(),
                'new' => $this->newLeads->map(fn($l) => ['name' => $l->name, 'id' => $l->id])->values()->toArray(),
            ],
            'total_count' => $this->hotList->count() + $this->staleLeads->count() + $this->newLeads->count()
        ];
    }
}
