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
        Schema::table('build_credit_cards', function (Blueprint $table) {
            $table->unsignedBigInteger('card_id')->nullable()->change(); // Make card_id nullable
        });
    }

    public function down()
    {
        Schema::table('build_credit_cards', function (Blueprint $table) {
            $table->unsignedBigInteger('card_id')->nullable(false)->change(); // Revert to non-nullable
        });
    }
};
