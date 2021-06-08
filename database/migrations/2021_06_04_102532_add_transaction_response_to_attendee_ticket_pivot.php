<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTransactionResponseToAttendeeTicketPivot extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasColumns('attendee_ticket_pivot', ['tr_message', 'tr_reference', 'tr_status', 'tr_transaction'])) {
            Schema::table('attendee_ticket_pivot', function (Blueprint $table) {
                $table->string('tr_message')->nullable();
                $table->string('tr_reference')->nullable();
                $table->string('tr_status')->nullable();
                $table->string('tr_transaction')->nullable();
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
        if(Schema::hasColumns('attendee_ticket_pivot', ['tr_message', 'tr_reference', 'tr_status', 'tr_transaction'])) {
            Schema::table('attendee_ticket_pivot', function (Blueprint $table) {
                $table->dropColumn('tr_message');
                $table->dropColumn('tr_reference');
                $table->dropColumn('tr_status');
                $table->dropColumn('tr_transaction');
            });
        }
    }
}
