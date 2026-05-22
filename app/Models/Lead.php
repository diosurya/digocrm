<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Lead extends Model
{
    use HasUuids, SoftDeletes, Auditable;

    protected $fillable = [
        'lead_code',
        'account_id',
        'user_id',
        'name',
        'company_name',
        'job_title',
        'industry',
        'city',
        'email',
        'phone',
        'status',
        'source',
        'source_reference',
        'product',
        'qualification',
        'estimated_budget',
        'estimated_deal_value',
        'customer_needs',
        'reminder_enabled',
        'reminder_interval',
        'next_followup_at',
        'last_activity_at',
        'status_updated_at',
        'notes',
    ];

    protected $casts = [
        'next_followup_at' => 'datetime',
        'last_activity_at' => 'datetime',
        'status_updated_at' => 'datetime',
        'estimated_budget' => 'decimal:2',
        'estimated_deal_value' => 'decimal:2',
        'reminder_enabled' => 'boolean',
    ];

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all follow-up tasks related to this lead.
     */
    public function tasks(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Task::class);
    }

    /**
     * Get all activities related to this lead.
     */
    public function activities(): MorphMany
    {
        return $this->morphMany(Activity::class, 'activitable');
    }
}
