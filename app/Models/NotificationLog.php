<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationLog extends Model
{
    protected $fillable = [
        'lead_id',
        'user_id',
        'channel',
        'recipient',
        'message',
        'status',
        'error_message',
    ];
}
