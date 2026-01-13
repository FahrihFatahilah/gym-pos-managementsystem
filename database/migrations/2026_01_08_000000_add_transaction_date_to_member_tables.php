<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('members', function (Blueprint $table) {
            $table->date('transaction_date')->nullable()->after('status');
        });

        Schema::table('p_t_members', function (Blueprint $table) {
            $table->date('transaction_date')->nullable()->after('status');
        });

        Schema::table('memberships', function (Blueprint $table) {
            $table->date('transaction_date')->nullable()->after('status');
        });
    }

    public function down()
    {
        Schema::table('members', function (Blueprint $table) {
            $table->dropColumn('transaction_date');
        });

        Schema::table('p_t_members', function (Blueprint $table) {
            $table->dropColumn('transaction_date');
        });

        Schema::table('memberships', function (Blueprint $table) {
            $table->dropColumn('transaction_date');
        });
    }
};