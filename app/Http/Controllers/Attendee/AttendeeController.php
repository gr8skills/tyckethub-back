<?php

namespace App\Http\Controllers\Attendee;

use App\Http\Controllers\ApiController;
use App\Models\Attendee;
use Illuminate\Http\Request;

class AttendeeController extends ApiController
{
    public function index()
    {
        $attendees = Attendee::all();
        return $this->showAll($attendees);
    }

    public function show(Attendee $attendee)
    {
        return $this->showOne($attendee);
    }
}
