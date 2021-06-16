<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use PDF;

class EventBooked extends Mailable
{
    use Queueable, SerializesModels;

    public $event;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $attendee;
    public function __construct($attendee)
    {
        $this->attendee = $attendee;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $domPdfData = [];
        $domPdfData['attendee_name'] = $this->attendee['name'];
        $domPdfData['event_title'] = $this->attendee['event_title'];
        $domPdfData['start_date'] = $this->attendee['start_date'];
        $domPdfData['start_time'] = $this->attendee['start_time'];
        $domPdfData['ticket_qty'] = $this->attendee['ticket_qty'];
        $domPdfData['unique_id'] = $this->attendee['unique_id'];
        $domPdfData['transaction_ref'] = $this->attendee['transaction_ref'];
        $domPdfData['id_starts'] = $this->attendee['ticket_id_starts'];

        $pdf = PDF::loadView('mails.mailEvent', $domPdfData);
        $file_name = time() . 'ticket.pdf';
        $file_to_save = public_path('/receipts/').$file_name;
        file_put_contents($file_to_save, $pdf->output());
//        $output->move(public_path('receipts/'), $file_name);
        return $this->view('mails.mailEvent')
            ->with([
                'attendee_name' => $this->attendee['name'],
                'event_title' => $this->attendee['event_title'],
                'start_date' => $this->attendee['start_date'],
                'start_time' => $this->attendee['start_time'],
                'ticket_qty' => $this->attendee['ticket_qty'],
                'unique_id' => $this->attendee['unique_id'],
                'transaction_ref' => $this->attendee['transaction_ref'],
                'id_starts' => $this->attendee['ticket_id_starts']
            ])
            ->attach(public_path('/receipts/').$file_name, [
                'as' => 'ticket.pdf',
                'mime' => 'application/pdf',
                'Content-Transfer-Encoding'=> 'binary',
                'Accept-Ranges' => 'bytes'
            ]);
    }
}
