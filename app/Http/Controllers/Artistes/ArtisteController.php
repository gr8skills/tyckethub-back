<?php

namespace App\Http\Controllers\Artistes;

use App\Http\Controllers\ApiController;
use App\Models\Artiste;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class ArtisteController extends ApiController
{
    public function index()
    {
        $artistes = Artiste::with(['images'])->get();
        return $this->showAll($artistes);
    }

    public function store(Request $request)
    {
        $request->validate(['name' => ['required']]);

        $artiste = Artiste::create([
            'name' => $request->input('name')
        ]);
        return $this->showOne($artiste);
    }

    public function show(Artiste $artiste)
    {
        return $this->showOne($artiste);
    }

    public function update(Request $request, Artiste $artiste)
    {
        $artiste->fill(Arr::only($request->all(), ['name']));

        if ($artiste->isDirty()) {
            $artiste->save();
            return $this->showOne($artiste);
        }

        return $this->errorResponse('Artiste not updated. None artiste attribute was modified.');
    }

    public function destroy($artiste)
    {
        $artiste = Artiste::where('id', $artiste)->first();
        try {
            $artiste->delete();
            return $this->showOne($artiste);
        } catch (\Exception $e) {
            return $this->errorResponse('Operation failed. Please try again later.');
        }
    }

    public function create(Request $request)
    {
        $artisteCreate = Artiste::create($request->all());
        $artistes = Artiste::all();
        if ($artisteCreate)
            return $this->showAll($artistes);
        else
            return ['error', 'Could not create artiste'];
    }

}
