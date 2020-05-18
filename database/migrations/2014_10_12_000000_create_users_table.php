<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('phone_number')->nullable();
            $table->string('alt_phone_number')->nullable();
            $table->string('country_code')->nullable();
            $table->integer('id_type')->default(1);
            $table->string('id_value')->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->morphs('typeable');
            $table->integer('status')->default(1);
            $table->integer('creator_id')->unsigned()->nullable()->index();
            $table->integer('updater_id')->unsigned()->nullable()->index();
            $table->integer('profile_id')->unsigned()->nullable()->index();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
