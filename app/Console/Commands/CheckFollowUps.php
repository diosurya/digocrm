<?php

namespace App\Console\Commands;

use App\Models\Lead;
use App\Models\User;
use App\Models\Activity;
use App\Models\NotificationLog;
use App\Notifications\DailySalesDigestNotification;
use App\Notifications\EscalationNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CheckFollowUps extends Command
{
    protected $signature = 'crm:check-followups';
    protected $description = 'Odoo Style: Sales Velocity & Lead Hygiene check with Daily Digest';

    public function handle()
    {
        $this->info("🚀 [DigoCRM] Starting Sales Hygiene Check...");

        $users = User::whereIn('role', ['marketing', 'manager_marketing'])->get();

        foreach ($users as $user) {
            $this->processUserDigest($user);
        }

        $this->info("✅ Sales Hygiene Check Completed.");

        return Command::SUCCESS;
    }

    /**
     * Process all leads for a specific user and send a Daily Digest.
     */
    protected function processUserDigest(User $user)
    {
        $activeLeads = Lead::where('user_id', $user->id)
            ->whereNotIn('status', ['WON', 'LOST', 'CONVERTED'])
            ->get();

        if ($activeLeads->isEmpty()) return;

        // 1. HOT LIST (Due today or overdue)
        $hotList = $activeLeads->filter(function($lead) {
            return $lead->next_followup_at && $lead->next_followup_at->isPast();
        });

        // 2. ATTENTION NEEDED (STALE: No activity for >= 3 days)
        // Check "When did you last touch this data?"
        $staleLeads = $activeLeads->filter(function($lead) {
            $lastTouch = $lead->last_activity_at ?: $lead->created_at;
            return $lastTouch->diffInDays(now()) >= 3;
        });

        // 3. NEW LEADS (Untouched for > 24h)
        $newLeads = $activeLeads->filter(function($lead) {
            return $lead->status === 'NEW' && $lead->created_at->diffInHours(now()) >= 24;
        });

        // 4. MANAGER ESCALATION (Aging & Stagnation rules)
        $this->handleEscalations($user, $activeLeads);

        // Send Digest Email (1 Summary Email instead of 20)
        if ($hotList->isNotEmpty() || $staleLeads->isNotEmpty() || $newLeads->isNotEmpty()) {
            $user->notify(new DailySalesDigestNotification($hotList, $staleLeads, $newLeads));
            $this->info("📧 Daily Digest sent to: {$user->email}");
        }
    }

    /**
     * Handle aging leads that need manager attention.
     */
    protected function handleEscalations(User $user, $leads)
    {
        $overdueForManager = $leads->filter(function($lead) {
            // Rule 1: Critical Stale (7 days no activity)
            $lastTouch = $lead->last_activity_at ?: $lead->created_at;
            $isVeryStale = $lastTouch->diffInDays(now()) >= 7;

            // Rule 2: Critical New (Untouched NEW lead for 3 days)
            $isAbandonedNew = $lead->status === 'NEW' && $lead->created_at->diffInDays(now()) >= 3;

            // Rule 3: Lead Aging (STAGNANT for > 30 days in initial stages)
            // If lead hasn't moved to QUALIFIED/NEGOTIATION/WON within 30 days
            $isStagnant = in_array($lead->status, ['NEW', 'CONTACTED', 'FOLLOW_UP']) 
                          && $lead->created_at->diffInDays(now()) >= 30;
            
            return $isVeryStale || $isAbandonedNew || $isStagnant;
        });

        if ($overdueForManager->isNotEmpty()) {
            $manager = ($user->role === 'manager_marketing' || $user->role === 'superadmin') 
                       ? $user 
                       : $user->manager;
            
            if ($manager) {
                foreach ($overdueForManager as $lead) {
                    // Prevent double escalation within 24h if already logged
                    $alreadyEscalatedToday = Activity::where('activitable_id', $lead->id)
                        ->where('activity_type', 'NOTE')
                        ->where('result', 'like', '%SYSTEM ESCALATION%')
                        ->whereDate('created_at', today())
                        ->exists();

                    if (!$alreadyEscalatedToday) {
                        $this->warn("⚠️ [AGING] Escalating Lead {$lead->lead_code} to Manager: {$manager->name}");
                        
                        $lead->activities()->create([
                            'user_id' => $user->id,
                            'activity_type' => 'NOTE',
                            'result' => 'SYSTEM ESCALATION (Odoo Protocol): Lead identified as STAGNANT (>30 days) or CRITICAL STALE (>7 days).',
                            'outcome' => 'Escalated to Management',
                            'followup_date' => now(),
                        ]);

                        $manager->notify(new EscalationNotification($lead, $user));
                    }
                }
            }
        }
    }
}
