<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Update invalid type values to 'monthly'
        DB::table('memberships')
            ->whereNotIn('type', ['daily', 'monthly', 'yearly', 'custom'])
            ->update(['type' => 'monthly']);
            
        // Now safely modify the enum
        DB::statement("ALTER TABLE memberships MODIFY COLUMN type ENUM('daily', 'monthly', 'yearly', 'custom') NOT NULL");
    }

    public function down()
    {
        DB::statement("ALTER TABLE memberships MODIFY COLUMN type ENUM('monthly', 'yearly', 'custom') NOT NULL");
    }
};