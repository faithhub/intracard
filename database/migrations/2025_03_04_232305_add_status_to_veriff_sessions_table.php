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
        Schema::table('veriff_sessions', function (Blueprint $table) {
            $table->string('status')->nullable()->after('end_user_id'); // Store verification status
            $table->json('webhook_payload')->nullable()->after('status'); // Store full webhook response
        });
    }

    public function down()
    {
        Schema::table('veriff_sessions', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->dropColumn('webhook_payload');
        });
    }
};
