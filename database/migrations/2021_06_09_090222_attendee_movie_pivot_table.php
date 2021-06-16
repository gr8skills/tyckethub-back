<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AttendeeMoviePivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attendee_movie_pivot', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->foreignId('movie_id');
            $table->tinyInteger('is_favorite')->default(\App\Models\Movie::IS_FAVORITE_FALSE);
            $table->tinyInteger('is_purchased')->default(\App\Models\Movie::IS_PURCHASED_FALSE);
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
        Schema::dropIfExists('attendee_movie_pivot');
    }
}
