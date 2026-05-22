<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Auditable;

class Customer extends Model
{
    /** @use HasFactory<\Database\Factories\CustomerFactory> */
    use HasFactory, HasUuids, SoftDeletes, Auditable;

    protected $fillable = [
        'account_id',
        'user_id',
        'customer_code',
        'name',
        'company_name',
        'contact_person',
        'job_title',
        'type',
        'status',
        'whatsapp',
        'alt_phone',
        'email',
        'location',
        'address', // Assuming address exists from previous migrations
        'province',
        'country',
        'postal_code',
        'source',
        'source_reference',
        'follow_up_date',
        'last_contact_date',
        'next_action',
        'priority',
        'erp_customer_id',
        'api_sync_status',
        'payment_term',
        'currency',
        'tax_type',
        'npwp',
        'important_chat',
    ];

    protected $casts = [
        'follow_up_date' => 'datetime',
        'last_contact_date' => 'datetime',
    ];

    public function account(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all activities related to this customer.
     */
    public function activities(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany(Activity::class, 'activitable');
    }

    public function orders(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Order::class);
    }
}
