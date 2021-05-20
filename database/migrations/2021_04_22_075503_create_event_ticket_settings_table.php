<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventTicketSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_ticket_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_ticket_id')->constrained();

            $table->date('sales_start')->default(now());
            $table->date('sales_end')->default(now());

            $table->tinyInteger('status')->default(\App\Models\EventTicketSetting::STATUS_PUBLIC);
            $table->smallInteger('allowed_per_order_min')->default(1);
            $table->smallInteger('allowed_per_order_max')->default(1);
            $table->tinyInteger('sales_channel')->default(\App\Models\EventTicketSetting::SALE_CHANNEL_ONLINE);

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
        Schema::dropIfExists('event_ticket_settings');
    }
}
