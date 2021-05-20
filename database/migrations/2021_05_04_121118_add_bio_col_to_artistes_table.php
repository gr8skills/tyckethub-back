<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBioColToArtistesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('artistes', function (Blueprint $table) {
            $table->longText('bio')->after('name')->nullable();
        });

        if (config('app.env') === 'local') {
            $faker = \Faker\Factory::create();
            \App\Models\Artiste::all()->each(function ($artiste) use ($faker) {
                $artiste->bio = $faker->paragraph(100);
                $artiste->save();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('artistes', function (Blueprint $table) {
            $table->dropColumn('bio');
        });
    }
}
