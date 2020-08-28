<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaxTransactionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tax_transaction', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('tax_id');
            $table->bigInteger('transaction_id');
            $table->timestamps();

            $table->index(['tax_id', 'transaction_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tax_transaction');
    }
}
