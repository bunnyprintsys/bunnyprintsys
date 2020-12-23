<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMultiplierIdsProductFrames extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_frames', function (Blueprint $table) {
            $table->decimal('multiplier', 5, 2)->nullable();

            $table->integer('product_id');
            $table->integer('frame_id');
            $table->index(['product_id', 'frame_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_frames', function (Blueprint $table) {
            $table->dropColumn('multiplier');
            $table->dropColumn('product_id');
            $table->dropColumn('frame_id');
        });
    }
}
