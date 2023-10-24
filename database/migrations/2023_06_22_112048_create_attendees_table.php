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
        Schema::create('attendees', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('event_id');

            $table->string('username');
            $table->string('password');

            $table->string('salutation')->nullable();
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->string('email_address');
            $table->string('mobile_number');
            $table->string('landline_number')->nullable();

            $table->string('company_name');
            $table->string('job_title');
            $table->string('country');

            $table->string('pfp')->nullable();
            $table->longText('biography')->nullable();

            $table->string('badge_number');
            $table->string('pass_type');
            $table->string('registration_type')->default('Delegate');

            
            $table->dateTime('joined_date_time');

            $table->dateTime('password_changed_date_time')->nullable();
            $table->integer('password_changed_count')->default(0);

            $table->timestamps();
            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attendees');
    }
};
