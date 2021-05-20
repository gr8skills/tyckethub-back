<?php

namespace App\Http\Controllers\EventCategories;

use App\Http\Controllers\ApiController;
use App\Models\AgeRestriction;
use App\Models\EventCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class EventCategoryController extends ApiController
{
    public function index()
    {
        $categories = EventCategory::all();
        return $this->showAll($categories);
    }

    public function store(Request $request)
    {
        $validated_data = $request->validate([
            'name' => ['required', 'string'],
        ]);

        $category = EventCategory::create(Arr::only($request->all(), ['name', 'description']));
        return $this->showOne($category);
    }

    public function show(EventCategory $event_category)
    {
        return $this->showOne($event_category);
    }

    public function update(Request $request, EventCategory $event_category)
    {
        $event_category->fill(Arr::only($request->all(), ['name', 'description']));
        if ($event_category->isDirty()) {
            $event_category->save();
            return $this->showOne($event_category);
        }
        return $this->errorResponse('Event category not updated. None of the event category attribute was modified.');
    }

    public function destroy(EventCategory $event_category)
    {
        try {
            $event_category->delete();
            return $this->showOne($event_category);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }
    public function ageRestrictions(){
        $restrictions = AgeRestriction::all();
        return $this->showAll($restrictions);
    }
}
