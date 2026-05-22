<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

use Illuminate\Support\Facades\Schedule;

// Odoo Style: Daily Sales Digest at 07:30 AM
Schedule::command('crm:check-followups')->dailyAt('07:30');
