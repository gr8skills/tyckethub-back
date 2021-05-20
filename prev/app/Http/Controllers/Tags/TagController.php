<?php

namespace App\Http\Controllers\Tags;

use App\Http\Controllers\ApiController;
use App\Models\EventTag;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class TagController extends ApiController
{
    public function index()
    {
        $tags = EventTag::all();
        return $this->showAll($tags);
    }

    public function store(Request $request)
    {
        $validated_data = $request->validate([
            'name' => ['required', 'string'],
        ]);

        $tag = EventTag::create($validated_data);
        return $this->showOne($tag);
    }

    public function show(EventTag $tag)
    {
        return $this->showOne($tag);
    }

    public function update(Request $request, EventTag $tag)
    {
        $tag->fill(Arr::only($request->all(), ['name']));
        if ($tag->isDirty('name')) {
            return $this->showOne($tag);
        }
        return $this->errorResponse('Tag not updated. None of the tag attributes were modified.');
    }

    public function destroy(EventTag $tag)
    {
        try {
            $tag->delete();
            return $this->showOne($tag);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }
}
