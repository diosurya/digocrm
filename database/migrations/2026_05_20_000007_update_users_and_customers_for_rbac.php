<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Hierarchy: Manager can have many Marketing members
            $table->uuid('parent_id')->nullable()->after('id');
            $table->foreign('parent_id')->references('id')->on('users')->onDelete('set null');

            // Company Assignment: Which company this user belongs to
            $table->uuid('account_id')->nullable()->after('parent_id');
            $table->foreign('account_id')->references('id')->on('accounts')->onDelete('set null');

            // Explicit roles as requested
            // role already added in previous migration, but let's ensure it's robust
            // $table->string('role')->default('marketing')->change(); 
        });

        Schema::table('customers', function (Blueprint $table) {
            // Assignment: Which marketing personnel owns this customer
            $table->uuid('user_id')->nullable()->after('account_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
            $table->dropForeign(['account_id']);
            $table->dropColumn(['parent_id', 'account_id']);
        });

        Schema::table('customers', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};
