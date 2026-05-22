<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            // Link Customer to an Account (B2B capability)
            $table->foreignIdFor(\App\Models\Account::class, 'account_id')->nullable()->after('id')->constrained()->onDelete('set null');
            
            // Add job title for more enterprise contact feel
            $table->string('job_title')->nullable()->after('name');
            
            // Mark as B2B or B2C
            $table->enum('type', ['individual', 'corporate'])->default('individual')->after('account_id');

            // We keep email and whatsapp as they are standard.
            // Old 'order' and 'purchase_date' fields are now redundant due to 'orders' table.
            // But we'll keep them for migration/legacy data or drop them later.
        });
    }

    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropForeign(['account_id']);
            $table->dropColumn(['account_id', 'job_title', 'type']);
        });
    }
};
