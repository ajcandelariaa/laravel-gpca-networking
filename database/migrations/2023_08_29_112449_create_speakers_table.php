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
        Schema::create('speakers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('event_id');
            $table->unsignedBigInteger('feature_id')->nullable();
            $table->unsignedBigInteger('speaker_type_id')->nullable();

            $table->string('salutation')->nullable();
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');

            $table->string('company_name')->nullable();
            $table->string('job_title')->nullable();

            $table->text('biography_html_text')->nullable();

            $table->unsignedBigInteger('pfp_media_id')->nullable();
            $table->unsignedBigInteger('cover_photo_media_id')->nullable();

            $table->string('country')->nullable();
            $table->string('email_address')->nullable();
            $table->string('mobile_number')->nullable();
            
            $table->string('website')->nullable();
            $table->string('facebook')->nullable();
            $table->string('linkedin')->nullable();
            $table->string('twitter')->nullable();
            $table->string('instagram')->nullable();

            $table->boolean('is_active')->default(true);

            $table->dateTime('datetime_added');

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
        Schema::dropIfExists('speakers');
    }
};
