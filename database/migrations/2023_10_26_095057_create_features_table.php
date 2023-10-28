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
            $table->string('name');
            $table->string('short_name');
            $table->string('tagline')->nullable();
            $table->string('location');
            $table->longText('short_description')->nullable();
            $table->longText('long_description')->nullable();
            $table->string('link');
            $table->date('start_date');
            $table->date('end_date');
            $table->string('logo')->nullable();
            $table->string('banner')->nullable();
            $table->string('image')->nullable();
            $table->boolean('active')->default(true);
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
