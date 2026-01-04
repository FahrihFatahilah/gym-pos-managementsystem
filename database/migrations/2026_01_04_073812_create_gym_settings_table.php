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
        Schema::create('gym_settings', function (Blueprint $table) {
            $table->id();
            $table->string('gym_name')->default('Gym & POS System');
            $table->string('gym_logo')->nullable();
            $table->text('gym_address')->nullable();
            $table->string('gym_phone')->nullable();
            $table->string('gym_email')->nullable();
            $table->string('gym_website')->nullable();
            $table->text('gym_description')->nullable();
            $table->string('receipt_footer')->nullable();
            $table->decimal('membership_monthly_price', 10, 2)->default(150000);
            $table->decimal('membership_yearly_price', 10, 2)->default(1500000);
            $table->string('currency', 10)->default('IDR');
            $table->string('timezone', 50)->default('Asia/Jakarta');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gym_settings');
    }
};
