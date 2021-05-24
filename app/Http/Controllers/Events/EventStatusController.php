<?php

namespace App\Http\Controllers\Events;

use App\Http\Controllers\ApiController;
use App\Models\Event;
use Illuminate\Http\Request;

class EventStatusController extends ApiController
{
    public function index(Event $event)
    {
        $status = $event->status;
        return $this->showOne($status);
    }
}
