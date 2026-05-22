<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });

        // Seed initial roles for the menu to be functional immediately
        DB::table('roles')->insert([
            [
                'name' => 'Super Admin',
                'slug' => 'superadmin',
                'description' => 'Akses penuh ke seluruh fitur dan pengaturan sistem.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Manager Marketing',
                'slug' => 'manager_marketing',
                'description' => 'Mengelola tim marketing dan melihat data gabungan tim.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Marketing',
                'slug' => 'marketing',
                'description' => 'Menginput prospek dan melakukan follow-up harian.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};
