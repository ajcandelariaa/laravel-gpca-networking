<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attendee_otp_codes', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('event_id');
            $table->unsignedBigInteger('attendee_id');
            $table->string('purpose')->default('activation'); // activation / password_reset (future use)
            $table->string('code_hash');
            $table->dateTime('expires_datetime');
            $table->boolean('is_used')->default(false);
            $table->dateTime('used_datetime')->nullable();
            $table->unsignedSmallInteger('attempts')->default(0);

            $table->timestamps();

            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
            $table->foreign('attendee_id')->references('id')->on('attendees')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attendee_otp_codes');
    }
};
