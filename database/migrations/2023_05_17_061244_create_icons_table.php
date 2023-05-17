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
        Schema::create('icons', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('event_id');
            $table->string('category');

            $table->string('icon');
            $table->string('icon_color');
            $table->string('icon_bg_color');

            $table->string('title');
            $table->string('title_color');
            $table->string('title_bg_color');

            $table->integer('sequence');
            $table->string('hidden');

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
        Schema::dropIfExists('icons');
    }
};
