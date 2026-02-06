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
        Schema::table('transactions', function (Blueprint $table) {
            // Drop foreign keys first
            $table->dropForeign(['client_id']);
            $table->dropForeign(['user_id']);
            
            // Drop indexes
            $table->dropIndex(['client_id']);
            $table->dropIndex(['user_id']);
            
            // Drop columns
            $table->dropColumn(['client_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->foreignId('client_id')->nullable()->after('id')->constrained()->nullOnDelete();
            $table->foreignId('user_id')->nullable()->after('payment_state')->constrained()->nullOnDelete();
            
            $table->index('client_id');
            $table->index('user_id');
        });
    }
};
