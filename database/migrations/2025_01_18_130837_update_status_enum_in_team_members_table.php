<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        DB::statement("ALTER TABLE team_members MODIFY COLUMN status ENUM('pending', 'accepted', 'declined', 'deactivated', 'suspended') NOT NULL DEFAULT 'pending'");
    }

    public function down()
    {
        DB::statement("ALTER TABLE team_members MODIFY COLUMN status ENUM('pending', 'accepted', 'rejected', 'deactivated', 'suspended') NOT NULL DEFAULT 'pending'");
    }
};
