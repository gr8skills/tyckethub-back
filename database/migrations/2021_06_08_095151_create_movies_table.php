<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMoviesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('movies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('movie_status_id')->constrained();
            $table->foreignId('genre_id')->constrained();
            $table->string('name');
            $table->mediumText('description')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->string('age_restriction')->nullable();
            $table->tinyInteger('display_start_time')->default(\App\Models\Movie::DISPLAY_TIME_OFF);
            $table->tinyInteger('display_end_time')->default(\App\Models\Movie::DISPLAY_TIME_OFF);
            $table->softDeletes();
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
        Schema::dropIfExists('movies');
    }
}
