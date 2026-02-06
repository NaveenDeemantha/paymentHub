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
            $table->string('student_name')->nullable()->after('customer_phone');
            $table->string('student_id')->nullable()->after('student_name');
            $table->string('program')->nullable()->after('student_id');
            $table->string('nic_passport')->nullable()->after('program');
            $table->string('reference')->nullable()->after('nic_passport');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn(['student_name', 'student_id', 'program', 'nic_passport', 'reference']);
        });
    }
};
