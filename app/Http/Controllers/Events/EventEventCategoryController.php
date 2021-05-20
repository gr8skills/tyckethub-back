<?php

namespace App\Http\Controllers\Events;

use App\Http\Controllers\ApiController;
use App\Models\Event;
use Illuminate\Http\Request;

class EventEventCategoryController extends ApiController
{
    public function index(Event $event)
    {
        $categories = $event->categories;
        return $this->showAll($categories);
    }
}
