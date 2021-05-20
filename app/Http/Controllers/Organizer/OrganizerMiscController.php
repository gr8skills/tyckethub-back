<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\ApiController;
use App\Models\Organizer;
use Illuminate\Http\Request;

class OrganizerMiscController extends ApiController
{
    public function uncompletedEvents(Organizer $organizer)
    {
        $incompleteEvents = $organizer->events()->where('is_completed', 0)->get();
        return $this->showAll($incompleteEvents);
    }
}
