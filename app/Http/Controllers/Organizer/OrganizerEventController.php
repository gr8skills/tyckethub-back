<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\ApiController;
use App\Models\Event;
use App\Models\Organizer;
use App\Models\User;

//use Illuminate\Http\Request;

class OrganizerEventController extends ApiController
{
    public function index(User $organizer)
    {
        $role = $organizer->roles()->get();
        if ($role == (1 || 2)){
            $events = Event::orderBy('id', 'DESC')
                ->with(['tickets.allAttendees'])
                ->get();
        }else{
            $events = $organizer->events()->with(['tickets.allAttendees'])->get();
//            $events = $organizer->events()->with(['tickets', 'artistes', 'location', 'status', 'attendees'])->get();
        }

        return $this->showAll($events);
    }
}
