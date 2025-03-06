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
        Schema::table('payments', function (Blueprint $table) {
            $table->foreignId('wallet_id')->nullable()->constrained();
            // Add period_key for unique payment identification
            $table->string('period_key')->nullable()->after('is_team_payment');
            
            // Add payment timing tracking
            $table->timestamp('paid_at')->nullable()->after('payment_date');
            $table->string('payment_timing')->nullable()->after('paid_at'); // 'early', 'on_time', 'late'
            $table->integer('days_difference')->nullable()->after('payment_timing');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn('wallet_id');
            $table->dropColumn('period_key');
            $table->dropColumn('paid_at');
            $table->dropColumn('payment_timing');
            $table->dropColumn('days_difference');
        });
    }
};
