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
        Schema::create('single_conversation_messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('single_conversation_id');
            $table->unsignedBigInteger('attendee_id');
            $table->text('message')->nullable();
            $table->unsignedBigInteger('file_media_id')->nullable();
            $table->boolean('is_seen')->default(false);
            $table->timestamps();
            $table->foreign('single_conversation_id')->references('id')->on('single_conversations')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('single_conversation_messages');
    }
};
