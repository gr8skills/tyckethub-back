<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\ApiController;
use App\Models\Organizer;
use App\Models\User;
use Illuminate\Http\Request;

class OrganizerTicketController extends ApiController
{
    public function index(User $organizer)
    {
        $tickets = $organizer->events()
            ->whereHas('tickets')
            ->with(['tickets', 'tickets.event', 'tickets.setting'])
            ->get()
            ->pluck('tickets')
            ->collapse();
        return $this->showAll($tickets);
    }
}
