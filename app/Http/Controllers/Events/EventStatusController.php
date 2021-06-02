<?php

namespace App\Http\Controllers\Events;

use App\Http\Controllers\ApiController;
use App\Models\Event;
use App\Models\EventStatus;
use Illuminate\Http\Request;

class EventStatusController extends ApiController
{
    public function index(Event $event)
    {
        $status = $event->status;
        return $this->showOne($status);
    }

    public function create(Request $request)
    {
        $eventStatus = EventStatus::create($request->all());
        $status = EventStatus::all();
        if ($eventStatus)
            return $this->showAll($status);
        else
            return ['error', 'Could not create event status'];

    }

    public function destroy($event)
    {
        $event = EventStatus::where('id', $event)->first();
        try {
            $event->delete();
            return $this->showOne($event);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }

    }

    public function approve($id)
    {
        $id = (int)$id;
        $event = Event::where('id', $id)->first();
        try {
            if ($event['is_published'] === 0)
                $event['is_published'] = 1;
            else
                $event['is_published'] = 0;
            $event->save();
        }catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }

    }
}
