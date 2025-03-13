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
        Schema::create('attendee_contacts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('event_id');
            $table->unsignedBigInteger('attendee_id');
            $table->unsignedBigInteger('contact_attendee_id');
            $table->dateTime('datetime_added');
        
            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
            $table->foreign('attendee_id')->references('id')->on('attendees')->onDelete('cascade');
            $table->foreign('contact_attendee_id')->references('id')->on('attendees')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attendee_contacts');
    }
};
