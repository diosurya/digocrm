<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Enhance Leads Table
        Schema::table('leads', function (Blueprint $table) {
            $table->string('lead_code')->unique()->nullable()->after('id');
            $table->string('company_name')->nullable()->after('name');
            $table->string('job_title')->nullable()->after('company_name');
            $table->string('industry')->nullable()->after('job_title');
            $table->string('city')->nullable()->after('industry');
            $table->string('qualification')->nullable(); // Cold, Warm, Hot
            $table->decimal('estimated_budget', 15, 2)->default(0);
            $table->decimal('estimated_deal_value', 15, 2)->default(0);
            $table->text('customer_needs')->nullable();
            $table->boolean('reminder_enabled')->default(true);
            $table->integer('reminder_interval')->default(1); // days
            
            // Pipeline Status override
            // new, contacted, qualified, proposal, negotiation, won, lost
            $table->string('status')->default('new')->change(); 
        });

        // 2. Enhance Orders (Sales Order) Table
        Schema::table('orders', function (Blueprint $table) {
            $table->decimal('discount_amount', 15, 2)->default(0)->after('total_amount');
            $table->decimal('tax_amount', 15, 2)->default(0)->after('discount_amount');
            $table->decimal('dp_amount', 15, 2)->default(0)->after('tax_amount');
            $table->decimal('remaining_balance', 15, 2)->default(0)->after('dp_amount');
            $table->string('invoice_number')->nullable()->after('order_number');
            $table->string('purchasing_reference')->nullable(); // Reference for Purchasing API
            $table->string('erp_sync_status')->default('pending'); // pending, synced, failed
        });

        // 3. Rename and Generalize Activities
        Schema::rename('lead_activities', 'crm_activities');
        Schema::table('crm_activities', function (Blueprint $table) {
            $table->uuid('activitable_id')->nullable()->after('id');
            $table->string('activitable_type')->nullable()->after('activitable_id');
            $table->string('outcome')->nullable()->after('result');
            $table->string('reminder_channel')->default('email');
            $table->string('attachment_path')->nullable();
            
            // Drop specific lead_id after migration logic (manual later)
            // $table->dropForeign(['lead_id']);
        });
    }

    public function down(): void
    {
        // Down logic omitted for brevity in development but recommended for prod
    }
};
