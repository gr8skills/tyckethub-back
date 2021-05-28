<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\ApiController;
use App\Models\Organizer;
use App\Models\User;

//use Illuminate\Http\Request;

class OrganizerEventController extends ApiController
{
    public function index(User $organizer)
    {
        $events = $organizer->events()->with(['tickets', 'artistes', 'location', 'status'])->get();

        return $this->showAll($events);
    }
}
