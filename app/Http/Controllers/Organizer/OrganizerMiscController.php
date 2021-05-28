<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\ApiController;
use App\Models\Organizer;
use App\Models\User;
use Illuminate\Http\Request;

class OrganizerMiscController extends ApiController
{
    public function uncompletedEvents(User $organizer)
    {
        $incompleteEvents = $organizer->events()->where('is_completed', 0)->get();
        return $this->showAll($incompleteEvents);
    }
}
