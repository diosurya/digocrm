<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('order_number')->unique();
            $table->foreignIdFor(\App\Models\Customer::class, 'customer_id')->constrained()->onDelete('cascade');
            $table->foreignIdFor(\App\Models\Account::class, 'account_id')->nullable()->constrained()->onDelete('set null');
            $table->enum('status', ['draft', 'pending', 'confirmed', 'processing', 'shipped', 'completed', 'cancelled'])->default('draft');
            $table->decimal('total_amount', 15, 2)->default(0);
            $table->string('currency')->default('IDR');
            $table->text('notes')->nullable();
            $table->date('order_date');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
