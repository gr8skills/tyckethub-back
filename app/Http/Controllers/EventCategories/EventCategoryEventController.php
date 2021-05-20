<?php

namespace App\Http\Controllers\EventCategories;

use App\Http\Controllers\ApiController;
use App\Models\EventCategory;
use Illuminate\Http\Request;

class EventCategoryEventController extends ApiController
{
    public function index(EventCategory $event_category)
    {
        $events = $event_category->events;
        return $this->showAll($events);
    }
}
