<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMultiplierTypeIdMultipliers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('multipliers', function (Blueprint $table) {
            $table->bigInteger('multiplier_type_id')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('multipliers', function (Blueprint $table) {
            $table->dropColumn('multiplier_type_id');
        });
    }
}
