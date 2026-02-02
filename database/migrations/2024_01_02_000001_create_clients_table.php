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
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('Client/Website name');
            $table->string('domain')->unique()->comment('Client website domain');
            $table->string('api_key')->unique()->comment('API key for authentication');
            $table->string('return_url')->comment('URL to redirect after payment');
            $table->string('webhook_url')->nullable()->comment('URL for payment notifications');
            $table->string('webhook_secret')->nullable()->comment('Secret for webhook signature');
            $table->boolean('is_active')->default(true);
            $table->json('settings')->nullable()->comment('Client-specific settings');
            $table->timestamps();

            $table->index('api_key');
            $table->index('is_active');
        });

        // Add client_id to transactions table
        Schema::table('transactions', function (Blueprint $table) {
            $table->foreignId('client_id')->nullable()->after('id')->constrained()->nullOnDelete();
            $table->index('client_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign(['client_id']);
            $table->dropColumn('client_id');
        });

        Schema::dropIfExists('clients');
    }
};
