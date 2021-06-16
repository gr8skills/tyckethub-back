<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMovieLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('movie_locations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('movie_id')->nullable()->constrained();
            $table->foreignId('country_id')->nullable()->constrained();
            $table->foreignId('state_id')->nullable()->constrained();

            $table->string('city_name')->nullable();
            $table->string('venue_address', 255)->nullable();
            $table->tinyInteger('platform')->default(\App\Models\MovieLocation::PLATFORM_LIVE);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('movie_locations');
    }
}
