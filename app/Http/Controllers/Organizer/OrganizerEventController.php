<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\ApiController;
use App\Models\Event;
use App\Models\Movie;
use App\Models\Organizer;
use App\Models\User;

//use Illuminate\Http\Request;

class OrganizerEventController extends ApiController
{
    public function index(User $organizer)
    {
        $role = $organizer->roles()->get();
        if ($role[0]->id === 1){
            $events = Event::orderBy('id', 'DESC')
                ->with(['tickets.allAttendees'])
                ->get();
        }else if($role[0]->id === 2){
            $events = Event::orderBy('id', 'DESC')
                ->with(['tickets.allAttendees'])
                ->get();
        } else{
            $events = $organizer->events()->with(['tickets.allAttendees'])->get();
//            $events = $organizer->events()->with(['tickets', 'artistes', 'location', 'status', 'attendees'])->get();
        }

        return $this->showAll($events);
    }

    public function movies(User $organizer)
    {
        $organizer = 1;
        $organizer = User::find($organizer);
        $role = $organizer->roles()->get();
        if ($role[0]->id === 1){
            $movies = Movie::orderBy('id', 'DESC')
                ->with(['tickets.allAttendees'])
                ->get();
        }else if($role[0]->id === 2){
            $movies = Movie::orderBy('id', 'DESC')
                ->with(['tickets.allAttendees'])
                ->get();
        } else{
            $movies = $organizer->movies()->with(['tickets.allAttendees'])->get();
//            $movies = $organizer->movies()->with(['tickets', 'artistes', 'location', 'status', 'attendees'])->get();
        }

        return $this->showAll($movies);
    }
}
