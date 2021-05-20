<?php

namespace App\Http\Controllers\Tags;

use App\Http\Controllers\ApiController;
use App\Models\EventTag;
use Illuminate\Http\Request;

class TagEventController extends ApiController
{
    public function index(EventTag $tag)
    {
        $events = $tag->events;
        return $this->showAll($events);
    }
}
