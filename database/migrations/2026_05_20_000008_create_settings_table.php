<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('group')->default('general'); // e.g., 'email', 'whatsapp', 'general'
            $table->timestamps();
        });

        // Seed default notification settings
        // Note: In real app, we'd do this in a Seeder
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
