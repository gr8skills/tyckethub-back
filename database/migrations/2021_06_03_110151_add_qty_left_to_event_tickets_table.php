<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddQtyLeftToEventTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasColumn('event_tickets', 'quantity_remaining')) {
            Schema::table('event_tickets', function (Blueprint $table) {
                $table->integer('quantity_remaining')->default(0)->after('quantity');
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
        if(Schema::hasColumn('event_tickets', 'quantity_remaining')) {
            Schema::table('event_tickets', function (Blueprint $table) {
                $table->dropColumn('quantity_remaining');
            });
        }
    }
}
