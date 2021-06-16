<?php

namespace App\Jobs;

use App\Http\Controllers\MailGunController;
use App\Models\Custom;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Mailgun\Mailgun;
use App\Mail\EventBooked;
use Illuminate\Support\Facades\Mail;

class TicketReceipt implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $attendee;
    public function __construct($attendee)
    {
        //
        $this->attendee = $attendee;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        $this->sendNow();
    }

    public function sendNow(){
        Mail::to($this->attendee['email'])->send(new EventBooked($this->attendee));
    }
}
