<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained();

            $table->string('title');
            $table->text('description');
            $table->tinyInteger('quantity_type')->default(\App\Models\EventTicket::QUANTITY_LIMITED);
            $table->bigInteger('quantity')->unsigned()->nullable();
            $table->tinyInteger('type')->default(\App\Models\EventTicket::TYPE_FREE);
            $table->double('price')->default(0);
            $table->tinyInteger('pricing')->default(\App\Models\EventTicket::PRICING_FIXED);

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
        Schema::dropIfExists('event_tickets');
    }
}
