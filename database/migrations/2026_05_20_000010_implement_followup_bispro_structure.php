<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->string('phone')->nullable()->after('email');
            $table->string('product')->nullable()->after('source');
            $table->dateTime('next_followup_at')->nullable()->after('status');
            $table->dateTime('last_contacted_at')->nullable()->after('next_followup_at');
            $table->text('notes')->nullable()->after('last_contacted_at');
        });

        Schema::create('lead_activities', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignIdFor(\App\Models\Lead::class, 'lead_id')->constrained()->onDelete('cascade');
            $table->foreignIdFor(\App\Models\User::class, 'user_id')->constrained()->onDelete('cascade');
            $table->string('activity_type'); // call, email, whatsapp, meeting, note
            $table->text('result');
            $table->string('previous_status')->nullable();
            $table->string('new_status')->nullable();
            $table->dateTime('followup_date');
            $table->timestamps();
        });

        Schema::create('notification_logs', function (Blueprint $table) {
            $table->id();
            $table->uuid('lead_id')->nullable();
            $table->uuid('user_id')->nullable();
            $table->string('channel'); // email, whatsapp
            $table->string('recipient');
            $table->text('message');
            $table->enum('status', ['pending', 'sent', 'failed'])->default('pending');
            $table->text('error_message')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropColumn(['phone', 'product', 'next_followup_at', 'last_contacted_at', 'notes']);
        });
        Schema::dropIfExists('lead_activities');
        Schema::dropIfExists('notification_logs');
    }
};
