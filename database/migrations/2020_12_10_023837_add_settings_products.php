<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSettingsProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->boolean('is_material')->default(false);
            $table->boolean('is_shape')->default(false);
            $table->boolean('is_lamination')->default(false);
            $table->boolean('is_frame')->default(false);
            $table->boolean('is_finishing')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('is_material');
            $table->dropColumn('is_shape');
            $table->dropColumn('is_lamination');
            $table->dropColumn('is_frame');
            $table->dropColumn('is_finishing');
        });
    }
}
