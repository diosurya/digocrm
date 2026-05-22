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
            if (!Schema::hasColumn('crm_activities', 'status')) {
                $table->string('status')->default('OPEN')->after('outcome'); // OPEN, PENDING, DONE, MISSED, CANCELLED
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('crm_activities', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
