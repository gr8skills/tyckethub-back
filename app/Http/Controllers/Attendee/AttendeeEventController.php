<?php

namespace App\Http\Controllers\Attendee;

use App\Http\Controllers\ApiController;
use App\Models\Attendee;
use Illuminate\Http\Request;

class AttendeeEventController extends ApiController
{
    public function index(Attendee $attendee)
    {
        $events = $attendee->events;
        return $this->showAll($events);
    }
}
