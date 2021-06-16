<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterMoviesTableAddUidLinks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasColumn('movies', 'uid')) {
            Schema::table('movies', function (Blueprint $table) {
                $table->string('uid')->nullable()->after('display_end_time');
                $table->string('organizer')->nullable()->after('uid');
                $table->integer('is_completed')->nullable()->after('organizer');
                $table->integer('is_published')->nullable()->after('is_completed');
                $table->boolean('visibility')->default(false)->after('is_published');
                $table->string('schedule_time')->nullable()->after('visibility');
                $table->integer('schedule')->nullable()->after('schedule_time');
                $table->string('organizer_link')->nullable()->after('schedule');
                $table->string('movie_link')->nullable()->after('organizer_link');
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
        if(Schema::hasColumn('movies', 'uid')) {
            Schema::table('movies', function (Blueprint $table) {
                $table->dropColumn('uid');
                $table->dropColumn('organizer');
                $table->dropColumn('is_completed');
                $table->dropColumn('is_published');
                $table->dropColumn('visibility');
                $table->dropColumn('schedule_time');
                $table->dropColumn('schedule');
                $table->dropColumn('organizer_link');
                $table->dropColumn('movie_link');
            });
        }
    }
}
