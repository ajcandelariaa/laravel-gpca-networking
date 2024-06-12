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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            
            $table->string('category');
            $table->string('full_name');
            $table->string('short_name');
            $table->string('edition');
            $table->string('location');
            $table->text('description_html_text')->nullable();
            $table->string('event_full_link');
            $table->string('event_short_link');
            $table->date('event_start_date');
            $table->date('event_end_date');

            $table->unsignedBigInteger('event_logo_media_id');
            $table->unsignedBigInteger('event_logo_inverted_media_id')->nullable();
            $table->unsignedBigInteger('app_sponsor_logo_media_id')->nullable();

            $table->unsignedBigInteger('event_splash_screen_media_id')->nullable();
            $table->unsignedBigInteger('event_banner_media_id')->nullable();
            $table->unsignedBigInteger('app_sponsor_banner_media_id')->nullable();

            $table->string('about_conference_webview_link')->nullable();
            $table->string('tc_conference_webview_link')->nullable();

            $table->text('login_html_text')->nullable();
            $table->text('continue_as_guest_html_text')->nullable();
            $table->text('forgot_password_html_text')->nullable();

            $table->string('primary_bg_color');
            $table->string('secondary_bg_color');
            $table->string('primary_text_color');
            $table->string('secondary_text_color');

            $table->year('year');

            $table->boolean('is_visible_in_the_app')->default(false);
            $table->boolean('is_accessible_in_the_app')->default(false);

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
        Schema::dropIfExists('events');
    }
};
