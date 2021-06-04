<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUniqueColumnsToAttendeeTicketPivot extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasColumns('attendee_ticket_pivot', ['used_qty', 'unique_id'])) {
            Schema::table('attendee_ticket_pivot', function (Blueprint $table) {
                $table->integer('used_qty')->default(0)->after('quantity');
                $table->string('unique_id')->after('price');
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
        if(Schema::hasColumns('attendee_ticket_pivot', ['used_qty', 'unique_id'])) {
            Schema::table('attendee_ticket_pivot', function (Blueprint $table) {
                $table->dropColumn('used');
                $table->dropColumn('unique_id');
            });
        }
    }
}
