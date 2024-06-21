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
        Schema::table('features', function (Blueprint $table) {
            $table->string('primary_bg_color');
            $table->string('secondary_bg_color');
            $table->string('primary_text_color');
            $table->string('secondary_text_color');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('features', function (Blueprint $table) {
            $table->dropColumn('primary_bg_color');
            $table->dropColumn('secondary_bg_color');
            $table->dropColumn('primary_text_color');
            $table->dropColumn('secondary_text_color');
        });
    }
};
