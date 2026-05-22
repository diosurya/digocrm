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
        Schema::table('crm_activities', function (Blueprint $table) {
            // Make legacy columns nullable so they don't block inserts
            if (Schema::hasColumn('crm_activities', 'lead_id')) {
                $table->uuid('lead_id')->nullable()->change();
            }
            if (Schema::hasColumn('crm_activities', 'customer_id')) {
                $table->uuid('customer_id')->nullable()->change();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('crm_activities', function (Blueprint $table) {
            if (Schema::hasColumn('crm_activities', 'lead_id')) {
                $table->uuid('lead_id')->nullable(false)->change();
            }
            if (Schema::hasColumn('crm_activities', 'customer_id')) {
                $table->uuid('customer_id')->nullable(false)->change();
            }
        });
    }
};
