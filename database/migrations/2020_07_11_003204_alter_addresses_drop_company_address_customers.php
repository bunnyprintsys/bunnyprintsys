<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterAddressesDropCompanyAddressCustomers extends Migration
{
    // AddPostcodeAddressStateIdCustomers
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn('company_address');
        });
        Schema::table('addresses', function (Blueprint $table) {
            $table->dropColumn('state');
            $table->dropColumn('full_address');
            $table->integer('state_id')->nullable();
            $table->integer('country_id')->nullable();
            $table->morphs('typeable');
        });
        Schema::table('profiles', function (Blueprint $table) {
            $table->dropColumn('address');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}
