<?php

namespace App\Http\Controllers\Events;

use App\Http\Controllers\ApiController;
use App\Models\Event;
use Illuminate\Http\Request;

class EventArtisteController extends ApiController
{
    public function index(Event $event)
    {
        $artistes = $event->artistes;
        return $this->showAll($artistes);
    }
}
