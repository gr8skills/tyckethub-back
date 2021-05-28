<?php

namespace App\Http\Controllers\Attendee;

use App\Http\Controllers\ApiController;
use App\Models\Attendee;
use App\Models\Event;
use App\Models\EventTicket;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;

class AttendeeEventMiscController extends ApiController
{
    public function toggleEventFavorite(Attendee $attendee, Event $event)
    {
        if ($this->checkAttendeeEventRelationship($attendee, $event)) {
            $pivotEntry = $attendee->events()
                ->where('event_id', $event->id)
                ->first()
                ->pivot;
            $pivotEntry->is_favorite = $pivotEntry->is_favorite === Event::IS_FAVORITE_TRUE
                ? Event::IS_FAVORITE_FALSE
                : Event::IS_FAVORITE_TRUE;
            $pivotEntry->save();

            return $this->showOne($pivotEntry);
        }

        $attendee->events()->attach($event->id, [
            'is_favorite' => Event::IS_FAVORITE_TRUE,
        ]);

        $pivotEntry = $attendee->events()
            ->where('event_id', $event->id)
            ->first()
            ->pivot;

        return $this->showOne($pivotEntry);
    }

    public function purchaseEventTicket(Request $request, User $attendee)
    {
        if ($request->get('ticketsDetails')) {
            $ticketsDetails = $request->get('ticketsDetails');
            foreach ($ticketsDetails as $ticketData) {
                $attendee->tickets()->attach($ticketData['id'], [
                    'quantity' => $ticketData['quantity'],
                    'price' => $ticketData['quantity'] * $ticketData['price']
                ]);
                $event = EventTicket::find($ticketData['id'])
                    ->event()
                    ->first();
                $attendeeEvent = $event
                    ->attendees()
                    ->first();
                if (!$attendeeEvent) {
                    EventTicket::find($ticketData['id'])
                        ->event()
                        ->first()
                        ->attendees()
                        ->attach($attendee->id, ['is_purchased' => Event::IS_PURCHASED_TRUE]);
                } else {
                    $attendeeEvent
                        ->pivot
                        ->update(['is_purchased' => Event::IS_PURCHASED_TRUE]);
                }
            }

            return $this->showMessage('Ticket' . (count($request->get('ticketsDetails')) > 1) ? 's' : '' . ' purchased successfully.');
        }
        return $this->errorResponse('Error processing your payment. Please try again.');
    }

    public function sellEventTicket(Request $request, Attendee $attendee, EventTicket $ticket)
    {
        if (!$this->checkAttendeeTicketRelationship($attendee, $ticket)) {
            return $this->errorResponse('This ticket does not belong to you');
        }

        //Check the quantity from the request and deduct from the attendees purchased ticket
        $data = $request->all();
        $originalTicket = $ticket;
        $ticketId = $originalTicket->id;
//        unset($ticket);

        $ticketPivot = $attendee->tickets()->get();

        return $this->showMessage($ticketPivot);
        if ($data['quantity'] > $originalTicket->pivot->quantity) {
            return $this->errorResponse('Quantity exceeds the available tickets');
        }
        if ($data['amountPerTicket'] > $originalTicket->price) {
            return $this->errorResponse('Price exceeds the original purchase price of the ticket');
        }

        try {
            $originalTicket->pivot->quantity -= $data['quantity'];
            $originalTicket->pivot->save();

            return $this->showMessage('Ticket/s sold successfully.');
        } catch (\Throwable $exception) {
           return $this->errorResponse($exception->getMessage());
        }
    }

    private function checkAttendeeEventRelationship(Attendee $attendee, Event $event)
    {
        return $attendee->events()
                ->get()
                ->filter(function ($evt) use ($event) {
                    return $evt->id === $event->id;
                })
                ->count() > 0;
    }

    private function checkAttendeeTicketRelationship(Attendee $attendee, EventTicket $ticket)
    {
        return $attendee->tickets()
                ->get()
                ->filter(function ($aTicket) use ($ticket) {
                    return $aTicket->id === $ticket->id;
                })
                ->count() > 0;
    }

}
