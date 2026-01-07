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
        Schema::create('packets', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., '1 Sesi', '4 Sesi', etc.
            $table->enum('type', ['individual', 'couple', 'group', 'daily', 'membership']); // Individual, Couple, Group, Daily, Membership
            $table->integer('sessions'); // Number of sessions
            $table->integer('duration_days'); // Duration in days
            $table->decimal('price', 10, 2); // Price
            $table->integer('duration_minutes'); // Duration per session in minutes
            $table->text('description')->nullable(); // Additional description/notes
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('packets');
    }
};
