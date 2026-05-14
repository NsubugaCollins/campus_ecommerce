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
        Schema::create('user_sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('product_name');
            $table->string('category');
            $table->enum('condition', ['New', 'Like New', 'Good', 'Fair']);
            $table->text('description');
            $table->decimal('expected_price', 12, 2);
            $table->decimal('offered_price', 12, 2)->nullable();
            $table->enum('status', ['pending', 'under_review', 'offer_made', 'accepted', 'rejected', 'completed'])->default('pending');
            $table->text('admin_notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_sales');
    }
};
