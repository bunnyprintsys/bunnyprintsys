<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddJobRunningInvoiceRunningPrefixesProfiles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('profiles', function (Blueprint $table) {
            $table->bigInteger('job_running_number')->nullable();
            $table->string('job_prefix')->nullable();
            $table->bigInteger('invoice_running_number')->nullable();
            $table->string('invoice_prefix')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('profiles', function (Blueprint $table) {
            $table->dropColumn('job_running_number');
            $table->dropColumn('job_prefix');
            $table->dropColumn('invoice_running_number');
            $table->dropColumn('invoice_prefix');
        });
    }
}
