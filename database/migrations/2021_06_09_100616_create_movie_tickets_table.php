<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMovieTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('movie_tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('movie_id')->constrained();

            $table->string('title');
            $table->text('description');
            $table->tinyInteger('quantity_type')->default(\App\Models\MovieTicket::QUANTITY_LIMITED);
            $table->bigInteger('quantity')->unsigned()->nullable();
            $table->tinyInteger('type')->default(\App\Models\MovieTicket::TYPE_FREE);
            $table->double('price')->default(0);
            $table->tinyInteger('pricing')->default(\App\Models\MovieTicket::PRICING_FIXED);

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
        Schema::dropIfExists('movie_tickets');
    }
}
