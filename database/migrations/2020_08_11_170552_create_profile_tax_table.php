<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfileTaxTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profile_tax', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('profile_id');
            $table->bigInteger('tax_id');
            $table->timestamps();

            $table->index(['profile_id', 'tax_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('profile_tax');
    }
}
