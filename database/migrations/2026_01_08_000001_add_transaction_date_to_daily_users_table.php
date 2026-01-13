<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('daily_users', function (Blueprint $table) {
            $table->date('transaction_date')->nullable()->after('payment_method');
        });
    }

    public function down()
    {
        Schema::table('daily_users', function (Blueprint $table) {
            $table->dropColumn('transaction_date');
        });
    }
};