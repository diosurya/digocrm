<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->string('unit')->default('pcs')->after('quantity');
            $table->decimal('discount', 15, 2)->default(0)->after('unit_price');
            $table->decimal('tax', 15, 2)->default(0)->after('discount');
        });

        Schema::table('crm_activities', function (Blueprint $table) {
            // Fill activitable_id from old lead_id
            DB::statement("UPDATE crm_activities SET activitable_id = lead_id, activitable_type = 'App\\\\Models\\\\Lead' WHERE lead_id IS NOT NULL");
        });
    }

    public function down(): void
    {
        // 
    }
};
