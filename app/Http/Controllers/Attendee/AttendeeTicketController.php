<?php

namespace App\Http\Controllers\Attendee;

use App\Http\Controllers\ApiController;
use App\Models\Attendee;
use Illuminate\Http\Request;

class AttendeeTicketController extends ApiController
{
    public function index(Attendee $attendee)
    {
        $tickets = $attendee->tickets()->with(['event'])->get();
        return $this->showAll($tickets);
    }
}
