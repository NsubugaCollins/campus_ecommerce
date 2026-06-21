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
        // Add subscription fields to users table
        Schema::table('users', function (Blueprint $table) {
            $table->string('subscription_type')->default('none');
            $table->timestamp('subscription_expires_at')->nullable();
        });

        // Create subscription_payments table
        Schema::create('subscription_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('reference')->unique();
            $table->string('phone_number');
            $table->string('provider'); // mtn or airtel
            $table->decimal('amount', 10, 2);
            $table->string('plan'); // daily, weekly, monthly
            $table->string('status')->default('pending'); // pending, completed, failed
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscription_payments');

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['subscription_type', 'subscription_expires_at']);
        });
    }
};
