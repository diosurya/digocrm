<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Activity extends Model
{
    use HasUuids, SoftDeletes, Auditable;

    protected $table = 'crm_activities';

    protected $fillable = [
        'activitable_id',
        'activitable_type',
        'user_id',
        'task_id',
        'activity_type', // CALL, WHATSAPP, EMAIL, MEETING, VISIT, DEMO, NOTE
        'result',
        'outcome',
        'previous_status',
        'new_status',
        'followup_date',
        'status', // OPEN, PENDING, DONE, MISSED, CANCELLED
        'reminder_channel',
        'attachment_path',
    ];

    protected $casts = [
        'followup_date' => 'datetime',
    ];

    /**
     * Get the parent activitable model (Lead or Customer).
     */
    public function activitable(): MorphTo
    {
        return $this->morphTo();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
