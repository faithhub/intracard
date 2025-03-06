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
            $table->foreignId('team_id')->nullable()->after('user_id')
                  ->constrained('teams')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('payment_schedules', function (Blueprint $table) {
            $table->dropForeign(['team_id']);
            $table->dropColumn('team_id');
        });
    }
};
