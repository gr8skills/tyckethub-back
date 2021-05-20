<?php

namespace App\Http\Controllers\Events;

use App\Http\Controllers\ApiController;
use App\Models\Event;
use Illuminate\Http\Request;

class EventLocationController extends ApiController
{
    public function index(Event $event)
    {
        $location = $event->location;
        return $this->showOne($location);
    }
}
