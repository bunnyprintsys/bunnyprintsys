<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddShapeLaminationFrameFinishingIdDeals extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('deals', function (Blueprint $table) {
            $table->bigInteger('shape_id')->nullable();
            $table->bigInteger('lamination_id')->nullable();
            $table->bigInteger('frame_id')->nullable();
            $table->bigInteger('finishing_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('deals', function (Blueprint $table) {
            $table->dropColumn('shape_id');
            $table->dropColumn('lamination_id');
            $table->dropColumn('frame_id');
            $table->dropColumn('finishing_id');
        });
    }
}
