<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVouchersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vouchers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->text('desc')->nullable();
            $table->datetime('valid_from')->nullable();
            $table->datetime('valid_to')->nullable();
            $table->boolean('is_percentage')->default(true);
            $table->boolean('is_unique_customer')->default(true);
            $table->boolean('is_count_limit')->default(true);
            $table->boolean('is_active')->default(true);
            $table->integer('count_limit')->nullable();
            $table->decimal('value', 15, 2)->default(0.00);
            $table->timestamps();

            $table->bigInteger('created_by')->index();
            $table->bigInteger('updated_by')->nullable()->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vouchers');
    }
}
