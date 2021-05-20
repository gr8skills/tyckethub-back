<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPublishOptionsColsToEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('events', function (Blueprint $table) {
            $table->tinyInteger('visibility')->after('is_published')->default(\App\Models\Event::VISIBILITY_PUBLIC);
            $table->tinyInteger('schedule')->after('visibility')->default(\App\Models\Event::PUBLISH_OPTION_NOW);
            $table->dateTime('schedule_time')->after('visibility')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn(['visibility', 'schedule', 'schedule_time']);
        });
    }
}
