<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSmsLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sms_logs', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('provider', 15);
            $table->string('code', 64);
            $table->string('sender_type')->nullable();
            $table->unsignedBigInteger('sender_id')->nullable();
            $table->string('recipient_type');
            $table->unsignedBigInteger('recipient_id');
            $table->string('triggerable_type', 128)->nullable();
            $table->unsignedInteger('triggerable_id')->nullable();
            $table->string('destination', 255);
            $table->string('subject');
            $table->json('metadata')->nullable();
            $table->boolean('is_success')->default(false);
            $table->text('error')->nullable();
            $table->datetime('sent_at')->nullable();
            $table->unsignedBigInteger('profile_id')->nullable();
            $table->timestamps();

            $table->index('profile_id');
            $table->index(['recipient_type', 'recipient_id'], 'recipient');
            $table->index(['sender_type', 'sender_id'], 'sender');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sms_logs');
    }
}
