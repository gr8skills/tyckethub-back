<?php

namespace App\Http\Controllers\Events;

use App\Http\Controllers\ApiController;
use App\Models\Event;
use App\Models\EventTicket;
use App\Models\EventTicketSetting;
use Carbon\Carbon;
use Illuminate\Http\Request;

class EventTicketController extends ApiController
{

    public function index(Event $event)
    {
        $tickets = $event->tickets()
            ->with(['event.status'])
            ->get();
        return $this->showAll($tickets);
    }

    public function store(Request $request, Event $event)
    {
        $request->validate([
            'title' => ['required'],
            'description' => ['required'],
        ]);
        $quantity = $request->get('quantity');
        if ($quantity === 2000000)
            $quantity_type = 1;
        else
            $quantity_type = 2;

        $ticket = $event->tickets()->create([
            'title' => $request->get('title'),
            'description' => $request->get('description'),
            'quantity_type' => $quantity_type,
            'quantity' => $request->get('quantity'),
            'quantity_remaining' => $request->get('quantity'),
            'type' => $request->get('type'),
            'pricing' => $request->get('pricing'),
            'price' => $request->get('price')
        ]);

        if (!$ticket) {
            return $this->errorResponse('Error saving ticket. Please try again');
        }

        if ($request->has('ticket_setting')) {
            $data = (array)$request->get('ticket_setting');

            $setting = $ticket->setting()->create([
                'sales_start' => Carbon::parse($data['sales_start']),
                'sales_end' => Carbon::parse($data['sales_end']),
                'status' => $data['status'],
                'allowed_per_order_min' => $data['allowed_per_order_min'],
                'allowed_per_order_max' => $data['allowed_per_order_max'],
                'sales_channel' => $data['sales_channel']
            ]);
        }

        return $this->showOne($ticket);
    }

    public function show(Event $event, EventTicket $ticket)
    {
        $this->verifyTicketBelongsToEvent($event, $ticket);

        return $this->showOne($ticket);
    }

    public function update(Request $request, Event $event, EventTicket $ticket)
    {
        $this->verifyTicketBelongsToEvent($event, $ticket);

        $ticket->fill($request->all());
        if (!$ticket->isDirty()) {
            $ticket->save();
        }

        //Check if request has ticket setting payload
        if ($request->has('ticket_setting')) {
            $setting = new EventTicketSetting();
            $data = (array)$request->get('ticket_setting');

            //Check if ticket has settings entry and update
            if (!$ticket->setting()->exists()) {

                $setting = $ticket->setting()->create([
                    'sales_start' => $data['sales_start'],
                    'sales_end' => $data['sales_end'],
                    'status' => $data['status'],
                    'allowed_per_order_min' => $data['allowed_per_order_min'],
                    'allowed_per_order_max' => $data['allowed_per_order_max'],
                    'sales_channel' => $data['sales_channel']
                ]);
            } else {
                $setting = $ticket->setting;
                $setting->fill($data);
                if ($setting->isDirty()) {
                    $setting->save();
                }
            }
        }

        return $this->showOne($ticket);
    }

    public function destroy(Event $event, EventTicket $ticket)
    {
        $this->verifyTicketBelongsToEvent($event, $ticket);
        try {
            $ticket->delete();
        } catch (\Exception $e) {
            return $this->errorResponse('Error deleting ticket. ' . $e->getMessage());
        }

        return $this->showOne($ticket);
    }

    private function verifyTicketBelongsToEvent($event, $ticket)
    {
        if ($ticket->event_id !== $event->id) {
            return $this->errorResponse('Ticket does not belong to the specified event');
        }
    }
}
