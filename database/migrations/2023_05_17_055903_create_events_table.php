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
            $table->string('name');
            $table->string('location');
            $table->longText('description');
            $table->string('event_full_link');
            $table->string('event_short_link');
            $table->date('event_start_date');
            $table->date('event_end_date');

            $table->string('event_logo');
            $table->string('event_logo_inverted');
            $table->string('app_sponsor_logo');

            $table->string('event_splash_screen');
            $table->string('event_banner');
            $table->string('app_sponsor_banner');

            $table->string('year');
            $table->boolean('active');

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
