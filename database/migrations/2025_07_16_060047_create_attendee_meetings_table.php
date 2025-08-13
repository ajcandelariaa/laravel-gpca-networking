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
        Schema::create('attendee_meetings', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('event_id');
            $table->unsignedBigInteger('attendee_id');
            $table->unsignedBigInteger('receiver_id');
            $table->string('receiver_type');

            $table->string('meeting_status');
            $table->string('meeting_title');
            $table->date('meeting_date');
            $table->time('meeting_start_time');
            $table->time('meeting_end_time');
            $table->string('meeting_location');
            $table->text('meeting_notes')->nullable();

            $table->dateTime('accepted_datetime')->nullable();
            $table->dateTime('declined_datetime')->nullable();
            $table->dateTime('cancelled_datetime')->nullable();
            $table->dateTime('expired_datetime')->nullable();

            $table->text('accepted_reason')->nullable();
            $table->text('declined_reason')->nullable();
            $table->text('cancelled_reason')->nullable();

            $table->boolean('is_reschedule')->default(false);
            $table->unsignedBigInteger('parent_meeting_id')->nullable();

             // For non-attendees only
            $table->string('respond_token')->nullable();
            $table->dateTime('respond_token_expires_at')->nullable();
            $table->string('respond_token_status')->nullable();

            $table->timestamps();

            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
            $table->foreign('attendee_id')->references('id')->on('attendees')->onDelete('cascade');
            $table->foreign('parent_meeting_id')->references('id')->on('attendee_meetings')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attendee_meetings');
    }
};
