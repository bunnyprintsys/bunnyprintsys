<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProfileIdMultipleTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->integer('profile_id')->unsigned();
        });
        Schema::table('admins', function (Blueprint $table) {
            $table->integer('profile_id')->unsigned();
        });
        Schema::table('members', function (Blueprint $table) {
            $table->integer('profile_id')->unsigned();
        });
        Schema::table('transactions', function (Blueprint $table) {
            $table->integer('profile_id')->unsigned();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn('profile_id');
        });
        Schema::table('admins', function (Blueprint $table) {
            $table->dropColumn('profile_id');
        });
        Schema::table('members', function (Blueprint $table) {
            $table->dropColumn('profile_id');
        });
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn('profile_id');
        });
    }
}
