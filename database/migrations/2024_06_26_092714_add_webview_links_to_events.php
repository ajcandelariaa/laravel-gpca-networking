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
        Schema::table('events', function (Blueprint $table) {
            $table->string('delegate_feedback_survey_link')->nullable();
            $table->string('app_feedback_survey_link')->nullable();
            $table->string('about_event_link')->nullable();
            $table->string('venue_link')->nullable();
            $table->string('press_releases_link')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn('delegate_feedback_survey_link');
            $table->dropColumn('app_feedback_survey_link');
            $table->dropColumn('about_event_link');
            $table->dropColumn('venue_link');
            $table->dropColumn('press_releases_link');
        });
    }
};
