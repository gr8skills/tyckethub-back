<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOnlinePlatformExtrasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('online_platform_extras', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_location_id')->constrained();

            $table->text('text')->nullable();
            $table->string('image')->nullable();
            $table->string('video_url')->nullable();
            $table->string('link_title')->nullable();
            $table->string('link_url')->nullable();

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
        Schema::dropIfExists('online_platform_extras');
    }
}
