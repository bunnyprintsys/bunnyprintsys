<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBankBindingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bank_bindings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('bankable_type');
            $table->unsignedBigInteger('bankable_id');
            $table->unsignedBigInteger('bank_id')->nullable();
            $table->string('bank_account_holder', 150)->nullable();
            $table->string('bank_account_number', 25)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bank_bindings');
    }
}
