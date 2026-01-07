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
        Schema::create('p_t_members', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone')->unique();
            $table->string('email')->nullable();
            $table->text('address')->nullable();
            $table->foreignId('personal_trainer_id')->constrained('personal_trainers');
            $table->foreignId('packet_id')->constrained('packets');
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('sessions_remaining');
            $table->integer('total_sessions');
            $table->decimal('amount_paid', 10, 2);
            $table->enum('payment_method', ['cash', 'qris', 'transfer']);
            $table->enum('status', ['active', 'expired', 'completed'])->default('active');
            $table->text('notes')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users'); // Who processed this
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('p_t_members');
    }
};
