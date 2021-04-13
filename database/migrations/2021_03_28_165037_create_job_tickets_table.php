<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJobTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_tickets', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code');
            $table->string('doc_no')->nullable();
            $table->datetime('doc_date')->nullable();
            $table->integer('qty')->default(0);
            $table->text('remarks')->nullable();

            $table->bigInteger('customer_id')->nullable();
            $table->bigInteger('product_id')->nullable();
            $table->bigInteger('status_id')->nullable();
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
        Schema::dropIfExists('job_tickets');
    }
}
