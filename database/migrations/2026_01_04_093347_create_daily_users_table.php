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
        Schema::create('daily_users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone');
            $table->string('email')->nullable();
            $table->foreignId('personal_trainer_id')->nullable()->constrained()->onDelete('set null');
            $table->text('fitness_goals')->nullable();
            $table->date('visit_date');
            $table->decimal('amount_paid', 10, 2);
            $table->enum('payment_method', ['cash', 'qris', 'transfer']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_users');
    }
};
