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
        Schema::create('features', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('event_id');

            $table->string('full_name');
            $table->string('short_name')->nullable();
            $table->string('edition')->nullable();

            $table->string('location')->nullable();
            $table->mediumText('description_html_text')->nullable();

            $table->string('link')->nullable();
            $table->date('start_date');
            $table->date('end_date');

            $table->unsignedBigInteger('logo_media_id')->nullable();
            $table->unsignedBigInteger('banner_media_id')->nullable();

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
        Schema::dropIfExists('features');
    }
};
