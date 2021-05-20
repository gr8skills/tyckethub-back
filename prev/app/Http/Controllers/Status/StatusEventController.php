<?php

namespace App\Http\Controllers\Status;

use App\Http\Controllers\ApiController;
use App\Models\EventStatus;
use Illuminate\Http\Request;

class StatusEventController extends ApiController
{
    public function index(EventStatus $status)
    {
        $events = $status->events;
        return $this->showAll($events);
    }
}
