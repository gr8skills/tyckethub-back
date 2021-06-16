<?php

namespace App\Http\Controllers\Movies;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\Movie;
use App\Models\MovieTicket;
use App\Models\MovieTicketSetting;
use Carbon\Carbon;
use Illuminate\Http\Request;

class MovieTicketController extends ApiController
{
    public function index(Movie $movie)
    {
        $tickets = $movie->tickets()
            ->with(['movie.status'])
            ->get();
        return $this->showAll($tickets);
    }

    public function store(Request $request, Movie $movie)
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

        $ticket = $movie->tickets()->create([
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

    public function show(Movie $movie, MovieTicket $ticket)
    {
        $this->verifyTicketBelongsToMovie($movie, $ticket);

        return $this->showOne($ticket);
    }

    public function update(Request $request, Movie $movie, MovieTicket $ticket)
    {
        $this->verifyTicketBelongsToMovie($movie, $ticket);

        $ticket->fill($request->all());
        if (!$ticket->isDirty()) {
            $ticket->save();
        }

        //Check if request has ticket setting payload
        if ($request->has('ticket_setting')) {
            $setting = new MovieTicketSetting();
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

    public function destroy(Movie $movie, MovieTicket $ticket)
    {
        $this->verifyTicketBelongsToMovie($movie, $ticket);
        try {
            $ticket->delete();
        } catch (\Exception $e) {
            return $this->errorResponse('Error deleting ticket. ' . $e->getMessage());
        }

        return $this->showOne($ticket);
    }

    private function verifyTicketBelongsToMovie($movie, $ticket)
    {
        if ($ticket->movie_id !== $movie->id) {
            return $this->errorResponse('Ticket does not belong to the specified movie');
        }
    }
}
