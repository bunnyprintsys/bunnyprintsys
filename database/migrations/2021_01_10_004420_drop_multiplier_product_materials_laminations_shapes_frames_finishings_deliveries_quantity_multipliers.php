<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropMultiplierProductMaterialsLaminationsShapesFramesFinishingsDeliveriesQuantityMultipliers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_materials', function (Blueprint $table) {
            $table->dropColumn('multiplier');
        });
        Schema::table('product_laminations', function (Blueprint $table) {
            $table->dropColumn('multiplier');
        });
        Schema::table('product_shapes', function (Blueprint $table) {
            $table->dropColumn('multiplier');
        });
        Schema::table('product_frames', function (Blueprint $table) {
            $table->dropColumn('multiplier');
        });
        Schema::table('product_finishings', function (Blueprint $table) {
            $table->dropColumn('multiplier');
        });
        Schema::table('product_deliveries', function (Blueprint $table) {
            $table->dropColumn('multiplier');
        });
        Schema::table('quantity_multipliers', function (Blueprint $table) {
            $table->dropColumn('multiplier');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
