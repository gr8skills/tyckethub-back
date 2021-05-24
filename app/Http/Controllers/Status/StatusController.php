<?php

namespace App\Http\Controllers\Status;

use App\Http\Controllers\ApiController;
use App\Models\EventStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class StatusController extends ApiController
{
    public function index()
    {
        $statuses = EventStatus::all();
        return $this->showAll($statuses);
    }

    public function store(Request $request)
    {
        $validated_data = $request->validate([
            'name' => ['required', 'string'],
        ]);
        $status = EventStatus::create($validated_data);
        return $this->showOne($status);
    }

    public function show(EventStatus $status)
    {
        return $this->showOne($status);
    }

    public function update(Request $request, EventStatus $status)
    {
        $status->fill(Arr::only($request->all(), ['name']));
        if ($status->isDirty('name')) {
            $status->save();
            return $this->showOne($status);
        }
        return $this->errorResponse('Status not updated. None of the event status attribute was modified.');
    }

    public function destroy(EventStatus $status)
    {
        try {
            $status->delete();
            return $this->showOne($status);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }
}
