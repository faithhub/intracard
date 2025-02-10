<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('wallet_transactions', function (Blueprint $table) {
            $table->unsignedBigInteger('bill_id')->nullable()->after('wallet_id'); // Add the bill_id column
            $table->foreign('bill_id')->references('id')->on('bills')->onDelete('cascade'); // Foreign key constraint
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('wallet_transactions', function (Blueprint $table) {
            $table->dropForeign(['bill_id']); // Drop the foreign key
            $table->dropColumn('bill_id'); // Remove the bill_id column
        });
    }
};
