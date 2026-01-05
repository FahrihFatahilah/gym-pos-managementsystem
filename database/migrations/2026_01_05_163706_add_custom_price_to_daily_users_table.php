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
        Schema::table('daily_users', function (Blueprint $table) {
            $table->boolean('is_custom_price')->default(false)->after('amount_paid');
            $table->decimal('custom_price', 10, 2)->nullable()->after('is_custom_price');
            $table->date('valid_until')->nullable()->after('visit_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('daily_users', function (Blueprint $table) {
            $table->dropColumn(['is_custom_price', 'custom_price', 'valid_until']);
        });
    }
};
