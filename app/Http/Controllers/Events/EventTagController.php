<?php

namespace App\Http\Controllers\Events;

use App\Http\Controllers\ApiController;
use App\Models\Event;
use Illuminate\Http\Request;

class EventTagController extends ApiController
{
    public function index(Event $event)
    {
        $tags = $event->tags;
        return $this->showAll($tags);
    }
}
