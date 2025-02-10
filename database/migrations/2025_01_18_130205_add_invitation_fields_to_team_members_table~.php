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
        Schema::table('team_members', function (Blueprint $table) {
            $table->string('invitation_token')->nullable();
            $table->timestamp('invitation_expires_at')->nullable();
        });
    }

    public function down()
    {
        Schema::table('team_members', function (Blueprint $table) {
            $table->dropColumn(['invitation_token', 'invitation_expires_at']);
        });
    }
};
