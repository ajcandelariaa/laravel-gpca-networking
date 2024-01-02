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
        Schema::create('attendee_interests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('event_id');
            $table->unsignedBigInteger('attendee_id');

            $table->boolean('technology')->default(0);
            $table->boolean('innovation')->default(0);
            $table->boolean('leadership')->default(0);
            $table->boolean('sustainability')->default(0);
            $table->boolean('startups')->default(0);
            $table->boolean('digital_transformation')->default(0);
            $table->boolean('ceo')->default(0);
            $table->boolean('developer')->default(0);
            $table->boolean('designer')->default(0);
            $table->boolean('marketing')->default(0);
            $table->boolean('engineer')->default(0);

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
        Schema::dropIfExists('attendee_interests');
    }
};
