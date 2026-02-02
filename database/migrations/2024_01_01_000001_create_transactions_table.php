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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('client_ref')->unique()->comment('Order ID / Client Reference');
            $table->string('reqid')->nullable()->unique()->comment('Paycenter Request ID');
            $table->string('transaction_id')->nullable()->comment('Paycenter Transaction ID');
            
            // Amount details
            $table->decimal('amount', 10, 2)->comment('Payment amount');
            $table->string('currency', 3)->default('LKR');
            
            // Payment status
            $table->string('status')->default('pending')->comment('pending, processing, completed, failed, cancelled');
            $table->string('payment_state')->nullable()->comment('State from Paycenter');
            
            // User/Customer info
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('customer_email')->nullable();
            $table->string('customer_phone')->nullable();
            
            // Request/Response data
            $table->text('description')->nullable();
            $table->json('request_data')->nullable()->comment('Original request payload');
            $table->json('response_data')->nullable()->comment('Response from Paycenter');
            
            // Timestamps
            $table->timestamp('initiated_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            
            // Indexes
            $table->index('user_id');
            $table->index('status');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
