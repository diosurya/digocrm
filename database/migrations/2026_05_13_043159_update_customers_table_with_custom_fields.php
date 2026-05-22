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
        Schema::table('customers', function (Blueprint $table) {
            // Drop old fields if necessary, or just add new ones. 
            // For a clean slate, let's rename or add the specific ones requested.
            $table->string('whatsapp')->nullable()->after('name');
            $table->string('order')->nullable()->after('email');
            $table->string('location')->nullable()->after('order');
            $table->date('purchase_date')->nullable()->after('location');
            $table->text('important_chat')->nullable()->after('purchase_date');
            
            // Drop fields that are no longer in the requested format
            $table->dropColumn(['phone', 'company', 'address']);
        });
    }

    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn(['whatsapp', 'order', 'location', 'purchase_date', 'important_chat']);
            $table->string('phone')->nullable();
            $table->string('company')->nullable();
            $table->text('address')->nullable();
        });
    }
};
