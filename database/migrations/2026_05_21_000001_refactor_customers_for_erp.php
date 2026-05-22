<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->string('customer_code')->unique()->after('id')->nullable();
            $table->string('company_name')->nullable()->after('name');
            $table->string('contact_person')->nullable()->after('company_name');
            $table->string('status')->default('prospect')->after('contact_person'); // lead, prospect, active, inactive
            $table->string('alt_phone')->nullable()->after('whatsapp');
            // 'address' might already exist from older migrations, but let's ensure it's there or use location. 
            // We will add specific address fields.
            $table->string('province')->nullable()->after('location');
            $table->string('country')->default('Indonesia')->after('province');
            $table->string('postal_code')->nullable()->after('country');
            $table->string('source')->nullable()->after('postal_code');
            $table->dateTime('follow_up_date')->nullable();
            $table->dateTime('last_contact_date')->nullable();
            $table->string('next_action')->nullable();
            $table->string('priority')->default('medium'); // low, medium, high
            $table->string('erp_customer_id')->nullable();
            $table->string('api_sync_status')->default('pending'); // pending, synced, failed
            $table->string('payment_term')->nullable(); // e.g., Net 30, Cash
            $table->string('currency')->default('IDR');
            $table->string('tax_type')->nullable(); // V0, V1, V2 etc or Non-Tax
            $table->string('npwp')->nullable();
        });

        // Generate codes for existing customers
        $customers = DB::table('customers')->get();
        foreach ($customers as $index => $customer) {
            $code = 'CUST-' . date('ym') . str_pad($index + 1, 4, '0', STR_PAD_LEFT);
            DB::table('customers')->where('id', $customer->id)->update(['customer_code' => $code]);
        }
    }

    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn([
                'customer_code', 'company_name', 'contact_person', 'status', 'alt_phone',
                'province', 'country', 'postal_code', 'source', 'follow_up_date', 'last_contact_date',
                'next_action', 'priority', 'erp_customer_id', 'api_sync_status', 'payment_term', 'currency', 'tax_type', 'npwp'
            ]);
        });
    }
};
