<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            // Track the heart-beat and lifecycle of the lead
            // last_contacted_at is already added in migration 2026_05_20_000010
            $table->timestamp('last_activity_at')->nullable()->after('next_followup_at');
            $table->timestamp('status_updated_at')->nullable()->after('status');
        });
        
        // Initialize existing data
        \Illuminate\Support\Facades\DB::table('leads')->update([
            'last_activity_at' => \Illuminate\Support\Facades\DB::raw('created_at'),
            'status_updated_at' => \Illuminate\Support\Facades\DB::raw('created_at'),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropColumn(['last_activity_at', 'status_updated_at']);
        });
    }
};
