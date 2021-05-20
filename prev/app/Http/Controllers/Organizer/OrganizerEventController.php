<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\ApiController;
use App\Models\Organizer;
//use Illuminate\Http\Request;

class OrganizerEventController extends ApiController
{
    public function index(Organizer $organizer)
    {
        $events = $organizer->events()->with(['tickets', 'artistes', 'location', 'status'])->get();

        return $this->showAll($events);
    }
}
