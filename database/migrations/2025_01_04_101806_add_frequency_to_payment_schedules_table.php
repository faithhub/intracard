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
        Schema::table('payment_schedules', function (Blueprint $table) {
            $table->enum('frequency', ['monthly', 'bi-weekly'])->default('monthly')->after('amount');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payment_schedules', function (Blueprint $table) {
            $table->dropColumn('frequency');
        });
    }
};
