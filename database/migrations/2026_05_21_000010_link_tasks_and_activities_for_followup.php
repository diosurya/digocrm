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
        Schema::table('tasks', function (Blueprint $table) {
            // Link Task to Lead (Task acts as a Follow-up Milestone)
            if (!Schema::hasColumn('tasks', 'lead_id')) {
                $table->uuid('lead_id')->nullable()->after('user_id');
            }
            // Milestone type tracking
            if (!Schema::hasColumn('tasks', 'is_milestone')) {
                $table->boolean('is_milestone')->default(false)->after('status');
            }
        });

        Schema::table('crm_activities', function (Blueprint $table) {
            // Link Activity to a specific Task (Activity acts as a Touchpoint within a Task)
            if (!Schema::hasColumn('crm_activities', 'task_id')) {
                $table->uuid('task_id')->nullable()->after('user_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropColumn(['lead_id', 'is_milestone']);
        });

        Schema::table('crm_activities', function (Blueprint $table) {
            $table->dropColumn('task_id');
        });
    }
};
