<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // For SQLite, we need to recreate the table with the new enum values
        if (DB::getDriverName() === 'sqlite') {
            // Create temporary table with new structure
            Schema::create('memberships_temp', function (Blueprint $table) {
                $table->id();
                $table->foreignId('member_id')->constrained()->onDelete('cascade');
                $table->enum('type', ['daily', 'monthly', 'yearly', 'custom']);
                $table->date('start_date');
                $table->date('end_date');
                $table->decimal('price', 10, 2);
                $table->enum('status', ['active', 'expired'])->default('active');
                $table->timestamps();
            });
            
            // Copy data from old table to new table
            DB::statement('INSERT INTO memberships_temp SELECT * FROM memberships');
            
            // Drop old table and rename new table
            Schema::dropIfExists('memberships');
            Schema::rename('memberships_temp', 'memberships');
        } else {
            // For other databases, use ALTER TABLE
            DB::statement("ALTER TABLE memberships MODIFY COLUMN type ENUM('daily', 'monthly', 'yearly', 'custom')");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // For SQLite, recreate table without 'daily'
        if (DB::getDriverName() === 'sqlite') {
            Schema::create('memberships_temp', function (Blueprint $table) {
                $table->id();
                $table->foreignId('member_id')->constrained()->onDelete('cascade');
                $table->enum('type', ['monthly', 'yearly', 'custom']);
                $table->date('start_date');
                $table->date('end_date');
                $table->decimal('price', 10, 2);
                $table->enum('status', ['active', 'expired'])->default('active');
                $table->timestamps();
            });
            
            // Copy data (excluding daily types)
            DB::statement("INSERT INTO memberships_temp SELECT * FROM memberships WHERE type != 'daily'");
            
            Schema::dropIfExists('memberships');
            Schema::rename('memberships_temp', 'memberships');
        } else {
            DB::statement("ALTER TABLE memberships MODIFY COLUMN type ENUM('monthly', 'yearly', 'custom')");
        }
    }
};