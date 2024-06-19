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
        Schema::create('sessions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('event_id');
            $table->unsignedBigInteger('feature_id')->nullable();

            $table->date('session_date');
            $table->string('session_day');
            $table->string('session_type')->nullable();
            
            $table->string('title');
            $table->text('description_html_text')->nullable();
            $table->string('start_time');
            $table->string('end_time');
            $table->string('location')->nullable();
            
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
        Schema::dropIfExists('sessions');
    }
};
