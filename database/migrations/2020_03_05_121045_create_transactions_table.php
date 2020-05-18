<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->datetime('order_date');
            $table->string('job_id')->nullable();
            $table->string('job')->nullable();
            $table->string('receiver_name')->nullable();
            $table->string('receiver_phone_number')->nullable();
            $table->text('delivery_address')->nullable();
            $table->decimal('cost', 15, 2)->nullable();
            $table->boolean('is_artwork_provided')->default(false);
            $table->boolean('is_design_required')->default(false);
            $table->string('invoice_id')->nullable();
            $table->datetime('dispatch_date')->nullable();
            $table->integer('status')->default(1);
            $table->string('tracking_number')->nullable();
            $table->timestamps();

            $table->bigInteger('customer_id')->index();
            $table->bigInteger('admin_id')->nullable()->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}
