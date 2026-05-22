<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Auditable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasUuids, SoftDeletes, Auditable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'whatsapp',
        'role',
        'parent_id',
        'account_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the manager (parent) of the user.
     */
    public function manager(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'parent_id');
    }

    /**
     * Get the subordinates (marketing members) under this manager.
     */
    public function subordinates(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(User::class, 'parent_id');
    }

    /**
     * Get the accounts/companies assigned to this user (Many-to-Many).
     */
    public function accounts(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Account::class);
    }

    /**
     * Legacy/Compatibility: Get the primary account of this user.
     */
    public function account(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        // Even though it returns a Collection, using it as a relationship property
        // like $user->account will return the first one if we use an accessor.
        return $this->belongsToMany(Account::class);
    }

    /**
     * Accessor for $user->account (returns the first object or null).
     */
    public function getAccountAttribute()
    {
        return $this->accounts->first();
    }

    /**
     * Get the first account as a helper (legacy support).
     */
    public function getAccountIdAttribute()
    {
        return $this->accounts()->first()?->id;
    }

    /**
     * Get customers owned by this user.
     */
    public function customers(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Customer::class);
    }

    public function activities(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Activity::class);
    }

    // Role Helpers
    public function isSuperadmin(): bool
    {
        return $this->role === 'superadmin';
    }

    public function isManager(): bool
    {
        return $this->role === 'manager_marketing';
    }

    public function isMarketing(): bool
    {
        return $this->role === 'marketing';
    }
}
